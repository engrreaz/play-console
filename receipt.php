<?php
include 'inc.php';
$prno = $_GET['prno'];
$prdate = $_GET['prdate'];
$stname = $_GET['stname'];
$collname = $_GET['collname'];
$cls = $_GET['cls'];
$sec = $_GET['sec'];
$roll = $_GET['roll'];
$stid = $_GET['stid'];
$cnt = $_GET['cnt'];
$total = $_GET['total'];  //$ = $_GET[''];  $ = $_GET[''];  
?>

<div class="container-fluids">

    <div class="card text-start page-top-box ">
        <div class="card-body">
            <table width="100%" style="color:white;">
                <tr>
                    <td>
                        <div class="menu-icon"><i class="bi bi-receipt"></i></div>
                        <div class="menu-text"> Payments Receipt </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card text-start page-info-box ">
        <div class="card-body">
            <table width="100%" style="color:white;">
                <tr>
                    <td colspan="2">
                        <div class="d-flex pt-2 pb-2">
                            <img class="st-pic-normal" src="<?php echo $pth; ?>" />

                            <div class="ms-3 ">
                                <div class="stname-eng pb-1"><?php echo $stname; ?></div>
                                <div class="st-id">Id # <?php echo $stid; ?></div>
                                <div class="roll-no">Roll # <?php echo $roll; ?></div>
                                <div class="roll-no">class : <b><?php echo strtoupper($cls);
                                ; ?></b> <i class="bi bi-arrow-right-circle-fill"></i>
                                    <b><?php echo strtoupper($sec); ?></b>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card text-left" style="background:var(--light); color:var(--darker);">
        <div class="card-body">
            <div class="float-end"><b><?php echo $prno; ?></b></div>
            <div class="left">Reciept No. </div>

            <div class="float-end"><?php echo $prdate; ?></div>
            <div class="left">Date :</div>
        </div>
    </div>

  

    <div class="card text-left" style="background:var(--lighter); color:var(--darker);">
        <div class="card-body">
            <?php
            for ($a = 1; $a <= $cnt; $a++) {
                ?>
                <div class="border-bottom">

                
                <div class="float-end"><?php echo $_GET['item' . $a . 'taka'] . '.00';
                ; ?></div>
                <div class="left"><?php echo $_GET['item' . $a . 'txt'];
                ; ?></div>
</div>

                <?php
            }
            ?>

        </div>
    </div>

    <div class="card text-left" style="background:var(--light); color:var(--darker);">
        <div class="card-body">
            <div class="float-end"><b><?php echo $total . '.00'; ?></b></div>
            <div class="left">Total Amount : </div>
        </div>
    </div>

    <div class="card text-left" style="background:var(--lighter); color:var(--darker);">
        <div class="card-body">
            <div class="float-end"><b><?php echo $collname; ?></b></div>
            <div class="left">Collected By : </div>
        </div>
    </div>

    <div style="padding:8px;text-align:center;">
        <button class="btn btn-info mt-2 btn-block" onclick="history.go(-1);">Back </button>
    </div>


</div>






<div style="height:52px;"></div>