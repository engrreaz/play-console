<?php 
include 'incb.php';
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
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="go(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                                Subcription Services
                            </div>
                        </td>
                    </tr>
                
                    
                </table>
            </div>
        </div>
        <div style="height:8px;"></div>
    
    <?php  if($userlevel == 'Administrator' || $userlevel == 'Head Teacher'){ ?>
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">report</i></td>
                        <td>
                            <div>
                                Bye a pack as you like 
                            </div>
                            <div><b>OR</b></div>
                            <div style="background:var(--darker); color:white; padding:5px 8px; border-radius:8px; margin-bottom:8px;">Just Click <b>Trial Period</b> to start a 72-Hours free trial.</div>
                            <button class="btn btn-success" onclick="trial();">Start Free Trial</button><div id="sublock"></div>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">report</i></td>
                        <td>
                            <h4>Exam Management</h4>
                            <small>Entry Marks for the student of class SIX - TEN</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                        <td>
                            <h4>Attendance</h4>
                            <small>Create/View Group for Curriculum 2023</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">receipt</i></td>
                        <td>
                            <h4>Messaging</h4>
                            <small>Create/View PI/BI Entry Sheet | Result Sheet</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">description</i></td>
                        <td>
                            <h4>Student's Collection/Dues</h4>
                            <small>Process Transcript for Students</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            
            
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">description</i></td>
                        <td>
                            <h4>Columner Cash Book</h4>
                            <small>Users Listed in our Institution</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                        <td>
                            <h4>Library</h4>
                            <small>Details Information/Settings on Classes & Sections</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            
            
            
            
            
            
            
            
            
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">school</i></td>
                        <td>
                            <h4>------</h4>
                            <small>Update Institute Informations</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
        
        <?php } ?>
        
        
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
    
        function lnk1(){ window.location.href="accountbuypack.php"; }
        
        
        
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
  </script>
    
    <script>
      function trial() {
		
		var infor="sccode=<?php echo $sccode;?>&user=<?php echo $rootuser;?>";
	$("#sublock").html( "" );

	 $.ajax({
			type: "POST",
			url: "trial.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#sublock').html('<span class="">Please Wait...</span>');
			},
			success: function(html) {    
				$("#sublock").html( html );
				window.location.href = "index.php";
			}
		});
    }
    
    $(document).ready(trial());
  </script>
  
</body>

</html>