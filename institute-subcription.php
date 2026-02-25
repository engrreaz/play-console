<?php $page_title = "Subscription"; include 'inc.php'; 
$row = $conn->query("SELECT package_name, expire, sms_balance, account_balance FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc(); ?>

<main class="container py-4">
    <div class="m3-hero-profile mb-4" style="background: linear-gradient(180deg, #7B1FA2 0%, #4A148C 100%);">
        <h4 class="fw-black m-0">Subscription</h4>
        <p class="small opacity-75">Plan: <?= $row['package_name'] ?> | Exp: <?= date('d M, Y', strtotime($row['expire'])) ?></p>
    </div>

    <div class="row g-3">
        <div class="col-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <small class="text-muted fw-bold">SMS BALANCE</small>
                <h2 class="fw-black text-success mt-1"><?= $row['sms_balance'] ?></h2>
                <button class="btn btn-sm btn-outline-success rounded-pill mt-2" data-bs-toggle="modal" data-bs-target="#smsModal">Update Credits</button>
            </div>
        </div>
        <div class="col-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <small class="text-muted fw-bold">ACC. BALANCE</small>
                <h2 class="fw-black text-primary mt-1">৳<?= $row['account_balance'] ?></h2>
                <button class="btn btn-sm btn-outline-primary rounded-pill mt-2" data-bs-toggle="modal" data-bs-target="#accModal">Update Balance</button>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="accModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="accForm" class="modal-body p-4">
                <h5 class="fw-black mb-4">Edit Account Balance</h5>
                <div class="m3-input-box mb-4"><label>CURRENT BALANCE (৳)</label><input type="number" name="account_balance" value="<?= $row['account_balance'] ?>" class="m3-clean-input"></div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black">CONFIRM UPDATE</button>
            </form>
        </div>
    </div>
</div>

<script>
$('#accForm').submit(function(e){ e.preventDefault(); $.post('backend/save-institute-info.php', $(this).serialize(), ()=>location.reload()); });
</script>