<?php 
include 'inc.php';
$classname = $_GET['cls']; $sectionname = $_GET['sec']; 
$exam = $_GET['exam']; $sub = $_GET['sub']; $assess = $_GET['assess']; 

$sql00v = "SELECT * from subjects where subcode='$sub' ";
                    $result00v = $conn->query($sql00v);
                    if ($result00v->num_rows > 0) 
                    {while($row00v = $result00v->fetch_assoc()) { 
                        $subname=$row00v["subject"]; $subben=$row00v["subben"];}}
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
            
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"  />
    
    <style>
        .pic{
            width:45px; height:45px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }
        
        @media print
            {    
                .noprint
                {
                    display: none !important;
                }
                
                body{
                    width:267mm !important;
                }
            }
        
        
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        .top{font-size:16x; width:70px; text-align:center; font-weight:700;}
        .gen{font-size:16px;  text-align:center; font-weight:400; padding:5px 0;}
        #boxtbl tr,#boxtbl td{border:1px solid gray;}
        thead {display: table-header-group;}
        .gap {vertical-align:top; padding:2px 5px 2px 2px;}
        .gap small{font-size:10px;}
        .rndbox{border:1px solid gray; border-radius:8px; height:62px; padding:8px; margin:0 5px;}
        .rndbox table{width:100%;;}
        .sh {height:62px;}
    </style>
</head>

