<?php
if (!isset($_SESSION))
{

if (version_compare(PHP_VERSION, '5.3.7', '<')) {exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {require_once('libraries/password_compatibility_library.php');}
require_once('config/config.php');include('db.php'); require_once('translations/en.php');require_once('libraries/PHPMailer.php');require_once('classes/Login.php');$login = new Login();
if ($login->isUserLoggedIn() == true) 
{ $usr=$_SESSION['user_name']; 
 }else {    include("views/not_logged_in.php");}
}
 
 include 'db.php';
date_default_timezone_set('Asia/Dhaka');;							
$tz = new DateTimeZone('Asia/Dhaka');

$sql0 = "SELECT * FROM users where user_name='$usr'";
								$result0 = $conn->query($sql0);
								if ($result0->num_rows > 0) 
							{while($row0 = $result0->fetch_assoc()) { 
							
							$ulevel=$row0["user_level"];
							$sccode=$row0["eiin"];
							}}
							
if (isset($_POST['sccode'])){$sccode=$_POST['sccode'];}else{$sccode=0;}
if (isset($_POST['classname'])){$classname=$_POST['classname'];}else{$classname='NA';}
if (isset($_POST['sectionname'])){$sectionname=$_POST['sectionname'];}else{$sectionname='NA';}
if (isset($_POST['adate'])){$adate=$_POST['adate'];}else{$adate='2016-01-01';}
if (isset($_POST['exam'])){$exam=$_POST['exam'];}else{$exam='';}
if (isset($_POST['subject'])){$subject=$_POST['subject'];}else{$subject='NA';}
$year=date('Y');;
?>

<?php

	$sql22="SELECT count(stid) as cnt FROM sessioninfo where sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' and sessionyear='$year'" ;
	$result22 = $conn->query($sql22);
	if ($result22->num_rows > 0) 
		{while($row22 = $result22->fetch_assoc()) 
			{
			$cnt = $row22["cnt"] ;}}?>
			
			<span class="button cycle-button info"><span class="mif-right-arrow"></span> </span> <span class="fg-emerald">Total Student Found : <span id="stcnt"><?php echo $cnt;?></span></span>
