<?php 

function student_profile_image_path($student_id) {
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


function teacher_profile_image_path($teacher_id) {
    global $BASE_PATH_URL_FILE, $BASE_PATH_URL;
    
    $possible_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($possible_extensions as $ext) {
        $file_path = dirname(__DIR__) . '/teacher/'  . $teacher_id . '.' . $ext;
        if (file_exists($file_path)) {
            return $BASE_PATH_URL . 'teacher/' . $teacher_id . '.' . $ext;
        }
    }
    return $BASE_PATH_URL . 'teacher/no-img.jpg';
}

?>