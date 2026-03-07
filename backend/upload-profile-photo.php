<?php
include '../inc.light.php';

if ($_FILES['photo']['name']) {
    $userid = $_POST['userid'];
    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

    // ফাইলের নাম ইউনিক করা (যেমন: teacher_12345.jpg)
    $new_file_name = "teacher_" . $userid . "_" . time() . "." . $extension;

    // আপলোড ডিরেক্টরি (আপনার সার্ভারের লোকাল পাথ অনুযায়ী)
    $upload_path = "../../teacher/" . $new_file_name;
    $public_url = $BASE_PATH_URL_FILE . "teacher/" . $new_file_name;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
        // ডাটাবেস আপডেট (photourl কলামে নতুন লিঙ্ক সেভ করা)
        $stmt = $conn->prepare("UPDATE usersapp SET photourl = ? WHERE userid = ?");
        $stmt->bind_param("ss", $public_url, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'new_url' => $public_url]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File move failed. Check folder permissions.']);
    }
}