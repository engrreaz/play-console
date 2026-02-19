<?php
$page_title = "Proxy Register";
include 'inc.php';

// ---------- ব্যাকএন্ড লজিক (AJAX) ----------

// ১. সেভ বা আপডেট
if (isset($_POST['save_proxy'])) {
    ob_clean(); // অন্য কোনো টেক্সট আউটপুট বন্ধ করা
    header('Content-Type: application/json');

    $id = intval($_POST['f_id'] ?? 0);
    $date = $_POST['f_date'];
    $period = $conn->real_escape_string($_POST['f_period']);
    $class_name = $conn->real_escape_string($_POST['f_class']);
    $section_name = $conn->real_escape_string($_POST['f_section']);
    $absent_tid = intval($_POST['f_absent']);
    $proxy_tid = intval($_POST['f_proxy']);
    $note = $conn->real_escape_string($_POST['f_note']);

    if ($id > 0) {
        $sql = "UPDATE proxy_register SET date='$date', period='$period', class_name='$class_name', section_name='$section_name', absent_tid=$absent_tid, proxy_tid=$proxy_tid, note='$note' WHERE id=$id AND sccode=$sccode";
    } else {
        $sql = "INSERT INTO proxy_register (sccode, date, period, class_name, section_name, absent_tid, proxy_tid, note) VALUES ($sccode, '$date', '$period', '$class_name', '$section_name', $absent_tid, $proxy_tid, '$note')";
    }

    $res = $conn->query($sql);
    echo json_encode(['status' => $res ? 1 : 0, 'msg' => $res ? 'Success' : 'Database Error: ' . $conn->error]);
    exit;
}

// ২. ডিলিট
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $res = $conn->query("DELETE FROM proxy_register WHERE id=$id AND sccode=$sccode");
    echo $res ? '1' : '0';
    exit;
}

// ৩. এডিটের জন্য ডাটা ফেচ
if (isset($_GET['get_proxy_id'])) {
    ob_clean();
    $id = intval($_GET['get_proxy_id']);
    $res = $conn->query("SELECT * FROM proxy_register WHERE id=$id AND sccode=$sccode");
    echo json_encode($res->fetch_assoc());
    exit;
}

// ৪. এনালিটিক্স ডাটা
// Analytics data endpoint
// ৪. এনালিটিক্স ডাটা (Fix: Added t.tname in GROUP BY)
if (isset($_GET['analytics'])) {
    if (ob_get_length())
        ob_clean();
    header('Content-Type: application/json');

    $current_month = date('m');
    $current_year = date('Y');

    // সংশোধিত কুয়েরি: GROUP BY তে t.tname যোগ করা হয়েছে
    $sql_analytics = "SELECT t.tname, COUNT(pr.id) as total 
                      FROM proxy_register pr 
                      JOIN teacher t ON pr.proxy_tid = t.tid 
                      WHERE pr.sccode = ? AND MONTH(pr.date) = ? AND YEAR(pr.date) = ?
                      GROUP BY pr.proxy_tid, t.tname 
                      ORDER BY total DESC";

    $stmt_an = $conn->prepare($sql_analytics);
    if (!$stmt_an) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }

    $stmt_an->bind_param("iii", $sccode, $current_month, $current_year);
    $stmt_an->execute();
    $re = $stmt_an->get_result();

    $data = ['teachers' => [], 'total' => []];
    while ($row = $re->fetch_assoc()) {
        $data['teachers'][] = $row['tname'];
        $data['total'][] = (int) $row['total'];
    }

    echo json_encode($data);
    exit;
}

// ৫. সাধারণ ভিউ লজিক
$view = $_GET['view'] ?? 'today';
$today = date('Y-m-d');
$tid_filter = $_GET['tid'] ?? '';

$sql_base = "SELECT pr.*, t1.tname as absent_name, t2.tname as proxy_name FROM proxy_register pr LEFT JOIN teacher t1 ON pr.absent_tid = t1.tid LEFT JOIN teacher t2 ON pr.proxy_tid = t2.tid WHERE pr.sccode = $sccode";

if ($view == 'today') {
    $sql = $sql_base . " AND pr.date='$today' ORDER BY pr.period ASC";
} elseif ($view == 'history') {
    $sql = $sql_base . " AND pr.date < '$today' ORDER BY pr.date DESC LIMIT 50";
} elseif ($view == 'teacher' && !empty($tid_filter)) {
    $sql = $sql_base . " AND (pr.absent_tid=$tid_filter OR pr.proxy_tid=$tid_filter) ORDER BY pr.date DESC";
} else {
    $sql = $sql_base . " ORDER BY pr.date DESC LIMIT 20";
}

