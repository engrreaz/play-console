<?php 
$page_title = "Receipt Details";
include 'inc.php'; 

// ১. প্যারামিটার হ্যান্ডলিং (আপনার প্রদান করা প্যারামিটার অনুযায়ী)
$prno = $_GET['prno'] ?? '';
$prdate = $_GET['prdate'] ?? '';
$stname = $_GET['stname'] ?? '';
$collname = $_GET['collname'] ?? '';
$cls = $_GET['cls'] ?? '';
$sec = $_GET['sec'] ?? '';
$roll = $_GET['roll'] ?? '';
$stid = $_GET['stid'] ?? '';
$cnt = $_GET['cnt'] ?? 0;
$total = $_GET['total'] ?? 0;


?>

<style>
    body { background-color: #FEF7FF; font-family: 'Roboto', sans-serif; } /* M3 Surface Background */

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
    
    /* Receipt Top Cut Effect */
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
        margin-top: 20px; transition: 0.3s;
    }
    .btn-m3-danger:active { transform: scale(0.95); opacity: 0.8; }
</style>


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
            <span class="receipt-value"><?php echo strtoupper($cls) . " ($sec) - $roll"; ?></span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Student ID</span>
            <span class="receipt-value">#<?php echo $stid; ?></span>
        </div>

        <div class="items-box shadow-sm">
            <div class="label-small mb-2 fw-bold text-primary" style="font-size: 0.7rem; text-transform: uppercase;">Payment Breakdown</div>
            
            <?php
            // আপনার প্রদান করা ডাইনামিক আইটেম লুপ
            for ($a = 1; $a <= $cnt; $a++) {
                $item_txt = $_GET['item' . $a . 'txt'] ?? 'Item '.$a;
                $item_taka = $_GET['item' . $a . 'taka'] ?? 0;
            ?>
                <div class="item-line">
                    <span class="small fw-medium"><?php echo $item_txt; ?></span>
                    <span class="small fw-bold">৳<?php echo number_format($item_taka, 2); ?></span>
                </div>
            <?php } ?>

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
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=PR:<?php echo $prno; ?>|ST:<?php echo $stid; ?>|AMT:<?php echo $total; ?>" alt="QR" class="opacity-50">
            <p style="font-size: 0.6rem; color: gray; margin-top: 8px; letter-spacing: 1px;">DIGITALLY VERIFIED RECEIPT</p>
        </div>
    </div>

    <div class="px-3">
        <button class="btn-m3-danger shadow-sm" onclick="history.back();">
            <i class="bi bi-arrow-left-circle me-2"></i> RETURN TO PORTAL
        </button>
    </div>
</main>

<div style="height: 60px;"></div>

<?php include 'footer.php'; ?>


<script>
    epos(<?php echo $prno; ?>);
</script>