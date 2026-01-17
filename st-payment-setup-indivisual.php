<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. প্যারামিটার হ্যান্ডলিং
$cls2 = trim($_GET['cls'] ?? '');
$sec2 = trim($_GET['sec'] ?? '');
$roll2 = trim($_GET['roll'] ?? '');
$stid = '';
$stname_eng = $stname_ben = "";

// ৩. স্টুডেন্ট আইডি ফেচিং (Prepared Statement)
if ($cls2 != '' && $sec2 != '' && $roll2 != '') {
    $stmt_st = $conn->prepare("SELECT stid FROM sessioninfo WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? AND rollno = ? LIMIT 1");
    $stmt_st->bind_param("sssss", $sccode, $sy_param, $cls2, $sec2, $roll2);
    $stmt_st->execute();
    $res_st = $stmt_st->get_result();
    if ($row = $res_st->fetch_assoc()) {
        $stid = $row['stid'];
        // প্রোফাইল ডাটা লুকআপ (Array search in datam)
        $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
        if ($st_ind !== false) {
            $stname_eng = $datam_st_profile[$st_ind]['stnameeng'];
            $stname_ben = $datam_st_profile[$st_ind]['stnameben'];
        }
    }
    $stmt_st->close();
}

// ৪. ফিন্যান্স মাস্টার সেটিংস ফেচ করা
$finsetup = [];
$stmt_fin = $conn->prepare("SELECT * FROM financesetup WHERE sccode = ? AND sessionyear LIKE ? ORDER BY slno ASC");
$stmt_fin->bind_param("ss", $sccode, $sy_param);
$stmt_fin->execute();
$res_fin = $stmt_fin->get_result();
while ($row = $res_fin->fetch_assoc()) $finsetup[] = $row;
$stmt_fin->close();

// ৫. ব্যক্তিগত কাস্টম ফি সেটআপ ফেচ করা
$finsetupind = [];
if ($stid != '') {
    $stmt_ind = $conn->prepare("SELECT * FROM financesetupind WHERE sccode = ? AND sessionyear LIKE ? AND stid = ?");
    $stmt_ind->bind_param("sss", $sccode, $sy_param, $stid);
    $stmt_ind->execute();
    $res_ind = $stmt_ind->get_result();
    while ($row = $res_ind->fetch_assoc()) $finsetupind[] = $row;
    $stmt_ind->close();
}

