<?php
// INSERT NECESSARY TO-DO-LIST ***************************************************************************************************************************
$ddd = 0;
$sql0 = "SELECT *  FROM todolist where date='$td' and sccode='$sccode' and user='$usr' and todotype='attendance'";
$result01x = $conn->query($sql0);
if ($result01x->num_rows == 0) {

    $query33pxy = "insert into todolist (id, sccode, date, user, todotype, descrip1, descrip2, status, creationtime, response, responsetxt, responsetime) 
                    values (NULL, '$sccode', '$td', '$usr', 'Attendance', '', '', 0, '$cur', 'geoattnd', 'Submit', NULL);";
    $conn->query($query33pxy);
}


// INSERT NECESSARY TO-DO-LIST ***************************************************************************************************************************
?>


<style>
    .card {
        border: 0;
        border-radius: 0;
    }

    .nored {
        border: 0;
        border-radius: 0;
    }

    .card-header {
        padding: 10px;
    }



    .nmbr {
        font-size: 30px;
        font-weight: bold;
    }

    .nmbr small {
        font-size: 14px;
        font-weight: 500;
    }

    .prog {
        width: 80px;
        text-align: center;
    }
</style>





<?php if ($reallevel == 'Super Administrator') { ?>


    <?php include 'task-teacher.php'; ?>














    <div class="card gg">
        <div class="card-body">
            EIIN : <b><?php echo $sccode; ?></b><br>
            <input class="input form-control" type="number" id="scc" onblur="chng();" value="<?php echo $sccode; ?>" />
            

        </div>
    </div>




<?php }
//**********************************************************************************************************************************************************************   

