<?php
include 'inc.php';
$dnx = $chh = '';
$stid = $_GET['id'];
if (isset($_GET['edit'])) {
    $edit = $_GET['edit'];
} else {
    $edit = 0;
}


$sql5 = "SELECT id,  itemcode, splitable FROM financesetup where sessionyear LIKE '%$sy%' and sccode='$sccode'  order by id";
// echo $sql5; 
$result5r = $conn->query($sql5);
if ($result5r->num_rows > 0) {
    while ($row5 = $result5r->fetch_assoc()) {
        $finset[] = $row5;
    }
}
?>

<style>
    input[type="checkbox"] {
        transform: scale(1.5);
        margin: 5px;
    }

    input {
        font-weight: bold;
    }

    input[type=checkbox]:checked {
        background-color: var(--dark) !important;
        color: #ffffff !important;
        border: 1px solid var(--dark);
    }
</style>

<main>

    <?php
    $cnt = 0;
    $sql0 = "SELECT * FROM sessioninfo where sessionyear LIKE '%$sy%'  and sccode='$sccode' and stid='$stid'";
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
            $ccc = $row0["classname"];
            $sss = $row0["sectionname"];
            $lastpr = $row0["lastpr"];
            $ssyy = $row0["sessionyear"];

            $sector = $row0["sector"];
            $rate = $row0["rate"];

            include 'component/student-image-path.php';

            $sql00 = "SELECT * FROM students where  sccode='$sccode' and stid='$stid' LIMIT 1";
            $result00 = $conn->query($sql00);
            if ($result00->num_rows > 0) {
                while ($row00 = $result00->fetch_assoc()) {
                    $neng = $row00["stnameeng"];
                    $nben = $row00["stnameben"];
                    $vill = $row00["previll"];
                    $mobileno = $row00["guarmobile"];
                }
            }
        }
    }


    $sql00x = "SELECT prno FROM stpr where  sccode='$sccode' and stid='$stid' and sessionyear LIKE '%$sy%' order by prno DESC LIMIT 1";
    // echo  $sql00x;
    $result00x = $conn->query($sql00x);
    if ($result00x->num_rows > 0) {
        while ($row00x = $result00x->fetch_assoc()) {
            $lastpr = $row00x["prno"];
        }
    } else {
        $lastpr = 0;
    }

    

    if ($lastpr > 0) {
        $prno = $lastpr + 1;
        $aa = $prno % 100;
        $prno = ($SY % 100) * 1000000 + ($stid % 10000) * 100 + $aa;
    } else {
        $prno = ($SY % 100) * 1000000 + ($stid % 10000) * 100 + 1;
    }

