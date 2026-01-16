<?php
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once('libraries/password_compatibility_library.php');
}
require_once('config/config.php');
require_once('translations/en.php');
require_once('libraries/PHPMailer.php');
require_once('classes/Login.php');
$login = new Login();
if ($login->isUserLoggedIn() == true) 
{ $usr=$_SESSION['user_name']; ?>
<?php include('views/_header.php'); include('db.php'); 
					$sql0 = "SELECT * FROM users where user_name='$usr'";
								$result0 = $conn->query($sql0);
								if ($result0->num_rows > 0) 
							{while($row0 = $result0->fetch_assoc()) { 
							
							$ulevel=$row0["user_level"];
							$sccode=$row0["eiin"];$einame=$row0["einame"];
							}}
							
							
							
							$sql200 = "SELECT sum(amount) as amount FROM accounts where sccode='$sccode'";
								$result200 = $conn->query($sql200);
								if ($result200->num_rows > 0) 
							{while($row200 = $result200->fetch_assoc()) { 
							
							$dues=$row200["amount"];  }}

							$sql200z = "SELECT *  FROM users where eiin='$sccode' and user_level='100'";
								$result200z = $conn->query($sql200z);
								if ($result200z->num_rows > 0) 
							{while($row200z = $result200z->fetch_assoc()) { 
							
							$usrs=$row200z["user_name"];  }} 


       
                                       if(isset($_GET['paper'])){
                                           $paper = $_GET['paper'];
                                       } else {
                                           $paper = "260mm" ;
                                       }
                                            



if (isset($dues)){$dues=$dues;}else{$dues=0;}


$adues = accept_dues;
if ($ulevel>=80 && $dues<= $adues)
{

?>


<script>

function rpro() 
{
	document.getElementById("part1").innerHTML = "";
	document.getElementById("part2").innerHTML = "";
	document.getElementById("part3").innerHTML = "";
	document.getElementById("part4").innerHTML = "";
	document.getElementById("part5").innerHTML = "";

	fetchstd();
	markret();
	calctotal();
	
	

	
}

function fnlprocess() {
			
	document.getElementById("part1").innerHTML = "";
	document.getElementById("part2").innerHTML = "";
	document.getElementById("part3").innerHTML = "";
	document.getElementById("part4").innerHTML = "";
	document.getElementById("part5").innerHTML = "";
	document.getElementById("part1").innerHTML = '<span class="button cycle-button success"><span class="mif-right-file"></span> </span> <span class="fg-emerald"> Processing Result Succeed Completely.</span>';
	
	}

</script>


<script>
function submitform() {		
		var stcount = document.getElementById("stcount").innerHTML;
		var usr=document.getElementById("usr").innerHTML;
		var cn=document.getElementById("classname").value;
		var exam=document.getElementById("examname").value;
		var subname=document.getElementById("subname").value;
		var infor = "";
		var i;
		for (i=0;i<stcount;i++)
			{
			var n;
			var x;
			n = document.getElementById("stmark"+i).value;
			
			infor += "id"+i+ "=" + document.getElementById("id"+i).value + "&ppp"+i+"=" + n + "&";
			}
			infor += "cn=" + cn + "&sccode=" + document.getElementById("sccode").innerHTML + "&exam=" + exam + "&subname=" + subname + "&stcount=" + stcount  + "&usr=" + usr;
			
	$("#stlist").html( "" );

	 $.ajax({
			type: "POST",
			url: "savestmark.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#stlist').html('<span class="mif-spinner4 mif-ani-pulse"></span>');
			},
			success: function(html) {    
				$("#stlist").html( html ); 
			}
		});
}
</script>
<script>

function fetchstd() {		

		var exam=document.getElementById("examname").value;
		var classname=document.getElementById("classname").value;
		var sccode=document.getElementById("sccode").innerHTML;
		var usr=document.getElementById("usr").innerHTML;
		
		document.getElementById("part1").innerHTML = "Please wait....<br>While processing result. It may take a few moments. Don't leave this page before complete.";
		
		var infor= "classname=" + classname +  "&sccode=" + sccode +  "&usr=" + usr  +  "&exam=" + exam   ;
	
	$("#part2").html( "" );

	 $.ajax({
			type: "POST",
			url: "resultgetst.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#part2').html('<span class="mif-spinner4 mif-ani-pulse"></span>  Please Wait, Processing...');
			},
			success: function(html) {    
				$("#part2").html( html ); 
			}
		});
}

