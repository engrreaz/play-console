<?php
include 'incc.php';
;
date_default_timezone_set('Asia/Dhaka');
;
$dt = date('Y-m-d H:i:s');
;
include('../db.php');
;
$cn = $_POST['cn'];
$sec = $_POST['sec'];
$prdate = $_POST['prdate'];

$sql0 = "SELECT stid, rollno, sum(amount) as taka FROM stpr where sccode='$sccode' and prdate='$prdate' and classname='$cn' and sectionname = '$sec' group by stid order by rollno;";
$result01xgr = $conn->query($sql0);
if ($result01xgr->num_rows > 0) {
    while ($row0 = $result01xgr->fetch_assoc()) {
        $stid = $row0["stid"];
        $rn = $row0["rollno"];

        $amto = $row0["taka"];

        $sql0 = "SELECT stnameeng, stnameben FROM students where sccode='$sccode' and stid='$stid';";
        $result01xgrd = $conn->query($sql0);
        if ($result01xgrd->num_rows > 0) {
            while ($row0 = $result01xgrd->fetch_assoc()) {
                $nam = $row0["stnameeng"];
                $namb = $row0["stnameben"];
            }
        }
        if ($nam == '') {
            $nam = $namb;
        }

        ?>

        <div class="card " style="background:var(--lighter); color:black; border-radius:0; border:0; height:52px;">
            <div
                style="width:10px; height:10px; left:20px; top:21px; position:absolute; background:deeppink; border-radius:50%;">
            </div>
            <div style="width:1px; height:53px; left:25px; top:0; position:absolute; background:deeppink; "></div>
            <div style="width:1px; height:53px; left:-5px; top:0; position:absolute; background:black; "></div>
            <div style="padding: 0 30px;">
                <div class="card-body" style="color:deeppink;">
                    <div style="font-size:15px; font-weight:700; float:right;">
                        <span style="font-size:12px; font-weight:400;">BDT</span> <?php echo number_format($amto, 2, ".", ","); ?>
                    </div>
                    <div style="font-size:12px; font-weight:400; color:black; font-style:normal;">
                        <?php echo $stid . '<b> # ' . $rn . '</b>'; ?>
                        <br><span style="color:deeppink; font-size:13px;"><?php echo $nam; ?></span>
                    </div>
                </div>

                <div id="pg<?php echo $cn . $sec . $prdate; ?>"></div>
            </div>



        </div>


        <?php
    }
}

// 
?>