// echo $prno;

    if ($userlevel == 'Administrator' or $userlevel == 'Super Administrator') {
        $dtds = '';
    } else {
        $dtds = 'disabled';
    }
    ?>

    <!-- Modal Structure -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Split Window</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    Enter the amount to be split from the selected item.<br>

                    <input type="text" class="form-control" id="spltid" placeholder="Enter ID" value="" hidden>
                    <input type="text" class="form-control" id="spltamtpre" placeholder="Enter Amount" value="" hidden>
                    <input type="text" class="form-control" id="spltamt" placeholder="Enter Amount" value="">
                    <span class="text-muted text-small">The remaining amount will stay as dues in the original
                        item.</span>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="mybtn" onclick="splitable();">Split Now</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="fineModal" tabindex="-1" aria-labelledby="fineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="fineModalLabel">Add Fine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fine_amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="fine_amount" placeholder="Enter Amount" value="">
                    </div>
                    <div class="mb-3">
                        <label for="fine_note" class="form-label">Note</label>
                        <textarea class="form-control" id="fine_note" rows="3" placeholder="Enter a note (optional)"></textarea>
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="addFineBtn" onclick="saveFine();">Add Fine</button>
                </div>
            </div>
        </div>
    </div>


    <div class="containerx-fluid">
        <div style="position:relative; z-index:99; width:100%;">

            <div class="card text-start page-top-box ">
                <div class="card-body">
                    <table width="100%" style="color:white;">
                        <tr>
                            <td>
                                <div class="menu-icon"><i class="bi bi-coin"></i></div>
                                <div class="menu-text"> Payments Collection </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card text-start page-info-box ">
                <div class="card-body">
                    <table width="100%" style="color:white;">
                        <tr>
                            <td colspan="2">
                                <div class="d-flex pt-2 pb-2">
                                    <img class="st-pic-normal" src="<?php echo $pth; ?>" />

                                    <div class="ms-3 flex-grow-1">
                                        <i class="bi bi-plus-circle text-white menu-icon float-end ps-3"
                                            onclick="splitable2();"></i>
                                        <i class="bi bi-pencil-square text-white menu-icon float-end"
                                            onclick="goedit();"></i>
                                        <div class="stname-eng"><?php echo $neng; ?></div>
                                        <div class="stname-ben"><?php echo $nben; ?></div>
                                        <div class="float-end" id="history"></div>

                                        <div class="st-id">Id # <?php echo $stid; ?></div>

                                        <div class="roll-no">Roll # <?php echo $rollno; ?></div>
                                        <div class="roll-no">class : <b><?php echo strtoupper($ccc);
                                        ; ?></b> <i class="bi bi-arrow-right-circle-fill"></i>
                                            <b><?php echo strtoupper($sss); ?></b>
                                        </div>

                                        <div class="mt-3 d-block col-12">
                                            <div class="col-12">
                                                <div style="font-size:36px; font-weight:700; line-height:20px;"
                                                    id="cnt">
                                                    0.00
                                                </div>
                                                <div
                                                    style="color: var(--light); font-size:12px; font-weight:400; font-style:italic; line-height:30px;">
                                                    Total Dues</div>
                                            </div>

                                            <div id="btnblock"></div>
                                            <div class="col-12 text-right">

                                                <input type="text" class="text-danger" id="mylastpr" value="23272003"
                                                    hidden />
                                                <button class="btn btn-secondary w-100 mt-2 text-small text-right "
                                                    onclick="epos();">
                                                    <span class="float-start"><i class="bi bi-printer-fill"></i></span>
                                                    <span class="float-end">Print Last PR (POS)</span>

                                                </button>
                                                <div id="eposlink" style="display:block;"></div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="card text-left" style="background:var(--dark); color:var(--lighter);border-radius:0;"
                onclick="gol(<?php echo $id; ?>)" hidden>

                <div class="card-body">
                    <table width="100%" style="color:var(--lighter);">



                        <tr>

                        </tr>
                        <tr>
                            <td>






                            </td>
                            <td style="text-align:right;">
                                <div style="font-size:30px; font-weight:700; line-height:20px;" id="cnt"></div>
                                <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">
                                    Total Dues</div>

                                <br>
                                <?php if ($edit == 0) { ?>
                                    <button class="btn btn-danger text-right" onclick="edit(<?php echo $stid; ?>);">Modify
                                        Setup</button>
                                    <?php $d = '';
                                } else { ?>
                                    <button class="btn btn-info text-right" onclick="editn(<?php echo $stid; ?>);">Back
                                        To Normal</button>
                                    <?php $d = 'disabled';
                                } ?>

                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div style="height:2px;"></div>


            <div class="card text-center bg-white text-dark rounded-0">
                <div class="card-body">
                    <!-- Receipt Number & Pay Amount -->
                    <div class="row g-3 align-items-center">
                        <div class="col-6">
                            <label for="prno" class="form-label fs-6">Receipt Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-earmark-post"></i></span>
                                <input type="text" class="form-control text-center" id="prno"
                                    value="<?php echo $prno; ?>" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="amt" class="form-label fs-6">Pay Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-coin"></i></span>
                                <input type="number" id="amt" class="form-control text-end fw-bold" value="0"
                                    style="font-size:16px; color:var(--dark);" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Date & Pay Button -->
                    <div class="row g-3 align-items-center">
                        <div class="col-6">
                            <label for="prdate" class="form-label fs-6 text-tsmall">Payment Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" class="form-control text-center" id="prdate"
                                    value="<?php echo date('Y-m-d'); ?>" <?php echo $dtds; ?>>
                            </div>
                        </div>
                        <div class="col-6 d-grid pt-1">
                            <button class="btn btn-danger mt-4" onclick="save();" <?php echo $dnx; ?>>Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>


            <?php if ($edit == 1) { ?>

                <div class="card text-center" style="background:white; color:var(--darker);border-radius:0;">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <select class="form-control" id="sector"
                                            onchange="modsector(<?php echo $stid; ?>,0);">
                                            <option value="" <?php if ($sector == '') {
                                                echo 'selected';
                                            } ?>></option>
                                            <option value="Scholarship" <?php if ($sector == 'Scholarship') {
                                                echo 'selected';
                                            } ?>>Scholarship</option>
                                            <option value="Stipend" <?php if ($sector == 'Stipend') {
                                                echo 'selected';
                                            } ?>>
                                                Stipend</option>
                                            <option value="Poor" <?php if ($sector == 'Poor') {
                                                echo 'selected';
                                            } ?>>Poor
                                            </option>
                                            <option value="On Request" <?php if ($sector == 'On Request') {
                                                echo 'selected';
                                            } ?>>On Request</option>
                                        </select>
                                    </div>

                                </td>
                                <td style="width:10px;" rowspan="2"></td>
                                <td>
                                    <input type="number" id="rate" class="input form-control text-right"
                                        value="<?php echo $rate; ?>"
                                        style=" font-size:16px; color:var(--dark); font-weight:700; text-align:right;"
                                        disabled />
                                </td>

                            </tr>
                            <tr>
                                <td class="lbl">Student Category</td>
                                <td class="lbl">Applying Rate (%)</td>
                            </tr>

                        </table>
                        <div id="upd"></div>
                    </div>
                </div>

            <?php } ?>




        </div>
        <div style="height:8px;"></div>

        <!--
            <div style="height:400px;"></div>
            <?php if ($edit == 1) {
                echo '<div style="height:120px;"></div>';
            } ?>
            -->


        <?php
        $cnt = 0;
        $tamt = 0;
        $month = date('m');
        // $month = 12;
        $sql0 = "SELECT * FROM stfinance where sessionyear LIKE '%$sy%'  and sccode='$sccode' and stid='$stid' and month <='$month' and (dues>0 || particulareng='Fine' || particulareng='Misc') order by id";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $fid = $row0["id"];
                $stid = $row0["stid"];
                $peng = $row0["particulareng"];
                $pben = $row0["particularben"];
                $partid = $row0["partid"];
                $amount = $row0["amount"];
                $payable = $row0["payableamt"];
                $paid = $row0["paid"];
                $dues = $row0["dues"];

                $icode = $row0["itemcode"];
                $splitid = $row0["splitid"];


                $src = array_search($partid, array_column($finset, 'id'));
                // $upddate = $finset[$src][$upd];
                // $updtaka = $finset[$src][$ccc];
                $icode2 = $finset[$src]['itemcode'];
                $splt = 0;

                // echo $icode . '==' . $icode2 . '<br>';
                if ($icode == $icode2) {
                    $splt = $finset[$src]['splitable'];
                }


                $tamt = $tamt + $dues;

                if (strpos($peng, 'Admission') !== false) {
                    echo '<script>sell(' . $cnt . ');</script>';
                }





                $sql00 = "SELECT * FROM students where  sccode='$sccode' and stid='$stid' LIMIT 1";
                $result00 = $conn->query($sql00);
                if ($result00->num_rows > 0) {
                    while ($row00 = $result00->fetch_assoc()) {
                        $neng = $row00["stnameeng"];
                        $nben = $row00["stnameben"];
                        $vill = $row00["previll"];
                    }
                }

                if ($status == 0) {
                    $bgc = '--light';
                    $dsbl = ' disabled';
                    $gip = '';
                } else {
                    $bgc = '--lighter';
                    $dsbl = '';
                    $gip = 'checked';
                }
                //if($card == '1'){$qrc = '<img src="https://chart.googleapis.com/chart?chs=20x20&cht=qr&chl=http://www.students.eimbox.com/myinfo.php?id=5000&choe=UTF-8&chld=L|0" />';} else {$qrc = '';}
        

                ?>
                <div class="card text-center" style="background:var(<?php echo $bgc; ?>); color:var(--darker); border-radius:0;"
                    id="block<?php echo $stid; ?>" <?php echo $dsbl; ?>>
                    <img class="card-img-top" alt="">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td style="width:30px;">
                                    <span style="font-size:24px; font-weight:700;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="rex<?php echo $cnt; ?>"
                                                style=" " onclick="sel(<?php echo $cnt; ?>);" <?php echo $chh; ?>>
                                        </div>
                                    </span>

                                </td>
                                <td style="text-align:left; padding-left:5px;" onclick="sell(<?php echo $cnt; ?>);">
                                    <div style="display:none;" class="a" id="fid<?php echo $cnt; ?>"><?php echo $fid; ?></div>
                                    <div class="stname-eng" id="peng<?php echo $cnt; ?>"><?php echo $peng; ?></div>
                                    <div class="stname-ben" id="pben<?php echo $cnt; ?>"><?php echo $pben; ?></div>
                                </td>
                                <td style="text-align:right;">
                                    <?php
                                    if ($splt == 1) {
                                        if ($splitid > 0) {

                                            ?>
                                            <span class="btn text-primary m-0 pl-1 pr-1 fs-3"
                                                onclick="mergerow(<?php echo $fid; ?>, <?php echo $splitid; ?>, 2);"
                                                title="This item is already split. Click to rollback.">
                                                <i class="bi bi-union"></i>
                                            </span>
                                            <?php
                                        } else {

                                            ?>
                                            <span class="btn text-secondary m-0  pl-1 pr-1 fs-3"
                                                onclick="splitable0(<?php echo $fid; ?>, <?php echo $dues; ?>);"
                                                title="This item is splitable">
                                                <i class="bi bi-subtract"></i>
                                            </span>
                                            <?php
                                        }

                                    }

                                    if (strpos($peng, 'FINE') !== false) {
                                        ?>
                                        <span class="btn text-danger m-0 pl-1 pr-1 fs-3"
                                            onclick="mergerow(<?php echo $fid; ?>, 0, 4);" title="Remove Fine">
                                            <i class="bi bi-trash3-fill"></i>
                                        </span>
                                        <?php
                                    }

                                    ?>
                                </td>
                                <td style="text-align:right; font-size:24px; font-weight:700; color:var(--darker); width:80px; "
                                    onclick="sell(<?php echo $cnt; ?>);" id="amt<?php echo $cnt; ?>"><?php echo $dues; ?></td>
                            </tr>
                        </table>

                        <?php if ($edit == 1 && strpos($peng, 'Tution') === false) {
                            if ($peng == "Fine" || $peng == "Misc Fee") {
                                $amount = 500;
                            }

                            ?>
                            <div id="ed<?php echo $fid; ?>" style="width:75%; padding-left:40px;" class="slidecontainer">
                                <input type="range" class="slider" style="width:100%;" id="rng<?php echo $cnt; ?>" min="0"
                                    max="<?php echo $amount; ?>" value="<?php echo $payable; ?>" step="10"
                                    onchange="modsector(<?php echo $cnt; ?>,<?php echo $fid; ?>);" />
                            </div>
                        <?php } ?>


                    </div>
                </div>


                <div style="height:2px;"></div>
                <?php
                $cnt++;
            }
        }

        ?>

        <input type="number" id="cntp" value="<?php echo $cnt; ?>" hidden />
        <input type="number" id="chk" value="0" hidden />

    </div>

