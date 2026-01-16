<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');

// 0 == Add new (Expenditute), 1 == Edit, 2 == Delete, 3 == Set Memo No., 4 == ........, 5 == Add New (Income)

$id = $_POST['id'];
$slot = $_POST['slot'];
$sy = $_POST['sy'];
if (strlen($sy) < 4) {
    $sy += 2000;
}
$item = $_POST['item'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];
$amt = $_POST['amt'];
// $tail = $_POST['tail'];
$stid = $_POST['stid'];
$indid = $_POST['indid'];








if ($indid == 0) {
    if ($amt >= 0) {

        $query331 = "INSERT INTO financesetupind (id, sccode, slot, sessionyear, stid, slno, itemcode, classname, sectionname, amount, update_time) 
    values (NULL, '$sccode',  '$slot',  '$sy', '$stid', '',  '$item',  '$cls',  '$sec',  '$amt',  '$cur');";
        $conn->query($query331);
    }
} else {
    if ($amt > 0) {
        $query331 = "UPDATE financesetupind set amount = '$amt', update_time = '$cur' where id='$indid' and sccode = '$sccode';";
        $conn->query($query331);
    } else {
        $query331 = "DELETE from financesetupind where id='$indid' and sccode = '$sccode';";
        $conn->query($query331);
    }

}

// echo $query331;

if ($sec != '') {
    $btn = 'info';
} else if ($cls != '') {
    $btn = 'primary';
} else {
    $btn = 'success';
}
?>

<button class="btn btn-inverse-<?php echo $btn; ?> pt-2  mr-3"><i
        class="bi bi-check-circle-fill text-<?php echo $btn; ?>  mdi-18px"></i></button>