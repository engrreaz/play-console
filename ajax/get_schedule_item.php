<?php
// ডাটাবেস কানেকশন এবং স্কুল কোড (sccode) ইনক্লুড করা হচ্ছে
include_once '../inc.light.php';

// যদি ID পোস্ট করা হয়
if (isset($_POST['id'])) {
    // ইনপুট সিকিউরিটি (Sanitize)
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // নির্দিষ্ট ID এবং স্কুল কোড অনুযায়ী ডাটা সিলেক্ট করা
    $sql = "SELECT * FROM classschedule WHERE id = '$id' AND sccode = '$sccode' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // ডাটাবেসের টাইম ফরম্যাট যদি H:i:s থাকে, তবে তা HTML5 time ইনপুট সাপোর্টেড ফরম্যাটে রাখা
        // (যা editSchedule জাভাস্ক্রিপ্ট ফাংশনে ঠিকঠাক কাজ করবে)
        echo json_encode($row);
    } else {
        // যদি কোন ডাটা না পাওয়া যায়
        echo json_encode(['error' => 'No record found']);
    }
} else {
    // যদি ID মিসিং থাকে
    echo json_encode(['error' => 'Invalid ID request']);
}
?>