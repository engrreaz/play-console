<?php
	include ('inc.php');; 
	
    $fnltxt = '';
     $id = $_POST['id'];; $pp = $_POST['pp'];;     
     
    if($pp == 1){
        $query3p ="UPDATE sessioninfo set finsetup = 0 where sccode = '$sccode' and sessionyear='$sy'";
	    $conn->query($query3p); 
    }
     
     
    $sql00xr = "SELECT * FROM financesetup where  sccode='$sccode' and sessionyear='$sy' and id='$id'"; //echo $sql00xr;
    $result00xr = $conn->query($sql00xr);
    if ($result00xr->num_rows > 0) {while($row00xr = $result00xr->fetch_assoc()) { 
        $peng=$row00xr["particulareng"];     $pben=$row00xr["particularben"];    $partid=$row00xr["id"];   $month=$row00xr["month"];   
        $play=$row00xr["play"]; $nursery=$row00xr["nursery"];  
        $one=$row00xr["one"]; $two=$row00xr["two"]; $three=$row00xr["three"]; $four=$row00xr["four"]; $five=$row00xr["five"];   
        $six=$row00xr["six"]; $seven=$row00xr["seven"]; $eight=$row00xr["eight"]; $nine=$row00xr["nine"]; $ten=$row00xr["ten"];   
    }} 
    
    $sql00x = "SELECT * FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and finsetup = 0  order by stid LIMIT 1"; 
    $result00x = $conn->query($sql00x);
    if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
        $rid=$row00x["id"];  $cls=$row00x["classname"];  $sec=$row00x["sectionname"];  $stid=$row00x["stid"];  $rollno=$row00x["rollno"]; // $=$row00x[""];  $=$row00x[""];  $=$row00x[""];  
       
        if($cls == 'Play'){$amt = $play;}
        if($cls == 'Nursery'){$amt = $nursery;}
        if($cls == 'One'){$amt = $one;}
        if($cls == 'Two'){$amt = $two;}
        if($cls == 'Three'){$amt = $three;}
        if($cls == 'Four'){$amt = $four;}
        if($cls == 'Five'){$amt = $five;}
        if($cls == 'Six'){$amt = $six;}
        if($cls == 'Seven'){$amt = $seven;}
        if($cls == 'Eight'){$amt = $eight;}
        if($cls == 'Nine'){$amt = $nine;}
        if($cls == 'Ten'){$amt = $ten;}
           
            
            
            $sql00xd = "SELECT * FROM stfinance where  sccode='$sccode' and sessionyear='$sy' and  stid='$stid' and partid='$partid' ";
            $result00xd = $conn->query($sql00xd);
            if ($result00xd->num_rows > 0) {while($row00xd = $result00xd->fetch_assoc()) {$getid=$row00xd["id"];    
                $query33 ="UPDATE stfinance set amount = '$amt', payableamt='$amt', dues='$amt', setupdate='$cur'  where id = '$getid'";
		        $conn->query($query33);
            }} else {
                
                if($peng == 'Tution Fee'){ 
                    for($x=1; $x<=12; $x++){
                        $dx = $sy . '-' . $x . '-01';
                        $peng = 'Tution Fee : ' . date('F', strtotime($dx)) . '/' . $sy;
                        $pben = 'মাসিক বেতন : ' . date('F', strtotime($dx)) . '/' . $sy;
                        $month = $x;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);  //echo $query33;  
                    }
                } else if($peng == 'Coaching Fee'){ 
                    for($x=1; $x<=12; $x++){
                        $dx = $sy . '-' . $x . '-01';
                        $peng = 'Coaching Fee : ' . date('F', strtotime($dx)) . '/' . $sy;
                        $pben = 'কোচিং ফি: ' . date('F', strtotime($dx)) . '/' . $sy;
                        $month = $x;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);  //echo $query33;  
                    }
                } else if($peng == 'Exam Fee'){
                        $peng = 'Exam Fee : Half Yearly/'.$sy;
                        $pben = 'পরীক্ষা ফি : অর্ধবাষর্ষিকী/'.$sy;
                        $month = 6;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
        		        
        		        $peng = 'Exam Fee : Annual/'.$sy;
                        $pben = 'পরীক্ষা ফি : বাষর্ষিক/'.$sy;
                        $month = 11;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
                } else if($peng == 'Class Test'){
                        $peng = 'Class Test - 1 / '.$sy;
                        $pben = 'ক্লাস টেষ্ট - ১ /'.$sy;
                        $month = 6;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
        		        
        		        $peng = 'Class Test - 1 / '.$sy;
                        $pben = 'ক্লাস টেষ্ট - ২/'.$sy;
                        $month = 11;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
                } else if($peng == 'Miscellaneous'){
                        $peng = 'Miscellaneous - 1 / '.$sy;
                        $pben = 'বিবিধ - ১ /'.$sy;
                        $month = 6;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
        		        
        		        $peng = 'Miscellaneous - 2 / '.$sy;
                        $pben = 'বিবিধ - ২/'.$sy;
                        $month = 11;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
                } else if($peng == 'Transcript Fee'){
                        $peng = 'Transcript Fee - Half Yearly / '.$sy;
                        $pben = 'ফলাফল বিবরণী (অর্ধ বাষর্িকী) /'.$sy;
                        $month = 6;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
        		        
        		        $peng = 'Transcript Fee - Annual / '.$sy;
                        $pben = 'ফলাফল  বিবরণী (বাষর্িক)/'.$sy;
                        $month = 11;
                        $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
        		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
        		        $conn->query($query33);//echo $query33;
                } else {
                    $query33 ="INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, particulareng, particularben, amount, month, setupdate, setupby, payableamt, dues)
    		            VALUES(NULL, '$sccode', '$sy', '$cls', '$sec', '$stid', '$rollno', '$partid', '$peng', '$pben', '$amt', '$month', '$cur' , '$usr', '$amt', '$amt')";
    		        $conn->query($query33);   //echo $query33;
                }
                
                
                
                    
            }
            
  
        $query33 ="UPDATE sessioninfo set finsetup = 1 where id = '$rid'";
		$conn->query($query33);   
        
        
    }} else {
        $fnltxt = 'Done !';
    }
    
    
    
    
    
    
    
    
    
    
    
    
    if($fnltxt == ''){
        $sql00xr = "SELECT count(*) as cnt FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and finsetup=0";
        $result00xr = $conn->query($sql00xr);
        if ($result00xr->num_rows > 0) {while($row00xr = $result00xr->fetch_assoc()) { 
        $cnt=$row00xr["cnt"];}}  
        $fnltxt = $cnt . ' students remaining.';
    }

   echo $fnltxt;