$res = $conn->query($sql);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --primary: #6750A4;
        --tonal: #EADDFF;
        --surface: #FEF7FF;
    }

    body {
        background: var(--surface);
        font-family: 'Inter', sans-serif;
    }

    .hero-proxy {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 30px 20px 60px;
        border-radius: 0 0 32px 32px;
    }

    /* Tabs */
    .tab-bar {
        display: flex;
        background: #eee;
        padding: 4px;
        border-radius: 100px;
        margin: -25px 16px 20px;
        position: relative;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .tab-bar a {
        flex: 1;
        text-align: center;
        padding: 10px;
        border-radius: 100px;
        text-decoration: none;
        color: #49454F;
        font-weight: 800;
        font-size: 0.7rem;
        transition: 0.3s;
    }

    .tab-bar a.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* M3 Cards */




    .action-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #f5f5f5;
        color: var(--primary);
    }

    .teacher-line {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 5px 0;
    }

    .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .fab {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 56px;
        height: 56px;
        background: var(--primary);
        color: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(103, 80, 164, 0.3);
        border: none;
        z-index: 1000;
    }
</style>
<style>
    /* Card Layout Stability */
    .proxy-card {
        background: #ffffff !important;
        border-radius: 20px !important;
        padding: 18px !important;
        margin: 12px 16px !important;
        border: 1px solid #e0e0e0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        position: relative !important;
        display: block !important;
    }

    .period-chip {
        display: inline-block;
        background: #EADDFF;
        color: #21005D;
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .teacher-info-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f5f5f5;
    }

    .t-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot-red {
        background: #ff5252;
    }

    .dot-green {
        background: #4caf50;
    }

    /* Modal Inputs Fix */
    .m3-input {
        width: 100%;
        border: 1.5px solid #79747E;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 15px;
        font-family: inherit;
        font-size: 1rem;
    }
</style>

<main>
    <div class="hero-proxy">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-black m-0">Proxy Register</h4>
            <button class="btn btn-sm btn-light rounded-pill px-3 fw-bold" onclick="showReport()"><i
                    class="bi bi-bar-chart"></i></button>
        </div>
    </div>

    <div class="tab-bar">
        <a href="?view=today" class="<?= $view == 'today' ? 'active' : '' ?>">TODAY</a>
        <a href="?view=history" class="<?= $view == 'history' ? 'active' : '' ?>">HISTORY</a>
        <a href="javascript:void(0)" onclick="showSearch()" class="<?= $view == 'teacher' ? 'active' : '' ?>">BY
            TEACHER</a>
    </div>

    <div id="list-container">
        <?php if ($res->num_rows == 0): ?>
            <div class="text-center py-5 opacity-50">No records found.</div>
        <?php endif; ?>

        <?php while ($p = $res->fetch_assoc()): ?>
            <div class="proxy-card">
                <button class="action-btn" onclick="editProxy(<?= $p['id'] ?>)"><i class="bi bi-pencil-fill"></i></button>
                <div class="mb-2"><span class="period-chip">Period <?= $p['period'] ?></span></div>
                <div class="fw-black text-dark" style="font-size: 1.1rem;"><?= $p['class_name'] ?>
                    (<?= $p['section_name'] ?>)</div>
                <div class="small fw-bold text-muted mb-2"><?= date('d M Y', strtotime($p['date'])) ?></div>

                <div class="teacher-line">
                    <div class="indicator bg-danger"></div>
                    <div class="small">Absent: <b><?= $p['absent_name'] ?></b></div>
                </div>
                <div class="teacher-line">
                    <div class="indicator bg-success"></div>
                    <div class="small">Proxy: <b><?= $p['proxy_name'] ?></b></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<button class="fab" onclick="openAddModal()"><i class="bi bi-plus-lg"></i></button>