</main>
<div style="height:52px;"></div>
<footer>
    <!-- place footer here -->
</footer>
<!-- Bootstrap JavaScript Libraries -->


<script>

    function more() {
        let val = document.getElementById("myswitch").checked;
        if (val == true) {
            $(".sele").show();
        } else {
            $(".sele").hide();
        }
    }

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

    function modsector(id, prt) {
        let sector = document.getElementById("sector").value;

        let rate; let infor;
        if (sector == '') { rate = 100; }
        else if (sector == 'Scholarship') { rate = 0; }
        else if (sector == 'Stipend') { rate = 0; }
        else if (sector == 'Poor') { rate = 0; }
        else if (sector == 'On Request') { rate = 50; }
        document.getElementById("rate").value = rate;

        if (prt == 0) {
            infor = "stid=" + id + "&sector=" + sector + "&rate=" + rate + "&prt=" + prt;
        } else {
            let tk = document.getElementById("rng" + id).value;
            document.getElementById("amt" + id).innerHTML = tk;
            infor = "stid=<?php echo $stid; ?>&fid=" + prt + "&tk=" + tk + "&prt=" + 1;
        }

        $("#upd").html("");

        $.ajax({
            type: "POST",
            url: "updfreefin.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#upd").html('.....');
            },
            success: function (html) {
                $("#upd").html(html);
            }
        });
    }



    function save() {

        let cnto = document.getElementById("cntp").value;
        cnto = parseInt(cnto) * 1;
        let chk = document.getElementById("chk").value;
        let prno = document.getElementById("prno").value;
        let prdate = document.getElementById("prdate").value;
        let aaa = document.getElementById("amt").value;
        if (aaa > 0) {
            let tail = "count=" + chk + "&stid=<?php echo $stid; ?>&rollno=<?php echo $rollno; ?>&cls=<?php echo $ccc; ?>&sec=<?php echo $sss; ?>&neng=<?php echo $neng; ?>&nben=<?php echo $nben; ?>&prno=" + prno + "&prdate=" + prdate + "&mobileno=<?php echo $mobileno; ?>";
            let run = 0;
            for (let x = 0; x < cnto; x++) {
                let ch = document.getElementById("rex" + x).checked;
                if (ch === true) {
                    let fid = document.getElementById("fid" + x).innerHTML;
                    let peng = document.getElementById("peng" + x).innerHTML;
                    let pben = document.getElementById("pben" + x).innerHTML;
                    let amt = document.getElementById("amt" + x).innerHTML;
                    tail = tail + "&fid" + run + "=" + fid + "&peng" + run + "=" + peng + "&pben" + run + "=" + pben + "&amt" + run + "=" + amt;
                    run++;
                }
            }

            var infor = tail;
            // alert(tail);

            $("#btnblock").html("");

            $.ajax({
                type: "POST",
                url: "backend/save-pr.php",
                data: infor,
                cache: false,
                beforeSend: function () {
                    $("#btnblock").html('<span class=""><center></span>');
                },
                success: function (html) {
                    $("#btnblock").html(html);
                    document.location.href = "finstudents.php?cls=<?php echo $ccc; ?>&sec=<?php echo $sss; ?>";
                }
            });
        }
    }
