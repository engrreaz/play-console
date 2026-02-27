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
        font-size: 0.75rem;
        font-weight: 800;
        color: #6750A4;
        margin: 20px 0 10px 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-card {
        background: #fff;
        border-radius: 24px;
        padding: 20px;
        border: 1px solid #E7E0EC;
        margin-bottom: 16px;
    }

    .data-label {
        font-size: 0.65rem;
        color: #79747E;
        font-weight: 800;
        text-transform: uppercase;
    }

    .data-value {
        font-size: 0.95rem;
        color: #1C1B1F;
        font-weight: 700;
        display: block;
        margin-bottom: 12px;
    }

    .data-value:last-child {
        margin-bottom: 0;
    }
</style>


<style>
    /* এই অংশটুকু আপনার গ্লোবাল সিএসএস ফাইলে একবার থাকলেই হবে */
    .m3-hero-card {
        background: var(--m3-primary-container);
        border-radius: var(--m3-radius-lg);
        /* 28px */
        border: none;
        transition: transform 0.3s ease;
    }

    .btn-m3-primary {
        background-color: var(--m3-primary);
        color: var(--m3-on-primary);
        border-radius: var(--m3-radius-pill);
        /* 100px */
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-m3-primary:hover {
        background-color: #4F378B;
        /* Darker primary */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .btn-m3-primary:active {
        transform: scale(0.95);
    }
</style>


<style>
    /* Modal Specific Enhancements using Global Tokens */
    .modal-content {
        background-color: var(--m3-surface);
        border-radius: 16px;
        /* 28px */
    }

    .m3-form-group-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--m3-primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        padding-left: 4px;
    }

    .m3-icon-container {
        width: 48px;
        height: 48px;
        background: var(--m3-primary-container);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Reusing Global Input Styles */
    .m3-input-box {
        position: relative;
        border: 1px solid var(--m3-outline);
        border-radius: var(--m3-radius-sm);
        /* 12px */
        padding: 10px 16px;
        background: #FFFFFF;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .m3-input-box:focus-within {
        border: 2px solid var(--m3-primary);
        background: var(--m3-surface);
    }

    .m3-input-box label {
        position: absolute;
        top: -10px;
        left: 12px;
        background: #FFFFFF;
        /* Matches container background */
        padding: 0 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--m3-primary);
    }

    .m3-clean-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 1rem;
        background: transparent;
        color: var(--m3-on-surface);
    }

    .shadow-24 {
        box-shadow: 0px 11px 15px -7px rgba(0, 0, 0, 0.2),
            0px 24px 38px 3px rgba(0, 0, 0, 0.14),
            0px 9px 46px 8px rgba(0, 0, 0, 0.12);
    }
</style>


