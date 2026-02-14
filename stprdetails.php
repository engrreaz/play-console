<?php
$page_title = "Payment History";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. প্যারামিটার হ্যান্ডলিং ও ডাটা ফেচিং
$stid = $_GET['id'] ?? '';

$total_paid_all = 0;

// ২. স্টুডেন্ট ইনফো ফেচ করা (Prepared Statement)
$stmt_st = $conn->prepare("SELECT classname, sectionname, rollno, lastpr FROM sessioninfo WHERE sessionyear=? AND sccode=? AND stid=?");
$stmt_st->bind_param("sss", $sy, $sccode, $stid);
$stmt_st->execute();
$res_st = $stmt_st->get_result();

if ($row0 = $res_st->fetch_assoc()) {
    $rollno = $row0["rollno"];
    $ccc = $row0["classname"];
    $sss = $row0["sectionname"];
    $lastpr = $row0["lastpr"];

    // প্রোফাইল ডাটা লুকআপ (Array Search)
    $st_ind = array_search($stid, array_column($datam_st_profile, 'stid'));
    $neng = $datam_st_profile[$st_ind]["stnameeng"] ?? 'N/A';
    $nben = $datam_st_profile[$st_ind]["stnameben"] ?? '';
    $photo_path = "https://eimbox.com/students/" . $stid . ".jpg";
}
$stmt_st->close();
?>

<style>
    body {
        background-color: #FEF7FF;
    }

    /* M3 Surface Background */

    /* Hero Profile Card */
    .st-hero-card {
        background-color: #FFFFFF;
        border-radius: 28px;
        padding: 24px;
        margin: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .st-avatar {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        object-fit: cover;
        margin-right: 16px;
        border: 2px solid #EADDFF;
    }

    .paid-badge {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 12px 20px;
        border-radius: 20px;
        text-align: center;
        margin: 0 16px 20px;
    }

    /* Transaction Card Style */
    .pr-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 16px;
        margin: 0 16px 12px;
        border: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, background-color 0.2s;
    }

    .pr-card:active {
        transform: scale(0.98);
        background-color: #F3EDF7;
    }

    .pr-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #F3EDF7;
        color: #6750A4;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.4rem;
    }

    .pr-amount {
        font-weight: 800;
        font-size: 1.1rem;
        color: #146C32;
        text-align: right;
    }

    .breakdown-box {
        background: #F7F2FA;
        border-radius: 16px;
        padding: 12px;
        margin-top: 12px;
        display: none;
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        padding: 4px 0;
        border-bottom: 1px dashed #E7E0EC;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="javascript:history.back()" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-printer"></i></div>
</header>

<main class="pb-5 mt-2">
    <div class="st-hero-card shadow-sm">
        <img src="<?php echo $photo_path; ?>" class="st-avatar shadow-sm"
            onerror="this.src='https://eimbox.com/students/noimg.jpg';">
        <div class="overflow-hidden">
            <h5 class="fw-bold mb-0 text-truncate"><?php echo $neng; ?></h5>
            <p class="small text-muted mb-1"><?php echo "$ccc - $sss | Roll: $rollno"; ?></p>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">ID: <?php echo $stid; ?></span>
        </div>
    </div>

    <div id="total_paid_container"></div>

    <h6 class="ms-4 mb-3 text-secondary fw-bold small text-uppercase tracking-wider">Transaction History</h6>

    <div id="receipt-list">
        <?php
        // ৩. লেনদেন তালিকা ফেচ করা
        $stmt_pr = $conn->prepare("SELECT * FROM stpr WHERE sccode=? AND sessionyear=? AND stid=? ORDER BY prdate DESC, prno DESC");
        $stmt_pr->bind_param("sss", $sccode, $sy, $stid);
        $stmt_pr->execute();
        $res_pr = $stmt_pr->get_result();

        if ($res_pr->num_rows > 0):
            while ($row = $res_pr->fetch_assoc()):
                $prno = $row["prno"];
                $tamt = $row["amount"];
                $total_paid_all += $tamt;
                ?>
                <div class="pr-card shadow-sm" onclick="toggleBreakdown('<?php echo $prno; ?>')">
                    <div class="d-flex align-items-center">
                        <div class="pr-icon"><i class="bi bi-receipt-cutoff"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">PR #<?php echo $prno; ?></div>
                            <div class="small text-muted"><?php echo date('d M, Y', strtotime($row['prdate'])); ?></div>
                        </div>
                        <div class="pr-amount">
                            ৳<?php echo number_format($tamt, 0); ?>
                        </div>
                    </div>

                    <div class="breakdown-box shadow-sm" id="hde<?php echo $prno; ?>">
                        <div class="label-small text-primary fw-bold mb-2" style="font-size: 0.65rem;">ITEMIZED BREAKDOWN</div>
                        <?php
                        $stmt_item = $conn->prepare("SELECT particulareng, amount FROM stfinance WHERE pr1no=? OR pr2no=?");
                        $stmt_item->bind_param("ss", $prno, $prno);
                        $stmt_item->execute();
                        $res_item = $stmt_item->get_result();
                        while ($item = $res_item->fetch_assoc()):
                            ?>
                            <div class="breakdown-item">
                                <span><?php echo $item['particulareng']; ?></span>
                                <span class="fw-bold">৳<?php echo number_format($item['amount'], 0); ?></span>
                            </div>
                        <?php endwhile;
                        $stmt_item->close(); ?>

                        <div class="text-center mt-3">
                            <button class="btn btn-sm btn-primary rounded-pill px-4"
                                onclick="event.stopPropagation(); epos('<?php echo $prno; ?>')">
                                <i class="bi bi-printer me-1"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-cash-stack display-1"></i>
                <p class="mt-2 fw-bold">No payments recorded yet.</p>
            </div>
        <?php endif;
        $stmt_pr->close(); ?>
    </div>
</main>

<div style="height: 60px;"></div>


<?php include 'footer.php'; ?>
<script>
    // ৪. ডাইনামিক টোটাল আপডেট
    document.getElementById("total_paid_container").innerHTML = `
        <div class="paid-badge shadow-sm">
            <div class="small fw-bold opacity-75 text-uppercase">Total Amount Paid</div>
            <div class="h2 fw-extrabold mb-0">৳ ${new Intl.NumberFormat().format(<?php echo $total_paid_all; ?>)}</div>
        </div>
    `;

    function toggleBreakdown(id) {
        $("#hde" + id).slideToggle(200);
    }



    function epos(prno) {
        // লোডার দেখানো (ঐচ্ছিক কিন্তু ইউজার এক্সপেরিয়েন্সের জন্য ভালো)
        Swal.fire({ title: 'Processing Receipt...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        // ১. ব্যাকএন্ড থেকে তথ্য আনা
        $.post('backend/getprinfo.php', { prno: prno }, function (res) {
            const data = JSON.parse(res);

            if (data.success) {
                // ২. প্রাপ্ত ডাটা দিয়ে URL প্যারামিটার তৈরি করা

                // ৪. রিসিট পেজে রিডাইরেক্ট
                window.location.href = data;
            } else {
                Swal.fire('Error', 'Receipt not found!', 'error');
            }
        });
    }
</script>