<?php 
include 'inc.php';
$classname = $_GET['cls']; $sectionname = $_GET['sec']; 
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
    <style>
    .box {
        padding: 5px 25px;
        box-sizing: border-box;
        display: flex;
        border: 1px solid var(--darker);
        border-width: 0;
        margin:5px 0;
    }
    


    .box-icon {
        font-size: 25px;
        display: inline;
        width: 40px;
        padding-top: 3px;
        margin-right: 5px;
    }
    
    .box-icon::before {
        content: '';
  position: absolute;
  width: 0px;
  background-color: var(--dark);
  top: 0;
  bottom: 0;
  left: 40px;
  margin-left: -1px;
    }


    .box-text {
        display: flex;
        flex-direction: column;
        flex: auto;
        margin-top:1px;
        padding-left:5px;
    }

    .box-title {
        font-size: 12px;
        font-weight: 500;
        margin: 0;
    }


    .box-subtitle {
        font-size: 10px;
        font-weight: 400;
        font-style: italic;
        margin: 0;
        color: var(--normal);
    }

    .box-prog {
        height: 50px;
        width: 50px;
        display: none;
    }
    
    .sender {
        width:35px; height:35px; border-radius:50%; background: white; z-index:999;
        box-shadow: 2px 2px 8px #888888;
    }
