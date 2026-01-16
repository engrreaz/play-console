<?php
	$tp="DELETE  from tabulatingsheet where classname ='$cn'  and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and exam='$exam' and rollno between '$start' and '$start'  " ;
    $conn->query($tp);
    //echo $tp;