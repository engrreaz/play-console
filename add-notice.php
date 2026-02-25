<?php
$page_title = "Notice Board";
include 'inc.php';

// ক্যাটাগরি এবং নোটিশ ফেচ করা
$categories = $conn->query("SELECT category FROM notice_category ORDER BY category ASC")->fetch_all(MYSQLI_ASSOC);
$notices = $conn->query("SELECT * FROM notice WHERE sccode = '$sccode' ORDER BY id DESC");
?>

<style>
    .notice-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white; padding: 50px 24px 80px; border-radius: 0 0 32px 32px; text-align: center;
    }
    .notice-container { margin-top: -50px; padding: 0 16px 100px; position: relative; z-index: 10; }
    
    .m3-notice-card {
        background: #fff; border-radius: 24px; padding: 20px; margin-bottom: 16px;
        border: 1px solid #E7E0EC; transition: 0.3s;
    }
    .m3-notice-card:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0,0,0,0.05); }
    
    .cat-badge { background: #EADDFF; color: #21005D; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 800; }
    .exp-date { font-size: 0.75rem; color: #B3261E; font-weight: 700; }
    
    /* Target Audience Icons */
    .audience-box { display: flex; gap: 8px; margin-top: 12px; }
    .audience-tag { width: 32px; height: 32px; border-radius: 10px; background: #F3EDF7; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: #6750A4; }
    .audience-tag.off { opacity: 0.2; grayscale: 1; }
</style>

<button class="m3-fab-main shadow-lg" onclick="openNoticeModal()">
    <i class="bi bi-plus-lg fs-3"></i>
</button>

<style>
    /* FAB Styling */
    .m3-fab-main {
        position: fixed;
        bottom: 90px;
        right: 25px;
        width: 64px;
        height: 64px;
        border-radius: 16px; /* M3 Squircle Shape */
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }
    .m3-fab-main:hover { transform: scale(1.1) rotate(90deg); background: #311B92; }

    /* M3 Dialog Styles */
    .m3-dialog-content {
        border-radius: 28px !important;
        background-color: #FEF7FF !important;
        border: none;
    }

    .m3-icon-circle {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
    }

    .bg-tonal-purple { background-color: #EADDFF; color: #21005D; }

    /* Modern Clean Input Box */
    .m3-input-box {
        background: #F3EDF7;
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid #E7E0EC;
        transition: 0.3s ease;
    }

    .m3-input-box:focus-within {
        border-color: #6750A4;
        background: #fff;
        box-shadow: 0 0 0 1px #6750A4;
    }

    .m3-label-sm {
        font-size: 0.65rem;
        font-weight: 800;
        color: #6750A4;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 2px;
    }

    .m3-clean-input {
        border: none;
        background: transparent;
        width: 100%;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
        padding: 4px 0;
    }
</style>


<main>
    <div class="notice-hero shadow">
        <h3 class="fw-black mb-1">Notice Repository</h3>
        <p class="small opacity-75 fw-bold mb-0"><?= $notices->num_rows ?> Announcements Published</p>
    </div>

    <div class="notice-container">
        <div id="notice-list-data">
            <?php while($row = $notices->fetch_assoc()): ?>
                <div class="m3-notice-card shadow-sm" id="notice-<?= $row['id'] ?>">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="cat-badge text-uppercase"><?= $row['category'] ?></span>
                        <div class="dropdown">
                            <i class="bi bi-three-dots-vertical text-muted pointer" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                                <li><a class="dropdown-item fw-bold small" onclick="openNoticeModal(<?= htmlspecialchars(json_encode($row)) ?>)">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Notice</a></li>
                                <li><a class="dropdown-item fw-bold small text-danger" onclick="deleteNotice(<?= $row['id'] ?>)">
                                    <i class="bi bi-trash3 me-2"></i> Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="fw-black text-dark mb-1"><?= $row['title'] ?></h5>
                    <p class="small text-muted mb-3"><?= mb_strimwidth($row['descrip'], 0, 100, "...") ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                        <div class="audience-box">
                            <div class="audience-tag <?= $row['teacher'] ? '' : 'off' ?>" title="Teachers"><i class="bi bi-person-badge"></i></div>
                            <div class="audience-tag <?= $row['guardian'] ? '' : 'off' ?>" title="Guardians"><i class="bi bi-people"></i></div>
                            <div class="audience-tag <?= $row['smc'] ? '' : 'off' ?>" title="SMC"><i class="bi bi-shield-check"></i></div>
                        </div>
                        <div class="exp-date">
                            <i class="bi bi-calendar-x me-1"></i>Expires: <?= date('d M', strtotime($row['expdate'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<button class="m3-fab shadow-lg" style="position:fixed; bottom:90px; right:25px;" onclick="openNoticeModal()">
    <i class="bi bi-plus-lg fs-3"></i>
</button>

<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-black text-primary" id="modalTitle">New Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="noticeForm" class="modal-body p-4">
                <input type="hidden" name="id" id="n_id" value="0">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="m3-input-box">
                            <label>CATEGORY</label>
                            <select name="category" id="n_category" class="m3-clean-input bg-transparent border-0 w-100" required>
                                <?php foreach($categories as $cat) echo "<option value='{$cat['category']}'>{$cat['category']}</option>"; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="m3-input-box">
                            <label>EXPIRY DATE</label>
                            <input type="date" name="expdate" id="n_expdate" class="m3-clean-input" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="m3-input-box">
                            <label>NOTICE TITLE</label>
                            <input type="text" name="title" id="n_title" class="m3-clean-input" placeholder="Enter headline" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="m3-input-box">
                            <label>DETAILS DESCRIPTION</label>
                            <textarea name="descrip" id="n_descrip" class="m3-clean-input w-100" rows="4" placeholder="Write full notice here..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="m3-section-title mt-4 mb-2">Display Audience</div>
                <div class="row g-2 mb-4">
                    <?php foreach(['teacher'=>'Teachers','guardian'=>'Guardians','smc'=>'SMC'] as $key=>$label): ?>
                    <div class="col-4">
                        <div class="form-check form-switch bg-light p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                            <label class="small fw-bold m-0"><?= $label ?></label>
                            <input class="form-check-input" type="checkbox" name="<?= $key ?>" id="n_<?= $key ?>" value="1" checked>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black shadow">
                    <i class="bi bi-send-fill me-2"></i>PUBLISH ANNOUNCEMENT
                </button>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content m3-dialog-content shadow-lg">
            
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-circle bg-tonal-purple">
                        <i class="bi bi-megaphone-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-black m-0 text-dark" id="modalTitle">New Announcement</h5>
                        <p class="small text-muted mb-0">Publish official institutional news</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form id="noticeForm" class="modal-body px-4 py-4">
                <input type="hidden" name="id" id="n_id" value="0">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="m3-input-box">
                            <label class="m3-label-sm">NEWS CATEGORY</label>
                            <select name="category" id="n_category" class="m3-clean-input cursor-pointer">
                                <?php foreach($categories as $cat) echo "<option value='{$cat['category']}'>{$cat['category']}</option>"; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="m3-input-box">
                            <label class="m3-label-sm">VISIBLE UNTIL</label>
                            <input type="date" name="expdate" id="n_expdate" class="m3-clean-input" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="m3-input-box">
                            <label class="m3-label-sm">NOTICE HEADLINE</label>
                            <input type="text" name="title" id="n_title" class="m3-clean-input" placeholder="e.g. Annual Sports Week 2026">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="m3-input-box">
                            <label class="m3-label-sm">FULL ANNOUNCEMENT DETAILS</label>
                            <textarea name="descrip" id="n_descrip" class="m3-clean-input w-100" rows="5" placeholder="Write the content here..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="m3-label-sm mb-3">TARGET AUDIENCE</label>
                    <div class="row g-2">
                        <?php foreach(['teacher'=>'Teachers','guardian'=>'Guardians','smc'=>'SMC'] as $key=>$label): ?>
                        <div class="col-4">
                            <div class="form-check form-switch bg-white border p-3 rounded-4 d-flex justify-content-between align-items-center m-0">
                                <label class="small fw-bold m-0 text-dark"><?= $label ?></label>
                                <input class="form-check-input" type="checkbox" name="<?= $key ?>" id="n_<?= $key ?>" value="1" checked>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-m3-primary w-100 rounded-pill py-3 fw-black shadow-sm">
                        <i class="bi bi-send-check-fill me-2"></i>CONFIRM & PUBLISH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
<script>
const nModal = new bootstrap.Modal(document.getElementById('noticeModal'));

function openNoticeModal(data = null) {
    const form = document.getElementById('noticeForm');
    form.reset();
    
    if (data) {
        document.getElementById('modalTitle').innerText = "Edit Announcement";
        document.getElementById('n_id').value = data.id;
        document.getElementById('n_category').value = data.category;
        document.getElementById('n_title').value = data.title;
        document.getElementById('n_descrip').value = data.descrip;
        document.getElementById('n_expdate').value = data.expdate;
        document.getElementById('n_teacher').checked = data.teacher == 1;
        document.getElementById('n_guardian').checked = data.guardian == 1;
        document.getElementById('n_smc').checked = data.smc == 1;
    } else {
        document.getElementById('modalTitle').innerText = "New Announcement";
        document.getElementById('n_id').value = 0;
    }
    nModal.show();
}

$('#noticeForm').on('submit', function(e) {
    e.preventDefault();
    $.post('backend/save-notice.php', $(this).serialize(), function(res) {
        if(res.status == 'success') {
            Swal.fire({ icon: 'success', title: 'Notice Saved', showConfirmButton: false, timer: 1200 })
            .then(() => location.reload());
        }
    }, 'json');
});

function deleteNotice(id) {
    Swal.fire({ title: 'Delete Notice?', icon: 'warning', showCancelButton: true }).then((r) => {
        if(r.isConfirmed) $.post('backend/save-notice.php', { delete_id: id }, () => location.reload());
    });
}
</script>