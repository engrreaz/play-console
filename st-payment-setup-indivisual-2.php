<?php
include 'inc.php';

// $month = $_GET['m'] ?? 0;
// $year = $_GET['y'] ?? 0;

// $refno = $_GET['ref'] ?? 0;
// $undef = $_GET['undef'] ?? 99;


if (isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = date('Y');
}

if (isset($_GET['cls'])) {
    $cls2 = $_GET['cls'];
} else {
    $cls2 = '';
}
if (isset($_GET['sec'])) {
    $sec2 = $_GET['sec'];
} else {
    $sec2 = '';
}
if (isset($_GET['roll'])) {
    $roll2 = $_GET['roll'];
} else {
    $roll2 = '';
}

$cls2 = trim($cls2);
$sec2 = trim($sec2);

$sql0x = "SELECT stid  FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '$year%' and classname='$cls2' and sectionname='$sec2' and rollno='$roll2' order by id desc limit 1 ;";
$result0xxdr = $conn->query($sql0x);
if ($result0xxdr->num_rows > 0) {
    while ($row0x = $result0xxdr->fetch_assoc()) {
        $stid = $row0x['stid'];
    }
} else {
    $stid = '';
}




$status = 0;

if (isset($_GET['addnew'])) {
    $newblock = 'block';
    $exid = $_GET['addnew'];
    if ($exid == '') {
        $exid = 0;
    }
} else {
    $newblock = 'none';
    $exid = 0;
}
// $newblock = 'block';

$classnamelist = ' playnurseryonetwothreefourfivesixseveneightnineten';
$sql0x = "SELECT count(*) as cnt FROM sessioninfo where  sccode='$sccode' and sessionyear LIKE '$year%'  ;";
$result0xxd = $conn->query($sql0x);
if ($result0xxd->num_rows > 0) {
    while ($row0x = $result0xxd->fetch_assoc()) {
        $tsc = $row0x['cnt'];
    }
}

$finsetup = array();
$sql0x = "SELECT * FROM financesetup where sccode='$sccode' and sessionyear LIKE '%$year%' order by slno;";
// echo $sql0x;
$result0x = $conn->query($sql0x);
if ($result0x->num_rows > 0) {
    while ($row0x = $result0x->fetch_assoc()) {
        $finsetup[] = $row0x;
    }
}


$finsetupval = array();
$sql0x = "SELECT * FROM financesetupvalue where sccode='$sccode' and sessionyear LIKE '%$year%' order by slno;";
// echo $sql0x;
$result0xval = $conn->query($sql0x);
if ($result0xval->num_rows > 0) {
    while ($row0x = $result0xval->fetch_assoc()) {
        $finsetupval[] = $row0x;
    }
}
$cntcode = count($finsetupval);


$finsetupind = array();
$sql0x = "SELECT * FROM financesetupind where sccode='$sccode' and sessionyear LIKE '%$year%' and stid='$stid' order by slno;";
// echo $sql0x;
$result0xvalst = $conn->query($sql0x);
if ($result0xvalst->num_rows > 0) {
    while ($row0x = $result0xvalst->fetch_assoc()) {
        $finsetupind[] = $row0x;
    }
}
// var_dump($finsetupind);

$clslist = array();
$sql0x = "SELECT areaname, slot, sessionyear FROM areas where user='$rootuser' and sessionyear like '$year%' group by areaname order by idno ;";
$result0xxt = $conn->query($sql0x);
if ($result0xxt->num_rows > 0) {
    while ($row0x = $result0xxt->fetch_assoc()) {
        $clslist[] = $row0x;
    }
}

$seclist = array();
$sql0x = "SELECT areaname, subarea FROM areas where user='$rootuser' and sessionyear like '$year%' group by areaname, subarea order by idno ;";
$result0xxt = $conn->query($sql0x);
if ($result0xxt->num_rows > 0) {
    while ($row0x = $result0xxt->fetch_assoc()) {
        $seclist[] = $row0x;
    }
}

$frval = array( '10', '11', '12', '22', '33', '44', '66', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
$frtxt = array('October', 'November', 'December', 'Two Months Frequency', 'Quarterly', 'Four Months Frequency', 'Half-Yearly', 'Every Month', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September');

?>
<style>
    thead th {
        position: sticky;
        top: 0;
    }
</style>


<h3 id="lbl-inex">Student's Payment Setup</h3>

<style>
    #prog.fade {
        opacity: 1;
        transition: opacity 2s;
    }

    #prog {
        opacity: 0;
        transition: opacity 3s;
    }
    
    .pointer{
        cursor:pointer;
    }
</style>

