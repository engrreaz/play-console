<?php
/**
 * User Profile Update Backend - Secure Version
 * Logic: Prepared Statements | SQL Injection Protected | Multi-field Sync
 */

// টাইমজোন এবং ডিবি কানেকশন
date_default_timezone_set('Asia/Dhaka');
$dt = date('Y-m-d H:i:s');
include('../inc.light.php'); // পাথ চেক করে নিন (আপনার স্ট্রাকচার অনুযায়ী ../ প্রয়োজন হতে পারে)

// ১. ইনপুট ডাটা গ্রহণ (POST রিকোয়েস্ট থেকে)
$id          = $_POST['id'] ?? 0;
$profilename = $_POST['profilename'] ?? '';
$mobile      = $_POST['mobile'] ?? '';
$area        = $_POST['area'] ?? '';
$dist        = $_POST['dist'] ?? '';

// ২. প্রাথমিক ভ্যালিডেশন
if (empty($id) || empty($profilename)) {
    http_response_code(400);
    echo "<b>Invalid Request: Missing required data.</b>";
    exit;
}

// ৩. সিকিউর আপডেট কুয়েরি (Prepared Statement)
// profilename, mobile এর সাথে area এবং dist ফিল্ডগুলোও আপডেট করা হচ্ছে
$sql = "UPDATE usersapp SET 
        profilename = ?, 
        mobile = ?, 
        area = ?, 
        dist = ?, 
        lastaccess = ? 
        WHERE id = ? LIMIT 1";

$stmt = $conn->prepare($sql);

if ($stmt) {
    // 'sssssi' -> ৪টি স্ট্রিং (name, mobile, area, dist, dt) এবং ১টি ইন্টিজার (id)
    $stmt->bind_param("sssssi", $profilename, $mobile, $area, $dist, $dt, $id);
    
    if ($stmt->execute()) {
        // ৪. সফল হলে রেসপন্স (এটি AJAX success ফাংশনে প্রদর্শিত হবে)
        echo '<i class="bi bi-check2-circle text-success"></i> <b>Profile Updated Successfully.</b>';
    } else {
        // এক্সিকিউশন ফেইল করলে
        http_response_code(500);
        echo "<b>Database Update Failed.</b>";
    }
    
    $stmt->close();
} else {
    // কুয়েরি প্রিপেয়ার করতে সমস্যা হলে
    http_response_code(500);
    echo "<b>Server Error: Could not prepare statement.</b>";
}

$conn->close();