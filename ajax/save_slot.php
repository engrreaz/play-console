<?php
include_once '../inc.light.php';

// ইনপুট স্যানিটাইজেশন
$id = $_POST['id'] ?? '';
$slotname = mysqli_real_escape_string($conn, $_POST['slotname']);
$merit = intval($_POST['merit']);
$parents = mysqli_real_escape_string($conn, $_POST['parents']);
$cus_report = mysqli_real_escape_string($conn, $_POST['cus_report']);
$reqin = $_POST['reqin'] ?: null;
$reqout = $_POST['reqout'] ?: null;
$eng = isset($_POST['trans_name_eng']) ? 1 : 0;
$ben = isset($_POST['trans_name_ben']) ? 1 : 0;

if (!empty($id)) {
    // এডিট/আপডেট লজিক
    $sql = "UPDATE slots SET 
            slotname = '$slotname', merit = '$merit', parents = '$parents', 
            cus_report = '$cus_report', reqin = '$reqin', reqout = '$reqout', 
            trans_name_eng = '$eng', trans_name_ben = '$ben' 
            WHERE id = '$id' AND sccode = '$sccode'";
} else {
    // নতুন ইনসার্ট লজিক
    $sql = "INSERT INTO slots (sccode, slotname, merit, parents, cus_report, reqin, reqout, trans_name_eng, trans_name_ben) 
            VALUES ('$sccode', '$slotname', '$merit', '$parents', '$cus_report', '$reqin', '$reqout', '$eng', '$ben')";
}

if ($conn->query($sql)) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}
?>