<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$sccode = $_POST['rootuser'];;  $tid = $_POST['tid'];;  $tname = $_POST['tname'];;  
	$pos = $_POST['pos'];;  $mno = $_POST['mno'];;   $action = $_POST['action'];;  
	
	if($pos == 'Head Teacher'){$rank = 1;} else if($pos == 'Asstt. Head Teacher'){$rank = 2;} else if($pos == 'Asstt Teacher'){$rank = 3;} 
	else if($pos == 'Office Assistant'){$rank = 4;} else if($pos == 'Accountant'){$rank = 5;} else {$rank = 6;} 
	
	if($action == 1){
	    if($tid == ''){
	        $tidstart = $sccode * 10000 +9000;
	        $tidend = $sccode * 10000 +9998;
	        $sql00xgrg = "SELECT * FROM teacher where sccode='$sccode' and tid between '$tidstart' and '$tidend' order by tid asc limit 1";  
            $result00xgrg = $conn->query($sql00xgrg);
            if ($result00xgrg->num_rows > 0) {while($row00xgrg = $result00xgrg->fetch_assoc()) {
                $ltid=$row00xgrg["tid"]; }} else {$ltid = $sccode * 10000 +9999;} $ltid = $ltid - 1;
	        $query33 ="INSERT INTO teacher (id, tid, tname, position, mobile, sccode, ranks, status) VALUES (null, '$ltid', '$tname', '$pos', '$mno', '$sccode', '$rank', 1);";
	    } else {
	        $query33 ="UPDATE teacher set tname = '$tname', position = '$pos', mobile = '$mno', ranks = '$rank' where tid='$tid';";
	    }
	} else {
	    $query33 ="DELETE from teacher  where tid='$tid' and sccode = '$sccode';";
	}
	$conn->query($query33);  
	
    
   //************************************************************************************************************************************************
   //****************************************************************************************************************************************************************
   
    
        $sql00xgr = "SELECT * FROM teacher where sccode='$sccode' order by ranks, tid desc";  
        $result00xgr = $conn->query($sql00xgr);
        if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
            $tid2=$row00xgr["tid"]; $tname2=$row00xgr["tname"]; $pos2=$row00xgr["position"];  $mno2=$row00xgr["mobile"]; 
        ?>
        
                <div class="card" style="background:var(--lighter); color:var(--darker);" >
                  <img class="card-img-top"  alt="">
                  <div class="card-body">
                    <table >
                        <tr>
                            <td style="width:50px; vertical-align:top; color:var(--dark);"><i class="material-icons">group</i></td>
                            <td>
                                <table>
                                    <tr><td><h6 style="line-height:1px;" id="tid<?php echo $tid2;?>"><?php echo $tid2;?></h6></td></tr>
                                    <tr><td style="padding-bottom:15px;"><small>Teacher's ID</small></td></tr>
                                    
                                    <tr><td><h4 style="line-height:1px;" id="tname<?php echo $tid2;?>"><?php echo $tname2;?></h4></td></tr>
                                    <tr><td style="padding-bottom:15px;"><small>Teacher's Name</small></td></tr>
                                    
                                    <tr><td><h6 style="line-height:1px;" id="pos<?php echo $tid2;?>"><?php echo $pos2;?></h6></td></tr>
                                    <tr><td style="padding-bottom:15px;"><small>Designation</small></td></tr>
                                    
                                    <tr><td><h6 style="line-height:1px;" id="mno<?php echo $tid2;?>"><?php echo $mno2;?></h6></td></tr>
                                    <tr><td style="padding-bottom:15px;"><small>Mobile Number</small></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-top:5px;">
                                <button  class="btn btn-primary" onclick="edit(<?php echo $tid2;?>);">Edit</button>
                                <button  class="btn btn-danger" id="delbtn<?php echo $tid2;?>" style="display:none;" onclick="del(<?php echo $tid2;?>);">Confirm Delete</button>
                                <button  class="btn btn-warning" id="confbtn<?php echo $tid2;?>" onclick="confi(<?php echo $tid2;?>);">Delete</button>
                            </td>
                        </tr>
                    </table>
                  </div>
                </div>
                <div style="height:8px;"></div>
        
        
        <?php }} ?>
        
