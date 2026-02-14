<?php
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. তারিখ হ্যান্ডলিং (Default: Current Month)
$date1 = $_POST['start_date'] ?? $_GET['start_date'] ?? date('Y-m-01');
$date2 = $_POST['end_date'] ?? $_GET['end_date'] ?? date('Y-m-t');

$page_title = "Collection Report";

// ৩. ডাটা ফেচিং (Prepared Statement - Secure)
$total_collection = 0;
$daily_collections = [];

if (isset($conn, $sccode)) {
    $sql = "SELECT prdate, SUM(amount) as taka 
            FROM stpr 
            WHERE sccode = ? AND prdate BETWEEN ? AND ? 
            GROUP BY prdate 
            ORDER BY prdate DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $sccode, $date1, $date2);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $daily_collections[] = $row;
        $total_collection += $row['taka'];
    }
    $stmt->close();
}
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

    /* Hero Total Card (8px Radius) */
    .hero-stats {
        background: #6750A4; color: #fff; border-radius: 8px;
        padding: 20px; margin: 12px; box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }
    .hero-val { font-size: 1.8rem; font-weight: 800; display: block; line-height: 1.2; }
    .hero-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; }

    /* Filter Card (Condensed) */
    .filter-card {
        background: #fff; border-radius: 8px; padding: 12px; margin: 0 12px 16px;
        border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .form-control-sm { border-radius: 6px; border: 1px solid #79747E; font-size: 0.8rem; font-weight: 600; }

    /* List Item Row (8px Radius) */
    .report-card {
        background: #fff; border-radius: 8px; padding: 12px 16px;
        margin: 0 12px 8px; border: 1px solid #f0f0f0;
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: 0.2s;
    }
    .report-card:active { background-color: #F3EDF7; transform: scale(0.98); }

    .date-main { font-weight: 700; color: #1C1B1F; font-size: 0.9rem; }
    .date-sub { font-size: 0.7rem; color: #79747E; font-weight: 500; }
    .amt-val { font-weight: 800; color: #6750A4; font-size: 1rem; }

    .session-chip {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <span class="session-chip"><?php echo $current_session; ?></span>
</header>

<main class="pb-5">
    <div class="hero-stats shadow-sm">
        <span class="hero-lbl">Total Collection</span>
        <span class="hero-val">৳ <?php echo number_format($total_collection, 0); ?></span>
        <div class="mt-2 small opacity-75">
            <i class="bi bi-calendar-range me-1"></i>
            <?php echo date('d M', strtotime($date1)); ?> — <?php echo date('d M, Y', strtotime($date2)); ?>
        </div>
    </div>

    <div class="filter-card shadow-sm">
        <form method="POST" class="row gx-2 align-items-end">
            <div class="col-5">
                <label class="small fw-bold text-muted ms-1 mb-1">From</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo $date1; ?>">
            </div>
            <div class="col-5">
                <label class="small fw-bold text-muted ms-1 mb-1">To</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo $date2; ?>">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm" style="border-radius: 6px; height: 33px;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="px-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Daily Breakdown</div>

    <div class="list-container px-1">
        <?php if (!empty($daily_collections)): ?>
            <?php foreach ($daily_collections as $row): ?>
                <div class="report-card shadow-sm" onclick="viewDayDetails('<?php echo $row['prdate']; ?>')">
                    <div>
                        <div class="date-main"><?php echo date('d F, Y', strtotime($row['prdate'])); ?></div>
                        <div class="date-sub"><?php echo date('l', strtotime($row['prdate'])); ?></div>
                    </div>
                    <div class="text-end">
                        <div class="amt-val">৳ <?php echo number_format($row['taka'], 0); ?></div>
                        <i class="bi bi-chevron-right text-muted opacity-25" style="font-size: 0.7rem;"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-cash-stack display-1"></i>
                <p class="fw-bold mt-2">No transactions found.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 75px;"></div> <script>
    function viewDayDetails(date) {
        // বিস্তারিত রিপোর্ট পপআপ বা নেভিগেশন
        Swal.fire({
            title: 'Daily Details',
            text: 'Loading detailed report for ' + date,
            icon: 'info',
            confirmButtonColor: '#6750A4',
            timer: 1200,
            showConfirmButton: false
        });
    }
</script>

<?php include 'footer.php'; ?>