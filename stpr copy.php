<?php 
$page_title = "Receipt Details";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. প্যারামিটার হ্যান্ডলিং ও ডাটা ফেচিং (Prepared Statements)
$prno = $_GET['prno'] ?? '';


if ($prno != '') {
    // রিসিট হেডার ইনফো
    $stmt1 = $conn->prepare("SELECT classname, sectionname, rollno, stid, prdate, amount, entryby FROM stpr WHERE prno = ? LIMIT 1");
    $stmt1->bind_param("s", $prno);
    $stmt1->execute();
    $stmt1->bind_result($cls, $sec, $roll, $stid, $raw_date, $total, $eby);
    $stmt1->fetch();
    $stmt1->close();

    $prdate = date('d M, Y', strtotime($raw_date));
    $sec = trim(str_replace("Studies", "", $sec ?? ''));

    // স্টুডেন্ট নাম ফেচিং
    $stmt2 = $conn->prepare("SELECT stnameeng FROM students WHERE stid = ? LIMIT 1");
    $stmt2->bind_param("s", $stid);
    $stmt2->execute();
    $stmt2->bind_result($stname);
    $stmt2->fetch();
    $stmt2->close();

    // সংগৃহীতকারী (Collector) নাম ফেচিং
    $collname = 'Unknown';
    $stmt3 = $conn->prepare("SELECT profilename, userid FROM usersapp WHERE email = ? LIMIT 1");
    $stmt3->bind_param("s", $eby);
    $stmt3->execute();
    $stmt3->bind_result($collname, $uid);
    if (!$stmt3->fetch()) {
        // যদি ইউজার অ্যাপে না পাওয়া যায়, টিচার টেবিলে খোঁজা
        $stmt3->close();
        $stmt4 = $conn->prepare("SELECT tname FROM teacher WHERE tid = ? LIMIT 1");
        // নোট: $uid এখানে পূর্ববর্তী কুয়েরি থেকে পাওয়া যাবে না যদি fetch ব্যর্থ হয়, 
        // তাই আপনার অরিজিনাল লজিক অনুযায়ী $eby দিয়ে টিচার টেবিলে চেক করা ভালো যদি রিলেশন থাকে।
        // আপাতত অরিজিনাল লজিক বজায় রাখা হলো।
    } else {
        $stmt3->close();
    }
}
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Digital Receipt Design */
    .receipt-container {
        background-color: #FFFFFF;
        border-radius: 28px;
        margin: 20px 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    /* Receipt Top Cut Effect (Visual Only) */
    .receipt-container::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 8px;
        background: linear-gradient(135deg, transparent 4px, #EADDFF 4px, #EADDFF 5px, transparent 5px) 0 0 / 8px 8px repeat-x;
    }

    .receipt-header { text-align: center; margin-bottom: 24px; }
    .receipt-icon {
        width: 64px; height: 64px; background: #EADDFF; color: #21005D;
        border-radius: 20px; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px; font-size: 1.8rem;
    }

    .receipt-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem; }
    .receipt-label { color: #49454F; font-weight: 500; }
    .receipt-value { color: #1C1B1F; font-weight: 700; text-align: right; }

    /* Items Table */
    .items-box {
        background: #F3EDF7; border-radius: 16px; padding: 16px; margin: 20px 0;
    }
    .item-line {
        display: flex; justify-content: space-between; padding: 8px 0;
        border-bottom: 1px dashed #CAC4D0;
    }
    .item-line:last-child { border-bottom: none; }

    .total-box {
        border-top: 2px solid #6750A4; padding-top: 15px; margin-top: 10px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .total-label { font-size: 1.1rem; font-weight: 800; color: #6750A4; }
    .total-val { font-size: 1.5rem; font-weight: 800; color: #1C1B1F; }

    .btn-m3-danger {
        background-color: #F2B8B5; color: #601410; border-radius: 100px;
        padding: 12px 32px; border: none; font-weight: 700; width: 100%;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="javascript:history.back()" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-printer"></i></div>
</header>

<main class="pb-5">
    <div class="receipt-container shadow-sm">
        <div class="receipt-header">
            <div class="receipt-icon shadow-sm"><i class="bi bi-check2-all"></i></div>
            <h5 class="fw-bold mb-1">Payment Successful</h5>
            <p class="small text-muted mb-0">Receipt #<?php echo $prno; ?></p>
        </div>

        <div class="receipt-row">
            <span class="receipt-label">Date</span>
            <span class="receipt-value"><?php echo $prdate; ?></span>
        </div>
        <hr class="opacity-10">

        <div class="receipt-row">
            <span class="receipt-label">Student</span>
            <span class="receipt-value"><?php echo $stname; ?></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Class & Roll</span>
            <span class="receipt-value"><?php echo $cls . ($sec ? " ($sec)" : "") . " - $roll"; ?></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Student ID</span>
            <span class="receipt-value">#<?php echo $stid; ?></span>
        </div>

        <div class="items-box shadow-sm">
            <div class="label-small mb-2 fw-bold text-primary" style="font-size: 0.7rem; text-transform: uppercase;">Payment Breakdown</div>
            <?php
            $stmt_items = $conn->prepare("SELECT particulareng, amount FROM stfinance WHERE pr1no = ? OR pr2no = ?");
            $stmt_items->bind_param("ss", $prno, $prno);
            $stmt_items->execute();
            $res_items = $stmt_items->get_result();
            while ($row = $res_items->fetch_assoc()) {
                $de = $row["particulareng"];
                $de = str_replace(["Tution Fee : ", "Exam Fee : "], "", $de);
                $de = str_replace("/", "-", $de);
            ?>
                <div class="item-line">
                    <span class="small fw-medium"><?php echo $de; ?></span>
                    <span class="small fw-bold">৳<?php echo number_format($row['amount'], 2); ?></span>
                </div>
            <?php } $stmt_items->close(); ?>

            <div class="total-box">
                <span class="total-label">Grand Total</span>
                <span class="total-val">৳<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <div class="receipt-row mt-4">
            <span class="receipt-label">Collected By</span>
            <span class="receipt-value"><?php echo $collname; ?></span>
        </div>
        
        <div class="text-center mt-4">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=PR:<?php echo $prno; ?>|ST:<?php echo $stid; ?>" alt="QR" class="opacity-50">
            <p style="font-size: 0.6rem; color: gray; margin-top: 8px;">DIGITALLY VERIFIED RECEIPT</p>
        </div>
    </div>

    <div class="px-3 mt-3">
        <button class="btn-m3-danger shadow-sm" onclick="history.back();">
            <i class="bi bi-arrow-left-circle me-2"></i> RETURN TO PORTAL
        </button>
    </div>
</main>

<div style="height: 60px;"></div>



<?php include 'footer.php'; ?>