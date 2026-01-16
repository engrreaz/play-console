<?php 
include 'inc.php';
$classname = $_GET['cls']; $sectionname = $_GET['sec']; 

 $exam = $_GET['exam'];  $subj = $_GET['sub'];  $assess = $_GET['assess']; 
 $block = 0;
 if(isset($_GET['block'])){
     $block = $_GET['block'];
 } else {
     $block = 0;
 }
 
 
 $sql00 = "SELECT subject FROM subjects where subcode='$subj' ";
 $result00 = $conn->query($sql00); if ($result00->num_rows > 0) {while($row00 = $result00->fetch_assoc()) { $sname=$row00["subject"];}}
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
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="gox(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo"><i class="bi bi-check2-square"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Select Indicator (PI/BI)
                      
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($exam);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Name of Examination</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($classname) . ' : ' . strtoupper($sectionname);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Class & Section/Group</div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="text-align:left;">
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($sname);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Subject</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($assess);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Assessment</div>
                        </td>
                    </tr>
                    
                    
                </table>
            </div>
        </div>
    

            <?php
                            
                            $totaldone = 0; $totalreq = 0;
                            
                            if($assess == 'Continious Assessment') {$fld = 'continious';}
                            else if($assess == 'Total Assessment') {$fld = 'total';}
                            else if($assess == 'Behavioural Assessment') {$fld = 'behave';}
                            else if($assess == 'Merged PI') {$fld = 'total';}
                            else if($assess == 'Merged BI') {$fld = 'behave';}
                            
                            
                            
                            if($assess == 'Behavioural Assessment'){
                                $sql0 = "SELECT *  FROM pibitopics where sessionyear='$sy' and exam='$exam' and $fld=1 order by topiccode";
                            } else {
                                $sql0 = "SELECT *  FROM pibitopics where sessionyear='$sy' and exam='$exam' and class='$classname' and subcode='$subj' and $fld=1 order by topiccode";    
                            }
         
      echo $sql0;
                            $result0 = $conn->query($sql0);
                            if ($result0->num_rows > 0) 
                            {while($row0 = $result0->fetch_assoc()) { 
                                $code=$row0["topiccode"];  $title=$row0["topictitle"];  $id=$row0["id"];
                                $level1=$row0["level1"]; $level2=$row0["level2"]; $level3=$row0["level3"]; $areacode=$row0["pibiarea"]; 
                            ?>
            
            <div class="card gg" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  onclick="go<?php echo $block;?>(<?php echo $id;?>);">
                
                <?php
                
                   
                
                
                            $sql0n = "SELECT count(*) as cnt  FROM pibientry where sessionyear='$sy' and exam='$exam' and classname='$classname' and sectionname='$sectionname' and subcode='$subj' and assesstype ='$assess' and sccode='$sccode' and topicid='$id' and assessment>0";    
                            $result0n = $conn->query($sql0n);
                            if ($result0n->num_rows > 0) 
                            {while($row0n = $result0n->fetch_assoc()) { 
                                $donecnt=$row0n["cnt"];
                            }}
                            
                            $sql242fr = "SELECT religion, count(*) as cnt FROM sessioninfo where sessionyear='$sy' and classname='$classname' and sectionname='$sectionname' and sccode='$sccode' and status = 1 group by religion"; 
                            $result242fr = $conn->query($sql242fr); if ($result242fr->num_rows > 0) {while($row242fr = $result242fr->fetch_assoc()) {
                            $rel=$row242fr['religion']; $stcount=$row242fr['cnt']; 
                            if($rel == 'Hindu'){$hindu = $stcount;} if($rel == 'Islam'){$islam = $stcount;}}}
                            if($subcode == 906){ $stcount = $islam;} else if($subcode == 907){ $stcount = $hindu;} else { $stcount = $hindu + $islam;}
                            
                            $totaldone = $totaldone + $donecnt;
                            $totalreq = $totalreq + $stcount;
                    ?>
                
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <div class="form-group">
                    <div style="float:right; text-align:right; font-size:15px; background-color:var(--darker); color:var(--lighter); display:inline-block; padding:3px 8px; border-radius:4px;">
                        <b><?php echo $donecnt . ' / ' . $stcount;;?></b> <small>Entry Done.</small>
                    </div>
                    <h4><?php echo $code;?><span style="color:red; font-weight:700; font-size:30px;" id="counts<?php echo $id;?>"></span></h4>
                    <div style="">
                        <?php echo $title;?>
                    </div>
                  </div>
                  
                  <div class="" style="display:<?php if($block==1){echo 'block';} else {echo 'none';}?>" id="dirbox<?php echo $id;?>" onclick="bb(<?php echo $id;?>);">
                      <?php 
                      //echo $id . '/' . $areacode;;
                      $f1 = rand(0,9);
                      $f2 = rand(0,9);
                      $f3 = $f1+$f2;
                      
                      ?>
                      <input type="number" class="form-control" id="dirtxt<?php echo $id;?>" onkeyup="counts(<?php echo $id;?>);" placeholder="Just type your Assessment Value 3, 2 or 1 (0 for blank)"/>
                      <br>
                      <input type="text" style="width:100px; text-align:center; font-weight:bold; display:inline;" class="form-control" id="rbttxt<?php echo $id;?>" value="<?php echo $f1 . ' + ' . $f2 . ' = ' ;?>" disabled />
                      <input type="text" style="width:100px; text-align:center; font-weight:bold; display:inline;" class="form-control" id="rbtans<?php echo $id;?>" value="" placeholder="Answer" />
                      
                      <button class="btn btn-success" onclick="subass(<?php echo $id;?>, <?php echo $areacode;?>, <?php echo $f3;?>);">Submit Assessment</button>
                      <button class="btn btn-warning" onclick="aigen(<?php echo $id;?>, <?php echo $areacode;?>, <?php echo $f3;?>);">Auto Generate Assessment</button>
                      <span id="dirass<?php echo $id;?>"></span>
                  </div>
              </div>
            </div>
            
            <?php }} ?>
            
            <div class="card gg" style="background:var(--lighter); color:var(--darker);"  onclick="gon(999999);">
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <div class="form-group">
                    <div style="float:right; text-align:right; font-size:15px; background-color:var(--darker); color:var(--lighter); display:inline-block; padding:3px 8px; border-radius:4px;">
                        <b><?php echo $totaldone . ' / ' . $totalreq;?></b> <small>Entry Done.</small>
                    </div>
                    <h4>ALL</h4>
                    <div style="">
                        All (PI/BI) Assessment At-Once
                    </div>
                  </div>
              </div>
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
  document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
    function go0(id){
        let tail = '?exam=<?php echo $exam;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&sub=<?php echo $subj;?>&assess=<?php echo $assess;?>&id=' + id;
        window.location.href="markpibientry.php" + tail; 
    } 
    
    function gon(id){
        let tail = '?exam=<?php echo $exam;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&sub=<?php echo $subj;?>&assess=<?php echo $assess;?>&id=' + id;
        window.location.href="markpibientryall.php" + tail; 
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

		var infor="sccode=<?php echo $sccode;?>&cls=" + cls + "&sec=" + sec  ;
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
  </script>
  
  
  
  <script>
      function subass(id, area, ans) {
		var val=document.getElementById("dirtxt"+id).value;
		var subans=document.getElementById("rbtans"+id).value;
		if(subans == ans && val !='' ){
		    
		var infor="sccode=<?php echo $sccode;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&sub=<?php echo $subj;?>&exam=<?php echo $exam;?>&type=<?php echo $assess;?>&topicid=" + id + "&areacode=" + area + "&val=" + val;
//	alert(infor);
	$("#dirass+id").html( "" );

	 $.ajax({
			type: "POST",
			url: "savepibidir.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#dirass'+id).html('<span class="">Saving data...</span>');
			},
			success: function(html) {    
				$("#dirass"+id).html( html );
				document.getElementById("dirtxt"+id).value = ''; 
				document.getElementById("counts"+id).innerHTML = '';
			}
		});
		} else {
		    alert('You must enter assessment with Correct Capcha!');
		}
		
		
    }

