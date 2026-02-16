<?php
$page_title = "Full Academic Report";
include 'inc.php'; 

$stid = $_GET['stid'] ?? 0;
$examcode = $_GET['exam'] ?? '';

if (!$stid || !$examcode) die("<div class='p-5 text-center'>Invalid Request</div>");

// ১. স্টুডেন্ট ও পরীক্ষার তথ্য ফেচ করা
$stmt_st = $conn->prepare("SELECT s.*, si.classname, si.rollno, ex.examtitle 
                           FROM students s 
                           JOIN sessioninfo si ON s.stid = si.stid 
                           JOIN examlist ex ON ex.examcode = ?
                           WHERE s.stid = ? AND si.sessionyear LIKE ? LIMIT 1");
$sy_param = "%" . $sy . "%";
$stmt_st->bind_param("sss", $examcode, $stid, $sy_param);
$stmt_st->execute();
$st = $stmt_st->get_result()->fetch_assoc();

// ২. বিস্তারিত মার্কস ফেচ করা
$stmt_m = $conn->prepare("SELECT m.*, s.subject as subname FROM stmark m JOIN subjects s ON m.subject = s.subcode WHERE m.stid = ? AND m.examcode = ? ORDER BY s.subcode ASC");
$stmt_m->bind_param("ss", $stid, $examcode);
$stmt_m->execute();
$marks_res = $stmt_m->get_result();

$marks_data = [];
$total_marks = 0;
$total_gp = 0;
$failed_subs = 0;

while($row = $marks_res->fetch_assoc()) {
    $marks_data[] = $row;
    $total_marks += $row['markobt'];
    $total_gp += $row['gp'];
    if($row['gp'] == 0) $failed_subs++;
}

$sub_count = count($marks_data);
$final_gpa = ($sub_count > 0) ? number_format($total_gp / $sub_count, 2) : 0;
?>

<style>
    :root { --m3-primary: #6750A4; --m3-surface: #FEF7FF; }
    body { background-color: var(--m3-surface); font-family: 'Segoe UI', sans-serif; }
    
    /* Top Report Header */
    .report-header {
        background: #fff; padding: 25px 20px; border-radius: 0 0 32px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center;
    }
    .profile-img { width: 85px; height: 85px; border-radius: 20px; object-fit: cover; margin-bottom: 12px; border: 3px solid var(--m3-primary); }
    
    /* Summary Cards */
    .summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin: -30px 16px 20px; }
    .sum-card { background: #fff; padding: 16px; border-radius: 24px; text-align: center; border: 1px solid #E7E0EC; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .sum-card .val { font-size: 1.4rem; font-weight: 900; color: var(--m3-primary); }
    .sum-card .lbl { font-size: 0.65rem; font-weight: 800; color: #79747E; text-transform: uppercase; }

    /* Transcript Table */
    .transcript-box { background: #fff; border-radius: 28px; margin: 0 16px 20px; padding: 20px; border: 1px solid #E7E0EC; }
    .sub-row { padding: 12px 0; border-bottom: 1px dashed #E7E0EC; }
    .sub-row:last-child { border-bottom: none; }
    
    .grade-badge { width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; }
    .g-plus { background: #E8F5E9; color: #2E7D32; }
    .g-fail { background: #F9DEDC; color: #B3261E; }
    
    /* Chart Box */
    .chart-container { background: #fff; border-radius: 28px; margin: 0 16px 30px; padding: 20px; border: 1px solid #E7E0EC; }
</style>

<main class="pb-5">
    <div class="report-header">
        <img src="<?= student_profile_image_path($stid) ?>" class="profile-img shadow-sm">
        <h5 class="fw-black m-0 text-dark"><?= $st['stnameeng'] ?></h5>
        <div class="badge bg-primary-subtle text-primary rounded-pill px-3 mt-2"><?= $st['examtitle'] ?></div>
        <p class="small text-muted mt-1">Roll: <?= $st['rollno'] ?> | Class: <?= $st['classname'] ?></p>
    </div>

    <div class="summary-grid">
        <div class="sum-card shadow-sm">
            <div class="val"><?= ($failed_subs > 0) ? '0.00' : $final_gpa ?></div>
            <div class="lbl">GPA Point</div>
        </div>
        <div class="sum-card shadow-sm">
            <div class="val"><?= $total_marks ?></div>
            <div class="lbl">Total Marks</div>
        </div>
    </div>

    <div class="chart-container shadow-sm">
        <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Subject-wise Analysis</h6>
        <canvas id="resultChart" height="200"></canvas>
    </div>

    <div class="transcript-box shadow-sm">
        <h6 class="fw-bold mb-4 border-bottom pb-2">Academic Transcript</h6>
        <?php foreach($marks_data as $m): ?>
        <div class="sub-row d-flex align-items-center">
            <div class="grade-badge me-3 <?= ($m['gp'] > 0) ? 'g-plus' : 'g-fail' ?>">
                <?= $m['gl'] ?>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-dark small"><?= $m['subname'] ?></div>
                <div class="d-flex gap-3 mt-1" style="font-size: 0.65rem; font-weight: 700; color: #79747E;">
                    <span>CA: <?= $m['ca'] ?></span>
                    <span>SUB: <?= $m['subj'] ?></span>
                    <span>OBJ: <?= $m['obj'] ?></span>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-black text-dark"><?= $m['markobt'] ?></div>
                <div class="small text-muted" style="font-size: 0.6rem;">Point: <?= $m['gp'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="px-3 d-flex gap-2">
        <button class="btn btn-outline-primary flex-grow-1 py-3 rounded-pill fw-bold" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>PRINT REPORT
        </button>
        <button class="btn btn-primary py-3 px-4 rounded-circle" onclick="window.history.back()">
            <i class="bi bi-arrow-left fs-5"></i>
        </button>
    </div>
</main>



<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // রেজাল্ট চার্ট জেনারেশন (Bar Chart)
    const ctx = document.getElementById('resultChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($marks_data, 'subname')) ?>,
            datasets: [{
                label: 'Obtained Marks',
                data: <?= json_encode(array_column($marks_data, 'markobt')) ?>,
                backgroundColor: 'rgba(103, 80, 164, 0.6)',
                borderColor: '#6750A4',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 15
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { display: false } },
                x: { grid: { display: false }, ticks: { font: { size: 9 } } }
            }
        }
    });
</script>

