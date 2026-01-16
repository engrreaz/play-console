<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');
include '../datam/datam-stprofile.php';

$time_start = date('Y-m-d H:i:s');


$uid = $_POST['uid'];

$camp_name = $_POST['camp'];
$sms_text = $_POST['sms'];
$param1 = $_POST['p1'];
$param2 = $_POST['p2'];
$param3 = $_POST['p3'];
$param4 = $_POST['p4'];
$param5 = $_POST['p5'];
$pos = $_POST['pos'];




$sms_cnt = 0;
$audi_cnt = 0;
$final_array = array();
$count_sms = 0;





$sms_cnt = $count_sms;
// $audi_cnt = count($final_array);
$total_sms = $sms_cnt * $audi_cnt;
$price = $total_sms * $sms_price;



$fetch_temp_data = array();
$sql0 = "SELECT * FROM sms_temp where sccode = '$sccode' and campaign_id = '$uid' ";
// echo $sql0;
$result0rt44_fetch_temp_data = $conn->query($sql0);
if ($result0rt44_fetch_temp_data->num_rows > 0) {
    while ($row0 = $result0rt44_fetch_temp_data->fetch_assoc()) {
        $fetch_temp_data[] = $row0;
    }
}


$succ_sms = $err_sms = $tot_cost = 0;

foreach ($fetch_temp_data as $sms) {
    $idg = $sms['id'];
    $camp = $sms['campaign_id'];
    $number = $sms['mobile'];
    if ($number == null) {
        $number = '0';
    }
    $message = $sms['sms_text'];


    // echo $idg . '<br>';

    include 'send-sms-sql.php';

    $tot_cost += $cost;



    if ($sms_id == '') {
        $err_sms++;
    } else {
        $succ_sms++;
    }


    $query332xx = "DELETE FROM sms_temp   where id ='$idg' ;";
    $conn->query($query332xx);
    $audi_cnt++;
}

$toto = $succ_sms + $err_sms;

$sql0 = "SELECT * FROM sms_campaign where sccode = '$sccode' and camp_id = '$uid' order by id desc limit 1;";
$result0rtty = $conn->query($sql0);
if ($result0rtty->num_rows > 0) {
    while ($row0 = $result0rtty->fetch_assoc()) {
        $id = $row0["id"];
    }
}

echo '<div class="text-small fw-bold text-primary">Message Sending Done</div>';

$query332 = "UPDATE sms_campaign set camp_status = '1', total_count='$toto', price='$tot_cost', audi_count='$audi_cnt' where id ='$id' ;";
$conn->query($query332);

$time_end = date('Y-m-d H:i:s');

$dur = strtotime($time_end) - strtotime($time_start);


$sms_bill_count = 0;
$query332_scinfo = "UPDATE scinfo set sms_send = sms_send + '$toto' , sms_success = sms_success + '$succ_sms', sms_error = sms_error + '$err_sms', sms_cost =  sms_cost + '$tot_cost', sms_balance = sms_balance - '$sms_bill_count', account_balance = account_balance - '$tot_cost'  where sccode ='$sccode' ;";
$conn->query($query332_scinfo);




?>
<div id="status" class="text-small text-success "></div>
<div class="mt-3"></div>
<button class="btn btn-outline-primary text-small" onclick="prev(1);">Go Back</button>



<div hidden>
    <div id="a"><?php echo $succ_sms; ?></div>
    <div id="b"><?php echo $err_sms; ?></div>
    <div id="c"><?php echo $toto ; ?></div>
    <div id="d"><?php echo $tot_cost; ?></div>
</div>





<script>
    document.getElementById("prevnext").style.display = 'none';

    document.getElementById("status").innerHTML = document.getElementById("c").innerHTML + " Message has been send successfully at <?php echo $dur; ?> second(s).";

</script>