</style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">

        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="go(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="logoo"><i class="bi bi-bell-fill"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                                Software Issue
                            </div>
                        </td>
                    </tr>
                
                    
                </table>
                <style>
                    .spcl{font-size:12px; font-style:italic;}
                </style>
                <table style="width:100%">
                    <tr>
                        <td style="text-align:left;">
                            <div style="font-size:14px; color:white; text-align:center;">
                                <i class="bi bi-reception-2"></i> <span class="spcl">Under Build</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi bi-reception-4"></i> <span class="spcl">On Progress</span>
                                <br>
                                <i class="bi bi-exclamation-diamond-fill"></i> <span class="spcl">On Test</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi bi-check-circle-fill"></i> <span class="spcl">Done</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="bi-shield-fill-check"></i> <span class="spcl">Tested</span>
                            </div>
                        </td>
                        <td style="text-align:center; color:white;">
                            <div style="border:1px solid white; border-radius:5px; padding:5px;" onclick="iss();">
                                <i style="font-size:40px; color:white;" class="bi bi-plus"></i>
                                <br><span style="font-size:12px; font-style:italic;">Add a issue</span>
                            </div>
                        </td>
                    </tr>
                </table>
                            
            </div>
            
            
            <div class="card-body" style="display:none;" id="issueblock">
                <div style="color:white; background:var(--dark);">
                    <b>Add a Issue</b>
                    <br>
                    <span style="font-size:11px; font-style:italic;">Add a new issue or existing module issue</span>
                    <br>
                    
                    <div style="text-align:left; padding-top:0px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i class="material-icons ico">reorder</i></span>
                            <select class="form-control" id="cause" style="border:0; background:var(--dark); color:white; border-bottom:1px solid lightgray;">
                                <option >Select a type</option>
                                <option value="Student">Student </option>
                                <option value="Teacher">Teacher</option>
                                <option value="Columner Cashbook">Cash Book Related</option>
                                <option value="Bank Management">Bank Management</option>
                                <option value="Report">Reports</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="text-align:left; padding-top:5px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i class="material-icons ico">description</i></span>
                            <input  style="color:white;" type="text" id="descrip" name="descrip" class="form-control" placeholder="Your Issue..." value="">
                        </div>
                    </div>
                    <div style="text-align:left; padding-top:5px;">
                        <div class="input-group">
                            <span class="input-group-text" style="color:white;"><i class="material-icons ico">event</i></span>
                            <input  style="color:white; background:var(--dark); border:0; border-bottom:1px solid white;" type="date" id="date" name="date" class="form-control" placeholder="Deadline" value="<?php echo date('Y-m-d');?>">
                        </div>
                    </div>
                    
                    <div style="padding:5px 60px;">
                        <button class="btn " style="background:white; color:var(--dark); border-radius:5px;" onclick="addissue();;"   ><b>Add a issue</b></button>
                        <span id="settc"></span>
                    </div>
                </div>
            </div>
            
            <div id="settcc"></div>
            
        </div>

    
    
            <!--<div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk3();" >-->
            <!--  <img class="card-img-top"  alt="">-->
            <!--  <div class="card-body">-->
            <!--    <table style="">-->
            <!--        <tr>-->
            <!--            <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>-->
            <!--            <td>-->
            <!--                <h4>Administrative Setup</h4>-->
            <!--                <small>Class & Sections, Subjects, Teachers, Users etc.</small>-->
            <!--            </td>-->
            <!--        </tr>-->
            <!--    </table>-->
            <!--  </div>-->
            <!--</div>-->
            
            
            <div class="card-body">
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk30();" >
              <img class="card-img-top"  alt="">
              <?php
                $sql0 = "SELECT * FROM issue where issueby='$usr' order by stt, deadline, progress";
                $result0wwrt = $conn->query($sql0);
                if ($result0wwrt->num_rows > 0) 
                {while($row0 = $result0wwrt->fetch_assoc()) { 
                $category = $row0["category"];
                $description = $row0["description"];
                $deadline = $row0["deadline"];
                $prog = $row0["progress"];
                $icont = $row0["icon"];
                $id = $row0["id"];
                
                if($prog==100){
                    $icon = 'bi-shield-fill-check';
                    $clr = 'seagreen';
                } else if($prog>=80){
                    $icon = 'bi-check-circle-fill';
                    $clr = 'teal';
                } else if($prog>=70){
                    $icon = 'bi-exclamation-diamond-fill';
                    $clr = 'steelblue';
                } else if($prog>=60){
                    $icon = 'bi-reception-4';
                    $clr = 'orange';
                } else if($prog>=50){
                    $icon = 'bi-reception-2';
                    $clr = 'red';
                } else {
                    $icon = '';
                    $clr = '';
                }
                
            ?>  
                    <div class="box">
                        <div class="box-icon">
                            <img onclick="progress(<?php echo $id;?>);"  class="sender" src="https://eimbox.com/androidapplicationversion/iimg/icon/<?php echo $icont;?>.png?v=z" />
                        </div>
                        <div class="box-text">
                            <div style="float:right;text-align:right; right:25px; position:absolute;">
                                <div style="font-size:30px; color:<?php echo $clr;?>"><i class="bi <?php echo $icon;?>"></i></div>
                            </div>
                            <div class="box-title" style="<?php if($rws==1){ echo 'color:gray;';}?>">
                                <?php echo $title; ?>
                            </div>
                            <div class="box-title"  style="<?php echo 'color:gray;';?>"><?php echo $description;?></div>
                            <div class="box-subtitle"  style="<?php echo 'color:black;';?>"><?php echo $deadline . ' by <b>' . $iby . '</b>' ;?></div>
                            <?php if($prog<70){ ?>
                            <div style="background:var(--light);">
                                <div style="width:<?php echo $prog*1.5;?>%; height:2px; background:var(--dark);"></div>
                            </div>
                            <?php } ?>
                        </div>
                        
                    </div>
            <?php 
                }}
                
               
              ?>
              

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
    function iss(){
        document.getElementById("issueblock").style.display = 'block';
    }
    
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
    
        function lnk1(){ window.location.href="tools_allsubjects.php"; }
        function lnk2(){ window.location.href="pibiprocess.php"; }
        function lnk3(){ window.location.href="settings.php"; }
        function lnk4(){ window.location.href="transcriptselect.php"; }
        function lnk5(){ window.location.href="userlist.php"; }
        function lnk6(){ window.location.href="classes.php"; }
        function lnk7(){ window.location.href="transcriptselect.php"; }
        function lnk8(){ window.location.href="transcriptselect.php"; }
        function lnk31(){ window.location.href="accountsecurity.php"; }
        
        
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
      function addissue() {
		var cause=document.getElementById("cause").value;
		var descrip=document.getElementById("descrip").value;
		var date=document.getElementById("date").value;

		var infor="sccode=<?php echo $sccode;?>&cause=" + cause + "&descrip=" + descrip + "&date="+ date + "&tail=0";
	$("#settc").html( "" );

	 $.ajax({
			type: "POST",
			url: "saveissue.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#settc').html('<span class="">Adding your issue...</span>');
			},
			success: function(html) {    
				$("#settc").html( html );
			}
		});
    }
  </script>
  
  <script>
      function progress(id) {
		var infor="sccode=<?php echo $sccode;?>&id=" + id  + "&tail=1";
	$("#settcc").html( "" );

	 $.ajax({
			type: "POST",
			url: "saveissue.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#settcc').html('<span class="">Adding your issue...</span>');
			},
			success: function(html) {    
				$("#settcc").html( html );
			}
		});
    }
  </script>
    
    
  
</body>

</html>