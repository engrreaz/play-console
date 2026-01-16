<?php
include 'inc.php';
include 'datam/datam-teacher.php';
$cls = $_GET['cls'];
$sec = $_GET['sec'];

$sql0b2x = "SELECT * FROM areas where sessionyear LIKE '%$sy%'  and user='$rootuser' and areaname='$cls' and subarea='$sec'";
$result0b2x = $conn->query($sql0b2x);
if ($result0b2x->num_rows > 0) {
    while ($row02x = $result0b2x->fetch_assoc()) {
        $clstid = $row02x["classteacher"];
        $zzid = $row02x["id"];
    }
}

?>

<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);">
            
        <div class="card-body page-top-box">
            <div class="page-icon"><i class="bi bi-diagram-3-fill"></i></div>
            <div class="page-title">Teacher & Subject Binding</div>
        </div>
        
        
        <div class="card-body page-info-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div style="font-size:20px; font-weight:700; line-height:15px;"><?php echo $cls; ?></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:30px;">Class
                            </div>
                            
                        </td>
                        <td >
                            <div class="text-end" style="font-size:16px; font-weight:700; line-height:15px;">
                                <?php echo strtoupper($sec); ?></div>
                            <div class="text-end"  style="font-size:12px; font-weight:400; font-style:italic; line-height:30px;">Section
                            </div>
                        </td>
               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br>Class Teacher :
                            <select class="form-select" id="subb" onchange="settidcls(<?php echo $zzid; ?>);">
                                <option></option>
                                <?php
                                $sql0b2 = "SELECT * FROM teacher where sccode = '$sccode' and status=1 order by ranks, tid desc";
                                $result0b2 = $conn->query($sql0b2);
                                if ($result0b2->num_rows > 0) {
                                    while ($row02 = $result0b2->fetch_assoc()) {
                                        $tid = $row02["tid"];
                                        $tname = $row02["tname"];
                                        ?>
                                        <option value="<?php echo $tid; ?>" <?php if ($tid == $clstid) {
                                              echo 'selected';
                                          } ?>>
                                            <?php echo $tname; ?></option><?php }
                                } ?>
                            </select>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <div style="height:8px;"></div>


        <?php
        $sql0 = "SELECT * FROM subsetup where sccode = '$sccode' and classname='$cls' and sectionname = '$sec' and sessionyear LIKE '%$sy%'  order by subject";
        //echo $sql0;
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $subcode = $row0["subject"];
                $zid = $row0["id"];
                $subtid = $row0["tid"];

                $sql0b = "SELECT * FROM subjects where subcode = '$subcode' and sccategory='$sctype' ";
                $result0b = $conn->query($sql0b);
                if ($result0b->num_rows > 0) {
                    while ($row0 = $result0b->fetch_assoc()) {
                        $subnameeng = $row0["subject"];
                        $subnameben = $row0["subben"];
                    }
                }
                ?>
                <div class="card text-center" style="background:var(--lighter); color:var(--darker);"
                    onclick="gox('<?php echo $lnk; ?>')">
                    <img class="card-img-top" alt="">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td style="text-align:left; padding-left:5px;">
                                    <div class="stname-eng"><?php echo $subnameben; ?></div>
                                    <div class="stname-ben"><?php echo $subnameeng; ?></div>
                                </td>
                                <!-- <td style="text-align:right; width:60px;" rowspan="2"><img src="<?php echo $ico; ?>" class="pic" /></td> -->
                            </tr>
                            <tr>

                                <td >
                                    <select class="form-select" id="sub<?php echo $zid; ?>"
                                        onchange="settid(<?php echo $zid; ?>);">
                                        <option></option>
                                        <?php
                                        foreach ($datam_teacher_profile as $teatea) {
                                            $tid = $teatea["tid"];
                                            $tname = $teatea["tname"];
                                            ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($tid == $subtid) {
                                                  echo 'selected';
                                              } ?>>
                                                <?php echo $tname; ?></option><?php } ?>
                                    </select>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div style="height:8px;"></div>
            <?php }
        } ?>


    </div>

</main>

<div style="height:52px;"></div>


<script>
    // document.getElementById("cnt").innerHTML = "<?php echo $cnt; ?>";


    function go(id) {
        window.location.href = "clssettings.php?" + id;
    }  
</script>


<script>
    function settid(id) {
        var tea = document.getElementById("sub" + id).value;
        var infor = "id=" + id + "&tea=" + tea + "&s=0"; //alert(infor); 
        //$("#sectionblock").html( "" );

        $.ajax({
            type: "POST",
            url: "backend/settid.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                //$('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                //$("#sectionblock").html( html );
                $("#sub" + id).css("color", "red");
            }
        });
    }


    function settidcls(id) {
        var tea = document.getElementById("subb").value;
        var infor = "id=" + id + "&tea=" + tea + "&s=1"; //alert(infor); 
        //$("#sectionblock").html( "" );

        $.ajax({
            type: "POST",
            url: "backend/settid.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                //$('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                //$("#sectionblock").html( html );
                $("#subb").css({ "color": "green", "font-weight": "700" });
            }
        });
    }
</script>


</body>

</html>