</script>

  <script>
      function aigen(id, area, ans) {
		var val='';
		var subans=document.getElementById("rbtans"+id).value;

		    
		var infor="sccode=<?php echo $sccode;?>&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&sub=<?php echo $subj;?>&exam=<?php echo $exam;?>&type=<?php echo $assess;?>&topicid=" + id + "&areacode=" + area + "&val=" + val;

	$("#dirass+id").html( "" );

	 $.ajax({
			type: "POST",
			url: "aigen.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#dirass'+id).html('<span class="">Generating....</span>');
			},
			success: function(html) {    
				$("#dirass"+id).html( html );
				
				var z = document.getElementById("pulp"+id).innerHTML;
				
				document.getElementById("dirtxt"+id).value = z; 
				document.getElementById("dirass"+id).innerHTML =  'Auto generating assessment process has been done!';
				
			//	alert(z);
				// // document.getElementById("dirtxt"+id).value = z; 
				
				// document.getElementById("dirtxt"+id).value = z;
			}
		});
    }

</script>

<script>
    
    function bb(){
        
    }
    
    function counts(id){
        var lll = document.getElementById("dirtxt"+id).value;
        var ppp = lll.length;
        if(ppp>0){
            document.getElementById("counts"+id).innerHTML = ' ( ' + ppp + ' )' ;
        } else {
            document.getElementById("counts"+id).innerHTML = '' ;
        }
        
        if(lll.search("4")>=0||lll.search("5")>=0||lll.search("6")>=0||lll.search("7")>=0||lll.search("8")>=0||lll.search("9")>=0){
            alert('Wrong Entry. Supported value are 0, 1, 2 & 3 only.');
        }
        
    }
  </script>
    
    
  
</body>

</html>