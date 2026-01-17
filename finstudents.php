<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. প্যারামিটার হ্যান্ডলিং
$cls = $_GET['cls'] ?? '';
$sec = $_GET['sec'] ?? '';
$current_month = date('m');
$page_title = "Dues List";

// ৩. ডাটা ফেচিং অপ্টিমাইজেশন (Single Joined Query with Prepared Statement)
$student_list = [];
$sql = "SELECT s.stid, s.rollno, st.stnameeng, st.stnameben, st.previll,
               SUM(f.dues) as total_dues, SUM(f.paid) as total_paid
        FROM sessioninfo s
        JOIN students st ON s.stid = st.stid
        LEFT JOIN stfinance f ON s.stid = f.stid 
             AND f.sessionyear LIKE ? 
             AND f.month <= ?
        WHERE s.sccode = ? AND s.classname = ? AND s.sectionname = ? AND s.status = '1'
        GROUP BY s.stid
        ORDER BY s.rollno ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $sy_param, $current_month, $sccode, $cls, $sec);
$stmt->execute();
$result = $stmt->get_result();

$total_class_dues = 0;
while ($row = $result->fetch_assoc()) {
    $student_list[] = $row;
    $total_class_dues += ($row['total_dues'] ?? 0);
}
$stmt->close();
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* M3 Standard App Bar (8px radius bottom) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Hero Summary */
    .hero-stats {
        background: #F3EDF7; border-radius: 8px;
        padding: 12px; margin: 8px 12px;
        display: flex; justify-content: space-around; text-align: center;
    }
    .stat-val { font-size: 1.2rem; font-weight: 800; color: #6750A4; display: block; }
    .stat-lbl { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: #49454F; }

    /* Condensed Student Card (8px Radius) */
    .st-finance-card {
        background-color: #FFFFFF; border-radius: 8px;
        padding: 8px 12px; margin: 0 8px 6px;
        border: 1px solid #eee; display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: transform 0.2s;
    }
    .st-finance-card:active { transform: scale(0.98); background-color: #F7F2FA; }

    .roll-badge {
        width: 38px; height: 38px; border-radius: 6px;
        background-color: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1rem; margin-right: 12px; flex-shrink: 0;
    }

    .st-info { flex-grow: 1; overflow: hidden; }
    .st-name { font-weight: 700; color: #1C1B1F; font-size: 0.85rem; margin-bottom: 0; }
    .st-id-text { font-size: 0.65rem; color: #49454F; font-weight: 500; }

    .due-amount-box { text-align: right; min-width: 80px; }
    .due-val { font-weight: 800; font-size: 1rem; line-height: 1; }
    .due-label { font-size: 0.55rem; font-weight: 700; text-transform: uppercase; margin-top: 2px; }

    .btn-history {
        background-color: #F3EDF7; color: #6750A4; border-radius: 4px; border: none;
        padding: 2px 8px; font-size: 0.6rem; font-weight: 700; margin-top: 4px;
    }

    .text-danger-m3 { color: #B3261E; }
    .text-success-m3 { color: #146C32; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="finclssec.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <div class="page-title"><?php echo "$cls ($sec)"; ?></div>
    <div class="action-icons">
        <i class="bi bi-printer fs-4" onclick="epos();"></i>
    </div>
</header>

<main class="pb-5 mt-2">
    <div class="hero-stats shadow-sm">
        <div>
            <span class="stat-val"><?php echo count($student_list); ?></span>
            <span class="stat-lbl">Students</span>
        </div>
        <div class="vr mx-3 opacity-10"></div>
        <div>
            <span class="stat-val text-danger-m3">৳<?php echo number_format($total_class_dues, 0); ?></span>
            <span class="stat-lbl">Class Dues</span>
        </div>
        <div class="vr mx-3 opacity-10"></div>
        <div>
            <span class="stat-val text-muted"><?php echo $current_session; ?></span>
            <span class="stat-lbl">Session</span>
        </div>
    </div>

    <div class="list-container">
        <?php if (empty($student_list)): ?>
            <div class="text-center py-5 opacity-50">
                <i class="bi bi-person-x display-4"></i>
                <p class="mt-2 fw-bold small">No records for session <?php echo $current_session; ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($student_list as $st): 
                $due = $st['total_dues'] ?? 0;
                $is_defaulter = ($due > 0);
            ?>
                <div class="st-finance-card shadow-sm" onclick="go(<?php echo $st['stid']; ?>)">
                    <div class="roll-badge shadow-sm">
                        <?php echo $st['rollno']; ?>
                    </div>

                    <div class="st-info">
                        <div class="st-name text-truncate"><?php echo $st['stnameeng']; ?></div>
                        <div class="st-id-text">
                            ID: <?php echo $st['stid']; ?> <i class="bi bi-dot"></i> <?php echo $st['previll']; ?>
                        </div>
                        <button class="btn-history" onclick="event.stopPropagation(); window.location.href='stprdetails.php?id=<?php echo $st['stid']; ?>'">
                            <i class="bi bi-clock-history me-1"></i> HISTORY
                        </button>
                    </div>

                    <div class="due-amount-box">
                        <div class="due-val <?php echo $is_defaulter ? 'text-danger-m3' : 'text-success-m3'; ?>">
                            ৳<?php echo number_format($due, 0); ?>
                        </div>
                        <div class="due-label <?php echo $is_defaulter ? 'text-danger-m3' : 'text-success-m3'; ?>">
                            <?php echo $is_defaulter ? 'Pending' : 'Cleared'; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<div style="height: 65px;"></div> <script>
    function go(stid) {
        window.location.href = `stfinancedetails.php?id=${stid}&year=<?php echo $current_session; ?>`;
    }

    function epos() {
        Swal.fire({
            title: 'Print POS?',
            text: 'Print the last payment receipt for this section?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Print'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX logic here
            }
        });
    }
</script>

<?php include 'footer.php'; ?>