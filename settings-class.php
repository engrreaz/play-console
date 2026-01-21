<?php
/**
 * Class & Section Manager - M3-EIM-Floating Modal Style
 * Standards: 8px Radius | Floating Labels | Modal UI | AJAX Sync
 */
$page_title = "Class & Section";
include 'inc.php'; 

// সেশন হ্যান্ডলিং
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* M3 App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* M3 Floating Action Button (8px Radius) */
    .m3-fab-add {
        position: fixed; bottom: 85px; right: 20px;
        width: 56px; height: 56px; border-radius: 8px !important;
        background-color: #6750A4; color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        z-index: 1000; border: none; transition: transform 0.2s;
    }
    .m3-fab-add:active { transform: scale(0.9); }

    /* Modal Styling (M3-EIM-Floating) */
    .modal-content { border-radius: 8px !important; border: none; background: #fff; }
    .modal-header { border-bottom: 1px solid #F3EDF7; padding: 16px 20px; }
    .modal-body { padding: 24px 20px; }
    
    /* M3-EIM-Floating Input Styles */
    .m3-floating-group { position: relative; margin-bottom: 20px; }
    .m3-field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6750A4; font-size: 1.2rem; z-index: 10; }
    .m3-floating-label { position: absolute; left: 44px; top: -10px; background: #fff; padding: 0 6px; font-size: 0.75rem; font-weight: 700; color: #6750A4; z-index: 15; text-transform: uppercase; }
    .m3-input-floating { width: 100%; height: 52px; padding: 12px 16px 12px 48px; font-size: 0.95rem; font-weight: 600; border: 2px solid #CAC4D0; border-radius: 8px !important; }
    .m3-input-floating:focus { border-color: #6750A4; outline: none; }

    .btn-m3-submit {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white; border-radius: 8px !important;
        padding: 12px; font-weight: 800; border: none; width: 100%;
        letter-spacing: 0.5px; box-shadow: 0 2px 6px rgba(103, 80, 164, 0.2);
    }

    .m3-list-header {
        font-size: 0.65rem; font-weight: 900; text-transform: uppercase; 
        color: #6750A4; margin: 24px 0 12px 20px; letter-spacing: 1px;
    }
</style>


<main class="pb-5 mt-2">
    <?php if (in_array($userlevel, ['Administrator', 'Head Teacher', 'Super Administrator'])): ?>
        
        <button class="m3-fab-add shadow-lg" onclick="openClassModal();">
            <i class="bi bi-plus-lg fs-3"></i>
        </button>

        <div class="m3-list-header">Institutional Structure</div>
        
        <div id="class-list-container">
            <?php include 'backend/settings-class-class-list.php'; ?>
        </div>

    <?php else: ?>
        <div class="text-center py-5 opacity-50">
            <i class="bi bi-shield-lock display-1"></i>
            <h5 class="fw-bold mt-3">Access Restricted</h5>
        </div>
    <?php endif; ?>
</main>

<div class="modal fade" id="classModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-black" id="modalTitle">Register Class</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="entry_id" value="">
                
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Class Name</label>
                    <i class="bi bi-mortarboard m3-field-icon"></i>
                    <input type="text" id="cls" class="m3-input-floating" placeholder="e.g. Six, Seven">
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Section / Group</label>
                    <i class="bi bi-collection m3-field-icon"></i>
                    <input type="text" id="sec" class="m3-input-floating" placeholder="e.g. A, Science">
                </div>

                <div class="small text-muted mb-4 px-2" style="font-size: 0.65rem;">
                    <i class="bi bi-info-circle me-1"></i> This structure will define student enrollment blocks.
                </div>

                <button class="btn-m3-submit shadow-sm" onclick="submitClassData();">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> SAVE STRUCTURE
                </button>
            </div>
        </div>
    </div>
</div>

<div style="height: 80px;"></div>

<?php 
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php'; 
?>

<script>
    const classModal = new bootstrap.Modal(document.getElementById('classModal'));

    function openClassModal() {
        document.getElementById("entry_id").value = "";
        document.getElementById("cls").value = "";
        document.getElementById("sec").value = "";
        document.getElementById("modalTitle").innerText = "Register New Class";
        classModal.show();
    }

    function editClassEntry(id) {
        document.getElementById("entry_id").value = id;
        document.getElementById("modalTitle").innerText = "Update Class Structure";
        document.getElementById("cls").value = document.getElementById("cls_name_" + id).innerText.trim();
        document.getElementById("sec").value = document.getElementById("sec_name_" + id).innerText.trim();
        classModal.show();
    }

    function submitClassData() {
        const id = document.getElementById("entry_id").value;
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;

        if(!cls || !sec) {
            Swal.fire('Required', 'Class and Section names are mandatory.', 'warning');
            return;
        }

        const dataString = `rootuser=<?php echo $sccode; ?>&cls=${encodeURIComponent(cls)}&sec=${encodeURIComponent(sec)}&id=${id}&action=1`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-class.php",
            data: dataString,
            beforeSend: function () {
                $('#class-list-container').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><br><small class="fw-bold mt-2 d-block">Syncing Database...</small></div>');
            },
            success: function (res) {
                $("#class-list-container").hide().html(res).fadeIn(300);
                classModal.hide();
                Swal.fire({ title: 'Success!', text: 'Academic structure updated.', icon: 'success', timer: 1500, showConfirmButton: false });
            }
        });
    }

    function deleteClassEntry(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Removing this class may affect assigned student records!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/add-edit-class.php",
                    data: `rootuser=<?php echo $sccode; ?>&id=${id}&action=0`,
                    success: function (res) {
                        $("#class-list-container").html(res);
                        Swal.fire('Deleted', 'Structure has been removed.', 'info');
                    }
                });
            }
        });
    }
</script>