<?php

include('inc.back.php');
;
$adate = $_POST['adate'];
;
$cn = $_POST['cls'];
$sn = $_POST['sec'];
$opt = $_POST['opt'];

if ($opt == 2) {  // save attandance
    $iii = $_POST['stid'];
    $roll = $_POST['roll'];
    $yn = $_POST['val'];
    $per = $_POST['per'];
    if ($yn == 'true') {
        $yn = 1;
    } else {
        $yn = 0;
    }

    $submit_found = 0;
    $sql00 = "SELECT * FROM stattndsummery where  date='$td' and sccode='$sccode' and sessionyear LIKE '%$sy%'  and classname = '$cn' and sectionname='$sn' ";
    $result00gt_check_submit = $conn->query($sql00);
    if ($result00gt_check_submit->num_rows > 0) {
        $submit_found = 1;
    }

    $sql0 = "SELECT * FROM stattnd where stid='$iii' and adate='$adate' and sessionyear LIKE '%$sy%' ";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
        if ($per < 2) {
            $query33 = "UPDATE stattnd SET yn = '$yn', period1 = '$yn', period2 = '$yn', period3 = '$yn', period4 = '$yn', period5 = '$yn', period6 = '$yn', period7 = '$yn', period8 = '$yn', entryby = '$usr' WHERE stid='$iii' and adate='$adate' and sessionyear LIKE '%$sy%'  and sccode='$sccode'";
        } else {
            $cd = '';
            for ($i = $per; $i <= 8; $i++) {
                $cd .= 'period' . $i . '=' . $yn . ', ';
            }
            if ($yn == 0) {
                $bunk = 1;
                $query338 = "UPDATE stattndsummery set bunk = bunk+1 WHERE date='$adate' and sessionyear  LIKE '%$sy%'   and sccode='$sccode'";
                $conn->query(query: $query338);


            } else {
                $bunk = 0;
            }
            $cd .= 'period1=1 ';
            $query33 = "UPDATE stattnd SET $cd , bunk = '$bunk' WHERE stid='$iii' and adate='$adate' and sessionyear  LIKE '%$sy%'   and sccode='$sccode'";
        }
        $conn->query(query: $query33);
    } else {
        if ($submit_found == 0) {
            $query33 = "insert into stattnd (id, sccode, sessionyear, stid, adate, yn, entryby, classname, sectionname, rollno, period1) values 	(NULL, '$sccode', '$sy', '$iii','$adate','$yn','$usr','$cn','$sn', '$roll', '$yn')";
            $conn->query($query33);
        } else {
            echo '<span class="chk red"><i class="bi bi-x-circle-fill"></i></span>';
        }

    }
    // echo $query33;
    if ($submit_found == 0) {
        if ($yn == 1) {
            echo '<span class="chk green"><i class="bi bi-check2-circle"></i></span>';
        } else {
            echo '<span class="chk red"><i class="bi bi-x-circle"></i></span>';
        }
    } else {
        if ($yn == 1) {
            echo '<span class="chk red"><i class="bi bi-x-circle"></i></span>';
        } else {
            echo '<span class="chk orange"><i class="bi bi-x-circle"></i></span>';
        }
    }

    // echo $per;

} else if ($opt == 5) { // save final submition



    $cnt = $_POST['cnt'];
    $fnd = $_POST['fnd'];

    $sql0 = "SELECT count(*) as cnt FROM stattnd where sccode = '$sccode' and sessionyear LIKE '%$sy%'  and adate='$adate' and classname='$cn' and sectionname='$sn' and yn=1;";
    // echo $sql0 ;
    $result0rtx__stattnd = $conn->query($sql0);
    if ($result0rtx__stattnd->num_rows > 0) {
        while ($row0 = $result0rtx__stattnd->fetch_assoc()) {
            $fnd = $row0["cnt"];
        }
    }



    $rate = $fnd * 100 / $cnt;
    $query33 = "insert into stattndsummery (id, sccode, sessionyear, date, classname, sectionname, totalstudent, attndstudent, attndrate, submitby, submittime) 
                                            values 	(NULL, '$sccode', '$sy', '$adate','$cn','$sn','$cnt','$fnd','$rate', '$usr', '$cur')";
    $conn->query($query33);

    echo '<i class="bi bi-check-circle-fill text-success"></i> Submit Successfully.';
}
