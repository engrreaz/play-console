<?php $page_title = "Security"; include 'inc.php'; 
$row = $conn->query("SELECT algorithm, secret_key, api_key FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc(); ?>

<main class="container py-4">
    <div class="m3-hero-profile mb-4" style="background: linear-gradient(180deg, #006064 0%, #00838F 100%);">
        <h4 class="fw-black m-0">Security Setup</h4>
        <p class="small opacity-75">API Keys and Encryption Protocol</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="mb-3">
            <label class="small text-muted fw-bold">API KEY</label>
            <div class="bg-light p-3 rounded-3 font-monospace small border"><?= $row['api_key'] ?: 'NOT_SET' ?></div>
        </div>
        <button class="btn btn-info rounded-pill fw-black w-100 py-3" data-bs-toggle="modal" data-bs-target="#secModal">MANAGE KEYS</button>
    </div>
</main>

<div class="modal fade" id="secModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="secForm" class="modal-body p-4">
                <h5 class="fw-black mb-4 text-info">Update Security</h5>
                <div class="m3-input-box mb-3"><label>API KEY</label><input type="text" name="api_key" value="<?= $row['api_key'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>SECRET KEY</label><input type="text" name="secret_key" value="<?= $row['secret_key'] ?>" class="m3-clean-input"></div>
                <button type="submit" class="btn btn-info w-100 rounded-pill py-3 fw-black">SAVE KEYS</button>
            </form>
        </div>
    </div>
</div>

<script>
$('#secForm').submit(function(e){ e.preventDefault(); $.post('backend/save-institute-info.php', $(this).serialize(), ()=>location.reload()); });
</script>