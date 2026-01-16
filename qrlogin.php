<?php
	include 'incc.php';;
	date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; 
	include ('../db.php');;

    echo '<div style="font-size:40px;">';


	$qr= $_GET['qr'];; 
                                        
		$query33 ="update qrcodelogin set email='$usr', logintime='$dt', status=1 where token='$qr';"; //echo $query33;
		//echo $query33;
		$conn->query($query33);
        
    echo '</div>';

?>

<meta http-equiv="Refresh" content="0; url='index.php'" />