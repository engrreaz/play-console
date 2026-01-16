<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');

$year = $sy;
$typeg = $_POST['type'];
$partg = $_POST['part'];
$icodeg = $_POST['icode'];
$stidg = $_POST['stid'];
$clsg = $_POST['cls'];
$secg = $_POST['sec'];


$stime = date("Y-m-d H:i:s");
// echo $stime . '<br> ';
$check_count = 0;
$sl = 0;



$finsetupind = array();
$sql0x = "SELECT * FROM financesetupind where sccode='$sccode' and sessionyear LIKE '%$year%' and stid='$stidg' order by slno;";
// echo $sql0x;
$result0xvalstt = $conn->query($sql0x);
if ($result0xvalstt->num_rows > 0) {
    while ($row0x = $result0xvalstt->fetch_assoc()) {
        $finsetupind[] = $row0x;
    }
}





$financesetup = array();
if ($typeg == 'item' || $partg == 'icode') {
    $sql0x = "SELECT * FROM financesetup where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and itemcode='$icodeg' order by id;";
} else {
    $sql0x = "SELECT * FROM financesetup where  sccode='$sccode'  and sessionyear LIKE '%$sy%' order by id;";
}
// echo $sql0x;
$result0xxtr = $conn->query($sql0x);
if ($result0xxtr->num_rows > 0) {
    while ($row0x = $result0xxtr->fetch_assoc()) {
        $financesetup[] = $row0x;
    }
}


$financesetupval = array();
if ($icodeg != '') {
    if ($secg != '') {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and itemcode='$icodeg' and classname='$clsg' and sectionname='$secg' order by itemcode, classname DESC, sectionname DESC;";
    } else if ($clsg != '') {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and itemcode='$icodeg' and classname='$clsg'  order by itemcode, classname DESC, sectionname DESC;";
    } else {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and itemcode='$icodeg' order by itemcode, classname DESC, sectionname DESC;";
    }

            $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and itemcode='$icodeg' order by itemcode, classname DESC, sectionname DESC;";


} else {
    if ($secg != '') {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and classname='$clsg' and sectionname='$secg' order by itemcode, classname DESC, sectionname DESC;";
    } else if ($clsg != '') {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' and classname='$clsg'  order by itemcode, classname DESC, sectionname DESC;";
    } else {
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' order by itemcode, classname DESC, sectionname DESC;";
    }
        $sql0x = "SELECT * FROM financesetupvalue where  sccode='$sccode'  and sessionyear LIKE '%$sy%' order by itemcode, classname DESC, sectionname DESC;";

    
}


// echo $sql0x;
$result0xxtrv = $conn->query($sql0x);
if ($result0xxtrv->num_rows > 0) {
    while ($row0x = $result0xxtrv->fetch_assoc()) {
        $financesetupval[] = $row0x;
    }
}


$classlist = array();
$sql0x = "SELECT areaname, subarea, sessionyear FROM areas where user='$rootuser' and sessionyear like '%$sy%' and sccode='$sccode' order by idno ;";
$result0xxt = $conn->query($sql0x);
if ($result0xxt->num_rows > 0) {
    while ($row0x = $result0xxt->fetch_assoc()) {
        $classlist[] = $row0x;
    }
}



$new = $update = $noneed = 0;
$cls = $sec = $roll = $stid = ' ';
$sessiondata = array();


if ($stidg != '') {
    $sql0x = "SELECT id, stid, sessionyear, classname, sectionname, rollno, rate FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '%$sy%' and stid='$stidg' and validate>=0 ;";
} else if ($secg != '') {
    $sql0x = "SELECT id, stid, sessionyear, classname, sectionname, rollno, rate FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '%$sy%' and classname='$clsg' and sectionname='$secg' and validate=0 ;";
} else if ($clsg != '') {
    $sql0x = "SELECT id, stid, sessionyear, classname, sectionname, rollno, rate FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '%$sy%' and  classname='$clsg' and  validate=0 ;";
} else {
    $sql0x = "SELECT id, stid, sessionyear, classname, sectionname, rollno, rate FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '%$sy%' and validate=0 ;";
}
// echo $sql0x;
$result0xxdffl = $conn->query($sql0x);
if ($result0xxdffl->num_rows > 0) {
    while ($row0x = $result0xxdffl->fetch_assoc()) {
        $sessiondata[] = $row0x;
    }
}


