<?php
include 'inc.php';

$disp0 = $disp1 = $disp2 = $disp3 = $disp4 = $disp5 = 'none';
$icol0 = $icol1 = $icol2 = $icol3 = $icol4 = $icol5 = 'var(--dark)';
$pos = 0;
if (isset($_GET['pos'])) {
    $pos = $_GET['pos'];
}
if ($pos > 5) {
    $pos = 0;
}
if ($pos < 0) {
    $pos = 0;
}

switch ($pos) {
    case 0;
        $disp0 = 'block';
        $icol0 = 'white';
        break;
    case 1;
        $disp1 = 'block';
        $icol1 = 'white';
        break;
    case 2;
        $disp2 = 'block';
        $icol2 = 'white';
        break;
    case 3;
        $disp3 = 'block';
        $icol3 = 'white';
        break;
    case 4;
        $disp4 = 'block';
        $icol4 = 'white';
        break;
    case 5;
        $disp5 = 'block';
        $icol5 = 'white';
        break;
    default:
        $disp0 = 'block';
        $icol0 = 'white';
        break;
}

if (isset($_GET['cls'])) {
    $cls = $_GET['cls'];
} else {
    $cls = '';
}

?>

<script>
    function call_sec(sec) {
        var infor = "sec=" + sec;
        // alert(infor);
        $("#sec_name").html("");
        $.ajax({
            type: "POST",
            url: "backend/fetch-section-name.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#sec_name').html('<span class="text-small">Fetching...</span>');
            },
            success: function (html) {
                $("#sec_name").html(html);

                camp = sessionStorage.getItem("param-2");
                document.getElementById("param_2").value = camp;
            }
        });
    }
</script>