</script>

<script>

function markret() {		
	alert("Proceed?");
		var exam=document.getElementById("examname").value;
		var classname=document.getElementById("classname").value;
		var sccode=document.getElementById("sccode").innerHTML;
		var stcnt=document.getElementById("stcnt").innerHTML;
		
		document.getElementById("part1").innerHTML = "Please wait....<br>While processing result. It may take a few moments. <b>Don't leave this page before complete.";
		
		var infor= "classname=" + classname +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   ;
	
	$("#part3").html( "" );

	 $.ajax({
			type: "POST",
			url: "markret.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#part3').html('<span class="button cycle-button success"><span class="mif-spinner4 mif-ani-pulse"></span></span>  Retriving Marks. Please Wait, Processing...');
			},
			success: function(html) {    
				$("#part3").html( html ); 
			}
		});
}
</script>
<script>

function calctotal() {		

		var exam=document.getElementById("examname").value;
		var classname=document.getElementById("classname").value;
		var sccode=document.getElementById("sccode").innerHTML;
		var stcnt=document.getElementById("stcnt").innerHTML;
		
		document.getElementById("part1").innerHTML = "Please wait....<br>While Calculating Total, Grade, Merit Place. It may take a few moments. Don't leave this page before complete.";
		
		var infor= "classname=" + classname +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   ;
	
	$("#part4").html( "" );

	 $.ajax({
			type: "POST",
			url: "calctotal.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#part4').html('<span class="button cycle-button success"><span class="mif-spinner4 mif-ani-pulse"></span></span>  Retriving Marks. Please Wait, Processing...');
			},
			success: function(html) {    
				$("#part4").html( html ); 
				fnlprocess();viewresult();
			}
		});
		

}


</script>
<script>
function viewresult() {		
$("#holder").hide();
		var exam=document.getElementById("examname").value;
		var classname=document.getElementById("classname").value;
		var sectionname=document.getElementById("sectionname").value;
		var sccode=document.getElementById("sccode").innerHTML;
		var paper = "<?php echo $paper;?>";
		
		var topsheet=document.getElementById("topsheet").checked;
		var top50=document.getElementById("top50").checked;
		var coverpage=document.getElementById("coverpage").checked;
		
		
		var infor= "classname=" + classname +  "&sectionname=" + sectionname +  "&sccode=" + sccode +  "&exam=" + exam +  "&topsheet=" + topsheet +  "&top50=" + top50 +  "&coverpage=" + coverpage  + "&paper=" + paper ;
//	alert(infor);
	$("#showr").html( "" );

	 $.ajax({
			type: "POST",
			url: "showtabulatingsheet.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#showr').html('<span class="button cycle-button success"><span class="mif-spinner4 mif-ani-pulse"></span></span>  Retriving Marks. Please Wait, Processing...<br>Click on your school name to show option again.');
			},
			success: function(html) {    
				$("#showr").html( html ); 	

			}
		});
		

}


function fetchsection() {
	
		var classname=document.getElementById("classname").value;
		var infor="classname="+classname + "&usr=<?php echo $usr;?>" ;
	$("#secn").html( "" );

	 $.ajax({
			type: "POST",
			url: "fetchsection_mark.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#secn').html('<span class="mif-spinner3 mif-ani-pulse"></span>');
			},
			success: function(html) {    
				$("#secn").html( html );
			}
		});
}


function sh()
{
	$("#holder").show();
}

