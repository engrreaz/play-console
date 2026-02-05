<?php
$page_title = "Attendance Analytics";
include 'inc.php'; 

// ১. প্যারামিটার হ্যান্ডলিং
$current_session = $_GET['year'] ?? $_GET['session'] ?? $_COOKIE['query-session'] ?? $sy;
$sy_param = "%" . $current_session . "%";
$stid = $_GET['stid'] ?? 0;

// ২. স্টুডেন্ট ইনফো ফেচিং
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno FROM students s JOIN sessioninfo si ON s.stid = si.stid WHERE s.stid = ? AND si.sessionyear LIKE ? LIMIT 1");
$stmt->bind_param("ss", $stid, $sy_param);
$stmt->execute();
$std_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$std_data) die("<div class='p-5 text-center'>Student not found or session mismatch.</div>");

// ৩. উপস্থিতির ডাটা এবং মাসিক গ্রুপিং লজিক
$grouped_att = [];
$present_count = $absent_count = $bunk_count = 0;

$stmt_att = $conn->prepare("SELECT adate, yn, bunk FROM stattnd WHERE stid = ? AND sessionyear LIKE ? ORDER BY adate DESC");
$stmt_att->bind_param("ss", $stid, $sy_param);
$stmt_att->execute();
$res_att = $stmt_att->get_result();

while ($row = $res_att->fetch_assoc()) {
    $month_key = date('F Y', strtotime($row['adate']));
    $grouped_att[$month_key][] = $row;

    if ($row['yn'] == 1) {
        $present_count++;
        if ($row['bunk'] == '1') $bunk_count++;
    } else { $absent_count++; }
}
$stmt_att->close();

// ৪. গ্রাফের ডাটা প্রিপারেশন (Sorting chronologically)
$graph_labels = [];
$graph_values = [];
foreach (array_reverse($grouped_att) as $m => $recs) {
    $graph_labels[] = date('M', strtotime($m));
    $m_p = 0;
    foreach($recs as $r) if($r['yn'] == 1) $m_p++;
    $graph_values[] = $m_p;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

    /* Profile Card */
    .m3-profile-card {
        background: white; border-radius: 0 0 24px 24px;
        padding: 30px 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .avatar-box {
        width: 100px; height: 120px; border-radius: 12px;
        border: 4px solid var(--m3-tonal); margin: 0 auto 15px;
        overflow: hidden; box-shadow: 0 4px 10px rgba(103, 80, 164, 0.1);
    }
    .avatar-box img { width: 100%; height: 100%; object-fit: cover; }

    /* Stats Chips */
    .stat-container { display: flex; gap: 10px; padding: 15px; margin-top: -25px; position: relative; z-index: 10; }
    .stat-pill {
        flex: 1; background: white; border-radius: 12px; padding: 12px;
        text-align: center; border: 1px solid #f0f0f0; box-shadow: 0 4px 8px rgba(0,0,0,0.04);
    }
    .stat-pill b { font-size: 1.3rem; display: block; line-height: 1; }
    .stat-pill span { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #79747E; }

    /* Monthly Accordion */
    .month-box {
        background: white; border-radius: 8px; margin: 0 15px 10px;
        border: 1px solid #F0F0F0; overflow: hidden;
    }
    .month-header {
        padding: 15px; background: var(--m3-tonal); cursor: pointer;
        display: flex; justify-content: space-between; align-items: center;
    }
    .month-body { display: none; padding: 10px; border-top: 1px solid #F0F0F0; }
    
    .log-item {
        display: flex; align-items: center; padding: 10px;
        border-bottom: 1px solid #f9f9f9;
    }
    .log-icon {
        width: 35px; height: 35px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; margin-right: 12px;
    }

    .m3-chart-box {
        background: white; border-radius: 16px; padding: 15px;
        margin: 15px; border: 1px solid #f0f0f0;
    }
</style>

<main class="pb-5">
    <div class="m3-profile-card">
        <div class="avatar-box">
            <img src="<?= student_profile_image_path($stid) ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        </div>
        <h5 class="fw-black mb-1"><?= $std_data['stnameeng'] ?></h5>
        <div class="small fw-bold text-primary text-uppercase">
            CL: <?= $std_data['classname'] ?> <i class="bi bi-dot"></i> 
            SEC: <?= $std_data['sectionname'] ?> <i class="bi bi-dot"></i>
            ROLL: <?= $std_data['rollno'] ?>
        </div>
    </div>

    <div class="stat-container">
        <div class="stat-pill"><b class="text-success"><?= $present_count ?></b><span>Present</span></div>
        <div class="stat-pill"><b class="text-danger"><?= $absent_count ?></b><span>Absent</span></div>
        <div class="stat-pill"><b class="text-warning"><?= $bunk_count ?></b><span>Bunk</span></div>
    </div>

    <div class="m3-section-title px-3 mt-2">Attendance Trend</div>
    <div class="m3-chart-box shadow-sm">
        <canvas id="attChart" height="150"></canvas>
    </div>

    <div class="m3-section-title px-3 mt-4 mb-2">Monthly Log History</div>
    <?php foreach ($grouped_att as $month => $logs): 
        $m_p = 0; foreach($logs as $l) if($l['yn'] == 1) $m_p++;
    ?>
        <div class="month-box shadow-sm">
            <div class="month-header" onclick="toggleM(this)">
                <div>
                    <div class="fw-bold text-dark"><?= $month ?></div>
                    <small class="text-muted fw-bold" style="font-size: 0.65rem;">
                        P: <?= $m_p ?> | A: <?= count($logs)-$m_p ?> Records
                    </small>
                </div>
                <i class="bi bi-chevron-down text-muted"></i>
            </div>
            <div class="month-body">
                <?php foreach($logs as $log): 
                    $isP = ($log['yn'] == 1); $isB = ($log['bunk'] == '1');
                ?>
                <div class="log-item">
                    <div class="log-icon <?= $isP ? ($isB ? 'bg-warning text-white':'bg-success text-white') : 'bg-danger text-white' ?>">
                        <i class="bi <?= $isP ? 'bi-check-lg' : 'bi-x-lg' ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold small"><?= date('d M, l', strtotime($log['adate'])) ?></div>
                        <div class="text-muted" style="font-size: 0.7rem;">Status: <?= $isP ? ($isB ? 'Bunked':'Present'):'Absent' ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</main>


<?php include 'footer.php'; ?>



<script>
// Chart Logic
const ctx = document.getElementById('attChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($graph_labels) ?>,
        datasets: [{
            data: <?= json_encode($graph_values) ?>,
            borderColor: '#6750A4',
            backgroundColor: 'rgba(103, 80, 164, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#6750A4'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { 
            y: { beginAtZero: true, grid: { display: false }, ticks: { font: {size: 9}} },
            x: { grid: { display: false }, ticks: { font: {size: 9, weight:'bold'}} }
        }
    }
});

// Accordion Logic
function toggleM(el) {
    const body = el.nextElementSibling;
    const icon = el.querySelector('.bi-chevron-down');
    if (body.style.display === "block") {
        body.style.display = "none";
        icon.style.transform = "rotate(0deg)";
    } else {
        body.style.display = "block";
        icon.style.transform = "rotate(180deg)";
    }
}
</script>

