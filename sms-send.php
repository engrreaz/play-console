<?php
$page_title = 'SMS Send';
include 'inc.php';

// স্টেপ কন্ট্রোল লজিক
$pos = isset($_GET['pos']) ? (int)$_GET['pos'] : 0;
if ($pos > 5 || $pos < 0) $pos = 0;

$cls = isset($_GET['cls']) ? $_GET['cls'] : '';
?>

<style>
    :root {
        --m3-surface: #F7F9FF;
        --m3-primary: #0061A4;
        --m3-primary-container: #D1E4FF;
        --m3-on-primary-container: #001D36;
        --m3-secondary-container: #E1E2EC;
        --m3-on-secondary-container: #191C20;
        --m3-surface-variant: #E0E2EC;
        --m3-outline: #74777F;
    }

    body { background-color: var(--m3-surface); font-family: 'Roboto', sans-serif; margin: 0; }
    .m3-main { padding: 16px; max-width: 500px; margin: 0 auto; }

    /* Stepper Header */
    .stepper-card {
        background-color: var(--m3-primary-container);
        border-radius: 28px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }

    .step-icons {
        display: flex;
        justify-content: space-around;
        margin-top: 15px;
    }

    .step-icons i {
        font-size: 20px;
        color: var(--m3-on-primary-container);
        opacity: 0.3;
        transition: 0.3s;
    }

    .step-icons i.active { opacity: 1; transform: scale(1.2); }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid var(--m3-surface-variant);
        min-height: 300px;
    }

    .page-title { font-size: 22px; font-weight: 500; margin-bottom: 8px; color: var(--m3-primary); }
    .instruction-text { font-size: 14px; color: var(--m3-on-surface-variant); margin-bottom: 20px; line-height: 1.6; }

    /* Form Elements */
    .m3-field { margin-bottom: 20px; }
    .m3-label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; margin-left: 4px; color: var(--m3-primary); }
    .m3-input, .m3-select, .m3-textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--m3-outline);
        background: transparent;
        font-size: 16px;
        box-sizing: border-box;
    }

    /* Review Grid */
    .review-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 15px; }
    .review-item { background: var(--m3-secondary-container); padding: 12px; border-radius: 12px; }
    .review-val { font-size: 16px; font-weight: 700; }
    .review-lbl { font-size: 11px; opacity: 0.8; }

    /* Bottom Navigation */
    .nav-bar {
        position: fixed;
        bottom: 80px;
        left: 0;
        right: 0;
        background: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }

    .btn-m3 {
        padding: 12px 24px;
        border-radius: 100px;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-prev { background: var(--m3-secondary-container); color: var(--m3-on-secondary-container); }
    .btn-next { background: var(--m3-primary); color: white; }
    .btn-next:disabled { opacity: 0.5; }
</style>

<main class="m3-main">
    <div class="stepper-card">
        <div style="font-size: 14px; font-weight: 500;">Step <?php echo $pos + 1; ?> of 6</div>
        <div class="step-icons">
            <i class="bi bi-chat-fill <?php if($pos==0) echo 'active'; ?>"></i>
            <i class="bi bi-megaphone-fill <?php if($pos==1) echo 'active'; ?>"></i>
            <i class="bi bi-chat-right-text-fill <?php if($pos==2) echo 'active'; ?>"></i>
            <i class="bi bi-people-fill <?php if($pos==3) echo 'active'; ?>"></i>
            <i class="bi bi-send-fill <?php if($pos==4) echo 'active'; ?>"></i>
            <i class="bi bi-check-circle-fill <?php if($pos==5) echo 'active'; ?>"></i>
        </div>
    </div>

    <div class="content-card">
        <div id="step-0" style="display: <?php echo $pos==0 ? 'block':'none'; ?>;">
            <div class="page-title">Message Center</div>
            <p class="instruction-text">To send Message to you audiance like teacher, students, guardians, SMC/governing boby's member press Next</p>
        </div>

        <div id="step-1" style="display: <?php echo $pos==1 ? 'block':'none'; ?>;">
            <div class="page-title">Campaign</div>
            <div class="m3-field">
                <label class="m3-label">Campaign Title</label>
                <input type="text" id="camp_name" class="m3-input" placeholder="e.g. Exam Result 2026" onkeyup="store_data(1)">
            </div>
        </div>

        <div id="step-2" style="display: <?php echo $pos==2 ? 'block':'none'; ?>;">
            <div class="page-title">Message</div>
            <div class="m3-field">
                <label class="m3-label">Compose Message</label>
                <textarea id="sms_text" class="m3-textarea" rows="5" onkeyup="count_len(); store_data(2);"></textarea>
                <div style="display:flex; gap:15px; margin-top:8px; font-size:12px; color:var(--m3-primary);">
                    <span>Chars: <b id="count_len">0</b></span>
                    <span>SMS: <b id="count_qnt">0</b></span>
                </div>
            </div>
            <button class="btn-m3 btn-prev" style="width:100%; justify-content:center;" onclick="$('#var_list').toggle()">
                <i class="bi bi-info-circle"></i> Variables List
            </button>
            <div id="var_list" style="display:none; font-size:12px; margin-top:10px;" class="review-item">
                [STNAME_ENG], [STID], [MOBILE_NUMBER], [DUES]
            </div>
        </div>

        <div id="step-3" style="display: <?php echo $pos==3 ? 'block':'none'; ?>;">
            <div class="page-title">Audience</div>
            <div class="m3-field">
                <label class="m3-label">Category</label>
                <select id="param1" class="m3-select" onchange="store_data(3)">
                    <option value="">Select</option>
                    <option value="Student">Student</option>
                </select>
            </div>
            <div class="m3-field">
                <label class="m3-label">Filter Level 1</label>
                <select id="param_2" class="m3-select" onchange="store_data(4)">
                    <option value="">Select Class</option>
                    <?php 
                    $sql0 = "SELECT areaname FROM areas WHERE sccode = '$sccode' GROUP BY areaname";
                    $res = $conn->query($sql0);
                    while($r = $res->fetch_assoc()) echo "<option value='".$r['areaname']."'>".$r['areaname']."</option>";
                    ?>
                </select>
            </div>
            <div class="m3-field" id="sec_name">
                </div>
        </div>

        <div id="step-4" style="display: <?php echo $pos==4 ? 'block':'none'; ?>;">
            <div class="page-title">Review</div>
            <div class="review-grid">
                <div class="review-item"><div class="review-val" id="counta">-</div><div class="review-lbl">SMS Count</div></div>
                <div class="review-item"><div class="review-val" id="audiencea">-</div><div class="review-lbl">Audience</div></div>
                <div class="review-item"><div class="review-val" id="totala">-</div><div class="review-lbl">Total SMS</div></div>
                <div class="review-item"><div class="review-val" style="color:red;" id="costa">-</div><div class="review-lbl">Cost</div></div>
            </div>
            <div class="review-item" style="margin-top:12px;">
                <div class="review-lbl">Campaign ID</div>
                <div class="review-val" id="uid" style="font-size:12px;">-</div>
            </div>
            <div id="fetch-data"></div>
        </div>

        <div id="step-5" style="display: <?php echo $pos==5 ? 'block':'none'; ?>; text-align:center;">
            <i class="bi bi-check-circle-fill" style="font-size:60px; color:green;"></i>
            <div class="page-title">Process Started</div>
            <div id="fetch-data-final" class="instruction-text"></div>
        </div>
    </div>
</main>

<div class="nav-bar">
    <button class="btn-m3 btn-prev" onclick="prev(<?php echo $pos; ?>)">
        <i class="bi bi-arrow-left"></i> Back
    </button>
    <?php if($pos < 4): ?>
    <button class="btn-m3 btn-next" onclick="next(<?php echo $pos; ?>)">
        Next <i class="bi bi-arrow-right"></i>
    </button>
    <?php elseif($pos == 4): ?>
    <button class="btn-m3 btn-next" style="background: #198754;" onclick="send_bundle_sms(sessionStorage.getItem('uid'))">
        Send SMS <i class="bi bi-send-check"></i>
    </button>
    <?php endif; ?>
</div>

<input type="hidden" id="param3">

<?php include 'footer.php'; ?>
<script>
    // Navigation Functions
    function prev(id) { if(id > 0) window.location.href = 'sms-send.php?pos=' + (id-1); }
    function next(id) { window.location.href = 'sms-send.php?pos=' + (id+1); }

    // Character Counter
    function count_len() {
        let len = document.getElementById("sms_text").value.length;
        document.getElementById("count_len").innerHTML = len;
        document.getElementById("count_qnt").innerHTML = Math.ceil(len / 160);
    }

    // Data Storage (Session Based)
    function store_data(type) {
        if(type == 1) sessionStorage.setItem("camp-name", document.getElementById("camp_name").value);
        if(type == 2) sessionStorage.setItem("sms-text", document.getElementById("sms_text").value);
        if(type == 3) sessionStorage.setItem("param-1", document.getElementById("param1").value);
        if(type == 4) {
            let val = document.getElementById("param_2").value;
            sessionStorage.setItem("param-2", val);
            window.location.href = 'sms-send.php?pos=3&cls=' + val;
        }
    }

    // Initialization
    window.onload = function() {
        document.getElementById("camp_name").value = sessionStorage.getItem("camp-name") || "";
        document.getElementById("sms_text").value = sessionStorage.getItem("sms-text") || "";
        document.getElementById("param1").value = sessionStorage.getItem("param-1") || "";
        document.getElementById("param_2").value = sessionStorage.getItem("param-2") || "";
        
        let uid = sessionStorage.getItem("uid");
        if(!uid) {
            uid = 'CAM-' + Math.floor(Math.random() * 1000000);
            sessionStorage.setItem("uid", uid);
        }
        document.getElementById("uid").innerHTML = uid;
        
        if(<?php echo $pos; ?> == 4) call_back_data(uid);
        if(<?php echo $pos; ?> == 5) send_bundle_sms(uid);
        count_len();
    };
    
    // AJAX Functions (ইউজারের অরিজিনাল লজিক অনুযায়ী কল হবে)
    function call_back_data(uid) {
        // আপনার অরিজিনাল AJAX লজিক এখানে থাকবে
    }
    
    function send_bundle_sms(uid) {
        // আপনার অরিজিনাল Send লজিক এখানে থাকবে
    }
</script>