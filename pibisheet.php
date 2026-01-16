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
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="go(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="logoo" style="font-size:18px;">
                                                <i class="bi bi-triangle-fill"></i>
                                                <i class="bi bi-circle-fill"></i>
                                                <i class="bi bi-square-fill"></i>
                                </div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">PI/BI Sheet
                            
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="text-align:center; font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Both New (2023) PI/BI System & <br> Old Grading System</div>
                 
                            
                        </td>
                        
                    </tr>
                    
                </table>
            </div>
        </div>
    
            <div class="card" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <div class="form-group">
                    <label for="exam">Select Exam</label>
                    <select class="form-control" id="exam">
                      <?php
                            $sql0 = "SELECT * FROM examname order by id";
                            
                            $result0vf = $conn->query($sql0);
                            if ($result0vf->num_rows > 0) 
                            {while($row0 = $result0vf->fetch_assoc()) { 
                                $exname=$row0["examtitle"];
                                $exdisp=$row0["examdisplayname"];
                                echo '<option value="'.$exname.'">'.$exdisp.'</option>';
                            }}?>
                    ?>   
                    </select>
                  </div>
              </div>
            </div>
            
            
            <div class="card" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Select Class</label>
                    <select class="form-control" id="classname" onchange="fetchsection();">
                      <option></option>
                            <?php
                            $sql0 = "SELECT areaname as aname FROM areas where sessionyear='$sy' and user='$rootuser'  group by areaname order by idno";
                            
                            $result0 = $conn->query($sql0);
                            if ($result0->num_rows > 0) 
                            {while($row0 = $result0->fetch_assoc()) { 
                                $aname=$row0["aname"];
                                echo '<option value="'.$aname.'">'.$aname.'</option>';
                            }}?>
                    </select>
                  </div>
              </div>
            </div>
            
            <div class="card" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  >
              <img class="card-img-top"  alt="">
              <div class="card-body" id="sectionblock">
                <div class="form-group">
                    <label for="sectionname">Select Section</label>
                    <select class="form-control" id="sectionname" onchange="fetchsubject();">
                      <option></option>
                            <?php
                            $sql0 = "SELECT subarea as sec FROM areas where sessionyear='$sy' and user='$rootuser' and areaname='$cls' group by areaname order by idno";
                            $result0 = $conn->query($sql0);
                            if ($result0->num_rows > 0) 
                            {while($row0 = $result0->fetch_assoc()) { 
                                $sec=$row0["sec"];
                                echo '<option value="'.$sec.'">'.$sec.'</option>';
                            }}?>
                    </select>
                  </div>
              </div>
            </div>
            
            <div class="card" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  >
              <img class="card-img-top"  alt="">
              <div class="card-body" id="subblock">
                <div class="form-group">
                    <label for="subject">Select Subject</label>
                    <select class="form-control" id="subject">
                      <option></option>
                            <?php
                            $sql0 = "SELECT subject FROM subsetup where sccode='$sccode' and classname='$classname' and sectionname='$sectionname'  order by subject";
                            
                            $result0 = $conn->query($sql0);
                            if ($result0->num_rows > 0) 
                            {while($row0 = $result0->fetch_assoc()) { 
                                $scode=$row0["subject"];
                                
                                $sql00 = "SELECT subject FROM subjects where subcode='$scode' ";
                                $result00 = $conn->query($sql00);
                                if ($result00->num_rows > 0) 
                                {while($row00 = $result00->fetch_assoc()) { 
                                    $sname=$row00["subject"];}}
                                echo '<option value="'.$scode.'">'.$sname.'</option>';
                            }}?>
                    </select>
                  </div>
              </div>
            </div>
            
            <div class="card" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  >
              <div class="card-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Select Assessment</label>
                    <select class="form-control" id="assessment">
                      <option value="Continious Assessment">Continious Assessment (PI)</option>
                      <option value="Total Assessment" selected>Total Assessment (PI)</option>
                      <option value="Behavioural Assessment">Behavioural Assessment (BI)</option>
                    </select>
                  </div>
              </div>
            </div>
            
            <center>
                <br>
                <button class="btn btn-primary"  style="margin:0 0 10px 0; color:white;" onclick="go(1);">PI/BI (Recorded)</button>
            
        <button class="btn btn-danger"  style="margin:0 0 10px 0; color:white;" onclick="go(0);">PI/BI (Blank)</button>
            </center>
            
            
        
        
        
        
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
  
    function go(id){
        var cls=document.getElementById("classname").value; 
        var sec=document.getElementById("sectionname").value; 
        var sub=document.getElementById("subject").value; 
        var assess=document.getElementById("assessment").value; 
        var exam=document.getElementById("exam").value; 
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess; 
        if(cls=='Six'|| cls == 'Seven'){
            if(id==1){
                window.location.href="pibiprint.php" + tail; 
            } else {
                window.location.href="pibiprintblank.php" + tail; 
            }
            
        } else {
            alert("Select Class Six/Seven Only");
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