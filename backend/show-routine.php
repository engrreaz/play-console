<?php

include('inc.back.php');
include '../datam/datam-subject-list.php';

$rootuser = $_POST['rootuser'];
$id = $_POST['id'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];

//************************************************************************************************************************************************
//****************************************************************************************************************************************************************

for ($i = 1; $i <= 8; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($j == 1) {
            $day = 'Sunday';
        } else if ($j == 2) {
            $day = 'Monday';
        } else if ($j == 3) {
            $day = 'Tuesday';
        } else if ($j == 4) {
            $day = 'Wednesday';
        } else if ($j == 5) {
            $day = 'Thursday';
        }
        $sql00xgr = "SELECT * FROM clsroutine where sccode='$sccode' and sessionyear='$sy' and classname='$cls' and sectionname='$sec' and period = '$i' and wday='$j' order by period, wday";
        // echo $sql00xgr;
        $result00xgr = $conn->query($sql00xgr);
        if ($result00xgr->num_rows > 0) {
            while ($row00xgr = $result00xgr->fetch_assoc()) {
                $id = $row00xgr["id"];
                $subcode = $row00xgr["subcode"];
                $tidd = $row00xgr["tid"];
            }
        } else {
            $id = 0;
            $subcode = 0;
            $tidd = 0;
        }
        ?>
        <div class="card" style="background:var(--lighter); color:var(--darker);">
            <div class="card-body table-responsive">
                <table class="table">
                    <tr>
                        <td style="width:50px; font-size:24px; font-weight:bold; text-align:center;">
                            <?php if ($j == 1) {
                                echo $i . '<div class="st-id">Period</div>';
                            } ?>
                        </td>

                        <td style="display:none;" id="id<?php echo $i . $j; ?>"><?php echo $id; ?></td>
                        <td style="display:none;">Period : <span id="per<?php echo $i . $j; ?>"><?php echo $i; ?></span> Day :
                            <span id="wday<?php echo $i . $j; ?>"><?php echo $j; ?></span><?php echo $day; ?>
                        </td>
                        <td style="width:60px;">
                            <div class="st-id"><?php echo $day; ?></div>

                            <button class="btn btn-dark text-small p-1" onclick="same(<?php echo $i; ?>, <?php echo $j; ?>);"
                                id="same<?php echo $i . $j; ?>">Apply All</button>
                        </td>

                        <td>
                            <div class="">
                                <select class="form-control" id="subj<?php echo $i . $j; ?>"
                                    onchange="edit(<?php echo $i . $j; ?>);">
                                    <option value="">Select Subject</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM subjects where sccategory='$sctype' order by subcode";
                                    $result00xgr4 = $conn->query($sql00xgr);
                                    if ($result00xgr4->num_rows > 0) {
                                        while ($row00xgr = $result00xgr4->fetch_assoc()) {
                                            $scode = $row00xgr["subcode"];
                                            $subj = $row00xgr["subject"];
                                            if ($subcode == $scode) {
                                                $aa = 'selected';
                                            } else {
                                                $aa = '';
                                            }
                                            echo '<option value="' . $scode . '" ' . $aa . ' >' . $subj . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="">
                                <select class="form-control " id="tid<?php echo $i . $j; ?>"
                                    onchange="edit(<?php echo $i . $j; ?>);">

                                    <option value="">Select Teacher</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM teacher where sccode='$sccode' order by ranks, tid";
                                    $result00xgr4 = $conn->query($sql00xgr);
                                    if ($result00xgr4->num_rows > 0) {
                                        while ($row00xgr = $result00xgr4->fetch_assoc()) {
                                            $tid = $row00xgr["tid"];
                                            $tname = $row00xgr["tname"];
                                            if ($tidd == $tid) {
                                                $bb = 'selected';
                                            } else {
                                                $bb = '';
                                            }
                                            echo '<option value="' . $tid . '" ' . $bb . ' >' . $tname . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td class="pt-4" id="exe<?php echo $i . $j; ?>" >

                            <button class="btn btn-primary p-1" id="bbnt<?php echo $i . $j; ?>"
                                onclick="edit(<?php echo $i . $j; ?>);"><i class="bi bi-arrow-right-circle"></i></button>


                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php

        if ($j == 5) {
            echo '<div style="height:3px; background:var(--darker); "></div>';
        }
    }

}

?>

<script>
    function same(i, j) {
        var subj = document.getElementById("subj" + i + '1').value;
        var tid = document.getElementById("tid" + i + '1').value;
        if (j == 1) {
            var k;
            for (k = 1; k <= 5; k++) {
                document.getElementById("tid" + i + k).value = tid;
                document.getElementById("subj" + i + k).value = subj;
                var m = i * 10 + k;
                edit(m);
            }
        } else {
            document.getElementById("tid" + i + j).value = tid;
            document.getElementById("subj" + i + j).value = subj;
        }
    }
</script>