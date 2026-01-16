<?php
	$sub_no = 1; 
	$sy = date('Y');
	$sql22vl="SELECT * FROM subsetup where classname ='$cn' and sectionname='$secname' and  sccode = '$sccode' and sessionyear = '$sy' order by subject " ;

	$result22vl = $conn->query($sql22vl);
	if ($result22vl->num_rows > 0) 
		{while($row22vl = $result22vl->fetch_assoc()) 
			{
			$subject = $row22vl["subject"] ;
			
           
			
			
			
			$sql22vs="SELECT * FROM sessioninfo where classname ='$cn'  and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and rollno = '$start' order by rollno" ;
			$result22vs = $conn->query($sql22vs);
			if ($result22vs->num_rows > 0) 
				{while($row22vs = $result22vs->fetch_assoc()) 
					{
					$stid = $row22vs["stid"] ;
					$rollno = $row22vs["rollno"] ;
					
					$sql22vsx="SELECT * FROM stmark where classname ='$cn' and sectionname='$secname'  and sessionyear ='$sy' and sccode = '$sccode' and stid='$stid' and subject='$subject' and exam='$exam'" ;
					// echo $sql22vsx;
					$result22vsx = $conn->query($sql22vsx);
					if ($result22vsx->num_rows > 0) 
						{while($row22vsx = $result22vsx->fetch_assoc()) //********************************************************************************************
							{
							$sub_final = $row22vsx["sub_final"] ;
							$obj_final = $row22vsx["obj_final"] ;
							$pra_final = $row22vsx["pra_final"] ;
							$ca_final = $row22vsx["ca"] ;
							$total_final = $row22vsx["markobt"] ;
							$gp_final = $row22vsx["gp"] ;
							$gl_final = $row22vsx["gl"] ;
							
							//echo $sub_final.$obj_final.$pra_final.$ca_final.$total_final.$gp_final.$gl_final. '************';
							
							}}
							
							else {
							    $sub_final = 0 ;
    							$obj_final = 0 ;
    							$pra_final = 0 ;
    							$ca_final = 0 ;
    							$total_final = 0 ;
    							$gp_final = 0 ;
    							$gl_final = 0 ;
							}
							
							
							
							$col1='sub_'. $sub_no;
							$col2='sub_'. $sub_no . '_sub';
							$col3='sub_'. $sub_no . '_obj';
							$col4='sub_'. $sub_no . '_pra';
							$col5='sub_'. $sub_no . '_ca';
							$col6='sub_'. $sub_no . '_total';
							$col7='sub_'. $sub_no . '_gp';
							$col8='sub_'. $sub_no . '_gl';
							
							
							$query334 = "UPDATE tabulatingsheet SET 
								$col1 = '$subject', $col2 = '$sub_final', $col3 = '$obj_final', $col4 = '$pra_final', $col5 = '$ca_final', $col6 = '$total_final', $col7 = '$gp_final', $col8 = '$gl_final'								
								WHERE sessionyear='$sy' and exam='$exam' and stid='$stid'  ";
								
								
								//echo $query334 .'**' .  $sub_final . $obj_final . $pra_final .'**';
							
								if ($conn->query($query334) === TRUE)
							{ echo '';}  else  {echo '';}
					
							
					
					
					
					
					
					
					}}
			
	
			$sub_no = $sub_no+1;
			
			
							
								
							
			
			
			}}
			else
			{
			echo '<span class="tag warning padding5">No Subject Assigned. Process Failed</span>';
			}
