<?php
include '../inc.light.php';

if (isset($_POST['update_pass'])) {
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];

    // বর্তমান পাসওয়ার্ড চেক (ধরে নিচ্ছি 'usersapp' টেবিলে 'password' কলাম আছে)
    $stmt = $conn->prepare("SELECT password_hash FROM usersapp WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $usr);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($old, $res['password_hash'])) {
        // Argon2id অ্যালগরিদম ব্যবহার করে হ্যাশিং
        $hashed_pass = password_hash($new, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64MB
            'time_cost' => 4,
            'threads' => 2
        ]);

        $upd = $conn->prepare("UPDATE usersapp SET password_hash = ?, password_salt = ? WHERE email = ?");
        $upd->bind_param("sss", $hashed_pass, $new, $usr);
        echo $upd->execute() ? "1" : "Update failed.";
    } else {
        echo "Current password is incorrect.";
    }
    exit;
}