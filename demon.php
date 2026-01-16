<?php
date_default_timezone_set('Asia/Dhaka');;
	include ('../db.php');;
	

           
    $query33 ="UPDATE sessioninfo set trackyesterday = tracktoday, tracktoday = NULL";
    $conn->query($query33);
