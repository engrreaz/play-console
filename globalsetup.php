<?php 
    include 'incb.php'; $stid=$_GET['id'];
    
    $pth = '../students/' . $stid . '.jpg';
    if(file_exists($pth)){
        $pth = 'https://eimbox.com/students/' . $stid . '.jpg';
    } else {
        $pth = 'https://eimbox.com/students/noimg.jpg';
    }
    //echo $pth;
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
    <link rel="stylesheet" href="css.css?v=a3">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <style>
        .pic{
            width:100px; height:100px; padding:1px;  position:absolute; top:55px; z-index:99; margin:auto; margin-left:-50px;
        }
        
        .a{font-size:24px; font-weight:700; font-style:normal; line-height:18px; color:var(--dark);}
        .b{font-size:20px; font-weight:500; font-style:normal; line-height:22px; margin-top:5px;}
        .c{font-size:11px; font-weight:500; font-style:normal; line-height:12px;}
        .d{font-size:20px; font-weight:500; font-style:normal; line-height:22px; color:var(--darker);}
        
        
        .e{font-size:11px; font-weight:500; font-style:italic; line-height:11px; color:gray;}
        .ico{font-size:24px; color:var(--dark); }


    </style>
    
    
    
    
    
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="containerx" style="width:100%;" >
        
        
        <?php
        $sql0 = "SELECT * FROM globalsettings where sccode='$sccode' LIMIT 1";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0x = $result0->fetch_assoc()) { 
            $stattnd_sort = $row0x["stattnd_sort"]; 
            ?>
            
            
            <div class="card text-center" style="background:var(--dark); color:white; "  >
              <div class="card-body" style="height:100px;">
                  <center><b>Institute Information</b></center>
                    <center><img src="<?php echo $logo;?>" class="pic" /></center>
              </div>
            </div>
            
            
        
            <div class="card text-center gg" style="background:var(--lighter);" >

              <div class="card-body">
                  <div style="height:35px;"></div>
               
                <div style="text-align:left; padding-top:32px;">
                    
                <table width="100%">
                    <tr>
                        <td style="width:30px;" valign="top"></td>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td>
                                        <div class="b" onclick="relx(<?php echo $stid;?>);"><?php echo $sccode;?></div><div class="e">EIIN Number</div><div style="height:5px;"></div>
                                        <div class="b" style="font-size:16px;"><?php echo $scname;?></div><div class="e">Institution</div><div style="height:25px;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </div>
              </div>
            </div>

            
            
                    
            
            
            
            <div class="card text-center" style="background:var(--lighter);" >
              <div class="card-body">
                  
                  <div style="text-align:left;">
                        <small>Student's Attendance Sorting Style</small>
                    </div>
                  <div class="form-group input-group">
                        
                        <span class="input-group-text"><i class="material-icons ico">group</i></span>
                        <select class="form-control" id="pos">
                            
                            <option value="rollno" <?php if($stattnd_sort=='rollno'){echo 'selected';}?>>Roll No</option>
                            <option value="RAND()" <?php if($stattnd_sort=='rand()'){echo 'selected';}?>>Random</option>
                        </select>
                    </div>
                  
                  
                  <br><br>
                  
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico">person</i></span>
                        <input type="text" id="scname" name="scname" style="" class="form-control" placeholder="Institute Name" value="<?php echo $scname;?>">
                    </div>
                </div>
                
                <div style="margin:10px 0; height:2px; background:var(--lighter);"></div>
                
                <div style="margin:0px 0; height:1px;"></div>
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico">place</i></span>
                        <input type="text" id="add1" name="add1" class="form-control" placeholder="Address Line 1"  value="<?php echo $scadd1;?>">
                    </div>
                </div>
                
                
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico" style="color:#dfecef;">group</i></span>
                        <input type="text" id="add2" name="add2" class="form-control" placeholder="Address Line 2" value="<?php echo $scadd2;?>">
                    </div>
                </div>
                <div style="margin:0px 0; height:1px;"></div>
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico" style="color:#dfecef;">group</i></span>
                        <input type="text" id="ps" name="ps" class="form-control" placeholder="Upazila" value="<?php echo $ps;?>">
                    </div>
                </div>
                
                
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico">map</i></span>
                        <input type="text" id="dist" name="dist" class="form-control" placeholder="District" value="<?php echo $dist;?>">
                    </div>
                </div>
                
                <div style="margin:10px 0; height:2px; background:var(--lighter);"></div>
                
                
                <div style="margin:0px 0; height:1px;"></div>
                <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="material-icons ico">phone</i></span>
                        <input type="tel" id="mno" name="mno" class="form-control" placeholder="Mobile Number" value="<?php echo $mobile;?>">
                    </div>
                </div>
                
                
                <div style="text-align:left; padding-top :15px; padding-left:60px;">
                    <button type="button" class="btn btn-primary" onclick="upd();">Update Info</button>
                    <span id="px"></span>
                </div>
                
                
                
                
                
                
              </div>
            </div>
            <div style="height:1px;"></div>
            
         
         
         
         
         
         
         
         
         
         
       
            
            
            <div style="height:1px;"></div>
            
            
            
            
            <?php
        
        }} else {
            $query33p ="insert into globalsettings (id, sccode) values (NULL, '$sccode' )";
            $conn->query($query33p) ;
            header("location:globalsetup.php");
        }
        
        ?>
        
        
        
    </div>

  </main>
  <div style="height:5px;"></div>
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
    function rel(id){
        window.location.href="studentedit.php?id=" + id; 
    }  
    
    function edit(id){
        window.location.href="studentedit.php?id=" + id; 
    }  
  </script>
  
  <script>
      function upd() {
		var scname=document.getElementById("scname").value;
		var add1=document.getElementById("add1").value;
		var add2=document.getElementById("add2").value;
		var ps=document.getElementById("ps").value;
		var dist=document.getElementById("dist").value;
		var mno=document.getElementById("mno").value;
		
		
		var infor="sccode=<?php echo $sccode;?>&scname=" + scname + "&add1=" + add1 +  "&add2=" + add2 +  "&ps=" + ps +  "&dist=" + dist +  "&mno=" + mno;
		//alert(infor);
	$("#px").html( "" );

	 $.ajax({
			type: "POST",
			url: "updatescinfo.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#px').html('<span class="">Updating...</span>');
			},
			success: function(html) {    
				$("#px").html( html );
				//alert('students.php?cls=<?php echo $cls;?>&sec=<?php echo $sec;?>#<?php echo $stid;?>');
				window.location.href = 'index.php';
			}
		});
    }
  </script>
    
    
  
</body>

</html>