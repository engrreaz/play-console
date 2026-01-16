<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. প্যারামিটার হ্যান্ডলিং
$cls = $_GET['cls'] ?? '';
$sec = $_GET['sec'] ?? '';
$current_month = date('m');
$page_title = "Dues: $cls ($sec)";

// ২. ডাটা ফেচিং অপ্টিমাইজেশন (Single Joined Query)
// এটি 'sessioninfo' থেকে স্টুডেন্ট লিস্ট এবং 'stfinance' থেকে বকেয়া একবারেই নিয়ে আসবে
$student_list = [];
$sy_param = "%$sy%";

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
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Hero Summary Card */
    .hero-stats {
        background: #F3EDF7; border-radius: 28px;
        padding: 24px; margin: 16px;
        display: flex; justify-content: space-around; text-align: center;
    }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: #6750A4; display: block; }
    .stat-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #49454F; }

    /* Student Finance Card Style */
    .st-finance-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 16px;
        margin: 0 16px 12px;
        border: none;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, background-color 0.2s;
        cursor: pointer;
    }
    .st-finance-card:active {
        transform: scale(0.98);
        background-color: #F3EDF7;
    }

    .roll-badge {
        width: 44px; height: 44px;
        border-radius: 12px;
        background-color: #EADDFF;
        color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
        margin-right: 16px; flex-shrink: 0;
    }

    .st-info { flex-grow: 1; overflow: hidden; }
    .st-name { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; margin-bottom: 2px; }
    .st-id-text { font-size: 0.7rem; color: #49454F; font-weight: 500; }

    .due-amount-box { text-align: right; min-width: 90px; }
    .due-val { font-weight: 800; font-size: 1.1rem; line-height: 1; }
    .due-label { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; margin-top: 4px; }

    .btn-history {
        background-color: #F3EDF7; color: #6750A4;
        border-radius: 8px; border: none; padding: 4px 8px;
        font-size: 0.65rem; font-weight: 700; margin-top: 5px;
    }

    .text-danger-m3 { color: #B3261E; }
    .text-success-m3 { color: #146C32; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="finclssec.php" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <i class="bi bi-printer" onclick="epos();"></i>
    </div>
</header>

<main class="pb-5 mt-3">
    <div class="hero-stats shadow-sm">
        <div>
            <span class="stat-val"><?php echo count($student_list); ?></span>
            <span class="stat-lbl">Students</span>
        </div>
        <div class="vr mx-3 opacity-25"></div>
        <div>
            <span class="stat-val text-danger-m3">৳<?php echo number_format($total_class_dues, 0); ?></span>
            <span class="stat-lbl">Total Dues</span>
        </div>
    </div>

    <div class="container-fluid p-0">
        <?php if (empty($student_list)): ?>
            <div class="text-center py-5 opacity-50">
                <i class="bi bi-person-x fs-1"></i>
                <p class="mt-2 fw-bold">No records found.</p>
            </div>
        <?php else: ?>
            <?php foreach ($student_list as $st): 
                $due = $st['total_dues'] ?? 0;
                $is_defaulter = ($due > 0);
                $photo_path = "https://eimbox.com/students/" . $st['stid'] . ".jpg";
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

<div style="height: 60px;"></div>

<script>
    function go(stid) {
        window.location.href = "stfinancedetails.php?id=" + stid;
    }

    function epos() {
        // আপনার আগের লজিক অনুযায়ী লাস্ট পিআর প্রিন্ট করার ফাংশন
        Swal.fire({
            title: 'Print POS?',
            text: 'Do you want to print the last payment receipt?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX call logic here
            }
        });
    }
</script>

<?php include 'footer.php'; ?>