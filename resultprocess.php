<?php 
include 'inc.php'; $exam = 'Half Yearly';

$sql0 = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy' and classteacher='$userid' ";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while($row0 = $result0->fetch_assoc()) { 
        $cls=$row0["areaname"];  $sec=$row0["subarea"];   $jk=$row0["id"]; 
}} else {$cls = ' '; $sec = ' ';}
if($cls == 'Six' || $cls == 'Seven'){ $ex = " and status !=0 "; } else { $ex = ''; }
$sql0f = "SELECT count(*) as stcnt FROM sessioninfo where sccode='$sccode' and sessionyear='$sy' and classname='$cls' and sectionname = '$sec' $ex";
$result0f = $conn->query($sql0f);
if ($result0f->num_rows > 0) {
    while($row0 = $result0f->fetch_assoc()) { 
        $stcnt=$row0["stcnt"]; 
}} else {$stcnt = 0;}
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
        h4{font-size:18px; color:var(--darker); line-height:12px; font-weight:700;}
        small{font-size:10px; color:var(--dark); line-height:10px;}
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="gox(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 4px; font-weight:700; line-height:15px;">Result</div>
                            <div style="font-size:12px; text-align:center; padding: 2px; font-weight:400; line-height:15px;">Half Yearly Examination 2023</div>
                        </td>
                    </tr>
                
                    
                </table>
            </div>
        </div>
        <div style="height:8px;"></div>
    
    
            <div class="card" style="background:var(--darker); color:var(--lighter);" onclick="lnk11();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td style="width:50px;color:var(--lighter);"><i class="material-icons">group</i></td>
                        <td style="color:var(--lighter);">
                            <div style="float:right; font-size:20px; font-weight:700; padding-top:12px; " id="perc"></div>
                            <div style="float:right; color:white;  font-weight:700; " id="perc2">
                                <button class="btn" onclick=scan(<?php echo $jk;?>);><i style="font-size:40px; color:white;" class="material-icons">bug_report</i></button>
                            </div>
                            <h4  style="color:var(--lighter);">My Class</h4>
                            <small style="font-style:italic; line-height:14px; color:white;;">Class Teacher of</small><br><small  style="color:var(--lighter);"><b><?php echo $cls . ' : ' . $sec;?></b> | Students Found : <?php echo $stcnt;?></small>
                        </td>
                    </tr>
                </table>
                </div></div>
                <div style="height:2px;"></div>
                <?php
                $tottot = 0; $totfnd = 0;
                $sql0 = "SELECT * FROM subsetup where classname='$cls' and sectionname='$sec' and sccode='$sccode'  ";
                $result0x = $conn->query($sql0);
                if ($result0x->num_rows > 0) { while($row0 = $result0x->fetch_assoc()) { 
                    $subcode=$row0["subject"];  $tid=$row0["tid"];  
                    
                    $sql0x = "SELECT * FROM subjects where subcode='$subcode'  "; 
                    $result0xx = $conn->query($sql0x);
                    if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                        $subname=$row0x["subject"];  $subben=$row0x["subben"];}}
                    
                    $sql0x = "SELECT * FROM teacher where sccode='$sccode' and tid='$tid'  "; 
                    $result0xx = $conn->query($sql0x);
                    if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                        $tname=$row0x["tname"]; }}
                    
                    if($cls == 'Six' || $cls == 'Seven'){
                        $sql0x = "SELECT sum(continious) as ccc, sum(total) as ttt FROM pibitopics where exam='$exam' and sessionyear='$sy' and class='$cls' and subcode = '$subcode'  "; 
                        $result0xx = $conn->query($sql0x);
                        if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                            $ccc=$row0x["ccc"]; $ttt=$row0x["ttt"]; }}
                    
                        $sql0x = "SELECT count(*) as encnt from pibientry where exam='$exam' and sessionyear='$sy' and sccode = '$sccode' and  classname='$cls' and sectionname = '$sec' and subcode = '$subcode' and assessment>0 "; 
                        $result0xx = $conn->query($sql0x);
                        if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                            $encnt=$row0x["encnt"];  }}
                        
                    }
                ?>
                    
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk11();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="width:100%">        
                    <tr>
                        <td style="width:50px;color:var(--dark);"></td>
                        <td>
                            <h6 style="line-height:20px;"><?php echo $subname . '<br>' . $subben;?></h6>
                            <span style="font-size:13px; color:var(--darker); line-height:9px;"><?php echo $tname;?><br><br></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php $tot = $stcnt * ($ccc + $ttt + 10); $www = ceil($encnt * 100 / $tot);  $tottot = $tottot + $tot; $totfnd = $totfnd + $encnt; ?>
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:<?php echo $www;?>; height:3px; background:var(--dark);"></td>
                                    <td style="background:var(--light);"></td>
                                </tr>
                            </table>
                            <table style="width:100%;">
                                <tr>
                                    <td style=" color:var(--dark); font-size:12px;"><?php echo 'Record Found : <b>'.$encnt.'</b> / ' . $tot;?></td>
                                    <td style="color:var(--normal);  font-size:12px; text-align:right;"><?php echo $www.'%';?></td>
                                </tr>
                                <tr><td style="height:15px;"></td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            <?php }}?>
            
            <div class="card" style="background:var(--light); color:var(--darker); margin-top:30px;" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="width:100%; color:var(--darker);">
                    <tr>
                        <td style="width:50px;color:var(--darker);"><i class="material-icons">book</i></td>
                        <td>
                            <div style="float:right; font-size:24px; font-weight:700;" id="xpn"></div>
                            <h4 style="color:var(--darker);">My Subjects</h4>
                            <small style="color:var(--darker);">All my subjects that I evalute in the examinations</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:2px;"></div>
            

                
                <?php
                $tottot2 = 0; $totfnd2 = 0;
                $sql0 = "SELECT * FROM subsetup where tid='$userid' and sccode='$sccode'  ";
                $result0x = $conn->query($sql0);
                if ($result0x->num_rows > 0) { while($row0 = $result0x->fetch_assoc()) { 
                    $subcode=$row0["subject"];  $tid=$row0["tid"];   $clsmy=$row0["classname"];   $secmy=$row0["sectionname"];    $slid=$row0["id"];  
                    
                    $sql0x = "SELECT * FROM subjects where subcode='$subcode'  "; 
                    $result0xx = $conn->query($sql0x);
                    if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                        $subname=$row0x["subject"];  $subben=$row0x["subben"];}}
                    
                    $sql0x = "SELECT count(*) as stud FROM sessioninfo where sccode='$sccode' and sessionyear='$sy' and classname = '$clsmy' and sectionname='$secmy' and status =1  ";     
                    $result0xx = $conn->query($sql0x);
                    if ($result0xx->num_rows > 0) { while($row0x = $result0xx->fetch_assoc()) { 
                        $stud=$row0x["stud"]; }}
                    
                    if($clsmy == 'Six' || $clsmy == 'Seven'){
                        $sql0xg = "SELECT sum(continious) as cccc, sum(total) as tttt FROM pibitopics where exam='$exam' and sessionyear='$sy' and class='$clsmy' and subcode = '$subcode'  "; 
                        $result0xxg = $conn->query($sql0xg);
                        if ($result0xxg->num_rows > 0) { while($row0xg = $result0xxg->fetch_assoc()) { 
                            $cccc=$row0xg["cccc"]; $tttt=$row0xg["tttt"]; }}
                    
                        $sql0xf = "SELECT count(*) as encnt from pibientry where exam='$exam' and sessionyear='$sy' and sccode = '$sccode' and  classname='$clsmy' and sectionname = '$secmy' and subcode = '$subcode' and assessment>0  "; 
                        $result0xxf = $conn->query($sql0xf);
                        if ($result0xxf->num_rows > 0) { while($row0xf = $result0xxf->fetch_assoc()) { 
                            $encntt=$row0xf["encnt"];  }}
                        $tot2 = $stud * ($cccc + $tttt + 10); $www2 = ceil($encntt * 100 / $tot2);  
                        
                    } else {
                        $exm = $exam ;
                        $sql0xfr = "SELECT count(*) as encnt from stmark where exam='$exm' and sessionyear='$sy' and sccode = '$sccode' and  classname='$clsmy' and sectionname = '$secmy' and subject = '$subcode' and markobt >0 "; 
                        $result0xxfr = $conn->query($sql0xfr);
                        if ($result0xxfr->num_rows > 0) { while($row0xfr = $result0xxfr->fetch_assoc()) { 
                            $encntt=$row0xfr["encnt"];  }}
                       $tot2 = $stud; $www2 = ceil($encntt * 100 / $tot2); 
                    }
                    $tdone = $encntt; $treq = $tot2; $tperc = $www2;
                    $tottot2 = $tottot2 + $tot2; $totfnd2 = $totfnd2 + $encntt;
                ?>
                
                <div class="card" style="background:var(--darker); color:var(--lighter);" onclick="lnk15('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);" >
                    <div class="card-body">
                        <table style="width:100%">
                            <tr>
                                <td style="width:50px; color:var(--light);"></td>
                                <td style = "color:var(--light);">
                                    <h6 style="line-height:20px;"><?php echo $subname . '<br>' . $subben;?></h6>
                                    <span style="font-size:13px; color:var(--light); line-height:9px; font-weight:700;"><?php echo $clsmy . ' | ' . $secmy;?><br><br></span>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <?php  ?>
                                    
                                    <?php if($clsmy=='Six' || $clsmy == 'Seven'){
                                        $ca = 0; $ta = 0; $bi = 0;
                                            $sql242fra = "SELECT assesstype, count(*) as cnt FROM pibientry where sessionyear='$sy' and sccode='$sccode' and classname='$clsmy' and sectionname = '$secmy' and exam ='$exam' and subcode ='$subcode'  and assessment>0 group by assesstype order by field(assesstype, 'Continious Assessment','Total Assessment','Behavioural Assessment') "; 
                                            $result242fra = $conn->query($sql242fra); if ($result242fra->num_rows > 0) {while($row242fra = $result242fra->fetch_assoc()) {
                                            $asst=$row242fra['assesstype']; $pibidone=$row242fra['cnt']; 
                                            if($asst == 'Continious Assessment'){$ca = $pibidone;}
                                            if($asst == 'Total Assessment'){$ta = $pibidone;}
                                            if($asst == 'Behavioural Assessment'){$bi = $pibidone;}
                                            }}
                                            
                                            
                                            $sql242fr = "SELECT religion, count(*) as cnt FROM sessioninfo where sessionyear='$sy' and classname='$clsf' and sectionname='$secf' and sccode='$sccode' and status = 1 group by religion"; 
                                            $result242fr = $conn->query($sql242fr); if ($result242fr->num_rows > 0) {while($row242fr = $result242fr->fetch_assoc()) {
                                            $rel=$row242fr['religion']; $stcount=$row242fr['cnt']; 
                                                if($rel == 'Hindu'){$hindu = $stcount;}
                                                if($rel == 'Islam'){$islam = $stcount;}
                                            }}
                                            
                                            if($subcode == 906){ $stcount = $islam;} else 
                                            if($subcode == 907){ $stcount = $hindu;} else 
                                                                { $stcount = $hindu + $islam;}
                                            
                                            
                                            $careq = $stud * $cccc;  $tareq = $stud * $tttt;  $bireq = $stud * 10;
                                            $caperc = ceil($ca * 100 / $careq); $taperc = ceil($ta * 100 / $tareq); $biperc = ceil($bi * 100 / $bireq); 
                                            $cadeg = ceil($ca * 360 / $careq); $tadeg = ceil($ta * 360 / $tareq); $bideg = ceil($bi * 360 / $bireq); 
                                            
                                            $treq = $careq + $tareq + $bireq;
                                            $tdone = $ca + $ta + $bi;
                                            $tperc = ceil($tdone * 100 / $treq);
                                            
                                            
                                            ?>
                                    
                                    <table style="width:100%; color:white;">
                                        <tr>
                                            
                                            
                                            <td style="text-align:center">
                                                <div style="poisition:relative; margin:auto; text-align:center; border-radius:50%; height:60px; width:60px; background-image: conic-gradient(var(--dark) 0deg, var(--dark) <?php echo $cadeg;?>deg, var(--lighter) <?php echo $cadeg;?>deg, var(--lighter) 360deg);" id="ring<?php echo $slid;?>" onclick="lnk100('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);" >
                                                    <div style="border:1px solid purple; border-radius:50%; left:5px; top:5px; position:relative; background:var(--lighter); color:purple;;width:50px; height:50px; padding-top:15px;" onclick="lnk100('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);"><?php echo $caperc;?><small>%</small></div>
                                                </div>
                                            </td>
                                            <td style="text-align:center">
                                                <div style="poisition:relative; margin:auto; text-align:center; border-radius:50%; height:60px; width:60px; background-image: conic-gradient(var(--dark) 0deg, var(--dark) <?php echo $tadeg;?>deg, var(--lighter) <?php echo $tadeg;?>deg, var(--lighter) 360deg);" id="ring<?php echo $slid;?>" onclick="lnk200('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);" >
                                                    <div style="border:1px solid purple; border-radius:50%; left:5px; top:5px; position:relative; background:var(--lighter); color:purple;;width:50px; height:50px; padding-top:15px;" onclick="lnk200('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);"><?php echo $taperc;?><small>%</small></div>
                                                </div>
                                            </td>
                                            <td style="text-align:center">
                                                <div style="poisition:relative; margin:auto; text-align:center; border-radius:50%; height:60px; width:60px; background-image: conic-gradient(var(--dark) 0deg, var(--dark) <?php echo $bideg;?>deg, var(--lighter) <?php echo $bideg;?>deg, var(--lighter) 360deg);" id="ring<?php echo $slid;?>" onclick="lnk300('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);" >
                                                    <div style="border:1px solid purple; border-radius:50%; left:5px; top:5px; position:relative; background:var(--lighter); color:purple;;width:50px; height:50px; padding-top:15px;" onclick="lnk300('<?php echo $clsmy;?>','<?php echo $secmy;?>',<?php echo $subcode;?>);"><?php echo $biperc;?><small>%</small></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:9px; font-style:italic; color:white; text-align:center;"><?php echo $ca .'/'.$careq;?><br>Continious</td>
                                            <td style="font-size:9px; font-style:italic; color:white; text-align:center;"><?php echo $ta .'/'.$tareq;?><br>Total</td>
                                            <td style="font-size:9px; font-style:italic; color:white; text-align:center;"><?php echo $bi .'/'.$bireq;?><br>Behavioural</td>
                                        </tr>
                                        
                                    </table>
                                    <?php } ?> 
                                    <table style="width:100%;">
                                        <tr><td style="height:15px;"></td></tr>
                                        <tr>
                                            <td style=" color:var(--lighter); font-size:12px;"><?php echo 'Record Found : <b>' . $tdone.'</b> / ' . $treq;?></td>
                                            <td style="color:var(--normal);  font-size:12px; text-align:right;"><?php echo $tperc.'%';?></td>
                                        </tr>
                                    </table>
                                    
                                    <div style="background:white;">
                                        <div style="width:<?php echo $tperc;?>%; height:5px; background:seagreen;"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <?php if($usr == 'thisisengrreaz@gmail.com'){?>
                        
                        
                        <?php } ?>
                    </div>
                </div>         
                <div style="height:8px;"></div>
                
                <?php }}?>
                
                
            
            
            
            
           
        
        
        
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
  
    function go(){
        var cls=document.getElementById("classname").value; 
        var sec=document.getElementById("sectionname").value; 
        var sub=document.getElementById("subject").value; 
        var assess=document.getElementById("assessment").value; 
        var exam=document.getElementById("exam").value; 
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess; 
        if(cls=='Six'|| cls == 'Seven'){
            window.location.href="markpibi.php" + tail; 
        } else {
            window.location.href="markentry.php" + tail; 
        }
    }  
    
        function lnk1(){ window.location.href="grpview.php"; }
        function lnk2(){ window.location.href="pibisheet.php"; }
        function lnk3(){ window.location.href="markentryselect.php"; }
        function lnk4(){ window.location.href="transcriptselect.php"; }
        function lnk5(){ window.location.href="userlist.php"; }
        function lnk6(){ window.location.href="classes.php"; }
        function lnk7(){ window.location.href="transcriptselect.php"; }
        function lnk8(){ window.location.href="transcriptselect.php"; }
        
        
        
        function lnk100(cls, sec, sub){
            let x = "markpibi.php?exam=<?php echo $exam;?>&cls=" + cls + "&sec=" + sec + "&sub=" + sub + "&assess=Continious Assessment";
            window.location.href=x;
        }
        function lnk200(cls, sec, sub){
            let x = "markpibi.php?exam=<?php echo $exam;?>&cls=" + cls + "&sec=" + sec + "&sub=" + sub + "&assess=Total Assessment";
            window.location.href=x;
        }
        function lnk300(cls, sec, sub){
            let x = "markpibi.php?exam=<?php echo $exam;?>&cls=" + cls + "&sec=" + sec + "&sub=" + sub + "&assess=Behavioural Assessment";
            window.location.href=x;
        }
        
        function lnk15(cls, sec, sub){
            if(cls=='Six' || cls == 'Seven'){
                //window.location.href="markpibi.php?exam=<?php echo $exam;?>&cls=" + cls + "&sec=" + sec + "&sub=" + sub + "&assess=Total Assessment";
            } else {
                window.location.href="markentry.php?exam=<?php echo $exam;?>&cls=" + cls + "&sec=" + sec + "&sub=" + sub;
            }
        }
        
        
        function scan(id){
            let x = "pibiprocess.php?id=" + id;
            window.location.href=x;
        }
  </script>
  
  
  <script>
        
        let totfnd = <?php echo $totfnd;?>;
        let tottot = <?php echo $tottot;?>; 
        let val = totfnd * 100 / tottot;
        val = val.toFixed();
        document.getElementById("perc").innerHTML = val + '%';
        
        let totfnd2 = <?php echo $totfnd2;?>;
        let tottot2 = <?php echo $tottot2;?>; 
        let val2 = totfnd2 * 100 / tottot2;
        val2 = val2.toFixed();
        document.getElementById("xpn").innerHTML = val2 + '%';
        
        
        
        
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
  </script>
    
    
  
</body>

</html>