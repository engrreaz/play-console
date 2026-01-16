<?php
date_default_timezone_set('Asia/Dhaka');;
$dt = date('Y-m-d H:i:s');;
	include ('../db.php');;
	
	$sccode = $_POST['sccode'];;  
	$id = $_POST['id'];; 
	$mno = $_POST['mno'];; 
	$nam = $_POST['nam'];; 
	$level = $_POST['level'];; 
	$email = $_POST['email'];; 

	if($level == 'Student'){
	    $sql0xfix = "SELECT * FROM students where stid='$id' and sccode='$sccode' and guarmobile='$mno'";
        $result0xfix = $conn->query($sql0xfix);
        if ($result0xfix->num_rows == 1) {
            while($row0xfix = $result0xfix->fetch_assoc()) { 
             $stnameeng = $row0xfix["stnameeng"];
            }
            $qr = "UPDATE usersapp SET userlevel='$level', userid='$id', mobile='$mno', profilename='$stnameeng' where sccode='$sccode' and email='$email';";
            $conn->query($qr);
            echo '1';
        } else {
            echo 'Sorry. We are unable to verify you. May be you have missing something. Check it out...';
            //echo '<script>alert("Sorry. We are unable to verify you. May be you have missing something. Check it out...");</script>';
        }
	}  else if($level == 'Guardian'){
	    $sql0xfix = "SELECT * FROM students where stid='$id' and sccode='$sccode' and guarmobile='$mno'";
        $result0xfix = $conn->query($sql0xfix);
        if ($result0xfix->num_rows >0) {
            while($row0xfix = $result0xfix->fetch_assoc()) { 
             $stnameeng = $row0xfix["stnameeng"];
            }
            
            $idg = rand(2000000000, 2000999999);
            $sql0xfix = "SELECT * FROM usersapp where userid='$idg' ";
            $result0xfix = $conn->query($sql0xfix);
            if ($result0xfix->num_rows == 1) {
                echo 'Something were wrong. Please Submit again.';
            } 
            $qr = "UPDATE usersapp SET userlevel='$level', userid='$idg', mobile='$mno', profilename='$nam' where sccode='$sccode' and email='$email';";
            $conn->query($qr);
            echo '1';
        } else {
            echo 'Sorry. We are unable to verify you. May be you have missing something. Check it out...';
            //echo '<script>alert("Sorry. We are unable to verify you. May be you have missing something. Check it out...");</script>';
        }
	}

	

		
?>
