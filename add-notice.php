<?php
include 'inc.php'; // DB এবং সেশন লোড করবে

// ১. ডাটাবেজ থেকে ক্যাটাগরি ফেচ করা
$categories = [];
$res_cat = $conn->query("SELECT * FROM notice_category ORDER BY category ASC");
while($row = $res_cat->fetch_assoc()) { $categories[] = $row; }

// ২. নোটিশ সেভ করার লজিক এবং সাকসেস ফ্ল্যাগ
$success_status = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_notice'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $descrip = $_POST['descrip'];
    $expdate = $_POST['expdate'];
    
    $teacher = isset($_POST['teacher']) ? 1 : 0;
    $smc = isset($_POST['smc']) ? 1 : 0;
    $guardian = isset($_POST['guardian']) ? 1 : 0;
    
    $sms = isset($_POST['sms']) ? 1 : 0;
    $pushnoti = isset($_POST['pushnoti']) ? 1 : 0;
    $email = isset($_POST['email']) ? 1 : 0;
    
    $entryby = $usr; 
    $entrytime = date('Y-m-d H:i:s');

    $sql = "INSERT INTO notice (sccode, category, title, descrip, expdate, teacher, smc, guardian, sms, pushnoti, email, entryby, entrytime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssiiiiisss", $sccode, $category, $title, $descrip, $expdate, $teacher, $smc, $guardian, $sms, $pushnoti, $email, $entryby, $entrytime);
    
    if ($stmt->execute()) {
        $success_status = true;
    }
    $stmt->close();
}
?>

<style>
    /* ১. মডার্ন ফর্ম লেআউট */
    .m3-form-card {
        background: #fff; border-radius: 8px;
        padding: 24px 16px; margin: -25px 12px 20px;
        border: 1px solid #f0f0f0; box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        position: relative; z-index: 10;
    }
    
    /* আইকন এবং ফ্লোটিং লেবেলের পজিশনিং */
    .m3-floating-group { position: relative; margin-bottom: 22px; }
    
    .m3-field-icon {
        position: absolute; left: 14px; top: 14px;
        color: var(--m3-primary); font-size: 1.2rem;
        z-index: 10; pointer-events: none;
    }
    
    .m3-input-floating, .m3-select-floating {
        padding-left: 46px !important; /* আইকনের জন্য জায়গা */
    }
    
    textarea.m3-input-floating { height: 130px; padding-top: 15px !important; }
    textarea.m3-input-floating + .m3-floating-label { top: 12px; }

    /* ২. টোনাল সিলেকশন রো */
    .check-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; background: var(--m3-tonal-surface);
        border-radius: 8px; margin-bottom: 8px; border: 1px solid rgba(0,0,0,0.02);
    }
    .check-label { font-size: 0.85rem; font-weight: 700; color: #444; display: flex; align-items: center; gap: 12px; }
    .check-label i { color: var(--m3-primary); font-size: 1.1rem; }

    /* ৩. সাকসেস টোস্ট এনিমেশন */
    #successToast {
        position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%);
        background: #2E7D32; color: #fff; padding: 12px 24px;
        border-radius: 50px; font-weight: 700; font-size: 0.9rem;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 9999; visibility: hidden; opacity: 0;
        transition: visibility 0s, opacity 0.3s linear;
    }
    #successToast.show { visibility: visible; opacity: 1; }
</style>

<main>
    <div id="successToast">
        <i class="bi bi-check-circle-fill"></i> Notice Published Successfully!
    </div>

    <div class="hero-container">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="location.href='notice-board.php'">
                <i class="bi bi-arrow-left"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Publish News</div>
                <div style="font-size: 0.8rem; opacity: 0.9;">Draft an official announcement</div>
            </div>
        </div>
    </div>

    <div class="m3-form-card">
        <form method="POST" id="noticeForm">
            
            <div class="m3-floating-group">
                <i class="bi bi-tags m3-field-icon"></i>
                <select name="category" class="m3-select-floating" required>
                    <option value=""></option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['category']; ?>"><?php echo $cat['category']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="m3-floating-label">CATEGORY</label>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-chat-left-text m3-field-icon"></i>
                <input type="text" name="title" class="m3-input-floating" placeholder=" " required>
                <label class="m3-floating-label">NOTICE TITLE</label>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-card-text m3-field-icon"></i>
                <textarea name="descrip" class="m3-input-floating" placeholder=" " required></textarea>
                <label class="m3-floating-label">DETAILS</label>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-calendar-event m3-field-icon"></i>
                <input type="date" name="expdate" class="m3-input-floating" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                <label class="m3-floating-label">EXPIRY DATE</label>
            </div>

            <div class="m3-section-title" style="margin: 25px 0 12px;">Display To</div>
            <div class="check-row">
                <span class="check-label"><i class="bi bi-person-badge"></i>Teachers</span>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="teacher" checked></div>
            </div>
            <div class="check-row">
                <span class="check-label"><i class="bi bi-people"></i>Guardians</span>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="guardian" checked></div>
            </div>
            <div class="check-row">
                <span class="check-label"><i class="bi bi-shield-check"></i>SMC</span>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="smc"></div>
            </div>

            <div class="m3-section-title" style="margin: 25px 0 12px;">Notification Method</div>
            <div class="row g-2">
                <div class="col-4">
                    <div style="background:var(--m3-tonal-surface); padding:10px; border-radius:8px; text-align:center; border: 1px solid #EADDFF;">
                        <i class="bi bi-chat-dots d-block mb-1" style="color:var(--m3-primary);"></i>
                        <div style="font-size:0.6rem; font-weight:900;">SMS</div>
                        <input type="checkbox" name="sms" class="form-check-input">
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:var(--m3-tonal-surface); padding:10px; border-radius:8px; text-align:center; border: 1px solid #EADDFF;">
                        <i class="bi bi-phone-vibrate d-block mb-1" style="color:var(--m3-primary);"></i>
                        <div style="font-size:0.6rem; font-weight:900;">PUSH</div>
                        <input type="checkbox" name="pushnoti" class="form-check-input" checked>
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:var(--m3-tonal-surface); padding:10px; border-radius:8px; text-align:center; border: 1px solid #EADDFF;">
                        <i class="bi bi-envelope-at d-block mb-1" style="color:var(--m3-primary);"></i>
                        <div style="font-size:0.6rem; font-weight:900;">EMAIL</div>
                        <input type="checkbox" name="email" class="form-check-input">
                    </div>
                </div>
            </div>

            <button type="submit" name="save_notice" class="btn-m3-submit" style="margin-top: 30px; width: 100%;">
                <i class="bi bi-send-fill"></i>
                PUBLISH NOW
            </button>
        </form>
    </div>
</main>

<div style="height: 60px;"></div>

<script>
    // সাকসেস টোস্ট দেখানোর লজিক
    <?php if ($success_status): ?>
        window.onload = function() {
            var toast = document.getElementById("successToast");
            toast.classList.add("show");
            
            // ৩ সেকেন্ড পর টোস্ট হাইড করে নোটিশ বোর্ডে পাঠানো
            setTimeout(function() {
                toast.classList.remove("show");
                setTimeout(function() {
                    location.href = 'notices.php';
                }, 300);
            }, 3000);
        };
    <?php endif; ?>
</script>