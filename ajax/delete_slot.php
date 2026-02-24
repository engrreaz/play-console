<?php
include_once '../inc.light.php';

$id = $_POST['id'];
$conn->query("DELETE FROM slots WHERE id = '$id' AND sccode = '$sccode'");
echo "success";
?>