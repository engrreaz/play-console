<?php
include '../inc.light.php'; // DB connection & sccode
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

if (strlen($q) < 2) {
    echo json_encode(['status' => 'error', 'results' => []]);
    exit;
}

// SQL: title অথবা title_bn তে সার্চ করা হবে
$searchQuery = "%$q%";
$stmt = $conn->prepare("SELECT title, title_bn, url, url_app, icon, type FROM search_index 
                        WHERE (sccode = ? OR sccode IS NULL) 
                        AND (title LIKE ? OR title_bn LIKE ?) 
                        LIMIT 10");
$stmt->bind_param("iss", $sccode, $searchQuery, $searchQuery);
$stmt->execute();
$res = $stmt->get_result();

$results = [];
while ($row = $res->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode(['status' => 'success', 'results' => $results]);