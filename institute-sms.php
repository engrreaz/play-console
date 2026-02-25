<?php $page_title = "Messaging"; include 'inc.php'; 
$row = $conn->query("SELECT sms_gateway, sms_in FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc(); ?>

<main class="container py-4">
    <div class="m3-hero-profile mb-4" style="background: linear-gradient(180deg, #2E7D32 0%, #1B5E20 100%);">
        <h4 class="fw-black m-0">SMS Gateways</h4>
        <p class="small opacity-75">Configure API and Notification Templates</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h6 class="fw-black text-success mb-3">Gateway URL</h6>
        <div class="bg-dark text-success p-3 rounded-3 font-monospace small mb-3"><?= $row['sms_gateway'] ?></div>
        <button class="btn btn-success rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#gatewayModal">Change Gateway</button>
    </div>
</main>

<div class="modal fade" id="gatewayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="gwForm" class="modal-body p-4">
                <h5 class="fw-black mb-4 text-success">Update Gateway</h5>
                <div class="m3-input-box mb-4"><label>HTTP API URL</label>
                <textarea name="sms_gateway" class="m3-clean-input w-100" rows="4"><?= $row['sms_gateway'] ?></textarea></div>
                <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-black shadow">SAVE API CONFIG</button>
            </form>
        </div>
    </div>
</div>

<script>
$('#gwForm').submit(function(e){ e.preventDefault(); $.post('backend/save-institute-info.php', $(this).serialize(), ()=>location.reload()); });
</script>