<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy=date('Y');
	include ('../db.php');;
	$sub = $_POST['subject'];;  
	$add2 = $_POST['add2'];;  
	
	if($add2 != ""){
	    $query33 ="insert into kbase2 (id, sl, kbase1, title, createdate, modifieddate) values
            (NULL, 0, '$sub', '$add2', '$dt', '$dt');";
    	$conn->query($query33);
	}
	
	
	
	
	
?>

<select class="form-select form-select-md "  onchange="block3();"  id="module" aria-label="form-select-lg example" style=" background:var(--lighter);">
    <option value="" selected>Select Module</option>
    <?php
    $sql0 = "SELECT * FROM kbase2 where kbase1='$sub'  order by id";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) 
    {while($row0 = $result0->fetch_assoc()) { 
        $id = $row0["id"]; $title = $row0["title"]; 
        echo '<option value="' . $id . '">' . $title . '</option>';
    }}
    ?>
</select>