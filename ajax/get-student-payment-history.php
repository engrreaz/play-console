<?php
include '../inc.light.php';

$stid = $_POST['stid'];


// ১. রশিদের তথ্য ফেচ করা (stpr)
$stmt = $conn->prepare("SELECT * FROM stpr WHERE sccode = ? AND stid = ? ORDER BY prdate DESC, prno DESC");
$stmt->bind_param("ss", $sccode, $stid);
$stmt->execute();
$receipts = $stmt->get_result();

if ($receipts->num_rows == 0) {
    echo '<div class="text-center opacity-50 py-4"><i class="bi bi-info-circle display-4"></i><p>কোন পরিশোধের রেকর্ড পাওয়া যায়নি।</p></div>';
    exit;
}
?>

<div class="history-timeline">
    <?php while ($pr = $receipts->fetch_assoc()):
        $current_prno = $pr['prno'];
        ?>
        <div class="m3-card mb-3 border-0 shadow-sm" style="background: #F7F2FA; border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="badge rounded-pill bg-primary px-3">Receipt: <?= $current_prno ?></span>
                    <div class="small text-muted mt-1 px-1"><i
                            class="bi bi-calendar3 me-1"></i><?= date('d M, Y', strtotime($pr['prdate'])) ?></div>
                </div>
                <div class="text-end">
                    <div class="fw-black text-primary h5 mb-0">৳<?= number_format($pr['amount'], 2) ?></div>
                    <div class="small text-muted" style="font-size: 0.7rem;">Collected by: <?= $pr['entryby'] ?></div>
                </div>
            </div>

            <hr class="my-2 opacity-10">

            <div class="details-section">
                <div class="small fw-bold text-secondary mb-1">Paid Items:</div>
                <?php
                // ২. ওই রশিদের আন্ডারে কি কি আইটেম পেইড হয়েছে তা বের করা (stfinance)
                // pr1no অথবা pr2no এর সাথে ম্যাচ করা
                $stmt_items = $conn->prepare("SELECT particulareng, amount, pr1, month FROM stfinance WHERE (pr1no = ? OR pr2no = ?) AND stid = ? AND sccode = ? and sessionyear LIKE ?");
                $stmt_items->bind_param("sssss", $current_prno, $current_prno, $stid, $sccode, $sessionyear_param);
                $stmt_items->execute();
                $items = $stmt_items->get_result();

                while ($item = $items->fetch_assoc()):
                    ?>
                    <div class="d-flex justify-content-between small py-1 border-bottom border-white">
                        <span><?= $item['particulareng'] ?></span>
                        <span class="fw-bold">৳<?= number_format($item['pr1'], 0) ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<style>
    .fw-black {
        font-weight: 900;
    }

    .history-timeline {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* কাস্টম স্ক্রলবার */
    .history-timeline::-webkit-scrollbar {
        width: 4px;
    }

    .history-timeline::-webkit-scrollbar-thumb {
        background: #6750A4;
        border-radius: 10px;
    }
</style>