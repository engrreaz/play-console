<?php
include '../inc.light.php'; // DB connection & sccode
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

if (strlen($q) < 2) {
    echo json_encode(['status' => 'error', 'results' => []]);
    exit;
}
$results = [];
// SQL: title অথবা title_bn তে সার্চ করা হবে
$searchQuery = "%$q%";
$stmt = $conn->prepare("SELECT title, title_bn, url, url_app, icon, type FROM search_index 
                        WHERE (sccode = ? OR sccode IS NULL) 
                        AND (title LIKE ? OR title_bn LIKE ?) 
                        LIMIT 10");
$stmt->bind_param("iss", $sccode, $searchQuery, $searchQuery);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $results[] = $row;
}


$stmt2 = $conn->prepare("SELECT pagename as url_app, title, description FROM page_docs 
                        WHERE  (pagename LIKE ? OR title LIKE ? OR description LIKE ?) 
                        LIMIT 10");
$stmt2->bind_param("sss",  $searchQuery, $searchQuery, $searchQuery);
$stmt2->execute();
$res2 = $stmt2->get_result();

while ($row2 = $res2->fetch_assoc()) {
    $results[] = $row2;
}
echo json_encode(['status' => 'success', 'results' => $results]);
