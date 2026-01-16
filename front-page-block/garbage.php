<?php

if ($reallevel == 'Super Administrator') { 
    $cteachercls = $cteachersec = '';
    $cntt = 0;
    ?>
    <div class="card gg" >
        <div class="card-body">
            EIIN : <b><?php echo $sccode; ?></b><br>
            <input class="input form-control" type="number" id="scc" onblur="chng();" value="<?php echo $sccode; ?>" />
        </div>
    </div>

    <div class="card gg" >
        <div class="card-body">
            <a class="btn btn-warning" href="admin-sclist.php">Institute List</a>
            <a class="btn btn-danger" href="sout.php">Log Out</a>
            <br>
            <a class="btn btn-danger" href="kbase.php">Knowledge Base</a><br>
            <a class="btn btn-success" href="kbaseadd.php">Knowledge Add</a>
            <a class="btn btn-info" href="receipt.php?cls=Nine&sec=Science&roll=25">EPOS</a>
            <a class="btn btn-warning"
                href="stattnd.php?cls=<?php echo $cteachercls; ?>&sec=<?php echo $cteachersec; ?>">Attendance</a>

        </div>
    </div>
    <?php
}


if (strtotime($cur) < strtotime($expire)) {
    $tmcnt = strtotime($expire) - strtotime($cur);
} else {
    $tmcnt = 0;
}

if ($tmcnt <= 7 * 24 * 3600) {


    ?>
    <div id="kk" style="display:none;"><?php echo $tmcnt; ?></div>
    <div class="card gg" hidden>
        <div class="card-body">
            <h5>Service Expiry</h5>
            <small><span style="font-style:italic; ">Sir, Your service will expire in </span></small>
            <div style="font-weight:700; color:red;" id="jj"></div>
            <button class="btn btn-warning" style="margin-top:8px;" onclick="aaa();">Continue Services</button>
        </div>
    </div>

<?php } ?>

<div class="card gg" hidden>
    <div class="card-body">
        <h5>Settings</h5>
        <small><span style="font-style:italic; ">To start mark entry/process result, please insert some settings like
                teachers info, class info, subject info, create students profile.<br>To do this, click the settings
                button below :</span></small>
        <button class="btn btn-success" style="margin-top:8px;" onclick="gorx();">Go To Settings</button>
        <button class="btn btn-dark" style="margin-top:8px;" onclick="sublist();">Subjects List</button>

    </div>
</div>

<?php
if ($cntt >= 0) {
    $sql0 = "SELECT count(*) as cntt FROM areas where sessionyear LIKE '%$sy%' and user='$rootuser' and halfdone=0";
    $result01g = $conn->query($sql0);
    if ($result01g->num_rows > 0) {
        while ($row0 = $result01g->fetch_assoc()) {
            $cntt = $row0["cntt"];
        }
    }



    $sql0 = "SELECT sum(half) as req FROM areas where sessionyear LIKE '%$sy%' and user='$rootuser'";
    $result01 = $conn->query($sql0);
    if ($result01->num_rows > 0) {
        while ($row0 = $result01->fetch_assoc()) {
            $req = $row0["req"];
        }
    }
    $sql0 = "SELECT count(*) as doneold FROM pibientry where sessionyear LIKE '%$sy%' and sccode='$sccode' and exam = 'Half Yearly'";
    $result01 = $conn->query($sql0);
    if ($result01->num_rows > 0) {
        while ($row0 = $result01->fetch_assoc()) {
            $doneold = $row0["doneold"];
        }
    }
    $sql0 = "SELECT count(*) as donenew FROM stmark where sessionyear LIKE '%$sy%' and sccode='$sccode' and exam = 'Half Yearly'";
    $result01 = $conn->query($sql0);
    if ($result01->num_rows > 0) {
        while ($row0 = $result01->fetch_assoc()) {
            $donenew = $row0["donenew"];
        }
    }

    $done = $donenew + $doneold;
    if ($req > 0) {
        $rate = ceil($done * 100 / $req);
    } else {
        $rate = 0;
    }

    ?>
    <div class="card " onclick="gov();" hidden>
        <div class="card-header" style="color:var(--lighter); background:var(--dark);border-radius:0;"><b>Student
                Assessments</b></div>
        <div class="card-body">
            <h6><b>Marks Entry My Class</b></h6>
            <div style="background:var(--light);">
                <div style="background:var(--darker); width:<?php echo $rate; ?>%; height:10px;"></div>
            </div>
            <small><span style="font-style:normal;">Process : <b><?php echo $doneold; ?> </b> | Found :
                    <b><?php echo $donenew; ?> </b> | Required : <b><?php echo $req; ?> </b> | </span></small><br>
            <small><span style="font-style:italic;">Total Progress : <b><?php echo $rate; ?>%
                    </b>done.</span></small><br>
            <button class="btn btn-danger" style="margin-top:8px;" onclick="token();">Token</button>
        </div>
    </div>

    <?php


    ?>

    <div class="card gg" onclick="gor();" hidden>
        <div class="card-body">
            <h4>My Subjects (Marks Entry)</h4>
            <small><span style="font-style:italic;">View Status of Marks Entry (Annual Examination 2024)</span></small>
        </div>
    </div>


    <?php
}
