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
            return $BASE_PATH_URL_FILE . 'teacher/' . $teacher_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL_FILE . 'teacher/no-img.jpg';
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



function getPagePermission($curfile) {
    global $conn, $sccode, $usr, $userlevel, $reallevel, $is_admin, $is_chief;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // SAFE DEFAULTS
    if(!$is_admin) $is_admin = 0;
    if(!$is_chief) $is_chief = 0;

    // NOT LOGGED IN
    if (!isset($_SESSION['user'])) {
        return 0; // Access denied
    }

    // Fetch package/module info
    $package_id = $package_name = $tier = $valid_module = $active_module = '';
    $sql_scinfo = "SELECT package_id, package_name, tier, valid_module, active_module 
                   FROM scinfo WHERE sccode='{$sccode}'";
    $result_scinfo = $conn->query($sql_scinfo);

    if ($result_scinfo->num_rows > 0) {
        while ($row_scinfo = $result_scinfo->fetch_assoc()) {
            $package_id = $row_scinfo["package_id"];
            $package_name = $row_scinfo["package_name"];
            $tier = $row_scinfo["tier"] ?? 'A';
            $valid_module = $row_scinfo["valid_module"];
            $active_module = $row_scinfo["active_module"];
        }
    }

    $valid_modules = explode(' | ', $valid_module);
    $active_modules = explode(' | ', $active_module);

    $permission = 0; // default

    // Admin/Chief full access
    if ($is_admin > 3 || $is_chief > 0) {
        $permission = 3;
    } else {
        // Custom permission check
        $sql_permission = "
        SELECT * FROM permission_map_app 
        WHERE page_name = '$curfile' 
        AND (email = '$usr' OR email IS NULL OR email = '') 
        AND (userlevel = '$reallevel' OR userlevel IS NULL OR userlevel = '') 
        AND (sccode = '$sccode' OR sccode = 0) 
        ORDER BY email DESC, userlevel DESC, sccode DESC 
        LIMIT 1
        ";
        $result_permission = $conn->query($sql_permission);

        if ($result_permission->num_rows > 0) {
            $row_permission = $result_permission->fetch_assoc();
            $permission = $row_permission['permission'] ?? 0;
            $module_name = $row_permission['module'] ?? '';
            $p_email = $row_permission['email'] ?? '';
            $p_userlevel = $row_permission['userlevel'] ?? '';
            $p_sccode = $row_permission['sccode'] ?? 0;
            $p_root_page = $row_permission['root_page'] ?? '';

            if (in_array($module_name, $active_modules)) {
                if ($p_email == '' or $p_userlevel == '' or $p_sccode == 0) {
                    $sql = "
                        SELECT *
                        FROM permission_map
                        WHERE page_name = '$p_root_page'
                        AND (
                                email = '$usr'
                            OR userlevel = '$userlevel'
                            OR sccode = '$sccode'
                        )
                        ORDER BY 
                            CASE 
                                WHEN email = '$usr' THEN 1
                                WHEN sccode = '$sccode' THEN 2
                                WHEN userlevel = '$userlevel' THEN 3
                                ELSE 4
                            END
                        LIMIT 1
                    ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $permission = $row['permission'];
                    }
                }
            } else {
                $permission = 0;
            }

            // Special module overrides
            if ($module_name == 'Core') $permission = 3;
            if ($module_name == 'Developement') $permission = 0;
        } else {
            $permission = 0;
        }
    }

    // Admin GET override
    if (isset($_GET['perm']) && $is_admin > 3) {
        $permission = $_GET['perm'];
    }

    return $permission;
}