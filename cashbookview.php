<?php
include 'inc.php';

$datefrom = '2023-01-01';
date('Y-m-01');
$dateto = date('Y-m-t');

?>

<main>
    <div class="container-fluidx">

        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"
            onclick="go(<?php echo $id; ?>)">

            <div class="card-body">
                <div class="page-top-box">
                    <div class="menu-icon"><i class="bi bi-coin"></i></div>
                    <div class="menu-text">Cashbook Manager</div>
                </div>
                <div class="page-sub-box">

                </div>
                <style>
                    .spcl {
                        font-size: 12px;
                        font-style: italic;
                    }
                </style>
                <table style="width:100%; display:none;">
                    <tr>
                        <td style="text-align:left;">
                            <div style="font-size:14px; color:white; text-align:center;">
                                <i class="bi bi-reception-2"></i> <span class="spcl">Under Build</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi bi-reception-4"></i> <span class="spcl">On Progress</span>
                                <br>
                                <i class="bi bi-exclamation-diamond-fill"></i> <span class="spcl">On Test</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi bi-check-circle-fill"></i> <span class="spcl">Done</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi-shield-fill-check"></i> <span class="spcl">Tested</span>

                                <?php echo $datefrom . '/' . $dateto; ?>
                            </div>
                        </td>
                        <td style="text-align:center; color:white;">
                            <div style="border:1px solid white; border-radius:5px; padding:5px;" onclick="iss();">
                                <i style="font-size:40px; color:white;" class="bi bi-plus"></i>
                                <br><span style="font-size:12px; font-style:italic;">Add a issue</span>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>


            <div class="card-body" style="display:none;" id="issueblock">
                <div style="color:white; background:var(--dark);">
                    <b>Add a Issue</b>
                    <br>
                    <span style="font-size:11px; font-style:italic;">Add a new issue or existing module issue</span>
                    <br>

                    <div style="text-align:left; padding-top:0px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i
                                    class="material-icons ico">reorder</i></span>
                            <select class="form-control" id="cause"
                                style="border:0; background:var(--dark); color:white; border-bottom:1px solid lightgray;">
                                <option>Select a type</option>
                                <option value="Student">Student </option>
                                <option value="Teacher">Teacher</option>
                                <option value="Columner Cashbook">Cash Book Related</option>
                                <option value="Bank Management">Bank Management</option>
                                <option value="Report">Reports</option>
                            </select>
                        </div>
                    </div>

                    <div style="text-align:left; padding-top:5px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i
                                    class="material-icons ico">description</i></span>
                            <input style="color:white;" type="text" id="descrip" name="descrip" class="form-control"
                                placeholder="Your Issue..." value="">
                        </div>
                    </div>
                    <div style="text-align:left; padding-top:5px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i
                                    class="material-icons ico">event</i></span>
                            <input style="color:white; background:var(--dark); border:0; border-bottom:1px solid white;"
                                type="date" id="date" name="date" class="form-control" placeholder="Deadline"
                                value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div style="padding:5px 60px;">
                        <button class="btn " style="background:white; color:var(--dark); border-radius:5px;"
                            onclick="addissue();;"><b>Add a issue</b></button>
                        <span id="settc"></span>
                    </div>
                </div>
            </div>

            <div id="settcc"></div>

        </div>



        <!--<div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk3();" >-->
        <!--  <img class="card-img-top"  alt="">-->
        <!--  <div class="card-body">-->
        <!--    <table style="">-->
        <!--        <tr>-->
        <!--            <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>-->
        <!--            <td>-->
        <!--                <h4>Administrative Setup</h4>-->
        <!--                <small>Class & Sections, Subjects, Teachers, Users etc.</small>-->
        <!--            </td>-->
        <!--        </tr>-->
        <!--    </table>-->
        <!--  </div>-->
        <!--</div>-->


        <?php

        $inr = 0;
        $exr = 0;
        $inw = 0;
        $exw = 0;
        for ($jj = 0; $jj < 2; $jj++) {

            if ($jj == 0) {
                $ssc = $sccode;
                $ttxx = 'Sanctioned Bill/Vouchers';
                $idin = 'in0';
                $idex = 'ex0';
            } else if ($jj == 1) {
                $ssc = $sccode * 10;
                $ttxx = 'Ignored Bill/Vouchers';
                $idin = 'in1';
                $idex = 'ex1';
                $datefrom = '2023-01-01';
                $dateto = $td;
            }
            ?>


            <div class="card-body">
                <div class="card" style="background:var(--lighter); border:0; color:var(--darker);" onclick="lnk30();">
                    <div
                        style=" width:80%; border-radius:35px; background:var(--darker); color:white; font-weight:bold; margin:8px auto; text-align:center; padding:8px;">
                        <?php echo $ttxx; ?></div>
                    <table
                        style="width:65%; margin:0 auto 15px; border-bottom:1px dashed var(--dark); font-size:13px; color:var(--darker);">
                        <tr>
                            <td>
                                <div id="<?php echo $idin; ?>"></div>
                            </td>
                            <td>
                                <div style="text-align:right;" id="<?php echo $idex; ?>"></div>
                            </td>
                        </tr>
                    </table>
                    <?php


                    $sql0 = "SELECT * FROM cashbook where sccode='$ssc' and date between '$datefrom' and '$dateto'  and entryby !='System-Auto' order by date desc, partid";
                    $result0wwrt = $conn->query($sql0);
                    if ($result0wwrt->num_rows > 0) {
                        while ($row0 = $result0wwrt->fetch_assoc()) {
                            $type = $row0["type"];
                            $partid = $row0["partid"];
                            $particul = $row0["particulars"];
                            $taka = $row0["amount"];
                            $date = $row0["date"];

                            $eby = $row0["entryby"];
                            $id = $row0["id"];

                            $sql0 = "SELECT * FROM financesetup where sccode='$sccode' and id='$partid'";
                            $result0wwrtx = $conn->query($sql0);
                            if ($result0wwrtx->num_rows > 0) {
                                while ($row0 = $result0wwrtx->fetch_assoc()) {
                                    $partxt = $row0["particulareng"] . ' / ' . $row0["particularben"];
                                }
                            }

                            if ($type == 'Income') {
                                $txtclr = 'seagreen';
                            } else {
                                $txtclr = 'Tomato';
                            }



                            ?>
                            <div class="box" style="color:<?php echo $txtclr; ?>; border-bottom: 1px solid var(--dark); ">
                                <div class="box-icon">
                                    <img onclick="progress(<?php echo $id; ?>);" class="sender"
                                        src="https://eimbox.com/androidapplicationversion/iimg/icon/<?php echo $partid; ?>.png" />
                                </div>
                                <div class="box-text">
                                    <div style="float:right;text-align:right; right:25px; position:absolute;">
                                        <div style="font-size:18px; font-weight:700; color:<?php echo $clr; ?>"><?php echo $taka; ?>
                                        </div>
                                    </div>
                                    <div style="font-size:10px; font-weight:700;"><?php echo $partxt; ?></div>
                                    <div class="box-title" style="width: calc(100% - 60px);"><?php echo $particul; ?></div>
                                    <div class="box-subtitle">
                                        <?php echo date('d/m/Y', strtotime($date)) . ' by <b>' . $eby . '</b>'; ?></div>
                                    <div style="height:10px;"></div>
                                </div>

                                <div>
                                    <br>

                                    <span id="ddx<?php echo $id; ?>">
                                        <button class="btn btn-success" onclick="delitem(<?php echo $id; ?>,2);">Accept</button>
                                        <button class="btn btn-danger" onclick="delitem(<?php echo $id; ?>,1);">Delete</button>
                                    </span>
                                </div>

                            </div>
                            <?php

                            if ($jj == 0) {
                                if ($type == 'Income') {
                                    $inr += $taka;
                                } else {
                                    $exr += $taka;
                                }
                            } else {
                                if ($type == 'Income') {
                                    $inw += $taka;
                                } else {
                                    $exw += $taka;
                                }
                            }
                        }
                    }


                    ?>


                </div>
            </div>



        <?php }


        ?>



        <!-------------------------------------------------------->








    </div>