</script>
<?php
date_default_timezone_set('Asia/Dhaka');;							
$tdt = date('Y-m-d');
?>
<body style="top:0 !important; left:0 !important;"  onload="init()">  <!--HEADER baad dite hobe------------------------------------------>
<?php if($trialstatus=='CONTINUE'){ ?> <meta http-equiv="refresh" content="0; url=alpha.php" /> <?php } ?>
	<div class=" " style="top:0 !important;">
		<!---<h3 onclick="sh();"><?php echo $einame;?></h3>-->
		<div id="sccode" style="display:none"><?php echo $sccode;?></div>
		<div id="usr" style="display:none"><?php echo $usr;?></div>
		<div class="" data-text="" id="holder">
            <div class="grid" >
                <div class="row cells4">
					<div class="cell">
						<label><span class="fg-red">Examination</span></label>
                        <div class="input-control select full-size info" >
							<select id="examname" name="examname" >
								<option value="">Select a Exam</option>
								<option value="Half Yearly">Half Yearly Examination</option>
							    <option value="Annual Examination">Annual Examination</option>
							    <option value="Test Examination">Test Examination</option>
							    <option value="Model Test">Model Test</option>
							    <option value="1st Model Test">1st Model Test</option>
							    <option value="2nd Model Test">2nd Model Test</option>
							    <option value="3rd Model Test">3rd Model Test</option>
							</select>
						</div>
					</div>
                    
					<div class="cell">
                        <label><span class="">Class</span></label>
                        <div class="input-control select full-size info" >
							<select id="classname" name="classname"  onchange="fetchsection();">
								<option value="">Select a class</option>
								<?php 
								echo $usrs;
								$sql000 = "SELECT areaname FROM areas where user='$usrs' group by areaname order by idno";
								$result000 = $conn->query($sql000);
								if ($result000->num_rows > 0) 
							{while($row000 = $result000->fetch_assoc()) { 
							$clsname=$row000["areaname"];
							
							echo '<option value="' . $clsname . '">' . $clsname . '</option>';
							
							}}
							?>
							</select>
						</div>
						
						 
                    </div>
                    
                    <div class="cell">

                        <label><span class="">Section</span></label>
                        <div id="secn" class="input-control select full-size info" >
							<select id="sectionname" name="sectionname"  >
								<option value="">Select a Section</option>
							
								
							</select>
						</div>
                    </div>
                    
				
					
			
					
					<div class="cell">
                        <label><span class="fg-white">Action</span></label>
						<button type="submit" name="kkhfkkk" class="button warning full-size" onclick="viewresult();"  >View Result</button>
					</div>
					
				</div>
				
				
				
				<div class="row cells4">
                    <div class="cell">
                        <label class="input-control checkbox">
                            <input type="checkbox" name="topsheet" id="topsheet" value="0"    >
                            <span class="check"></span>
                            <span class="caption">Top Sheet</span>
                        </label>
                    </div>
                    <div class="cell">
                        <label class="input-control checkbox">
                            <input type="checkbox" name="top50" id="top50" value="0"   >
                            <span class="check"></span>
                            <span class="caption">Top 50 Report</span>
                        </label>
                    </div>
                    <div class="cell">
                        <label class="input-control checkbox">
                            <input type="checkbox" name="coverpage" id="coverpage" value="0"  >
                            <span class="check"></span>
                            <span class="caption">Cover Page</span>
                        </label>
                    </div>
                    <div class="cell">
                        <br>
						<a class="tag padding10 bg-violet fg-white full-size" style="text-align:center;" href="index.php"><b>Back To Home</b></a>
					</div>
                </div>
				
			
				
				
				
				
					
					
					
					
                    
                </div>
			</div>
			



					<div id="showr" class="cell">
					</div>



		
		</div>
		
		
		

	
	</div>
	
	
	
	

</body>
</html>

<?php

}
else
{
	if ($dues>$adues) { include 'payment.php';} else{ include 'authority.php';}
}

} 
else
{ include("login.php"); }