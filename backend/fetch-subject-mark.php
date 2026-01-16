<?php
include('inc.back.php');

$sccode = $_POST['sccode'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];

if (isset($_POST['tid'])) {
    $tid = $_POST['tid'];
} else {
    $tid = $userid;
}

$sql0 = "SELECT subject FROM subsetup where sccode='$sccode' and classname='$cls' and sectionname='$sec'  and sessionyear='$sy' and tid='$tid' order by subject";
//echo $sql0;
if ($userlevel == 'Administrator' || $userlevel == 'Super Administrator') {
    $sql0 = "SELECT subject FROM subsetup where sccode='$sccode' and classname='$cls' and sectionname='$sec' and sessionyear LIKE '%$sy%'  order by subject";

} else {
    $sql0 = "SELECT subject FROM subsetup where sccode='$sccode' and classname='$cls' and sectionname='$sec' and sessionyear LIKE '%$sy%'  and tid='$userid' order by subject";

}
?>

<div class="form-group">
    <label class="lblx text-muted mt-3" for="">My Subjects</label>
    <select class="form-control" id="subject">
        <option></option>
        <?php
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $scode = $row0["subject"];

                $sql00 = "SELECT subject FROM subjects where subcode='$scode' and sccategory='$sctype'  ";
                $result00 = $conn->query($sql00);
                if ($result00->num_rows > 0) {
                    while ($row00 = $result00->fetch_assoc()) {
                        $sname = $row00["subject"];
                    }
                }
                echo '<option value="' . $scode . '">' . $sname . '</option>';
            }
        } ?>
    </select>
</div>