</main>
<div style="height:52px;"></div>

<script>
    document.getElementById("in0").innerHTML = "Income : <b><?php echo $inr; ?></b>";
    document.getElementById("in1").innerHTML = "Income : <b><?php echo $inw; ?></b>";
    document.getElementById("ex0").innerHTML = "Expenditure : <b><?php echo $exr; ?></b>";
    document.getElementById("ex1").innerHTML = "Expenditure : <b><?php echo $exw; ?></b>";

    document.getElementById("cnt").innerHTML = "<?php echo $cnt; ?>";



    function iss() {
        document.getElementById("issueblock").style.display = 'block';
    }

    function go() {
        var cls = document.getElementById("classname").value;
        var sec = document.getElementById("sectionname").value;
        var sub = document.getElementById("subject").value;
        var assess = document.getElementById("assessment").value;
        var exam = document.getElementById("exam").value;
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess;
        if (cls == 'Six' || cls == 'Seven') {
            window.location.href = "markpibi.php" + tail;
        } else {
            window.location.href = "markentry.php" + tail;
        }
    }

    function lnk1() { window.location.href = "tools_allsubjects.php"; }
    function lnk2() { window.location.href = "pibiprocess.php"; }
    function lnk3() { window.location.href = "settings.php"; }
    function lnk4() { window.location.href = "transcriptselect.php"; }
    function lnk5() { window.location.href = "userlist.php"; }
    function lnk6() { window.location.href = "classes.php"; }
    function lnk7() { window.location.href = "transcriptselect.php"; }
    function lnk8() { window.location.href = "transcriptselect.php"; }
    function lnk31() { window.location.href = "accountsecurity.php"; }


