<?php

	include ('../db.php');;
	
	$id = $_POST['id'];;  
    $query33 ="DELETE from pibientry where id='$id'";
	$conn->query($query33);
	echo '*';

