<?php
$datam_st_profile = array();
$sql00 = "SELECT * FROM students where  sccode='$sccode' ";
$result00 = $conn->query($sql00);
if ($result00->num_rows > 0) {
    while ($row00 = $result00->fetch_assoc()) {
        $datam_st_profile[] = $row00;
    }
}