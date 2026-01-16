<?php
include 'inc.php';
 $stid= $_GET['stid'];
 

 
 $sql0xffffffffffg = "SELECT * FROM students where stid='$stid' order by stid desc";
        $result0xffffffffffg = $conn->query($sql0xffffffffffg);
        if ($result0xffffffffffg->num_rows > 0) 
        {while($row0xffffffffffg = $result0xffffffffffg->fetch_assoc()) { 
            $stdid = $row0xffffffffffg["stid"];
            $stnameeng = $row0xffffffffffg["stnameeng"];
            $stnameben = $row0xffffffffffg["stnameben"];
            $fname = $row0xffffffffffg["fname"];$fprof = $row0xffffffffffg["fprof"]; $fmob = $row0xffffffffffg["fmobile"];
            $mname = $row0xffffffffffg["mname"];$mprof = $row0xffffffffffg["mprof"]; $mmob = $row0xffffffffffg["mmob"];
            
            $dob = $row0xffffffffffg["dob"];  $religion = $row0xffffffffffg["religion"];  $gender = $row0xffffffffffg["gender"];   $guarmobile = $row0xffffffffffg["guarmobile"];
            
            
            $previll = $row0xffffffffffg["previll"];  $prepo = $row0xffffffffffg["prepo"];  $preps = $row0xffffffffffg["preps"];  $predist = $row0xffffffffffg["predist"];  
            $pervill = $row0xffffffffffg["pervill"];  $perpo = $row0xffffffffffg["perpo"];  $perps = $row0xffffffffffg["perps"];  $perdist = $row0xffffffffffg["perdist"];  
            
            
            
        }}
        
        
        

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
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px; color:var(--darker);}
        .form-control{font-size:16px; font-weight:bold; text-align:center;}
        
          .box {
            color: gray;
            font-weight: bold;
            text-align: left;
            padding: 7px 30px;
            border: 1px solid var(--light);
          }
          .fox {
            color: gray;
            font-weight: bold;
            text-align: left;
            padding: 7px;
          }
          .box small {
            font-weight: 400;
            font-size: 10px;
            color: var(--dark);
            padding-left: 30px;
            font-style:italic;
          }
          .icon {
            padding-right: 5px;
          }
          
          td  {
              text-align:center;
          }
          .lft {
               text-align:left; font-weight:500;
          }
          
          .bid {font-size:28px; color:gray;margin-top:10px;}
          .das {
            font-weight: 400;
            font-size: 12px;
            color: var(--dark);
            padding-top:8px;
          }
          
          .bbb{
              border:1px solid var(--darker);
              border-radius:5px;
              padding:7px 2px;
              background:var(--light);
              font-weight:600;
          }
          .right{
              float:right;
              margin-top:2px;
          }
          .hidden {display:none;}
          .icons{width:40px; vertical-align:top;padding-top:5px;}
          .prof {font-size:11px; font-weight:400; color:var(--dark);}

    </style>
</head>

