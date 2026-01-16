<?php 
include 'inc.php';
?>

    <style>
        .pic{
            width:45px; height:45px; padding:1px; border-radius:50%; border:1px solid var(--dark); margin:5px;
        }
        
        .a{font-size:18px; font-weight:700; font-style:normal; line-height:22px; color:var(--dark);}
        .b{font-size:16px; font-weight:600; font-style:normal; line-height:22px;}
        .c{font-size:11px; font-weight:400; font-style:italic; line-height:16px;}
        .card-bodyx{padding:0 25px;}     
        .lblx{font-size:11px; margin:3px 0px 5px 12px; color: gray; }
        
        
        .icon{font-size:30px; valign:top; width: 40px;}
        .title{display:block; font-size:16px; font-weight:500; }
        .subtitle{display:block; font-size:12px; font-weight:400; color: gray;}
        .rightpart{text-align:right; width; 50px;}
        
    </style>
    

  <main>
    <div class="container-fluidx">
        <div class="card page-box  no-rad text-left" style="background:var(--dark); color:var(--lighter);"  onclick="gox()">
          
            <div class="card-body">
                <div class="page-top-box">
                    <div class="menu-icon "><i class="bi bi-patch-question-fill"></i></div>
               
                    <div class="menu-text">
                         Knowledge Base<br>জ্ঞান কোষ
                    </div>
                   
                </div>
            </div>
        </div>
    
    
            <?php
                $sql0 = "SELECT * FROM kbase1 order by sl";
                $result0vf = $conn->query($sql0);
                if ($result0vf->num_rows > 0) 
                {while($row0 = $result0vf->fetch_assoc()) { 
                    $id=$row0["id"];
                    $icon=$row0["icon"];
                    $title=$row0["title"];
                    
                    $sql0 = "SELECT count(*) as qnt FROM kbase3 where kbase1='$id'";
                    $result0vfx = $conn->query($sql0);
                    if ($result0vfx->num_rows > 0) 
                    {while($row0 = $result0vfx->fetch_assoc()) { 
                        $qnt=$row0["qnt"];
                    }}
                    $sql0 = "SELECT count(*) as qnt FROM kbasedone where kbase1='$id' and email='$usr'";
                    $result0vfxd = $conn->query($sql0);
                    if ($result0vfxd->num_rows > 0) 
                    {while($row0 = $result0vfxd->fetch_assoc()) { 
                        $qnt2=$row0["qnt"];
                    }}
                    if($qnt==0){$perc = 100;} else {$perc = ceil($qnt2*100/$qnt);}
                    if($perc==100){$cl = 'var(--light)';$cl2 = 'gray';} else {$cl = 'var(--dark)';$cl2 = 'var(--dark)';}
                    
                    
                ?>
                    <div class="card" style="background:var(--lighter); color:<?php echo $cl;?>;"  onclick="godeep(<?php echo $id;?>);">
                        <div class="card-body">
                            <table style="width:100%; margin:0 10px;">
                                <tr>
                                    <td class="icon" style="color:<?php echo $cl;?>;"><span><i class="bi <?php echo $icon;?>"></i></span></td>
                                    <td>
                                        <span class="title"  style="color:<?php echo $cl2;?>;"><?php echo $title;?></span>
                                        <span class="subtitle">মোট  <?php echo $qnt;?> টি লেসন, <?php echo $qnt2;?> টি সমাপ্ত</span>
                                    </td>
                                    <td class="rightpart"><span>
                                        <div class="pie animate no-round" style="margin:0; --p:<?php echo $perc;?>;--c:<?php echo $cl;?>;--b:3px;"><?php echo $perc;?>%</div>
                                    </span></td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                <?php  }}?>
    
    
    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>


  
  <script>

  
    function go(){
        var cls=document.getElementById("classname").value; 
        var sec=document.getElementById("sectionname").value; 
        var sub=document.getElementById("subject").value; 
        var assess=document.getElementById("assessment").value; 
        var exam=document.getElementById("exam").value; 
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess; 
        if(cls=='Six'|| cls == 'Seven'){
            window.location.href="markpibi.php" + tail; 
        } else {
            window.location.href="markentry.php" + tail; 
        }
    }  
  </script>
  
  
  <script>
      function fetchsection() {
		var cls=document.getElementById("classname").value;

		var infor="user=<?php echo $rootuser;?>&cls=" + cls;
	$("#sectionblock").html( "" );

	 $.ajax({
			type: "POST",
			url: "fetchsection.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
			},
			success: function(html) {    
				$("#sectionblock").html( html );
			}
		});
    }
  </script>
  
  <script>
      function fetchsubject() {
		var cls=document.getElementById("classname").value;
		var sec=document.getElementById("sectionname").value;

		var infor="sccode=<?php echo $sccode;?>&tid=<?php echo $userid;?>&cls=" + cls + "&sec=" + sec; //alert(infor);
	$("#subblock").html( "" );

	 $.ajax({
			type: "POST",
			url: "fetchsubject.php",
			data: infor,
			cache: false,
			beforeSend: function () { 
				$('#subblock').html('<span class="">Retriving Subjects...</span>');
			},
			success: function(html) {    
				$("#subblock").html( html );
			}
		});
    }
    
    function print(){
        window.print();
    }
    
    function godeep(id){
        window.location.href="kbase2.php?base=" + id; 
    }
  </script>
    
    