<?php
include_once '../inc.light.php';
$id = $_POST['id'];
$result = $conn->query("SELECT * FROM slots WHERE id = '$id' AND sccode = '$sccode'");
echo json_encode($result->fetch_assoc());
?>