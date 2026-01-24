<?php
$page_title = "Individual Payment Setup";
include 'inc.php'; 
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

$frval = array('10', '11', '12', '22', '33', '44', '66', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
$frtxt = array('Oct', 'Nov', 'Dec', '2 Mo.', 'Quarter', '4 Mo.', 'Half-Yr.', 'Monthly', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep');
?>

<style>
    /* ১. হিরো সেকশন স্পেসিফিক */
    .hero-profile { padding-bottom: 30px; margin-bottom: 0; border-radius: 0 0 24px 24px; }
    
    .squircle-avatar {
        width: 64px; height: 64px;
        background: rgba(255,255,255,0.2);
        border-radius: 16px; /* M3 standard */
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: white;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }

    /* ২. ফি কার্ড ডিজাইন */
    .fee-card {
        padding: 14px; margin-bottom: 10px;
        border: 1px solid rgba(0,0,0,0.04);
        align-items: center;
    }

    .m3-amount-box {
        width: 85px; height: 44px;
        background: var(--m3-tonal-surface);
        border: 2px solid var(--m3-tonal-container);
        border-radius: 8px;
        text-align: right; padding: 0 10px;
        font-weight: 900; color: var(--m3-primary);
        font-size: 1rem;
    }
    .m3-amount-box:focus { border-color: var(--m3-primary); outline: none; background: #fff; }

    .freq-badge {
        font-size: 0.6rem; font-weight: 800;
        background: var(--m3-tonal-container);
        color: var(--m3-on-tonal-container);
        padding: 2px 8px; border-radius: 4px;
        text-transform: uppercase;
    }

    /* ৩. ফিল্টার ওভারলে */
    .filter-overlay { margin: -20px 16px 20px; position: relative; z-index: 10; }
</style>

<main>
    <div class="hero-container hero-profile">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="location.href='settings_admin.php'">
                <i class="bi bi-arrow-left"></i>
            </div>
            <?php if($stid != ''): ?>
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="syncNow('stid', '<?php echo $stid; ?>');">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <?php endif; ?>
        </div>

        <div style="display: flex; align-items: center; margin-top: 15px;">
            <div class="squircle-avatar shadow-sm">
                <i class="bi bi-person-fill-gear"></i>
            </div>
            <div style="margin-left: 15px; overflow: hidden;">
                <?php if($stid != ''): ?>
                    <div style="font-size: 1.3rem; font-weight: 900; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?php echo $stname_eng; ?>
                    </div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">
                        <?php echo "$cls2 • $sec2 | Roll: $roll2"; ?>
                    </div>
                    <div class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none; margin-top: 5px;">
                        STUDENT ID: <?php echo $stid; ?>
                    </div>
                <?php else: ?>
                    <div style="font-size: 1.3rem; font-weight: 900;">Individual Setup</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">Configure student-wise fee structures</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($stid == ''): ?>
    <div class="filter-overlay">
        <div class="m3-card shadow-sm" style="padding: 20px;">
            <div class="m3-section-title" style="margin-top: 0;"><i class="bi bi-funnel-fill me-1"></i> Selection Filter</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="m3-floating-group">
                        <select class="m3-select-floating" id="year">
                            <?php for($y=date('Y')+1; $y>=2024; $y--) echo "<option value='$y' ".($current_session==$y?'selected':'').">$y</option>"; ?>
                        </select>
                        <label class="m3-floating-label">SESSION</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="m3-floating-group">
                        <select class="m3-select-floating" id="cls">
                            <option value=""></option>
                            <?php foreach($clslist as $c) echo "<option value='".$c['areaname']."' ".($cls2==$c['areaname']?'selected':'').">".$c['areaname']."</option>"; ?>
                        </select>
                        <label class="m3-floating-label">CLASS</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="m3-floating-group">
                        <select class="m3-select-floating" id="sec">
                            <option value=""></option>
                            <?php foreach($seclist as $s) echo "<option value='".$s['subarea']."' ".($sec2==$s['subarea']?'selected':'').">".$s['subarea']."</option>"; ?>
                        </select>
                        <label class="m3-floating-label">SECTION</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="m3-floating-group">
                        <input type="number" id="roll" class="m3-input-floating" placeholder=" " value="<?php echo $roll2; ?>">
                        <label class="m3-floating-label">ROLL NO</label>
                    </div>
                </div>
            </div>
            <button class="btn-m3-submit" style="width: 100%; margin: 10px 0 0;" onclick="go();">
                <i class="bi bi-search me-1"></i> LOAD STUDENT DATA
            </button>
        </div>
    </div>
    <?php endif; ?>

    <?php if($stid != ''): ?>
    <div class="widget-grid" style="margin-top: 15px; padding-bottom: 80px;">
        <div class="px-3 d-flex justify-content-between align-items-center mb-2">
            <div class="m3-section-title" style="margin: 0;">Academic Fee Structures</div>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3" style="font-size: 0.65rem;">SESSION <?php echo $current_session; ?></span>
        </div>

        <?php foreach ($finsetup as $finitem): 
            $itemcode = $finitem['itemcode'];
            $freq_text = str_replace($frval, $frtxt, $finitem['month']);
            
            $amt = 0; $ind_id = 0;
            $ind_ind = array_search($itemcode, array_column($finsetupind, 'itemcode'));
            if ($ind_ind !== false) {
                $amt = $finsetupind[$ind_ind]['amount'];
                $ind_id = $finsetupind[$ind_ind]['id'];
            }
        ?>
            <div class="m3-list-item fee-card shadow-sm">
                <div class="icon-box c-fina" style="width: 44px; height: 44px;">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                
                <div class="item-info">
                    <div class="st-title" style="font-size: 0.95rem; color: #1C1B1F;"><?php echo $finitem['particulareng']; ?></div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="freq-badge"><?php echo $freq_text; ?></span>
                        <div class="st-desc" style="font-size: 0.8rem; font-weight: 500;"><?php echo $finitem['particularben']; ?></div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" id="amt<?php echo $itemcode; ?>" class="m3-amount-box" value="<?php echo $amt; ?>" 
                               onblur="saveInd('<?php echo $finitem['slot']; ?>', '<?php echo $current_session; ?>', '<?php echo $itemcode; ?>', <?php echo $ind_id; ?>);">
                        <div id="status<?php echo $itemcode; ?>" style="min-width: 20px;">
                            <i class="bi bi-check2-circle opacity-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="p-3">
            <button class="btn btn-outline-primary w-100" style="border-radius: 12px; font-weight: 800; border-width: 2px;" onclick="location.href='st-payment-setup-indivisual.php'">
                <i class="bi bi-people me-2"></i> CONFIGURE ANOTHER STUDENT
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
        if(!c || !s || !r) { alert("Please fill all fields"); return; }
        window.location.href = `st-payment-setup-indivisual.php?cls=${c}&sec=${s}&year=${y}&roll=${r}`;
    }

    function saveInd(slot, sy, item, indid) {
        const amt = document.getElementById('amt' + item).value;
        const statusIcon = document.getElementById('status' + item);
        const stid = '<?php echo $stid; ?>';
        
        statusIcon.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" style="width:0.8rem; height:0.8rem;"></div>';
        
        $.ajax({
            url: "backend/crud-set-financed-ind.php",
            type: "POST",
            data: { slot: slot, sy: sy, item: item, amt: amt, stid: stid, indid: indid },
            success: function (res) {
                statusIcon.innerHTML = '<i class="bi bi-cloud-check-fill text-success fs-5"></i>';
            },
            error: function() {
                statusIcon.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger"></i>';
            }
        });
    }

    function syncNow(type, stid) {
        Swal.fire({
            title: 'Recalculate Data?',
            text: 'This will re-sync all individual finance mappings for this student based on Master Setup.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Sync Now'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/check-student-finance.php",
                    data: { type: type, stid: stid },
                    success: function () {
                        Swal.fire({ title: 'Success', text: 'Financial data synchronized!', icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>