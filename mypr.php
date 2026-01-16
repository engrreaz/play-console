<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. ডিলিট করার সময়সীমা (৭ দিন = ৬০৪৮০০ সেকেন্ড)
$delete_timeout = 604800; 
$accountant_settled = 0; // এটি ডাটাবেজ থেকে পরে ডাইনামিক করা যাবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Hero Stats Card */
    .hero-stats {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 40px 20px 30px;
        color: white;
        text-align: center;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
    }

    .stat-main-val { font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 5px; }
    .stat-label { font-size: 0.8rem; font-weight: 600; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px; }

    /* Stats Chips */
    .tonal-chip-container {
        display: flex; gap: 10px; padding: 0 16px; margin-bottom: 20px;
    }
    .m3-tonal-chip {
        flex: 1; background: #F3EDF7; border-radius: 16px; padding: 12px;
        text-align: center; border: none;
    }
    .chip-val { font-weight: 800; color: #1C1B1F; font-size: 1rem; display: block; }
    .chip-lbl { font-size: 0.65rem; font-weight: 700; color: #6750A4; text-transform: uppercase; }

    /* Receipt Card Item */
    .receipt-card {
        background: #fff; border-radius: 24px; padding: 16px;
        margin: 0 16px 12px; border: none;
        display: flex; align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .receipt-card:active { transform: scale(0.98); background: #F7F2FA; }

    .st-img-wrapper {
        width: 52px; height: 52px; border-radius: 14px;
        overflow: hidden; margin-right: 15px; flex-shrink: 0;
        background: #eee; border: 1px solid #E7E0EC;
    }
    .st-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }

    .receipt-info { flex-grow: 1; overflow: hidden; }
    .st-name { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; margin-bottom: 2px; }
    .receipt-meta { font-size: 0.75rem; color: #49454F; line-height: 1.3; }

    .amount-badge {
        text-align: right; min-width: 80px;
    }
    .amt-val { font-weight: 800; color: #6750A4; font-size: 1.1rem; display: block; }
    
    .btn-delete-receipt {
        background: #FFEBEE; color: #B3261E; border: none;
        border-radius: 100px; padding: 4px 12px; font-size: 0.65rem;
        font-weight: 700; margin-top: 5px;
    }
    .btn-delete-receipt:disabled { opacity: 0.3; }
</style>

<main class="pb-5">
    <div class="hero-stats">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="reporthome.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h6 class="fw-bold mb-0">Collections Report</h6>
            <div style="width: 24px;"></div>
        </div>
        
        <div class="stat-label">Current Cash-in-hand</div>
        <div class="stat-main-val" id="cash_display">৳ 0.00</div>
        <div class="small opacity-75"><i class="bi bi-clock-history me-1"></i> Updated Just Now</div>
    </div>

    <div class="tonal-chip-container">
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val" id="count_display">0</span>
            <span class="chip-lbl">Receipts</span>
        </div>
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val" id="total_display">৳ 0</span>
            <span class="chip-lbl">Collected</span>
        </div>
        <div class="m3-tonal-chip shadow-sm">
            <span class="chip-val text-danger">৳ <?php echo number_format($accountant_settled, 0); ?></span>
            <span class="chip-lbl">Settled</span>
        </div>
    </div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Recent Transactions</h6>

    <div id="receipt-list">
        <?php
        $cnt = 0; $cntamt = 0;
        
        // ২. ডাটা ফেচিং (Prepared Statement - Secure)
        $stmt = $conn->prepare("SELECT stid, rollno, prno, prdate, amount, entrytime, classname, sectionname 
                                FROM stpr 
                                WHERE sessionyear LIKE ? AND sccode = ? AND entryby = ? AND entrytime >= '2025-01-01' 
                                ORDER BY entrytime DESC");
        $sy_param = "%$sy%";
        $stmt->bind_param("sss", $sy_param, $sccode, $usr);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()):
            $stid = $row["stid"];
            $prno = $row["prno"];
            $amount = $row["amount"];
            $entrytime = $row["entrytime"];
            
            $cnt++;
            $cntamt += $amount;

            // স্টুডেন্ট প্রোফাইল ডাটা লুকআপ
            $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
            $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown Student';
            $vill = $datam_st_profile[$st_idx]["previll"] ?? '';
            
            // ফটো পাথ
            include 'component/student-image-path.php'; // $pth ভেরিয়েবল প্রদান করে

            // ডিলিট করার অনুমতি চেক (আপনার লজিক অনুযায়ী)
            $can_delete = (strtotime($cur) - strtotime($entrytime) < $delete_timeout);
        ?>
            <div class="receipt-card shadow-sm" id="block<?php echo $prno; ?>">
                <div class="st-img-wrapper shadow-sm">
                    <img src="<?php echo $pth; ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                </div>
                
                <div class="receipt-info">
                    <div class="st-name text-truncate"><?php echo $neng; ?></div>
                    <div class="receipt-meta">
                        <span class="fw-bold text-primary">Roll: <?php echo $row['rollno']; ?></span> | ID: <?php echo $stid; ?><br>
                        <i class="bi bi-receipt me-1"></i> #<?php echo $prno; ?> <i class="bi bi-dot"></i> <?php echo date('d M, Y', strtotime($row['prdate'])); ?>
                    </div>
                </div>

                <div class="amount-badge">
                    <span class="amt-val">৳<?php echo number_format($amount, 0); ?></span>
                    <button class="btn-delete-receipt" onclick="deleteReceipt(<?php echo $prno; ?>)" <?php echo $can_delete ? '' : 'disabled'; ?>>
                        <i class="bi bi-trash3-fill me-1"></i> DELETE
                    </button>
                </div>
            </div>
        <?php endwhile; $stmt->close(); ?>
    </div>
</main>

<div style="height: 70px;"></div>



<script>
    // পরিসংখ্যান আপডেট করা
    document.getElementById("count_display").innerText = "<?php echo $cnt; ?>";
    document.getElementById("total_display").innerText = "৳ <?php echo number_format($cntamt, 0); ?>";
    document.getElementById("cash_display").innerText = "৳ <?php echo number_format($cntamt - $accountant_settled, 2); ?>";

    function deleteReceipt(pr) {
        Swal.fire({
            title: 'Delete Receipt?',
            text: "This action will remove receipt #" + pr + " from records.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete It'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/delmypr.php",
                    data: { prno: pr },
                    beforeSend: function () {
                        $("#block" + pr).css("opacity", "0.5");
                    },
                    success: function (html) {
                        $("#block" + pr).fadeOut(300, function(){ $(this).remove(); });
                        Swal.fire('Deleted!', 'The receipt has been removed.', 'success');
                        // এখানে আপনি চাইলে পেজ রিলোড বা স্ট্যাটাস আপডেট করতে পারেন
                    }
                });
            }
        });
    }

    function gox(id) { window.location.href = "stfinancedetails.php?id=" + id; }
</script>

<?php include 'footer.php'; ?>