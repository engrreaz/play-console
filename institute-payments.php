<?php
$page_title = "Payment Gateways";
include 'inc.php';
// নির্দিষ্ট ডাটা ফেচ করা
$row = $conn->query("SELECT bkash, nagad, rocket, bank FROM scinfo WHERE sccode = '$sccode'")->fetch_assoc();
?>

<style>
    /* Payment Hero - Orange/Teal Gradient */
    .m3-hero-profile {
        background: linear-gradient(180deg, #EF6C00 0%, #E65100 100%);
        padding: 50px 24px 70px;
        color: #fff;
        border-radius: 0 0 40px 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(239, 108, 0, 0.2);
    }

    /* Gateway Cards */
    .gateway-card {
        background: #fff;
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid #E7E0EC;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    .gateway-card:hover {
        transform: translateY(-3px);
        border-color: #EF6C00;
    }

    .brand-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #F3EDF7;
        padding: 8px;
        object-fit: contain;
    }

    .brand-info {
        flex-grow: 1;
        overflow: hidden;
    }

    .brand-name {
        font-weight: 900;
        color: #1C1B1F;
        font-size: 1.1rem;
    }

    .brand-val {
        font-size: 0.8rem;
        color: #79747E;
        font-weight: 600;
        word-break: break-all;
    }

    /* Action Buttons */
    .btn-tonal-orange {
        background: #FFDDB3;
        color: #291800;
        border: none;
        font-weight: 800;
        border-radius: 100px;
    }

    .btn-tonal-orange:hover {
        background: #FFCC91;
    }
</style>

<main class="container py-4">
    <div class="m3-hero-profile mb-5">
        <div class="m3-icon-circle bg-white text-warning mx-auto mb-3" style="width:64px; height:64px;">
            <i class="bi bi-wallet2 fs-2"></i>
        </div>
        <h4 class="fw-black m-0">Payment Gateways</h4>
        <p class="small opacity-75 fw-bold">Configure automated fee collection systems</p>
    </div>

    <div class="row g-3">
        <?php
        $gateways = [
            'bkash' => ['title' => 'bKash Merchant', 'color' => '#D12053'],
            'nagad' => ['title' => 'Nagad Account', 'color' => '#EC1C24'],
            'rocket' => ['title' => 'Rocket Details', 'color' => '#8C3494'],
            'bank' => ['title' => 'Bank Transfer', 'color' => '#006A6A']
        ];

        foreach ($gateways as $key => $meta): ?>
            <div class="col-12 col-md-6">
                <div class="gateway-card shadow-sm">
                    <div class="brand-logo d-flex align-items-center justify-content-center"
                        style="background: <?= $meta['color'] ?>15;">
                        <i class="bi bi-patch-check-fill fs-3" style="color: <?= $meta['color'] ?>;"></i>
                    </div>
                    <div class="brand-info">
                        <div class="brand-name"><?= $meta['title'] ?></div>
                        <div class="brand-val"><?= $row[$key] ?: 'Not Configured Yet' ?></div>
                    </div>
                    <button class="btn btn-tonal-orange btn-sm px-4" data-bs-toggle="modal"
                        data-bs-target="#modal_<?= $key ?>">
                        EDIT
                    </button>
                </div>
            </div>

            <div class="modal fade" id="modal_<?= $key ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-5 shadow-lg">
                        <form onsubmit="saveGateway(event, '<?= $key ?>')" class="modal-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="m3-icon-circle"
                                    style="background: <?= $meta['color'] ?>15; color: <?= $meta['color'] ?>; width:48px; height:48px;">
                                    <i class="bi bi-gear-fill fs-4"></i>
                                </div>
                                <h5 class="fw-black m-0">Update <?= ucfirst($key) ?></h5>
                            </div>

                            <div class="m3-input-box mb-4">
                                <label><?= strtoupper($key) ?> CONFIGURATION STRING</label>
                                <textarea name="<?= $key ?>" class="m3-clean-input w-100" rows="3"
                                    placeholder="Enter API Key or Account Details"><?= $row[$key] ?></textarea>
                            </div>

                            <div class="alert alert-info rounded-4 border-0 small fw-bold">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Please ensure the credentials match exactly with the provider's dashboard.
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-light rounded-pill flex-grow-1 fw-bold"
                                    data-bs-dismiss="modal">CANCEL</button>
                                <button type="submit"
                                    class="btn btn-m3-primary rounded-pill flex-grow-1 fw-bold shadow">SAVE
                                    SETTINGS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
    function saveGateway(e, key) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = $(form).find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: 'save-institute-info.php',
            type: 'POST',
            data: Object.fromEntries(formData),
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Gateway Updated',
                        text: 'Your payment settings have been synced.',
                        timer: 1500,
                        showConfirmButton: false,
                        border_radius: '28px'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    submitBtn.prop('disabled', false).text('SAVE SETTINGS');
                }
            }
        });
    }
</script>