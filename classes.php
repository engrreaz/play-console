<?php
include 'inc.php';
?>

<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);">

            <div class="card-body page-top-box">
                <div class="page-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <div class="page-title">Classes & Sections </div>
                <div class="roll-no"> Binding Subject with teacher according to Classes & Sections </div>
            </div>


            <div class="card-body page-info-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div style="font-size:20px; font-weight:700; line-height:15px;" id="ct"></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:28px;">No. of
                                Class & Section</div>
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:20px; font-weight:700; line-height:20px;" id="cnt"></div>
                            <div style="font-size:12px; font-weight:400; font-style:italic; line-height:28px;">No. of
                                Subjects</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <?php
        $totcls = 0;

        $sql0 = "SELECT count(*) as cpt FROM subsetup where sessionyear LIKE '%$sy%' and sccode='$sccode' ";
        //echo $sql0;
        $result0g = $conn->query($sql0);
        if ($result0g->num_rows > 0) {
            while ($row0 = $result0g->fetch_assoc()) {
                $cpt = $row0["cpt"];
            }
        }

        $sql0 = "SELECT * FROM areas where sessionyear LIKE '%$sy%' and user='$rootuser' order by idno, id";
        //echo $sql0;
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $cls = $row0["areaname"];
                $sec = $row0["subarea"];
                $ico = 'iimg/' . strtolower(substr($sec, 0, 5)) . '.png';
                $lnk = "cls=" . $cls . '&sec=' . $sec;
                $totcls = $totcls + 1;
                ?>
                <div class="card  mb-2" style="background:var(--lighter); color:var(--darker);"
                    onclick="go('<?php echo $lnk; ?>')">
                    <img class="card-img-top" alt="">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td style="text-align:left; padding-left:5px;">
                                    <div class="stname-eng"><?php echo strtoupper($cls); ?></div>
                                    <div class="stname-ben"><?php echo $sec; ?></div>
                                </td>
                                <td style="text-align:right;"><img src="<?php echo $ico; ?>" class="st-pic-small" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php }
        } ?>


    </div>

</main>

<div style="height:52px;"></div>


<script>
    document.getElementById("cnt").innerHTML = "<?php echo $cpt; ?>";
    document.getElementById("ct").innerHTML = "<?php echo $totcls; ?>";


    function go(id) {
        window.location.href = "cls-settings.php?" + id;
    }  
</script>