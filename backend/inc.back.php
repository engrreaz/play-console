<?php
session_start();
date_default_timezone_set('Asia/Dhaka');
include '../db.php';
//*****************************************************************
//*****************************************************************
//*****************************************************************
//*****************************************************************
//*****************************************************************
//*****************************************************************
//*****************************************************************
$sms_price = 0.4;
$usr = $_SESSION["user"];

$BASE_PATH_URL = '../../';
$BASE_PATH_URL_FILE = '../';

//*****************************************************************
$sy = date('y');
$SY = date('Y');

$td = date('Y-m-d');
$cur = date('Y-m-d H:i:s');
//********************************************************************

$sql0 = "SELECT * FROM usersapp where email='$usr' LIMIT 1";
//echo $sql0;
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $token = $row0["token"];
        $sccode = $row0["sccode"];
        $fullname = $row0["profilename"];
        $mobile = $row0["mobile"];
        $userlevel = $row0["userlevel"];
        $userid = $row0["userid"];
        $pth = $row0["photourl"];
        $exam = $row0["curexam"];
        // $sy = $row0["session"];
    }
} else {
    $query33p = "insert into usersapp (sccode, email, token, firstlogin, lastlogin, photourl) values ('0', '$usr', '$token', '$cur', '$cur', '$pth' )";
    //echo $query33p;
    $conn->query($query33p);
}

if ($userlevel == 'Super Administrator' || $userlevel == 'Moderator') {
    $reallevel = $userlevel;
    $userlevel = 'Administrator';
} else {
    $reallevel = $userlevel;
}


if ($sccode > 100) {
    $sql0x = "SELECT * FROM scinfo where sccode='$sccode' LIMIT 1";
    $result0x = $conn->query($sql0x);
    if ($result0x->num_rows > 0) {
        while ($row0x = $result0x->fetch_assoc()) {
            $scname = $row0x["scname"];
            $sctype = $row0x["sccategory"];
            $scadd1 = $row0x["scadd1"];
            $scadd2 = $row0x["scadd2"];
            $ps = $row0x["ps"];
            $dist = $row0x["dist"];
            $logo = $row0x["logo"];
            $mobile = $row0x["mobile"];
            $rootuser = $row0x["rootuser"];
            $short = $row0x["short"];
            $sctype = $row0x["sccategory"];

            $scaddress = $scadd1 . $scadd2 . $ps . $dist;
            $contact = $mobile;
        }
    }
}



$l = strlen($pth);
if ($l < 5) {
    $pth = "https://eimbox.com/images/no-image.png";
}


$ins_all_settings = array();
$sql0x = "SELECT * FROM settings where sccode='$sccode'";
// echo $sql0x;
$result0xrtyv = $conn->query($sql0x);
if ($result0xrtyv->num_rows > 0) {
    while ($row0x = $result0xrtyv->fetch_assoc()) {
        $ins_all_settings[] = $row0x;
    }
}