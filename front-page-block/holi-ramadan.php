<?php
// ১. আপনার দেওয়া টেবিল অনুযায়ী সঠিক ডাটাবেস (১৯ ফেব্রুয়ারি = ১ম রোজা)
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

    // ফেজ ডিটেকশন
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

<style>
    :root {
        --rd-primary: #1B5E20;
        --rd-light: #E8F5E9;
        --m3-tonal: #EADDFF;
    }

    .m3-ramadan-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E0E0E0;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    }

    .m3-ramadan-header {
        background: linear-gradient(135deg, #839b85 0%, #2E7D32 100%);
        color: white;
        padding: 18px 22px;
    }

    .countdown-box {
        padding: 22px;
    }

    .countdown-val {
        font-size: 2.4rem;
        font-weight: 900;
        color: var(--rd-primary);
        line-height: 1;
        letter-spacing: -1px;
    }

    .status-chip {
        background: var(--rd-light);
        color: var(--rd-primary);
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 8px;
    }

    .day-indicator {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: conic-gradient(#43A047
                <?= ($ram_day / 30) * 360 ?>
                deg, #F1F0F4 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .day-indicator-inner {
        width: 54px;
        height: 54px;
        background: white;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .m3-progress {
        height: 8px;
        background: #F1F0F4;
        border-radius: 10px;
        margin: 15px 0;
        overflow: hidden;
    }

    #waiting-line {
        height: 100%;
        background: #43A047;
        width: 0%;
        transition: width 1s linear;
    }

    .prayer-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
        margin-top: 10px;
    }

    .prayer-item {
        background: #F9F9F9;
        padding: 8px 2px;
        border-radius: 14px;
        text-align: center;
        border: 1px solid #F0F0F0;
    }

    .prayer-active {
        background: var(--rd-primary) !important;
        color: white !important;
        border: none;
        box-shadow: 0 4px 10px rgba(27, 94, 32, 0.2);
    }
</style>

<div class="m3-ramadan-card shadow-sm">
    <div class="m3-ramadan-header d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold fs-5">Ramadan Kareem</div>
        </div>
        <div class="text-end">
            <div class="small fw-bold"><?= date('l') ?></div>
            <div class="small opacity-75"><?= date('d M, Y') ?></div>
        </div>
    </div>

    <div class="countdown-box">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="status-chip">Time left for <b class="ms-1"><?= $next_name ?></b></span>
                <div id="wait-time" class="countdown-val">00:00:00</div>
                <div class="text-muted small mt-1">Target: <b
                        class="text-dark"><?= date('h:i A', strtotime($next_time)) ?></b></div>
            </div>
            <div class="day-indicator shadow-sm">
                <div class="day-indicator-inner">
                    <span class="fw-black text-success"
                        style="font-size: 1.1rem; line-height: 1;"><?= $ram_day ?></span>
                    <span class="fw-bold text-muted" style="font-size: 0.5rem; text-transform: uppercase;">Day</span>
                </div>
            </div>
        </div>

        <div class="m3-progress">
            <div id="waiting-line"></div>
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
                    <div style="font-size: 0.55rem; font-weight: 800; text-transform: uppercase; opacity: 0.8;"><?= $n ?>
                    </div>
                    <div style="font-size: 0.7rem; font-weight: 800;"><?= date('h:i', strtotime($t)) ?></div>
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