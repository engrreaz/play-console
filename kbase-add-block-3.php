<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy=date('Y');
	include ('../db.php');;
	$sub = $_POST['subject'];;  
	$mod = $_POST['module'];;  
	
	$add3 = $_POST['add3'];;  
	
	if($add3 != ""){
	    $query33 ="insert into kbase3 (id, sl, kbase1, kbase2, title, createdate, modifieddate) values
            (NULL, 0, '$sub', '$mod', '$add3', '$dt', '$dt');";
    	$conn->query($query33);
	}
?>

<select class="form-select form-select-md "  onchange="block4();"  id="section" aria-label="form-select-lg example" style=" background:var(--lighter);">
    <option value="" selected>Select Section</option>
    <?php
    $sql0 = "SELECT * FROM kbase3 where kbase1='$sub' and kbase2='$mod'  order by id";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) 
    {while($row0 = $result0->fetch_assoc()) { 
        $id = $row0["id"]; $title = $row0["title"]; 
        echo '<option value="' . $id . '">' . $title . '</option>';
    }}
    ?>
</select>