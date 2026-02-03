<?php
include '../inc.light.php';

$id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM task_manager WHERE id='$id'");

echo json_encode(mysqli_fetch_assoc($q));
