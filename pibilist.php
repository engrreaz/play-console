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
        <div class="card text-left" style="background:var(--normal); color:var(--lighter); padding:0 50px;" >
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div class="logoo"><i class="bi bi-person-bounding-box"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                                User Manager
                            </div>
                        </td>
                    </tr>
                    
                </table>
            </div>

            
            

            
             <?php
                                        $sql0r = "SELECT * FROM teacher where sccode='$sccode' and status='1' order by ranks, id"; 
                                        $result0r = $conn->query($sql0r);
                                        if ($result0r->num_rows > 0) 
                                        {while($row0r = $result0r->fetch_assoc()) { 
                                            $tid=$row0r["tid"]; $tname=$row0r["tname"];  
                                            if($tid == $userid){$pum = 'selected';} else {$pum = '';}
                                           // echo '<option value="'.$tid.'" ' . $pum . '>'.$tname .'</option>';
                                         }}
                                         
                                         ?>
        
        
            
      <?php
        
        if($reallevel == 'Super Administrator'){ ?>
        
                        <div style="color:white;">
                            <input class="input form-group form-control" type="text" id="cls" value="Seven" />
                            <input class="input form-group form-control" type="text" id="sub" value="901" />
                            <input class="input form-group form-control" type="text" id="topic" onkeyup="  add();" />
                            
                            <div style="padding-top:5px;">
                                <button class="btn btn-primary " onclick="src();">Search</button> <div id="html2"></div>
                                <button class="btn btn-danger" onclick="upd2();">Update</button> <div id="html"></div>
                            </div>
                                
                        </div>
        <?php } ?>
        
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
 // document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
  
  
    function go(id){
        window.location.href="students.php?" + id; 
    }  
  </script>
    
  <script>
      function upd(id, rank){
             var infor="id=" + id+ "&rank="+ rank ;
    	    $("#usr"+id).html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "userupd.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#usr'+id).html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#usr"+id).html( html );
    			}
    		});
      }
      
      </script>
    <script>
      function bind(id, tid){
            var a = document.getElementById('a'+id).value;
             var infor="id=" + id + "&tid=" + a + "&ch=1";
    	    $("#pro"+id).html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "userupd.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#pro'+id).html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#pro"+id).html( html );
    			}
    		});
      }
      
      
      function rem(id, tid){
            var a = document.getElementById('a'+id).value;
             var infor="id=" + id + "&tid=" + a + "&ch=2";
    	    $("#pro"+id).html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "userupd.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#pro'+id).html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#pro"+id).html( html );
    				window.location.href = 'userlist.php';
    			}
    		});
      }
      
      
      </script>
    <script>
      
      
      
      function add(){
          if (event.key === "Enter") {
              event.preventDefault();
              
              let cls = document.getElementById("cls").value;
            let sub = document.getElementById("sub").value;
            let topic = document.getElementById("topic").value;
             var infor="cls=" + cls + "&sub=" + sub + "&topic=" + topic ;
    	    $("#html").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "addtopic.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#html').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#html").html( html );
    				document.getElementById("topic").value = '';
    			}
    		});
          }
            
      }
      
      
      </script>
    <script>
      function src(){
            let cls = document.getElementById("cls").value;
            let sub = document.getElementById("sub").value;
            let topic = document.getElementById("topic").value;
             var infor="cls=" + cls + "&sub=" + sub + "&topic=" + topic + "&s=1" ;
    	    $("#html2").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "addtopic.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#html2').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#html2").html( html );
    				
    			}
    		});
      }
      
      </script>
    <script>
    
      function upd2(){
            let id = document.getElementById("id").value;
            let title = document.getElementById("title").value;
            let l1 = document.getElementById("l1").value;
            let l2 = document.getElementById("l2").value;
            let l3 = document.getElementById("l3").value;
            
             var infor="id=" + id + "&title=" + title + "&l1=" + l1 + "&l2=" + l2 + "&l3=" + l3 ;
    	    $("#html").html( "" );
    
    	    $.ajax({
    			type: "POST",
    			url: "addtopic.php",
    			data: infor,
    			cache: false,
    			beforeSend: function () { 
    				$('#html').html('<span class="">Saving, Please Wait....</span>');
    			},
    			success: function(html) {    
    				$("#html").html( html );
    			}
    		});
      }
    		
      
    
  </script>
    
    
  
</body>

</html>