<?php
	
	$sql22v="SELECT * FROM sessioninfo where classname ='$cn' and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and  rollno = '$start' " ;
	//$sql22v="SELECT * FROM sessioninfo where classname ='$cn' and sectionname='$secname' and sessionyear ='$sy' and sccode = '$sccode' and  status = 1 order by rollno" ;
	//echo $sql22v;
	$result22v = $conn->query($sql22v);
			if ($result22v->num_rows > 0) 
				{while($row22v = $result22v->fetch_assoc()) 
					{
                            $stid = $row22v["stid"] ;
                            //************************************************************************
                            $sql22vx="SELECT * FROM students where stid = '$stid'" ;
                            $result22vx = $conn->query($sql22vx);
                            if ($result22vx->num_rows > 0) 
                            {while($row22vx = $result22vx->fetch_assoc()) 
                            {
                            $gender = $row22vx["gender"] ;}}
					        //************************************************************************
					
					$rollno = $row22v["rollno"] ;
					
					$query33 ="insert into tabulatingsheet
							(id, sessionyear, sccode, exam, classname, sectionname, stid, rollno, gender, allfourth)
					values 	(NULL, '$sy', '$sccode', '$exam', '$cn', '$secname', '$stid', '$rollno', '$gender', '$allfourth')";
					
					//echo $query33;
							if ($conn->query($query33) === TRUE)
							{ echo '';}  else  {echo '';}
	
					
					}}