for ($lp = 0; $lp < 1; $lp++) {
    if (count($sessiondata) == 0) {
        break;
    }
    $stid = $sessiondata[0]['stid'];
    $cls = strtolower($sessiondata[0]['classname']);
    $sec = strtolower($sessiondata[0]['sectionname']);
    $roll = strtolower($sessiondata[0]['rollno']);
    $rate = strtolower($sessiondata[0]['rate']);
    $syear = strtolower($sessiondata[0]['sessionyear']);

   

    if ($partg == 'icode') {
        $query3pd = "UPDATE stfinance set validate = '0' where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$syear%' and itemcode='$icodeg' ;";
    } else {
        $query3pd = "UPDATE stfinance set validate = '0' where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$syear%' ;";
    }
    $conn->query($query3pd);
    $query3pq = "UPDATE stfinance set validate = '1' where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$syear%' and paid > 0 ;";
    $conn->query($query3pq);



    $stfinance = array();
    $sql0x = "SELECT * FROM stfinance where  sccode='$sccode' and sessionyear LIKE '%$sy%' and stid='$stid' and validate>=0 order by partid  ;";
    $result0xxdf = $conn->query($sql0x);
    if ($result0xxdf->num_rows > 0) {
        while ($row0x = $result0xxdf->fetch_assoc()) {
            $stfinance[] = $row0x;
        }
    }


    $setupcnt = count($financesetup);

    for ($i = 0; $i < $setupcnt; $i++) {

        $partid = $financesetup[$i]['id'];
        $itemcode = $financesetup[$i]['itemcode'];
        $month = $financesetup[$i]['month'];
        $parte = $financesetup[$i]['particulareng'];
        $partb = $financesetup[$i]['particularben'];

        // echo $partid . '/' . $itemcode . '/' . $parte . '<br>';

        $valcnt = count($financesetupval);
        $amt = 0;
        $ax = $bx = $cx = $dx = 0;
        for ($k = 0; $k < $valcnt; $k++) {
            $uu = $financesetupval[$k]['id'];
            $ii = $financesetupval[$k]['itemcode'];
            $cc = $financesetupval[$k]['classname'];
            $ss = $financesetupval[$k]['sectionname'];
            $aa = $financesetupval[$k]['amount'];

            // echo '<br>KKK : ' . $uu . ' : ' . $itemcode . ' - ' . $ii . '/' . $cls . ' - ' . $cc . '/' . $sec . ' - ' . $ss . ' [' . $aa . ']<br>------------<br>';

            if ($typeg == 'stid' && $ii == $itemcode ) {
                $dx = $aa;
                break;
            } else  if ($ii == $itemcode && ucwords($ss) == ucwords($sec) && ucwords($cc) == ucwords($cls)) {
                $ax = $aa;
                break;
            } else if ($ii == $itemcode && ucwords($ss) == '' && ucwords($cc) == ucwords($cls)) {
                $bx = $aa;
                break;
            } else if ($ii == $itemcode && ucwords($ss) == '' && $cc == '') {
                $cx = $aa;
                break;
            }
            // echo $aa . ' : ' . $ax . ' . ' . $bx . ' . ' . $cx . '<br>';
        }

        if ($ax > 0) {
            $amt = $ax;
        } else if ($bx > 0) {
            $amt = $bx;
        } else if ($cx > 0) {
            $amt = $cx;
        } else if ($dx > 0) {
            $amt = $dx;
        }

        // echo '<br>::: ' . $amt . ' TAKA<br>--------------<br>';
        if(str_contains($parte, 'Tution') ){
              $paya = $amt * $rate / 100;
        } else {
            $paya = $amt *1;
        }
      

        if ($month > 0 && $month < 13) {
            $ls = $month;
            $le = $ls + 1;
            $lstep = 1;
        } else if ($month == 0) {
            $ls = 1;
            $le = 13;
            $lstep = 1;
        } else {
            $lstep = $month / 11;
            $ls = $lstep;
            $le = 13;
        }
        // echo $ls . ' -- ' . $le . ' -- ' . $lstep . ' **** ';
        // var_dump($stfinance);
        $cnt = count($stfinance);
        for ($z = $ls; $z < $le; $z += $lstep) {
            $stfinid = 0;
            $paid = 0;
            if ($month == 0 || $month > 13) {
                $my = date('Y') . '-' . $z . '-01';
                $my = date('F/Y', strtotime($my));
                $partex = $parte . ' : ' . $my;
                $partbx = $partb . ' : ' . $my;
                // echo $partid . ' - ' . $itemcode . ' - ' . $partex . ' - ';
                // *******************************************

                for ($j = 0; $j < $cnt; $j++) {
                    $rowid = $stfinance[$j]['id'];
                    $pid = $stfinance[$j]['partid'];
                    $eng = $stfinance[$j]['particulareng'];
                    $stid = $stfinance[$j]['stid'];
                    $mnth = $stfinance[$j]['month'];
                    $paid = $stfinance[$j]['paid'];
                    $dues = $stfinance[$j]['dues'];

                    // echo '<br>' . $pid . ' ** ' . $eng . ' // ' . $month . '<br>';
                    if ($pid == $partid && $mnth == $z) {
                        $stfinid = $stfinance[$j]['id'];
                        break;
                    }
                }

                include 'check-student-finance-update.php';


                // *******************************************


            } else {
                $partex = $parte;
                $partbx = $partb;
                // echo $partid . ' * ' . $itemcode . ' * ' . $partex . ' * ';
                // *******************************************

                for ($j = 0; $j < $cnt; $j++) {
                    $rowid = $stfinance[$j]['id'];
                    $pid = $stfinance[$j]['partid'];
                    $eng = $stfinance[$j]['particulareng'];
                    $stid = $stfinance[$j]['stid'];
                    $mnth = $stfinance[$j]['month'];
                    $paid = $stfinance[$j]['paid'];
                    $icode = $stfinance[$j]['itemcode'];

                    // echo '<br>' . $pid . ' ** ' . $eng . ' // ' . $mnth . '<br>';
                    if ($itemcode == $icode && $mnth == $z) {
                        $stfinid = $stfinance[$j]['id'];
                        // echo '~~~~' . $stfinid . '~~~~~~~~~';
                        break;
                    }
                }

                include 'check-student-finance-update.php';



                // *******************************************
            }
            // echo ' ===== ' . $rowid . '<br>';


        }
    }

    $sccodes = $sccode * 10;
    // $query3pxdel = "DELETE FROM stfinance where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$sy%' and pr1=0  and validate=0 ;";
    $query3pxdel = "UPDATE stfinance set sccode='$sccodes', deleteby='$usr', deletetime='$cur' where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$sy%' and pr1=0  and validate=0 ;";
    $conn->query($query3pxdel);

    $query3pxdel1 = "update sessioninfo set validate=1, validationtime='$cur' where stid='$stid' and sccode='$sccode' and sessionyear LIKE '%$sy%' ;";
    $conn->query($query3pxdel1);

}


$sl++;


// $sql0x = "SELECT count(*) as cnn FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '%$sy%' and validate=0 ;";
// // echo $sql0x;
// $result0xxdfflcc = $conn->query($sql0x);
// if ($result0xxdfflcc->num_rows > 0) {
//     while ($row0x = $result0xxdfflcc->fetch_assoc()) {
//         $total_students_count = $row0x['cnn'];
//     }
// }



$total_students_count = count($sessiondata);
// echo $check_count;


// echo '<br><br>';
$etime = date("Y-m-d H:i:s");
// echo $etime . '<br>';
$time_elapsed = strtotime($etime) - strtotime($stime);

// echo  . ' %%%%% ' . ' / ' . $valid_class_list;
?>
<div class="float-right"><?php echo $time_elapsed; ?>s.</div>
<?php
echo '<span style="font-size:12px;">> ' . $cls . ' (' . $sec . ') : ' . $roll . ' => ' . $stid . '. ';
echo 'Stat -> insert-new ' . $new . ', update ' . $update . ', no-need ' . $noneed . ' [clean tree]</span><br>';
?>
<div id="totaltotal" hidden><?php echo $total_students_count; ?></div>