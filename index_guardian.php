<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Guardian Console";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full Width Top App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Hero Clock & Period Card (8px Radius) */
    .hero-timer-card {
        background: #6750A4; color: #fff; border-radius: 8px;
        padding: 20px; margin: 12px; box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }
    .clock-time { font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; }
    .clock-date { font-size: 0.75rem; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

    /* Period Progress */
    .period-lbl { font-size: 0.7rem; font-weight: 700; margin-top: 15px; display: block; }
    .m3-progress-bg { background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; margin-top: 4px; overflow: hidden; }
    .m3-progress-bar { background: #fff; height: 100%; width: 0%; transition: 0.5s; }

    /* Condensed Student Card (8px Radius) */
    .st-item-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 10px; border: 1px solid #eee;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .st-avatar { 
        width: 56px; height: 56px; border-radius: 8px; /* ৮ পিক্সেল */
        object-fit: cover; border: 2px solid #F3EDF7; margin-right: 12px;
    }

    .st-name-en { font-weight: 800; color: #1C1B1F; font-size: 0.95rem; line-height: 1.2; }
    .st-meta { font-size: 0.7rem; color: #49454F; font-weight: 600; margin-top: 2px; }

    /* M3 Action Row */
    .action-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
        margin-top: 12px; padding-top: 12px; border-top: 1px solid #F7F2FA;
    }
    .m3-tonal-btn {
        background: #F3EDF7; color: #6750A4; border-radius: 8px;
        padding: 8px; text-align: center; text-decoration: none !important;
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        transition: 0.2s;
    }
    .m3-tonal-btn:active { background: #EADDFF; transform: scale(0.95); }
    .m3-tonal-btn i { font-size: 1.2rem; display: block; margin-bottom: 4px; }

    .session-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
        <i class="bi bi-person-heart"></i>
    </div>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-badge"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5">
    <div class="hero-timer-card shadow-sm">
        <span class="clock-time" id="m3-clock">00:00:00</span>
        <span class="clock-date" id="m3-date"><?php echo date('l, d F'); ?></span>
        
        <span class="period-lbl"><i class="bi bi-clock-history me-1"></i> CURRENT STATUS: 3RD PERIOD</span>
        <div class="m3-progress-bg">
            <div class="m3-progress-bar" id="m3-bar" style="width: 37%;"></div>
        </div>
    </div>

    <?php include 'notice.php'; ?>

    <div class="px-3 mb-2 mt-4 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Registered Children</div>

    <div class="list-container">
        <?php 
        // ২. ডাটা ফেচিং (Prepared Statement - Secure)
        $stmt_st = $conn->prepare("SELECT stid, stnameeng, stnameben FROM students WHERE guarmobile = ? ORDER BY stid DESC");
        $stmt_st->bind_param("s", $usrmobile);
        $stmt_st->execute();
        $res_st = $stmt_st->get_result();

        if ($res_st->num_rows > 0):
            while($st = $res_st->fetch_assoc()):
                $stdid = $st["stid"];
                
                // সেশন ইনফো ফেচ করা
                $stmt_si = $conn->prepare("SELECT classname, sectionname, rollno FROM sessioninfo WHERE stid = ? AND sessionyear LIKE ? LIMIT 1");
                $stmt_si->bind_param("ss", $stdid, $sy_param);
                $stmt_si->execute();
                $res_si = $stmt_si->get_result();
                $si = $res_si->fetch_assoc();
                $stmt_si->close();
        ?>
            <div class="st-item-card shadow-sm">
                <div class="d-flex align-items-center">
                    <img src="https://eimbox.com/students/<?php echo $stdid; ?>.jpg" class="st-avatar shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="st-name-en text-truncate"><?php echo $st['stnameeng']; ?></div>
                        <div class="st-meta">
                            ID: <?php echo $stdid; ?> <i class="bi bi-dot"></i> Roll: <?php echo $si['rollno'] ?? 'N/A'; ?>
                        </div>
                        <div class="st-meta text-primary">
                            Class: <?php echo $si['classname'] ?? 'N/A'; ?> (<?php echo $si['sectionname'] ?? 'N/A'; ?>)
                        </div>
                    </div>
                </div>

                <div class="action-grid">
                    <a href="javascript:void(0);" class="m3-tonal-btn" onclick="goToModule(<?php echo $stdid; ?>, 1)">
                        <i class="bi bi-mortarboard"></i> Results
                    </a>
                    <a href="javascript:void(0);" class="m3-tonal-btn" onclick="goToModule(<?php echo $stdid; ?>, 2)">
                        <i class="bi bi-fingerprint"></i> Attendance
                    </a>
                    <a href="javascript:void(0);" class="m3-tonal-btn" onclick="goToModule(<?php echo $stdid; ?>, 3)">
                        <i class="bi bi-three-dots"></i> More
                    </a>
                </div>
            </div>
        <?php 
            endwhile;
        else:
            echo '<div class="text-center py-5 opacity-25"><i class="bi bi-person-x display-1"></i><p class="fw-bold mt-2">No children found.</p></div>';
        endif; $stmt_st->close();
        ?>
    </div>
</main>

<div style="height: 75px;"></div> <script>
    // নেভিগেশন ফাংশন
    function goToModule(id, type){
        const session = '<?php echo $current_session; ?>';
        let url = '';
        if(type == 1) url = 'stguarresult.php';
        else if(type == 2) url = 'stguarattnd.php';
        else url = 'stguarmore.php';
        
        window.location.href = `${url}?stid=${id}&year=${session}`;
    }

    // ঘড়ি এবং টাইমার আপডেট লজিক
    function updateM3Clock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        const dateOptions = { weekday: 'long', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('en-US', dateOptions);
        
        document.getElementById('m3-clock').innerText = timeStr;
        document.getElementById('m3-date').innerText = dateStr;

        // পিরিয়ড বার সিমুলেশন (আপনার আগের লজিক অনুযায়ী ফিক্সড ভ্যালু রাখা হয়েছে)
        // প্রকৃত ডাটা থাকলে এখানে ক্যালকুলেশন যোগ করা যাবে
        document.getElementById('m3-bar').style.width = "45%";
    }

    setInterval(updateM3Clock, 1000);
    updateM3Clock();
</script>

<?php include 'footer.php'; ?>