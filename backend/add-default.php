<?php
include('inc.back.php');

$id = $_POST['id'];

$sql00xgr = "SELECT * FROM areas where id='$id'";
$result00xgr = $conn->query($sql00xgr);
if ($result00xgr->num_rows > 0) {
    while ($row00xgr = $result00xgr->fetch_assoc()) {
        $clsf = $row00xgr["areaname"];
        $secf = $row00xgr["subarea"];
        $YEAR = $row00xgr["sessionyear"];
    }
}

/*
$sql00xgrf = "SELECT * FROM sessioninfo where sccode='$sccode' order by stid desc LIMIT 1";  
$result00xgrf = $conn->query($sql00xgrf);
if ($result00xgrf->num_rows > 0) {while($row00xgrf = $result00xgrf->fetch_assoc()) {
$lastid=$row00xgrf["stid"]; }} else {$lastid = $sccode * 10000 ;} $lastid = $lastid + 1;
*/

// echo $clsf . '/' . $secf . '/' . $YEAR;

if ($clsf == 'Six' || $clsf == 'Seven' || $clsf == 'Eight') {

    $sql242a = "SELECT * FROM subjectsettinglist where classname='$clsf' order by subject";
    $result242a = $conn->query($sql242a);
    if ($result242a->num_rows > 0) {
        while ($row242a = $result242a->fetch_assoc()) {
            $subcode = $row242a['subject'];
            $idr = $row242a['id'];

            $qq = "INSERT INTO subsetup (sccode, classname, sectionname, subject,fullmarks, subj, obj, pra, ca, camanual, pass_algorithm, cnt, reverse, sessionyear) SELECT '$sccode', classname, '$secf', subject,fullmarks, subj, obj, pra, 0, camanual, pass_algorithm, cnt, reverse, '$YEAR' from subjectsettinglist where id = '$idr';";
            $conn->query($qq);

        }
    }

} else if ($clsf == 'Nine' || $clsf == 'Ten') {
    
    $secx = substr($secf, 0, 5);
    $sql242a = "SELECT * FROM subjectsettinglist where classname='Ten' and sectionname like '%$secx%' and sccategory = '$sctype' order by subject";
    $result242a = $conn->query($sql242a);
    if ($result242a->num_rows > 0) {
        while ($row242a = $result242a->fetch_assoc()) {
            $subcode = $row242a['subject'];
            $idr = $row242a['id'];

            $qq = "INSERT INTO subsetup (sessionyear, sccode, classname, sectionname, subject,fullmarks, subj, obj, pra, ca, camanual, pass_algorithm, cnt, reverse) SELECT '$YEAR', '$sccode', '$clsf', '$secf', subject,fullmarks, subj, obj, pra, 0, camanual, pass_algorithm, cnt, reverse from subjectsettinglist where id = '$idr';";
            echo $qq;

            $conn->query($qq);

        }
    }

}


// echo $dust;

$sql242 = "SELECT * FROM subsetup where classname='$clsf' and sectionname = '$secf' and sccode='$sccode' and sessionyear = '$YEAR' ";
$result242 = $conn->query($sql242);
if ($result242->num_rows > 0) {
    while ($row242 = $result242->fetch_assoc()) {
        $subcode = $row242['subject'];

        $sql242f = "SELECT * FROM subjects where subcode='$subcode' and sccategory='$sctype' ";
        $result242f = $conn->query($sql242f);
        if ($result242f->num_rows > 0) {
            while ($row242f = $result242f->fetch_assoc()) {
                $subname = $row242f['subject'];
                $subben = $row242f['subben'];
            }
        }

        ?>

        <div class="card" style="background:var(--lighter); color:var(--darker);">
            <img class="card-img-top" alt="">
            <div class="card-body">
                <table style="">
                    <tr>
                        <td style="width:50px; vertical-align:top; color:var(--dark);"><i class="bi bi-book"></i></td>
                        <td>
                            <div><?php echo $subname . '<br>' . $subben;
                            ; ?></div>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="height:8px;"></div>



    <?php }
}