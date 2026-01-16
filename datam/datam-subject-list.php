<?php
$datam_subject_list = array();
$sql00 = "SELECT * FROM subjects where  sccategory='$sctype' ";
// echo $sql00;
$result00datam_subject_list  = $conn->query($sql00);
if ($result00datam_subject_list ->num_rows > 0) {
    while ($row00 = $result00datam_subject_list ->fetch_assoc()) {
        $datam_subject_list[] = $row00;
    }
}