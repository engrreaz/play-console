<?php

	include ('incb.php');;
	

	$rootuser = $_POST['rootuser'];;  $id = $_POST['id'];;  
	$cls = $_POST['cls'];;  $sec = $_POST['sec'];;  
	
	
    
   //************************************************************************************************************************************************
   //****************************************************************************************************************************************************************
   
    
        $sql00xgr = "SELECT * FROM sessioninfo where sccode='$sccode' and sessionyear='$sy'-1 and classname='$cls' and sectionname='$sec' order by rollno";  
        // echo $sql00xgr;
        $result00xgr = $conn->query($sql00xgr);
        if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
            $stid=$row00xgr["stid"]; $rollno=$row00xgr["rollno"]; 
            
            $sql00xgr = "SELECT * FROM students where sccode='$sccode' and stid='$stid'";  
            // echo $sql00xgr;
            $result00xgrt = $conn->query($sql00xgr);
            if ($result00xgrt->num_rows > 0) {while($row00xgr = $result00xgrt->fetch_assoc()) {
                $stnameeng=$row00xgr["stnameeng"];
            }}
            
            $sql00xgr = "SELECT * FROM sessioninfo where sccode='$sccode' and stid='$stid' and sessionyear='$sy'";  
            // echo $sql00xgr;
            $result00xgrt2 = $conn->query($sql00xgr);
            if ($result00xgrt2->num_rows > 0) {while($row00xgr = $result00xgrt2->fetch_assoc()) {
                $found=1; }} else {$found=0;}
        ?>
        
                <div class="card" style="background:var(--lighter); color:var(--darker);" >
                  <img class="card-img-top"  alt="">
                  <div class="card-body">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50px; vertical-align:top; color:var(--dark);"><i class="material-icons">group</i></td>
                            <td style="width:120px;  font-size:10px;font-style:italic;"><span id="stid<?php echo $stid;?>"><?php echo $stid;?></span></td>
                            <td style="width:50px;"><span id="roll<?php echo $stid;?>"><?php echo $rollno;?></span></td>
                            
                            <td></td>
                            <?php if($found==0){ ?>
                            <td style="width:80px;"><input class="form-control"  type="number" style="width:60px;" id="txt<?php echo $stid;?>" /></td>
                            <?php } else {  ?>
                            <td style="width:80px;"></td>
                            <?php } ?>
                            <td style="width:150px;" id="exe<?php echo $stid;?>">
                                <?php if($found==0){ ?>
                                <button  class="btn btn-primary" onclick="edit(<?php echo $stid;?>);" >Promote</button>
                                <?php } else { ?>
                                    <span style="font-size:30px;"><i style="color:red;" class="bi bi-check2-circle"></i></span>
                                <?php } ?>
                            </td>
                            <td style="font-weight:bold;"><span id="nam<?php echo $stid;?>"><?php echo $stnameeng;?></span></td>
                        </tr>
                    </table>
                  </div>
                </div>
        
        
        <?php }} ?>
        
