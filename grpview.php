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
        .lst{text-align:left; font-weight:500; color:gray; padding-left:20px;}
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);" >
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo"><i class="bi bi-ui-radios-grid"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">GROUP / TEAM
                       
                            </div>
                        </td>
                    </tr>

                    
                </table>
            </div>
        </div>
            
            
            <?php 
            $sql0 = "SELECT * FROM areas where sessionyear = '$sy' and user='$rootuser' and (areaname='Six' or areaname='Seven') order by idno";
                $result0 = $conn->query($sql0);
                if ($result0->num_rows > 0) 
                {while($row0 = $result0->fetch_assoc()) { 
                    $cls = $row0["areaname"];  $sec = $row0["subarea"];  
                    $ico = 'iimg/' . strtolower(substr($sec,0,5)) . '.png';
                    $lnk = "cls=" . $cls . '&sec=' . $sec;
                ?>
            <div class="card text-center" style="background:var(--lighter); color:var(--darker);" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td style="width:10px;"><span style="font-size:24px; font-weight:700;"><?php echo $rollno;?></span></td>
                        <td style="text-align:left; padding-left:5px;">
                            <div class="a"><?php echo strtoupper($cls);?></div>
                            <div class="b"><?php echo $sec;?></div>
                        </td>
                        <td style="text-align:right;">
                            
                            <button class="btn btn-success btn-round btn-sm mt-2 mb-2" style="float:right;"   onclick="add('<?php echo $cls?>', '<?php echo $sec?>' )" >
                                <i class="material-icons" style="">group_add</i>
                            </button>
                        </td>
                    </tr>
                </table>
                
                <table width="100%">
                <?php
                $sql0e = "SELECT * FROM pibigroup where sessionyear = '$sy' and sccode='$sccode' and classname='$cls' and sectionname='$sec' order by id"; 
                $result0e = $conn->query($sql0e);
                if ($result0e->num_rows > 0) 
                {
                    ?>
                        <tr>
                            <td colspan="3" style="padding:4px 0; color:black; text-align:center; background:var(--light);"><b>Group/Teams</b></td>
                        </tr>
                    <?php
                    
                    while($row0e = $result0e->fetch_assoc()) { 
                    $grname = $row0e["groupname"]; $id = $row0e["id"];  $eby = $row0e["entryby"];  $rls = $row0e["rolls"];
                    
                    ?>
                    
                    <tr>
                        <td style="width:65px;"><i class="material-icons" style="color:var(--dark); margin-right:5px; padding-left:25px;">group</i></td>
                        <td><?php echo $grname;?></td>
                        <td>
                            <button class="btn btn-danger btn-round btn-sm mt-2 mb-2" style="float:right;"  onclick="del(<?php echo $id;?>);" >
                                <i class="material-icons" style="">do_not_disturb_on</i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:65px;"></td>
                        <td style="padding-right:8px;"><input type="tel" id="rls<?php echo $id;?>" class="input form-control" value="<?php echo $rls;?>" </td>
                        <td>
                            <button class="btn btn-info btn-round btn-sm mt-2 mb-2" style="float:right;"  onclick="upd(<?php echo $id;?>, '<?php echo $cls;?>', '<?php echo $sec;?>', '<?php echo $grname;?>');" >
                                <i class="material-icons" style="">edit</i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:65px;"></td>
                        <td style="padding-right:8px; font-size:11px; color:gray; font-style:italic;" colspan="2">
                            <small>Write the roll numbers of the students associates with this group <b><?php echo $grname;?></b>, each roll must be separated with a full stop. e.g. 5.9.3.2.1</small>
                        </td>
                        
                    </tr>
                    
                    <?php
                }}
                ?>
                </table>
                <div id="pro"></div>
              </div>
            </div>
            <?php }} ?>
        
        
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
 // document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
  
  
    function go(id){
        window.location.href="students.php?" + id; 
    }  
  </script>
  
  <script>
      function add(cls, sec){
         
         let pr = prompt('       Enter a group name');
         if(pr !=null && pr.length>0){
             var infor="sccode=<?php echo $sccode;?>&cls=" + cls + "&sec=" + sec + "&grp=" + pr + "&eby=<?php echo $usr;?>";
    	    $("#pro").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "addgrp.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#pro').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#pro").html( html );
    				window.location.href="grpview.php?"; 
    			}
    		});
         }

    		
      }
      
      
      
      function del(id){
         
         let pr = confirm('Are you sure to delete the group/team?');
         if(pr==true){
             var infor="id=" + id;
    	    $("#pro").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "delgrp.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#pro').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#pro").html( html );
    				window.location.href="grpview.php?"; 
    			}
    		});
         }

    		
      }
      
      
      function upd(id, cls, sec, gr){
         let rls = document.getElementById("rls"+id).value
             var infor="id=" + id + "&rls=" + rls + "&cls="+cls+"&sec=" + sec + "&sccode=<?php echo $sccode;?>&grname=" + gr;
    	    $("#pro").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "delgrp.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#pro').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#pro").html( html );
    				window.location.href="grpview.php?"; 
    			}
    		});

      }
  </script>
    
    
  
</body>

</html>