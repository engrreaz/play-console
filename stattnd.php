<?php
include 'datam/datam-stprofile.php';

$classname = $_GET['cls'];
$sectionname = $_GET['sec'];
if (isset($_GET['dt'])) {
    $td = $_GET['dt'];
}



$ccur = date('H:i:s');
$sql0 = "SELECT * FROM classschedule where sccode = '$sccode' and sessionyear LIKE '%$sy%' and timestart<='$ccur' and timeend>='$ccur';";
// echo $sql0 ;
$result0rtx = $conn->query($sql0);
if ($result0rtx->num_rows > 0) {
    while ($row0 = $result0rtx->fetch_assoc()) {
        $period = $row0["period"];
        $ts = $row0["timestart"];
        $te = $row0["timeend"];
        $dur = $row0["duration"];
    }
} else {
    $period = 1;
    $ts = 0;
    $te = 0;
    $dur = 0;
}
// $period = 3;

$sql00 = "SELECT * FROM stattnd where  (adate='$td' and sccode='$sccode' and sessionyear LIKE '%$sy%'  and classname = '$classname' and sectionname='$sectionname') or yn=100 order by rollno";
// echo $sql00 ;
$result00gt = $conn->query($sql00);
if ($result00gt->num_rows > 0) {
    while ($row00 = $result00gt->fetch_assoc()) {
        $datam[] = $row00;
    }
}

$from = date("Y-m-d", strtotime("-7 days"));
$stattnd_7 = [];
$sql00 = "SELECT * FROM stattnd where  (adate between '$from' and  '$td' and sccode='$sccode' and sessionyear LIKE '%$sy%'  and classname = '$classname' and sectionname='$sectionname') or yn=100 order by rollno, adate";
// echo $sql00 ;
$result00gt = $conn->query($sql00);
if ($result00gt->num_rows > 0) {
    while ($row00 = $result00gt->fetch_assoc()) {
        $stattnd_7[] = $row00;
    }
}
// echo '<pre>';
// print_r($datam);
// echo '</pre>';


$sql00 = "SELECT * FROM stattndsummery where  date='$td' and sccode='$sccode' and sessionyear='$sy' and classname = '$classname' and sectionname='$sectionname'";
$result00gtt = $conn->query($sql00);
if ($result00gtt->num_rows > 0) {
    while ($row00 = $result00gtt->fetch_assoc()) {
        $rate = $row00["attndrate"];
        $subm = 1;
        $fun = 'grpssx0';
    }
} else {
    $subm = 0;
    $fun = 'grpssx';
}

if ($period >= 2) {
    $fun = 'grpssx2';
}

// 	echo var_dump($datam);
?>

<style>
    .chk {
        font-size: 36px;
    }

    .red {
        color: red;
    }

    .green {
        color: seagreen;
    }

    .blue {
        color: darkcyan;
    }
</style>


