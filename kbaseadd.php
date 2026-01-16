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
        body{
            font-family:Calibri, SutonnyOMJ; font-size:18px;
        }
        .pic{
            width:45px; height:45px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }
        
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        .card-bodyx{padding:0 25px;}     
        .lbl{font-size:14px; margin:3px 0px 5px 12px; color: gray; display:block;}
        
        
        .icon{font-size:30px; color: var(--dark); valign:top; width: 40px;}
        .title{display:block; font-size:16px; font-weight:500; color: var(--dark);}
        .subtitle{display:block; font-size:12px; font-weight:400; color: gray;}
        .rightpart{text-align:right; width; 50px;}
        
    </style>
    
    
    <style>

</style>          
            

</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);"  onclick="gox()">
          
            <div class="card-body">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="logoo"><i class="bi bi-patch-question-fill"></i></div>
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:25px;">
                                Knowledge Base Manager<br>জ্ঞান কোষ ব্যবস্থাপক
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    

                    <div class="card" style="background:var(--lighter); color:var(--darker);"  >
                        <div class="card-body">
                            <div class="lbl">Subject</div>
                            <div class="input-group mb-3" id="inbox" >
                                
                                <select class="form-select form-select-md " onchange="block2();" id="subject" aria-label="form-select-lg example" style=" background:var(--lighter);">
                                    <option value="" selected>Top Topics</option>
                                    <?php
                                    $sql0 = "SELECT * FROM kbase1  order by id";
                                    $result0 = $conn->query($sql0);
                                    if ($result0->num_rows > 0) 
                                    {while($row0 = $result0->fetch_assoc()) { 
                                        $id = $row0["id"]; $title = $row0["title"]; 
                                        echo '<option value="' . $id . '">' . $title . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            
                            <div class="input-group">
                                <span class="input-group-text"><i class="material-icons">create_new_folder</i></span>
                                <input type="text" class="form-control" id="add2" placeholder=".............." value="">
                                <button type="button"  class="btn btn-dark mt-2" onclick="block2();">Submit</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="background:var(--lighter); color:var(--darker);"  >
                        <div class="card-body">
                            <div class="lbl">Module</div>
                            <div class="input-group mb-3" id="block2" >
                                
                                <select class="form-select form-select-md " id="partid1" aria-label="form-select-lg example" style=" background:var(--lighter);">
                                    <option value="" selected>Top Topics</option>
                                    <?php
                                    $sql0 = "SELECT * FROM kbase1 where id=0  order by id";
                                    $result0 = $conn->query($sql0);
                                    if ($result0->num_rows > 0) 
                                    {while($row0 = $result0->fetch_assoc()) { 
                                        $id = $row0["id"]; $title = $row0["title"]; 
                                        echo '<option value="' . $id . '">' . $title . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="material-icons">create_new_folder</i></span>
                                <input type="text" class="form-control" id="add3" placeholder=".............." value="">
                                <button type="button"  class="btn btn-dark mt-2" onclick="block3();">Submit</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="background:var(--lighter); color:var(--darker);"  >
                        <div class="card-body">
                            <div class="lbl">Section</div>
                            <div class="input-group mb-3" id="block3" >
                                
                                <select class="form-select form-select-md " id="partid1" aria-label="form-select-lg example" style=" background:var(--lighter);">
                                    <option value="" selected>Top Topics</option>
                                    <?php
                                    $sql0 = "SELECT * FROM kbase1 where id=0  order by id";
                                    $result0 = $conn->query($sql0);
                                    if ($result0->num_rows > 0) 
                                    {while($row0 = $result0->fetch_assoc()) { 
                                        $id = $row0["id"]; $title = $row0["title"]; 
                                        echo '<option value="' . $id . '">' . $title . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="material-icons">create_new_folder</i></span>
                                <input type="text" class="form-control" id="add4" placeholder=".............." value="">
                                <button type="button"  class="btn btn-dark mt-2" onclick="block4();">Submit</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="background:var(--lighter); color:var(--darker);"  >
                        <div class="card-body">
                            <div class="lbl">Lesson</div>
                            <div class="input-group mb-3" id="block4" >
                                
                                <select class="form-select form-select-md " id="partid1" aria-label="form-select-lg example" style=" background:var(--lighter);">
                                    <option value="" selected>Top Topics</option>
                                    <?php
                                    $sql0 = "SELECT * FROM kbase1 where id=0  order by id";
                                    $result0 = $conn->query($sql0);
                                    if ($result0->num_rows > 0) 
                                    {while($row0 = $result0->fetch_assoc()) { 
                                        $id = $row0["id"]; $title = $row0["title"]; 
                                        echo '<option value="' . $id . '">' . $title . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="material-icons">create_new_folder</i></span>
                                <input type="text" class="form-control" id="add5" placeholder=".............." value="">
                                <button type="button"  class="btn btn-dark mt-2" onclick="block5();">Submit</button>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card" style="background:var(--lighter); color:var(--darker);"  >
                        <div class="card-body">
                            <div class="lbl">Steps</div>
                            <div class="input-group mb-3" id="block5" >
                                
                                <select class="form-select form-select-md " id="partid1" aria-label="form-select-lg example" style=" background:var(--lighter);">
                                    <option value="" selected>Top Topics</option>
                                    <?php
                                    $sql0 = "SELECT * FROM kbase1  where id=0 order by id";
                                    $result0 = $conn->query($sql0);
                                    if ($result0->num_rows > 0) 
                                    {while($row0 = $result0->fetch_assoc()) { 
                                        $id = $row0["id"]; $title = $row0["title"]; 
                                        echo '<option value="' . $id . '">' . $title . '</option>';
                                    }}
                                    ?>
                                </select>
                            </div>
                        </div>
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
    function block2() {
        var subject = document.getElementById("subject").value;
        var add2 = document.getElementById("add2").value;
        var infor="subject=" + subject + "&add2=" + add2 ;
        $("#block2").html( "" );
        
        $.ajax({
            type: "POST",
            url: "kbase-add-block-2.php",
            data: infor,
            cache: false,
            beforeSend: function () { 
                $('#block2').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function(html) {    
                $("#block2").html( html );
                document.getElementById("add2").value = '';
            }
        });
    }
</script>

<script>
    function block3() {
        var subject = document.getElementById("subject").value;
        var module = document.getElementById("module").value;
        
        var add2 = document.getElementById("add2").value;
        var add3 = document.getElementById("add3").value;
        var infor="subject=" + subject + "&module=" + module  + "&add2=" + add2  + "&add3=" + add3;
        $("#block3").html( "" );
        
        $.ajax({
            type: "POST",
            url: "kbase-add-block-3.php",
            data: infor,
            cache: false,
            beforeSend: function () { 
                $('#block3').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function(html) {    
                $("#block3").html( html );
            }
        });
    }
</script>

<script>
    function block4() {
        var subject = document.getElementById("subject").value;
        var module = document.getElementById("module").value;
        var section = document.getElementById("section").value;
        
        var add2 = document.getElementById("add2").value;
        var add3 = document.getElementById("add3").value;
        var add4 = document.getElementById("add4").value;
        var infor="subject=" + subject + "&module=" + module + "&section=" + section   + "&add2=" + add2  + "&add3=" + add3 + "&add4=" + add4;
        $("#block4").html( "" );
        
        $.ajax({
            type: "POST",
            url: "kbase-add-block-4.php",
            data: infor,
            cache: false,
            beforeSend: function () { 
                $('#block4').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function(html) {    
                $("#block4").html( html );
            }
        });
    }
</script>

<script>
    function block5() {
        var subject = document.getElementById("subject").value;
        var module = document.getElementById("module").value;
        var section = document.getElementById("section").value;
        var step = document.getElementById("step").value;
        
        var add2 = document.getElementById("add2").value;
        var add3 = document.getElementById("add3").value;
        var add4 = document.getElementById("add4").value;
        var add5 = document.getElementById("add5").value;
        var infor="subject=" + subject + "&module=" + module + "&section=" + section + "&step=" + step    + "&add2=" + add2  + "&add3=" + add3 + "&add4=" + add4 + "&add5=" + add5 ;
        $("#block5").html( "" );
        
        $.ajax({
            type: "POST",
            url: "kbase-add-block-5.php",
            data: infor,
            cache: false,
            beforeSend: function () { 
                $('#block5').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function(html) {    
                $("#block5").html( html );
            }
        });
    }
</script>



<script>
    function addstepitem() {
        var subject = document.getElementById("subject").value;
        var module = document.getElementById("module").value;
        var section = document.getElementById("section").value;
        var step = document.getElementById("step").value;
        var item = document.getElementById("stepitem").value;
        var infor="subject=" + subject + "&module=" + module + "&section=" + section + "&step=" + step + "&item=" + item;
        $("#block5").html( "" );
        
        $.ajax({
            type: "POST",
            url: "kbase-add-block-5.php",
            data: infor,
            cache: false,
            beforeSend: function () { 
                $('#block5').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function(html) {    
                $("#block5").html( html );
            }
        });
    }
</script>
  
  
    
    
  
</body>

</html>