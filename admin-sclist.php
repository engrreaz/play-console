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
        .d{font-size:11px; font-weight:400; font-style:normal; line-height:16px;}
    </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);" >
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Registered Institutes
                            <center>
                            <div style="width:75%; height:1px; background:gray; margin-top:5px;"></div></center>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:20px; font-weight:700; line-height:15px;" id="x">12</div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Valid Mobile</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;" id="y"></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">EIIN Found</div>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>
        <div style="height:8px;"></div>
            
            
            <?php 
            if($reallevel=='Super Administrator' || $reallevel == 'Moderator'){
                $sl = 1; $mob = 0;
            $sql0 = "SELECT * FROM scinfo where sccode >0 order by id desc";
            //echo $sql0;
                $result0 = $conn->query($sql0);
                if ($result0->num_rows > 0) 
                {while($row0 = $result0->fetch_assoc()) { $id = $row0["id"]; 
                    $scc = $row0["sccode"];  $scname = $row0["scname"];  $add1 = $row0["scadd1"];  $add2 = $row0["scadd2"];  $ps = $row0["ps"];  $dist = $row0["dist"];  $mno = $row0["mobile"];  
                    if(strlen($mno)==11){$clr = 'success'; $mob++; } else {$clr = 'muted';}
                ?>
            <div class="card text-center" style="background:var(--lighter); color:var(--darker);"  onclick="goga('<?php echo $lnk;?>')">
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td colspan="2" style="text-align:left; padding-left:5px;">
                            <div class="d"><?php echo 'SL # ' . $sl . '  EIIN : <b>' . strtoupper($scc) .  '</b>';?></div>
                            <div class="a"><?php echo strtoupper($scname);?></div>
                            <?php if($clr!='muted'){?>
                            <button class="btn btn-<?php echo $clr;?>" style="float:right;"><i class="material-icons">phone</i></button><?php } ?>
                            <div class="c"><?php echo $add1 . ', ' . $add2;?></div>
                            <div class="c"><b><?php echo $ps . ', ' . $dist;?></b></div>
                            <div class="b">
                                
                                <?php echo $mno;?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:0px;">
                            <div id="txt<?php echo $scc;?>">
                            <form>
                                <input type="text" class="form-control" placeholder="Enter Any Notes or Comments?" id="txtt<?php echo $scc;?>" />
                            </form>
                            </div>
                        </td>
                        <td style="vertical-align:top; width:40px; text-align:right;">
                            <button class="btn btn-primary" onclick="btn(<?php echo $scc;?>);"><i class="material-icons">send</i></button>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            <div style="height:8px;"></div>
            <?php $sl++; }}} else {echo 'You are not authorized.';} ?>
        
        
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
  document.getElementById("x").innerHTML = "<?php echo $mob;?>";
  document.getElementById("y").innerHTML = "<?php echo $sl;?>";
  
  
    function go(id){
        window.location.href="students.php?" + id; 
    }  
  </script>
  
  <script>
          function btn(id) {
		var txt=document.getElementById("txtt"+id).value;

		var infor="id=" + id + "&usr=<?php echo $usr;?>&txt=" + txt; 
	$("#txt"+id).html( "" );

	 $.ajax({
			type: "POST",
			url: "admin_notes_save.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$('#txt'+id).html('<span class="">Saving...</span>');
			},
			success: function(html) {
				$("#txt"+id).html( html );
				//window.location.href = 'index.php';
			}
		});
    }
      </script>
    
    
    
  
</body>

</html>