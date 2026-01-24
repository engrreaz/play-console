<?php
// ১. ডাটা প্রিপারেশন (লজিক অপরিবর্তিত)
$pf_balance = $pf_balance ?? 0;
$upd_date = $upd_date ?? date('Y-m-d H:i:s');

$tp_data = [];
foreach ($datam_teacher_profile as $row) {
    if ($row['tid'] == $tid) {
        $tp_data = $row;
        break; 
    }
}
// ডাটা না থাকলে এম্পটি হ্যান্ডলিং
if(empty($tp_data)) { $tp_data = array_fill_keys(['position','slots','subjects','mobile','email','emergency','bgroup','fname','mname','spouse','previll','prepo','preps','predist','pervill','perpo','perps','perdist','jdate','fjdate','dob','religion','nid','tin','mpoindex','accno','bankname','branch','routing','accnosch','bnamesch','bbrsch','routesch','accnopf','bnamepf','bbrpf','routepf','ex_1','val_1','ex_2','val_2','ex_3','val_3','ex_4','val_4'], ''); }
?>

<style>
    /* PF Widget Specific Style */
    .pf-tonal-widget {
        background: var(--m3-tonal-container);
        border-radius: 8px; /* Strict 8px */
        padding: 16px;
        margin: 0 12px 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(103, 80, 164, 0.1);
    }
    
    .pf-icon-circle {
        width: 48px; height: 48px;
        background: #fff;
        color: var(--m3-primary);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .info-label { font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 0.88rem; font-weight: 700; color: #1C1B1F; margin-bottom: 12px; }
    
    .section-divider {
        display: flex; align-items: center; gap: 10px;
        margin: 24px 16px 12px;
    }
    .section-divider span { font-size: 0.7rem; font-weight: 900; color: var(--m3-primary); text-transform: uppercase; letter-spacing: 1px; }
    .section-divider::after { content: ''; flex: 1; height: 1px; background: var(--m3-tonal-container); }

    .m3-data-card { background: #fff; border-radius: 8px; border: 1px solid #f0f0f0; margin: 0 12px 12px; padding: 16px; }
</style>

<main>
    <div class="section-divider"><span>Position & Contact</span></div>
    <div class="m3-data-card">
        <div style="display: flex; gap: 12px; margin-bottom: 15px; background: var(--m3-tonal-surface); padding: 12px; border-radius: 8px;">
            <div class="icon-box c-inst" style="width: 40px; height: 40px;"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="fw-bold" style="font-size: 0.95rem; color: var(--m3-on-tonal-container);"><?php echo $tp_data['position']; ?></div>
                <div class="small text-muted"><?php echo $tp_data['slots'] . ' • ' . $tp_data['subjects']; ?></div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-6"><div class="info-label">Mobile</div><div class="fw-bold small"><?php echo $tp_data['mobile']; ?></div></div>
            <div class="col-6"><div class="info-label">Email</div><div class="fw-bold small text-truncate"><?php echo $tp_data['email']; ?></div></div>
        </div>
    </div>

    <div class="section-divider"><span>Financial Overview</span></div>
    <div class="pf-tonal-widget shadow-sm">
        <div class="pf-icon-circle">
            <i class="bi bi-piggy-bank-fill"></i>
        </div>
        <div class="flex-grow-1">
            <div class="info-label" style="color: var(--m3-on-tonal-container);">Provident Fund Balance</div>
            <div style="display: flex; align-items: baseline; gap: 5px;">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--m3-primary);">BDT</span>
                <span style="font-size: 1.6rem; font-weight: 900; color: var(--m3-on-tonal-container);">
                    <?php echo number_format($pf_balance, 2); ?>
                </span>
            </div>
            <div style="font-size: 0.6rem; font-weight: 700; color: #888; margin-top: 2px;">
                Updated: <?php echo date('d M, Y', strtotime($upd_date)); ?>
            </div>
        </div>
        <div class="tonal-icon-btn c-fina" style="width: 32px; height: 32px; border-radius: 8px;">
            <i class="bi bi-chevron-right"></i>
        </div>
    </div>

    <div class="section-divider"><span>Personal Details</span></div>
    <div class="m3-data-card">
        <div class="row g-3">
            <div class="col-6"><div class="info-label">Father</div><div class="info-value"><?php echo $tp_data['fname']; ?></div></div>
            <div class="col-6"><div class="info-label">Mother</div><div class="info-value"><?php echo $tp_data['mname']; ?></div></div>
            <div class="col-6"><div class="info-label">Blood Group</div><div class="info-value text-danger"><?php echo $tp_data['bgroup']; ?></div></div>
            <div class="col-6"><div class="info-label">Religion</div><div class="info-value"><?php echo $tp_data['religion']; ?></div></div>
        </div>
    </div>

    <div class="section-divider"><span>Address & Timeline</span></div>
    <div class="m3-data-card">
        <div class="info-label">Present Address</div>
        <div class="fw-bold small mb-3"><?php echo $tp_data['previll'].', '.$tp_data['preps'].', '.$tp_data['predist']; ?></div>
        
        <div class="row border-top pt-3">
            <div class="col-6 border-end">
                <div class="info-label">Joined</div>
                <div class="fw-bold small"><?php echo date("d M, Y", strtotime($tp_data['jdate'])); ?></div>
            </div>
            <div class="col-6 ps-3">
                <div class="info-label">Retirement</div>
                <div class="fw-bold small text-danger">
                    <?php 
                        $lpr = date_create($tp_data['dob']);
                        date_add($lpr, date_interval_create_from_date_string("60 years -1 day"));
                        echo date_format($lpr, "d M, Y");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="section-divider"><span>Bank Accounts</span></div>
    <div class="m3-list-item" style="margin-bottom: 8px;">
        <div class="icon-box c-acad" style="width: 36px; height: 36px; font-size: 0.9rem;"><i class="bi bi-bank"></i></div>
        <div class="item-info">
            <div class="st-title" style="font-size: 0.85rem;">MPO: <?php echo $tp_data['accno']; ?></div>
            <div class="st-desc" style="font-size: 0.7rem;"><?php echo $tp_data['bankname']; ?></div>
        </div>
    </div>
    <div class="m3-list-item" style="margin-bottom: 8px;">
        <div class="icon-box c-util" style="width: 36px; height: 36px; font-size: 0.9rem;"><i class="bi bi-wallet2"></i></div>
        <div class="item-info">
            <div class="st-title" style="font-size: 0.85rem;">PF: <?php echo $tp_data['accnopf']; ?></div>
            <div class="st-desc" style="font-size: 0.7rem;"><?php echo $tp_data['bnamepf']; ?></div>
        </div>
    </div>

</main>