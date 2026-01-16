<?php
session_start();
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. তারিখ হ্যান্ডলিং (Default current month)
$default_start = date('Y-m-01');
$default_end = date('Y-m-t');

$date1 = $_POST['start_date'] ?? $_GET['start_date'] ?? $default_start;
$date2 = $_POST['end_date'] ?? $_GET['end_date'] ?? $default_end;

// ২. ডাটা ফেচিং (Prepared Statement)
$total_collection = 0;
$daily_collections = [];

if (isset($conn, $sccode)) {
    // প্রিপেড স্টেটমেন্ট ব্যবহার করে সিকিউর কোয়েরি
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
    body { background-color: #FEF7FF; } /* M3 Surface */
    
    .m-card { background: #fff; border-radius: 24px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    
    /* Summary Hero Card */
    .hero-card {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        color: white;
        border-radius: 28px;
        padding: 24px;
        margin-bottom: 24px;
    }

    /* Filter Section */
    .filter-box {
        background: #F3EDF7;
        border-radius: 20px;
        padding: 16px;
    }
    
    /* Table Styling for Mobile */
    .report-row {
        padding: 16px;
        border-bottom: 1px solid #E7E0EC;
        transition: background 0.2s;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .report-row:active { background-color: #EADDFF; }
    .report-row:last-child { border-bottom: none; }
    
    .date-label { font-weight: 600; color: #1C1B1F; font-size: 0.95rem; }
    .day-label { font-size: 0.75rem; color: #79747E; }
    .amount-label { font-weight: 700; color: #6750A4; font-size: 1.1rem; }
</style>

<main class="container mt-3 pb-5">
    <div class="d-flex align-items-center mb-4">
        <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="fw-bold mb-0">Collection Report</h4>
    </div>

    <div class="hero-card shadow-lg">
        <div class="small opacity-75 text-uppercase fw-bold mb-1">Total Collection</div>
        <h2 class="display-5 fw-bold mb-3">৳ <?php echo number_format($total_collection, 2); ?></h2>
        <div class="d-flex align-items-center small">
            <i class="bi bi-calendar3 me-2"></i>
            <span><?php echo date('d M', strtotime($date1)); ?> — <?php echo date('d M, Y', strtotime($date2)); ?></span>
        </div>
    </div>

    <div class="filter-box mb-4 shadow-sm">
        <form method="POST" action="">
            <div class="row g-2 align-items-end">
                <div class="col-5">
                    <label class="small fw-bold text-muted ms-2 mb-1">From</label>
                    <input type="date" name="start_date" class="form-control rounded-pill border-0 px-3" value="<?php echo $date1; ?>">
                </div>
                <div class="col-5">
                    <label class="small fw-bold text-muted ms-2 mb-1">To</label>
                    <input type="date" name="end_date" class="form-control rounded-pill border-0 px-3" value="<?php echo $date2; ?>">
                </div>
                <div class="col-2 text-end">
                    <button type="submit" class="btn btn-primary rounded-circle p-2 shadow-sm" style="width: 42px; height: 42px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="m-card overflow-hidden shadow-sm">
        <div class="p-3 border-bottom bg-light">
            <h6 class="mb-0 fw-bold text-secondary small text-uppercase">Daily Breakdown</h6>
        </div>
        
        <?php if (!empty($daily_collections)): ?>
            <div class="list-group list-group-flush">
                <?php foreach ($daily_collections as $row): ?>
                    <div class="report-row" onclick="viewDayDetails('<?php echo $row['prdate']; ?>')">
                        <div>
                            <div class="date-label"><?php echo date('d F, Y', strtotime($row['prdate'])); ?></div>
                            <div class="day-label"><?php echo date('l', strtotime($row['prdate'])); ?></div>
                        </div>
                        <div class="text-end">
                            <div class="amount-label"><?php echo number_format($row['taka'], 2); ?></div>
                            <i class="bi bi-chevron-right small text-muted opacity-50"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-folder-x display-4 text-muted opacity-25"></i>
                <p class="text-muted mt-2">No records found for this period.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function viewDayDetails(date) {
        // বিস্তারিত রিপোর্টের জন্য নেভিগেশন (ভবিষ্যতের জন্য)
        Swal.fire({
            title: 'Daily Details',
            text: 'Fetching collection details for ' + date,
            icon: 'info',
            confirmButtonColor: '#6750A4',
            timer: 1500,
            showConfirmButton: false
        });
        // window.location.href = "collection_details.php?date=" + date;
    }
</script>

<?php include 'footer.php'; ?>