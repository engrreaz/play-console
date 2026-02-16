<?php
$page_title = "Student Attendance Analytics";
include 'inc.php';

// ১. প্যারামিটার হ্যান্ডলিং
$stid = isset($_GET['stid']) ? intval($_GET['stid']) : 0;
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// ২. সেশন এবং স্টুডেন্ট ইনফো ফেচিং
$stmt = $conn->prepare("SELECT s.*, si.classname, si.sectionname, si.rollno FROM students s JOIN sessioninfo si ON s.stid = si.stid WHERE s.stid = ? AND si.sccode = ? LIMIT 1");
$stmt->bind_param("ii", $stid, $sccode);
$stmt->execute();
$std_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$std_data)
    die("<div class='p-5 text-center'>Student not found.</div>");

// ৩. উইকেন্ড সেটিংস ফেচ করা (উন্নত লজিক - ডট বা স্পেস উভয়ই হ্যান্ডেল করবে)
$weekendDays = [];
foreach ($ins_all_settings as $row) {
    if ($row['setting_title'] === 'Weekends') {
        $weekendDays = preg_split('/[\s,.]+/', trim($row['settings_value']));
        break;
    }
}

// ৪. তারিখ নির্ধারণ
$start = "$year-$month-01";
$end = ($month == date('m') && $year == date('Y')) ? date('Y-m-d') : date("Y-m-t", strtotime($start));

// ৫. উপস্থিতির ডাটা (stattnd)
$stmt_att = $conn->prepare("SELECT adate, yn, bunk FROM stattnd WHERE stid = ? AND sccode = ? AND adate BETWEEN ? AND ?");
$stmt_att->bind_param("iiss", $stid, $sccode, $start, $end);
$stmt_att->execute();
$res_att = $stmt_att->get_result();
$att_data = [];
while ($r = $res_att->fetch_assoc()) {
    $att_data[$r['adate']] = $r;
}
$stmt_att->close();

