<?php
// File: updtracking.php (Refactored & Secured)
date_default_timezone_set('Asia/Dhaka');
// inc.php includes db.php and sets up session variables like $userid
include('inc.php');

// --- Input and Validation ---
$task_id = $_POST['id'] ?? 0;

// Redirect if essential data is missing. $userid comes from inc.php (session).
if (empty($task_id) || empty($userid)) {
    header("Location: index.php?error=Invalid_Request");
    exit();
}

$tracking_entry = null;
$is_owner = false;

// 1. Securely fetch the task and verify that the logged-in user owns it.
$stmt_verify = $conn->prepare("SELECT * FROM sttracking WHERE id = ? LIMIT 1");
$stmt_verify->bind_param("i", $task_id);
$stmt_verify->execute();
$result_verify = $stmt_verify->get_result();
if ($result_verify) {
    $tracking_entry = $result_verify->fetch_assoc();
    if ($tracking_entry && $tracking_entry['stid'] == $userid) {
        $is_owner = true;
    }
}
$stmt_verify->close();

// If the user doesn't own this task, or the task doesn't exist, they are not authorized.
if (!$is_owner) {
    header("Location: index.php?error=Unauthorized_Action");
    exit();
}

// Proceed only if ownership is verified and the task hasn't been completed yet.
if ($tracking_entry && $tracking_entry['responsetime'] === null) {
    $cur = date('Y-m-d H:i:s');
    $distance_val = $distance ?? null; // $distance is a global from inc.php

    // 2. Securely update the task as complete.
    $stmt_update = $conn->prepare("UPDATE sttracking SET responsetime = ?, distance = ? WHERE id = ?");
    $stmt_update->bind_param("sdi", $cur, $distance_val, $task_id);
    $stmt_update->execute();
    $stmt_update->close();

    // 3. Recalculate and update the 'tracktoday' string for sessioninfo.
    $stmt_track_string = $conn->prepare("SELECT responsetime FROM sttracking WHERE stid = ? AND date = ? ORDER BY id");
    $stmt_track_string->bind_param("ss", $userid, $td);
    $stmt_track_string->execute();
    $result_track_string = $stmt_track_string->get_result();
    $trp = '';
    if ($result_track_string) {
        while ($row = $result_track_string->fetch_assoc()) {
            $trp .= ($row['responsetime'] === null) ? '0' : '1';
        }
    }
    $stmt_track_string->close();

    if ($trp !== '') {
        $stmt_update_session = $conn->prepare("UPDATE sessioninfo SET tracktoday = ? WHERE stid = ? AND sessionyear = ?");
        $stmt_update_session->bind_param("sss", $trp, $userid, $sy);
        $stmt_update_session->execute();
        $stmt_update_session->close();
    }

    // 4. Find the teacher for the subject and send a notification.
    $stmt_teacher = $conn->prepare("
        SELECT u.email, u.token, u.userid as teacher_id
        FROM subsetup ss
        JOIN usersapp u ON ss.tid = u.userid
        WHERE ss.sccode = ? AND ss.classname = ? AND ss.sectionname = ? AND ss.subject = ?
        LIMIT 1
    ");
    $stmt_teacher->bind_param(
        "ssss", 
        $sccode, 
        $tracking_entry['classname'], 
        $tracking_entry['sectionname'], 
        $tracking_entry['subject']
    );
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();

    if ($result_teacher && $result_teacher->num_rows > 0) {
        $teacher_info = $result_teacher->fetch_assoc();
        $tmail = $teacher_info['email'];
        $ttoken = $teacher_info['token'];
        $tid = $teacher_info['teacher_id'];

        if (!empty($ttoken)) {
            // --- FCM Push Notification Logic ---
            $title = $stnameeng . ' completed a task'; // $stnameeng from inc.php
            $body = 'Task completed on subject ' . $tracking_entry['subject'] . ' at ' . $cur;
            $icon = 'https://eimbox.com/students/'. $userid . '.jpg';
            $apiKey = 'AAAAiSanis8:APA91bGHIRxAjn8YBaf562fukaYy9N9_8LiNIm5XcTZnHEPqIK7Nr38PQhMJrWTpt9g0VI6U9DMvRT58K-D8AwHwwBvG3YqK8hKbxTMNu9qjaAm6KGj09FGyYT3RVUwExfs4IWXSfucp'; // This should be stored securely, not in code.
            
            $apiBody = [
                'notification' => ['title' => $title, 'body' => $body, 'image' => $icon],
                'data' => ['source' => 'task_completion'],
                'to' => $ttoken
            ];
            
            $headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false, // Not recommended for production
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_POSTFIELDS => json_encode($apiBody)
            ]);
            $fcm_result = curl_exec($ch);
            curl_close($ch);
            
            // Securely log the notification
            $stmt_notify = $conn->prepare("
                INSERT INTO notification (sccode, sessionyear, datetime, frommail, tomail, fromusercat, tousercat, fromuserid, touserid, msgtype, title, smstext) 
                VALUES (?, ?, ?, ?, ?, 'Student', 'Teacher', ?, ?, 'Daily Task', ?, ?)
            ");
            $stmt_notify->bind_param("sssssssss", $sccode, $sy, $cur, $usr, $tmail, $userid, $tid, $title, $body);
            $stmt_notify->execute();
            $stmt_notify->close();
        }
    }
    $stmt_teacher->close();
}

// 5. Redirect back to the dashboard to prevent form resubmission.
header("Location: index.php");
exit();
?>
