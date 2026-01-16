<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy=date('Y');
	include ('../db.php');;
	$sub = $_POST['subject'];;  
	$mod = $_POST['module'];; 
	$sec = $_POST['section'];; 
	$step = $_POST['step'];; 
	
	
	
	if(isset($_POST['item'])){
	    $item = $_POST['item'];; 
	    $query333 ="INSERT INTO kbase4 (id, sl, kbase1, kbase2, kbase3, stepid, descrip, pic, status, createdate, modifieddate)
	                           VALUES (NULL, 0, '$sub', '$mod', '$sec', '$step', '$item', 0, 1 , '$dt', '$dt');";
    	$conn->query($query333);
	}
	
    
    
    
    $sql0 = "SELECT * FROM kbase4 where kbase1='$sub' and kbase2='$mod' and kbase3='$sec' and stepid='$step'  order by id";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) 
    {while($row0 = $result0->fetch_assoc()) { 
        $id = $row0["id"]; $descrip = $row0["descrip"]; 
        echo $descrip . '<br>';
    }}
    ?>
    

    <div style="display:block; width:100%; height:1px"></div>
    
    
    <hr style="margin:0; border:1px solid var(--darker);"
    <div style="display:block; padding-top:8px; font-weight:bold;">Add a New Step</div>
    <div class="input-group">
        <span class="input-group-text"><i class="material-icons">create_new_folder</i></span>
        <input type="text" class="form-control" id="stepitem" placeholder=".............." value="">
    </div>
    
    <br>
    <div id="btn">
        <button type="button"  class="btn btn-dark mt-2" onclick="addstepitem();">Submit</button><br><br>
    </div>
    
    <div id="status5" class="status"></div>