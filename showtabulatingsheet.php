<?php 
include 'incc.php';


	$sessionyear= $sy;//date("Y");;
	$sessionyear=$sy;;
	$paper=$_GET['paper'];;
	//$paper = '320mm';

	$cn=$_GET['classname'];;
	$secname=$_GET['sectionname'];;
	$exam=$_GET['exam'];;
	$etdt = date('Y-m-d H:i:s');;
	$cover=$_GET['cover'];;
    $goga = $sessionyear ;;
	
	$topsheet=$_GET['topsheet'];;
	$top50=$_GET['top50'];;
	$coverpage=$_GET['coverpage'];;


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
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        h4{font-size:20px; color:var(--darker); line-height:12px; font-weight:700;}
        h5{font-size:16px; color:var(--dark); line-height:12px; font-weight:500;}
        small{font-size:10px; color:gray; line-height:10px; margin:3px 0 8px;}
        table, tr, td {border:1px solid black;}
    </style>
</head>

<body style="background:white;">
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid noprint">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  >
          
            <div class="card-body">
                <div style="float:right;">
                    <a href="index.php" class="btn btn-light"><i class="material-icons" style="font-size:35px;color:black;">home</i></a>
                </div>
                <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                    Tabulating Sheet
                </div>
                <center>
                    <small style="font-size:12px; color:white; line-height:16px;">
                        PRINT INSTRUCTION<br>&bull; For better printing experience, use <b>Chrome</b> browser. &bull; Always use <b>A4</b> as paper size and <b>Default Margin</b> with <b>Landscape</b> paper orientation.
                    </small>
                </center>
            </div>
        </div>
        <div style="height:8px;"></div>
    

        
    </div>
    
    
    
    
    
<?php



		
		                                    $sql0001110 = "SELECT * FROM users where  eiin='$sccode' and user_level=100 "; 
                                            $result0001110 = $conn->query($sql0001110);
                                            if ($result0001110->num_rows > 0) 
                                            {while($row0001110 = $result0001110->fetch_assoc()) { 
                                            $einame=$row0001110["einame"];
                                            $eiadd=$row0001110["eiaddress"];
                                            $eicontact=$row0001110["eicontact"];
                                            $email=$row0001110["user_email"];
                                            }}
                                            
                                            $sdate = $sessionyear . '-01-01';
                                            $edate = $sessionyear . '-12-31';
                                            
                                            $sql0001110e = "SELECT * FROM holiday where  sccode='$sccode' and reason like '%$exam%' and hdtype='Event' and date between '$sdate' and '$edate' "; 
                                            $result0001110e = $conn->query($sql0001110e);
                                            if ($result0001110e->num_rows > 0) 
                                            {while($row0001110e = $result0001110e->fetch_assoc()) { 
                                            $rsn=$row0001110e["date"];
                                            }}
                                            
                                            $eicontact = $email;
                                       
                                       
                              
?>

        <style type="text/css" >
        @page
        {
            size: auto; /* auto is the initial value */
            margin: 12mm; /* this affects the margin in the printer settings */
            -webkit-print-color-adjust: exact !important;
        }
        body{
            -webkit-print-color-adjust: exact !important;
        }
        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }
        .chip {line-height:11px; padding:3px !important;}
        
        @media print {

           * {
            -webkit-print-color-adjust: exact !important;
            }
            .noprint {display:none;}
            
            thead { display: table-header-group; }
tfoot { display: table-footer-group; }
        }
    </style>

   
