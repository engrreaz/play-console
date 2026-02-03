<?php 
include '../inc.light.php';
mysqli_query($conn,"DELETE FROM task_manager WHERE id='$_POST[id]'");
