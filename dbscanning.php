<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');; $sy=date('Y');
	include ('incc.php');;
	include ('../db.php');
	$tail = $_POST['tail'];
	
	if($tail == 0){
	    echo '<table style="width:100%">';
	    echo '<tr><td>Class</td><td>Roll</td><td>Entry</td></tr>';
	    
        $cnt = 1; $koytat = 0;
		$sql0 = "SELECT * from sessioninfo where sccode='$sccode' and sessionyear = '$sy' and status = 0 order by stid;";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $stid=$row0["stid"]; $cls=$row0["classname"]; $sec=$row0["sectionname"];$roll=$row0["rollno"];
            
            
            if($cls == 'Six' || $cls == 'Seven'){
                $sql01 = "SELECT count(*) as cpl from pibientry where sccode='$sccode' and sessionyear='$sy' and exam='$exam' and stid='$stid' ;";
                $result01 = $conn->query($sql01);
                if ($result01->num_rows > 0) 
                {while($row0 = $result01->fetch_assoc()) { 
                    $koyta=$row0["cpl"];}} else {$koyta = 0;}
            }  else {
                $sql01 = "SELECT count(*) as cpl from stmark where sccode='$sccode' and sessionyear='$sy' and exam='$exam' and stid='$stid' ;";
                $result01 = $conn->query($sql01);
                if ($result01->num_rows > 0) 
                {while($row0 = $result01->fetch_assoc()) { 
                    $koyta=$row0["cpl"];}} else {$koyta = 0;}
            }  
            
            echo '<tr><td>' . $cls . '<br>' . $sec . '</td><td>' . $roll . '</td><td>' . $koyta . '</td></tr>';
            
            
            
            $cnt++; $koytat = $koytat + $koyta;
            
            
            
        }}
		echo '<tr><td></td><td></td><td><b>' . $koytat . '</b></td></tr>';
		echo '</table>';
		echo '<button  class="btn btn-info" onclick="checkabsentmarkentry(1);">Check Absent Student Mark Entry</button>';
		
	} else if($tail == 1){
	    
	    $sql0 = "SELECT * from sessioninfo where sccode='$sccode' and sessionyear = '$sy' and status = 0 order by stid;";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $stid=$row0["stid"]; $cls=$row0["classname"]; $sec=$row0["sectionname"];$roll=$row0["rollno"];
            
            
            if($cls == 'Six' || $cls == 'Seven'){
                $sql01 = "delete from pibientry where sccode='$sccode' and sessionyear='$sy' and exam='$exam' and stid='$stid' ;";
                $conn->query($sql01);
            }  else {
                $sql01x = "delete from stmark where sccode='$sccode' and sessionyear='$sy' and exam='$exam' and stid='$stid' ;";
                $conn->query($sql01x);
                
            }  
	}}
	
	echo 'Remove Complete';
	} else if($tail == 2){
	    echo '<table>';
	    $sql0 = "SELECT classname, sectionname, stid, subcode, topicid, assesstype, count(*) as cnt FROM `pibientry` WHERE sessionyear='$sy' and exam = '$exam' and sccode = '$sccode' group by classname, sectionname, stid, subcode, topicid, assesstype having count(*)>1 order by cnt desc;";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $cls=$row0["classname"];  $sec=$row0["sectionname"];  $stid=$row0["stid"];  $sub=$row0["subcode"];  $topicid=$row0["topicid"];  $assess=$row0["assesstype"];  $cnt=$row0["cnt"];  
            
            echo '<tr><td>' . $cls . '</td><td>' . $sec . '</td><td>' . $stid . '</td><td>' . $sub . '</td><td>' . $topicid . '</td><td>' . $assess . '</td><td>' . $cnt . '</td><td><button >clean</button></td></tr>';
            
            ?>
                <tr>
                    <td colspan="7">
                        <?
                        $sql0x = "SELECT * FROM `pibientry` WHERE `sessionyear` = '$sy' AND `exam` = '$exam' AND `subcode` = '$sub'  AND `sccode` = '$sccode' AND `stid` = '$stid' AND `topicid` = '$topicid' and assesstype='$assess'";
                        $result0x = $conn->query($sql0x);
                        if ($result0x->num_rows > 0) 
                        {while($row0x = $result0x->fetch_assoc()) { 
                            $id=$row0x["id"]; $ass=$row0x["assessment"]; //$=$row0x[""]; $=$row0x[""]; 
                            echo '<button id="btn'.$id.'" onclick="del('.$id.');">' . $ass . '</button> ';
                        }}
            ?>
                        
                    </td>
                </tr>
            <?php
            
        }}
        echo '</table>';
	    
	}
	    //
	    //
?>

