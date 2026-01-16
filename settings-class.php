<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* App Bar Style */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* M3 Floating Action Button (FAB) */
    .fab-add {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background-color: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.4);
        z-index: 1000;
        border: none;
        transition: transform 0.2s;
    }
    .fab-add:active { transform: scale(0.9); }

    /* Form Card Styling */
    .m3-card-form {
        background: #F3EDF7;
        border-radius: 28px;
        border: none;
        padding: 20px;
        margin: 15px;
        display: none; /* Initially hidden */
    }

    .form-floating > .form-control {
        border-radius: 12px;
        border: 1px solid #79747E;
        background: white;
    }
    
    .form-floating > .form-control:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
    }

    /* List Container */
    .list-header {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 20px 0 10px 20px;
        letter-spacing: 1px;
    }

    .btn-m3-primary {
        background-color: #6750A4;
        color: white;
        border-radius: 100px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Class & Section</h4>
                <small class="text-muted">Manage institution structure</small>
            </div>
        </div>
    </div>

    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>
        
        <button class="fab-add" id="fab-trigger" onclick="showaddnew();">
            <i class="bi bi-plus-lg fs-3"></i>
        </button>

        <div class="m3-card-form shadow-sm" id="newblock">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" id="form-title">Add New Class</h6>
                <button type="button" class="btn-close" onclick="hideform();"></button>
            </div>
            
            <input type="hidden" id="id" value="">
            
            <div class="form-floating mb-3">
                <input type="text" id="cls" class="form-control" placeholder="Class Name">
                <label for="cls">Class Name (e.g. Six, Seven)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" id="sec" class="form-control" placeholder="Section">
                <label for="sec">Section/Group Name (e.g. A, Science)</label>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-m3-primary w-100 shadow-sm" onclick="submit_class();">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Save Changes
                </button>
            </div>
        </div>

        <div class="list-header">Existing Classes & Sections</div>
        
        <div id="block" class="px-2">
            <?php include 'backend/settings-class-class-list.php'; ?>
        </div>

    <?php else: ?>
        <div class="container mt-5 text-center">
            <i class="bi bi-shield-lock display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">Access Denied. Admins only.</p>
        </div>
    <?php endif; ?>

</main>

<div style="height:70px;"></div>

<script>
    // ফর্ম শো/হাইড লজিক
    function showaddnew() {
        document.getElementById("newblock").style.display = 'block';
        document.getElementById("fab-trigger").style.display = 'none';
        document.getElementById("form-title").innerText = "Add New Class";
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideform() {
        document.getElementById("newblock").style.display = 'none';
        document.getElementById("fab-trigger").style.display = 'flex';
        clear_form();
    }

    function clear_form() {
        document.getElementById("id").value = "";
        document.getElementById("cls").value = "";
        document.getElementById("sec").value = "";
    }

    // সাবমিট লজিক (AJAX)
    function submit_class() {
        const id = document.getElementById("id").value;
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;

        if(!cls || !sec) {
            Swal.fire('Error', 'Class and Section are required.', 'error');
            return;
        }

        const infor = `rootuser=<?php echo $rootuser; ?>&cls=${cls}&sec=${sec}&id=${id}&action=1`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-class.php",
            data: infor,
            beforeSend: function () {
                $('#block').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><br>Processing...</div>');
            },
            success: function (html) {
                $("#block").html(html);
                hideform();
                Swal.fire('Success', 'Information saved successfully.', 'success');
            }
        });
    }

    // এডিট ফাংশন (লিস্ট থেকে ট্রিগার হবে)
    function edit(id) {
        showaddnew();
        document.getElementById("form-title").innerText = "Edit Class/Section";
        document.getElementById("cls").value = document.getElementById("cls" + id).innerText;
        document.getElementById("sec").value = document.getElementById("sec" + id).innerText;
        document.getElementById("id").value = id;
    }

    // ডিলিট লজিক
    function del(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This class and its data will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                const infor = `rootuser=<?php echo $rootuser; ?>&id=${id}&action=0`;
                $.ajax({
                    type: "POST",
                    url: "addeditclass.php",
                    data: infor,
                    success: function (html) {
                        $("#block").html(html);
                        Swal.fire('Deleted!', 'Class has been removed.', 'success');
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>