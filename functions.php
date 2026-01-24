<?php

include_once 'inc.light.php';
function student_profile_image_path($student_id)
{
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;

    $possible_extensions = ['jpg'];
    foreach ($possible_extensions as $ext) {
        $file_path = dirname(__DIR__) . '/students/' . $student_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL . 'students/' . $student_id . '.' . $ext;
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
    return $BASE_PATH_URL . 'teacher/no-img.jpg';
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