// ৬. শিক্ষার্থীর অনুমোদিত ছুটি (student_leave_app)
$stmt_lv = $conn->prepare("SELECT date_from, date_to FROM student_leave_app WHERE stid = ? AND sccode = ? AND status='Approved' AND date_from <= ? AND date_to >= ?");
$stmt_lv->bind_param("iiss", $stid, $sccode, $end, $start);
$stmt_lv->execute();
$res_lv = $stmt_lv->get_result();
$leave_dates = [];
while ($r = $res_lv->fetch_assoc()) {
    $cur = $r['date_from'];
    while ($cur <= $r['date_to']) {
        $leave_dates[$cur] = true;
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

// ৭. ক্যালেন্ডার/হলিডে ডাটা
$stmt_cal = $conn->prepare("SELECT date, dateto, category, work FROM calendar WHERE (sccode=? OR sccode=0) AND (date BETWEEN ? AND ? OR dateto BETWEEN ? AND ?)");
$stmt_cal->bind_param("issss", $sccode, $start, $end, $start, $end);
$stmt_cal->execute();
$res_cal = $stmt_cal->get_result();
$holidays = [];
while ($r = $res_cal->fetch_assoc()) {
    $to = $r['dateto'] ?: $r['date'];
    $cur = $r['date'];
    while ($cur <= $to) {
        $holidays[$cur] = $r['category'];
        $cur = date("Y-m-d", strtotime("+1 day", strtotime($cur)));
    }
}

// ৮. পরিসংখ্যান এবং চার্ট ডাটা জেনারেশন
$dates_array = [];
$d = $start;
while ($d <= $end) {
    $dates_array[] = $d;
    $d = date("Y-m-d", strtotime("+1 day", strtotime($d)));
}

$chart_labels = [];
$chart_values = [];
$present = $absent = $bunk = $leave = 0;

foreach ($dates_array as $dt) {
    $chart_labels[] = date('d', strtotime($dt));
    $dayName = date('l', strtotime($dt));

    $is_h = isset($holidays[$dt]);
    $is_w = in_array($dayName, $weekendDays);
    $is_lv = isset($leave_dates[$dt]);

    if ($is_h || $is_w) {
        $chart_values[] = null; // ছুটি বা উইকেন্ডে চার্ট পয়েন্ট নেই
    } elseif ($is_lv) {
        $leave++;
        $chart_values[] = 0.5; // ছুটির জন্য মাঝামাঝি পয়েন্ট
    } elseif (isset($att_data[$dt])) {
        if ($att_data[$dt]['yn'] == 1) {
            $present++;
            $chart_values[] = 1;
            if ($att_data[$dt]['bunk'] == 1)
                $bunk++;
        } else {
            $absent++;
            $chart_values[] = 0;
        }
    } else {
        $absent++;
        $chart_values[] = 0;
    }
}
$display_dates = array_reverse($dates_array);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-tonal: #F3EDF7;
        --att-present: #E8F5E9;
        --att-absent: #F9DEDC;
        --att-leave: #FFF8E1;
        --att-bunk: #FFF9C4;
        --att-off: #F1F0F4;
    }


    /* Hero Student Card */
    .m3-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        margin: 12px;
        padding: 24px 20px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.2);
    }

    .stat-row {
        display: flex;
        gap: 8px;
        padding: 0 12px;
        margin-top: -25px;
        position: relative;
        z-index: 10;
    }

    .stat-card {
        flex: 1;
        background: white;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        border: 1px solid #F0F0F0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .stat-card b {
        font-size: 1.2rem;
        display: block;
        line-height: 1;
    }

    .stat-card span {
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #79747E;
    }

    /* Attendance Tiles */
    .att-tile {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        margin: 0 12px 10px;
        display: flex;
        align-items: center;
        border: 1px solid #F0F0F0;
        transition: 0.2s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .date-box {
        min-width: 50px;
        text-align: center;
        border-right: 1px solid #EEE;
        margin-right: 15px;
        padding-right: 15px;
    }

    .date-box .day {
        font-size: 1.2rem;
        font-weight: 900;
        color: var(--m3-primary);
        line-height: 1;
    }

    .date-box .wk {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #79747E;
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1rem;
        margin-right: 15px;
    }

    .present {
        background: var(--att-present);
        color: #2E7D32;
    }

    .absent {
        background: var(--att-absent);
        color: #B3261E;
    }

    .leave {
        background: var(--att-leave);
        color: #FF8F00;
    }

    .bunk {
        background: var(--att-bunk);
        color: #F57F17;
    }

    .off {
        background: var(--att-off);
        color: #49454F;
    }

    /* M3 Field Style for Modal */
    .m3-input-group {
        position: relative;
        margin-top: 15px;
    }

    .m3-input-label {
        position: absolute;
        left: 12px;
        top: -10px;
        background: #fff;
        padding: 0 5px;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
    }

    .m3-field-m {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 2px solid var(--m3-tonal);
        outline: none;
        font-weight: 600;
    }
</style>

<main class="pb-5">
    <div class="m3-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="avatar-box me-3"
                    style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(255,255,255,0.3);">
                    <img src="<?= student_profile_image_path($stid) ?>" class="w-100 h-100 object-fit-cover"
                        onerror="this.src='https://eimbox.com/students/noimg.jpg';">
                </div>
                <div>
                    <h5 class="fw-black m-0"><?= $std_data['stnameeng'] ?></h5>
                    <p class="small m-0 opacity-75"><?= $std_data['classname'] ?> | <?= $std_data['sectionname'] ?> 
                    <p class="small m-0 opacity-75"> Roll &mdash; <?= $std_data['rollno'] ?></p>
                    </p>
                </div>
            </div>
            <button class="btn btn-light btn-sm fw-bold rounded-pill px-3 shadow-sm text-primary"
                onclick="$('#leaveModal').modal('show')">
                <i class="bi bi-plus-circle-fill"></i> LEAVE
            </button>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-card"><b id="count-p"><?= $present ?></b><span>Present</span></div>
        <div class="stat-card"><b id="count-a"><?= $absent ?></b><span>Absent</span></div>
        <div class="stat-card"><b id="count-b"><?= $bunk ?></b><span>Bunked</span></div>
        <div class="stat-card"><b id="count-lv"><?= $leave ?></b><span>Leave</span></div>
    </div>

    <div class="m3-chart-box shadow-sm mx-3 mt-4 p-3 bg-white rounded-4 border">
        <div class="small fw-bold text-muted mb-2 text-uppercase" style="letter-spacing: 1px;">Monthly Activity Chart
        </div>
        <canvas id="attChart" style="max-height: 180px;"></canvas>
    </div>

    <div class="px-3 mt-4 mb-3 d-flex justify-content-between align-items-center">
        <span class="fw-black text-muted small text-uppercase">Activity Timeline</span>
        <span class="badge bg-light text-dark border rounded-pill"><?= date('F Y', strtotime($start)) ?></span>
    </div>

    <div id="attendance-list">
        <?php foreach ($display_dates as $dt):
            $dayName = date('l', strtotime($dt));
            $is_h = isset($holidays[$dt]);
            $is_w = in_array($dayName, $weekendDays);
            $is_lv = isset($leave_dates[$dt]);
            $is_att = isset($att_data[$dt]);

            $cls = 'absent';
            $icon = 'A';
            $status_txt = 'Absent from Class';

            if ($is_h) {
                $cls = 'off';
                $icon = 'H';
                $status_txt = $holidays[$dt];
            } elseif ($is_w) {
                $cls = 'off';
                $icon = 'W';
                $status_txt = 'Weekly Weekend';
            } elseif ($is_lv) {
                $cls = 'leave';
                $icon = 'LV';
                $status_txt = 'Approved Leave';
            } elseif ($is_att) {
                if ($att_data[$dt]['yn'] == 1) {
                    $cls = ($att_data[$dt]['bunk'] == 1) ? 'bunk' : 'present';
                    $icon = ($att_data[$dt]['bunk'] == 1) ? 'B' : 'P';
                    $status_txt = ($att_data[$dt]['bunk'] == 1) ? 'Bunked after Attendance' : 'Present in Class';
                }
            }
            ?>
            <div class="att-tile">
                <div class="date-box">
                    <div class="day"><?= date('d', strtotime($dt)) ?></div>
                    <div class="wk"><?= date('D', strtotime($dt)) ?></div>
                </div>
                <div class="status-icon <?= $cls ?> shadow-sm"><?= $icon ?></div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= $status_txt ?></div>
                    <div class="small text-muted"><?= $dayName ?></div>
                </div>
                <i class="bi bi-chevron-right text-muted opacity-25"></i>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4 mb-5 px-3">
        <button class="btn btn-outline-primary rounded-pill w-100 py-3 fw-bold shadow-sm" id="loadMore"
            data-month="<?= $month ?>" data-year="<?= $year ?>" data-stid="<?= $stid ?>">
            <i class="bi bi-clock-history me-2"></i>LOAD PREVIOUS MONTH
        </button>
    </div>
</main>

<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-black text-primary"><i class="bi bi-pencil-square me-2"></i>Apply for Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="leaveApplyForm">
                    <input type="hidden" name="stid" value="<?= $stid ?>">
                    <input type="hidden" name="sccode" value="<?= $sccode ?>">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="m3-input-group"><label class="m3-input-label">Date From</label><input
                                    type="date" name="date_from" class="m3-field-m" required></div>
                        </div>
                        <div class="col-6">
                            <div class="m3-input-group"><label class="m3-input-label">Date To</label><input type="date"
                                    name="date_to" class="m3-field-m" required></div>
                        </div>
                        <div class="col-12">
                            <div class="m3-input-group"><label class="m3-input-label">Leave Type</label><select
                                    name="leave_type" class="m3-field-m">
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                </select></div>
                        </div>
                        <div class="col-12">
                            <div class="m3-input-group"><label class="m3-input-label">Additional Note</label><textarea
                                    name="apply_by" class="m3-field-m" rows="2"
                                    placeholder="Explain reason..."></textarea></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 mt-4 rounded-pill fw-bold shadow"
                        id="submitLeave">SUBMIT APPLICATION</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    // --- ১. চার্ট রেন্ডারিং ---
    const ctx = document.getElementById('attChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                data: <?= json_encode($chart_values) ?>,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                spanGaps: true,
                backgroundColor: 'rgba(103, 80, 164, 0.05)', // নিচের হালকা শ্যাডো

                // ১. ডাইনামিক পয়েন্ট কালার (আগের মতোই)
                pointBackgroundColor: function (context) {
                    const value = context.dataset.data[context.dataIndex];
                    if (value === 1) return '#2E7D32';   // Green for P
                    if (value === 0) return '#B3261E';   // Red for A
                    if (value === 0.5) return '#FF8F00'; // Orange for LV
                    return '#6750A4';
                },

                // ২. ডাইনামিক লাইন কালার (Segment Logic)
                segment: {
                    borderColor: function (context) {
                        const val = context.p1.parsed.y; // পরবর্তী পয়েন্টের মান অনুযায়ী রঙ হবে
                        if (val === 1) return '#2E7D32';   // লাইনের শেষ যদি P হয়
                        if (val === 0) return '#B3261E';   // লাইনের শেষ যদি A হয়
                        if (val === 0.5) return '#FF8F00'; // লাইনের শেষ যদি LV হয়
                        return '#6750A4';
                    }
                }
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    min: 0,
                    max: 1,
                    ticks: {
                        stepSize: 0.5,
                        callback: v => v == 1 ? 'P' : (v == 0 ? 'A' : (v == 0.5 ? 'LV' : ''))
                    }
                },
                x: { grid: { display: false }, ticks: { font: { size: 9 } } }
            }
        }
    });

    // --- ২. লোড মোর (AJAX) ---
    $(document).on('click', '#loadMore', function () {
        let btn = $(this);
        let month = parseInt(btn.attr('data-month')) - 1;
        let year = parseInt(btn.attr('data-year'));
        if (month < 1) { month = 12; year--; }

        $.ajax({
            url: 'ajax/student-attnd-more.php',
            type: 'GET',
            data: { stid: btn.attr('data-stid'), month: month, year: year },
            beforeSend: function () { btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> LOADING...'); },
            success: function (res) {
                if (res.trim().length > 10) {
                    // ১. নতুন মাসের তালিকা যোগ করা
                    const responseHtml = $(res);
                    $('#attendance-list').append(responseHtml);

                    // ২. নতুন ডাটা থেকে পরিসংখ্যান খুঁজে বের করা
                    const newStats = responseHtml.filter('.new-stats-data');
                    if (newStats.length > 0) {
                        // বর্তমান ভ্যালুগুলো নেওয়া
                        let currP = parseInt($('#count-p').text());
                        let currA = parseInt($('#count-a').text());
                        let currB = parseInt($('#count-b').text());
                        let currLV = parseInt($('#count-lv').text());

                        // নতুন মাসের ভ্যালুগুলো যোগ করা
                        $('#count-p').text(currP + parseInt(newStats.data('p')));
                        $('#count-a').text(currA + parseInt(newStats.data('a')));
                        $('#count-b').text(currB + parseInt(newStats.data('b')));
                        $('#count-lv').text(currLV + parseInt(newStats.data('lv')));
                    }

                    // বাটন ডাটা আপডেট (আগের মতোই)
                    btn.attr('data-month', month).attr('data-year', year);
                    btn.prop('disabled', false).html('<i class="bi bi-clock-history me-2"></i> LOAD PREVIOUS MONTH');
                } else {
                    btn.text('NO MORE RECORDS').prop('disabled', true);
                }
            }
        });
    });

    // --- ৩. লিভ অ্যাপ্লিকেশন সাবমিট ---
    $('#leaveApplyForm').on('submit', function (e) {
        e.preventDefault();
        const btn = $('#submitLeave');
        $.post('ajax/save_student_leave.php', $(this).serialize(), function (res) {
            if (res.trim() === 'success') {
                alert('Application submitted successfully!');
                location.reload();
            } else { alert('Error: ' + res); }
        });
    });
</script>

<?php include 'footer.php'; ?>