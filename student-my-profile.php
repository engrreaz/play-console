<?php 
include 'inc.php';
$stid=$_GET['stid'];
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
    
    
    
    <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  
  <script>
  

    
        // function lnk1(){ window.location.href="tools_allsubjects.php"; }

        
        
  </script>
</head>

<?php
    $sql0 = "SELECT * FROM students where stid='$stid' and sccode='$sccode'"; //echo $sql0;
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) 
    {while($row0 = $result0->fetch_assoc()) { 
    $stid=$row0["stid"]; //$=$row0[""]; 
    $neng=$row0["stnameeng"]; 
    $nben=$row0["stnameben"]; 
    }}

?>

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
                            <div class="logoo"><i class="bi bi-x-diamond-fill"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                                
                               My Profile
                            </div>
                        </td>
                    </tr>
                
                    
                </table>
            </div>
        </div>

    
    
            <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk3();" >
              <img class="card-img-top"  alt="">
              <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                        <td>
                            <h4><?php echo $neng;?></h4>
                            <h5><?php echo $nben;?></h4>
                            <small>Class & Sections, Subjects, Teachers, Users etc.</small>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
            
            

        
        
        
        
    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>
  
  
  


    
    
  
</body>

</html>