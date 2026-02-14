<?php
include '../inc.light.php';

$prno = $_POST['prno'] ?? '';
if ($prno == '') {
    $sql0r = "SELECT * FROM stpr where sccode='$sccode' and entryby='$usr' order by entrytime desc limit 1 ";
} else {
    $sql0r = "SELECT * FROM stpr where sccode='$sccode' and prno='$prno' order by entrytime desc limit 1 ";

}

// echo $sql0r;

$result0r = $conn->query($sql0r);
if ($result0r->num_rows > 0) {
    while ($row0r = $result0r->fetch_assoc()) {
        $cls = $row0r["classname"];
        $sec = $row0r["sectionname"];
        $roll = $row0r["rollno"];
        $stid = $row0r["stid"];
        $prno = $row0r["prno"];
        $prdate = date('d-m-Y', strtotime($row0r["prdate"]));
        $total = $row0r["amount"];
        $eby = $row0r["entryby"];
    }
}

$sec = str_replace("Studies", "", $sec);

$sql0r = "SELECT * FROM students where stid='$stid' ";
$result0b = $conn->query($sql0r);
if ($result0b->num_rows > 0) {
    while ($row0r = $result0b->fetch_assoc()) {
        $stname = $row0r["stnameeng"];
    }
}



$sql0r = "SELECT count(*) as cnt FROM stfinance where (pr1no='$prno' || pr2no='$prno') and sccode='$sccode' and stid='$stid' ";
$result0bt = $conn->query($sql0r);
if ($result0bt->num_rows > 0) {
    while ($row0r = $result0bt->fetch_assoc()) {
        $cnt = $row0r["cnt"];
    }
}

$sql0r = "SELECT * FROM usersapp where email='$eby' ";
$result0bx = $conn->query($sql0r);
if ($result0bx->num_rows > 0) {
    while ($row0r = $result0bx->fetch_assoc()) {
        $collname = $row0r["profilename"];
        $uid = $row0r["userid"];
    }
}

if ($collname == '') {
    $sql0r = "SELECT * FROM teacher where tid='$uid' ";
    $result0bxg = $conn->query($sql0r);
    if ($result0bxg->num_rows > 0) {
        while ($row0r = $result0bxg->fetch_assoc()) {
            $collname = $row0r["tname"];
        }
    }
}


$loop = '';
$item = 1;
$sql0r = "SELECT * FROM stfinance where (pr1no='$prno' || pr2no='$prno') and sccode='$sccode' and stid='$stid'";
$result0bg = $conn->query($sql0r);
if ($result0bg->num_rows > 0) {
    while ($row0r = $result0bg->fetch_assoc()) {
        // $item = $item;
        $partid = $row0r["partid"];
        $de = $row0r["particulareng"];
        $de = str_replace("Tution Fee : ", "", $de);
        $de = str_replace("Exam Fee : ", "", $de);
        $de = str_replace("/", "-", $de);
        $tk = $row0r["paid"];
        $loop = $loop . '&item' . $item . 'id=' . $partid . '&item' . $item . 'txt=' . $de . '&item' . $item . 'taka=' . $tk;
        $item++;
    }
}



// echo $loop;


$lnk = 'https://playconsole.eimbox.com/stpr.php?prno=' . $prno . '&prdate=' . $prdate . '&stname=' . $stname . '&cls=' . $cls . '&sec=' . $sec . '&roll=' . $roll . '&total=' . $total . '&stid=' . $stid . '&collname=' . $collname . '&cnt=' . $cnt . $loop;
// $lnk = 'receipt.php?prno='.$prno.'&prdate='.$prdate.'&stname='.$stname.'&cls='.$cls.'&sec='.$sec.'&roll='.$roll.'&total='.$total.'&stid='.$stid.'&collname='.$collname.'&cnt='.$cnt.$loop;
echo $lnk;
?>

<!-- <meta http-equiv="refresh" content="0; URL=" /> -->