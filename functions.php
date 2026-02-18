<?php

include_once 'inc.light.php';
function student_profile_image_path($student_id)
{
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;

    $possible_extensions = ['jpg'];
    foreach ($possible_extensions as $ext) {
        $file_path = dirname(__DIR__) . '/students/' . $student_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL_FILE . 'students/' . $student_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL . 'students/noimg.jpg';
}


function teacher_profile_image_path($teacher_id)
{
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;

    $possible_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($possible_extensions as $ext) {
        $file_path = dirname(__DIR__) . '/teacher/' . $teacher_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL . 'teacher/' . $teacher_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL . '/teacher/no-img.jpg';
}




function get_student_info_by_id($stid)
{
    global $conn, $sccode, $sessionyear_param;

    $std_data = [];
    $stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno,
        s.stnameeng, s.stnameben
        FROM students s 
        JOIN sessioninfo si ON s.stid = si.stid 
        WHERE s.stid = ? AND si.sessionyear LIKE ? and si.sccode = ? LIMIT 1 ");
    $stmt->bind_param("sss", $stid, $sessionyear_param, $sccode);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $std_data = $row;
    }
    $stmt->close();
    return $std_data;
}


function getNotifMeta($type)
{
    $type = strtolower($type);
    switch ($type) {
        case 'achievements':
        case 'achievement':
            return ['icon' => 'trophy-fill', 'color' => '#FFC107']; // Gold
        case 'payment':
            return ['icon' => 'credit-card-fill', 'color' => '#2E7D32']; // Green
        case 'alert':
            return ['icon' => 'exclamation-triangle-fill', 'color' => '#B3261E']; // Red
        default:
            return ['icon' => 'bell-fill', 'color' => '#6750A4']; // Purple
    }
}




function saveTeacherAttendance($tid, $detect, $val = NULL, $time = null)
{
    global $conn, $sccode, $usr;

    // Punch time
    $ts = $time ? strtotime($time) : time();
    $date = date('Y-m-d', $ts);
    $tval = date('H:i:s', $ts);

    // Decimal hour
    $decimal = date('H', $ts) + date('i', $ts) / 60;

    // Entry user
    $entryuser = $usr;


    

    /* =========================
       1️⃣ Determine ReqIn / ReqOut
    ========================== */
    $reqin = $reqout = null;

    // Step 1: teacher table curin / curout
    $tq = $conn->prepare("SELECT curin, curout, slots FROM teacher WHERE tid=? AND sccode=? LIMIT 1");
    $tq->bind_param("ii", $tid, $sccode);
    $tq->execute();
    $teacher = $tq->get_result()->fetch_assoc();

    if ($teacher) {
        $reqin = $teacher['curin'] ?? '00:00:00';
        $reqout = $teacher['curout'] ?? '00:00:00';
        $slot = $teacher['slots'];
    }

    // Step 2: slots table
    if (!$reqin || !$reqout || $reqin == '00:00:00' || $reqout == '00:00:00') {
        $sq = $conn->prepare("SELECT reqin, reqout FROM slots WHERE sccode=? AND slotname=? LIMIT 1");
        $sq->bind_param("ii", $sccode, $slot);
        $sq->execute();
        $slotRow = $sq->get_result()->fetch_assoc();
        if ($slotRow) {
            $reqin = $slotRow['reqin'] ?? '00:00:00';
            $reqout = $slotRow['reqout'] ?? '00:00:00';
        }
    }

    // Step 3: scinfo table
    if (!$reqin || !$reqout || $reqin == '00:00:00' || $reqout == '00:00:00') {
        $scq = $conn->prepare("SELECT intime, outtime FROM scinfo WHERE sccode=? LIMIT 1");
        $scq->bind_param("i", $sccode);
        $scq->execute();
        $scinfo = $scq->get_result()->fetch_assoc();
        if ($scinfo) {
            $reqin = $scinfo['intime'] ?? '00:00:00';
            $reqout = $scinfo['outtime'] ?? '00:00:00';
        }
    }

    // Step 4: default fallback
    if (!$reqin || $reqin == '00:00:00')
        $reqin = '10:00:00';
    if (!$reqout || $reqout == '00:00:00')
        $reqout = '16:00:00';


    // echo $reqin . ' | ' . $reqout . '<br>';

    $reqTs = strtotime($date . ' ' . $reqin);
    $reqOutTs = strtotime($date . ' ' . $reqout);

    /* =========================
       2️⃣ Check Existing Record
    ========================== */
    $chk = $conn->prepare("SELECT id, realin, realout FROM teacherattnd WHERE tid=? AND adate=? AND sccode=? LIMIT 1");
    $chk->bind_param("isi", $tid, $date, $sccode);
    $chk->execute();
    $res = $chk->get_result()->fetch_assoc();

    /* =========================
       3️⃣ INSERT (IN PUNCH)
    ========================== */
    if (!$res) {
        $status = ($ts > $reqTs) ? 'Late' : 'Present';

        $q = $conn->prepare("
            INSERT INTO teacherattnd
            (tid, adate, realin, detectin, statusin, disin, in_decimal, sccode, entryuser, reqin, reqout)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");

        $dist = intval($val);

        $q->bind_param(
            "issssidisss",
            $tid,
            $date,
            $tval,
            $detect,
            $status,
            $dist,
            $decimal,
            $sccode,
            $entryuser,
            $reqin,
            $reqout
        );

        return $q->execute();
    }

    /* =========================
       4️⃣ UPDATE (OUT PUNCH)
    ========================== */ else {
        if (!empty($res['realout']))
            return false;

        $q = $conn->prepare("
            UPDATE teacherattnd
            SET realout=?,
                detectout=?,
                disout=?,
                out_decimal=?,
                dutytime = TIMEDIFF(?, realin)
            WHERE id=?
        ");

        $dist = intval($val);

        $q->bind_param(
            "sssisi",
            $tval,
            $detect,
            $dist,
            $decimal,
            $tval,
            $res['id']
        );

        return $q->execute();
    }
}


