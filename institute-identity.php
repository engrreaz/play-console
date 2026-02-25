<?php 
$page_title = "Identity Hub";
include 'inc.php'; 

// ডাটাবেস থেকে সব প্রয়োজনীয় ফিল্ড ফেচ করা
$sql = "SELECT scname, sccategory, short, sccode, rootuser, scadd1, scadd2, ps, dist, postal_code, mobile, scmail, scmail2, scweb, headname, headtitle FROM scinfo WHERE sccode = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<style>
    .m3-section-label {
        font-size: 0.75rem; font-weight: 800; color: #6750A4;
        margin: 20px 0 10px 5px; text-transform: uppercase; letter-spacing: 1px;
    }
    .info-card {
        background: #fff; border-radius: 24px; padding: 20px;
        border: 1px solid #E7E0EC; margin-bottom: 16px;
    }
    .data-label { font-size: 0.65rem; color: #79747E; font-weight: 800; text-transform: uppercase; }
    .data-value { font-size: 0.95rem; color: #1C1B1F; font-weight: 700; display: block; margin-bottom: 12px; }
    .data-value:last-child { margin-bottom: 0; }
</style>

<main class="container py-4">
    <div class="m3-hero-profile mb-4">
        <h4 class="fw-black m-0">Institute Identity</h4>
        <p class="small opacity-75 fw-bold">Manage core branding and institutional credentials</p>
        <button class="btn btn-light rounded-pill px-4 mt-3 fw-black shadow-sm" data-bs-toggle="modal" data-bs-target="#idModal">
            <i class="bi bi-pencil-square me-2"></i>EDIT ALL DETAILS
        </button>
    </div>

    <div class="m3-section-label"><i class="bi bi-bank me-2"></i>Basic Identity</div>
    <div class="info-card shadow-sm">
        <div class="row">
            <div class="col-12">
                <span class="data-label">Official Name</span>
                <span class="data-value fs-5 text-primary"><?= $row['scname'] ?></span>
            </div>
            <div class="col-6">
                <span class="data-label">Short Name</span>
                <span class="data-value"><?= $row['short'] ?></span>
            </div>
            <div class="col-6">
                <span class="data-label">Category</span>
                <span class="data-value"><?= $row['sccategory'] ?></span>
            </div>
            <div class="col-6">
                <span class="data-label">EIIN / SC-Code</span>
                <span class="data-value"><?= $row['sccode'] ?></span>
            </div>
            <div class="col-6">
                <span class="data-label">Root Username</span>
                <span class="data-value font-monospace"><?= $row['rootuser'] ?></span>
            </div>
        </div>
    </div>

    <div class="m3-section-label"><i class="bi bi-geo-alt me-2"></i>Location & Address</div>
    <div class="info-card shadow-sm">
        <div class="row">
            <div class="col-12">
                <span class="data-label">Street Address</span>
                <span class="data-value"><?= $row['scadd1'] . ', ' . $row['scadd2'] ?></span>
            </div>
            <div class="col-4">
                <span class="data-label">Upazila / PS</span>
                <span class="data-value"><?= $row['ps'] ?></span>
            </div>
            <div class="col-4">
                <span class="data-label">District</span>
                <span class="data-value"><?= $row['dist'] ?></span>
            </div>
            <div class="col-4">
                <span class="data-label">Postal Code</span>
                <span class="data-value"><?= $row['postal_code'] ?></span>
            </div>
        </div>
    </div>

    <div class="m3-section-label"><i class="bi bi-globe me-2"></i>Contact & Digital Presence</div>
    <div class="info-card shadow-sm">
        <div class="row">
            <div class="col-6">
                <span class="data-label">Mobile Number</span>
                <span class="data-value"><?= $row['mobile'] ?></span>
            </div>
            <div class="col-6">
                <span class="data-label">Official Website</span>
                <span class="data-value text-primary"><?= $row['scweb'] ?></span>
            </div>
            <div class="col-12">
                <span class="data-label">Primary Email</span>
                <span class="data-value"><?= $row['scmail'] ?></span>
            </div>
            <div class="col-12">
                <span class="data-label">Support/Alt Email</span>
                <span class="data-value"><?= $row['scmail2'] ?: '<i>Not Provided</i>' ?></span>
            </div>
        </div>
    </div>

    <div class="m3-section-label"><i class="bi bi-person-badge me-2"></i>Institutional Leadership</div>
    <div class="info-card shadow-sm">
        <div class="row">
            <div class="col-7">
                <span class="data-label">Head of Institute</span>
                <span class="data-value"><?= $row['headname'] ?></span>
            </div>
            <div class="col-5">
                <span class="data-label">Designation</span>
                <span class="data-value"><?= $row['headtitle'] ?></span>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="idModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black text-primary"><i class="bi bi-pencil-square me-2"></i>Update Registry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="idForm" class="modal-body px-4 pb-4">
                
                <p class="small fw-bold text-muted mb-3 mt-0">GENERAL INFORMATION</p>
                <div class="m3-input-box mb-3"><label>INSTITUTE FULL NAME</label><input type="text" name="scname" value="<?= $row['scname'] ?>" class="m3-clean-input"></div>
                <div class="row g-2">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>SHORT NAME</label><input type="text" name="short" value="<?= $row['short'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>CATEGORY</label><input type="text" name="sccategory" value="<?= $row['sccategory'] ?>" class="m3-clean-input"></div></div>
                </div>

                <p class="small fw-bold text-muted mb-3 mt-4">ADDRESS DETAILS</p>
                <div class="m3-input-box mb-3"><label>STREET ADDRESS (LINE 1)</label><input type="text" name="scadd1" value="<?= $row['scadd1'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-3"><label>STREET ADDRESS (LINE 2)</label><input type="text" name="scadd2" value="<?= $row['scadd2'] ?>" class="m3-clean-input"></div>
                <div class="row g-2">
                    <div class="col-4"><div class="m3-input-box mb-3"><label>UPAZILA/PS</label><input type="text" name="ps" value="<?= $row['ps'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-4"><div class="m3-input-box mb-3"><label>DISTRICT</label><input type="text" name="dist" value="<?= $row['dist'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-4"><div class="m3-input-box mb-3"><label>POSTAL CODE</label><input type="text" name="postal_code" value="<?= $row['postal_code'] ?>" class="m3-clean-input"></div></div>
                </div>

                <p class="small fw-bold text-muted mb-3 mt-4">CONTACT & WEB</p>
                <div class="row g-2">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>MOBILE NO</label><input type="text" name="mobile" value="<?= $row['mobile'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>WEBSITE URL</label><input type="text" name="scweb" value="<?= $row['scweb'] ?>" class="m3-clean-input"></div></div>
                </div>
                <div class="m3-input-box mb-3"><label>PRIMARY EMAIL</label><input type="email" name="scmail" value="<?= $row['scmail'] ?>" class="m3-clean-input"></div>
                <div class="m3-input-box mb-3"><label>ALTERNATIVE EMAIL</label><input type="email" name="scmail2" value="<?= $row['scmail2'] ?>" class="m3-clean-input"></div>

                <p class="small fw-bold text-muted mb-3 mt-4">ADMINISTRATION</p>
                <div class="row g-2">
                    <div class="col-7"><div class="m3-input-box mb-3"><label>HEAD OF INSTITUTE</label><input type="text" name="headname" value="<?= $row['headname'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-5"><div class="m3-input-box mb-3"><label>HEAD TITLE</label><input type="text" name="headtitle" value="<?= $row['headtitle'] ?>" class="m3-clean-input"></div></div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 mt-4 fw-black shadow">
                    <i class="bi bi-cloud-upload-fill me-2"></i>UPDATE REPOSITORY
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
    $('#idForm').submit(function (e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-2"></div> SYNCING...');

        $.post('backend/save-institute-info.php', $(this).serialize(), function (res) {
            if (res.status == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Repository Updated',
                    text: 'Institutional identity data has been synchronized.',
                    timer: 1500,
                    showConfirmButton: false,
                    border_radius: '28px'
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
                submitBtn.prop('disabled', false).html('<i class="bi bi-cloud-upload-fill me-2"></i>UPDATE REPOSITORY');
            }
        }, 'json');
    });
</script>