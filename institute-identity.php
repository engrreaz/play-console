<?php $page_title = "Identity";
include 'inc.php';
$row = $conn->query("SELECT * FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc(); ?>

<main class="container py-4">
    <div class="m3-hero-profile mb-4">
        <h4 class="fw-black m-0">Institute Identity</h4>
        <p class="small opacity-75">Manage name, location and branding</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-black text-primary m-0">Basic Information</h6>
            <button class="btn btn-tonal rounded-pill" data-bs-toggle="modal" data-bs-target="#idModal">Edit
                Details</button>
        </div>
        <div class="row small fw-bold">
            <div class="col-12 mb-2 text-muted text-uppercase">Full Name: <span
                    class="text-dark d-block fs-6"><?= $row['scname'] ?></span></div>
            <div class="col-6 mb-2 text-muted text-uppercase">Short Name: <span
                    class="text-dark d-block"><?= $row['short'] ?></span></div>
            <div class="col-6 mb-2 text-muted text-uppercase">Mobile: <span
                    class="text-dark d-block"><?= $row['mobile'] ?></span></div>
            <div class="col-12 text-muted text-uppercase">Address: <span
                    class="text-dark d-block"><?= $row['scadd1'] . ', ' . $row['scadd2'] . ', ' . $row['ps'] . ', ' . $row['dist'] ?></span>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="idModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <form id="idForm" class="modal-body p-4">
                <h5 class="fw-black mb-4 text-primary">Edit Identity</h5>
                <div class="m3-input-box mb-3"><label>INSTITUTE FULL NAME</label><input type="text" name="scname"
                        value="<?= $row['scname'] ?>" class="m3-clean-input"></div>
                <div class="row">
                    <div class="col-6">
                        <div class="m3-input-box mb-3"><label>SHORT NAME</label><input type="text" name="short"
                                value="<?= $row['short'] ?>" class="m3-clean-input"></div>
                    </div>
                    <div class="col-6">
                        <div class="m3-input-box mb-3"><label>MOBILE</label><input type="text" name="mobile"
                                value="<?= $row['mobile'] ?>" class="m3-clean-input"></div>
                    </div>
                </div>
                <div class="m3-input-box mb-3"><label>ADDRESS LINE 1</label><input type="text" name="scadd1"
                        value="<?= $row['scadd1'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-4"><label>ADDRESS LINE 2</label><input type="text" name="scadd2"
                        value="<?= $row['scadd2'] ?>" class="m3-clean-input"></div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</div>

<script>
    $('#idForm').submit(function (e) { e.preventDefault(); saveData($(this)); });
    function saveData(form) {
        $.post('backend/save-institute-info.php', form.serialize(), function (res) {
            if (res.status == 'success') { Swal.fire('Saved', '', 'success').then(() => location.reload()); }
        });
    }
</script>