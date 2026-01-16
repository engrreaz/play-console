<?php
$datam_teacher_profile = array();
$sql00 = "SELECT * FROM teacher where  sccode='$sccode' order by sl, id";
$result00_teacher_datam = $conn->query($sql00);
if ($result00_teacher_datam->num_rows > 0) {
    while ($row00 = $result00_teacher_datam->fetch_assoc()) {
        $datam_teacher_profile[] = $row00;
    }
}