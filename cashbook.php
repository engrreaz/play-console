<?php 
include 'inc.php';
$dtst = date('Y-m-01'); $dted = date('Y-m-d');
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
            width:45px; height:45px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }
        
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px;}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
    </style>
    
    
    
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
  
    function grp(id) {
		var val=document.getElementById("sel"+id).value;
		var infor="dtid=" + id + "&val=" + val + "&opt=1";
	    $("#blocksel"+id).html( "" );

	    $.ajax({
			type: "POST",
			url: "grpupd.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#blocksel"+id).html('<span class=""><center>Fetching Section Name....</center></span>');
			},
			success: function(html) {
				$("#blocksel"+id).html( html );
			}
		});
    }
    
    function grpp(id) {
		var val=document.getElementById("sel"+id).value;
		var infor="dtid=" + id + "&val=" + val + "&opt=1";
	    $("#blocksel"+id).html( "" );

	    $.ajax({
			type: "POST",
			url: "fourupd.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#blocksel"+id).html('<span class=""><center>Fetching Section Name....</center></span>');
			},
			success: function(html) {
				$("#blocksel"+id).html( html );
			}
		});
    }
    
    function grps(id) {
		var val=document.getElementById("rel"+id).value;
		var infor="dtid=" + id + "&val=" + val  + "&opt=2";
	    $("#blocksel"+id).html( "" );

	    $.ajax({
			type: "POST",
			url: "grpupd.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#blocksel"+id).html('<span class=""><center>Fetching Section Name....</center></span>');
			},
			success: function(html) {
				$("#blocksel"+id).html( html );
			}
		});
    }
    
    
    
    
    function delpr(pr) {
		//var val=document.getElementById("sta"+id).checked;
		//alert(pr);
		var infor="prno=" + pr;
	    $("#block"+pr).html( "" );

	    $.ajax({
			type: "POST",
			url: "delmypr.php",
			data: infor,
			cache: false,
			beforeSend: function () {
				$("#block"+pr).html('Deleting....');
			},
			success: function(html) {
				$("#block"+pr).html( html );
			}
		});
    }
  </script>
    
    <script>

    function go(id){
        //window.location.href="stfinancedetails.php?id=" + id; 
        window.location.href="stfinancedetails.php?id=" + id + "&edit=1";
    }  
    function pr(id){
        let ln = "stprdetails.php?id=" + id;
        alert(ln);
        window.location.href = ln; 
    }  
  </script>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="containerx-fluid">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);border-radius:0; "  onclick="gol(<?php echo $id;?>)">
          
            <div class="card-body" style="border-radius:0;">
                <table width="100%" style="color:white;">
                    <tr>
                        <td colspan="2">
                            <div style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">Cash Book
                            
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo date('d-m-Y',strtotime($dtst));?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Date From</div>
                            <br>
                            <div style="font-size:16px; font-weight:700; line-height:15px;"><?php echo date('d-m-Y',strtotime($dted));?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Date To</div>
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:30px; font-weight:700; line-height:20px;" id="cnt">...</div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">Memo Found</div>
                            <br>
                            <div style="font-size:30px; font-weight:700; line-height:20px;" id="cntamt">...</div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">Total Amount</div>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>
        <div style="height:1px;"></div>
    
        
        <?php
        /*
        $sql0 = "SELECT * FROM sessioninfo where sessionyear='$sy' and sccode='$sccode' and classname='$classname' and sectionname = '$sectionname' order by rollno";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $stid=$row0["stid"];
        */
        
        $cnt = 0; $cntamt = 0; $mottaka = 0;
        
        
        $sql0 = "SELECT * FROM cashbook where sessionyear='$sy' and sccode='$sccode' and date between '$dtst' and '$dted' order by entrytime desc";
        //echo $sql0; 
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) 
        {while($row0 = $result0->fetch_assoc()) { 
            $date=$row0["date"]; $type=$row0["type"]; $partid=$row0["partid"];  $memo=$row0["memono"];   $particulars=$row0["particulars"];   $tk=$row0["amount"];   $entrytime=$row0["entrytime"]; $entryby=$row0["entryby"]; 
            $mottaka += $tk;
            if($type ==  'Income'){$bgc = 'seagreen'; } else {$bgc = 'red'; }
            $sql00 = "SELECT * FROM financesetup where  id='$partid'";
            $result00 = $conn->query($sql00);
            if ($result00->num_rows > 0) 
            {while($row00 = $result00->fetch_assoc()) { 
                $eng=$row00["particulareng"];
            }}

            
            
            ?>
            <div class="card text-center" style="background:var(--lighter);; color:<?php echo $bgc;?>;border-radius:0;" id="block<?php echo $prno;?>"  <?php echo $dsbl;?> >
              <div class="card-body" style="border-radius:0; color:<?php echo $bgc;?>;"  onclick="gox(<?php echo $stid;?>)"  >
                <table width="100%">
                    <tr>
                        
                        <td style="text-align:left; padding-left:5px;">
                            <div style="font-size:15px; font-weight: 700; color:<?php echo $bgc;?>"><?php echo $eng;?></div>
                            <div style="font-size:13px; font-weight: 400; color:<?php echo $bgc;?>"><?php echo $particulars;?></div>
                            <span style="font-size:11px; font-style:italic; font-weight:400; color:gray;">
                                <?php echo $entryby;?><br>@ <?php echo date('d-m-Y H:i:s', strtotime($entrytime));?>
                            </span>
                 
                        </td>
                        <td style="text-align:right; font-size:20px; font-weight:600;  color:<?php echo $bgc;?>;">
                            <?php echo number_format($tk,2,".", ","); 
                            
                            if(strtotime($cur) - strtotime($entrytime)>300) {$ddd = 'hidden';} else {$ddd = '';}
                            
                            ?>
                            <br>
                            <button class="btn btn-danger" onclick="delpr(<?php echo $prno;?>)" <?php echo $ddd;?>>Delete Receipt</button>
                        </td>
                    </tr>
                </table>
              </div>
            </div>

            <div style="height:3px;"></div>
            <?php
            $cnt++; $cntamt = $cntamt + $totaldues;
        }}
        ?>
    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
    <script>
          document.getElementById("cnt").innerHTML = "<?php echo $cnt;?>";
          document.getElementById("cntamt").innerHTML = "<?php echo number_format($mottaka,2,".",",");?>";
    </script>
</body>

</html>