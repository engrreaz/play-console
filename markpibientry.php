<?php
include 'inc.php';
$classname = $_GET['cls']; $sectionname = $_GET['sec'];

 $exam = $_GET['exam'];  $subj = $_GET['sub'];  $assess = $_GET['assess']; $id= $_GET['id'];

 $sql00 = "SELECT * FROM areas where areaname='$classname' and subarea = '$sectionname' and user='$rootuser' ";
 $result00r = $conn->query($sql00); if ($result00r->num_rows > 0) {while($row00 = $result00r->fetch_assoc()) { $halfdone=$row00["halfdone"];  $fulldone=$row00["fulldone"];  }}
    if($exam == 'Half Yearly'){  $lock = $halfdone; } else {$lock = $fulldone;}
    
 $sql00 = "SELECT subject FROM subjects where subcode='$subj' ";
 $result00 = $conn->query($sql00); if ($result00->num_rows > 0) {while($row00 = $result00->fetch_assoc()) { $sname=$row00["subject"];}}
    
    
    
    
    
    $sql0 = "SELECT * FROM pibitopics where id = '$id'";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0)
    {while($row0 = $result0->fetch_assoc()) {
        $code=$row0["topiccode"];  $title=$row0["topictitle"];  $id=$row0["id"]; $acode = $row0["pibiarea"];
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
    </style>
    
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    
    <script>
        function hhh(){
            document.getElementById("a1").style.display = 'none';
            document.getElementById("a2").style.display = 'none';
            document.getElementById("a3").style.display = 'none';
            document.getElementById("a4").style.display = 'none';
            document.getElementById("a5").style.display = 'none';
        }
        function sss(){
            document.getElementById("a1").style.display = 'block';
            document.getElementById("a2").style.display = 'block';
            document.getElementById("a3").style.display = 'block';
            document.getElementById("a4").style.display = 'block';
            document.getElementById("a5").style.display = 'block';
        }
            
            function hhhsss(){
                if(document.getElementById("a1").style.display == 'none'){
                    sss();
                } else {
                    hhh();
                }
            }
    </script>
    
    
    
  <script>
  document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
    function go(id){
        let tail = '?exam=<?php echo $exam;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&sub=<?php echo $subj;?>&assess=<?php echo $assess;?>&id=' + id;
        window.location.href="markpibientry.php" + tail;
    }
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

  <script>
      function mentry(id,pi, roll) {
          let lock = <?php echo $lock * 1;?>; 
          if( lock == 0 ) {
        //alert(id + '/' + pi + '/' + roll);
		var infor="sccode=<?php echo $sccode;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&exam=<?php echo $exam;?>&sub=<?php echo $subj;?>&assess=<?php echo $assess;?>&topic=<?php echo $id;?>&usr=<?php echo $usr;?>&roll="+roll+"&stid=" + id + "&pi=" + pi + "&acode=<?php echo $acode;?>" ;
       // alert(infor);
        if(pi==1){
            var k = 's' + id;
        } else if(pi==2){
            var k = 'c' + id;
        } else if(pi==3){
            var k = 't' + id;
        } else {
            var k = 'b' + id;
        }


	$("#"+k).html( "" );

	 $.ajax({
			type: "POST",
			url: "savepibi.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#"+k).html('<div style="padding-top:0px;"><i style="font-size:28px;  color:var(--dark);" class="bi bi-tools"></i></div>');
			},
			success: function(html) {
				$("#table"+id).html( html );

			}
		});
    }
          else {
              alert('Entry/Modify has been locked.');
          }
          
          
      }
  </script>
  
  <script>
      function grp(){
          let chk = document.getElementById("grp").checked;
          if(chk == true){
              $('.stbox').hide(); $('.grpbox').show();
          } else {
              $('.stbox').show(); $('.grpbox').hide();
          }
      } 
      
      $('.stbox').show(); $('.grpbox').hide();
  </script>
</head>