<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:<?php if ($subm == 1) {
            echo 'red';
        } else {
            echo 'var(--dark)';
        } ?>; color:var(--lighter);" onclick="gol(<?php echo $id; ?>)">

            <div class="card-body page-top-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="menu-icon"><i class="bi bi-fingerprint"></i></div>
                            <div class="menu-text"> Attendance </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-body page-info-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="stname-eng">
                                <?php echo strtoupper($classname); ?> | <?php echo strtoupper($sectionname); ?>
                            </div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Name
                                of Class | Section</div>
                            <br>
                            <div class="roll-no" style="font-size:24px;">
                                <?php echo strtoupper($period); ?>
                            </div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">
                                Period </div>
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:30px; font-weight:700; line-height:40px;"><span id="att"></span>/<span
                                    id="cnt"></span></div>
                            <div class="st-id">Bunk : <b><span id="bunk">0</span></b> out of <span id="found2"></span>
                            </div>
                            <div
                                style="font-size:12px; font-weight:400; font-style:italic; line-height:24px; color:var(--light);">
                                Attendance Found
                            </div>


                            <div  id="dddate">
                                <input onchange="dtcng();" max="<?php echo $td; ?>" id="xp"
                                    class="form-control text-center  pt-2" type="date" value="<?php echo $td; ?>" <?php if ($period > 1) {
                                           echo 'disabled';
                                       } ?> />

                            </div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">Date
                            </div>


                        </td>
                    </tr>

                    <?php if ($subm == 1) { ?>
                        <tr>
                            <td colspan="2">
                                <div
                                    style="text-align:center; font-size:14px; font-weight:400; font-style:italic; padding: 5px 10px; background:red; color:white; border-radius:15px;; border:1px solid white; ">
                                    Attendance already submitted.</div>
                            </td>
                        </tr>
                    <?php } ?>

                </table>
            </div>
        </div>


        <?php
        $cnt = 0;
        $found = 0;
        $bunk = 0;
        $sql0 = "SELECT * FROM sessioninfo where sessionyear LIKE '%$sy%' and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by $stattnd_sort";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $stid = $row0["stid"];
                $rollno = $row0["rollno"];
                $card = $row0["icardst"];
                $dtid = $row0["id"];
                $status = $row0["status"];
                $rel = $row0["religion"];
                $four = $row0["fourth_subject"];

                include 'component/student-image-path.php';

                $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
                $neng = $datam_st_profile[$st_ind]["stnameeng"];
                $nben = $datam_st_profile[$st_ind]["stnameben"];
                $vill = $datam_st_profile[$st_ind]["previll"];
                $grnametxt = '';


                // $sql00 = "SELECT * FROM students where  sccode='$sccode' and stid='$stid' LIMIT 1";
                // $result00 = $conn->query($sql00);
                // if ($result00->num_rows > 0) {
                //     while ($row00 = $result00->fetch_assoc()) {
                //         $neng = $row00["stnameeng"];
                //         $nben = $row00["stnameben"];
                //         $vill = $row00["previll"];
                //         $grnametxt = '';
                //     }
                // }
        

                //if($card == '1'){$qrc = '<img src="https://chart.googleapis.com/chart?chs=20x20&cht=qr&chl=http://www.students.eimbox.com/myinfo.php?id=5000&choe=UTF-8&chld=L|0" />';} else {$qrc = '';}
        


                $key = array_search($stid, array_column($datam, 'stid'));
                if ($key != NULL || $key != '') {
                    $status = $datam[$key]['yn'];

                    $per1 = $datam[$key]['period1'];
                    $per2 = $datam[$key]['period2'];
                    $per3 = $datam[$key]['period3'];
                    $per4 = $datam[$key]['period4'];
                    $per5 = $datam[$key]['period5'];
                    $per6 = $datam[$key]['period6'];
                    $per7 = $datam[$key]['period7'];
                    $per8 = $datam[$key]['period8'];
                    $bunk = $datam[$key]['bunk'];
                } else {
                    $status = 0;
                    $per1 = '5';
                    $per2 = '5';
                    $per3 = '5';
                    $per4 = '5';
                    $per5 = '5';
                    $per6 = '5';
                    $per7 = '5';
                    $per8 = '5';
                    $bunk = 0;
                }

                if ($status == 0 || $bunk == 1) {
                    $bgc = '--light';
                    $dsbl = ' disabled';
                    $gip = '';
                    $found += 0;
                } else {
                    $bgc = '--lighter';
                    $dsbl = '';
                    $gip = 'checked';
                    $found++;

                    if ($per1 * $per2 * $per3 * $per4 * $per5 * $per6 * $per7 * $per8 == 0) {
                        $bunk++;
                    }
                }

                $day7 = '';
                for ($u = 0; $u < 7; $u++) {
                    $curdatet = strtotime($from) + $u * 86400;
                    $curdate = date('Y-m-d', $curdatet);
                    $clra = 'gray';
                    foreach ($stattnd_7 as $iii => $st) {
                        if ($st['stid'] == $stid && $st['adate'] == $curdate) {
                            if ($st['yn'] == 1) {
                                $clra = 'green';
                            } else {
                                $clra = 'red';
                            }
                            if ($st['bunk'] == 1) {
                                $clra = 'orange';
                            }
                            unset($stattnd_7[$iii]);
                        }
                    }
                    $day7 .= '<div class="attnd-dot" style="background:' . $clra . '; "></div>';
                }


                ?>
                <div class="card text-center" style="background:var(<?php echo $bgc; ?>); color:var(--darker);"
                    onclick="<?php echo $fun; ?>(<?php echo $stid; ?>, <?php echo $rollno; ?>, <?php echo $bunk; ?>)"
                    id="block<?php echo $stid; ?>" <?php echo $dsbl; ?>>
                    <img class="card-img-top" alt="">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td style="padding-left:10px; width:50px;">
                                    <?php if ($period < 2) { ?>
                                        <input style="scale:1.5; border:1px solid var(--dark); " class="form-check-input"
                                            type="checkbox" name="darkmode" id="sta<?php echo $stid; ?>"
                                            onchange="grpssx(<?php echo $stid; ?>, <?php echo $rollno; ?>);" <?php echo $gip; ?>
                                            disabled>
                                    <?php } else { ?>
                                        <input style="scale:1.5; border:1px solid black; " class="form-check-input" type="checkbox"
                                            name="darkmodes" id="sta2<?php echo $stid; ?>"
                                            onchange="grpssx2(<?php echo $stid; ?>, <?php echo $rollno; ?>);" <?php echo $gip; ?>
                                            disabled>
                                    <?php } ?>
                                    <!--<label for="sta<?php echo $stid; ?>">&nbsp;&nbsp;&nbsp;Present</label>-->
                                </td>
                                <td style="width:40px;"><span
                                        style="font-size:24px; font-weight:700;"><?php echo $rollno; ?></span>
                                    <span>
                                        <?php $qrc = '';
                                        echo $qrc; ?>
                                    </span>
                                </td>
                                <td style="text-align:left; padding-left:5px;">
                                    <div class="stname-eng"><?php echo $neng; ?></div>
                                    <div class="stname-ben"><?php echo $nben; ?></div>
                                    <div class="st-id" style="font-weight:600; font-style:normal; color:gray;">ID #
                                        <?php echo $stid . $grnametxt; ?>
                                    </div>
                                    <div class="st-id text-secondary"><?php echo $vill;
                                    ; ?>

                                        <div class="d-flex">
                                            <div style="font-size:11px; font-weight:bold; padding-right:8px;">Today </div>

                                            <?php
                                            for ($u = 1; $u <= 8; $u++) {
                                                if ($per1 == 0) {
                                                    $clr = 'clr-2';
                                                } else if ($per1 == 5) {
                                                    $clr = 'clr-5';
                                                } else {
                                                    $clr = 'clr-5';
                                                    $varvar = 'per' . $u;
                                                    if ($$varvar == '1') {
                                                        $clr = 'clr-1';
                                                    } else if ($$varvar == '0') {
                                                        $clr = 'clr-0';
                                                    } else if ($$varvar == '5') {
                                                        $clr = 'clr-2';
                                                    }
                                                }

                                                // echo $clr;
                                                ?>
                                                <div class="attnd-dot <?php echo $clr; ?>"></div>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                        <div class="d-flex mt-1">
                                            <div style="font-size:11px; font-weight:bold; padding-right:8px;">Last 7 days </div>
                                            <?php echo $day7; ?>
                                        </div>


                                    </div>
                                </td>
                                <td style="text-align:right;" id="ut<?php echo $stid; ?>"><img src="<?php echo $pth; ?>"
                                        class="st-pic-normal" /></td>
                            </tr>
                        </table>


                    </div>
                </div>
                <div class="card text-center sele gg"
                    style="background:var(<?php echo $bgc; ?>); display:none; color:var(--darker);"
                    id="blocksel<?php echo $dtid; ?>">

                </div>

                <?php
                $cnt++;
            }
        }

        ?>


        <?php if ($subm == 0) { ?>
            <div class="card text-center" id="sfinal" style="padding:8px;"><button style="padding:15px; border-radius:5px;"
                    class="btn btn-danger" onclick="submitfinal();">Submit
                    Attendance</button></div>
        <?php } ?>
    </div>