<div class="row " id="newblock" style="display:<?php echo $newblock; ?>;">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted font-weight-normal">
                    Add a New Payment Item(s)
                </h6>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover text-white">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php



                                $sccodes = $sccode * 10;
                                $sql0x = "SELECT * FROM financesetup where sccode='$sccode' and id = '$exid' ;";
                                $result0x = $conn->query($sql0x);
                                if ($result0x->num_rows > 0) {
                                    while ($row0x = $result0x->fetch_assoc()) {
                                        $peng = $row0x["particulareng"];
                                        $pben = $row0x["particularben"];
                                        $mon = $row0x["month"];
                                        $incom = $row0x["inexin"];
                                        if ($incom == 1) {
                                            $incom = 'checked';
                                        } else {
                                            $incom = '';
                                        }
                                        $expen = $row0x["inexex"];
                                        if ($expen == 1) {
                                            $expen = 'checked';
                                        } else {
                                            $expen = '';
                                        }
                                    }
                                } else {
                                    $peng = "";
                                    $pben = "";
                                    $mon = "";
                                    $expen = "";
                                    $incom = '';
                                    $expen = '';
                                }
                                // $ = $row0x[""];
                                ?>
                                <tr>
                                    <td>Particulars (In English) :
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="peng"
                                            value="<?php echo $peng; ?>" />
                                    </td>

                                </tr>
                                <tr>
                                    <td>Particulars (In Bengali) :
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="pben"
                                            value="<?php echo $pben; ?>" />
                                    </td>
                                </tr>

                                <tr>
                                    <td>Month (Payment Applied) :</td>
                                    <td>
                                        <select class="form-control" id="monmon">
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                if ($i == $mon) {
                                                    $yoyo = 'selected';
                                                } else {
                                                    $yoyo = '';
                                                }

                                                if ($i == 0) {
                                                    $mname = 'Every Month';
                                                } else if ($i == 22) {
                                                    $mname = 'February, April, June, August, October, December';
                                                } else if ($i == 33) {
                                                    $mname = 'March, June, September, November';
                                                } else if ($i == 44) {
                                                    $mname = 'April, August, November';
                                                } else if ($i == 66) {
                                                    $mname = 'January, November';
                                                } else {
                                                    $tarikh = '2024-' . $i . '-01';
                                                    $mname = date('F', strtotime($tarikh));
                                                }
                                                echo '<option value="' . $i . ' " ' . $yoyo . '>' . $i . ' - ' . $mname . '</option>';
                                            }
                                            echo '<option value=""></option>';
                                            echo '<option value="0" ' . $yoyo . '>Every Month</option>';
                                            echo '<option value="22" ' . $yoyo . '>2 Months Frequency : February, April, June, August, October, December</option>';
                                            echo '<option value="33" ' . $yoyo . '>3 Months Frequency : March, June, September, November</option>';
                                            echo '<option value="44" ' . $yoyo . '>4 Months Frequency : April, August, November</option>';
                                            echo '<option value="66" ' . $yoyo . '>6 Months Frequency : January, November</option>';

                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Item also Displayed in :</td>
                                    <td>
                                        <table class="borderless">
                                            <tr>
                                                <td><input type="checkbox" class="form-control" id="inin" <?php echo $incom; ?> /></td>
                                                <td>Income</td>
                                                <td><input type="checkbox" class="form-control" id="exex" value="" <?php echo $expen; ?> /></td>
                                                <td>Expenditure</td>
                                            </tr>
                                        </table>
                                    </td>

                                </tr>



                                <tr>
                                    <td></td>
                                    <td>
                                        <div id="">
                                            <button class="btn btn-primary"
                                                onclick="crud(<?php echo $exid; ?>, 1);">Save</button>
                                            <div class="text-small text-danger">
                                                <i class="mdi mdi-exclamation mdi-24px text-danger"></i>
                                                Caution ! Update Payment Item Information after synced payment setting
                                                at your own risk.
                                                <br>Please Contact with your system administrator to do this.
                                            </div>
                                            <div id="gex"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-3 grid-margin stretch-card">
