<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. প্যারামিটার হ্যান্ডলিং (Secure)
$year = $_GET['year'] ?? date('Y');
if (strlen($year) < 4) $year += 2000;

$cls2 = trim($_GET['cls'] ?? '');
$sec2 = trim($_GET['sec'] ?? '');
$roll2 = trim($_GET['roll'] ?? '');

$stid = '';
$stname_eng = $stname_ben = "";

// ২. স্টুডেন্ট আইডি ফেচিং (Prepared Statement)
if ($cls2 != '' && $sec2 != '' && $roll2 != '') {
    $stmt_st = $conn->prepare("SELECT stid FROM sessioninfo WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? AND rollno = ? LIMIT 1");
    $sy_like = $year . "%";
    $stmt_st->bind_param("sssss", $sccode, $sy_like, $cls2, $sec2, $roll2);
    $stmt_st->execute();
    $res_st = $stmt_st->get_result();
    if ($row = $res_st->fetch_assoc()) {
        $stid = $row['stid'];
        // প্রোফাইল ডাটা লুকআপ
        $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
        if ($st_idx !== false) {
            $stname_eng = $datam_st_profile[$st_idx]['stnameeng'];
            $stname_ben = $datam_st_profile[$st_idx]['stnameben'];
        }
    }
    $stmt_st->close();
}

// ৩. ফিন্যান্স সেটিংস ফেচ করা
$finsetup = [];
$stmt_fin = $conn->prepare("SELECT * FROM financesetup WHERE sccode = ? AND sessionyear LIKE ? ORDER BY slno ASC");
$stmt_fin->bind_param("ss", $sccode, $sy_like);
$stmt_fin->execute();
$res_fin = $stmt_fin->get_result();
while ($row = $res_fin->fetch_assoc()) $finsetup[] = $row;
$stmt_fin->close();

// ৪. ব্যক্তিগত সেটআপ ফেচ করা
$finsetupind = [];
if ($stid != '') {
    $stmt_ind = $conn->prepare("SELECT * FROM financesetupind WHERE sccode = ? AND sessionyear LIKE ? AND stid = ?");
    $stmt_ind->bind_param("sss", $sccode, $sy_like, $stid);
    $stmt_ind->execute();
    $res_ind = $stmt_ind->get_result();
    while ($row = $res_ind->fetch_assoc()) $finsetupind[] = $row;
    $stmt_ind->close();
}

