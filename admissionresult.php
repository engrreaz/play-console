<?php
include 'inc.php';
$classname = 'Six'; $sectionname = $_GET['sec'];

 $exam = "Admission";  $subj = '';  $assess = ''; $id= $_GET['id'];

 $sql00 = "SELECT * FROM areas where areaname='$classname' and subarea = '$sectionname' and user='$rootuser' ";
 $result00r = $conn->query($sql00); if ($result00r->num_rows > 0) {while($row00 = $result00r->fetch_assoc()) { $halfdone=$row00["halfdone"];  $fulldone=$row00["fulldone"];  }}
    if($exam == 'Half Yearly'){  $lock = $halfdone; } else {$lock = $fulldone;}


 $sql00 = "SELECT subject FROM subjects where subcode='$subj' ";
 $result00 = $conn->query($sql00); if ($result00->num_rows > 0) {while($row00 = $result00->fetch_assoc()) { $sname=$row00["subject"];}}

    $sql0 = "SELECT * FROM pibitopics where id = '$id'";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0)
    {while($row0 = $result0->fetch_assoc()) {
        $code=$row0["topiccode"];  $title=$row0["topictitle"];  $id=$row0["id"];
        $level1=$row0["level1"]; $level2=$row0["level2"]; $level3=$row0["level3"]; }}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="css.css?v=a">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        .pic{
            width:45px; height:45px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }

        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        .form-control{font-size:16px; font-weight:bold; text-align:center;}
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
      
      <?php
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	$sql22v="SELECT * FROM subsetup where classname ='$classname'  and sectionname='$sectionname' and subject ='$subj' and sccode = '$sccode' " ;
    		$result22v = $conn->query($sql22v);
    	if ($result22v->num_rows > 0) {while($row22v = $result22v->fetch_assoc()) {
    			$fullmark = $row22v["fullmarks"] ; $careal = $row22v["ca"] ; $checkcamanual = $row22v["camanual"] ; $pass_algorithm = $row22v["pass_algorithm"] ;
    			     $subj_full = $row22v["subj"] ; $obj_full = $row22v["obj"] ; $pra_full = $row22v["pra"] ; $ca_full = $row22v["ca"] ;
    	}}
    	
    
    	    if($subj_full == 0){ $sd = 'disabled'; } else { $sd = '';}
        	if($obj_full == 0){ $od = 'disabled'; } else { $od = '';}
        	if($pra_full == 0){ $pd = 'disabled'; } else { $pd = '';}
    
        	
    	
    	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	?>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--darker); color:var(--lighter); border:0;" >

            <div class="card-body" style="text-align:center; width:95%; margin:auto;">
                <table style="margin:auto;">
                    <tr>
                        <td style="width:70px; border:0;">
                            <img src="https://eimbox.com/logo/103187.png" width="70" />
                        </td>
                        <td style="border:0;">
                            <div class="a"><?php echo $scname;?></div>
                            <div class="b"><?php echo $scaddress2;?></div>
                            <div class="b"><?php echo 'Mobile : ' . $mobile;?></div>
                        </td>
                    </tr>
                    
                </table>
               
            </div>
        </div>
        
    <style>
        th, td{border:1px solid gray; text-align:center; padding:0 5px;
        }
        th{
            padding:8px;
        }
    </style>
    
                                                    
    <table id="ttboxd" style="width:100%">
        <thead>
            <th>SL.</th><th>Name (Eng)</th><th>Name (Ben)</th><th>Father</th><th>Marks</th><th>CMP</th><th>SMP</th>
        </thead>


        <?php
        
        
        //**********************************************************************************************************************
        //**********************************************************************************************************************
        
        $sql0 = "SELECT * FROM stmark where sessionyear='$sy' and sccode='$sccode' and classname='Six' and exam='Admission' and markobt>0 order by ca";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0)
        {while($row0 = $result0->fetch_assoc()) {
            $stid=$row0["stid"]; $mo=$row0["markobt"]; $ca=$row0["ca"];  
            $secroll = ceil($ca/2);
            
            $sql00 = "SELECT * FROM sessioninfo where  sccode='$sccode' and sessionyear='$sy' and stid='$stid' LIMIT 1";
            $result00v = $conn->query($sql00);
            if ($result00v->num_rows > 0)
            {while($row00 = $result00v->fetch_assoc()) {
                $roll=$row00["rollno"]; $sss=$row00["sectionname"]; 
            }}
            
            $sql00 = "SELECT * FROM students where  sccode='$sccode' and stid='$stid' LIMIT 1";
            $result00 = $conn->query($sql00);
            if ($result00->num_rows > 0)
            {while($row00 = $result00->fetch_assoc()) {
                $neng=$row00["stnameeng"]; $nben=$row00["stnameben"]; $fname=$row00["fname"];
            }}
            
            ?>
                <tr>
                    <td><?php echo $roll . ' (' . $sss . ')' ;?></td>
                    <td style="text-align:left;"><?php echo $neng;?></td>
                    <td style="text-align:left; font-family:SutonnyOMJ; font-size:18px;"><?php echo $nben;?></td>
                    <td style="text-align:left; "><?php echo $fname;?></td>
                    <td><?php echo $mo;?></td>
                    <td><?php echo $ca;?></td>
                    <td><?php echo $secroll;?></td>
                </tr>
            
            <?php
            
        }}
        
        //**********************************************************************************************************************
        //**********************************************************************************************************************
     
        //*****************************************************************************
        ?>
    </table>
    
    <div style="font-size:11px; font-style:italic;">
        * CMP = Combined Merit Place
        <br>
        * SMP = Section Wise Merit Place
    </div>
    
    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



  <script>
        function focu(id,port){
            $('#sub'+id).css({"font-size":"30px", "color":"var(--dark)"});$('#sub'+id).select();
        }
        
        function blurs(id,roll){
          
          $('#sub'+id).css({"font-size":"16px", "color":"black"});
        //   mentry(id, 0, roll);
          markcall(id);
      }
        
    </script>


  <script>      
        
    function markcall(id){
            let fm = 100;
                    let sub = document.getElementById("sub" + id).value;
                    let obj = 0;
                    let pra = 0;
                    
                var infor="sccode=<?php echo $sccode;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&exam=<?php echo $exam;?>&sub=<?php echo $subj;?>&usr=<?php echo $usr;?>&stid=" + id + "&fm=" + fm + "&subj=" + sub + "&obj=" + obj + "&pra=" + pra  ;
	            //alert(infor);
	            $("#gg"+id).html( "" );
        	    $.ajax({
        			type: "POST",
        			url: "savestmark.php",
        			data: infor,
        			cache: false,
        			beforeSend: function () {
        				$("#gg"+id).html('<div style="padding-top:5px;"><i class="material-icons" style="font-size:35px;color:black;">save</i></div>');
        			},
        			success: function(html) {
        				$("#gg"+id).html( html );
        			}
        		});
    }    
        
   
  </script>

  <script>
      function mentry(id,pi, roll) {
        //alert(id + '/' + pi + '/' + roll);
        var sub = document.getElementById("sub" + id).value;
		var infor="sccode=<?php echo $sccode;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&exam=<?php echo $exam;?>&sub=<?php echo $subj;?>&assess=<?php echo $assess;?>&topic=<?php echo $id;?>&usr=<?php echo $usr;?>&roll="+roll+"&stid=" + id + "&pi=" + pi  ;
        //alert(infor);



	$("#"+k).html( "" );

	 $.ajax({
			type: "POST",
			url: "savestmark.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#"+k).html('<div style="padding-top:5px;"><i class="material-icons" style="font-size:35px;color:black;">save</i></div>');
			},
			success: function(html) {
				$("#table"+id).html( html );

			}
		});
    }
  </script>



</body>

</html>
