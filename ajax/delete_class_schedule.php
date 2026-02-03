<?php
include_once '../inc.light.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $sql = "DELETE FROM classschedule WHERE id = '$id' AND sccode = '$sccode'";
    
    if($conn->query($sql)){
        echo 'success';
    } else {
        echo 'error';
    }
}
?>