</script>

<script>
    document.getElementById("cnt").innerHTML = "<?php echo $tamt; ?>.00";
    function go(id) {
        window.location.href = "stfinancedetails.php?id=" + id;
    }
    function edit(id) {
        window.location.href = "stfinancedetails.php?id=" + id + "&edit=1";
    }
    function editn(id) {
        window.location.href = "stfinancedetails.php?id=" + id;
    }

    function sell(id) {
        let ch = document.getElementById("rex" + id).checked;
        if (ch === true) {
            document.getElementById("rex" + id).checked = false;
        } else {
            document.getElementById("rex" + id).checked = true;
        }
        sel(id);

    }


    function sel(id) {
        let ch = document.getElementById("rex" + id).checked;

        let amt = document.getElementById("amt" + id).innerHTML;
        amt = parseInt(amt) * 1;
        let amtt = document.getElementById("amt").value * 1;
        let chk = document.getElementById("chk").value * 1;

        if (ch === true) {
            //document.getElementById("rex"+id).checked = true;
            amtt = amtt + amt;
            chk++;
        } else {
            //document.getElementById("rex"+id).checked = false;
            amtt = amtt - amt;
            chk--
        }
        document.getElementById("amt").value = amtt;
        document.getElementById("chk").value = chk;
    }


    function goedit() {
        window.location.href = "st-payment-setup-indivisual.php?sec=<?php echo $sss; ?>&cls=<?php echo $ccc; ?>&year=<?php echo $sy; ?>&roll=<?php echo $rollno; ?>";

    }

    function hazar() {

        let cnt = document.getElementById("cntp").value;
        for (let x = 0; x < cnt; x++) {
            let tx = document.getElementById("peng" + x).innerHTML;
            if (tx.includes('Admission') || tx.includes('xScience') || tx.includes('Scout') ||
                tx.includes('xPoor') || tx.includes('Electricity') || tx.includes('Sport') ||
                tx.includes('Develop') || tx.includes('xLibrary') || tx.includes('xMilad') ||
                tx.includes('PF') || tx.includes('xRed') || tx.includes('xClean') || tx.includes('Library')) {
                sell(x);
            }
        }
    }

    // hazar();