<div class="card">
    <div class="card-body">
        <h6>Select Student to setup Fees and Payments</h6>

        <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label text-small">Session</label>
                      
                                <select class="form-control " id="year">
                                    <option value="0"></option>
                                    <?php
                                    for ($y = date('Y'); $y >= 2024; $y--) {
                                        $flt2 = '';
                                        if ($year == $y) {
                                            $flt2 = 'selected';
                                        }
                                        echo '<option value="' . $y . '"' . $flt2 . '>' . $y . '</option>';
                                    }
                                    ?>
                                </select>
                            
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label text-small">Class :</label>
                            
                                <select class="form-control" id="cls" onchange="go();">
                                    <option value="">---</option>
                                    <?php
                                    $sql0x = "SELECT areaname FROM areas where user='$rootuser' and sessionyear='$year' group by areaname order by idno;";
                                    $result0x = $conn->query($sql0x);
                                    if ($result0x->num_rows > 0) {
                                        while ($row0x = $result0x->fetch_assoc()) {
                                            $cls = $row0x["areaname"];
                                            if ($cls == $cls2) {
                                                $selcls = 'selected';
                                            } else {
                                                $selcls = '';
                                            }
                                            echo '<option value="' . $cls . '" ' . $selcls . ' >' . $cls . '</option>';
                                        }
                                    }
                                    ?>

                                </select>
                     
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label text-small">Section</label>
                       
                                <select class="form-control " id="sec" onchange="go();">
                                    <option value="">---</option>
                                    <?php
                                    $sql0x = "SELECT subarea FROM areas where user='$rootuser' and sessionyear='$year' and areaname='$cls2' group by subarea order by idno;";
                                    // echo $sql0x;
                                    $result0r = $conn->query($sql0x);
                                    if ($result0r->num_rows > 0) {
                                        while ($row0x = $result0r->fetch_assoc()) {
                                            $sec = $row0x["subarea"];
                                            if ($sec == $sec2) {
                                                $selsec = 'selected';
                                            } else {
                                                $selsec = '';
                                            }
                                            echo '<option value="' . $sec . '" ' . $selsec . ' >' . $sec . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                        
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group row">
                         
                                <label class="col-form-label text-small">Roll No.</label>
                                <input type="text" id="roll" class="form-control " value="<?php echo $roll2;?>" >
                           
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group row">
                     
                  
                                <button type="button" style="padding:4px 10px 6px; border-radius:5px;"
                                    class="btn btn-lg btn-inverse-primary btn-icon-text btn-block pt-2 pb-2" style=""
                                    onclick="go();"> Show Payment Items </button>
                      
                        </div>
                    </div>

                    <div class="col-md-12" hidden>
                        <?php echo $stid;?>
                    </div>




    </div>
</div>
    </div>
    <div class="col-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div id="sspd"></div>
                <h5 class="text-muted font-weight-normal font-weight-bold">
                    Payment Items List
                </h5>

                <?php


                /*
                                $pick = "SELECT * FROM stfinance where partid='$id'  and sccode='$sccode' and sessionyear LIKE '$sy%' and stid = '$stid2' order by month ;";
                                $result0xx21 = $conn->query($pick);
                                if ($result0xx21->num_rows > 0) {
                                    while ($row0xn = $result0xx21->fetch_assoc()) {
                                        $datam[] = $row0xn;
                                    }
                                } else {
                                    $datam[] = '';
                                }
                                // echo var_dump($datam);
                                */
                ?>




                <div class="box sticky-top">
                    <div class="text-small text-muted ">
                        <input id="progx" class=" borderless bg-dark" style=" accent-color: #333333;" type="radio"
                            checked />
                        <span id="tsc"><?php echo $tsc; ?></span> Students Found.
                    </div>

                    <div id="gexx" class="text-small text-warning mb-2"></div>

                    <div id="prog" style="display:none;" class="progress progress-md portfolio-progress">
                        <div id="progbar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="more" class="text-small text-primary mt-2"></div>
                </div>



                <!-- -------------------------------------------------------------------------------------- -->




                <div class="row text-white">
                    <div class="col-12">


                        <?php


                        foreach ($finsetup as $finitem) {
                            $itemcode = $finitem['itemcode'];
                            $freq = $finitem['month'];
                            $slot = $finitem['slot'];
                            $idu = $finitem['id'];

                            $finvalcode = array();
                            $amt = 0;
                            $id1 = 0;
                            $syear = $sy;
                            for ($i = 0; $i < $cntcode; $i++) {
                                if ($finsetupval[$i]['itemcode'] == $itemcode) {
                                    $finvalcode[] = $finsetupval[$i];
                                    if ($finsetupval[$i]['classname'] == NULL && $finsetupval[$i]['sectionname'] == NULL) {
                                        $amt = $finsetupval[$i]['amount'];
                                        $id1 = $finsetupval[$i]['id'];
                                    }

                                 
                                    $ind_ind = array_search($itemcode, array_column($finsetupind, 'itemcode'));
                                    if($ind_ind !='' || $ind_ind != NULL){
                                        $amt = $finsetupind[$ind_ind]['amount'];
                                        $ind_id = $finsetupind[$ind_ind]['id'];
                                    } else {
                                        $ind_id = 0;
                                    }
                                    // echo $ind_id;
                                }
                            }
                            $arrcnt = count($finvalcode);
                            // var_dump($finvalcode);
                        
                            if($freq>0 && $freq<13){
                                $txt_color = 'info';
                                if($freq == 1){
                                     $txt_color = 'muted';
                                }
                            } else {
                                $txt_color = 'warning';
                            }
                            ?>

                            <div class="card">
                                <div class="card-header" id="item<?php echo $itemcode; ?>">
                                    <div class="row">
                                        <div class="col-9 p-0 m-0 d-flex">
                                            <div class="pointer">
                                                <i id="chev<?php echo $itemcode; ?>"
                                                    class="mdi mdi-chevron-right text-success mdi-24px"
                                                    onclick="itemsx('<?php echo $itemcode; ?>');"></i>
                                            </div>
                                            <div class="ml-3 pointer " onclick="itemsx('<?php echo $itemcode; ?>');">
                                                <h6 class="p-0 m-0"><?php echo $finitem['particulareng']; ?></h6>
                                                <div class="text-small text-muted m-0 p-0">
                                                    <?php echo $finitem['particularben']; ?> <span class="text-<?php echo $txt_color;?>"> 
                                                       <b> (<?php echo str_replace($frval, $frtxt, $freq); ?>)</b></span>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-3 p-0">
                                            <div class="form-group m-0 p-0 d-flex">
                                                <div id="status<?php echo $itemcode; ?>">
                                                    <button class="btn  pt-2  mr-3"><i
                                                            class="mdi  mdi-checkbox-blank-circle-outline text-dark mdi-18px"></i></button>

                                                </div>
                                                <input type="text" id="id<?php echo $itemcode; ?>"
                                                    value="<?php echo $id1; ?>" hidden />



                                                <div class="input-group">
                                                    <input type="text" class="form-control m-0 text-right"
                                                        id="amt<?php echo $itemcode; ?>" value="<?php echo $amt; ?>"
                                                        onclick="no();"
                                                        onblur="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','','', <?php echo $ind_id;?>);;" />

                                                    <div class="input-group-apend ">
                                                        <button class="btn btn-inverse-success  p-2"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            onclick="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','','', <?php echo $ind_id;?>);;" 
                                                            aria-expanded="false"><i
                                                                class="mdi mdi-content-save"></i></button>
                                                        
                                                    </div>

                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body m-0 p-0" id="itemex<?php echo $itemcode; ?>" style="display:none; ">
                                    <?php
                                    foreach ($clslist as $cls) {
                                        $clsname = $cls['areaname'];
                                        $slt = $cls['slot'];
                                        $syear = $cls['sessionyear'];
                                        // echo $clsname;
                                        $amtcls = 0;
                                        $id2 = 0;
                                        for ($j = 0; $j < $arrcnt; $j++) {
                                            if ($finvalcode[$j]['classname'] == $clsname && $finvalcode[$j]['sectionname'] == NULL) {
                                                $amtcls = $finvalcode[$j]['amount'];
                                                $id2 = $finvalcode[$j]['id'];
                                            }
                                        }

                                        ?>

                                        <div class="card" id="cls<?php echo $itemcode . $clsname; ?>" onclick="">
                                            <div class="card-header">
                                                <div class="row p-0">
                                                    <div class="col-9 p-0 text-primary d-flex">
                                                        <div style="width:35px;"></div>
                                                        <div class="pointer">
                                                            <i id="chev<?php echo $itemcode . $clsname; ?>"
                                                                class="mdi mdi-chevron-right text-primary mdi-24px"
                                                                onclick="cls('<?php echo $itemcode . $clsname; ?>');"></i>
                                                        </div>
                                                        <div class="ml-3 pointer" onclick="cls('<?php echo $itemcode . $clsname; ?>');">
                                                            <h6 class="p-0 m-0"><?php echo $clsname; ?></h6>
                                                            <div class="text-small text-muted"><?php echo $slt . ' : ' . $syear; ?></div>
                                                        </div>




                                                    </div>


                                                    <div class="col-3 p-0">
                                                        <div class="form-group m-0 p-0 d-flex">
                                                            <div id="status<?php echo $itemcode . $clsname; ?>">
                                                                <button class="btn  pt-2  mr-3"><i
                                                                        class="mdi  mdi-checkbox-blank-circle-outline text-dark mdi-18px"></i></button>

                                                            </div>
                                                            <input type="text" id="id<?php echo $itemcode . $clsname; ?>"
                                                                value="<?php echo $id2; ?>" hidden />

                                                            <div class="input-group">
                                                                <input type="text" class="form-control m-0"
                                                                    id="amt<?php echo $itemcode . $clsname; ?>"
                                                                    value="<?php echo $amtcls; ?>" onclick="no();"
                                                                    onblur="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','<?php echo $clsname; ?>','', 1);;" />
                                                                <div class="input-group-apend ">
                                                                    <button class="btn btn-inverse-primary dropdown-toggle p-2"
                                                                        type="button" data-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false"><i
                                                                            class="mdi mdi-arrow-down-drop-circle"></i></button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item text-small text-white"  onclick="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','<?php echo $clsname; ?>','', 1);;" href="#">Update Fees</a>
                                                                        <a class="dropdown-item text-small text-white" onclick="preloads('cls','icode', '<?php echo $itemcode; ?>','','<?php echo $clsname; ?>','', 1);;" href="#">Apply Fees to (<?php echo $clsname;?>)</a> 
                                                                    </div>
                                                                </div>
                                                            </div>




                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="card-body m-0 p-0" id="clsex<?php echo $itemcode . $clsname; ?>"
                                                style="display:none;">

                                                <?php
                                                foreach ($seclist as $sec) {
                                                    $secname = $sec['subarea'];
                                                    if ($sec['areaname'] == $clsname) {
                                                        $amtsec = 0;
                                                        $id3 = 0;
                                                        for ($k = 0; $k < $arrcnt; $k++) {
                                                            if (ucwords($finvalcode[$k]['classname']) == ucwords($clsname) && ucwords($finvalcode[$k]['sectionname']) == ucwords($secname)) {
                                                                $amtsec = $finvalcode[$k]['amount'];
                                                                $id3 = $finvalcode[$k]['id'];
                                                            }
                                                            // Reazul Hoque
                                                            // echo strlen($finsetupval[$k]['classname']. '/' . strlen($clsname) . '//' . strlen($finsetupval[$k]['sectionname']). '/' . strlen($secname)) . ' ---- ';
                                                            // echo ($finvalcode[$k]['classname']. '/' . ($clsname) . '*' . ($finvalcode[$k]['sectionname']). '/' . ($secname)) . ' : ' . $finsetupval[$k]['amount'] . '<br>';
                                                        }
                                                        ?>
                                                        <div class="card">
                                                            <div class="card-header"
                                                                id="sec<?php echo $itemcode . $clsname . $secname; ?>" onclick="">

                                                                <div class="row p-0">
                                                                    <div class="col-9 p-0 d-flex text-info">

                                                                        <div style="width:70px;"></div>
                                                                        <div class="pointer">
                                                                            <i id="chev<?php echo $itemcode . $clsname . $secname; ?>"
                                                                                class="mdi mdi-chevron-right text-info mdi-24px"
                                                                                onclick="sec('<?php echo $itemcode . $clsname . $secname; ?>');"></i>
                                                                        </div>
                                                                        <div class="ml-3 pt-2 pointer" onclick="sec('<?php echo $itemcode . $clsname . $secname; ?>');">
                                                                            <h6 class="p-0 m-0 mt-1 font-weight-bold"><?php echo $secname; ?></h6>
                                                                        </div>





                                                                    </div>
                                                                    <div class="col-3 p-0">
                                                                        <div class="form-group m-0 p-0 d-flex">
                                                                            <div
                                                                                id="status<?php echo $itemcode . $clsname . $secname; ?>">
                                                                                <button class="btn  pt-2  mr-3"><i
                                                                                        class="mdi  mdi-checkbox-blank-circle-outline text-dark mdi-18px"></i></button>

                                                                            </div>
                                                                            <input type="text"
                                                                                id="id<?php echo $itemcode . $clsname . $secname; ?>"
                                                                                value="<?php echo $id3; ?>" hidden />

                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control m-0 text-info" style="background:#d2b4de;"
                                                                                    style="opacity: 0.8;"
                                                                                    id="amt<?php echo $itemcode . $clsname . $secname; ?>"
                                                                                    value="<?php echo $amtsec; ?>" onclick="no();"
                                                                                    onblur="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','<?php echo $clsname; ?>','<?php echo $secname; ?>', 1);;" />

                                                                                <div class="input-group-apend ">
                                                                                    <button
                                                                                        class="btn btn-inverse-info dropdown-toggle p-2"
                                                                                        type="button" data-toggle="dropdown"
                                                                                        aria-haspopup="true" aria-expanded="false">
                                                                                        <i class="mdi mdi-arrow-down-drop-circle"></i>
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item text-small text-white"  onclick="upddata('<?php echo $slot; ?>','<?php echo $syear; ?>', '<?php echo $itemcode; ?>','<?php echo $clsname; ?>','<?php echo $secname; ?>', 1);"   href="#">Update Fees</a>
                                                                                        <a class="dropdown-item text-small text-white"  onclick="preloads('sec','icode', '<?php echo $itemcode; ?>','','<?php echo $clsname; ?>','<?php echo $secname; ?>', 1);" href="#">Apply Fees to (<?php echo $clsname . ' | ' . $secname; ?>)</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>




                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="card-body m-0 p-0 pl-5"
                                                                id="secex<?php echo $itemcode . $clsname . $secname; ?>"
                                                                style="display:none;">
                                                                <div class="row m-0 p-0 pl-5">
                                                                    -
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <?php
                                                    }
                                                }
                                                ?>


                                            </div>
                                        </div>





                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>



                            <?php

                        }
                        ?>




                    </div>
                </div>









                <div class="row" hidden >
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="position:sticky;">
                                <tr>
                                    <th>#</th>
                                    <th>Particulars</th>
                                    <th></th>
                                    <th>All</th>
                                    <?php
                                    $valid_class_list = '';
                                    $sql0x = "SELECT areaname FROM areas where user='$rootuser' and sessionyear like '$sy%' group by areaname order by idno ;";
                                    $result0xxt = $conn->query($sql0x);
                                    if ($result0xxt->num_rows > 0) {
                                        while ($row0x = $result0xxt->fetch_assoc()) {
                                            $cname = strtoupper($row0x["areaname"]);

                                            if (strpos($classnamelist, strtolower($cname)) > 0) {
                                                echo '<th class=" text-center">' . $cname . '</th>';
                                                $valid_class_list .= strtolower($cname) . '_';
                                            }
                                        }
                                    }

                                    ?>
                                    <th style="text-align:right;"></th>
                                    <th style="text-align:right;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $si = 1;
                                $sql0x = "SELECT * FROM financeitem where payment=1 and (sccode=0 || sccode = '$sccode') order by slno;";
                                // echo $sql0x;
                                $result0x = $conn->query($sql0x);
                                if ($result0x->num_rows > 0) {
                                    while ($row0x = $result0x->fetch_assoc()) {
                                        $id = $row0x["id"];
                                        $slno = $row0x["slno"];
                                        $parteng = $row0x["particulareng"];
                                        $partben = $row0x["particularben"];

                                        $custom = $row0x["sccode"];
                                        $freq = $row0x["month"];

                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
                                            <td style="line-height:20px;"><?php echo $parteng . '<br>' . $partben; ?></td>
                                            <td style="line-height:20px;">
                                                <?php if ($freq > 12) {
                                                    echo '~' . $freq / 11;
                                                } else {
                                                    echo $freq;
                                                } ?>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" id="" value="" style="width:60px;"
                                                    disabled />
                                            </td>
                                            <?php
                                            $exp = '';
                                            $sql0x = "SELECT areaname FROM areas where user='$rootuser'  and sessionyear like '$sy%' group by areaname order by idno ;";
                                            // echo $sql0x;
                                            $result0xx = $conn->query($sql0x);
                                            if ($result0xx->num_rows > 0) {
                                                while ($row0x = $result0xx->fetch_assoc()) {
                                                    $clsfld = strtolower($row0x["areaname"]);
                                                    if (strpos($classnamelist, $clsfld) > 0) {

                                                        $sql0x = "SELECT * FROM financesetup where sccode='$sccode' and sessionyear LIKE '%$sy%' and particulareng='$parteng' ;";
                                                        // echo $sql0x;
                                                        $result0xxxr = $conn->query($sql0x);
                                                        if ($result0xxxr->num_rows > 0) {
                                                            while ($row0x = $result0xxxr->fetch_assoc()) {

                                                                $taka = $row0x[$clsfld];
                                                                $idfin = $row0x['id'];

                                                                $itemcode = $row0x['itemcode'];

                                                                if ($itemcode == '' || $itemcode == NULL) {
                                                                    $icod = uniqid();
                                                                    $query331 = "UPDATE financesetup set itemcode='$icod' where id='$idfin';";
                                                                    $conn->query($query331);
                                                                }


                                                                $nupd = $row0x["need_update"];
                                                                if ($nupd == 0) {
                                                                    $syncclr = 'secondary';
                                                                    $ttl = 'Already Updated';
                                                                } else {
                                                                    $syncclr = 'success';
                                                                    $ttl = 'Need to Update';
                                                                }
                                                            }
                                                        } else {
                                                            $taka = '-';
                                                            $idfin = 0;
                                                            $syncclr = 'warning';
                                                            $ttl = 'Item Not Applied Yet';
                                                        }
                                                        ?>

                                                        <td class="pl-1 pr-1 text-center">

                                                            <?php
                                                            echo $itemcode;

                                                            ?>


                                                            <div id="div<?php echo $clsfld . $idfin; ?>"></div>
                                                            <input type="text" class="form-control text-right"
                                                                id="<?php echo $clsfld . $idfin; ?>" value="<?php echo $taka; ?>"
                                                                onblur="push(<?php echo $idfin; ?>, <?php echo $id; ?>, '<?php echo $clsfld; ?>', '<?php echo $clsfld . $idfin; ?>' );"
                                                                style="width:55px;" />

                                                        </td>

                                                        <?php
                                                    } else {
                                                        ?>

                                                        <td class="pl-1 pr-1  text-center" hidden>
                                                            <input type="text" class="form-control bg-dark " id="" value=""
                                                                style="width:50px;" hidden />
                                                        </td>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>

                                            <td class="m-0 p-0">
                                                <div id="ssp<?php echo $id; ?>">

                                                    <?php
                                                    if ($custom == $sccode) {
                                                        ?>
                                                        <button onclick="edits(<?php echo $id; ?>);"
                                                            class="btn btn-inverse-primary"><i
                                                                class="mdi mdi-grease-pencil mdi-18px pt-3"></i></button>
                                                        <?php
                                                    }
                                                    ?>
                                            </td>
                                            <td class="m-0 p-0 pr-3">
                                                <div id="tags<?php echo $idfin; ?>" style="display:none;">
                                                    <?php echo $parteng . ' (' . $partben . ')'; ?>
                                                </div>

                                                <div id="freq<?php echo $idfin; ?>" style="display:none;">
                                                    <?php echo $freq; ?>
                                                </div>

                                                <button onclick="syncfinance(<?php echo $idfin; ?>,1);"
                                                    class="btn btn-inverse-<?php echo $syncclr; ?>"><span
                                                        id="spn<?php echo $idfin; ?>"><i class="mdi mdi-sync mdi-18px pt-2"
                                                            title="<?php echo $ttl; ?>"></i></span></button>

                                                <button onclick="syncfinancech(<?php echo $idfin; ?>,1);"
                                                    class="btn btn-inverse-danger"><span id="spn2<?php echo $idfin; ?>"><i
                                                            class="mdi mdi-checkbox-marked-circle-outline mdi-18px pt-2"
                                                            title="<?php echo $ttl; ?>"></i></span></button>
                                                <?php echo $idfin; ?>
                            </div>
                            <div id="sspp<?php echo $id; ?>"></div>
                            </td>
                            </tr>
                            <?php $si++;
                                    }
                                } else { ?>
                        <tr>
                            <td colspan="7">No Data / Records Found.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php
include 'footer.php';
?>

<script>
    var uri = window.location.href;
    document.getElementById('defbtn').innerHTML = '';
    document.getElementById('defmenu').innerHTML = '';
    function defbtn() {
        // go();
    }


        function go() {
        var year = document.getElementById('year').value;
        var sec = document.getElementById('sec').value;
        var cls = document.getElementById('cls').value;
        var roll = document.getElementById('roll').value;
        window.location.href = 'st-payment-setup-indivisual.php?sec=' + sec + '&cls=' + cls + '&year=' + year + '&roll=' + roll ;
    }



    function edits(idn) {
        window.location.href = 'st-payment-setup-2.php?&addnew=' + idn;
    }



</script>
<script>
    function push(idfin, id, cls, tail) {
        var taka = document.getElementById(tail).value;
        var infor = "idfin=" + idfin + "&id=" + id + "&cls=" + cls + "&taka=" + taka;
        // alert(id + '/' + tail);
        $("#div" + tail).html("");

        $.ajax({
            url: "backend/set-finance.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#div" + tail).html('<span class=""><small></small></span>');
            },
            success: function (html) {
                $("#div" + tail).html(html);
                if (document.getElementById("div" + tail).innerHTML == 'insert') {
                    window.location.href = 'st-payment-setup.php';
                }
                document.getElementById(tail).style.borderColor = 'green';
            }
        });
    }
</script>
<script>
    function upddata(slot, sy, item, cls, sec, indid) {


        ind = item + cls + sec;
        // alert(ind);
        var amt = document.getElementById('amt' + ind).value;
        var id = document.getElementById('id' + ind).value;
        var stid = <?php echo $stid;?>;
// alert(indid);
        var infor = "id=" + id + "&slot=" + slot + "&sy=" + sy + "&item=" + item + "&cls=" + cls + "&sec=" + sec + "&amt=" + amt + "&stid=" + stid + "&indid=" + indid;
        // alert(infor);
        $("#status" + ind).html("");

        $.ajax({
            url: "backend/crud-set-financed-ind.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#status" + ind).html('<span class=""><small></small></span>');
            },
            success: function (html) {
                $("#status" + ind).html(html);
                // window.location.href = 'st-payment-setup.php';
                // if (document.getElementById("div" + tail).innerHTML == 'insert') {
                //     window.location.href = 'st-payment-setup.php';
                // }
                document.getElementById('amt' + ind).disabled = true;

            }
        });
    }
</script>

<script>
   function preloads(type, part, icode, stid, cls, sec, tail) {
        ind = icode + cls + sec;
        var infor = "type=" + type + "&part=" + part + "&icode=" + icode + "&stid=" + stid + "&cls=" + cls + "&sec=" + sec + "&tail=" + tail;
        // alert(infor);
        $("#status"+ind).html(""); 
        $.ajax({
            type: "POST",
            url: "backend/check-student-finance-pre.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#status"+ind).html('<i class="mdi mdi-autorenew"></i>');
            },
            success: function (html) {
                $("#status"+ind).html(html);
                // alert(type);
                window.location.href = 'sync-payment.php?' + infor;
            }
        });
    }
</script>

<script>
function del(id, tail){
    var x = confirm("Are you sure to delete?");
    if(x===true){
        crud(id, tail);
    }
}

    function crud(id, tail) {
        var eng = document.getElementById('peng').value;
        var ben = document.getElementById('pben').value;
        var month = document.getElementById('monmon').value;
        var inin = document.getElementById('inin').checked;
        var exex = document.getElementById('exex').checked;
        var infor = "id=" + id + "&tail=" + tail + "&eng=" + eng + "&ben=" + ben + "&month=" + month + "&inin=" + inin + "&exex=" + exex;
        // alert(infor);

        $("#gex").html("");

        $.ajax({
            url: "backend/crud-set-finance-add.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#gex").html('<span class=""><small></small></span>');
            },
            success: function (html) {
                $("#gex").html(html);

                window.location.href = 'st-payment-setup-2.php';

                // if (document.getElementById("div" + tail).innerHTML == 'insert') {
                //     window.location.href = 'st-payment-setup.php';
                // }
                // document.getElementById(tail).style.borderColor = 'green';
            }
        });
    }
