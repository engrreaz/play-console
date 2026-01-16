<?php 
include 'inc.php';
$srt = $_GET['srt']*1; 

	
	
    
	$sql00 = "SELECT * FROM stattndsummery where  date='$td' and sccode='$sccode' and sessionyear='$sy' and classname = '$classname' and sectionname='$sectionname'"; 
    $result00gtt = $conn->query($sql00);
    if ($result00gtt->num_rows > 0) 
    {while($row00 = $result00gtt->fetch_assoc()) {   
        $rate = $row00["attndrate"]; $subm = 1; $fun='grpssx0';
    }} else{
        $subm = 0;  $fun='grpssx';
    }
    
    if($period >=2){$fun = 'grpssx2';}
    
    
    
    
//     $sql00 = "SELECT * FROM lottery"; 
//     $result00gttx = $conn->query($sql00);
//     if ($result00gttx->num_rows > 0) 
//     {while($row00 = $result00gttx->fetch_assoc()) {   
//         $cou = $row00["coupon"]; $id = $row00["id"];  $cou = $cou % 100;
        
//         $query3x ="update lottery set sl='$cou' where id='$id';";
// 		$conn->query($query3x);
		
//     }}
    
    
    
    
    
    
    
    
// 	echo var_dump($datam);
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
    <link rel="stylesheet" href="css.css?v=bb">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <style>
        .pic{
            width:60px; height:60px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }
        
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        
        .chk{font-size:36px;} .red{color:red;} .green{color:seagreen;} .blue{color:darkcyan;}
    </style>
    
    <script>
        function att(id, roll,  bl, per) {
            if(per>=2){
                var val=document.getElementById("sta2"+id).checked;
            } else {
                var val=document.getElementById("sta"+id).checked;
            }
    		
    		var infor="stid=" + id + "&roll=" + roll + "&val=" + val  + "&opt=2&cls=<?php echo $classname;?>&sec=<?php echo $sectionname;?>&per=" + per;
    	    $("#ut"+id).html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "savestattnd.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () {
    				$("#ut"+id).html('<span class="chk blue"><i class="bi bi-server"></i></span>');
    			},
    			success: function(html) {
    				$("#ut"+id).html( html );
    			}
    		});
        }
        
        
    
    
    function grpssx(id, roll){
        var bl = document.getElementById("sta"+id).checked;
        var per = 1;
        var cnt = parseInt(document.getElementById("att").innerHTML)*1;
        if(bl==true){
            document.getElementById("sta"+id).checked = false;
            cnt--;
        } else {
            document.getElementById("sta"+id).checked = true;
            cnt++;
        }
        document.getElementById("att").innerHTML = cnt;
        att(id, roll, bl, per);
    }
    
    function grpssx2(id, roll){
        
        var per = <?php echo $period;?>;
        
        var bl = document.getElementById("sta2"+id).checked;
        var cnt = parseInt(document.getElementById("att").innerHTML)*1;
        if(bl==true){
            document.getElementById("sta2"+id).checked = false;
            cnt--;
        } else {
            document.getElementById("sta2"+id).checked = true;
            cnt++;
        }
        document.getElementById("att").innerHTML = cnt;
        att(id, roll, bl, per);
    }
    </script>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="gol(<?php echo $id;?>)">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo"><i class="bi bi-people-fill"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                                Study Tour - 2024<br>
                                Raffle Draw
                 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="lottery-setup.php?srt=0">Setup</a> 
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:30px; font-weight:700; line-height:20px;" ><span id="cnt"></span><span id="att"></span></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">Coupon/Prize</div>
                   
                        </td>
                    </tr>

                    
                </table>
            </div>
        </div>
    
        
        <?php
        $cnt = 0; $found =0; $tk = 0; $ss = 0;
        if($srt==0){ // Default
            // $sql0 = "SELECT * FROM lottery  order by prizestatus desc, sl, coupon"; // Only Active Prize List
            // $sql0 = "SELECT * FROM lottery  order by sl, coupon"; // All list with serial
            $sql0 = "SELECT * FROM lottery  order by coupon"; // 
        } else if($srt==1){ // coupon Yes
            $sql0 = "SELECT * FROM lottery order by codestatus desc, sl, coupon";
        } else if($srt==2){ // Price High
            $sql0 = "SELECT * FROM lottery order by taka desc, sl, coupon";
        } else if($srt==3){ // Prize Yes
            $sql0 = "SELECT * FROM lottery order by prizestatus desc, sl, coupon";
        } else if($srt==4){ // Prize Text
            $sql0 = "SELECT * FROM lottery order by prize desc, id, coupon";
        }
        
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $id=$row0["id"]; 
            $coupon=$row0["coupon"]; $cst=$row0["codestatus"];  
            $prize=$row0["prize"];   $pst=$row0["prizestatus"];   
            $taka=$row0["taka"];   $randcode=$row0["randcode"]; $randprize=$row0["randprize"]; 
            
            if($cst ==1){$gip = 'checked'; $cnt++;$jk = '';} else {$gip = '';$jk = '';}
            if($pst ==1){$gip2 = 'checked'; $found++; $tk += $taka; } else {$gip2 = ''; }
            
            
            
            
            ?>
            <div class="card text-center" style="background:var(<?php echo $bgc;?>); color:var(--darker);"  onclick="<?php echo $fun;?>(<?php echo $stid;?>, <?php echo $rollno;?>)" id="block<?php echo $stid;?>"  <?php echo $dsbl;?> >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table width="100%">
                    <tr>
                        <td style="width:50px; text-align:left; padding-left:15px;">
                            <div class="a"><?php echo $ss + 1 ;; echo $jk;?></div>
                        </td>
    
                        <td style="text-align:left; padding-left:15px;">
                            <div class="a"><?php echo $coupon;?></div>
                        </td>
                        
                        <td style="text-align:right; padding-right:15px;;">
                            <?php echo $randprize;?>
                        </td>

                    </tr>
                </table>
                
                
              </div>
            </div>
            
            
            <?php
                $ss++;
        }}
        
        ?>
        
        
        
        
        
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
  
    function more(){
        let val = document.getElementById("myswitch").checked;
        if(val == true){
            $(".sele").show();
        } else {
            $(".sele").hide();
        }
    }
  
    function lott(id, tail) {
		var cou=document.getElementById("sta"+id).checked;
		var st=document.getElementById("sta2"+id).checked;
		var pri=document.getElementById("prize"+id).value;
		var taka=document.getElementById("taka"+id).value;
		var infor="id=" + id + "&cou=" + cou  + "&st=" + st  + "&pri=" + pri  + "&taka=" + taka + "&opt=" + tail;
// 		alert(infor);
	    $("#blocksel"+id).html( "" );

	    $.ajax({
			type: "POST",
			url: "lottery-save.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#blocksel"+id).html('<span class=""><center>***</center></span>');
			},
			success: function(html) {
			    if(tail==5){
			        $("#sfinal").html( html );
			    } else {
			        $("#blocksel"+id).html( html );
			    }
				
			}
		});
    }
    
    
  </script>
    
    <script>
  document.getElementById("cnt").innerHTML = "<?php echo $ss;?>";
  
    function go(id){
        window.location.href="student.php?id=" + id; 
    }  
  </script>
  
</body>

</html>