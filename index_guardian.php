<?php

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear']
    ?? $_COOKIE['query-session']
    ?? $sy;
$sy_param = '%' . $current_session . '%';

?>

<style>
    body {
        background-color: #FAF8FC;
        /* M3 Light Surface Tint */
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* 1. M3 Premium Tonal Hero Timer Banner (No Hard Shadows) */
    .hero-timer-banner {
        background: #EADDFF;
        /* M3 Tonal Purple Container */
        color: #21005D;
        padding: 28px 24px;
        border-radius: 0 0 24px 24px;
        /* Material 3 Medium Top Bar End Curve */
        border-bottom: 1px solid #D0BCFF;
    }

    .clock-time {
        font-size: 2.2rem;
        font-weight: 900;
        display: block;
        line-height: 1;
        letter-spacing: -0.5px;
    }

    .clock-date {
        font-size: 0.75rem;
        color: #4F378B;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: block;
        margin-top: 4px;
    }

    /* Period Tracker Layer */
    .period-lbl {
        font-size: 0.72rem;
        font-weight: 800;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        color: #21005D;
    }

    .m3-flat-progress-bg {
        background: rgba(103, 80, 164, 0.15);
        height: 6px;
        border-radius: 3px;
        margin-top: 6px;
        overflow: hidden;
    }

    .m3-flat-progress-fill {
        background: #6750A4;
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* 2. Flat List Core Container Layout (Card-less Structure) */
    .section-lbl {
        font-size: 0.75rem;
        font-weight: 800;
        color: #49454F;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 24px 24px 10px 24px;
        background: #FAF8FC;
    }

    .m3-flat-list-group {
        background: #FFFFFF;
        border-bottom: 1px solid #ECE6F0;
    }

    /* Condensed Student Row Entity */
    .st-flat-row-item {
        background: #FFFFFF;
        padding: 18px 24px;
        border-bottom: 1px solid #F4EFF4;
    }

    .st-flat-row-item:last-child {
        border-bottom: none;
    }

    .st-avatar-squircle {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        /* M3 Squircle Metric */
        object-fit: cover;
        border: 1px solid #EADDFF;
        margin-right: 16px;
        background: #FAF8FC;
    }

    .st-name-en {
        font-weight: 800;
        color: #1C1B1F;
        font-size: 1rem;
        line-height: 1.2;
        letter-spacing: -0.2px;
    }

    .st-meta-text {
        font-size: 0.72rem;
        color: #49454F;
        font-weight: 600;
        margin-top: 2px;
    }

    .st-meta-primary {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6750A4;
        margin-top: 1px;
    }

    /* 3. M3 Flat Action Row (Grid Navigation) */
    .action-linear-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 16px;
        padding-top: 12px;
    }

    .m3-tonal-row-btn {
        background: #F3EDF7;
        color: #4F378B;
        border-radius: 8px;
        padding: 10px 4px;
        text-align: center;
        text-decoration: none !important;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: background-color 0.15s ease, transform 0.1s ease;
        border: 1px solid #EADDFF;
    }

    .m3-tonal-row-btn:active {
        background: #EADDFF;
        transform: scale(0.97);
    }

    .m3-tonal-row-btn i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 2px;
        color: #6750A4;
    }
</style>

