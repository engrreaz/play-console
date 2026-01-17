<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে

// সেশন হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
$page_title = "Class & Section";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top App Bar (8px radius bottom) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin: 0;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* M3 Floating Action Button (8px Radius) */
    .fab-add {
        position: fixed; bottom: 85px; right: 20px;
        width: 56px; height: 56px; border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
        background-color: #6750A4; color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
        z-index: 1000; border: none; transition: transform 0.2s;
    }
    .fab-add:active { transform: scale(0.9); }

    /* Form Card (8px Radius) */
    .m3-form-card {
        background: #F3EDF7; border-radius: 8px; border: 1px solid #EADDFF;
        padding: 16px; margin: 12px; display: none; /* Initially hidden */
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* M3 Inputs (8px Radius) */
    .form-floating > .form-control {
        border-radius: 8px !important; border: 1px solid #79747E;
        background: white; font-size: 0.9rem; font-weight: 600;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }
    .form-floating > .form-control:focus { border-color: #6750A4; box-shadow: 0 0 0 1px #6750A4; }

    /* Section Headers */
    .list-header-m3 {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 10px 16px; letter-spacing: 0.8px;
    }

    /* M3 Primary Button (8px Radius) */
    .btn-m3-save {
        background-color: #6750A4; color: white; border-radius: 8px;
        padding: 12px; font-weight: 800; border: none; width: 100%;
        letter-spacing: 0.5px; transition: 0.2s;
    }
    .btn-m3-save:active { transform: scale(0.97); }

    .access-denied {
        padding: 50px 20px; text-align: center; opacity: 0.5;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <?php if (in_array($userlevel, ['Administrator', 'Head Teacher', 'Super Administrator'])): ?>
        
        <button class="fab-add shadow-lg" id="fab-trigger" onclick="toggleAddForm();">
            <i class="bi bi-plus-lg fs-3"></i>
        </button>

        <div class="m3-form-card shadow-sm" id="newblock">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-bold text-dark" id="form-title">Add New Class</div>
                <button type="button" class="btn-close" onclick="toggleAddForm();"></button>
            </div>
            
            <input type="hidden" id="entry_id" value="">
            
            <div class="form-floating mb-3">
                <input type="text" id="cls" class="form-control" placeholder="Class Name">
                <label for="cls">Class Name (e.g. Six, Seven)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" id="sec" class="form-control" placeholder="Section">
                <label for="sec">Section or Group (e.g. A, Science)</label>
            </div>

            <button class="btn-m3-save shadow-sm" onclick="submitClassData();">
                <i class="bi bi-cloud-check-fill me-2"></i> SAVE CLASS INFO
            </button>
        </div>

        <div class="list-header-m3">Structure Overview</div>
        
        <div id="class-list-container" class="px-1">
            <?php include 'backend/settings-class-class-list.php'; ?>
        </div>

    <?php else: ?>
        <div class="access-denied">
            <i class="bi bi-shield-lock display-1"></i>
            <p class="fw-bold mt-3">Access Denied</p>
            <p class="small">Administrative privileges required.</p>
        </div>
    <?php endif; ?>
</main>

<div style="height: 75px;"></div> <script>
    // ফর্ম শো এবং টগল লজিক
    function toggleAddForm() {
        const form = document.getElementById("newblock");
        const fab = document.getElementById("fab-trigger");
        
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = 'block';
            fab.style.display = 'none';
            document.getElementById("form-title").innerText = "Add New Class";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            form.style.display = 'none';
            fab.style.display = 'flex';
            resetFormFields();
        }
    }

    function resetFormFields() {
        document.getElementById("entry_id").value = "";
        document.getElementById("cls").value = "";
        document.getElementById("sec").value = "";
    }

    // AJAX সাবমিশন
    function submitClassData() {
        const id = document.getElementById("entry_id").value;
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;

        if(!cls || !sec) {
            Swal.fire('Input Required', 'Please provide both class and section name.', 'warning');
            return;
        }

        const dataString = `rootuser=<?php echo $sccode; ?>&cls=${encodeURIComponent(cls)}&sec=${encodeURIComponent(sec)}&id=${id}&action=1`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-class.php",
            data: dataString,
            beforeSend: function () {
                $('#class-list-container').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><br><small>Syncing Structure...</small></div>');
            },
            success: function (res) {
                $("#class-list-container").html(res);
                toggleAddForm();
                Swal.fire({ title: 'Success', text: 'Class structure updated.', icon: 'success', timer: 1500, showConfirmButton: false });
            },
            error: function() {
                Swal.fire('Error', 'Could not save data.', 'error');
            }
        });
    }

    // এডিট ফাংশন (লিস্ট পেজ থেকে কল হবে)
    function editClassEntry(id) {
        if(document.getElementById("newblock").style.display === "none") {
            toggleAddForm();
        }
        document.getElementById("form-title").innerText = "Update Class/Section";
        document.getElementById("cls").value = document.getElementById("cls_name_" + id).innerText;
        document.getElementById("sec").value = document.getElementById("sec_name_" + id).innerText;
        document.getElementById("entry_id").value = id;
    }

    // ডিলিট ফাংশন
    function deleteClassEntry(id) {
        Swal.fire({
            title: 'Confirm Delete?',
            text: "All associated data for this class will be affected!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete It'
        }).then((result) => {
            if (result.isConfirmed) {
                const dataString = `rootuser=<?php echo $sccode; ?>&id=${id}&action=0`;
                $.ajax({
                    type: "POST",
                    url: "backend/add-edit-class.php",
                    data: dataString,
                    success: function (res) {
                        $("#class-list-container").html(res);
                        Swal.fire('Deleted', 'Class has been removed.', 'info');
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>