<div>
    
    
    <?php 

    if(($topsheet=='true')||($top50=='true')||($coverpage=='true')){
    if ($topsheet=='true'){
        include 'result_top_sheet.php'; }
    if ($top50=='true'){
      //  include 'passfail.php';
        include 'top_50_merit_list.php';
        
    }
    if ($coverpage=='true'){
        include 'tabulating_cover.php';
        include 'tabulating_back.php';}
    } else{


    
    ?>



<!--<table class="table  bordered border" style="-fs-table-paginate: paginate; padding:0; margin:0; width:320mm; margin-left:15mm;">
    -->
    <table class="table  bordered border" style="-fs-table-paginate: paginate; padding:0; margin:0;">
    <tfoot>
       <tr> <td colspan="19"><span style="font-size:11px; font-weight:normal; color:#1f43af; border: 1px solid white !important; padding:0 !important;">
            <table border="0" width="100%" style="border:0;">
                <tr style="border:0;">
                    <td style="border:0;">
                        
                        Result Published on : <b><?php $rsn = date('Y-m-d');; echo date('l, d F, Y', strtotime($rsn));?></b>
                    </td>
                    <td  style="border:0;" width="25%"><center>Signature (Class Teacher)</center></td>
                    <td style="border:0;" width="25%"><center>Signature (Headmaster)</center></td>
                </tr>
            </table>
            </span>
        </td>
        </tr>
    </tfoot>
                  
                     
    <thead>
    <tr>
     <td  colspan="22" >
     <table border = "0" width="100%" style="height:0mm;border:0;" >
         <tr style="border:0;">
             <td style="background-color:#ffffff !important; height:10mm; padding:0; margin:0; border:0;">
                <span style="font-size:12px; color:#33a04e" onclick="sh();">
                <?php echo $einame ?>
                </span>
                <br>
                <span style="font-size:10px;">
               <?php echo $eiadd . '<br>' . $eicontact;?>
                </span>
             </td>
             <td  style="text-align:right; background-color:#ffffff !important; border:0;">
                <span style="font-size:11px; text-decoration:none;">
                <?php echo 'Class : <span style="color:blue;">' . $cn . '</span> | Section/Group : <span style="color:blue;">' . $secname . '</span><br>' . $exam . ' | ' . $goga;?>
                </span>
             </td>
         </tr>
     </table>
     </td>
    </tr>
 


  
  
                  
                  <tr>
							<td><span class="tag bg-white">Roll</span></td>
							<td><span class="tag bg-white">Student</span></td>
							<td><span class="tag bg-white"></span></td>
					<?php	                
					$sql0001110a = "SELECT count(*) as tts FROM subsetup where  classname='$cn' and sectionname='$secname'  and sccode='$sccode' ";
                                            $result0001110a = $conn->query($sql0001110a);
                                            if ($result0001110a->num_rows > 0) 
                                            {while($row0001110a = $result0001110a->fetch_assoc()) { 
                                            $tts=$row0001110a["tts"];}}
                                            
                                           
					
					$sql0001110 = "SELECT * FROM subsetup where  classname='$cn' and sectionname='$secname'  and sccode='$sccode' and sessionyear='$sy' order by subject "; 
                                            $result0001110 = $conn->query($sql0001110);
                                            if ($result0001110->num_rows > 0) 
                                            {while($row0001110 = $result0001110->fetch_assoc()) { 
                                            $subsh=$row0001110["subject"];
                                            
                                            	$sql0001110f = "SELECT * FROM subjects where  subcode='$subsh'" ;
                                            $result0001110f = $conn->query($sql0001110f);
                                            if ($result0001110f->num_rows > 0) 
                                            {while($row0001110f = $result0001110f->fetch_assoc()) { 
                                            $subs=$row0001110f["subshname"];}}
                                           // if($subs=='HM'){$subs='HM/ HSc';}
                                             echo '<td><span class="tag bg-white">'. $subs . '</span></td>';  
                                                
                                                
                                            }}
                        
                        $rsub = 15-$tts;
                        for ($lps = 0; $lps<$rsub; $lps++){
                            echo '<td><span class="tag bg-white"></span></td>';
                        }
                        
                        
                        ?>
						<td></td>
					<td><span class="tag bg-white">BEN</span></td>
					<td><span class="tag bg-white">ENG</span></td>
							
							<td><span class="tag bg-white">STAT</span></td>
							<td><span class="tag bg-white">Merit</span></td>
					</tr>
						
							</thead>
							
							<tbody>
							<?php 
								$sql0001 = "SELECT * FROM sessioninfo where sccode='$sccode' and classname='$cn'  and sectionname = '$secname' and sessionyear = '$sessionyear' 
											order by rollno";
								$result0001 = $conn->query($sql0001);
								if ($result0001->num_rows > 0) 
							{while($row0001 = $result0001->fetch_assoc()) { 
							$rollno=$row0001["rollno"];
							$fourth=$row0001["fourth_subject"];
							$stid=$row0001["stid"];
							
							
								$sql00011 = "SELECT * FROM students where stid='$stid' ";
								$result00011 = $conn->query($sql00011);
								if ($result00011->num_rows > 0) 
							{while($row00011 = $result00011->fetch_assoc()) { 
							$stnameeng=$row00011["stnameeng"];$stnameben=$row00011["stnameben"];
							}}
							
								$sql000111 = "SELECT * FROM tabulatingsheet where exam='$exam' and classname='$cn' and sectionname='$secname' and sessionyear = '$sessionyear' and stid='$stid' ";
						
								$result000111 = $conn->query($sql000111);
								if ($result000111->num_rows > 0) 
								{while($row000111 = $result000111->fetch_assoc()) { 
								    $sub_1=$row000111["sub_1"];
                                    $sub_1_sub=$row000111["sub_1_sub"];
                                    $sub_1_obj=$row000111["sub_1_obj"];
                                    $sub_1_pra=$row000111["sub_1_pra"];
                                    $sub_1_ca=$row000111["sub_1_ca"];
                                    $sub_1_total=$row000111["sub_1_total"];
                                    $sub_1_gp=$row000111["sub_1_gp"];
                                    $sub_1_gl=$row000111["sub_1_gl"];
                                    $sub_2=$row000111["sub_2"];
                                    $sub_2_sub=$row000111["sub_2_sub"];
                                    $sub_2_obj=$row000111["sub_2_obj"];
                                    $sub_2_pra=$row000111["sub_2_pra"];
                                    $sub_2_ca=$row000111["sub_2_ca"];
                                    $sub_2_total=$row000111["sub_2_total"];
                                    $sub_2_gp=$row000111["sub_2_gp"];
                                    $sub_2_gl=$row000111["sub_2_gl"];
                                    $sub_3=$row000111["sub_3"];
                                    $sub_3_sub=$row000111["sub_3_sub"];
                                    $sub_3_obj=$row000111["sub_3_obj"];
                                    $sub_3_pra=$row000111["sub_3_pra"];
                                    $sub_3_ca=$row000111["sub_3_ca"];
                                    $sub_3_total=$row000111["sub_3_total"];
                                    $sub_3_gp=$row000111["sub_3_gp"];
                                    $sub_3_gl=$row000111["sub_3_gl"];
                                    $sub_4=$row000111["sub_4"];
                                    $sub_4_sub=$row000111["sub_4_sub"];
                                    $sub_4_obj=$row000111["sub_4_obj"];
                                    $sub_4_pra=$row000111["sub_4_pra"];
                                    $sub_4_ca=$row000111["sub_4_ca"];
                                    $sub_4_total=$row000111["sub_4_total"];
                                    $sub_4_gp=$row000111["sub_4_gp"];
                                    $sub_4_gl=$row000111["sub_4_gl"];
                                    $sub_5=$row000111["sub_5"];
                                    $sub_5_sub=$row000111["sub_5_sub"];
                                    $sub_5_obj=$row000111["sub_5_obj"];
                                    $sub_5_pra=$row000111["sub_5_pra"];
                                    $sub_5_ca=$row000111["sub_5_ca"];
                                    $sub_5_total=$row000111["sub_5_total"];
                                    $sub_5_gp=$row000111["sub_5_gp"];
                                    $sub_5_gl=$row000111["sub_5_gl"];
                                    $sub_6=$row000111["sub_6"];
                                    $sub_6_sub=$row000111["sub_6_sub"];
                                    $sub_6_obj=$row000111["sub_6_obj"];
                                    $sub_6_pra=$row000111["sub_6_pra"];
                                    $sub_6_ca=$row000111["sub_6_ca"];
                                    $sub_6_total=$row000111["sub_6_total"];
                                    $sub_6_gp=$row000111["sub_6_gp"];
                                    $sub_6_gl=$row000111["sub_6_gl"];
                                    $sub_7=$row000111["sub_7"];
                                    $sub_7_sub=$row000111["sub_7_sub"];
                                    $sub_7_obj=$row000111["sub_7_obj"];
                                    $sub_7_pra=$row000111["sub_7_pra"];
                                    $sub_7_ca=$row000111["sub_7_ca"];
                                    $sub_7_total=$row000111["sub_7_total"];
                                    $sub_7_gp=$row000111["sub_7_gp"];
                                    $sub_7_gl=$row000111["sub_7_gl"];
                                    $sub_8=$row000111["sub_8"];
                                    $sub_8_sub=$row000111["sub_8_sub"];
                                    $sub_8_obj=$row000111["sub_8_obj"];
                                    $sub_8_pra=$row000111["sub_8_pra"];
                                    $sub_8_ca=$row000111["sub_8_ca"];
                                    $sub_8_total=$row000111["sub_8_total"];
                                    $sub_8_gp=$row000111["sub_8_gp"];
                                    $sub_8_gl=$row000111["sub_8_gl"];
                                    $sub_9=$row000111["sub_9"];
                                    $sub_9_sub=$row000111["sub_9_sub"];
                                    $sub_9_obj=$row000111["sub_9_obj"];
                                    $sub_9_pra=$row000111["sub_9_pra"];
                                    $sub_9_ca=$row000111["sub_9_ca"];
                                    $sub_9_total=$row000111["sub_9_total"];
                                    $sub_9_gp=$row000111["sub_9_gp"];
                                    $sub_9_gl=$row000111["sub_9_gl"];
                                    
                                    $sub_10=$row000111["sub_10"];
                                    $sub_10_sub=$row000111["sub_10_sub"];
                                    $sub_10_obj=$row000111["sub_10_obj"];
                                    $sub_10_pra=$row000111["sub_10_pra"];
                                    $sub_10_ca=$row000111["sub_10_ca"];
                                    $sub_10_total=$row000111["sub_10_total"];
                                    $sub_10_gp=$row000111["sub_10_gp"];
                                    $sub_10_gl=$row000111["sub_10_gl"];
									
									$sub_11=$row000111["sub_11"];
                                    $sub_11_sub=$row000111["sub_11_sub"];
                                    $sub_11_obj=$row000111["sub_11_obj"];
                                    $sub_11_pra=$row000111["sub_11_pra"];
                                    $sub_11_ca=$row000111["sub_11_ca"];
                                    $sub_11_total=$row000111["sub_11_total"];
                                    $sub_11_gp=$row000111["sub_11_gp"];
                                    $sub_11_gl=$row000111["sub_11_gl"];
									
									$sub_12=$row000111["sub_12"];
                                    $sub_12_sub=$row000111["sub_12_sub"];
                                    $sub_12_obj=$row000111["sub_12_obj"];
                                    $sub_12_pra=$row000111["sub_12_pra"];
                                    $sub_12_ca=$row000111["sub_12_ca"];
                                    $sub_12_total=$row000111["sub_12_total"];
                                    $sub_12_gp=$row000111["sub_12_gp"];
                                    $sub_12_gl=$row000111["sub_12_gl"];

									
									$sub_13=$row000111["sub_13"];
                                    $sub_13_sub=$row000111["sub_13_sub"];
                                    $sub_13_obj=$row000111["sub_13_obj"];
                                    $sub_13_pra=$row000111["sub_13_pra"];
                                    $sub_13_ca=$row000111["sub_13_ca"];
                                    $sub_13_total=$row000111["sub_13_total"];
                                    $sub_13_gp=$row000111["sub_13_gp"];
                                    $sub_13_gl=$row000111["sub_13_gl"];

									$sub_14=$row000111["sub_14"];
                                    $sub_14_sub=$row000111["sub_14_sub"];
                                    $sub_14_obj=$row000111["sub_14_obj"];
                                    $sub_14_pra=$row000111["sub_14_pra"];
                                    $sub_14_ca=$row000111["sub_14_ca"];
                                    $sub_14_total=$row000111["sub_14_total"];
                                    $sub_14_gp=$row000111["sub_14_gp"];
                                    $sub_14_gl=$row000111["sub_14_gl"];
									
									$sub_15=$row000111["sub_15"];
                                    $sub_15_sub=$row000111["sub_15_sub"];
                                    $sub_15_obj=$row000111["sub_15_obj"];
                                    $sub_15_pra=$row000111["sub_15_pra"];
                                    $sub_15_ca=$row000111["sub_15_ca"];
                                    $sub_15_total=$row000111["sub_15_total"];
                                    $sub_15_gp=$row000111["sub_15_gp"];
                                    $sub_15_gl=$row000111["sub_15_gl"];

                                    $ben=$row000111["ben"];
                                    $ben_sub=$row000111["ben_sub"];
                                    $ben_obj=$row000111["ben_obj"];
                                    $ben_pra=$row000111["ben_pra"];
                                    $ben_ca=$row000111["ben_ca"];
                                    $ben_total=$row000111["ben_total"];
                                    $ben_gp=$row000111["ben_gp"];
                                    $ben_gl=$row000111["ben_gl"];
                                    
                                    $eng=$row000111["eng"];
                                    $eng_sub=$row000111["eng_sub"];
                                    $eng_obj=$row000111["eng_obj"];
                                    $eng_pra=$row000111["eng_pra"];
                                    $eng_ca=$row000111["eng_ca"];
                                    $eng_total=$row000111["eng_total"];
                                    $eng_gp=$row000111["eng_gp"];
                                    $eng_gl=$row000111["eng_gl"];

                                    $totalmarks=$row000111["totalmarks"];
								    $avgrate=$row000111["avgrate"];
								    $gpa=$row000111["gpa"];
								    $gla=$row000111["gla"];
								    $meritplace=$row000111["meritplace"];
								    $totalfail=$row000111["totalfail"];
								    
								    
								    $boygirl=$row000111["gender"];
								    $thisex=$row000111["thisexam"];  $prevex=$row000111["prevexam"];  
								    
								
							
								
								}} else {
								    $sub_1=0;
                                    $sub_1_sub=0;
                                    $sub_1_obj=0;
                                    $sub_1_pra=0;
                                    $sub_1_ca=0;
                                    $sub_1_total=0;
                                    $sub_1_gp=0;
                                    $sub_1_gl=0;
                                    $sub_2=0;
                                    $sub_2_sub=0;
                                    $sub_2_obj=0;
                                    $sub_2_pra=0;
                                    $sub_2_ca=0;
                                    $sub_2_total=0;
                                    $sub_2_gp=0;
                                    $sub_2_gl=0;
                                    $sub_3=0;
                                    $sub_3_sub=0;
                                    $sub_3_obj=0;
                                    $sub_3_pra=0;
                                    $sub_3_ca=0;
                                    $sub_3_total=0;
                                    $sub_3_gp=0;
                                    $sub_3_gl=0;
                                    $sub_4=0;
                                    $sub_4_sub=0;
                                    $sub_4_obj=0;
                                    $sub_4_pra=0;
                                    $sub_4_ca=0;
                                    $sub_4_total=0;
                                    $sub_4_gp=0;
                                    $sub_4_gl=0;
                                    $sub_5=0;
                                    $sub_5_sub=0;
                                    $sub_5_obj=0;
                                    $sub_5_pra=0;
                                    $sub_5_ca=0;
                                    $sub_5_total=0;
                                    $sub_5_gp=0;
                                    $sub_5_gl=0;
                                    $sub_6=0;
                                    $sub_6_sub=0;
                                    $sub_6_obj=0;
                                    $sub_6_pra=0;
                                    $sub_6_ca=0;
                                    $sub_6_total=0;
                                    $sub_6_gp=0;
                                    $sub_6_gl=0;
                                    $sub_7=0;
                                    $sub_7_sub=0;
                                    $sub_7_obj=0;
                                    $sub_7_pra=0;
                                    $sub_7_ca=0;
                                    $sub_7_total=0;
                                    $sub_7_gp=0;
                                    $sub_7_gl=0;
                                    $sub_8=0;
                                    $sub_8_sub=0;
                                    $sub_8_obj=0;
                                    $sub_8_pra=0;
                                    $sub_8_ca=0;
                                    $sub_8_total=0;
                                    $sub_8_gp=0;
                                    $sub_8_gl=0;
                                    $sub_9=0;
                                    $sub_9_sub=0;
                                    $sub_9_obj=0;
                                    $sub_9_pra=0;
                                    $sub_9_ca=0;
                                    $sub_9_total=0;
                                    $sub_9_gp=0;
                                    $sub_9_gl=0;
                                    
                                    $sub_10=0;
                                    $sub_10_sub=0;
                                    $sub_10_obj=0;
                                    $sub_10_pra=0;
                                    $sub_10_ca=0;
                                    $sub_10_total=0;
                                    $sub_10_gp=0;
                                    $sub_10_gl=0;
									
									$sub_11=0;
                                    $sub_11_sub=0;
                                    $sub_11_obj=0;
                                    $sub_11_pra=0;
                                    $sub_11_ca=0;
                                    $sub_11_total=0;
                                    $sub_11_gp=0;
                                    $sub_11_gl=0;
									
									$sub_12=0;
                                    $sub_12_sub=0;
                                    $sub_12_obj=0;
                                    $sub_12_pra=0;
                                    $sub_12_ca=0;
                                    $sub_12_total=0;
                                    $sub_12_gp=0;
                                    $sub_12_gl=0;

									
									$sub_13=0;
                                    $sub_13_sub=0;
                                    $sub_13_obj=0;
                                    $sub_13_pra=0;
                                    $sub_13_ca=0;
                                    $sub_13_total=0;
                                    $sub_13_gp=0;
                                    $sub_13_gl=0;

									$sub_14=0;
                                    $sub_14_sub=0;
                                    $sub_14_obj=0;
                                    $sub_14_pra=0;
                                    $sub_14_ca=0;
                                    $sub_14_total=0;
                                    $sub_14_gp=0;
                                    $sub_14_gl=0;
									
									$sub_15=0;
                                    $sub_15_sub=0;
                                    $sub_15_obj=0;
                                    $sub_15_pra=0;
                                    $sub_15_ca=0;
                                    $sub_15_total=0;
                                    $sub_15_gp=0;
                                    $sub_15_gl=0;

                                    $ben=0;
                                    $ben_sub=0;
                                    $ben_obj=0;
                                    $ben_pra=0;
                                    $ben_ca=0;
                                    $ben_total=0;
                                    $ben_gp=0;
                                    $ben_gl=0;
                                    
                                    $eng=0;
                                    $eng_sub=0;
                                    $eng_obj=0;
                                    $eng_pra=0;
                                    $eng_ca=0;
                                    $eng_total=0;
                                    $eng_gp=0;
                                    $eng_gl=0;

                                    $totalmarks=0;
								    $avgrate=0;
								    $gpa=0;
								    $gla=0;
								    $meritplace=0;
								    $totalfail=0;
								    
								}
								
								
								  
								    if($sub_1_total == 0){$cllr_1 = '#ffffff';} else {if($sub_1_gp == '0'){$cllr_1 = 'red';} else if($sub_1_gp == '5'){$cllr_1 = '#33a04e';} else {$cllr_1 = 'black';}}
								    if($sub_2_total == 0){$cllr_2 = '#ffffff';} else {if($sub_2_gp == '0'){$cllr_2 = 'red';} else if($sub_2_gp == '5'){$cllr_2 = '#33a04e';} else {$cllr_2 = 'black';}}
								    if($sub_3_total == 0){$cllr_3 = '#ffffff';} else {if($sub_3_gp == '0'){$cllr_3 = 'red';} else if($sub_3_gp == '5'){$cllr_3 = '#33a04e';} else {$cllr_3 = 'black';}}
								    if($sub_4_total == 0){$cllr_4 = '#ffffff';} else {if($sub_4_gp == '0'){$cllr_4 = 'red';} else if($sub_4_gp == '5'){$cllr_4 = '#33a04e';} else {$cllr_4 = 'black';}}
								    if($sub_5_total == 0){$cllr_5 = '#ffffff';} else {if($sub_5_gp == '0'){$cllr_5 = 'red';} else if($sub_5_gp == '5'){$cllr_5 = '#33a04e';} else {$cllr_5 = 'black';}}
								    if($sub_6_total == 0){$cllr_6 = '#ffffff';} else {if($sub_6_gp == '0'){$cllr_6 = 'red';} else if($sub_6_gp == '5'){$cllr_6 = '#33a04e';} else {$cllr_6 = 'black';}}
								    if($sub_7_total == 0){$cllr_7 = '#ffffff';} else {if($sub_7_gp == '0'){$cllr_7 = 'red';} else if($sub_7_gp == '5'){$cllr_7 = '#33a04e';} else {$cllr_7 = 'black';}}
								    if($sub_8_total == 0){$cllr_8 = '#ffffff';} else {if($sub_8_gp == '0'){$cllr_8 = 'red';} else if($sub_8_gp == '5'){$cllr_8 = '#33a04e';} else {$cllr_8 = 'black';}}
								    if($sub_9_total == 0){$cllr_9 = "#ffffff";} else {if($sub_9_gp == '0'){$cllr_9 = 'red';} else if($sub_9_gp == '5'){$cllr_9 = '#33a04e';} else {$cllr_9 = 'black';}}
								    if($sub_10_total == 0){$cllr_10 = '#ffffff';} else {if($sub_10_gp == '0'){$cllr_10 = 'red';} else if($sub_10_gp == '5'){$cllr_10 = '#33a04e';} else {$cllr_10 = 'black';}}
								    if($sub_11_total == 0){$cllr_11 = '#ffffff';} else {if($sub_11_gp == '0'){$cllr_11 = 'red';} else if($sub_11_gp == '5'){$cllr_11 = '#33a04e';} else {$cllr_11 = 'black';}}
								    if($sub_12_total == 0){$cllr_12 = '#ffffff';} else {if($sub_12_gp == '0'){$cllr_12 = 'red';} else if($sub_12_gp == '5'){$cllr_12 = '#33a04e';} else {$cllr_12 = 'black';}}
								    if($sub_13_total == 0){$cllr_13 = '#ffffff';} else {if($sub_13_gp == '0'){$cllr_13 = 'red';} else if($sub_13_gp == '5'){$cllr_13 = '#33a04e';} else {$cllr_13 = 'black';}}
								    if($sub_14_total == 0){$cllr_14 = '#ffffff';} else {if($sub_14_gp == '0'){$cllr_14 = 'red';} else if($sub_14_gp == '5'){$cllr_14 = '#33a04e';} else {$cllr_14 = 'black';}}
								    if($sub_15_total == 0){$cllr_15 = '#ffffff';} else {if($sub_15_gp == '0'){$cllr_15 = 'red';} else if($sub_15_gp == '5'){$cllr_15 = '#33a04e';} else {$cllr_15 = 'black';}}
								    if($ben_total == 0){$cllr_ben = '#ffffff';} else {if($ben_gp == '0'){$cllr_ben = 'red';} else if($ben_gp == '5'){$cllr_ben = '#33a04e';} else {$cllr_ben = 'black';}}
								    if($eng_total == 0){$cllr_eng = '#ffffff';} else {if($eng_gp == '0'){$cllr_eng = 'red';} else if($eng_gp == '5'){$cllr_eng = '#33a04e';} else {$cllr_eng = 'black';}}
								    
						
								    
								    
								    
								    
								    
								   
								    
								    if($gpa == '0'){$cllr_gpa = 'red';} else if($gpa == '5'){$cllr_gpa = '#33a04e';} else {$cllr_gpa = 'black';}
								    
								    if($totalmarks == 0){$cllr_gpa = '#ffffff'; $meritplace = '';}
								    //echo $fourth;
								    if($sub_1 == $fourth){$op_1 = '*';} else {$op_1 = '';}
								    if($sub_2 == $fourth){$op_2 = '*';} else {$op_2 = '';}
								    if($sub_3 == $fourth){$op_3 = '*';} else {$op_3 = '';}
								    if($sub_4 == $fourth){$op_4 = '*';} else {$op_4 = '';}
								    if($sub_5 == $fourth){$op_5 = '*';} else {$op_5 = '';}
								    if($sub_6 == $fourth){$op_6 = '*';} else {$op_6 = '';}
								    if($sub_7 == $fourth){$op_7 = '*';} else {$op_7 = '';}
								    if($sub_8 == $fourth){$op_8 = '*';} else {$op_8 = '';}
								    if($sub_9 == $fourth){$op_9 = '*';} else {$op_9 = '';}
								    if($sub_10 == $fourth){$op_10 = '*';} else {$op_10 = '';}
								    if($sub_11 == $fourth){$op_11 = '*';} else {$op_11 = '';}
								    if($sub_12 == $fourth){$op_12 = '*';} else {$op_12 = '';}
								    if($sub_13 == $fourth){$op_13 = '*';} else {$op_13 = '';}
								    if($sub_14 == $fourth){$op_14 = '*';} else {$op_14 = '';}
								    if($sub_15 == $fourth){$op_15 = '*';} else {$op_15 = '';}
								    
								  
								if($totalmarks==0){
								    $stl = 'border:1px solid red; padding:3px; border-radius:3px;';
								} else {
								    $stl = '';
								}
								
						
							?>
							<tr>
							<td><center><span style="<?php echo $stl;?>"><?php echo $rollno;?></span></center></td>
							<td><?php echo $stnameeng . '<br>' . $stnameben;?><br>
							    <span style="font-size:9px; color: #ba4b1b;"><?php echo $stid;?></span>
							</td>
							<td class="chip" style="font-size:11px;"><center>S<br>O<br>P<br>C<br>T<br>G<br>L</center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_1;?>;"><center><?php if($sub_1>0){ echo $sub_1_sub . '<br>' .$sub_1_obj . '<br>' .$sub_1_pra . '<br>' .$sub_1_ca . '<br><b>' .$sub_1_total . '</b><br>' .$sub_1_gp . $op_1 . '<br>' .$sub_1_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_2;?>;"><center><?php if($sub_2>0){ echo $sub_2_sub . '<br>' .$sub_2_obj . '<br>' .$sub_2_pra . '<br>' .$sub_2_ca . '<br><b>' .$sub_2_total . '</b><br>' .$sub_2_gp . $op_2 . '<br>' .$sub_2_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_3;?>;"><center><?php if($sub_3>0){ echo $sub_3_sub . '<br>' .$sub_3_obj . '<br>' .$sub_3_pra . '<br>' .$sub_3_ca . '<br><b>' .$sub_3_total . '</b><br>' .$sub_3_gp . $op_3 . '<br>' .$sub_3_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_4;?>;"><center><?php if($sub_4>0){ echo $sub_4_sub . '<br>' .$sub_4_obj . '<br>' .$sub_4_pra . '<br>' .$sub_4_ca . '<br><b>' .$sub_4_total . '</b><br>' .$sub_4_gp . $op_4 . '<br>' .$sub_4_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_5;?>;"><center><?php if($sub_5>0){ echo $sub_5_sub . '<br>' .$sub_5_obj . '<br>' .$sub_5_pra . '<br>' .$sub_5_ca . '<br><b>' .$sub_5_total . '</b><br>' .$sub_5_gp . $op_5 . '<br>' .$sub_5_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_6;?>;"><center><?php if($sub_6>0){ echo $sub_6_sub . '<br>' .$sub_6_obj . '<br>' .$sub_6_pra . '<br>' .$sub_6_ca . '<br><b>' .$sub_6_total . '</b><br>' .$sub_6_gp . $op_6 . '<br>' .$sub_6_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_7;?>;"><center><?php if($sub_7>0){ echo $sub_7_sub . '<br>' .$sub_7_obj . '<br>' .$sub_7_pra . '<br>' .$sub_7_ca . '<br><b>' .$sub_7_total . '</b><br>' .$sub_7_gp . $op_7 . '<br>' .$sub_7_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_8;?>;"><center><?php if($sub_8>0){ echo $sub_8_sub . '<br>' .$sub_8_obj . '<br>' .$sub_8_pra . '<br>' .$sub_8_ca . '<br><b>' .$sub_8_total . '</b><br>' .$sub_8_gp . $op_8 . '<br>' .$sub_8_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_9;?>;"><center><?php if($sub_9>0){ echo $sub_9_sub . '<br>' .$sub_9_obj . '<br>' .$sub_9_pra . '<br>' .$sub_9_ca . '<br><b>' .$sub_9_total . '</b><br>' .$sub_9_gp . $op_9 . '<br>' .$sub_9_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_10;?>;"><center><?php if($sub_10>0){ echo $sub_10_sub . '<br>' .$sub_10_obj . '<br>' .$sub_10_pra . '<br>' .$sub_10_ca . '<br><b>' .$sub_10_total . '</b><br>' .$sub_10_gp . $op_10 . '<br>' .$sub_10_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_11;?>;"><center><?php if($sub_11>0){ echo $sub_11_sub . '<br>' .$sub_11_obj . '<br>' .$sub_11_pra . '<br>' .$sub_11_ca . '<br><b>' .$sub_11_total . '</b><br>' .$sub_11_gp . $op_11 . '<br>' .$sub_11_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_12;?>;"><center><?php if($sub_12>0){ echo $sub_12_sub . '<br>' .$sub_12_obj . '<br>' .$sub_12_pra . '<br>' .$sub_12_ca . '<br><b>' .$sub_12_total . '</b><br>' .$sub_12_gp . $op_12 . '<br>' .$sub_12_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_13;?>;"><center><?php if($sub_13>0){ echo $sub_13_sub . '<br>' .$sub_13_obj . '<br>' .$sub_13_pra . '<br>' .$sub_13_ca . '<br><b>' .$sub_13_total . '</b><br>' .$sub_13_gp . $op_13 . '<br>' .$sub_13_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_14;?>;"><center><?php if($sub_14>0){ echo $sub_14_sub . '<br>' .$sub_14_obj . '<br>' .$sub_14_pra . '<br>' .$sub_14_ca . '<br><b>' .$sub_14_total . '</b><br>' .$sub_14_gp . $op_14 . '<br>' .$sub_14_gl . '<br>' ;}?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_15;?>;"><center><?php if($sub_15>0){ echo $sub_15_sub . '<br>' .$sub_15_obj . '<br>' .$sub_15_pra . '<br>' .$sub_15_ca . '<br><b>' .$sub_15_total . '</b><br>' .$sub_15_gp . $op_15 . '<br>' .$sub_15_gl . '<br>' ;}?> </center></td>
                            
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_ben;?>;"><center><?php echo $ben_sub . '<br>' .$ben_obj . '<br>' .$ben_pra . '<br>' .$ben_ca . '<br><b>' .$ben_total . '</b><br>' .$ben_gp . '<br>' .$ben_gl . '<br>' ;?> </center></td>
                            <td class="chip" style="font-size:11px; color:<?php echo $cllr_eng;?>;"><center><?php echo $eng_sub . '<br>' .$eng_obj . '<br>' .$eng_pra . '<br>' .$eng_ca . '<br><b>' .$eng_total . '</b><br>' .$eng_gp . '<br>' .$eng_gl . '<br>' ;?> </center></td>
                            
                            <td style="font-size:11px; color:<?php echo $cllr_gpa;?>;"><center><?php echo '<b>' . $thisex . '</b><br><b>' .sprintf('%0.2f',$avgrate) . '%</b><br>' .sprintf('%0.2f',$gpa) . '<br>' .$gla  ;?> </center></td>
                            <?php
                                if(($meritplace=='1st')||($meritplace=="2nd")||($meritplace == '3rd')){$fs=16; $fw='bold';} else {$fs=11; $fw='normal';}
                                //if($gpa==0){$meritplace='';}
                                
                                
                                
                            ?>
                            <td style="font-size:<?php echo $fs;?>px; font-weight: <?php echo $fw;?> ;color:<?php echo $cllr_gpa;?>;"><center><?php echo $meritplace; ?> </center></td>

							
							
					
							</tr>
							<?php
						
							
							}}
							
							
    }
							?>
							
							</tbody>
							
						<table>
						    
						    
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
  

  

  
  
    
    
  
</body>

</html>








