<?php
/**
 * Update Institution Info - Secure Backend
 * Logic: Prepared Statements | JSON Response | SQL Injection Protected
 */

include('../inc.light.php');

// ১. রিকোয়েস্ট মেথড চেক করা
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ২. ইনপুট ডাটা সংগ্রহ (নিরাপদভাবে)
    // str_replace বা ম্যানুয়াল এসকেপিং এর প্রয়োজন নেই, কারণ আমরা Prepared Statement ব্যবহার করছি
    $scname = $_POST['scname'] ?? '';
    $add1   = $_POST['add1']   ?? '';
    $add2   = $_POST['add2']   ?? '';
    $ps     = $_POST['ps']     ?? '';
    $dist   = $_POST['dist']   ?? '';
    $mno    = $_POST['mno']    ?? '';

    // ৩. সিকিউর কুয়েরি (Prepared Statement)
    // sccode এবং dt (কারেন্ট টাইম) সাধারণত inc ফাইলে ডিফাইন করা থাকে
    $sql = "UPDATE scinfo SET 
            scname = ?, 
            scadd1 = ?, 
            scadd2 = ?, 
            ps = ?, 
            dist = ?, 
            mobile = ?, 
            modifieddate = ? 
            WHERE sccode = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // 'ssssssss' মানে ৮টি স্ট্রিং প্যারামিটার
        $stmt->bind_param("ssssssss", $scname, $add1, $add2, $ps, $dist, $mno, $cur, $sccode);
        
        if ($stmt->execute()) {
            // সফল হলে JSON রেসপন্স
            echo json_encode([
                "status" => "success",
                "message" => "Institution profile updated successfully."
            ]);
        } else {
            // এক্সিকিউশনে সমস্যা হলে
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Database update failed: " . $stmt->error
            ]);
        }
        $stmt->close();
    } else {
        // কুয়েরি প্রিপেয়ার করতে সমস্যা হলে
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Internal server error: Prepared statement failed."
        ]);
    }

} else {
    // সরাসরি ফাইল এক্সেস করার চেষ্টা করলে
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
}

$conn->close();