<main>
    <div class="containerx" style="width:100%;">



        <div class="card text-center">
            <div class="card-body page-top-box">
                <div class="menu-icon"><i class="bi bi-send-fill"></i></div>
                <div class="menu-text">Send SMS</div>
            </div>
            <div class="card-body page-info-box  ">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">

                        <div class="row" style="font-size:20px;">
                            <div class="col-2 text-center " style="color:<?php echo $icol0; ?>"><i
                                    class="bi bi-chat-fill"></i></div>
                            <div class="col-2 text-center " style="color:<?php echo $icol1; ?>"><i
                                    class="bi bi-megaphone-fill"></i></div>
                            <div class="col-2 text-center " style="color:<?php echo $icol2; ?>"><i
                                    class="bi bi-chat-right-text-fill"></i></div>
                            <div class="col-2 text-center " style="color:<?php echo $icol3; ?>"><i
                                    class="bi bi-file-post"></i></div>
                            <div class="col-2 text-center " style="color:<?php echo $icol4; ?>"><i
                                    class="bi bi-send-fill"></i></div>
                            <div class="col-2 text-center " style="color:<?php echo $icol5; ?>"><i
                                    class="bi bi-check-circle-fill"></i></div>
                        </div>
                    </div>
                    <div class="col-1"></div>

                </div>

            </div>
        </div>



        <div class="card text-center gg" style="background:var(--lighter);">
            <div class="card-body text-small" id="step-block-0" style="display:<?php echo $disp0; ?>">
                <div class="page-icon mb-2" style="color:var(--normal);">
                    <i class="bi bi-chat-fill"></i>
                </div>
                <div class="page-title">Message Center</div>
                Welcome to EIMBox Messaging System.
                <br>To start sending message to your audiance
                <br>
                press <i class="bi bi-caret-right-fill text-danger"> </i> button to continue.



            </div>
            <div class="card-body text-start" id="step-block-1" style="display:<?php echo $disp1; ?>">

                <div class="page-icon mb-2" style="color:var(--normal);">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div class="page-title"> Campaign </div>

                <div class="form-group">
                    <label class="text-small ps-1" for="camp_name">Campaign Name</label>
                    <input type="text" class="form-control" id="camp_name" onkeyup="store_data(1);" />
                </div>

            </div>
            <div class="card-body text-start" id="step-block-2" style="display:<?php echo $disp2; ?>">
                <div class="page-icon mb-2" style="color:var(--normal);">
                    <i class="bi bi-chat-right-text-fill"></i>
                </div>
                <div class="page-title"> Message </div>
                <label class="text-small ps-1" for="sms_text">Compose Your Message</label>

                <textarea class="form-control" onkeyup="count_len(); store_data(2);" id="sms_text"></textarea>
                <div class=" ps-1 mt-2 d-flex text-small">
                    <div class="text-small">Character Count : </div>
                    <div id="count_len">0</div>
                    <div class="text-small ms-3">SMS Count : </div>
                    <div id="count_qnt">0</div>
                </div>
                <div class="text-small text-info ps-1">
                    You may compose a message with maximum 500 characters.
                    You may also add some built-in variables. Message length will calculate with variables value.
                </div>
                <button class="btn btn-warning text-small  mt-2" onclick="var_list();">Available Built-In Variables
                    List</button>

                <div class="responsive  mt-2" id="var_list" style="display:none;">
                    <table class="table table-condensed text-small">
                        <thead>
                            <tr>
                                <th>Variable</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>STNAME_ENG</td>
                                <td>return Student Name</td>
                            </tr>
                            <tr>
                                <td>STID</td>
                                <td>return Student ID</td>
                            </tr>
                            <tr>
                                <td>MOBILE_NUMBER</td>
                                <td>return Gruadian's Mobile Number</td>
                            </tr>
                            <tr>
                                <td>DUES</td>
                                <td>return Student Dues</td>
                            </tr>
                            <tr>
                                <td>ATTND_DAYS</td>
                                <td>return Student Attend Count</td>
                            </tr>

                        </tbody>

                    </table>

                    <div class="text-small text-warning">
                        Alway use a square bracket around variables name.<br>
                        i.e. use <b>[STNAME_ENG]</b> not STNAME_ENG
                    </div>
                </div>


            </div>
            <div class="card-body text-start" id="step-block-3" style="display:<?php echo $disp3; ?>">
                <div class="page-icon mb-2" style="color:var(--normal);">
                    <i class="bi bi-file-post-fill"></i>
                </div>
                <div class="page-title">Audience</div>

                <div class="text-small text-dark">Selected your desire audience to send messages.</div>

                <div class="row ">
                    <div class="col-4">
                        <label class="text-small ps-1" for="param1"> Category </label>
                        <select class="form-control" id="param1" onchange="store_data(3);">
                            <option value=""></option>
                            <option value="Student">Student</option>
                        </select>
                    </div>

                    <div class="col-4">
                        <label class="text-small ps-1" for="param_2"> Filter Level 1 </label>
                        <select class="form-control" id="param_2" onchange="store_data(4);">
                            <option value=""></option>
                            <?php
                            $sql0 = "SELECT areaname FROM areas where sccode = '$sccode' and sessionyear LIKE '%$sy%' and user='$rootuser' group by areaname order by areaname;";
                            //    echo $sql0;
                            $result0rta = $conn->query($sql0);
                            if ($result0rta->num_rows > 0) {
                                while ($row0 = $result0rta->fetch_assoc()) {
                                    $aname = $row0["areaname"];
                                    // echo $aname . '<br><br><br>';
                                    echo '<option value="' . $aname . '">' . $aname . '</option>';
                                }
                            }

                            ?>



                        </select>
                    </div>
                    <div class="col-4" id="sec_name">
                        <label class="text-small ps-1" for="param3"> Filter Level 2</label>
                        <select class="form-control" id="param3" onchange="store_data(5);">
                            <option value=""></option>
                            <?php
                            $sql0x = "SELECT subarea FROM areas where sccode = '$sccode' and sessionyear LIKE '%$sy%' and user='$rootuser' and areaname='$cls' group by subarea order by subarea;";
                            $result0rtagx = $conn->query($sql0x);
                            if ($result0rtagx->num_rows > 0) {
                                while ($row0x = $result0rtagx->fetch_assoc()) {
                                    $anamex = $row0x["subarea"];
                                    // echo $anamex . '<br><br><br>';
                                    echo '<option value="' . $anamex . '">' . $anamex . '</option>';
                                }
                            }

                            ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="card-body text-start" id="step-block-4" style="display:<?php echo $disp4; ?>">

                <div class="page-icon mb-2" style="color:var(--normal);">
                    <i class="bi bi-send-fill"></i>
                </div>
                <div class="page-title"> Review </div>
                <div class="text-small text-dark pb-3">Review your campaign. To send message press <b>Send SMS</b></div>

                <div class="row">
                    <div class="col-4">
                        <div class="fw-bold" id="counta">-</div>
                        <div class="text-small">Count SMS</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold" id="audiencea">-</div>
                        <div class="text-small">Audience</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold" id="totala">-</div>
                        <div class="text-small">Total SMS</div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-4">
                        <div class="fw-bold text-danger" id="costa">-</div>
                        <div class="text-small">Total Cost</div>
                    </div>
                    <div class="col-8">
                        <div class="fw-bold" id="uid">-</div>
                        <div class="text-small">Campaing ID</div>
                    </div>
                </div>

                <div class="row mt-3 mb-4">
                    <div class="col-12">
                        <div class="text-small">Sample Message</div>
                        <div class="fw-bold text-small" id="samplea">-</div>
                    </div>
                </div>







                <div id="fetch-data"> </div>
            </div>
            <div class="card-body" id="step-block-5" style="display:<?php echo $disp5; ?>">
                <div class="page-icon mb-2" style="color:var(--normal); font-size:60px;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
                <div class="page-title"> Sending Messages</div>
                <div class="text-small text-success pb-4">Campaign Status</div>

                <div id="fetch-data-final"></div>
            </div>


            <div class="card-body mt-1" id="prevnext">
                <?php
                if ($pos >= 4) {
                    $next_d = 'disabled';
                } else {
                    $next_d = '';
                }
                $prev_d = '';

                ?>

                <button class="btn btn-rounded btn-outline-dark text-small me-3" onclick="prev(<?php echo $pos; ?>);"
                    <?php echo $prev_d; ?>>
                    <i class="bi bi-caret-left-fill"></i> </button>


                <button class="btn btn-rounded btn-outline-dark text-small ms-3" onclick="next(<?php echo $pos; ?>);"
                    <?php echo $next_d; ?>>
                    <i class="bi bi-caret-right-fill"></i> </button>



            </div>


        </div>
    </div>