<body style="background:white; width:524pt; margin:auto; font-family: Segoe UI, SutonnyOMJ ;">
  <header>
    <!-- place navbar here -->
  </header>
  <main>
                <table style="width:100%">
                    <tr>
                        <td colspan="3" style="text-align:center;"><h3><b>Half Yearly Total Assessment</b></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="rndbox">
                                <table>
                                    <tr>
                                        <td  style="vertical-align:top;">Institute Name : &nbsp;&nbsp;&nbsp;</td>
                                        <td  style="vertical-align:top;">
                                            <h5><b><?php echo $scname;?></b></h5>
                                            <small style="line-height:9px;"><?php echo $scaddress ;?></small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="rndbox">
                                <table>
                                    <tr>
                                        <td>
                                            <b><?php echo date('d/m/Y');?></b><br><small>Date</small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="3" style="height:10px;"></td></tr>
                    <tr>
                        <td>
                            <div class="rndbox sh">
                                <table>
                                    <tr>
                                        <td  style="vertical-align:top;">Class : </td>
                                        <td  style="vertical-align:top;">
                                            <b><?php echo $classname . ' | ' . $sectionname;;?></b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="rndbox sh">
                                <table>
                                    <tr>
                                        <td  style="vertical-align:top;">Subject : </td>
                                        <td  style="vertical-align:top;">
                                            <b><?php echo $subname . '<br>' . $subben;;?></b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td >
                            
                            <div class="rndbox sh">
                                <table>
                                    <tr>
                                        <td  style="vertical-align:bottom; font-size:9px; text-align:center;"><br><br><br>Teacher's Name & Signature</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                
                <div>
                    <div style="float:right; font-size:10px; margin:10px; color:red;" class="noprint">
                        <span onclick="prnt(0);">Minimum</span> | <span onclick="prnt(1);">Standard</span> | <span onclick="prnt(2);">Details</span>
                    </div>
                    <h5 style="padding:15px 8px 5px 8px;;" class="topic"><b>Performance Indicator / পারদর্শিতার সূচক</b></h5
                </div>
                
                
                <table id="topic" class="topic">
                    <?php $sql0 = "SELECT * FROM pibitopics where sessionyear = '$sy' and class='$classname' and subcode='$sub' and exam='$exam'  order by topiccode";
                    $result0g = $conn->query($sql0);
                    if ($result0g->num_rows > 0)
                    {while($row0 = $result0g->fetch_assoc()) {
                        $code=$row0["topiccode"];  $title=$row0["topictitle"];  $id=$row0["id"];
                        $level1=$row0["level1"]; $level2=$row0["level2"]; $level3=$row0["level3"]; 
                        ?>
                            <tr>
                                <td style="width:20px;"></td>
                                <td class="gap"><?php echo $code;?></td>
                                <td class="gap">
                                    <?php echo $title;?>
                                    <br>
                                    <small class="level">
                                        <?php echo '&#9632; - ' . $level1;?><br><?php echo '&#11044; - ' .  $level2;?><br><?php echo '&#9650; - ' .  $level3;?><br>
                                    </small>
                                </td>
                            </tr>
                        <?php
                        }}?>
                </table>
                
                <?php 
                    $toprow = ''; $cntpibi = 0; $genrow = '';
                    $sql00 = "SELECT * from pibitopics where sessionyear='$sy' and subcode= '$sub'  and class='$classname' and exam = '$exam' order by topiccode";
                    $result00 = $conn->query($sql00);
                    if ($result00->num_rows > 0) 
                    {while($row00 = $result00->fetch_assoc()) { 
                        $code=$row00["topiccode"];
                        $title=$row00["topictitle"];
                        $toprow = $toprow . '<td class="top">' . $code . '</td>';
                        $genrow = $genrow . '<td class="gen"><img src="iimg/pibi.png" width="60" /></td>';
                        $cntpibi++;
                    }}
                    
                    for($x = 1; $x <= 10 - $cntpibi; $x++){
                        $toprow = $toprow . '<td class="top"></td>';
                        $genrow = $genrow . '<td class="gen"></td>';
                    }
                ?>
                
                
                <h5 style="padding:15px 8px 5px 8px;"><b>Student's List / ছাত্র/ছাত্রীদের তালিকা</b></h5>
                
                
                <table style="width:100%;" id="boxtbl">
                    <thead>
                        <tr><td class="" style="width:40px; text-align:center" rowspan="2">Roll</td><td class=""  rowspan="2">Student's Name</td><td  colspan="10"><center><b>Applicable PI/BI</b></center></td></tr>
                        <tr><?php echo $toprow;?></tr>
                    </thead>
                    <?php 
                    $sql0 = "SELECT * from sessioninfo where sessionyear='$sy' and classname='$classname' and sectionname='$sectionname' and sccode = '$sccode' order by rollno";
                    $result0 = $conn->query($sql0);
                    if ($result0->num_rows > 0) 
                    {while($row0 = $result0->fetch_assoc()) { 
                        $rollno=$row0["rollno"]; $stid=$row0["stid"];
                        
                        $sql0x = "SELECT * from students where stid='$stid'";
                        $result0x = $conn->query($sql0x);
                        if ($result0x->num_rows > 0) 
                        {while($row0x = $result0x->fetch_assoc()) { 
                            $ben=$row0x["stnameben"];}}
                        
                        
                        
                    ?>
                        <tr>
                            <td style="width:40px; text-align:center"><?php echo $rollno;?></td>
                            <td style="font-family:sutonnyOMJ; padding-left:5px;"><?php echo $ben;?></td>
                            <?php echo $genrow;?>
                        </tr>
                    <?php }} ?>
                    
                </table>
                
                
                

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
  
    function go(){
        var cls=document.getElementById("classname").value; 
        var sec=document.getElementById("sectionname").value; 
        var sub=document.getElementById("subject").value; 
        var assess=document.getElementById("assessment").value; 
        var exam=document.getElementById("exam").value; 
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess; 
        if(cls=='Six'|| cls == 'Seven'){
            window.location.href="pibiprint.php" + tail; 
        } else {
            alert("Select Class Six/Seven Only");
        }
    }  
  </script>
  
  <script>
      function prnt(id){
          if(id==0){
              $('.level').hide();
              $('.topic').hide();
          } else if(id==1){
              $('.level').hide();
              $('.topic').show();
          } else if(id==2){
              $('.level').show();
              $('.topic').show();
          }
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
      function fetchsubject() {
		var cls=document.getElementById("classname").value;
		var sec=document.getElementById("sectionname").value;

		var infor="sccode=<?php echo $sccode;?>&cls=" + cls + "&sec=" + sec;
	$("#subblock").html( "" );

	 $.ajax({
			type: "POST",
			url: "fetchsubject.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#subblock').html('<span class="">Retriving Subjects...</span>');
			},
			success: function(html) {    
				$("#subblock").html( html );
			}
		});
    }
    
    function print(){
        window.print();
    }
  </script>
    
    
  
</body>

</html>