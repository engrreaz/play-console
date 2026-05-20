<?php
/**
 * Dashboard Body - Refactored for Android WebView (EIMBox)
 * M3 Standards | 8px Radius | High Data Density
 */

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. টু-ডু লিস্ট লজিক (নিভৃত রাখা হলো - আপনার লজিক ঠিক আছে)
$stmt_todo = $conn->prepare("SELECT id FROM todolist WHERE date=? AND sccode=? AND user=? AND todotype='attendance'");
$stmt_todo->bind_param("sss", $td, $sccode, $usr);
$stmt_todo->execute();
if ($stmt_todo->get_result()->num_rows == 0) {
    $ins_todo = "INSERT INTO todolist (sccode, date, user, todotype, status, creationtime, response, responsetxt) 
                 VALUES ('$sccode', '$td', '$usr', 'Attendance', 0, '$cur', 'geoattnd', 'Submit')";
    $conn->query($ins_todo);
}
$stmt_todo->close();

// ৩. কালেকশন সামারি ফেচিং
$paisi = 0;
$stmt_pr = $conn->prepare("SELECT SUM(amount) as total FROM stpr WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ?");
$sy_like = "%$current_session%";
$stmt_pr->bind_param("sss", $sy_like, $sccode, $usr);
$stmt_pr->execute();
$res_pr = $stmt_pr->get_result();
if ($row = $res_pr->fetch_assoc()) {
    $paisi = $row["total"] ?? 0;
}
$stmt_pr->close();

// ডাইনামিক গ্রিটিং
$hr = date('H');
$greet = ($hr < 12) ? "Good Morning" : (($hr < 17) ? "Good Afternoon" : "Good Evening");
?>