<main class="pb-5">

    <!-- 1. M3 PREMIUM TONAL HERO TIMER BANNER -->
    <div class="hero-timer-banner">
        <span class="clock-time" id="m3-clock">00:00:00</span>
        <span class="clock-date" id="m3-date"><?php echo date('l, d F'); ?></span>

        <span class="period-lbl"><i class="bi bi-clock-history"></i> Current Status: 3rd Period</span>
        <div class="m3-flat-progress-bg">
            <div class="m3-flat-progress-fill" id="m3-bar" style="width: 37%;"></div>
        </div>
    </div>


    <!-- 2. REGISTERED CHILDREN MODULE (LINIAR GROUP) -->
    <div class="section-lbl">Registered Children</div>

    <div class="m3-flat-list-group">
        <?php
        // ডাটা ফেচিং (Prepared Statement - Secure)
        $stmt_st = $conn->prepare("SELECT stid, stnameeng, stnameben FROM students WHERE guarmobile = ? ORDER BY stid DESC");
        $stmt_st->bind_param("s", $usrmobile);
        $stmt_st->execute();
        $res_st = $stmt_st->get_result();

        if ($res_st->num_rows > 0):
            while ($st = $res_st->fetch_assoc()):
                $stdid = $st["stid"];

                // সেশন ইনফো ফেচ করা
                $stmt_si = $conn->prepare("SELECT classname, sectionname, rollno FROM sessioninfo WHERE stid = ? AND sessionyear LIKE ? LIMIT 1");
                $stmt_si->bind_param("ss", $stdid, $sy_param);
                $stmt_si->execute();
                $res_si = $stmt_si->get_result();
                $si = $res_si->fetch_assoc();
                $stmt_si->close();
                ?>
                <!-- Flat Row Student Item (No Outlines / No Shadows) -->
                <div class="st-flat-row-item">
                    <div class="d-flex align-items-center">
                        <img src="https://eimbox.com/students/<?php echo $stdid; ?>.jpg" class="st-avatar-squircle"
                            onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="st-name-en text-truncate"><?php echo htmlspecialchars($st['stnameeng']); ?></div>
                            <div class="st-meta-text">
                                ID: <?php echo $stdid; ?> <i class="bi bi-dot"></i> Roll: <?php echo $si['rollno'] ?? 'N/A'; ?>
                            </div>
                            <div class="st-meta-primary">
                                Class: <?php echo $si['classname'] ?? 'N/A'; ?> (<?php echo $si['sectionname'] ?? 'N/A'; ?>)
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Grid inside the item -->
                    <div class="action-linear-grid">
                        <a href="javascript:void(0);" class="m3-tonal-row-btn" onclick="goToModule(<?php echo $stdid; ?>, 1)">
                            <i class="bi bi-mortarboard-fill"></i> Results
                        </a>
                        <a href="javascript:void(0);" class="m3-tonal-row-btn" onclick="goToModule(<?php echo $stdid; ?>, 2)">
                            <i class="bi bi-fingerprint"></i> Attendance
                        </a>
                        <a href="javascript:void(0);" class="m3-tonal-row-btn" onclick="goToModule(<?php echo $stdid; ?>, 3)">
                            <i class="bi bi-grid-fill"></i> More
                        </a>
                    </div>
                </div>
            <?php
            endwhile;
        else:
            echo '<div class="text-center py-5 opacity-25" style="background:#fff;"><i class="bi bi-person-x display-2 text-muted"></i><p class="fw-bold mt-2 small text-muted">No children records linked.</p></div>';
        endif;
        $stmt_st->close();
        ?>
    </div>


    <div id="blocksContainer" class="mt-1">
        <?php
        foreach ($blocks as $id => $info):
            $valid_user = $info['role'] ?? '';
            $roles = array_map('trim', explode('|', $valid_user));

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







</main>

<script>
    // নেভিগেশন ফাংশন
    function goToModule(id, type) {
        const session = '<?php echo $current_session; ?>';
        let url = '';
        if (type == 1) url = 'stguarresult.php';
        else if (type == 2) url = 'stguarattnd.php';
        else url = 'stguarmore.php';

        window.location.href = `${url}?stid=${id}&year=${session}`;
    }

    // ঘড়ি এবং টাইমার আপডেট লজিক
    function updateM3Clock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        const dateOptions = { weekday: 'long', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('en-US', dateOptions);

        document.getElementById('m3-clock').innerText = timeStr;
        document.getElementById('m3-date').innerText = dateStr;

        // প্রোগ্রেস বার অ্যানিমেশন ফিক্সড হ্যান্ডলার
        document.getElementById('m3-bar').style.width = "45%";
    }

    setInterval(updateM3Clock, 1000);
    updateM3Clock();
</script>