</script>

<script>
    function splitable0(fid, amt) {
        event.stopPropagation();
        document.getElementById("spltid").value = fid;
        document.getElementById("spltamt").value = amt;
        document.getElementById("spltamtpre").value = amt;
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
        });
        myModal.show();
        const input = document.getElementById("spltamt");
        input.focus();
        input.select();
    }

    function splitable2() {
        event.stopPropagation();
        var myModal = new bootstrap.Modal(document.getElementById('fineModal'), {
            keyboard: false
        });
        myModal.show();
        const input = document.getElementById("fine_amount");
        input.focus();
        input.select();
    }

</script>
<script>

    function splitable() {

        var fid = document.getElementById("spltid").value;
        var amtpre = document.getElementById("spltamtpre").value * 1;
        var amt = document.getElementById("spltamt").value * 1;


        if (amt >= amtpre || amt <= 0 || amt == '') {
            alert('Invalid Amount');
            const input = document.getElementById("spltamt");
            input.focus();
            input.select();
            return;
        }

        var infor = "fid=" + fid + "&amt=" + amt + "&tail=1";
        // alert(infor);

        $("#history").html("");
        $.ajax({
            type: "POST",
            url: "backend/stfinance-item-split.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#history").html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#history").html(html);
                var modalEl = document.getElementById('exampleModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();


                window.location.reload();

            }
        });
    }




    function saveFine() {
        let stid = '<?php echo $stid; ?>';
        let amount = $('#fine_amount').val();
        let note = $('#fine_note').val();
        let session = '<?php echo $sy; ?>';
        let cls = '<?php echo $ccc; ?>';
        let sec = '<?php echo $sss; ?>';
        let roll = '<?php echo $rollno; ?>';

        if (!amount || amount <= 0) {
            alert('Fine amount required');
            return;
        }

        $.post('payments/save-fine.php', {
           session:session,
           stid: stid,
           roll:roll,
           cls: cls,
           sec: sec,
           amount: amount,
           note: note
        }, function (res) {
            if (res === 'OK') {
                $('#fineModal').modal('hide');
                showToast('success', 'Student Fine Added', 'Add Fine');
                setCookie('payment-stid', stid);
                setTimeout(function () {
                    window.location.reload();
                }, 800);
            } else {
                alert(res);
            }
        });
    }
</script>


<script>


    function mergerow(id, amt, tail) {
        event.stopPropagation();
        var infor = "fid=" + id + "&amt=" + amt + "&tail=" + tail;
        // alert(infor);

        $("#history").html("");
        $.ajax({
            type: "POST",
            url: "backend/stfinance-item-split.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#history").html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#history").html(html);


                window.location.reload();

            }
        });
    }



    function rollback(id, taka, tail) {
        var infor = "id=" + id + "&taka=" + taka + "&tail=" + tail;
        // alert(infor);
        $("#bbttnn" + id).html("");
        $.ajax({
            type: "POST",
            url: "backend/roll-back-st-finance-item.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#bbttnn" + id).html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#bbttnn" + id).html(html);
            }
        });
    }
</script>

<script>
    const button = document.getElementById("mybtn");
    const input = document.getElementById("spltamt");
    input.addEventListener("keyup", function (event) {
        if (event.key === "Enter") {
            button.click();
        }
    });
</script>