</script>


<script>
    function fetchsection() {
        var cls = document.getElementById("classname").value;

        var infor = "user=<?php echo $rootuser; ?>&cls=" + cls;
        $("#sectionblock").html("");

        $.ajax({
            type: "POST",
            url: "fetchsection.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                $("#sectionblock").html(html);
            }
        });
    }
</script>

<script>
    function addissue() {
        var cause = document.getElementById("cause").value;
        var descrip = document.getElementById("descrip").value;
        var date = document.getElementById("date").value;

        var infor = "sccode=<?php echo $sccode; ?>&cause=" + cause + "&descrip=" + descrip + "&date=" + date + "&tail=0";
        $("#settc").html("");

        $.ajax({
            type: "POST",
            url: "saveissue.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#settc').html('<span class="">Adding your issue...</span>');
            },
            success: function (html) {
                $("#settc").html(html);
            }
        });
    }
</script>

<script>
    function progress(id) {
        var infor = "sccode=<?php echo $sccode; ?>&id=" + id + "&tail=1";
        $("#settcc").html("");

        $.ajax({
            type: "POST",
            url: "saveissue.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#settcc').html('<span class="">Adding your issue...</span>');
            },
            success: function (html) {
                $("#settcc").html(html);
            }
        });
    }
</script>

<script>
    function delitem(id, tail) {
        var infor = "sccode=<?php echo $sccode; ?>&id=" + id + "&tail=" + tail;
        $("#ddx" + id).html("");

        $.ajax({
            type: "POST",
            url: "delcashbook.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#ddx' + id).html('<span class="">Deleting....</span>');
            },
            success: function (html) {
                $("#ddx" + id).html(html);
            }
        });
    }
</script>



</body>

</html>