$page_title = "Individual Setup";
$frval = array('10', '11', '12', '22', '33', '44', '66', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
$frtxt = array('Oct', 'Nov', 'Dec', '2 Mo.', 'Quarter', '4 Mo.', 'Half-Yr.', 'Monthly', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep');
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; }

    /* M3 Components (8px Radius) */
    .m3-card { background: #fff; border-radius: 8px; padding: 12px; margin: 0 8px 8px; border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .hero-banner { background: #6750A4; color: #fff; border-radius: 0 0 8px 8px; padding: 16px; margin-bottom: 12px; }
    
    /* Condensed Item Style */
    .fee-row { 
        display: flex; align-items: center; padding: 10px 12px; background: #fff; 
        border-radius: 8px; margin: 0 8px 6px; border: 1px solid #f0f0f0; transition: 0.2s; 
    }
    
    .item-icon { 
        width: 40px; height: 40px; border-radius: 6px; background: #F3EDF7; 
        color: #6750A4; display: flex; align-items: center; justify-content: center; 
        margin-right: 12px; flex-shrink: 0; 
    }

    .amt-input { 
        width: 80px; border: 1px solid #79747E; border-radius: 6px; 
        text-align: right; padding: 4px 8px; font-weight: 700; color: #6750A4; 
    }
    .amt-input:focus { border-color: #6750A4; outline: none; background: #F3EDF7; }

    .input-field { border-radius: 8px !important; border: 1px solid #79747E; background: #fff; font-weight: 700; }
    .btn-pill { border-radius: 8px !important; font-weight: 700; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <i class="bi bi-person-badge fs-4"></i>
    </div>
</header>

<main class="pb-5">
    <div class="hero-banner shadow-sm">
        <div class="d-flex align-items-center">
            <div class="bg-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-person-fill text-primary fs-3"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <?php if($stid != ''): ?>
                    <div class="fw-bold text-truncate" style="font-size: 0.95rem;"><?php echo $stname_eng; ?></div>
                    <div class="small opacity-80"><?php echo "$cls2 - $sec2 | Roll: $roll2"; ?></div>
                    <div class="small fw-bold">ID: <?php echo $stid; ?> | Session: <?php echo $current_session; ?></div>
                <?php else: ?>
                    <div class="fw-bold">No Student Selected</div>
                    <div class="small opacity-75">Please use filters below</div>
                <?php endif; ?>
            </div>
            <?php if($stid != ''): ?>
                <button class="btn btn-sm btn-outline-light border-white" style="border-radius: 6px;" onclick="syncNow('stid', '<?php echo $stid; ?>');">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if($stid == ''): ?>
    <div class="m3-card shadow-sm">
        <h6 class="fw-bold text-primary mb-3 small uppercase"><i class="bi bi-funnel-fill me-2"></i>Filter Student</h6>
        <div class="row g-2">
            <div class="col-6">
                <select class="form-select input-field" id="year">
                    <?php for($y=date('Y'); $y>=2024; $y--) echo "<option value='$y' ".($current_session==$y?'selected':'').">$y</option>"; ?>
                </select>
            </div>
            <div class="col-6">
                <select class="form-select input-field" id="cls">
                    <option value="">Class</option>
                    <?php foreach($clslist as $c) echo "<option value='".$c['areaname']."' ".($cls2==$c['areaname']?'selected':'').">".$c['areaname']."</option>"; ?>
                </select>
            </div>
            <div class="col-6">
                <select class="form-select input-field" id="sec">
                    <option value="">Section</option>
                    <?php foreach($seclist as $s) echo "<option value='".$s['subarea']."' ".($sec2==$s['subarea']?'selected':'').">".$s['subarea']."</option>"; ?>
                </select>
            </div>
            <div class="col-6">
                <input type="number" id="roll" class="form-control input-field" placeholder="Roll No" value="<?php echo $roll2; ?>">
            </div>
        </div>
        <button class="btn btn-primary btn-pill w-100 mt-3 py-2" onclick="go();">LOAD STUDENT</button>
    </div>
    <?php endif; ?>

    <?php if($stid != ''): ?>
    <div class="px-2">
        <h6 class="fw-bold text-secondary mb-3 ms-2 small uppercase tracking-wider">Fee Structures</h6>
        <div id="payment-list">
            <?php foreach ($finsetup as $finitem): 
                $itemcode = $finitem['itemcode'];
                $freq_text = str_replace($frval, $frtxt, $finitem['month']);
                
                // ব্যক্তিগত অংক খোঁজা
                $amt = 0; $ind_id = 0;
                $ind_ind = array_search($itemcode, array_column($finsetupind, 'itemcode'));
                if ($ind_ind !== false) {
                    $amt = $finsetupind[$ind_ind]['amount'];
                    $ind_id = $finsetupind[$ind_ind]['id'];
                }
            ?>
            <div class="fee-row shadow-sm">
                <div class="item-icon"><i class="bi bi-tag-fill fs-5"></i></div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;"><?php echo $finitem['particulareng']; ?></div>
                    <div class="text-muted" style="font-size: 0.65rem;">
                        <?php echo $freq_text; ?> <i class="bi bi-dot"></i> <?php echo $finitem['particularben']; ?>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <input type="number" id="amt<?php echo $itemcode; ?>" class="amt-input" value="<?php echo $amt; ?>" 
                           onblur="saveInd('<?php echo $finitem['slot']; ?>', '<?php echo $current_session; ?>', '<?php echo $itemcode; ?>', <?php echo $ind_id; ?>);">
                    <div id="status<?php echo $itemcode; ?>" class="ms-2"><i class="bi bi-check2-circle opacity-25"></i></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-4 px-2">
            <button class="btn btn-outline-primary btn-pill w-100 py-2" onclick="window.location.href='st-payment-setup-indivisual.php'">
                <i class="bi bi-arrow-left-right me-1"></i> SWITCH STUDENT
            </button>
        </div>
    </div>
    <?php endif; ?>
</main>

<script>
    function go() {
        const y = document.getElementById('year').value;
        const c = document.getElementById('cls').value;
        const s = document.getElementById('sec').value;
        const r = document.getElementById('roll').value;
        window.location.href = `st-payment-setup-indivisual.php?cls=${c}&sec=${s}&year=${y}&roll=${r}`;
    }

    function saveInd(slot, sy, item, indid) {
        const amt = document.getElementById('amt' + item).value;
        const statusIcon = document.getElementById('status' + item);
        const stid = '<?php echo $stid; ?>';
        
        statusIcon.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';
        
        $.ajax({
            url: "backend/crud-set-financed-ind.php",
            type: "POST",
            data: { slot: slot, sy: sy, item: item, amt: amt, stid: stid, indid: indid },
            success: function () {
                statusIcon.innerHTML = '<i class="bi bi-cloud-check-fill text-success"></i>';
            },
            error: function() {
                statusIcon.innerHTML = '<i class="bi bi-exclamation-triangle text-danger"></i>';
            }
        });
    }

    function syncNow(type, stid) {
        Swal.fire({
            title: 'Recalculate?',
            text: 'This will re-sync all finance data for this student.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#6750A4'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/check-student-finance.php",
                    data: { type: type, stid: stid },
                    success: function () {
                        Swal.fire('Synced!', 'Data updated.', 'success').then(() => location.reload());
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>