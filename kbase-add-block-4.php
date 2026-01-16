<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy=date('Y');
	include ('../db.php');;
	$sub = $_POST['subject'];;  
	$mod = $_POST['module'];;  
	$sec = $_POST['section'];;  
	
	$add4 = $_POST['add4'];;  
	
	if($add4 != ""){
	    $query33 ="insert into kbasestep (id, sl, kbase1, kbase2, kbase3, step, createdate, modifieddate) values
            (NULL, 0, '$sub', '$mod', '$sec', '$add4', '$dt', '$dt');";
    	$conn->query($query33); echo $query33;
	}
?>

<select class="form-select form-select-md "  onchange="block5();"  id="step" aria-label="form-select-lg example" style=" background:var(--lighter);">
    <option value="" selected>Select Section</option>
    <?php
    $sql0 = "SELECT * FROM kbasestep where kbase1='$sub' and kbase2='$mod' and kbase3='$sec'  order by id";
  
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) 
    {while($row0 = $result0->fetch_assoc()) { 
        $id = $row0["id"]; $title = $row0["step"]; 
        echo '<option value="' . $id . '">' . $title . '</option>';
    }}
    ?>
</select>