<style>
    /* ড্যাশবোর্ড র‍্যাপার */
    .m3-dashboard {
        padding: 12px;
    }

    /* গ্লোবাল ৮ পিক্সেল রেডিয়াস */
    .card,
    .m-card,
    .btn,
    .block-unit,
    .collapse-content {
        border-radius: 8px !important;
        border: 1px solid #f0f0f0 !important;
    }

    /* কালেকশন চিপ (M3 Success Tonal) */
    .m3-collection-hero {
        background: #E8F5E9;
        border: 1px solid #C8E6C9;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .coll-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #2E7D32;
        text-transform: uppercase;
    }

    .coll-amount {
        font-size: 1.25rem;
        font-weight: 900;
        color: #1B5E20;
        display: block;
    }

    /* ক্যাটাগরি লেবেল (Condensed) */
    .m3-lbl {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #6750A4;
        margin: 16px 0 8px 4px;
        letter-spacing: 1px;
    }

    /* গ্রিড এবং স্পেসিং */
    .block-item {
        margin-bottom: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    /* কুইক অ্যাকশন বাটন */
    .btn-m3-tonal {
        background: #F3EDF7;
        color: #6750A4;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 8px;
        border: none !important;
    }

    .btn-m3-tonal:active {
        background: #EADDFF;
        transform: scale(0.98);
    }
</style>

<div class="hero-container">
    <div class="small fw-bold opacity-75 text-uppercase mb-1" style="letter-spacing: 1px;">
        <?php echo $greeting; ?>, Sir
    </div>
    <div class="h3 fw-bold mb-0"><?php echo date('l'); ?></div>
    <div class="small opacity-90"><?php echo date('d M, Y'); ?></div>

    <div class="mt-3 d-flex gap-2">
        <span class="badge bg-white text-primary rounded-pill px-3 py-2 border-0 shadow-sm" style="font-size: 0.6rem;">
            <i class="bi bi-shield-check me-1"></i> System Active
        </span>
    </div>
</div>


<div class="m3-dashboard pb-5">

    <div class="m3-collection-hero shadow-sm">
        <div>
            <span class="coll-label">Session Collection (<?php echo $current_session; ?>)</span>
            <span class="coll-amount">৳ <?php echo number_format($paisi, 0); ?></span>
        </div>
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
            style="width: 42px; height: 42px;">
            <i class="bi bi-wallet2 text-success fs-5"></i>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <div class="block-item"><?php include 'front-page-block/schedule.php'; ?></div>
        </div>
        <div class="col-12">
            <div class="block-item"><?php include 'front-page-block/holi-ramadan.php'; ?></div>
        </div>
    </div>

    <div class="m3-lbl">Daily Operations</div>
    <div class="block-item"><?php include 'front-page-block/task-teacher.php'; ?></div>

    <?php if ($notice_block == 1): ?>
        <div class="block-item border-start border-4 border-warning bg-white shadow-sm p-1">
            <?php include 'front-page-block/notice.php'; ?>
        </div>
    <?php endif; ?>

    <div class="m3-lbl">Class Tracking</div>
    <div class="row g-2">
        <div class="col-12">
            <div class="block-item"><?php include 'front-page-block/cls-teacher-attendance.php'; ?></div>
        </div>
        <div class="col-12">
            <div class="block-item"><?php include 'front-page-block/clsteacherblock.php'; ?></div>
        </div>
    </div>



</div>

<div style="height: 65px;"></div>
<script>
    /**
     * Session Persistence Navigation
     */
    function nav(url) {
        const session = '<?php echo $current_session; ?>';
        window.location.href = url + (url.includes('?') ? '&' : '?') + 'year=' + session;
    }

    function goclsp() { nav('finclssec.php'); }
    function goclsa() { nav('finacc.php'); }
    function sublist() { nav('tools_allsubjects.php'); }
    function mypr() { nav('mypr.php'); }
    function goclsattall() { nav('attndclssec.php'); }

    function register(c, s) {
        const session = '<?php echo $current_session; ?>';
        window.location.href = `st-attnd-register.php?cls=${c}&sec=${s}&year=${session}`;
    }
</script>



<!-- ------------------------------------------------------------------------------------ -->

<style>
    /* হিরো অ্যাকশন বাটন স্টাইল */
    .hero-action-row {
        display: flex;
        justify-content: flex-end;
        /* ডান পাশে নেয়ার জন্য */
        gap: 10px;
        margin-top: -10px;
        /* হিরো ব্লকের সাথে অ্যাডজাস্ট করার জন্য */
        padding-right: 5px;
    }

    .action-icon-btn {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        /* হালকা ট্রান্সপারেন্ট সাদা */
        color: #fff;
        border-radius: 12px;
        /* M3 স্টাইল কার্ভ */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        /* গ্লাস ইফেক্ট */
    }

    .action-icon-btn:hover {
        background: #fff;
        color: var(--m3-primary);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* নটিফিকেশন ডট (লাল বিন্দু) */
    .btn-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: #FF3B30;
        border-radius: 50%;
        border: 1.5px solid #fff;
    }
</style>

<style>
    .btn-badge-count {
        position: absolute;
        top: -5px;
        right: -5px;
        color: white;
        font-size: 0.65rem;
        font-weight: 900;
        padding: 2px 6px;
        border-radius: 10px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .badge-red {
        background: #B3261E;
    }

    /* New updates - Alert */
    .badge-amber {
        background: #F9A825;
        color: #000;
    }

    /* Unexplored - Warning */
</style>



<main class="pb-0">

    <div class="hero-container">
        <div class="small fw-bold opacity-75 text-uppercase mb-1" style="letter-spacing: 1px;">
            <?php echo $greeting; ?>, Admin
        </div>
        <div class="h3 fw-bold mb-0"><?php echo date('l'); ?></div>
        <div class="small opacity-90"><?php echo date('d M, Y'); ?></div>

        <div class="mt-3 d-flex gap-2">
            <span class="badge bg-white text-warning rounded-pill px-3 py-2 border-0 shadow-sm"
                style="font-size: 1.0rem;">
                <i class="bi bi-shield-check"></i>
                <span class="vr"></span>
                <i class="bi bi-shield-fill-check"></i>
            </span>

            <div class="flex-grow-1"></div>

            <div class="hero-action-row text-right">
                <div class="action-icon-btn position-relative" title="What's New" onclick="navigateTo('whatsnew.php')">
                    <i class="bi bi-stars"></i>
                    <?php
                    if ($new_updates_count > 0):
                        // অগ্রাধিকার ১: নতুন আপডেট থাকলে লাল ব্যাজ
                        echo '<span class="btn-badge-count badge-red">' . $new_updates_count . '</span>';
                    elseif ($unexplored_count > 0):
                        // অগ্রাধিকার ২: নতুন নেই কিন্তু আন-এক্সপ্লোরড থাকলে হলুদ ব্যাজ
                        echo '<span class="btn-badge-count badge-amber">' . $unexplored_count . '</span>';
                    endif;
                    ?>
                </div>

                <div class="action-icon-btn" title="Messages">
                    <i class="bi bi-chat-dots"></i>
                </div>

                <div class="action-icon-btn" title="To-Do List">
                    <i class="bi bi-list-check"></i>
                </div>

                <div class="action-icon-btn position-relative" title="Notifications"
                    onclick="navigateTo('notification.php')">
                    <i class="bi bi-bell"></i>
                    <?php if ($unread_count > 0): ?>
                        <span class="btn-badge"></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>

    <div class="widget-grid">

        <div id="blocksContainer">
            <?php
            foreach ($blocks as $id => $info):
                $valid_user = $info['role'] ?? '';
                echo $valid_user . '/' . $userlevel . '<br>';
                $roles = explode('|', $valid_user);
                foreach($roles as $r){
                    echo '--' . trim($r) . '--<br>';
                }
                if (in_array($userlevel, $roles)) {
                    ?>
                    <div class="block-unit shadow-sm" id="block-<?php echo $id; ?>" data-id="<?php echo $id; ?>">
                        <?php
                        // ফাইলটি লোড করার আগে চেক করে নিন সেটি সঠিক কি না
                        include 'front-page-block/' . $info['link'];
                        ?>
                    </div>
                    <?php
                }
            endforeach;
            ?>
        </div>


    </div>





</main>



<script>
    // প্রোফেশনাল নেভিগেশন স্ক্রিপ্ট
    function navigateTo(url) {
        if (!url) return;
        const session = '<?php echo $sessionyear; ?>';
        const target = url.includes('?') ? `${url}&year=${session}` : `${url}?year=${session}`;
        window.location.href = target;
    }

    // আপনার বিদ্যমান রাউট গুলো এখানে হ্যান্ডেল হবে
    function goclsatt(cls, sec) {
        window.location.href = `stattnd.php?cls=${cls}&sec=${sec}&year=<?php echo $current_session; ?>`;
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ১. লোকাল স্টোরেজ থেকে ডেটা আনা
        const dashboardConfig = JSON.parse(localStorage.getItem('eimbox_dashboard_v1'));

        if (dashboardConfig) {
            const container = document.getElementById('blocksContainer');

            // ২. ভিজিবিলিটি চেক এবং মার্ক করা (Visibility == false হলে হাইড করা)
            if (dashboardConfig.visibility) {
                dashboardConfig.visibility.forEach(item => {
                    const blockElement = document.getElementById('block-' + item.id);
                    if (blockElement) {
                        if (item.visible === false) {
                            // যদি হাইড করতে চান:
                            blockElement.style.display = 'none';

                            // অথবা যদি মার্ক (Gray out) করতে চান:
                            // blockElement.style.opacity = '0.3';
                            // blockElement.style.pointerEvents = 'none';
                        }
                    }
                });
            }

            // ৩. অর্ডার অনুযায়ী সাজানো (Re-arrange)
            if (dashboardConfig.order) {
                dashboardConfig.order.forEach(id => {
                    const blockElement = document.getElementById('block-' + id);
                    if (blockElement) {
                        // appendChild করলে আগের এলিমেন্টটি অটোমেটিক নতুন সিরিয়ালে চলে যায়
                        container.appendChild(blockElement);
                    }
                });
            }
        }
    });
</script>