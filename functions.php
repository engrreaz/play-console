<?php 

function student_profile_image_path($student_id) {
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;
    
    $possible_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($possible_extensions as $ext) {
        $file_path = $BASE_PATH_URL_FILE . 'students/' . $student_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL . 'students/' . $student_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL . 'students/default.png';
}


function teacher_profile_image_path($teacher_id) {
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;
    
    $possible_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($possible_extensions as $ext) {
        $file_path = $BASE_PATH_URL_FILE . 'teacher/' . $teacher_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL . 'teacher/' . $teacher_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL . 'teacher/no-img.jpg';
}

?>