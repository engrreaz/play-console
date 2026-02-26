<?php
// ১. আপনার দেওয়া লজিক এবং ডাটাবেস অপরিবর্তিত রাখা হলো
$ramadan_schedule = [
    "2026-02-19" => ["day" => 1, "sehar" => "05:13:00", "iftar" => "17:56:00", "dhuhr" => "12:12 PM", "asr" => "03:29 PM", "isha" => "07:12 PM"],
    "2026-02-20" => ["day" => 2, "sehar" => "05:12:00", "iftar" => "17:57:00", "dhuhr" => "12:12 PM", "asr" => "03:29 PM", "isha" => "07:12 PM"],
    "2026-02-21" => ["day" => 3, "sehar" => "05:12:00", "iftar" => "17:57:00", "dhuhr" => "12:12 PM", "asr" => "03:29 PM", "isha" => "07:13 PM"],
    "2026-02-22" => ["day" => 4, "sehar" => "05:11:00", "iftar" => "17:58:00", "dhuhr" => "12:12 PM", "asr" => "03:29 PM", "isha" => "07:13 PM"],
    "2026-02-23" => ["day" => 5, "sehar" => "05:10:00", "iftar" => "17:58:00", "dhuhr" => "12:12 PM", "asr" => "03:30 PM", "isha" => "07:14 PM"],
    "2026-02-24" => ["day" => 6, "sehar" => "05:09:00", "iftar" => "17:59:00", "dhuhr" => "12:12 PM", "asr" => "03:30 PM", "isha" => "07:14 PM"],
    "2026-02-25" => ["day" => 7, "sehar" => "05:09:00", "iftar" => "17:59:00", "dhuhr" => "12:11 PM", "asr" => "03:30 PM", "isha" => "07:15 PM"],
    "2026-02-26" => ["day" => 8, "sehar" => "05:08:00", "iftar" => "18:00:00", "dhuhr" => "12:11 PM", "asr" => "03:30 PM", "isha" => "07:15 PM"],
    "2026-02-27" => ["day" => 9, "sehar" => "05:07:00", "iftar" => "18:00:00", "dhuhr" => "12:11 PM", "asr" => "03:31 PM", "isha" => "07:15 PM"],
    "2026-02-28" => ["day" => 10, "sehar" => "05:06:00", "iftar" => "18:01:00", "dhuhr" => "12:11 PM", "asr" => "03:31 PM", "isha" => "07:16 PM"],
    "2026-03-01" => ["day" => 11, "sehar" => "05:05:00", "iftar" => "18:01:00", "dhuhr" => "12:11 PM", "asr" => "03:31 PM", "isha" => "07:16 PM"],
    "2026-03-02" => ["day" => 12, "sehar" => "05:05:00", "iftar" => "18:02:00", "dhuhr" => "12:11 PM", "asr" => "03:31 PM", "isha" => "07:17 PM"],
    "2026-03-03" => ["day" => 13, "sehar" => "05:04:00", "iftar" => "18:02:00", "dhuhr" => "12:10 PM", "asr" => "03:31 PM", "isha" => "07:17 PM"],
    "2026-03-04" => ["day" => 14, "sehar" => "05:03:00", "iftar" => "18:03:00", "dhuhr" => "12:10 PM", "asr" => "03:31 PM", "isha" => "07:18 PM"],
    "2026-03-05" => ["day" => 15, "sehar" => "05:02:00", "iftar" => "18:03:00", "dhuhr" => "12:10 PM", "asr" => "03:31 PM", "isha" => "07:18 PM"],
    "2026-03-06" => ["day" => 16, "sehar" => "05:01:00", "iftar" => "18:03:00", "dhuhr" => "12:10 PM", "asr" => "03:31 PM", "isha" => "07:19 PM"],
    "2026-03-07" => ["day" => 17, "sehar" => "05:00:00", "iftar" => "18:04:00", "dhuhr" => "12:09 PM", "asr" => "03:31 PM", "isha" => "07:19 PM"],
    "2026-03-08" => ["day" => 18, "sehar" => "04:59:00", "iftar" => "18:04:00", "dhuhr" => "12:09 PM", "asr" => "03:31 PM", "isha" => "07:19 PM"],
    "2026-03-09" => ["day" => 19, "sehar" => "04:58:00", "iftar" => "18:05:00", "dhuhr" => "12:09 PM", "asr" => "03:32 PM", "isha" => "07:20 PM"],
    "2026-03-10" => ["day" => 20, "sehar" => "04:57:00", "iftar" => "18:05:00", "dhuhr" => "12:09 PM", "asr" => "03:32 PM", "isha" => "07:20 PM"],
    "2026-03-11" => ["day" => 21, "sehar" => "04:57:00", "iftar" => "18:06:00", "dhuhr" => "12:08 PM", "asr" => "03:32 PM", "isha" => "07:21 PM"],
    "2026-03-12" => ["day" => 22, "sehar" => "04:56:00", "iftar" => "18:06:00", "dhuhr" => "12:08 PM", "asr" => "03:32 PM", "isha" => "07:21 PM"],
    "2026-03-13" => ["day" => 23, "sehar" => "04:55:00", "iftar" => "18:07:00", "dhuhr" => "12:08 PM", "asr" => "03:32 PM", "isha" => "07:22 PM"],
    "2026-03-14" => ["day" => 24, "sehar" => "04:54:00", "iftar" => "18:07:00", "dhuhr" => "12:08 PM", "asr" => "03:32 PM", "isha" => "07:22 PM"],
    "2026-03-15" => ["day" => 25, "sehar" => "04:53:00", "iftar" => "18:07:00", "dhuhr" => "12:07 PM", "asr" => "03:31 PM", "isha" => "07:22 PM"],
    "2026-03-16" => ["day" => 26, "sehar" => "04:52:00", "iftar" => "18:08:00", "dhuhr" => "12:07 PM", "asr" => "03:31 PM", "isha" => "07:23 PM"],
    "2026-03-17" => ["day" => 27, "sehar" => "04:51:00", "iftar" => "18:08:00", "dhuhr" => "12:07 PM", "asr" => "03:31 PM", "isha" => "07:23 PM"],
    "2026-03-18" => ["day" => 28, "sehar" => "04:50:00", "iftar" => "18:09:00", "dhuhr" => "12:06 PM", "asr" => "03:31 PM", "isha" => "07:24 PM"],
    "2026-03-19" => ["day" => 29, "sehar" => "04:49:00", "iftar" => "18:09:00", "dhuhr" => "12:06 PM", "asr" => "03:31 PM", "isha" => "07:24 PM"],
    "2026-03-20" => ["day" => 30, "sehar" => "04:48:00", "iftar" => "18:10:00", "dhuhr" => "12:06 PM", "asr" => "03:31 PM", "isha" => "07:25 PM"]
];

