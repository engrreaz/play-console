<?php
	include 'incc.php';;
	date_default_timezone_set('Asia/Dhaka');;
    $dt = date('Y-m-d H:i:s');; 
	include ('../db.php');;
	$prdate= $_POST['prdate'];

    $sql0 = "SELECT classname, sectionname, sum(amount) as taka FROM stpr where sccode='$sccode' and prdate='$prdate' group by classname, sectionname order by FIELD(classname, 'Six', 'Seven', 'Eight', 'Nine', 'Ten');";
    $result01xgr = $conn->query($sql0); if ($result01xgr->num_rows > 0) {while($row0 = $result01xgr->fetch_assoc()) { 
        $cn=$row0["classname"]; 
        $sec=$row0["sectionname"]; $sec2 = str_replace(" ","-",$sec);
        $amt=$row0["taka"]; 
        
        ?>

                    <div class="card " style="background:var(--lighter); color:black; border-radius:0; border:0;"  onclick="gogx('<?php echo $cn;?>','<?php echo $sec;?>','<?php echo $prdate;?>')">
                        <div style="width:10px; height:10px; left:20px; top:21px; position:absolute; background:black; border-radius:50%;"></div>
                        <div style="width:1px; height:53px; left:25px; top:0; position:absolute; background:black; "></div>
                        <div style="padding: 0 30px;">
                            <div class="card-body">
                                <div style="font-size:15px; font-weight:700; float:right;">
                                <span style="font-size:12px; font-weight:500;">BDT</span> <?php echo number_format($amt,2,".",",");?>
                                </div>
                                <div style="font-size:14px; font-weight:600; color:black; font-style:normal;"><?php echo $cn . ' - ' . $sec;?></div>
                            </div>
                            
                            <div id="p<?php echo $cn.$sec2.$prdate;?>"></div>
                        </div>
                        
                        
                        
                    </div>
                    
        
        <?php
    }}

// 
?>
