<?php

include('inc.back.php');
$sy = date('Y');
$id = $_POST['id'];


$sql00 = "SELECT * FROM areas where id='$id' ";
$result00 = $conn->query($sql00);
if ($result00->num_rows > 0) {
    while ($row00 = $result00->fetch_assoc()) {
        $cls = $row00["areaname"];
        $sec = $row00["subarea"];
        $clstid = $row00["classteacher"];
    }
}
// echo '<i style="font-size:24px;" class="bi bi-book"></i><br><b>Subject List</b>';

$sql0 = "SELECT * FROM subsetup where sccode='$sccode' and classname='$cls' and sectionname='$sec' and sessionyear='$sy' order by subject";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $subcode = $row0["subject"];
        $tid = $row0["tid"];
        if ($tid == $clstid) {
            $str = '<span style="color:red; font-size:20px;">*</span>';
        } else {
            $str = '';
        }

        $sql = "SELECT * FROM subjects where subcode='$subcode' and sccategory='$sctype' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sname = $row["subject"];
                $snameben = $row["subben"];
            }
        }

        $sql = "SELECT * FROM teacher where tid='$tid' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tname = $row["tname"];
                $position = $row["position"];
                $clr = 'seagreen';
            }
        } else {
            $tname = '<b>Not Assigned.</b>';
            $position = 'Please assigned a teacher.';
            $clr = 'red';
        }

        ?>

        <table style="width:80%; margin-left:20%;">
            <tr>
                <td>
                    <div style="font-size:16px; font-weight:bold; color:gray;"><?php echo $sname; ?></div>
                    <div style="font-size:14px; font-weight:500; color:gray;"><?php echo $snameben; ?></div>

                    <div style="font-size:15px; font-weight:400; color:<?php echo $clr; ?>;"><?php echo $tname; ?><small>,
                            <?php echo $position; ?></small><?php echo $str; ?></div>

                 <div class="menu-separator mt-2 mb-2"></div>
                </td>
            </tr>
        </table>


    <?php }
}

?>