<body >

  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="got(<?php echo $id;?>)">

            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo" style="font-size:18px;">
                                                <i class="bi bi-triangle-fill"></i>
                                                <i class="bi bi-circle-fill"></i>
                                                <i class="bi bi-square-fill"></i>
                                </div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Assessment Entry
                        
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($exam);?></div>
                            <div id="a1" style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Name of Examination</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($classname) . ' : ' . strtoupper($sectionname);?></div>
                            <div  id="a2" style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Class & Section/Group</div>
                        </td>
                        <td style="text-align:right;">
                             <?php if($lock == 1){ ?>
                                <div style="font-weight:bold; display:inline; font-size:40px; color:white; padding:4px 8px; border-radius:5px;"><i class="bi bi-file-earmark-lock2-fill"></i></div>
                                
                            <?php } ?>
                            <div onclick="hhhsss();" style="font-weight:bold; display:inline; font-size:40px; color:white; padding:4px 4px; border-radius:5px;"><i class="bi bi-arrow-down-square-fill"></i></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:left;" colspan="2">
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($sname);?></div>
                            <div id="a3" style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Subject</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($assess);?></div>
                            <div id="a4" style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Assessment Type</div>
                        </td>
                    
                    </tr>
                </table>

                <div class="form-group" style="margin-top:10px;">
                    <h3><b><?php echo $code;?></b></h3>
                    <div style="">
                        <b><?php echo $title;?></b>
                    </div>
                    <div id="a5" style="font-size:12px; margin-top:5px;">
                        <table width="100%">
                            <tr>
                                <td style="padding:3px 0 3px; width:27px; vertical-align:top;"><i class="material-icons" style="font-size:15px;color:white;">check_box_outline_blank</i></td>
                                <td style="font-size:13px; color:white;"><?php echo $level1;?></td>
                            </tr>
                            <tr>
                                <td style="padding:3px 0 3px; width:27px; vertical-align:top;"><i class="material-icons" style="font-size:15px;color:white;">radio_button_unchecked</i></td>
                                <td style="font-size:13px; color:white;"><?php echo $level2;?></td>
                            </tr>
                            <tr>
                                <td style="padding:3px 0 3px; width:27px; vertical-align:top;"><i class="material-icons" style="font-size:15px;color:white;">change_history</i></td>
                                <td style="font-size:13px; color:white;"><?php echo $level3;?></td>
                            </tr>
                        </table>
                    </div>
                    
                   
                </div>
            </div>
        </div>
        
            <script>hhh();</script>
        
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);" >
            <div class="card-body" style="text-align:center;">
                <h5><b>Student's List</b></h5>
                <table style="margin:auto; font-weight:500; color:white;">
                    <tr>
                        <td>Indivisual &nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <div class="form-check form-switch">
                     <input class="form-check-input" type="checkbox" id="grp" name="darkmode" value="yes" onclick="grp()" > 
                </div>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;Group</td>
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
            $stid=$row0["stid"]; $rollno=$row0["rollno"]; $card=$row0["icardst"]; $ggg=$row0["groupname"];  $stt=$row0["status"];

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


            if($stt == '0'){$bgc = '--light'; $ddd = 'pointer-events:none;';} else {$bgc = '--lighter'; $ddd = '';}
            //if($card == '1'){$qrc = '<img src="https://chart.googleapis.com/chart?chs=20x20&cht=qr&chl=http://www.students.eimbox.com/myinfo.php?id=5000&choe=UTF-8&chld=L|0" />';} else {$qrc = '';}


            ?>
            <div class="card text-center stbox" style="background:var(<?php echo $bgc;?>); color:var(--darker);"   id="block<?php echo $stid;?>" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td style="width:30px;">
                            <span style="">
                                <img src="<?php echo $pth;?>" class="pic" />
                            </span>
                        </td>
                        <td style="text-align:left; padding-left:5px;">
                            <div class="a"><?php echo $neng;?></div>
                            <div class="b"><?php echo $nben;?></div>
                            <div class="c" style="font-weight:600; font-style:normal; color:gray;">ID # <?php echo $stid . ' | <b>' . $ggg . '</b>' ;?></div>
                        </td>
                        <td style="text-align:right;"><span style="float:right; font-size:24px; font-weight:700; text-align:center;"><?php echo $rollno;?></span></td>
                    </tr>
                    <?php if($stt == 1){ ?>
                    <tr>
                        <td></td>
                        <td>
                            <table width="100%" id="table<?php echo $stid;?>">
                                <?php
                                $sy = date('Y');
                                $sql00x = "SELECT * FROM pibientry where  sccode='$sccode' and exam = '$exam' and sessionyear='$sy' and subcode='$subj' and stid='$stid' and assesstype='$assess' and topicid='$id' LIMIT 1";
                                $result00x = $conn->query($sql00x);
                                if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
                                    $pibi=$row00x["assessment"];}} else {$pibi = 0;}
                                    
                                       include 'pibiblock.php';
                                ?>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
              </div>
            </div>
            <div class="stbox" style="height:0px;"></div>
            <?php
            $cnt++;
        }}
        //*****************************************************************************
        ?>
        
        
        
       
        <?php
        //**********************************************************************************************************************
        $sql0f = "SELECT * FROM pibigroup where sessionyear='$sy' and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by id";
        $result0f = $conn->query($sql0f);
        if ($result0f->num_rows > 0)
        {while($row0f = $result0f->fetch_assoc()) {
            $gn=$row0f["groupname"]; $stid=$row0f["id"]; $rollno = $row0f["id"] + 10000;

            /*
            $pth = '../students/' . $stid . '.jpg';
            if(file_exists($pth)){
                $pth = 'https://eimbox.com/students/' . $stid . '.jpg';
            } else {
                $pth = 'https://eimbox.com/students/noimg.jpg';
            }
            */
            ?>
            
            <div class="card text-center grpbox" style="background:var(--lighter); color:var(--darker);"   id="block<?php echo $stid;?>">
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td style="width:30px;">
                            <span style="">
                                <img src="<?php echo $pth;?>" class="pic" />
                            </span>
                        </td>
                        <td style="text-align:left; padding-left:5px;">
                            <div class="a"><?php echo $gn;?></div>
                            <div class="c" style="font-weight:600; font-style:normal; color:gray;">ID # <?php echo $stid;?></div>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td >
                            <table width="100%" id="table<?php echo $stid;?>">

                                <?php
                                $sy = date('Y');
                                $sql00x = "SELECT * FROM pibientry where  sccode='$sccode' and exam = '$exam' and sessionyear='$sy' and subcode='$subj' and stid='$stid' and assesstype='$assess' and topicid='$id' LIMIT 1";
                                $result00x = $conn->query($sql00x);
                                if ($result00x->num_rows > 0) {while($row00x = $result00x->fetch_assoc()) {
                                    $pibi=$row00x["assessment"];}} else {$pibi = 0;}
                                       include 'pibiblock.php';
                                ?>
                            </table>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div class="grpbox" style="height:8px;"></div>
            <?php
        }}
        //*****************************************************************************
        ?>







    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->




</body>

</html>
