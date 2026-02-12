<?php
include 'inc.php';


$q = $conn->prepare("
    SELECT t.id, t.tid, t.tname,
           tm.gps_st, tm.bio_st, tm.card_st, tm.manual_st
    FROM teacher t
    LEFT JOIN tattnd_manager tm
        ON tm.id = (
            SELECT id
            FROM tattnd_manager
            WHERE tid = t.tid
              AND sccode = t.sccode
            ORDER BY id DESC
            LIMIT 1
        )
    WHERE t.sccode = ?
");

$q->bind_param("i", $sccode);
$q->execute();
$res = $q->get_result();
?>


<style>
    /* Teacher Card Design */
    .m3-teacher-card {
        background: #fff;
        border-radius: 8px;
        padding: 14px;
        margin: 0 0 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #F0F0F0;
        transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(103, 80, 164, 0.05);
    }

    .m3-teacher-card:active {
        transform: scale(0.97);
        background: var(--m3-tonal);
    }

    .m3-teacher-card.disabled {
        opacity: 0.5;
        filter: grayscale(1);
        border-style: dashed;
    }

    /* Avatar System */
    .teacher-avatar {
        width: 54px;
        height: 54px;
        background: var(--m3-primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.4rem;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.2);
    }

    /* Status Icons - Chip Style */
    .status-pill-box {
        display: flex;
        gap: 6px;
        margin-top: 6px;
    }

    .status-icon-m3 {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    .status-icon-m3.active {
        color: black;
        border: 1px solid gray;
    }

    /* Modal & Accordion Refinement */
    .modal-m3 {
        border-radius: 8px !important;
        border: none;
        overflow: hidden;
    }

    .m3-accordion-item {
        border: 1px solid var(--m3-outline-variant) !important;
        border-radius: 16px !important;
        margin-bottom: 10px;
        overflow: hidden;
        background: #fff;
    }

    .m3-accordion-button {
        font-weight: 800 !important;
        color: var(--m3-on-tonal) !important;
        padding: 16px !important;
        box-shadow: none !important;
    }

    .m3-accordion-button:not(.collapsed) {
        background: var(--m3-tonal) !important;
    }

    /* Floating Field Style for Modal */
    .m3-input-group {
        position: relative;
        margin-top: 10px;
    }

    .m3-input-label {
        position: absolute;
        left: 12px;
        top: -10px;
        background: #fff;
        padding: 0 5px;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
    }

    .m3-field-m {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 2px solid var(--m3-outline-variant);
        outline: none;
        font-weight: 600;
    }

    .m3-field-m:focus {
        border-color: var(--m3-primary);
    }
</style>

<div class="hero-container">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-black m-0">Teacher Directory</h4>
            <p class="small m-0 opacity-75">Manage access & permissions</p>
        </div>
        <i class="bi bi-people-fill display-5 opacity-25"></i>
    </div>
</div>

<div class="container-fluid pb-5">
    <?php while ($r = $res->fetch_assoc()): ?>
        <div class="m3-teacher-card <?= !$r['manual_st'] ? 'disabled' : '' ?>" data-tid="<?= $r['tid'] ?>">
            <div class="teacher-avatar shadow-sm">
                <?= substr($r['tname'], 0, 1) ?>
            </div>

            <div class="flex-grow-1">
                <div class="fw-bold text-dark" style="font-size: 1rem;"><?= $r['tname'] ?></div>
                <div class="text-muted" style="font-size: 0.75rem; font-weight: 600;">ID: <?= $r['tid'] ?></div>

                <div class="status-pill-box">
                    <?php
                    $modes = [
                        ['key' => $r['gps_st'], 'icon' => 'geo-alt-fill', 'title' => 'GPS', 'clr' => 'success'],
                        ['key' => $r['bio_st'], 'icon' => 'fingerprint', 'title' => 'Biometric', 'clr' => 'info'],
                        ['key' => $r['card_st'], 'icon' => 'credit-card', 'title' => 'Card', 'clr' => 'warning'],
                        ['key' => $r['manual_st'], 'icon' => 'hand-index-thumb', 'title' => 'Manual', 'clr' => 'danger']
                    ];
                    foreach ($modes as $m): ?>
                        <div class="status-icon-m3 <?= $m['key'] ? 'active shadow-sm' : '' ?>" title="<?= $m['title'] ?>">
                            <i class="bi bi-<?= $m['icon'] ?> text-<?= $m['clr'] ?>"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dropdown" style="border-radius:50%;">
                <button class="btn btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown"
                    style="width: 38px; height: 38px; border-radius:50%;">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2" style="border-radius: 8px;">
                    <li>
                        <a class="dropdown-item py-2 permBtn fw-bold text-primary" data-tid="<?= $r['tid'] ?>" href="#">
                            <i class="bi bi-shield-lock-fill me-2"></i> Permissions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-black text-primary"><i class="bi bi-shield-check me-2"></i>Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="perm_tid">

                <div class="accordion accordion-flush" id="permAcc">
                    <?php
                    $icons = ['gps' => 'geo-alt', 'bio' => 'fingerprint', 'card' => 'credit-card', 'manual' => 'hand-index-thumb'];
                    foreach ($icons as $x => $icon): ?>
                        <div class="accordion-item m3-accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button m3-accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#col-<?= $x ?>">
                                    <i class="bi bi-<?= $icon ?> me-3 text-primary"></i> <?= strtoupper($x) ?> Access
                                </button>
                            </h2>
                            <div id="col-<?= $x ?>" class="accordion-collapse collapse" data-bs-parent="#permAcc">
                                <div class="accordion-body bg-light rounded-bottom-4">
                                    <div class="m3-input-group mb-4">
                                        <label class="m3-input-label">Access Level</label>
                                        <select class="m3-field-m perm-st" data-type="<?= $x ?>">
                                            <option value="1">✅ Enabled</option>
                                            <option value="0">❌ Disabled</option>
                                        </select>
                                    </div>

                                    <div class="m3-input-group">
                                        <label class="m3-input-label">Reason / Security Note</label>
                                        <input type="text" class="m3-field-m perm-reason"
                                            placeholder="Write reason here...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="btn btn-primary w-100 py-3 mt-4 rounded-pill fw-black shadow" id="savePerm"
                    style="letter-spacing: 1px;">
                    UPDATE ALL PERMISSIONS
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>


<script>

    document.querySelectorAll('.m3-teacher-card').forEach(card => {

        card.addEventListener('click', function () {

            if (card.classList.contains('disabled')) return;

            let tid = card.dataset.tid;

            Swal.fire({
                title: 'Manual Attendance?',
                text: 'Are you sure to set attendance manually?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonColor: '#198754'
            }).then(result => {

                if (!result.isConfirmed) return;

                fetch('ajax/manual-attendance.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        tid: tid
                    })
                })
                    .then(r => r.text())
                    .then(t => {
                        if (t.trim() === 'OK') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Attendance Submitted',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        } else if (t.trim() === 'disabled') {
                            Swal.fire('Disabled', 'Manual attendance is disabled for this teacher.', 'warning');
                        } else if (t.trim() === 'FALSE') {
                            Swal.fire('Submitted', 'Attendance Already Submitted for this teacher.', 'warning');
                        }
                    });

            });

        });

        // ❌ STOP BUBBLING FROM DROPDOWN BUTTON + ITEMS
        document.querySelectorAll('.m3-teacher-card .dropdown').forEach(dd => {

            dd.addEventListener('click', function (e) {
                e.stopPropagation();
            });

        });

    });





    document.querySelectorAll('.permBtn').forEach(b => {
        b.onclick = () => {
            event.stopPropagation();
            perm_tid.value = b.dataset.tid;

            fetch('ajax/load-perm.php?tid=' + b.dataset.tid)
                .then(r => r.json())
                .then(d => {
                    for (let k in d) {
                        document
                            .querySelector(`.perm-st[data-type=${k}]`)
                            .value = d[k].st;
                    }
                    new bootstrap.Modal(permModal).show();
                });
        };
    });

    savePerm.onclick = () => {
        let fd = new FormData();
        fd.append('tid', perm_tid.value);

        document.querySelectorAll('.perm-st').forEach(s => {
            fd.append(s.dataset.type + '_st', s.value);
        });

        fetch('ajax/save-perm.php', { method: 'POST', body: fd })
            .then(r => r.text())
            .then(() => location.reload());
    };

</script>