</main>
<input type="text" id="param2" hidden />
<input type="text" id="param3" hidden />
<div style="height:52px;"></div>


<script>
    function prev(id) {
        id--;
        window.location.href = 'sms-send.php?pos=' + id;
    }

    function next(id) {
        id++;
        window.location.href = 'sms-send.php?pos=' + id;
    }

    function var_list() {
        document.getElementById("var_list").style.display = 'block';
    }

</script>

<script>
    function call_back_data(uid) {

        var camp_name = document.getElementById("camp_name").value;
        var sms_text = document.getElementById("sms_text").value;
        var param1 = document.getElementById("param1").value;
        var param2 = document.getElementById("param_2").value;
        var param3 = document.getElementById("param3").value;
        // alert('x');
        var param4 = '';
        var param5 = '';

        var infor = "sccode=<?php echo $sccode; ?>&uid=" + uid + "&camp=" + camp_name + "&sms=" + sms_text + "&p1=" + param1 + "&p2=" + param2 + "&p3=" + param3 + "&p4=" + param4 + "&p5=" + param5 + "&pos=<?php echo $pos; ?>";
        // alert(infor);
        $("#fetch-data").html("");

        $.ajax({
            type: "POST",
            url: "backend/fetch-sms-campaing.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#fetch-data').html('<span class="text-small">Fetching...</span>');
            },
            success: function (html) {
                $("#fetch-data").html(html);

                document.getElementById("counta").innerHTML = document.getElementById("aa").innerHTML;
                document.getElementById("audiencea").innerHTML = document.getElementById("bb").innerHTML;
                var miss = document.getElementById("cc").innerHTML;
                if (miss != '0') {
                    document.getElementById("audiencea").innerHTML += ' <small class="text-danger">(missing : ' + miss + ')</small>';
                }
                document.getElementById("totala").innerHTML = document.getElementById("cc").innerHTML;
                document.getElementById("costa").innerHTML = document.getElementById("dd").innerHTML;
                document.getElementById("samplea").innerHTML = document.getElementById("ee").innerHTML;
                // window.location.href = 'index.php';
            }
        });
    }

