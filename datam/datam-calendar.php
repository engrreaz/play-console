<?php
// সেশন ইয়ার অনুযায়ী ডেট রেঞ্জ সেট করা
$target_year = $current_session ?? date('Y');
$dd1 = $target_year . '-01-01';
$dd2 = $target_year . '-12-31';

$datam_calendar_events = array();

// Prepared Statement ব্যবহার করা হয়েছে সিকিউরিটির জন্য
// sccode = 0 (সবার জন্য) অথবা নির্দিষ্ট স্কুলের ডেটা ফেচ করা হবে
$sql0 = "SELECT * FROM calendar 
         WHERE (sccode = ? OR sccode = 0) 
         AND (
            (date BETWEEN ? AND ?) OR 
            (dateto BETWEEN ? AND ?)
         )
         AND descrip != '' 
         ORDER BY date ASC";

$stmt = $conn->prepare($sql0);
$stmt->bind_param("issss", $sccode, $dd1, $dd2, $dd1, $dd2);
$stmt->execute();
$result0 = $stmt->get_result();

while ($row0 = $result0->fetch_assoc()) {
  $datam_calendar_events[] = $row0;
}
$stmt->close();