</main>
<div style="height:52px;"></div>


<script>


    function grp(id) {
        var val = document.getElementById("sel" + id).value;
        var infor = "dtid=" + id + "&val=" + val + "&opt=1";
        $("#blocksel" + id).html("");

        $.ajax({
            type: "POST",
            url: "grpupd.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#blocksel" + id).html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                $("#blocksel" + id).html(html);
            }
        });
    }

    function grpp(id) {
        var val = document.getElementById("sel" + id).value;
        var infor = "dtid=" + id + "&val=" + val + "&opt=1";
        $("#blocksel" + id).html("");

        $.ajax({
            type: "POST",
            url: "fourupd.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#blocksel" + id).html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                $("#blocksel" + id).html(html);
            }
        });
    }




    function grpss(id) {
        var val = document.getElementById("sta" + id).checked;
        var infor = "dtid=" + id + "&val=" + val + "&opt=3";
        $("#blocksel" + id).html("");

        $.ajax({
            type: "POST",
            url: "grpupd.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#blocksel" + id).html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                $("#blocksel" + id).html(html);
            }
        });
    }
</script>
<script>
    function submitfinal() {
        var fnd = parseInt(document.getElementById("att").innerHTML) * 1;
        var cnt = parseInt(document.getElementById("cnt").innerHTML) * 1;
        var infor = "cnt=" + cnt + "&fnd=" + fnd + "&opt=5&cls=<?php echo $classname; ?>&sec=<?php echo $sectionname; ?>&adate=<?php echo $td; ?>";
        // alert(infor);
        $("#sfinal").html("");
        $.ajax({
            type: "POST",
            url: "backend/save-st-attnd.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#sfinal").html('<span class="chk blue"><i class="bi bi-floppy-fill"></i></span>');
            },
            success: function (html) {
                $("#sfinal").html(html);
            }
        });
    }
