<?php
include 'inc.php';
include 'datam/datam-subject-list.php';

$class_schedule = array();
$sql00xgr = "SELECT * FROM classschedule where  sessionyear LIKE '%$sy%' and sccode='$sccode'   order by period";
$result00xgr11tyu = $conn->query($sql00xgr);
if ($result00xgr11tyu->num_rows > 0) {
    while ($row00xgr = $result00xgr11tyu->fetch_assoc()) {
        $class_schedule[] = $row00xgr;
    }
}

?>

<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);">

            <div class="card-body page-top-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="menu-icon"><i class="bi bi-clock-fill"></i></div>
                            <div class="menu-text"> My Class Schedule </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <?php if ($userlevel == 'Teacher' || $userlevel == 'Asstt. Teacher' || $userlevel == 'Class Teacher') {
            $bar = date('w');
            $sql00xgr = "SELECT * FROM clsroutine where  sessionyear LIKE '%$sy%' and sccode='$sccode' and tid='$userid' and wday='$bar'  order by period, classname, sectionname , subcode";
            $result00xgr11 = $conn->query($sql00xgr);
            if ($result00xgr11->num_rows > 0) {
                while ($row00xgr = $result00xgr11->fetch_assoc()) {
                    $clsname = $row00xgr["classname"];
                    $secname = $row00xgr["sectionname"];
                    $period = $row00xgr["period"];
                    $wday = $row00xgr["wday"];
                    $subcode = $row00xgr["subcode"];

                    $sub_ind = array_search($subcode, array_column($datam_subject_list, 'subcode'));
                    $sname_eng = $datam_subject_list[$sub_ind]['subject'];
                    $sname_ben = $datam_subject_list[$sub_ind]['subben'];

                    $time_start = $time_end = '<span class="text-secondary">Undefined</span>';
                    $sch_ind = array_search($period, array_column($class_schedule, 'period'));
                    if ($sch_ind != '' || $sch_ind != NULL) {
                        $time_start = $class_schedule[$sch_ind]['timestart'];
                        $time_end = $class_schedule[$sch_ind]['timeend'];
                    }

                    ?>



                    <div class="card mb-1" style="background:var(--lighter); color:var(--darker); " onclick="lnk1();">
                        <img class="card-img-top" alt="">
                        <div class="card-body d-flex">
                            <div style="color:var(--darker); font-size:40px; padding-right:15px;">
                                <i class="bi bi-<?php echo $period; ?>-circle-fill"></i>
                            </div>
                            <div>
                                <div class="st-id"><?php echo $time_start . ' <i class="bi bi-arrow-right"></i> ' . $time_end; ?>
                                </div>
                                <div class="stname-eng"><?php echo $sname_eng; ?></div>
                                <div class="stname-ben"><?php echo $sname_ben; ?></div>

                                <div class="roll-no"><b><?php echo $clsname . ' (' . $secname . ')'; ?></b></div>
                            </div>

                        </div>
                    </div>












                </div>
            <?php }
            }
        } ?>


    </div>

</main>
<div style="height:52px;"></div>


<script>
    document.getElementById("cnt").innerHTML = "<?php echo $cnt; ?>";

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

    function lnk7() { window.location.href = "settingsinstituteinfo.php"; }
    function lnk6() { window.location.href = "settingsclass.php"; }

</script>


<script>
    function submit() {
        var id = 0;//document.getElementById("id").value;
        var cls = document.getElementById("cls").value;
        var sec = document.getElementById("sec").value;

        var infor = "rootuser=<?php echo $rootuser; ?>&cls=" + cls + "&sec=" + sec + "&id=" + id + "&action=1";
        $("#block").html("");

        $.ajax({
            type: "POST",
            url: "showroutine.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#block').html('<span class=""><center>Processing, Please Wait....</center></span>');
            },
            success: function (html) {
                $("#block").html(html);
            }
        });
    }


    function edit(id) {
        var sub = document.getElementById("subj" + id).value;
        var tid = document.getElementById("tid" + id).value;
        var iid = parseInt(document.getElementById("id" + id).innerHTML) * 1;

        var period = parseInt(document.getElementById("per" + id).innerHTML) * 1;
        var wday = parseInt(document.getElementById("wday" + id).innerHTML) * 1;

        var cls = document.getElementById("cls").value;
        var sec = document.getElementById("sec").value;

        var infor = "cls=" + cls + "&sec=" + sec + "&sub=" + sub + "&tid=" + tid + "&id=" + iid + "&period=" + period + "&wday=" + wday;

        $("#exe" + id).html("");

        $.ajax({
            type: "POST",
            url: "save-routine.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#exe' + id).html('<i class="bi bi-arrow-repeat"></i>');
            },
            success: function (html) {
                $("#exe" + id).html(html);
            }
        });
    }
</script>