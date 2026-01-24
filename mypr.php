<?php
include 'inc.php'; 
include 'datam/datam-stprofile.php';

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                    ?? $_COOKIE['query-session'] 
                    ?? $sy;
$sy_param = "%" . $current_session . "%";

// ২. সেটিংস ও কনফিগ
$delete_timeout = 604800; 
$accountant_settled = $accountant_settled ?? 0; 
$page_title = "My Collections";
?>

<style>
    /* Collection Specific Enhancements */
    .hero-cash {
        padding-bottom: 40px; margin-bottom: 0; border-radius: 0 0 24px 24px;
        text-align: center;
    }
    
    .stats-overlay {
        margin: -30px 16px 20px;
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
        position: relative; z-index: 10;
    }

    .m3-stat-card {
        background: #fff; border-radius: 8px; padding: 12px 8px;
        text-align: center; border: 1px solid #f0f0f0;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.05);
    }
    
    .stat-v { font-size: 0.95rem; font-weight: 900; color: #1C1B1F; display: block; }
    .stat-l { font-size: 0.55rem; font-weight: 800; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Receipt List Styling */
    .receipt-item {
        padding: 12px; margin-bottom: 8px;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .st-thumb {
        width: 48px; height: 48px; border-radius: 8px;
        object-fit: cover; background: var(--m3-tonal-surface);
        margin-right: 14px; border: 1px solid #eee;
    }

    .pr-badge {
        font-size: 0.6rem; font-weight: 800;
        background: var(--m3-tonal-container);
        color: var(--m3-on-tonal-container);
        padding: 2px 6px; border-radius: 4px;
    }

    .amount-text {
        font-size: 1.1rem; font-weight: 900; color: var(--m3-primary);
        text-align: right;
    }
</style>

<main>
    <div class="hero-container hero-cash">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="location.href='reporthome.php'">
                <i class="bi bi-arrow-left"></i>
            </div>
            <div style="font-size: 0.75rem; font-weight: 800; opacity: 0.9;">SESSION: <?php echo $current_session; ?></div>
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;">
                <i class="bi bi-funnel"></i>
            </div>
        </div>
        
        <div class="stat-label" style="font-size: 0.75rem; font-weight: 700; opacity: 0.8; letter-spacing: 1px;">NET CASH-IN-HAND</div>
        <div style="font-size: 2.2rem; font-weight: 950; margin: 5px 0;" id="cash_label">৳ 0.00</div>
        <div style="font-size: 0.65rem; font-weight: 600; opacity: 0.7;">Calculated based on current receipts</div>
    </div>

    <div class="stats-overlay">
        <div class="m3-stat-card">
            <span class="stat-v" id="count_label">0</span>
            <span class="stat-l">Receipts</span>
        </div>
        <div class="m3-stat-card">
            <span class="stat-v" id="total_label">৳ 0</span>
            <span class="stat-l">Collected</span>
        </div>
        <div class="m3-stat-card">
            <span class="stat-v text-danger">৳<?php echo number_format($accountant_settled); ?></span>
            <span class="stat-l">Settled</span>
        </div>
    </div>

    <div class="px-3">
        <div class="m3-section-title">Collection History</div>
    </div>

    <div class="widget-grid" style="padding: 0 12px 80px;">
        <?php
        $cnt = 0; $cntamt = 0;
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

                $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown Student';
                $can_delete = (strtotime($cur) - strtotime($etime) < $delete_timeout);
        ?>
            <div class="m3-list-item receipt-item" id="block<?php echo $prno; ?>">
                <img src="https://eimbox.com/students/<?php echo $stid; ?>.jpg" class="st-thumb" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                
                <div class="item-info">
                    <div class="st-title" style="font-size: 0.9rem;"><?php echo $neng; ?></div>
                    <div class="st-desc" style="font-size: 0.75rem;">
                        Roll: <?php echo $row['rollno']; ?> <span style="color:#ccc;">|</span> PR: #<?php echo $prno; ?>
                    </div>
                    <div style="margin-top: 4px;">
                        <span class="pr-badge"><i class="bi bi-calendar-event me-1"></i><?php echo date('d M, Y', strtotime($row['prdate'])); ?></span>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div class="amount-text">৳<?php echo number_format($amount); ?></div>
                    <?php if($can_delete): ?>
                        <button class="btn-del-sm" style="background:#FFEBEE; color:#B3261E; border:none; padding:4px 8px; border-radius:6px; font-size:0.6rem; font-weight:800; margin-top:8px;" onclick="delReceipt(<?php echo $prno; ?>)">
                            <i class="bi bi-trash3"></i> DELETE
                        </button>
                    <?php else: ?>
                        <div style="font-size: 0.55rem; color: #aaa; margin-top: 10px; font-weight: 700;">FINALIZED</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; else: ?>
            <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                <i class="bi bi-receipt-cutoff" style="font-size: 3.5rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No History Found</div>
            </div>
        <?php endif; $stmt->close(); ?>
    </div>
</main>



<script>
    // ডাইনামিক ইউআই আপডেট
    document.getElementById("count_label").innerText = "<?php echo $cnt; ?>";
    document.getElementById("total_label").innerText = "৳ <?php echo number_format($cntamt); ?>";
    document.getElementById("cash_label").innerText = "৳ <?php echo number_format($cntamt - $accountant_settled, 2); ?>";

    function delReceipt(pr) {
        Swal.fire({
            title: 'Delete Receipt?',
            text: "This will remove PR #" + pr + " permanently.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            cancelButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("backend/delmypr.php", { prno: pr }, function() {
                    $("#block" + pr).fadeOut(300);
                    Swal.fire('Deleted!', 'Receipt has been removed.', 'success');
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>