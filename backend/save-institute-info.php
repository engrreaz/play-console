<?php
include '../inc.light.php'; // ডাটাবেস কানেকশন এবং $sccode এখানে আছে

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = $_POST;
    $update_parts = [];
    $params = [];
    $types = "";

    foreach ($fields as $key => $value) {
        if ($key != 'submit') { // সাবমিট বাটন বাদে বাকি সব কলাম হিসেবে ধরবে
            $update_parts[] = "$key = ?";
            $params[] = $value;
            $types .= "s";
        }
    }

    $sql = "UPDATE scinfo SET " . implode(', ', $update_parts) . " WHERE sccode = ?";
    $params[] = $sccode;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Information updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    exit;
}