<?php
include '../inc.light.php'; // আপনার পাথ অনুযায়ী

$prno = $_POST['prno'] ?? '';
if ($prno == '') {
    $sql0r = "SELECT * FROM stpr where sccode='$sccode' and entryby='$usr' order by entrytime desc limit 1 ";
} else {
    $sql0r = "SELECT * FROM stpr where sccode='$sccode' and prno='$prno' order by entrytime desc limit 1 ";
}

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

// স্টুডেন্ট নাম
$sql0r = "SELECT stnameeng FROM students where stid='$stid' ";
$result0b = $conn->query($sql0r);
$stname = ($result0b->num_rows > 0) ? $result0b->fetch_assoc()["stnameeng"] : 'Unknown';

// আইটেম কাউন্ট
$sql0r = "SELECT count(*) as cnt FROM stfinance where (pr1no='$prno' || pr2no='$prno') and sccode='$sccode' and stid='$stid' ";
$result0bt = $conn->query($sql0r);
$cnt = ($result0bt->num_rows > 0) ? $result0bt->fetch_assoc()["cnt"] : 0;

// কালেক্টর নাম
$sql0r = "SELECT profilename, userid FROM usersapp where email='$eby' ";
$result0bx = $conn->query($sql0r);
$collname = '';
if ($result0bx->num_rows > 0) {
    $u_data = $result0bx->fetch_assoc();
    $collname = $u_data["profilename"];
    $uid = $u_data["userid"];
}

if ($collname == '') {
    $sql0r = "SELECT tname FROM teacher where tid='$uid' ";
    $result0bxg = $conn->query($sql0r);
    if ($result0bxg->num_rows > 0) {
        $collname = $result0bxg->fetch_assoc()["tname"];
    }
}

// আইটেম লুপ তৈরি
$loop = '';
$item = 1;
$sql0r = "SELECT * FROM stfinance where (pr1no='$prno' || pr2no='$prno') and sccode='$sccode' and stid='$stid'";
$result0bg = $conn->query($sql0r);
if ($result0bg->num_rows > 0) {
    while ($row0r = $result0bg->fetch_assoc()) {
        $de = str_replace(["Tution Fee : ", "Exam Fee : ", "/"], ["", "", "-"], $row0r["particulareng"]);
        $tk = $row0r["paid"];
        // urlencode ব্যবহার করা হয়েছে যাতে লিংকে এরর না আসে
        $loop .= '&item' . $item . 'txt=' . urlencode($de) . '&item' . $item . 'taka=' . $tk;
        $item++;
    }
}

// ফাইনাল লিংক জেনারেশন
$baseUrl = 'https://playconsole.eimbox.com/stpr.php?';
$params = [
    'prno' => $prno,
    'prdate' => $prdate,
    'stname' => $stname,
    'cls' => $cls,
    'sec' => $sec,
    'roll' => $roll,
    'total' => $total,
    'stid' => $stid,
    'collname' => $collname,
    'cnt' => $cnt
];

$lnk = $baseUrl . http_build_query($params) . $loop;

// শুধুমাত্র লিংকটি আউটপুট হিসেবে পাঠাবে
echo $lnk;
?>