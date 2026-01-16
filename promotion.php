<?php 
include 'inc.php';
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
        h4{font-size:20px; color:var(--darker); line-height:12px; font-weight:700;}
        h5{font-size:16px; color:var(--dark); line-height:12px; font-weight:500;}
        small{font-size:10px; color:gray; line-height:10px; margin:3px 0 8px;}
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  >
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="logoo"><i class="bi bi-square-half"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Promotion
                            </div>
                        </td>
                    </tr>
                
                    
                </table>
            </div>
        </div>
    
    
    <?php  if($userlevel == 'Administrator' || $userlevel == 'Head Teacher'){ ?>
            
            
            
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="width:100%;">
                    <tr>
                        <td><b>Current Year Class :</b></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group input-group">
                                <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                <select class="form-control" id="clsnew">
                                    
                                    <option value="">Choose a Class</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy'  group by areaname order by idno, id"; 
                                    $result00xgr11 = $conn->query($sql00xgr);
                                    if ($result00xgr11->num_rows > 0) {while($row00xgr = $result00xgr11->fetch_assoc()) {
                                    $ccc=$row00xgr["areaname"];
                                    echo '<option value="' . $ccc . '">' . $ccc . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                <select class="form-control" id="secnew">
                                    
                                    <option value="">Choose a Section</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy' group by subarea order by idno, id"; 
                                    $result00xgr = $conn->query($sql00xgr);
                                    if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
                                    $sec=$row00xgr["subarea"];
                                    echo '<option value="' . $sec . '">' . $sec . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            
                            
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <hr>
                            <b>Last Year Class :</b>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            
                            <div style="text-align:left; padding-top:0px; display:none;">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                    <input type="text" id="id" name="id" class="form-control" placeholder="Enter Section/Group Name"  value="">
                                </div>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                <select class="form-control" id="cls">
                                    
                                    <option value="">Choose a Class</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy'-1 group by areaname order by idno, id "; 
                                    $result00xgr11 = $conn->query($sql00xgr);
                                    if ($result00xgr11->num_rows > 0) {while($row00xgr = $result00xgr11->fetch_assoc()) {
                                    $ccc=$row00xgr["areaname"];
                                    echo '<option value="' . $ccc . '">' . $ccc . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group input-group">
                                <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                <select class="form-control" id="sec">
                                    
                                    <option value="">Choose a Section</option>
                                    <?php
                                    $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy'-1  group by subarea order by idno, id"; 
                                    $result00xgr = $conn->query($sql00xgr);
                                    if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
                                    $sec=$row00xgr["subarea"];
                                    echo '<option value="' . $sec . '">' . $sec . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            
                            
                        </td>
                    </tr>
                    
                    
                    
                    
                    
                    <tr>

                        <td >
                            <div style="margin:0px 0; height:5px; background:var(--lighter);"></div>
                            <button  class="btn btn-success pad60" onclick="submit();">Search/View List</button>
                        </td>
                    </tr>
                </table>
              </div>
            </div>

            
            
            
            <div class="card" style="background:var(--darker); color:var(--lighter);" >
                  <img class="card-img-top"  alt="">
                  <div class="card-body">
                        <center><b>Student List</b></center>
                  </div>
                </div>

            
            
            
            
            
            
            <div id="block">
               
                
                
            </div>
            
          
            </div>
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
    
        function lnk7(){ window.location.href="settingsinstituteinfo.php"; }
        function lnk6(){ window.location.href="settingsclass.php"; }
        
        
        
  </script>
  
  
  <script>
      function submit() {
		var id=0;//document.getElementById("id").value;
		var cls=document.getElementById("cls").value;
		var sec=document.getElementById("sec").value;

		var infor="rootuser=<?php echo $rootuser;?>&cls=" + cls + "&sec=" + sec + "&id=" + id + "&action=1";
	$("#block").html( "" );

	 $.ajax({
			type: "POST",
			url: "showprevstlist.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#block').html('<span class=""><center>Processing, Please Wait....</center></span>');
			},
			success: function(html) {    
				$("#block").html( html );
			}
		});
    }
    
    
    function edit(id) {
        var cls = document.getElementById("clsnew").value;
        var sec = document.getElementById("secnew").value;
        var roll = document.getElementById("txt"+id).value;
		var infor="cls=" + cls + "&sec=" + sec + "&roll=" + roll + "&stid=" + id;
	$("#exe"+id).html( "" );

	 $.ajax({
			type: "POST",
			url: "save-promotion.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#exe'+id).html('<i class="bi bi-arrow-repeat"></i>');
			},
			success: function(html) {    
				$("#exe"+id).html( html );
			}
		});
    }
  </script>
  
  
    
    
  
</body>

</html>