</script>

<script>
    document.getElementById("cnt").innerHTML = "<?php echo $cnt; ?>";
    document.getElementById("att").innerHTML = "<?php echo $found; ?>";
    document.getElementById("found2").innerHTML = "<?php echo $found; ?>";
    document.getElementById("bunk").innerHTML = "<?php echo $bunk; ?>";

    function go(id) {
        window.location.href = "student.php?id=" + id;
    }  
</script>

<script>
    function att(id, roll, bl, per) {
        if (per >= 2) {
            var val = document.getElementById("sta2" + id).checked;
        } else {
            var val = document.getElementById("sta" + id).checked;
        }

        var infor = "stid=" + id + "&roll=" + roll + "&val=" + val + "&opt=2&cls=<?php echo $classname; ?>&sec=<?php echo $sectionname; ?>&per=" + per + "&adate=<?php echo $td; ?>";
        $("#ut" + id).html("");

        $.ajax({
            type: "POST",
            url: "backend/save-st-attnd.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#ut" + id).html('<span class="chk blue"><i class="bi bi-server"></i></span>');
            },
            success: function (html) {
                $("#ut" + id).html(html);
            }
        });
    }


    function dtcng() {
        var ddd = document.getElementById("xp").value;
        window.location.href = 'stattnd.php?cls=<?php echo $classname; ?>&sec=<?php echo $sectionname; ?>&dt=' + ddd;
    }


    function grpssx(id, roll, bunk) {
        // alert(0);
        if (bunk == 1) {
            Swal.fire({
                title: "<small>Already Bunked</small>",
                icon: "warning",
                draggable: true
            });
        } else {
            var bl = document.getElementById("sta" + id).checked;
            var per = 1;
            var cnt = parseInt(document.getElementById("att").innerHTML) * 1;
            if (bl == true) {
                document.getElementById("sta" + id).checked = false;
                cnt--;
            } else {
                document.getElementById("sta" + id).checked = true;
                cnt++;
            }
            document.getElementById("att").innerHTML = cnt;
            att(id, roll, bl, per);
        }
    }

    function grpssx2(id, roll, bunk) {
        // alert(2);

        if (bunk == 1) {
            Swal.fire({
                title: "<small>Already Bunked</small>",
                icon: "warning",
                draggable: true
            });
        } else {
            var per = <?php echo $period; ?>;

            var bl = document.getElementById("sta2" + id).checked;
            var cnt = parseInt(document.getElementById("att").innerHTML) * 1;
            if (bl == true) {
                document.getElementById("sta2" + id).checked = false;
                cnt--;
            } else {
                document.getElementById("sta2" + id).checked = true;
                cnt++;
            }
            document.getElementById("att").innerHTML = cnt;
            att(id, roll, bl, per);
        }
    }
</script>