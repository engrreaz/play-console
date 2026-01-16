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
    </style>
    
    <style>


.card {
    box-shadow: 0 0.46875rem 2.1875rem rgba(4,9,20,0.03), 0 0.9375rem 1.40625rem rgba(4,9,20,0.03), 0 0.25rem 0.53125rem rgba(4,9,20,0.05), 0 0.125rem 0.1875rem rgba(4,9,20,0.03);
    border-width: 0;
    transition: all .2s;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: var(--lighter);
    background-clip: border-box;
    border: 1px solid rgba(26,54,126,0.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}
.vertical-timeline {
    width: 100%;
    position: relative;
    padding: 1.5rem 0 1rem;
}

.vertical-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 8px;
    height: 100%;
    width: 4px;
    background: var(--light);
    border-radius: .25rem;
}

.vertical-timeline-element {
    position: relative;
    margin: 0 0 1rem;
}

.vertical-timeline--animate .vertical-timeline-element-icon.bounce-in {
    visibility: visible;
    animation: cd-bounce-1 .8s;
}
.vertical-timeline-element-icon {
    position: absolute;
    top: 0;
    left: 1px;
}

.vertical-timeline-element-icon .badge-dot-xl {

}

.badge-dot-xl {
    width: 18px;
    height: 18px;
    position: relative;
}
.badge:empty {
    display: none;
}


.badge-dot-xl::before {
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -5px 0 0 -5px;
    background: #f00;
}

.vertical-timeline-element-content {
    position: relative;
    margin-left: 25px;
    font-size: .8rem;
}

.vertical-timeline-element-content .timeline-title {
    font-size: .8rem;
    text-transform: uppercase;
    margin: 0 0 .5rem;
    padding: 2px 0 0;
    font-weight: bold;
}

.vertical-timeline-element-content .vertical-timeline-element-date {
    display: block;
    position: absolute;
    left: -90px;
    top: 0;
    padding-right: 10px;
    text-align: right;
    color: #adb5bd;
    font-size: .7619rem;
    white-space: nowrap;
}

.vertical-timeline-element-content:after {
    content: "";
    display: table;
    clear: both;
}

    .wd{width:20%;}
</style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid">
        <div style="height:8px;"></div>
        <div class="card text-left" style="background:var(--dark); color:var(--lighter); position:fixed; z-index:998; left:0; top:0; width:100%; border-radius:0;" >
            <div class="card-body" style="border-radius:0;">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">What's New
                            <center>
                            <div style="width:75%; height:1px; background:gray; font-size:12px; font-weight:400; margin-top:5px;"></div></center>
                            <div style="height:10px; font-size:12px; font-weight:400; margin-top:5px;">Update History</div></center>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:20px; font-weight:700; line-height:15px;">12</div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">No. of Class & Section</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo strtoupper($sectionname);?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Name of Section</div>
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:30px; font-weight:700; line-height:20px;" id="cnt">697</div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">No. of Students</div>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>
        <div style="height:150px;"></div>
            
            <div class="main-card mb-3 card mt-3">
            <div class="card-header"><b>Notice</b></div>
            <div class="card-body">
            <?php 
            $sql0 = "SELECT * FROM areas where sessionyear = '$sy' and user='$rootuser' order by FIELD(areaname,'Six', 'Seven', 'Eight', 'Nine', 'Ten'), subarea, idno";
            //echo $sql0;
                $result0 = $conn->query($sql0);
                if ($result0->num_rows > 0) 
                {while($row0 = $result0->fetch_assoc()) { 
                    $cls = $row0["areaname"];  $sec = $row0["subarea"];   $clstid = $row0["classteacher"];     $id = $row0["id"];  
                    $ico = 'iimg/' . strtolower(substr($sec,0,5)) . '.png';
                    $lnk = "cls=" . $cls . '&sec=' . $sec;
                ?>
            
                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                    <div class="vertical-timeline-item vertical-timeline-element">
                        <div>
                            <span class="vertical-timeline-element-icon bounce-in">
                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                            </span>
                            <span class="vertical-timeline-element-icon bounce-in">
                                <i class="badge badge-dot badge-dot-xl badge-success"></i>
                            </span>
                            <div class="vertical-timeline-element-content bounce-in">
                                <h4 class="timeline-title">Half-Yearly Examination 2023</h4>
                                <p>Starts from Wednesday, 07 June, 2023<a href="javascript:void(0);" data-abc="true"></a></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                

            <div style="height:8px;"></div>
            <?php }} ?>
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
 // document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
  
  
    function go(id){
        window.location.href="students.php?" + id; 
    }  
    
    
    
    
    function sublist(id) {
        var infor="sccode=<?php echo $sccode;?>&id=" + id;
        $("#tailbox"+id).html( "" );
    
        $.ajax({
            url: "fetchsublist.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#tailbox"+id).html('<span class=""><center><small>Please wait, fetching list...</small></center></span>');
            },
            success: function(html) {
                $("#tailbox"+id).html( html );
            }
        });
    }
  </script>
    
    
  
</body>

</html>