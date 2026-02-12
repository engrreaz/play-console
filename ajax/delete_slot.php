<?php
include_once '../inc.light.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM slots WHERE id = '$id' AND sccode = '$sccode'";
    
    if ($conn->query($sql)) {
        echo "success";
    }
}
?>