</script>

<script>
    function send_bundle_sms(uid) {

        var camp_name = document.getElementById("camp_name").value;
        var sms_text = document.getElementById("sms_text").value;
        var param1 = document.getElementById("param1").value;
        var param2 = document.getElementById("param_2").value;
        var param3 = document.getElementById("param3").value;
        var param4 = '';
        var param5 = '';

        var infor = "sccode=<?php echo $sccode; ?>&uid=" + uid + "&camp=" + camp_name + "&sms=" + sms_text + "&p1=" + param1 + "&p2=" + param2 + "&p3=" + param3 + "&p4=" + param4 + "&p5=" + param5 + "&pos=<?php echo $pos; ?>";
        // alert(infor);
        $("#fetch-data-final").html("");

        $.ajax({
            type: "POST",
            url: "backend/fetch-sms-campaing-final.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#fetch-data-final').html('<span class="text-small">Fetching...</span>');
            },
            success: function (html) {
                $("#fetch-data-final").html(html);

                // window.location.href = 'index.php';

                sessionStorage.setItem("camp-name", '');
                sessionStorage.setItem("uid", '');
                sessionStorage.setItem("sms-text", '');
                sessionStorage.setItem("param-1", '');
                sessionStorage.setItem("param-2", '');
                sessionStorage.setItem("param-3", '');

                // prev(1);

            }
        });
    }


</script>



<script>
    function count_len() {
        var elem1 = document.getElementById("sms_text");
        var elem2 = document.getElementById("count_len");
        var elem3 = document.getElementById("count_qnt");
        var leng = elem1.value.length;
        elem2.innerHTML = leng;
        elem3.innerHTML = Math.ceil(leng / 159);

    }

    function store_data(params) {
        if (params == 1) {
            var camp = document.getElementById("camp_name").value;

            var uid = sessionStorage.getItem("uid");
            if (uid == '' || uid == null) {
                uid = '<?php echo uniqid(); ?>';
                // alert(uid);
                sessionStorage.setItem("uid", uid);
            }
            // alert(camp);
            sessionStorage.setItem("camp-name", camp);
        } else if (params == 2) {
            var camp = document.getElementById("sms_text").value;
            // alert(camp);
            sessionStorage.setItem("sms-text", camp);
        } else if (params == 3) {
            var camp = document.getElementById("param1").value;
            window.location.reload();
            // alert(camp);
            sessionStorage.setItem("param-1", camp);
        } else if (params == 4) {
            var campf = document.getElementById("param_2").value;
            sessionStorage.setItem("param-2", campf);

            window.location.href = window.location.href + '&cls=' + campf;
        } else if (params == 5) {
            var camp = document.getElementById("param3").value;
            sessionStorage.setItem("param-3", camp);


            // camp = sessionStorage.getItem("param-3");
            // document.getElementById("param3").value = camp;
            // alert(camp);
            window.location.reload();
        }
    }

    function send_final() {
        alert("Are you Sure?");
        next(4);
    }



    var camp = campx = '';;
    camp = sessionStorage.getItem("camp-name");
    document.getElementById("camp_name").value = camp;
    campx = sessionStorage.getItem("uid");
    if (campx == '') {
        document.getElementById("uid").innerHTML = '<div class="bg-danger text-white btn text-small">Campaign title is missing.</div>';
        // $('#send_btn').prop('disabled', true);

        //   document.getElementById("send_btn").style.display = "block";
        //   document.getElementById("msg").innerHTML = "Your must enter a campaign name.";
        // alert('...');
    } else {
        document.getElementById("uid").innerHTML = campx;
    }

    camp = sessionStorage.getItem("sms-text");
    document.getElementById("sms_text").value = camp;
    count_len();
    camp = sessionStorage.getItem("param-1");
    document.getElementById("param1").value = camp;

    camp = sessionStorage.getItem("param-2");
    document.getElementById("param_2").value = camp;
    call_sec(camp);

    camp = sessionStorage.getItem("param-3");
    document.getElementById("param3").value = camp;
    // alert(camp);

    call_back_data(campx);
    // alert(campx);
    if (<?php echo $pos; ?> == 5) {
        // alert('Ready to send message');
        send_bundle_sms(campx);
    }

</script>