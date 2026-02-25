<?php $page_title = "Protocols"; include 'inc.php'; 
$row = $conn->query("SELECT geolat, geolon, dista_differ, time_differ FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc(); ?>

<main class="container py-4">
    <div class="m3-hero-profile mb-4" style="background: linear-gradient(180deg, #455A64 0%, #263238 100%);">
        <h4 class="fw-black m-0">Protocols & GPS</h4>
        <p class="small opacity-75">Device security and attendance range</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-black text-secondary m-0">Active Thresholds</h6>
            <button class="btn btn-dark rounded-pill" data-bs-toggle="modal" data-bs-target="#protoModal">Edit Protocols</button>
        </div>
        <div class="row">
            <div class="col-6 small fw-bold text-muted text-uppercase">GPS Radius: <span class="text-dark d-block fs-5"><?= $row['dista_differ'] ?>m</span></div>
            <div class="col-6 small fw-bold text-muted text-uppercase">Time Buffer: <span class="text-dark d-block fs-5"><?= $row['time_differ'] ?>s</span></div>
        </div>
    </div>
</main>

<div class="modal fade" id="protoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="proForm" class="modal-body p-4">
                <h5 class="fw-black mb-4">System Protocols</h5>
                <div class="row">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>LATITUDE</label><input type="text" name="geolat" value="<?= $row['geolat'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>LONGITUDE</label><input type="text" name="geolon" value="<?= $row['geolon'] ?>" class="m3-clean-input"></div></div>
                </div>
                <div class="m3-input-box mb-3"><label>FENCE RADIUS (METERS)</label><input type="number" name="dista_differ" value="<?= $row['dista_differ'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>TIME BUFFER (SECONDS)</label><input type="number" name="time_differ" value="<?= $row['time_differ'] ?>" class="m3-clean-input"></div>
                <button type="submit" class="btn btn-dark w-100 rounded-pill py-3 fw-black">UPDATE PROTOCOLS</button>
            </form>
        </div>
    </div>
</div>

<script>
$('#proForm').submit(function(e){ e.preventDefault(); $.post('backend/save-institute-info.php', $(this).serialize(), ()=>location.reload()); });
</script>