if ($reallevel == 'Super Administrator' || $reallevel == 'Moderator') {
    ?>

    <div class="card gg">
        <div class="card-body">

            <a class="btn btn-warning" href="admin-sclist.php">Institute List</a>
            <a class="btn btn-danger" href="sout.php">Log Out</a>
            <br>
            <a class="btn btn-danger" href="kbase.php">Knowledge Base</a><br>
            <a class="btn btn-success" href="kbaseadd.php">Knowledge Add</a>
            <a class="btn btn-info" href="receipt.php?cls=Nine&sec=Science&roll=25">EPOS</a>
            <a class="btn btn-warning"
                href="stattnd.php?cls=<?php echo $cteachercls; ?>&sec=<?php echo $cteachersec; ?>">Attendance</a>

        </div>
    </div>




    <?php
}
//**********************************************************************************************************************************************************************   
if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher' || $reallevel == 'Super Administrator' || $reallevel == 'Teacher') {
    if (strtotime($cur) < strtotime($expire)) {
        $tmcnt = strtotime($expire) - strtotime($cur);
    } else {
        $tmcnt = 0;
    }

    if ($tmcnt <= 7 * 24 * 3600) {


        ?>
        <div id="kk" style="display:none;"><?php echo $tmcnt; ?></div>
        <div class="card gg" hidden>
            <div class="card-body">
                <h5>Service Expiry</h5>
                <small><span style="font-style:italic; ">Sir, Your service will expire in </span></small>
                <div style="font-weight:700; color:red;" id="jj"></div>
                <button class="btn btn-warning" style="margin-top:8px;" onclick="aaa();">Continue Services</button>
            </div>
        </div>

    <?php } ?>

    <div class="card gg" hidden>
        <div class="card-body">
            <h5>Settings</h5>
            <small><span style="font-style:italic; ">To start mark entry/process result, please insert some settings like
                    teachers info, class info, subject info, create students profile.<br>To do this, click the settings
                    button below :</span></small>
            <button class="btn btn-success" style="margin-top:8px;" onclick="gorx();">Go To Settings</button>
            <button class="btn btn-dark" style="margin-top:8px;" onclick="sublist();">Subjects List</button>

        </div>
    </div>

    <?php
    if ($reallevel == 'Super Administrator') {
        //  echo '<a href="lottery-view.php">Lottery</a>';
    }


    if ($servicefinance >= 0) { //Block to active for Financial serive activated institute.........................

        if ($rank == 'Head Teacher' || $rank == 'Principal' || $reallevel == 'Super Administrator') {
            $mon = date('m');
            echo '';

            echo '<a class="btn btn-dark" style="margin-top:8px;"  href="mypr.php">My Receipts</a>';

            include 'front-page-block/cashmanager.php';
            include 'front-page-block/st-payment-block.php';
            // include 'front-page-block/st-payment-block.php';vvvvv

            //   include 'front-page-block/clsteacherblock.php';










        }









    } //Finance service activa institiute...................

    ?>

    <?php
    $sql0 = "SELECT count(*) as cntt FROM areas where sessionyear='$sy' and user='$rootuser' and halfdone=0";
    $result01g = $conn->query($sql0);
    if ($result01g->num_rows > 0) {
        while ($row0 = $result01g->fetch_assoc()) {
            $cntt = $row0["cntt"];
        }
    }

    if ($cntt >= 0) {


        $sql0 = "SELECT sum(half) as req FROM areas where sessionyear='$sy' and user='$rootuser'";
        $result01 = $conn->query($sql0);
        if ($result01->num_rows > 0) {
            while ($row0 = $result01->fetch_assoc()) {
                $req = $row0["req"];
            }
        }
        $sql0 = "SELECT count(*) as doneold FROM pibientry where sessionyear='$sy' and sccode='$sccode' and exam = 'Half Yearly'";
        $result01 = $conn->query($sql0);
        if ($result01->num_rows > 0) {
            while ($row0 = $result01->fetch_assoc()) {
                $doneold = $row0["doneold"];
            }
        }
        $sql0 = "SELECT count(*) as donenew FROM stmark where sessionyear='$sy' and sccode='$sccode' and exam = 'Half Yearly'";
        $result01 = $conn->query($sql0);
        if ($result01->num_rows > 0) {
            while ($row0 = $result01->fetch_assoc()) {
                $donenew = $row0["donenew"];
            }
        }

        $done = $donenew + $doneold;
        if ($req > 0) {
            $rate = ceil($done * 100 / $req);
        } else {
            $rate = 0;
        }

        ?>
        <div class="card " onclick="gov();">
            <div class="card-header" style="color:var(--lighter); background:var(--dark);border-radius:0;"><b>Student
                    Assessments</b></div>
            <div class="card-body">
                <h6><b>Marks Entry My Class</b></h6>
                <div style="background:var(--light);">
                    <div style="background:var(--darker); width:<?php echo $rate; ?>%; height:10px;"></div>
                </div>
                <small><span style="font-style:normal;">Process : <b><?php echo $doneold; ?> </b> | Found :
                        <b><?php echo $donenew; ?> </b> | Required : <b><?php echo $req; ?> </b> | </span></small><br>
                <small><span style="font-style:italic;">Total Progress : <b><?php echo $rate; ?>%
                        </b>done.</span></small><br>
                <button class="btn btn-danger" style="margin-top:8px;" onclick="token();">Token</button>
            </div>
        </div>

        <?php


        ?>

        <div class="card gg" onclick="gor();">
            <div class="card-body">
                <h4>My Subjects (Marks Entry)</h4>
                <small><span style="font-style:italic;">View Status of Marks Entry (Annual Examination 2024)</span></small>
            </div>
        </div>


        <?php
    }
}

//**********************************************************************************************************************************************************************   

//   echo 'xxx';
$sql0 = "SELECT sum(amount) as paisi FROM stpr where sessionyear='$sy' and sccode='$sccode' and entryby='$usr'";
$result01xe = $conn->query($sql0);
if ($result01xe->num_rows > 0) {
    while ($row0 = $result01xe->fetch_assoc()) {
        $paisi = $row0["paisi"];
    }
}


include 'front-page-block/accountantsblock.php';
include 'front-page-block/clsteacherblock.php';




