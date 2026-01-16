<?php
if (($cn == 'Six') || ($cn == 'Seven') || ($cn == 'Eight')) {
    $ben_avg = $ben_total;
    if ($ben_avg < 33) {
        $ben_gp = 0;
        $ben_gl = 'F'; //$tfail++;
    } else {
        $sql22 = "SELECT * FROM gpa where maxvalues >='$ben_avg'  order by maxvalues LIMIT 0,1";
        $result22 = $conn->query($sql22);
        if ($result22->num_rows > 0) {
            while ($row22 = $result22->fetch_assoc()) {
                $ben_gp = $row22["gp"];
                $ben_gl = $row22["gl"];
            }
        }
    }
    //if($sccode=='105676'){$eng_fullmarks = 150;}
    $eng_avg = $sub_2_total;
    // echo $rollno . '........' . $eng_avg . '****<br>';
    if ($eng_avg < 33) {
        $eng_gp = 0;
        $eng_gl = 'F'; //$tfail++;
    } else {
        $sql22 = "SELECT * FROM gpa where maxvalues >='$eng_avg'  order by maxvalues LIMIT 0,1";
        $result22 = $conn->query($sql22);
        if ($result22->num_rows > 0) {
            while ($row22 = $result22->fetch_assoc()) {
                $eng_gp = $row22["gp"];
                $eng_gl = $row22["gl"];
            }
        }
    }

    if ($tfail > 7) {
        $tfail = 7;
    }

} else {
    //********************************************************************************************
    //if($sccode==105676){$rat = 1.25;} else {$rat = 1;}
    $rat = 1;
    $ben_sub_rate = ceil($ben_sub * 100 / $sss) * $rat;
    $ben_obj_rate = ceil($ben_obj * 100 / $ooo) * $rat;
    $ben_total_rate = $ben_total * 100 / $ben_fullmarks;
    if (($ben_sub_rate < 33) || ($ben_obj_rate < 33)) {
        $ben_gp = 0;
        $ben_gl = 'F';
    } else {
        $sql22 = "SELECT * FROM gpa where maxvalues >='$ben_total_rate'  order by maxvalues LIMIT 0,1";
        $result22 = $conn->query($sql22);
        if ($result22->num_rows > 0) {
            while ($row22 = $result22->fetch_assoc()) {
                $ben_gp = $row22["gp"];
                $ben_gl = $row22["gl"];
            }
        }
    }
    //********************************************************************************************
    $eng_fullmarks = 200;
    //$eng_sub_rate = ceil($ben_sub * 100 / $sss);
    //$ben_obj_rate = ceil($ben_obj * 100 / $ooo);
    $eng_total = $sub_3_total + $sub_4_total;
    $eng_total_rate = ($eng_total * 100 / $eng_fullmarks);
    if ($eng_total_rate < 33) {
        $eng_gp = 0;
        $eng_gl = 'F';
    } else {
        $sql22 = "SELECT * FROM gpa where maxvalues >='$eng_total_rate'  order by maxvalues LIMIT 0,1";
        $result22 = $conn->query($sql22);
        if ($result22->num_rows > 0) {
            while ($row22 = $result22->fetch_assoc()) {
                $eng_gp = $row22["gp"];
                $eng_gl = $row22["gl"];
            }
        }
    }
    //********************************************************************************************
    if ($ben_gp == 0) {
        $tfail = $tfail + 1;
    }
    if ($eng_gp == 0) {
        $tfail = $tfail + 1;
    }
}

