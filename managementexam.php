<?php
include 'inc.php';
if(isset($_GET['token'])){
     $devicetoken = $_GET['token'];
     if($token != $devicetoken){
        $query33px ="update usersapp set token='$devicetoken' where  email='$usr' LIMIT 1";
        $conn->query($query33px) ;
    }
} else {
    $devicetoken = $token;
}
   


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="css.css?v=a" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
  </head>

  <body style="background: var(--lighter); width:100%; max-width:100%; overflow:auto;">
    <div class="containerx" style="width: 100%">
      <div style="text-align: center; margin-top: 25px">
        <div class="logoo" style="color:black"><i class="bi bi-vector-pen"></i></div>
        <div style="margin-bottom:12px; font-weight:bold;">Exam Management</div>

        <style>
          .box {
            color: gray;
            font-weight: bold;
            text-align: left;
            padding: 7px 30px;
            border: 1px solid #ccc;
          }
          .box small {
            font-weight: 400;
            font-size: 10px;
            color: var(--normal);
            padding-left: 30px;
            font-style:italic;
          }
          .icon {
            padding-right: 5px;
          }
          

          
          .das {
            font-weight: 400;
            font-size: 12px;
            color: var(--dark);
            padding-top:8px;
          }
          
          .bbb{
              border:1px solid var(--darker);
              border-radius:5px;
              padding:7px 2px;
              background:var(--light);
              font-weight:600;
          }
          .right{
              float:right;
              margin-top:2px;
          }
          .hidden {display:none;}
          
          .box table {width:100%; }
          .box .icons {font-size:24px; wwidth:35px; text-align: left; }
          .box .title {font-size:18px; color: var(--normal); border:0px solid gray; font-weight:600;}
          .box .subtitle {font-size:11px; font-style:italic;}
          .box .subvalue {font-size:11px; font-style:normal; font-weight:600; }
        </style>

        <div class="box">
            <table>
                <tr>
                    <td class="icons"><i class="bi bi-vector-pen"></i></td>
                    <td>
                        <div class="title">Half Yearly Examination</div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div >
                            <span class="subtitle">Date from </span>
                            <span class="subvalue">05 July, 2023</span>
                            <br>
                            <span class="subtitle"> to </span>
                            <span class="subvalue">16 July, 2023</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
            
            
            
            
            

        
        

          
          
          
          
          
          
          
          
          
          
        </div>
        
        
        
        
        
      </div>




<?php include 'footer.php';?>
  </body>
</html>




 <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  
