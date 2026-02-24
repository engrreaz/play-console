<?php
$page_title = "Student ID Generator";
include 'inc.php';

// এক্সেস কন্ট্রোল চেক
$is_admin = in_array($userlevel, ['Administrator', 'Head Teacher', 'Principal']);



// ডাটা সংগ্রহ এবং গ্রুপিং
$grouped_areas = [];
$sql_areas = "SELECT * FROM areas WHERE user='$rootuser' AND sessionyear LIKE '%$sy%' ORDER BY areaname, subarea";
$res_areas = $conn->query($sql_areas);

while ($row = $res_areas->fetch_assoc()) {
    $classname = $row['areaname'];
    $grouped_areas[$classname][] = $row;
}
ksort($grouped_areas); // শ্রেণি অনুযায়ী সর্টিং
?>


<style>
    /* M3 Immersive Hero */
    .id-gen-hero {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        color: white;
        padding: 40px 24px;
        border-radius: 0 0 40px 40px;
        position: relative;
        overflow: hidden;
    }

    .hero-glass-pill {
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Floating Content Area */
    .id-gen-container {
        margin-top: 50px;
        padding: 0 16px;
        position: relative;
        z-index: 10;
    }

    .m3-id-card {
        background: white;
        border-radius: 28px;
        /* M3 Large Shape */
        padding: 24px;
        margin-bottom: 16px;
        border: 1px solid #E7E0EC;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .icon-box-m3 {
        width: 52px;
        height: 52px;
        background: #F3EDF7;
        color: #6750A4;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* M3 Floating Inputs */
    .m3-input-box {
        background: #F7F2FA;
        border-radius: 12px;
        padding: 10px 16px;
        border-bottom: 2px solid #6750A4;
        margin-bottom: 12px;
    }

    .m3-input-box label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #6750A4;
        display: block;
        text-transform: uppercase;
    }

    .m3-input-box input {
        border: none;
        background: transparent;
        width: 100%;
        font-weight: 700;
        outline: none;
    }
</style>

<main>
    <div class="id-gen-hero shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-black mb-1">ID Generator</h2>
                <p class="small opacity-75 fw-bold mb-0">Automated Student Identity Minting</p>
            </div>
            <div class=" session-pill  hero-glass-pill"><?= $sessionyear ?></div>
        </div>
    </div>


    <style>
        .class-segment-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: #6750A4;
            margin: 25px 0 10px 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .class-segment-title::after {
            content: "";
            flex-grow: 1;
            height: 1px;
            background: #EADDFF;
        }

        .m3-section-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 10px;
            border: 1px solid #E7E0EC;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.2s ease;
        }

        .m3-section-card:hover {
            background: #F7F2FA;
            border-color: #6750A4;
        }

        .section-icon {
            width: 44px;
            height: 44px;
            background: #F3EDF7;
            color: #6750A4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
    </style>

    <div class="id-gen-container">
        <?php foreach ($grouped_areas as $class => $sections): ?>
            <div class="class-group mb-4">
                <div class="class-segment-title">
                    <i class="bi bi-mortarboard-fill"></i> CLASS: <?= strtoupper($class) ?>
                </div>

                <div class="row g-2">
                    <?php foreach ($sections as $sec): ?>
                        <div class="col-12">
                            <div class="m3-section-card shadow-sm">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="section-icon shadow-sm">
                                        <i class="bi bi-door-open"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-black m-0 text-dark"><?= $sec['subarea'] ?> Section</h6>
                                        <div class="small text-muted fw-bold">
                                            Range: <?= $sec['rollfrom'] ?> — <?= $sec['rollto'] ?>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-m3-tonal btn-sm rounded-pill px-3 fw-bold"
                                    onclick="openGenModal(<?= $sec['id'] ?>, '<?= $class ?>', '<?= $sec['subarea'] ?>', <?= $sec['rollfrom'] ?>, <?= $sec['rollto'] ?>)">
                                    <i class="bi bi-qr-code me-1"></i> GENERATE
                                </button>
                            </div>
                            <div id="gen<?= $sec['id'] ?>" class="px-2"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="modal fade" id="genModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content m3-modal-container shadow-lg" style="border-radius: 28px;">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="fw-black text-primary m-0" id="m_header">Generate IDs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="m_id">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="m3-input-box">
                                <label>Start Roll</label>
                                <input type="number" id="m_from" placeholder="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="m3-input-box">
                                <label>End Roll</label>
                                <input type="number" id="m_to" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 py-3 rounded-pill fw-black mt-3 shadow" onclick="executeGen()">
                        CONFIRM & GENERATE
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>


<?php include 'footer.php'; ?>

<script>
    function genid(id) {
        const from = document.getElementById("from" + id).value;
        const to = document.getElementById("to" + id).value;
        const statusBox = $('#gen' + id);

        if (from > 0 && to >= from) {
            $.ajax({
                type: "POST",
                url: "backend/generate-stid.php",
                data: {
                    rootuser: '<?= $rootuser ?>',
                    id: id,
                    sccode: '<?= $sccode ?>',
                    from: from,
                    to: to
                },
                beforeSend: function () {
                    statusBox.html(`
                    <div class="alert alert-info rounded-4 border-0 d-flex align-items-center gap-3">
                        <div class="spinner-border spinner-border-sm"></div>
                        <span class="fw-bold small">Generating IDs... Please wait.</span>
                    </div>
                `);
                },
                success: function (html) {
                    statusBox.html(html);
                }
            });
        } else {
            Swal.fire({ icon: 'warning', title: 'Invalid Range', text: 'Roll range is not valid.' });
        }
    }


    const gModal = new bootstrap.Modal(document.getElementById('genModal'));

    function openGenModal(id, cls, sec, from, to) {
        document.getElementById('m_id').value = id;
        document.getElementById('m_from').value = from;
        document.getElementById('m_to').value = to;
        document.getElementById('m_header').innerText = `Generate: ${cls} (${sec})`;
        gModal.show();
    }

    function executeGen() {
        const id = document.getElementById('m_id').value;
        const from = document.getElementById('m_from').value;
        const to = document.getElementById('m_to').value;
        const statusBox = $('#gen' + id);

        if (from > 0 && to >= from) {
            gModal.hide(); // মডাল বন্ধ করা

            $.ajax({
                type: "POST",
                url: "backend/generate-stid.php",
                data: { rootuser: '<?= $rootuser ?>', id: id, sccode: '<?= $sccode ?>', from: from, to: to },
                beforeSend: function () {
                    statusBox.html(`
                    <div class="alert alert-info rounded-4 border-0 d-flex align-items-center gap-2 py-2 mt-2">
                        <div class="spinner-border spinner-border-sm"></div>
                        <span class="fw-bold small">Processing Roll ${from} to ${to}...</span>
                    </div>
                `);
                },
                success: function (html) {
                    statusBox.html(html);
                    // ৩ সেকেন্ড পর মেসেজটি হালকা হয়ে যাবে
                    setTimeout(() => statusBox.find('.alert').fadeOut(), 5000);
                }
            });
        } else {
            Swal.fire('Error', 'Invalid roll range provided.', 'error');
        }
    }


</script>