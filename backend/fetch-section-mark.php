<?php

include('inc.back.php');

$user = $_POST['user'];
$cls = $_POST['cls'];

?>


<div class="form-group">
  <label class="lblx text-muted mt-3" for="">Section/Group</label>
  <select class="form-control" id="sectionname" onchange="fetchsubject();">
    <option></option>
    <?php
    $sql0 = "SELECT subarea as sec FROM areas where sessionyear LIKE '%$sy%' and user='$rootuser' and areaname='$cls' group by subarea order by idno";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
      while ($row0 = $result0->fetch_assoc()) {
        $sec = $row0["sec"];
        echo '<option value="' . $sec . '">' . $sec . '</option>';
      }
    } ?>
  </select>
</div>