if ($reallevel == 'Super Administrator' || $userlevel == 'Administrator') {
    ?>
    <div class="card nored" style="">
        <div class="card-header nored" style="color:var(--lighter); background:var(--dark); border-radius:0;"><b>Student's
                Attendance (Admin Block)</b></div>
        <div class="card-body" onclick="goclsattall();">
            <table width="100%">
                <tr>
                    <td>
                        <div class="nmbr" style="color:var(--dark);"><small> </small><span
                                id="duess"><?php echo $ddd; ?></span></div>
                        <small>
                            <div
                                style="font-style:normal; color:var(--dark); font-size:14px; font-weight:600; margin-bottom:8px;">
                                <?php echo date('d F, Y', strtotime($td)); ?>
                            </div>
                            <span style="font-style:normal; color:gray;">
                                Today's Attendance
                            </span>
                        </small>

                        <div class="d-flex flex-wrap">
                            <?php
                            $ts = 0;
                            $as = 0;
                            $sql0 = "SELECT * FROM areas where sessionyear = '$sy' and user='$rootuser' order by FIELD(areaname,'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'SSC $sy'), idno, subarea";
                            $result01xe1 = $conn->query($sql0);
                            if ($result01xe1->num_rows > 0) {
                                while ($row0 = $result01xe1->fetch_assoc()) {
                                    $cls = $row0["areaname"];
                                    $sec = $row0["subarea"];
                                    $sql0 = "SELECT * FROM stattndsummery where sessionyear = '$sy' and sccode='$sccode' and classname='$cls' and sectionname='$sec' and date='$td'";
                                    //echo $sql0;
                                    $result01xe2 = $conn->query($sql0);
                                    if ($result01xe2->num_rows > 0) {
                                        while ($row0 = $result01xe2->fetch_assoc()) {
                                            $tstu = $row0["totalstudent"];
                                            $astu = $row0["attndstudent"];
                                            $clr = 'dark';
                                        }
                                    } else {
                                        $tstu = 0;
                                        $astu = 0;
                                        $clr = 'light';
                                    }

                                    echo '<div style="text-align:center; width:20px;"><div style="margin:0 3px 0 0; font-size:8px; text-align:center; padding-top:3px; height:15px; width:15px; border-radius:50%; background:var(--' . $clr . '); color:white;">' . $astu . '</div>
                                    <span style="font-size:9px; color:var(--dark);">' . $astu . '</span></div>';
                                    $ts += $tstu;
                                    $as += $astu;
                                }
                            }
                            if($ts>0){
                                $attrate = ceil($as * 100 / $ts);
                            } else {
                                $attrate = 0;
                            }
                            
                            $deg = $attrate * 3.6;
                            ?>
                        </div>




                        <div style="font-size:24px; color:var(--dark); font-weight:700;">
                            <?php echo $as; ?><span style="font-size:12px; font-weight:400;"> out of
                                <b><?php echo $ts; ?></b></span>
                        </div>
                    </td>
                    <td class="prog">
                        <div
                            style="border:1px solid var(--dark); poisition:relative; margin:auto; text-align:center; border-radius:50%; height:72px; width:72px; background-image: conic-gradient(var(--dark) 0deg, var(--dark) <?php echo $deg; ?>deg, var(--lighter) <?php echo $deg; ?>deg, var(--lighter) 360deg);">
                            <div
                                style="border:1px solid var(--dark); border-radius:50%; left:5px; top:5px; position:relative; background:var(--light); color:purple;;width:60px; height:60px; padding-top:20px;">
                                <?php echo $attrate; ?><small>%</small>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>



        </div>
    </div>





    <?php
}


?>

<?php include 'notice.php'; ?>


<a href="https://www.web.eimbox.com/teachersedit.php?tid=<?php echo $userid; ?>" class="btn btn-info">My Pfofile</a>


<div style="height:52px;"></div>







<?php include 'footer.php'; ?>



<script>
    function goclsp() { window.location.href = 'finclssec.php'; }
    function goclsa() { window.location.href = 'finacc.php'; }
    function goclss() { window.location.href = 'finstudents.php?cls=<?php echo $cteachercls; ?>&sec=<?php echo $cteachersec; ?>'; }

    function gor() { alert("OK"); window.location.href = 'resultprocess.php'; }
    function gorx() { window.location.href = 'settings.php'; }
    function sublist() { window.location.href = 'tools_allsubjects.php'; }
    function update() { window.location.href = 'whatsnew.php'; }
    function token() { window.location.href = 'accountsecurity.php'; }

    function goclsa() { window.location.href = 'finacc.php'; }
    function mypr() { window.location.href = 'mypr.php'; }

    function goclsatt(x1, x2) { window.location.href = 'stattnd.php?cls=' + x1 + '&sec=' + x2; }
    function register(x1, x2) { window.location.href = 'stattndregister.php?cls=' + x1 + '&sec=' + x2; }




    function goclsattall() { window.location.href = 'attndclssec.php'; }
</script>