</script>


<script>
    function myg() {
        document.getElementById("prog").classList.toggle('fade');
        document.getElementById("prog").style.display = 'none';
        document.getElementById("more").innerHTML = '';
        document.getElementById("gexx").innerHTML = '';
    }
    function done() {
        setTimeout(myg, 2000);
    }
</script>

<script>
    function syncfinance(id, tail) {
        document.getElementById("more").innerHTML = "";
        document.getElementById("prog").style.display = 'flex';
        document.getElementById("progbar").style.width = '0%';
        var tsc = parseInt(<?php echo $tsc; ?>);
        var freq = parseInt(document.getElementById("freq" + id).innerHTML);
        if (freq == 0) {
            // document.getElementById("tsc").innerHTML = tsc * 12;
        }
        document.getElementById("progx").focus();
        // document.getElementById("prog").style.opacity = "1";
        document.getElementById("prog").classList.add('fade');
        syncfinance2(id, tail);
    }

    function syncfinancech(id, tail) {
        document.getElementById("more").innerHTML = "";
        document.getElementById("prog").style.display = 'flex';
        document.getElementById("progbar").style.width = '0%';
        var tsc = parseInt(<?php echo $tsc; ?>);
        var freq = parseInt(document.getElementById("freq" + id).innerHTML);
        if (freq == 0) {
            // document.getElementById("tsc").innerHTML = tsc * 12;
        }
        document.getElementById("progx").focus();
        // document.getElementById("prog").style.opacity = "1";
        document.getElementById("prog").classList.add('fade');
        syncfinancech2(id, tail);
    }

    function syncfinance2(id, tail) {
        var mor = document.getElementById("more").innerHTML;
        var txt = document.getElementById("tags" + id).innerHTML;
        // alert("repeta" + mor);
        if (mor == '') { document.getElementById("more").innerHTML = 0; }
        var infor = "id=" + id + "&tail=" + tail + "&vcl=<?php echo $valid_class_list; ?>";
        // alert(infor);
        $("#gexx").html("----------");

        // setInterval(function () {
        //     var object = document.getElementById('spn' + id);
        //     object.style.transform += "rotate(10deg)";
        // }, 10);

        $.ajax({
            url: "backend/sync-finance-amount-slow.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#gexx").html('<span class=""><small>Please wait, data syncing continue. It may take some time...</small> <br><span class="text-success">' + txt + '</span> </span>');
            },
            success: function (html) {
                $("#gexx").html(txt + '<br>' + html);
                var more = document.getElementById("more").innerHTML;
                let position = more.search("Done");
                if (position < 0) {
                    var curval = parseInt(more);
                    var totval = parseInt(document.getElementById("tsc").innerHTML);
                    var perc = curval * 100 / totval;
                    document.getElementById("progbar").style.width = perc + '%';
                    syncfinance2(id, tail);
                } else {
                    document.getElementById("progbar").style.width = '100%';
                    document.getElementById("gexx").innerHTML = txt;
                    document.getElementById("more").innerHTML = 'Payment Updated Successfully.';
                    document.getElementById("prog").classList.toggle('fade');
                    done();
                }
                // window.location.href = 'st-payment-setup.php';
                // if (document.getElementById("div" + tail).innerHTML == 'insert') {
                //     window.location.href = 'st-payment-setup.php';
                // }
                // document.getElementById(tail).style.borderColor = 'green';
            }
        });
    }

    function syncfinancech2(id, tail) {
        var mor = document.getElementById("more").innerHTML;
        var txt = document.getElementById("tags" + id).innerHTML;
        // alert("repeta" + mor);
        if (mor == '') { document.getElementById("more").innerHTML = 0; }
        var infor = "id=" + id + "&tail=" + tail + "&vcl=<?php echo $valid_class_list; ?>";
        // alert(infor);
        $("#gexx").html("----------");

        // setInterval(function () {
        //     var object = document.getElementById('spn' + id);
        //     object.style.transform += "rotate(10deg)";
        // }, 10);

        $.ajax({
            url: "backend/sync-finance-check.php", type: "POST", data: infor, cache: false,
            beforeSend: function () {
                $("#gexx").html('<span class=""><small>Please wait, data syncing continue. It may take some time...</small> <br><span class="text-success">' + txt + '</span> </span>');
            },
            success: function (html) {
                $("#gexx").html(txt + '<br>' + html);
                var more = document.getElementById("more").innerHTML;
                let position = more.search("Done");
                if (position < 0) {
                    var curval = parseInt(more);
                    var totval = parseInt(document.getElementById("tsc").innerHTML);
                    var perc = curval * 100 / totval;
                    document.getElementById("progbar").style.width = perc + '%';
                    syncfinance2(id, tail);
                } else {
                    document.getElementById("progbar").style.width = '100%';
                    document.getElementById("gexx").innerHTML = txt;
                    // document.getElementById("more").innerHTML = 'Payment Checked Successfully.';
                    document.getElementById("prog").classList.toggle('fade');
                    // done();
                }
                // window.location.href = 'st-payment-setup.php';
                // if (document.getElementById("div" + tail).innerHTML == 'insert') {
                //     window.location.href = 'st-payment-setup.php';
                // }
                // document.getElementById(tail).style.borderColor = 'green';
            }
        });
    }
</script>


<script>
    function no() {
        event.stopPropagation();
    }

    function items(code) {
        event.stopPropagation();
        var els = document.getElementById("itemex" + code);
        var chev = document.getElementById("chev" + code);
        if (els.style.display === 'block') {
            els.style.display = 'none';
            chev.classlist.remove("mdi-chevron-right");
            chev.classlist.add("mdi-chevron-down");
        } else {
            els.style.display = 'block';
            chev.classlist.remove("mdi-chevron-down");
            chev.classlist.add("mdi-chevron-right");
        }
    }


    function cls(code) {
        event.stopPropagation();
        var els = document.getElementById("clsex" + code);
        if (els.style.display === 'block') {
            els.style.display = 'none';
        } else {
            els.style.display = 'block';
        }
    }
    function sec(code) {
        event.stopPropagation();
        var els = document.getElementById("secex" + code);
        if (els.style.display === 'block') {
            els.style.display = 'none';
        } else {
            els.style.display = 'block';
        }
    }


  
</script>
<script>
      function defitem() {
        // event.stopPropagation();
        // alert(1);
        // doucment.getElementById("newblock").style.display = "block";
        // alert(1);
        crud(0,5);
    }
</script>