<main class="container py-4">
    <div class="m3-hero-card shadow-smx p-4 p-md-5 mb-4 position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 mt-n5 me-n5 opacity-25"
            style="width: 200px; height: 200px; background: var(--m3-primary); border-radius: 50%;"></div>

        <div class="position-relative">
            <h2 class="fw-bold m-0" style="color: var(--m3-on-primary-container);">Institute Identity</h2>
            <p class="mt-2 opacity-75 fw-medium" style="max-width: 500px; color: var(--m3-on-primary-container);">
                Manage core branding, institutional credentials, and global identification settings.
            </p>

            <button class="btn btn-m3-primary mt-3 px-4 py-2 d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#idModal">
                <i class="bi bi-pencil-square me-2 fs-5"></i>
                <span class="fw-bold">Edit All Details</span>
            </button>
        </div>
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
        <div class="modal-content shadow-24">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center">
                    <div class="m3-icon-container me-3">
                        <i class="bi bi-pencil-square fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--m3-on-surface);">Update Institute Info</h5>
                        <p class="small text-muted m-0">Synchronize institutional data</p>
                    </div>
                </div>
                <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="idForm" class="modal-body px-4 pb-4">

                <div class="m3-form-group-label">General Information</div>
                <div class="m3-floating-group mb-3">
                    <label class="m3-floating-label">Institute Full Name</label>
                    <i class="bi bi-mortarboard m3-field-icon"></i>
                    <input type="text" name="scname" value="<?= $row['scname'] ?>" class="m3-input-floating"
                        placeholder="e.g. Dhaka Model College">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="m3-floating-group">
                            <label class="m3-floating-label">Short Name</label>
                            <i class="bi bi-award m3-field-icon"></i>
                            <input type="text" name="short" value="<?= $row['short'] ?>" class="m3-input-floating">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="m3-floating-group">
                            <label class="m3-floating-label">Category</label>
                            <i class="bi bi-tag m3-field-icon"></i>
                            <select name="sccategory" class="m3-select-floating">
                                <option value="">Select Category</option>
                                <option value="School" <?= $row['sccategory'] == 'School' ? 'selected' : '' ?>>School
                                </option>
                                <option value="College" <?= $row['sccategory'] == 'College' ? 'selected' : '' ?>>College
                                </option>
                                <option value="University" <?= $row['sccategory'] == 'University' ? 'selected' : '' ?>>
                                    University</option>
                                <option value="Other" <?= $row['sccategory'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="m3-form-group-label mt-4">Address Details</div>
                <div class="m3-floating-group mb-3">
                    <label class="m3-floating-label">Street Address (Line 1)</label>
                    <i class="bi bi-geo-alt m3-field-icon"></i>
                    <input type="text" name="scadd1" value="<?= $row['scadd1'] ?>" class="m3-input-floating">
                </div>
                <div class="m3-floating-group mb-3">
                    <label class="m3-floating-label">Street Address (Line 2)</label>
                    <i class="bi bi-geo-alt m3-field-icon"></i>
                    <input type="text" name="scadd2" value="<?= $row['scadd2'] ?>" class="m3-input-floating">
                </div>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="m3-floating-group"><label class="m3-floating-label">Upazila/PS</label><i
                                class="bi bi-geo-alt m3-field-icon"></i><input type="text" name="ps"
                                value="<?= $row['ps'] ?>" class="m3-input-floating"></div>
                    </div>
                    <div class="col-4">
                        <div class="m3-floating-group"><label class="m3-floating-label">District</label><i
                                class="bi bi-geo-alt m3-field-icon"></i><input type="text" name="dist"
                                value="<?= $row['dist'] ?>" class="m3-input-floating"></div>
                    </div>
                    <div class="col-4">
                        <div class="m3-floating-group"><label class="m3-floating-label">Postal Code</label><i
                                class="bi bi-mailbox m3-field-icon"></i><input type="text" name="postal_code"
                                value="<?= $row['postal_code'] ?>" class="m3-input-floating"></div>
                    </div>
                </div>

                <div class="m3-form-group-label mt-4">Contact & Presence</div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="m3-floating-group"><label class="m3-floating-label">Mobile No</label><i
                                class="bi bi-phone m3-field-icon"></i><input type="text" name="mobile"
                                value="<?= $row['mobile'] ?>" class="m3-input-floating"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="m3-floating-group"><label class="m3-floating-label">Website URL</label><i
                                class="bi bi-globe m3-field-icon"></i><input type="text" name="scweb"
                                value="<?= $row['scweb'] ?>" class="m3-input-floating"></div>
                    </div>
                </div>
                <div class="m3-floating-group mb-3">
                    <label class="m3-floating-label">Primary Email</label>
                    <i class="bi bi-envelope m3-field-icon"></i>
                    <input type="email" name="scmail" value="<?= $row['scmail'] ?>" class="m3-input-floating">
                </div>

                <button type="submit" class="btn btn-m3-primary w-100 py-3 mt-4 shadow-sm">
                    <i class="bi bi-cloud-upload-fill me-2"></i> Update Repository
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