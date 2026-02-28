<?php
include '../inc.light.php';
$id = intval($_POST['id']);
$q = $conn->query("SELECT * FROM financesetup WHERE id=$id AND sccode='$sccode'");
echo json_encode($q->fetch_assoc());