<div class="modal fade" id="proxyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-3 shadow-lg" style="border-radius:28px">
            <div class="modal-header border-0">
                <h5 class="fw-black" id="modalTitle">Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="proxyForm">
                <input type="hidden" name="f_id" id="f_id">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6"><input type="date" name="f_date" id="f_date" class="m3-input" required></div>
                        <div class="col-6"><input type="text" name="f_period" id="f_period" class="m3-input"
                                placeholder="Period" required></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6"><input type="text" name="f_class" id="f_class" class="m3-input"
                                placeholder="Class" required></div>
                        <div class="col-6"><input type="text" name="f_section" id="f_section" class="m3-input"
                                placeholder="Section"></div>
                    </div>
                    <select name="f_absent" id="f_absent" class="m3-input" required>
                        <option value="">Absent Teacher</option>
                        <?php $ts = $conn->query("SELECT tid,tname FROM teacher WHERE sccode=$sccode ORDER BY tname");
                        while ($t = $ts->fetch_assoc())
                            echo "<option value='{$t['tid']}'>{$t['tname']}</option>"; ?>
                    </select>
                    <select name="f_proxy" id="f_proxy" class="m3-input" required>
                        <option value="">Proxy Teacher</option>
                        <?php $ts->data_seek(0);
                        while ($t = $ts->fetch_assoc())
                            echo "<option value='{$t['tid']}'>{$t['tname']}</option>"; ?>
                    </select>
                    <textarea name="f_note" id="f_note" class="m3-input" placeholder="Remarks"></textarea>
                </div>
                <div class="d-flex gap-2 px-3 pb-3">
                    <button type="button" class="btn btn-light rounded-pill flex-grow-1 fw-bold" id="delBtn"
                        onclick="deleteEntry()">DELETE</button>
                    <button type="submit" class="btn btn-primary rounded-pill flex-grow-1 fw-bold">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered px-4">
        <div class="modal-content border-0 p-4" style="border-radius:28px">
            <h6 class="fw-black mb-3">SELECT TEACHER</h6>
            <form method="GET">
                <input type="hidden" name="view" value="teacher">
                <select name="tid" class="m3-input" required>
                    <option value="">Choose...</option>
                    <?php $ts->data_seek(0);
                    while ($t = $ts->fetch_assoc())
                        echo "<option value='{$t['tid']}'>{$t['tname']}</option>"; ?>
                </select>
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">SHOW HISTORY</button>
            </form>
        </div>
    </div>
</div>

<!-- Analytics Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:28px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-black text-primary mb-0">Proxy Analytics</h5>
                <select id="analyticsMonth" class="form-select form-select-sm w-auto">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 1));
                        $selected = (date('m') == $m) ? 'selected' : '';
                        echo "<option value='$m' $selected>$monthName</option>";
                    }
                    ?>
                </select>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <canvas id="proxyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>


<div style="height:100px"></div>

<?php include 'footer.php'; ?>

<script>
    const proxyModal = new bootstrap.Modal(document.getElementById('proxyModal'));
    const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));
    const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));

    function openAddModal() {
        document.getElementById('proxyForm').reset();
        document.getElementById('f_id').value = '';
        document.getElementById('modalTitle').innerText = 'New Entry';
        document.getElementById('delBtn').style.display = 'none';
        proxyModal.show();
    }

    function showSearch() { searchModal.show(); }

    function editProxy(id) {
        fetch('?get_proxy_id=' + id)
            .then(r => r.json())
            .then(d => {
                document.getElementById('f_id').value = d.id;
                document.getElementById('f_date').value = d.date;
                document.getElementById('f_period').value = d.period;
                document.getElementById('f_class').value = d.class_name;
                document.getElementById('f_section').value = d.section_name;
                document.getElementById('f_absent').value = d.absent_tid;
                document.getElementById('f_proxy').value = d.proxy_tid;
                document.getElementById('f_note').value = d.note;
                document.getElementById('modalTitle').innerText = 'Edit Entry';
                document.getElementById('delBtn').style.display = 'block';
                proxyModal.show();
            });
    }

    document.getElementById('proxyForm').onsubmit = function (e) {
        e.preventDefault();
        Swal.fire({ title: 'Saving...', didOpen: () => Swal.showLoading() });
        let fd = new FormData(this); fd.append('save_proxy', 1);
        fetch('', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => { if (d.status) location.reload(); else Swal.fire('Error', d.msg, 'error'); })
            .catch(() => Swal.fire('Error', 'Invalid Server Response', 'error'));
    }

    function deleteEntry() {
        let id = document.getElementById('f_id').value;
        if (!confirm('Delete this record?')) return;
        let fd = new FormData(); fd.append('delete_id', id);
        fetch('', { method: 'POST', body: fd }).then(r => r.text()).then(t => { if (t == '1') location.reload(); });
    }





</script>


<script>
    let proxyChartInstance = null;

    function showReport() {
        let modal = new bootstrap.Modal(document.getElementById('reportModal'));
        modal.show();

        // Load chart for default month
        loadAnalyticsChart();
    }

    // Load chart with selected month
    function loadAnalyticsChart() {
        const month = document.getElementById('analyticsMonth').value;
        const ctx = document.getElementById('proxyChart').getContext('2d');

        fetch('?analytics=1&month=' + month)
            .then(res => res.json())
            .then(data => {
                if (proxyChartInstance) proxyChartInstance.destroy();
                proxyChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.teachers,
                        datasets: [{
                            label: 'Classes Done',
                            data: data.total,
                            backgroundColor: 'rgba(103,80,164,0.6)',
                            borderColor: 'rgba(103,80,164,1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Classes Done' } },
                            x: { title: { display: true, text: 'Teachers' } }
                        }
                    }
                });
            })
            .catch(() => alert('Failed to load analytics data'));
    }

    // Event listener for month change
    document.getElementById('analyticsMonth').addEventListener('change', loadAnalyticsChart);

</script>