<body style="background:var(--lighter);">
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
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);" >

            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo">
                                <img src="<?php echo 'https://eimbox.com/students/' . $stdid . '.jpg';?>" style="border:1px solid var(--lighter); width:100px; height:100px; border-radius:50%; " />
                            </div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:600; line-height:15px;">
                                    <?php echo $stnameeng;?>
                            </div>
                            <div style="font-size:18px; text-align:center; padding: 2px 2px 8px; font-weight:400; line-height:15px;">
                                    <?php echo $stnameben;?>
                            </div>
         
                        </td>
                    </tr>

                </table>

               
            </div>
        </div>
 
        
        <div class="box">
            <table style="width:100%;">
                <tr>
                    <td class="icons" style="">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="gray" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                        </svg>
                    </td>
                    <td class="fox">
                        <?php echo $fname;?>
                        <br><span class="prof"><?php echo $fprof;?> <i class="bi bi-telephone-fill"></i> <?php echo $fmob;?> </span>
                        <br /><?php echo $mname;?>
                        <br><span class="prof"><?php echo $fprof;?> <i class="bi bi-telephone-fill"></i> <?php echo $fmob;?> </span>
                        <br /><span class="c">Parents</span>
                    </td>
                </tr>
                <tr>
                    <td class="icons" style="">
                        <i class="bid bi bi-geo-alt-fill"></i>
                    </td>
                    <td class="fox">
                        <?php echo $previll.', ' . $prepo . ', ' . $preps . ', ' . $predist;?>
                        <br /><span class="c">Present Address</span>
                    </td>
                </tr>
                <tr>
                    <td class="icons" style="">
                        
                    </td>
                    <td class="fox">
                        <?php echo $pervill.', ' . $perpo . ', ' . $perps . ', ' . $perdist;?>
                        <br /><span class="c">Permanent Address</span>
                    </td>
                </tr>
            </table>

        </div>
        
        
        <div class="box">
            <table style="width:100%;">
                <tr>
                    <td class="icons" style="">
                        <i class="bid bi bi-calendar-check-fill"></i>
                    </td>
                    <td class="fox">
                        <?php echo $dob;?>
                        <br /><span class="c">Date of Birth</span>
                    </td>
                </tr>
                <tr>
                    <td class="icons" style="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="gray" class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z"/>
</svg>
                    </td>
                    <td class="fox">
                        <?php echo $religion.' / ' . $gender;?>
                        <br /><span class="c">Religion / Gender</span>
                    </td>
                </tr>

            </table>

        </div>
        <div class="box">
            <table style="width:100%;">
          
                <tr>
                    <td class="icons" style="">
                        <i class="bid bi bi-telephone-fill"></i>
                    </td>
                    <td class="fox">
                        <?php echo $guarmobile;?>
                        <br /><span class="c">Mobile Number</span>
                    </td>
                </tr>

            </table>

        </div>
        
        <style>
            .year{color:var(--lighter); background:var(--dark); padding:5px; border:0; border-radius:5px; font-size:12px; font-weight:700; margin-top:7px;}
        </style>
        
        <?php
        $sql0xffffffffffw = "SELECT * FROM sessioninfo where stid='$stdid' order by sessionyear desc";
            $result0xffffffffffw = $conn->query($sql0xffffffffffw);
            if ($result0xffffffffffw->num_rows > 0) 
            {while($row0xffffffffffw = $result0xffffffffffw->fetch_assoc()) { 
                $year = $row0xffffffffffw["sessionyear"];
                $cls = $row0xffffffffffw["classname"];
                $sec = $row0xffffffffffw["sectionname"];
                $roll = $row0xffffffffffw["rollno"];
                $team = $row0xffffffffffw["groupname"];
                if($team == ''){$team = 'Team not assigned.';}
                ?>
                <div class="box">
                    <table style="width:100%;">
                  
                        <tr>
                            <td class="icons" style="">
                                <div class="year"><?php echo $year;?></div>
                            </td>
                            <td class="fox">
                                <?php echo $cls . ' | ' . $sec;?>
                                <br /><span class="c">Class | Section/Group</span>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                        <tr>
                            <td class="icons" style="">
                                
                            </td>
                            <td class="fox">
                                <?php echo $roll . ' (' . $team . ')';?>
                                <br /><span class="c">Roll / Team</span>
                            </td>
                            <td rowspan="2">
                                
                            </td>
                            <td></td>
                        </tr>
                    </table>
        
                </div>
                
                <?php
            }}
            
            ?>
        
        
        









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
  document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";

  </script>


  <script>
      function fetchsection() {
		var cls=document.getElementById("classname").value;

		var infor="user=<?php echo $rootuser;?>&cls=" + cls;
	$("#sectionblock").html( "" );

	 $.ajax({
			type: "POST",
			url: "fetchsection.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
			},
			success: function(html) {
				$("#sectionblock").html( html );
			}
		});
    }
  </script>



</body>

</html>
