<?php
include '../inc.light.php'; // আপনার DB কানেকশন এবং $sccode এখানে আছে
header('Content-Type: application/json');

if ($is_admin > 3) {
    $sccode = 0;
}
// ১. ডিলিট অপারেশন (যদি delete_id পাঠানো হয়)
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM history WHERE id = ? AND sccode = ?");
    $stmt->bind_param("ii", $id, $sccode);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
    exit;
}

// ২. সেভ/আপডেট অপারেশন
$id = intval($_POST['id']);
$date = $_POST['date'];
$day = date('d', strtotime($date));
$month = date('m', strtotime($date));
$category = $_POST['category'];
$type = $_POST['type'];
$zone = $_POST['zone'];
$details = $_POST['details'];
$priority = intval($_POST['priority']);

if ($id == 0) {
    // নতুন ইনসার্ট
    $sql = "INSERT INTO history (sccode, date, day, month, category, type, zone, details, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiissssi", $sccode, $date, $day, $month, $category, $type, $zone, $details, $priority);
} else {
    // বিদ্যমান ডাটা আপডেট
    $sql = "UPDATE history SET date=?, day=?, month=?, category=?, type=?, zone=?, details=?, priority=? WHERE id=? AND sccode=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisssssii", $date, $day, $month, $category, $type, $zone, $details, $priority, $id, $sccode);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'History synchronized!']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
$stmt->close();