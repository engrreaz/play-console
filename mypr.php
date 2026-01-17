<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. ডিলিট করার সময়সীমা (৭ দিন) এবং পেজ টাইটেল
$delete_timeout = 604800; 
$accountant_settled = 0; // এটি পরে আপনার ডাটাবেজ লজিক অনুযায়ী আপডেট হবে
$page_title = "My Collections";
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

    /* Condensed Hero Banner */
    .hero-stats {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 8px 8px; /* গাইডলাইন অনুযায়ী স্লিম রেডিয়াস */
        padding: 24px 16px; margin-bottom: 12px;
        color: white; text-align: center;
        box-shadow: 0 4px 8px rgba(103, 80, 164, 0.15);
    }
    .stat-main-val { font-size: 2rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 0.7rem; font-weight: 700; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; }

    /* M3 Tonal Chips (8px radius) */
    .chip-container { display: flex; gap: 8px; padding: 0 12px; margin-bottom: 16px; }
    .m3-tonal-chip {
        flex: 1; background: #F3EDF7; border-radius: 8px; padding: 8px;
        text-align: center; border: 1px solid #EADDFF;
    }
    .chip-val { font-weight: 800; color: #1C1B1F; font-size: 0.9rem; display: block; }
    .chip-lbl { font-size: 0.55rem; font-weight: 700; color: #6750A4; text-transform: uppercase; }

    /* Condensed Receipt Card (8px Radius) */
    .receipt-card {
        background: #fff; border-radius: 8px; padding: 10px 12px;
        margin: 0 8px 6px; border: 1px solid #eee;
        display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .receipt-card:active { transform: scale(0.98); background: #F7F2FA; }

    .st-avatar-sm {
        width: 44px; height: 44px; border-radius: 6px;
        overflow: hidden; margin-right: 12px; flex-shrink: 0;
        background: #eee; border: 1px solid #E7E0EC;
    }
    .st-avatar-sm img { width: 100%; height: 100%; object-fit: cover; }

    .receipt-info { flex-grow: 1; overflow: hidden; }
    .st-name-text { font-weight: 700; color: #1C1B1F; font-size: 0.85rem; margin-bottom: 0; }
    .receipt-meta { font-size: 0.65rem; color: #49454F; line-height: 1.3; }

    .amt-badge { text-align: right; min-width: 75px; }
    .amt-val { font-weight: 800; color: #6750A4; font-size: 1rem; display: block; }
    
    .btn-del-sm {
        background: #FFEBEE; color: #B3261E; border: none;
        border-radius: 4px; padding: 2px 8px; font-size: 0.55rem;
        font-weight: 700; margin-top: 4px;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-funnel fs-5"></i></div>
</header>

<main class="pb-5">
    <div class="hero-stats shadow-sm">
        <div class="stat-label">Net Cash-in-hand</div>
        <div class="stat-main-val" id="cash_label">৳ 0.00</div>
        <div class="small opacity-75" style="font-size: 0.65rem;">Session: <?php echo $current_session; ?></div>
    </div>

    <div class="chip-container">
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val" id="count_label">0</span>
            <span class="chip-lbl">Receipts</span>
        </div>
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val" id="total_label">৳ 0</span>
            <span class="chip-lbl">Collected</span>
        </div>
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val text-danger">৳<?php echo number_format($accountant_settled); ?></span>
            <span class="chip-lbl">Settled</span>
        </div>
    </div>

    <h6 class="fw-bold text-secondary mb-3 ms-3 small uppercase tracking-wider">Transaction Timeline</h6>

    <div id="receipt-list">
        <?php
        $cnt = 0; $cntamt = 0;
        
        // ৩. ডাটা ফেচিং (Prepared Statement with Session Priority)
        $stmt = $conn->prepare("SELECT stid, rollno, prno, prdate, amount, entrytime, classname, sectionname 
                                FROM stpr 
                                WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ? 
                                ORDER BY entrytime DESC LIMIT 50");
        $stmt->bind_param("sss", $sy_param, $sccode, $usr);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $stid = $row["stid"];
                $prno = $row["prno"];
                $amount = $row["amount"];
                $etime = $row["entrytime"];
                
                $cnt++;
                $cntamt += $amount;

                // স্টুডেন্ট প্রোফাইল ডাটা লুকআপ
                $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown Student';
                
                // ডিলিট চেক
                $can_delete = (strtotime($cur) - strtotime($etime) < $delete_timeout);
        ?>
            <div class="receipt-card shadow-sm" id="block<?php echo $prno; ?>">
                <div class="st-avatar-sm shadow-sm">
                    <img src="https://eimbox.com/students/<?php echo $stid; ?>.jpg" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                </div>
                
                <div class="receipt-info">
                    <div class="st-name-text text-truncate"><?php echo $neng; ?></div>
                    <div class="receipt-meta">
                        <span class="fw-bold text-primary">Roll: <?php echo $row['rollno']; ?></span> | ID: <?php echo $stid; ?><br>
                        <i class="bi bi-receipt me-1"></i> #<?php echo $prno; ?> <i class="bi bi-dot"></i> <?php echo date('d M, y', strtotime($row['prdate'])); ?>
                    </div>
                </div>

                <div class="amt-badge">
                    <span class="amt-val">৳<?php echo number_format($amount, 0); ?></span>
                    <button class="btn-del-sm" onclick="delReceipt(<?php echo $prno; ?>)" <?php echo $can_delete ? '' : 'disabled'; ?>>
                        <i class="bi bi-trash3-fill"></i> DELETE
                    </button>
                </div>
            </div>
        <?php endwhile; else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-receipt-cutoff display-4"></i>
                <p class="small fw-bold mt-2">No records found for <?php echo $current_session; ?></p>
            </div>
        <?php endif; $stmt->close(); ?>
    </div>
</main>

<div style="height: 70px;"></div>

<script>
    // ৪. ডাইনামিক ইউআই আপডেট
    document.getElementById("count_label").innerText = "<?php echo $cnt; ?>";
    document.getElementById("total_label").innerText = "৳ <?php echo number_format($cntamt); ?>";
    document.getElementById("cash_label").innerText = "৳ <?php echo number_format($cntamt - $accountant_settled, 2); ?>";

    function delReceipt(pr) {
        Swal.fire({
            title: 'Delete Receipt?',
            text: "This will remove PR #" + pr + " from records.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("backend/delmypr.php", { prno: pr }, function() {
                    $("#block" + pr).fadeOut(300);
                    Swal.fire('Deleted!', '', 'success').then(() => location.reload());
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>