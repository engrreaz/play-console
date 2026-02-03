<?php
/**
 * Bulk Routine Saver - Backend Logic
 * Processes JSON data from Smart Routine Builder
 */
include_once '../inc.light.php'; // ডাটাবেজ কানেকশন এবং সেশন চেক

if (isset($_POST['routine'])) {
    $routine_json = $_POST['routine'];
    $data_array = json_decode($routine_json, true);

    if (!empty($data_array)) {
        foreach ($data_array as $slot) {
            $day = mysqli_real_escape_string($conn, $slot['day']);
            $period = mysqli_real_escape_string($conn, $slot['period']);
            $classname = "Nine"; // আপনার প্রোজেক্ট অনুযায়ী এটি ডাইনামিক করতে পারেন
            
            $subject = "";
            $teacher_id = "";

            // ডাটা থেকে সাবজেক্ট এবং টিচার আলাদা করা
            foreach ($slot['data'] as $item) {
                if (strpos($item, 'Teacher ID:') !== false) {
                    $teacher_id = trim(str_replace('Teacher ID:', '', $item));
                } else {
                    $subject = mysqli_real_escape_string($conn, $item);
                }
            }

            // ১. চেক করা এই স্লটটি ডাটাবেজে আগে থেকে আছে কি না
            $check = $conn->query("SELECT id FROM classroutine 
                                  WHERE sccode='$sccode' AND day='$day' 
                                  AND period='$period' AND classname='$classname'");

            if ($check->num_rows > 0) {
                // ২. থাকলে আপডেট করা
                $sql = "UPDATE classroutine SET 
                        subject = '$subject', 
                        tid = '$teacher_id', 
                        modifieddate = '$cur' 
                        WHERE sccode='$sccode' AND day='$day' 
                        AND period='$period' AND classname='$classname'";
            } else {
                // ৩. না থাকলে ইনসার্ট করা (যদি মাস্টার শিডিউল থেকে সিঙ্ক করা না থাকে)
                // দ্রষ্টব্য: এখানে সময় (timestart/end) মাস্টার শিডিউল থেকে আনা উচিত
                $sql = "INSERT INTO classroutine (sccode, sessionyear, classname, day, period, subject, tid, modifieddate) 
                        VALUES ('$sccode', '2025', '$classname', '$day', '$period', '$subject', '$teacher_id', '$cur')";
            }

            $conn->query($sql);
        }
        echo "success";
    } else {
        echo "No data received";
    }
} else {
    echo "Invalid Request";
}
?>