<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');
include '../datam/datam-stprofile.php';

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
$miss = 0;
$sms_textx = "";



if ($pos == 4) {



    if ($param = 'Student') {

        $student_list = array();
        $sql0 = "SELECT * FROM sessioninfo where sccode = '$sccode' and sessionyear LIKE '%$sy%' and classname='$param2' and sectionname='$param3' order by rollno";
        // echo $sql0;
        $result0rt44 = $conn->query($sql0);
        if ($result0rt44->num_rows > 0) {
            while ($row0 = $result0rt44->fetch_assoc()) {
                $student_list[] = $row0;
            }
        }
        $i = 0;
        // echo '<br><br>';


        foreach ($student_list as $st_data) {
            $stid = $st_data['stid'];
            // echo $stid;

            $final_array[$i]['stid'] = $stid;



            $ind = array_search($stid, array_column($datam_st_profile, 'stid'));
            if ($ind != '' || $ind != NULL) {
                $final_array[$i]['stname'] = $datam_st_profile[$ind]['stnameeng'];
                $final_array[$i]['guarmobile'] = $datam_st_profile[$ind]['guarmobile'];
            }
            $i++;
        }
    }

    // echo '<pre>';
    // print_r($final_array);
    // echo '</pre>';


    $query332x = "DELETE FROM sms_temp   where campaign_id ='$uid' ;";
    $conn->query($query332x);

    $datam = array();
    $cn = 0;
    $total_sms = 0;
    foreach ($final_array as $indind) {
        $stid = $indind['stid'];
        $mno = $indind['guarmobile'];
        $stname = $indind['stname'];
        // echo $stid . '/' . $mno . '/' . $stname . '<br>';

        $search_array = array("[STNAME_ENG]", "[STID]", "[MOBILE_NUMBER]");
        // $replace_array = array();
        $replace_array = array($stname, $stid, $mno);

        $datam[$cn]['[STNAME_ENG]'] = $stname;
        $datam[$cn]['[STID]'] = $stid;
        $datam[$cn]['[MOBILE_NUMBER]'] = $mno;



        // echo '<pre>';

        // print_r($datam[$cn]);
        // print_r($search_array);
        // print_r($replace_array);
        // echo '</pre>';

        $sms_textx = strtr($sms_text, $datam[$cn]);

        // $sms_text = str_replace($search_array, $replace_array, $sms_text);
        // echo $sms_textx . '<br><br>';
        $char = strlen($sms_textx);
        $count_sms = ceil($char / 159);
        $total_sms += $count_sms;

        $query332y = "INSERT INTO sms_temp (id, sccode, campaign_id, stid, mobile, sms_text, char_count, sms_count, time_record) 
        VALUES (NULL, '$sccode', '$uid', '$stid','$mno', '$sms_textx', '$char', '$count_sms', '$cur' );";
        $conn->query($query332y);

        if ($mno != '') {
            $miss++;
        }
        $cn++;
    }

    $cost = $total_sms * $sms_price;
    // echo $cost;





}



$sms_cnt = $count_sms;
$audi_cnt = count($final_array);
$total_sms = $sms_cnt * $audi_cnt;
$price = $total_sms * $sms_price;


?>

<div class="text-center text-small mt-2">
    <button id="send_btn" class="btn btn-outline-danger" onclick="send_final();">Send SMS</button>
    <div id="msg"></div>
</div>

<script>
    var campx = sessionStorage.getItem("uid");
    if (campx == '' || campx == null) {
        document.getElementById("send_btn").disabled = true;
        document.getElementById("msg").innerHTML = "Your must enter a campaign name.";
    }
</script>



<div hidden>
    <div id="aa"><?php echo $sms_cnt; ?></div>
    <div id="bb"><?php echo $audi_cnt; ?></div>
    <div id="cc"><?php echo $total_sms; ?></div>
    <div id="dd"><?php echo $price; ?></div>
    <div id="ee"><?php echo $sms_textx; ?></div>
    <div id="ff"><?php echo $miss; ?></div>
</div>

<?php



$sql0 = "SELECT * FROM sms_campaign where sccode = '$sccode' and camp_id = '$uid' order by id desc limit 1;";
$result0rt = $conn->query($sql0);
if ($result0rt->num_rows > 0) {
    while ($row0 = $result0rt->fetch_assoc()) {
        $id = $row0["id"];
    }
    $query332 = "UPDATE sms_campaign set date = '$td', camp_name='$camp_name', sms_text='$sms_text', audi_param_1='$param1',  audi_param_2='$param2',  audi_param_3='$param3',  audi_param_4='$param4',  audi_param_5='$param5', sms_count='$sms_cnt', audi_count='$audi_cnt', total_count='$total_sms', price='$price'  where id ='$id' ;";
    $conn->query($query332);


} else {
    $query332 = "INSERT INTO sms_campaign(id, sccode, date, camp_name, camp_id, sms_text, audi_param_1, audi_param_2, audi_param_3, audi_param_4, audi_param_5, sms_count, audi_count, total_count, price, camp_status, modifieddate) 
    VALUES (NULL, '$sccode', '$td', '$camp_name', '$uid', '$sms_text', '$param1', '$param2', '$param3', '$param4', '$param5', '$sms_cnt', '$audi_cnt', '0', '$price', '0', '$cur' );";
    $conn->query($query332);
}





// $sql0 = "SELECT * FROM logbook where email = '$usr' and sccode = '$sccode' order by id desc limit 1;";
// $result0rt = $conn->query($sql0);
// if ($result0rt->num_rows == 1) {
//     while ($row0 = $result0rt->fetch_assoc()) {
//         $lastpage = $row0["pagename"];
//         $id = $row0["id"];
//         if ($lastpage == $page) {
//             $query331 = "UPDATE logbook set duration = duration + 5 where id='$id' ;";
//         } else {
//             $ip = $_SERVER['REMOTE_ADDR'];
//             $brs = $_SERVER['HTTP_USER_AGENT'];
//             $query331 = "INSERT INTO logbook (id, email, sccode, pagename, duration, filesize, ipaddr, platform, browser, location, entrytime) 
//             VALUES (NULL, '$usr', '$sccode', '$page', '5', '$size',  '$ip', 'ANDROID', '$brs', NULL, '$cur');";
//         }
//     }
// } else {
//     $ip = $_SERVER['REMOTE_ADDR'];
//     $brs = $_SERVER['HTTP_USER_AGENT'];
//     $query331 = "INSERT INTO logbook (id, email, sccode, pagename, duration, filesize, ipaddr, platform, browser, location, entrytime) 
//     VALUES (NULL, '$usr', '$sccode', '$page', '5', '$size', '$ip', 'ANDROID', '$brs', NULL, '$cur');";
// }

// $conn->query($query331);

// $curr = date('Y-m-d H:i:s');
// $query332 = "UPDATE usersapp set lastaccess = '$curr' where email ='$usr' ;";
// $conn->query($query332);


// echo $uid . '............................';