$today_key = date('Y-m-d');
$tomorrow_key = date('Y-m-d', strtotime('+1 day'));

$today_data = $ramadan_schedule[$today_key] ?? null;
$tomorrow_data = $ramadan_schedule[$tomorrow_key] ?? null;

if ($today_data) {
    $ram_day = $today_data['day'];
    $s_time = strtotime($today_key . ' ' . $today_data['sehar']);
    $i_time = strtotime($today_key . ' ' . $today_data['iftar']);
    $now = time();

    if ($now < $s_time) {
        $next_name = "Sehri";
        $next_time = date('Y-m-d H:i:s', $s_time);
        $last_time = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($today_key . ' ' . ($ramadan_schedule[date('Y-m-d', strtotime('-1 day'))]['iftar'] ?? '18:00:00'))));
    } elseif ($now < $i_time) {
        $next_name = "Iftar";
        $next_time = date('Y-m-d H:i:s', $i_time);
        $last_time = date('Y-m-d H:i:s', $s_time);
    } else {
        $next_name = "Sehri";
        $next_time = $tomorrow_data ? $tomorrow_key . ' ' . $tomorrow_data['sehar'] : date('Y-m-d H:i:s', $s_time + 86400);
        $last_time = date('Y-m-d H:i:s', $i_time);
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --rd-primary-dark: #064E3B; /* Deep Emerald */
        --rd-accent: #10B981; /* Bright Emerald */
        --rd-bg-soft: #F0FDF4; /* Very Light Green */
        --rd-text-main: #1F2937;
        --rd-card-bg: #FFFFFF;
    }

    .m3-ramadan-card {
        background: var(--rd-card-bg);
        border-radius: 16px; /* Tonal standard large radius */
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        font-family: 'Inter', sans-serif;
        max-width: 400px;
        margin: auto;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    .m3-ramadan-header {
        background: var(--rd-primary-dark);
        color: #ECFDF5;
        padding: 24px;
        position: relative;
    }

    .m3-ramadan-header::after {
        content: "";
        position: absolute;
        bottom: -10px; left: 0; right: 0;
        height: 20px;
        background: var(--rd-card-bg);
        border-radius: 50% 50% 0 0;
    }

    .countdown-box {
        padding: 24px;
        padding-top: 10px;
    }

    .status-chip {
        background: #D1FAE5;
        color: #065F46;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .countdown-val {
        font-size: 2rem;
        font-weight: 900;
        color: var(--rd-primary-dark);
        line-height: 1;
        letter-spacing: -2px;
        margin: 8px 0;
    }

    .target-time {
        font-size: 0.85rem;
        color: #6B7280;
    }

    .day-indicator {
        width: 70px;
        height: 70px;
        border-radius: 22px; /* Squircle style indicator */
        background: var(--rd-bg-soft);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px solid #D1FAE5;
    }

    .day-indicator b {
        font-size: 1.5rem;
        color: var(--rd-primary-dark);
        line-height: 1;
    }

    .day-indicator span {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 800;
        color: var(--rd-accent);
    }

    /* Modern Progress Bar */
    .m3-progress-container {
        margin: 20px 0;
    }

    .m3-progress-bg {
        height: 10px;
        background: #F3F4F6;
        border-radius: 20px;
        overflow: hidden;
    }

    #waiting-line {
        height: 100%;
        background: linear-gradient(90deg, var(--rd-accent), #34D399);
        width: 0%;
        border-radius: 20px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Prayer Grid - Tonal Minimalist */
    .prayer-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-top: 15px;
    }

    .prayer-item {
        background: #F9FAFB;
        padding: 12px 4px;
        border-radius: 18px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .prayer-active {
        background: var(--rd-primary-dark);
        color: white;
        transform: translateY(-2px);
    }

    .p-label {
        font-size: 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
        opacity: 0.7;
    }

    .prayer-active .p-label { opacity: 1; color: #A7F3D0; }

    .p-time {
        font-size: 0.8rem;
        font-weight: 700;
        display: block;
    }
</style>

<div class="m3-ramadan-card">
    <div class="m3-ramadan-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 600; opacity: 0.9;">Ramadan 1447 AH</div>
                <div style="font-size: 1.4rem; font-weight: 900; letter-spacing: -0.5px;">Ramadan Tracker</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.85rem; font-weight: 800;"><?= date('l') ?></div>
                <div style="font-size: 0.75rem; opacity: 0.8;"><?= date('d M, Y') ?></div>
            </div>
        </div>
    </div>

    <div class="countdown-box">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div class="status-chip">
                    <span style="display:inline-block; width:6px; height:6px; background:#059669; border-radius:50%; margin-right:8px;"></span>
                    Time until <?= $next_name ?>
                </div>
                <div id="wait-time" class="countdown-val">00:00:00</div>
                <div class="target-time">Starts at <b><?= date('h:i A', strtotime($next_time)) ?></b></div>
            </div>
            
            <div class="day-indicator">
                <b><?= $ram_day ?></b>
                <span>Day</span>
            </div>
        </div>

        <div class="m3-progress-container">
            <div class="m3-progress-bg">
                <div id="waiting-line"></div>
            </div>
        </div>

        <div class="prayer-grid">
            <?php
            $prayers = [
                "Sehar" => $today_data['sehar'],
                "Dhuhr" => $today_data['dhuhr'],
                "Asr" => $today_data['asr'],
                "Iftar" => $today_data['iftar'],
                "Isha" => $today_data['isha']
            ];
            foreach ($prayers as $n => $t):
                $is_target = ($n == $next_name);
                ?>
                <div class="prayer-item <?= $is_target ? 'prayer-active' : '' ?>">
                    <span class="p-label"><?= $n ?></span>
                    <span class="p-time"><?= date('h:i', strtotime($t)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="tracker-config" data-last="<?= $last_time ?>" data-next="<?= $next_time ?>" style="display:none;"></div>

<script>
    function updateRamadanTracker() {
        const config = document.getElementById('tracker-config');
        const nextTime = new Date(config.dataset.next).getTime();
        const lastTime = new Date(config.dataset.last).getTime();
        const now = new Date().getTime();

        const diff = nextTime - now;
        if (diff > 0) {
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            document.getElementById('wait-time').innerText =
                `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;

            const total = nextTime - lastTime;
            const elapsed = now - lastTime;
            const pct = Math.min(Math.max((elapsed / total) * 100, 0), 100);
            document.getElementById('waiting-line').style.width = pct + "%";
        } else {
            location.reload();
        }
    }
    setInterval(updateRamadanTracker, 1000);
    updateRamadanTracker();
</script>