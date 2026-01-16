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
    
    
    
        <!--JAVA SCRIPT LIBRARY-->
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
        				$("#gg"+id).html( "" );
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
        <div class="card text-left" style="background:var(--darker); color:var(--lighter);" >

            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo"><i class="bi bi-grip-horizontal"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Assessment Entry

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($exam);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px; color:gray;">Name of Examination</div>
                            <div style="height:5px;"></div>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($classname) . ' : ' . strtoupper($sectionname);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:12px; color:gray;">Class & Section/Group</div>
                        </td>
                        <td style="text-align:right; vertical-align:top;">
                             <?php if($lock == 1){ ?>
                                <div style="font-weight:bold; display:inline; font-size:40px; color:white; padding:4px 8px; border-radius:5px;"><i class="bi bi-file-earmark-lock2-fill"></i></div>
                                
                            <?php } ?>
                            <div onclick="hhhsss();" style="font-weight:bold; display:inline; font-size:40px; color:white; padding:4px 4px; border-radius:5px;"><i class="bi bi-arrow-down-square-fill"></i></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:left;">
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($sname);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px; color:gray;">Subject</div>
                            <div style="height:5px;"></div>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo $subj_full . ' + ' . $obj_full . ' + ' .$pra_full . ' =  ' . $fullmark;;?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;"><b>Sub + Obj + Pra = Full Marks</b></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px; color:gray;">Marks Distribution</div>
                        </td>
                    </tr>
                </table>

               
            </div>
        </div>
        
    

        <?php
        
        
        //**********************************************************************************************************************
        $cnt = 0;
        $sql0 = "SELECT * FROM sessioninfo where sessionyear='$sy' and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by rollno";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0)
        {while($row0 = $result0->fetch_assoc()) {
            $stid=$row0["stid"]; $rollno=$row0["rollno"]; $card=$row0["icardst"]; $ggg=$row0["groupname"];  $sta=$row0["status"];
            if($subj_full == 0){ $sd = 'disabled'; } else { $sd = '';}
        	if($obj_full == 0){ $od = 'disabled'; } else { $od = '';}
        	if($pra_full == 0){ $pd = 'disabled'; } else { $pd = '';}
        	
        	
            if($sta == 0){
                $dsbl = 'disabled'; $bgc = 'light'; $sd = 'disabled'; $od = 'disabled'; $pd = 'disabled';
            } else {
                $dsbl = ''; $bgc = 'lighter';
            }

            $pth = '../students/' . $stid . '.jpg';
            if(file_exists($pth)){
                $pth = 'https://eimbox.com/students/' . $stid . '.jpg';
            } else {
                $pth = 'https://eimbox.com/students/noimg.jpg';
            }

            $sql00 = "SELECT * FROM students where  sccode='$sccode' and stid='$stid' LIMIT 1";
            $result00 = $conn->query($sql00);
            if ($result00->num_rows > 0)
            {while($row00 = $result00->fetch_assoc()) {
                $neng=$row00["stnameeng"]; $nben=$row00["stnameben"]; $vill=$row00["previll"];
            }}


            //if($card == 'x'){$bgc = '--light';} else {$bgc = '--lighter';}
            //if($card == '1'){$qrc = '<img src="https://chart.googleapis.com/chart?chs=20x20&cht=qr&chl=http://www.students.eimbox.com/myinfo.php?id=5000&choe=UTF-8&chld=L|0" />';} else {$qrc = '';}


            ?>
            <div class="card text-center stbox" style="background:var(--<?php echo $bgc;?>); color:var(--darker);"   id="block<?php echo $stid;?>"   <?php echo $dsbl;?> >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td style="width:30px;">
                            <span style="">
                                <span style="float:right; font-size:24px; font-weight:700; text-align:center;"><?php echo $rollno;?></span>
                            </span>
                        </td>
                        <td style="text-align:left; padding-left:5px;">
                            <div class="a"><?php echo $neng;?></div>
                            <div class="b"><?php echo $nben;?></div>
                            <div class="c" style="font-weight:600; font-style:normal; color:gray;">ID # <?php echo $stid . ' | <b>' . $ggg . '</b>';?></div>
                        </td>
                        <td style="text-align:right; width:105px;">
                            
                                <?php
                                $sy = date('Y');
                                $ex = $exam;
                                $sql00x = "SELECT * FROM stmark where  sccode='$sccode' and exam = '$ex' and classname='$classname' and sectionname='$sectionname' and sessionyear='$sy' and subject ='$subj' and stid='$stid' ";
                                //echo $sql00x;
                                $result00x = $conn->query($sql00x);
                                if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
                                    $subje=$row00x["subj"]; $obj=$row00x["obj"]; $pra=$row00x["pra"]; $gp=$row00x["gp"];  $gl=$row00x["gl"];}} 
                                    else {$subje = ''; $obj = ''; $pra = ''; $gp = ''; $gl = '';}
                                    
                                    //   include 'pibiblock.php';
                                ?>
                               
                           
                                        <div class="form-group" style="text-align:right;">
                                            <input type="number" class="form-control" style="width:100px;" value="<?php echo $subje;?>" id="sub<?php echo $stid;?>" onfocus="focu(<?php echo $stid;?>,1);" onblur="blurs(<?php echo $stid;?>,<?php echo $rollno;?>);">
                                            <span  id="gg<?php echo $stid;?>" ></span>
                                        </div>
                            
                                    
                        </td>
                    </tr>

                </table>
              </div>
            </div>
            <div class="stbox" style="height:0px;"></div>
            <?php
            $cnt++;
        }}
        //*****************************************************************************
        ?>
        
        
    






    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>

</body>

</html>