// ফ্রিকোয়েন্সি টেক্সট ম্যাপিং
$frval = array('10', '11', '12', '22', '33', '44', '66', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
$frtxt = array('Oct', 'Nov', 'Dec', '2 Months', 'Quarterly', '4 Months', 'Half-Yearly', 'Monthly', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep');
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */
    
    /* Profile Hero Card */
    .profile-hero {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 30px 20px 40px;
        color: white;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* M3 Input Group */
    .m3-field {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 12px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px; border: 1px solid #79747E; background: transparent;
    }

    /* Payment Item Row */
    .payment-row {
        background: white; border-radius: 20px; padding: 12px 16px;
        margin-bottom: 10px; display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    
    .item-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px; flex-shrink: 0;
    }

    .amt-input {
        width: 80px; border: 1px solid #E7E0EC; border-radius: 10px;
        text-align: right; padding: 5px 10px; font-weight: 700; color: #6750A4;
    }
    .amt-input:focus { border-color: #6750A4; outline: none; background: #F3EDF7; }

    .sync-btn {
        background: #6750A4; color: white; border-radius: 100px;
        padding: 8px 16px; border: none; font-weight: 600; font-size: 0.8rem;
    }
</style>

<main class="pb-5">
    <div class="profile-hero text-center">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="settings_admin.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Individual Payment Setup</h6>
            <div style="width: 24px;"></div>
        </div>

        <?php if($stid != ''): ?>
            <div class="bg-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                <i class="bi bi-person-fill text-primary fs-2"></i>
            </div>
            <h5 class="fw-bold mb-0"><?php echo $stname_eng; ?></h5>
            <div class="small opacity-75 mb-3"><?php echo $stname_ben; ?> (ID: <?php echo $stid; ?>)</div>
            <button class="btn btn-sm btn-outline-light rounded-pill px-4" onclick="checknow('stid','', '', '<?php echo $stid; ?>','', '');">
                <i class="bi bi-arrow-repeat me-1"></i> Update Sync
            </button>
        <?php else: ?>
            <div class="py-3">
                <i class="bi bi-person-search display-5 opacity-50"></i>
                <p class="small mt-2">Please select a student to manage fees</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="container px-3">
        <?php if($stid == ''): ?>
        <div class="m3-field shadow-sm">
            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-funnel-fill me-2"></i>Select Student</h6>
            <div class="row g-2">
                <div class="col-6">
                    <div class="form-floating mb-2">
                        <select class="form-select" id="year">
                            <?php for($y=date('Y'); $y>=2024; $y--) echo "<option value='$y' ".($year==$y?'selected':'').">$y</option>"; ?>
                        </select>
                        <label>Session</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-floating mb-2">
                        <select class="form-select" id="cls">
                            <option value="">---</option>
                            <?php foreach($clslist as $c) echo "<option value='".$c['areaname']."' ".($cls2==$c['areaname']?'selected':'').">".$c['areaname']."</option>"; ?>
                        </select>
                        <label>Class</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-floating mb-2">
                        <select class="form-select" id="sec">
                            <option value="">---</option>
                            <?php foreach($seclist as $s) if($s['areaname']==$cls2) echo "<option value='".$s['subarea']."' ".($sec2==$s['subarea']?'selected':'').">".$s['subarea']."</option>"; ?>
                        </select>
                        <label>Section</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-floating mb-2">
                        <input type="number" id="roll" class="form-control" value="<?php echo $roll2; ?>">
                        <label>Roll No</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary w-100 rounded-pill mt-2 py-2 fw-bold" onclick="go();">SHOW PAYMENT ITEMS</button>
        </div>
        <?php endif; ?>

        <?php if($stid != ''): ?>
        <h6 class="ms-3 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Fee Particulars</h6>
        
        <div id="payment-list">
            <?php foreach ($finsetup as $finitem): 
                $itemcode = $finitem['itemcode'];
                $freq = $finitem['month'];
                $freq_text = str_replace($frval, $frtxt, $freq);
                
                // ব্যক্তিগত অংক খোঁজা
                $amt = 0; $ind_id = 0;
                $ind_ind = array_search($itemcode, array_column($finsetupind, 'itemcode'));
                if ($ind_ind !== false) {
                    $amt = $finsetupind[$ind_ind]['amount'];
                    $ind_id = $finsetupind[$ind_ind]['id'];
                }
            ?>
            <div class="payment-row shadow-sm">
                <div class="item-icon">
                    <i class="bi bi-cash-stack fs-5"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate small"><?php echo $finitem['particulareng']; ?></div>
                    <div class="text-muted" style="font-size: 0.65rem;">
                        <?php echo $freq_text; ?> <i class="bi bi-dot"></i> <?php echo $finitem['particularben']; ?>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <input type="number" id="amt<?php echo $itemcode; ?>" class="amt-input" value="<?php echo $amt; ?>" 
                           onblur="upddata('<?php echo $finitem['slot']; ?>','<?php echo $year; ?>', '<?php echo $itemcode; ?>','','', <?php echo $ind_id; ?>);">
                    <div id="status<?php echo $itemcode; ?>" class="ms-2">
                        <i class="bi bi-check2-circle text-muted opacity-25"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-4 px-2">
            <button class="btn btn-outline-primary w-100 rounded-pill py-2" onclick="window.location.href='st-payment-setup-indivisual.php'">
                <i class="bi bi-person-plus me-1"></i> Switch Student
            </button>
        </div>
        <?php endif; ?>

    </div>
</main>

<div id="run-text" class="text-center small"></div>



<script>
    // নেভিগেশন
    function go() {
        const year = document.getElementById('year').value;
        const sec = document.getElementById('sec').value;
        const cls = document.getElementById('cls').value;
        const roll = document.getElementById('roll').value;
        window.location.href = `st-payment-setup-indivisual.php?sec=${sec}&cls=${cls}&year=${year}&roll=${roll}`;
    }

    // ডাটা আপডেট (AJAX)
    function upddata(slot, sy, item, cls, sec, indid) {
        const amt = document.getElementById('amt' + item).value;
        const stid = '<?php echo $stid; ?>';
        const statusIcon = document.getElementById('status' + item);
        
        const infor = `slot=${slot}&sy=${sy}&item=${item}&cls=${cls}&sec=${sec}&amt=${amt}&stid=${stid}&indid=${indid}`;

        $.ajax({
            url: "backend/crud-set-financed-ind.php",
            type: "POST",
            data: infor,
            beforeSend: function () {
                statusIcon.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
            },
            success: function (html) {
                statusIcon.innerHTML = '<i class="bi bi-cloud-check-fill text-success"></i>';
            },
            error: function() {
                statusIcon.innerHTML = '<i class="bi bi-exclamation-circle text-danger"></i>';
            }
        });
    }

    // সিঙ্ক/ভ্যালিডেশন ফাংশন
    function checknow(type, part, icode, stid, cls, sec) {
        const infor = `type=${type}&part=${part}&icode=${icode}&stid=${stid}&cls=${cls}&sec=${sec}`;
        $("#run-text").html('<div class="spinner-border spinner-border-sm me-1"></div> Syncing...');
        
        $.ajax({
            type: "POST",
            url: "backend/check-student-finance.php",
            data: infor,
            success: function (html) {
                Swal.fire({
                    title: 'Sync Complete',
                    text: 'Finance data has been recalculated.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4'
                }).then(() => location.reload());
            }
        });
    }
</script>

<?php include 'footer.php'; ?>