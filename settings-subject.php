<?php
include 'inc.php'; // এটি header.php এবং DB কানেকশন লোড করবে
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Top App Bar Style */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Selection Card Styling */
    .selection-card {
        background: #F3EDF7; /* M3 Surface Container */
        border-radius: 28px;
        border: none;
        padding: 24px;
        margin: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Modern Select Styling */
    .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #79747E;
        background-color: white;
    }
    
    .form-floating > .form-select:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
    }

    /* Subject List Container */
    .list-header {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 20px 0 10px 24px;
        letter-spacing: 1.2px;
    }

    /* AJAX Loader */
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        color: #6750A4;
    }

    .btn-m3-fab {
        width: 48px; height: 48px;
        border-radius: 12px;
        background-color: #6750A4;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-m3-fab:active { transform: scale(0.9); }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Subject Setup</h4>
                <small class="text-muted">Configure class-wise subjects</small>
            </div>
        </div>
    </div>

    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>
        
        <div class="selection-card shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <i class="bi bi-filter-left"></i>
                </div>
                <h6 class="mb-0 fw-bold">Select Scope</h6>
            </div>

            <div class="d-flex gap-2">
                <div class="form-floating flex-grow-1">
                    <select class="form-select" id="cls" onchange="submit_selection();">
                        <option value="">Choose Class & Section</option>
                        <?php
                        // ১. প্রিপেড স্টেটমেন্ট ব্যবহার করে সুরক্ষিতভাবে এরিয়া ফেচ করা
                        $sy_param = "%$sy%";
                        $stmt = $conn->prepare("SELECT id, areaname, subarea FROM areas WHERE user = ? AND sessionyear LIKE ? ORDER BY idno ASC, id ASC");
                        $stmt->bind_param("ss", $rootuser, $sy_param);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row["id"].'">'.$row["areaname"].' | '.$row["subarea"].'</option>';
                        }
                        $stmt->close();
                        ?>
                    </select>
                    <label for="cls text-muted">Class | Section</label>
                </div>
                
                <button class="btn-m3-fab shadow-sm" onclick="submit_selection();">
                    <i class="bi bi-arrow-right-short fs-2"></i>
                </button>
            </div>
        </div>

        <div class="list-header">Assigned Subjects</div>
        
        <div id="block" class="px-2">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-book-half display-1"></i>
                <p class="mt-2 fw-bold">Select a class to manage subjects</p>
            </div>
        </div>

    <?php else: ?>
        <div class="container text-center py-5">
            <i class="bi bi-shield-lock display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">Only Administrators can access this setup.</p>
        </div>
    <?php endif; ?>

</main>

<div style="height: 70px;"></div>



<script>
    // মেইন সাবমিট ফাংশন
    function submit_selection() {
        const id = document.getElementById("cls").value;
        if(!id) return;

        const infor = `rootuser=<?php echo $rootuser; ?>&id=${id}&sccode=<?php echo $sccode; ?>&tail=2`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: infor,
            beforeSend: function () {
                $('#block').html('<div class="loading-spinner"><div class="spinner-border text-primary" role="status"></div><span class="mt-3 small fw-bold">Loading Subjects...</span></div>');
            },
            success: function (html) {
                $("#block").html(html);
            }
        });
    }

    // সাবজেক্ট অ্যাড/ডিলিট করার ফাংশন
    function adddel(id_val, tail) {
        const class_id = document.getElementById("cls").value;
        const infor = `rootuser=<?php echo $rootuser; ?>&id=${class_id}&tail=${tail}&sccode=<?php echo $sccode; ?>&sub_id=${id_val}`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: infor,
            success: function (html) {
                $("#block").html(html);
                const msg = tail == 1 ? "Subject Added" : "Subject Removed";
                // Toast message could be added here
            }
        });
    }

    // ডিফল্ট সাবজেক্ট সেটআপ
    function defaults(id) {
        Swal.fire({
            title: 'Set Defaults?',
            text: "This will reset subjects to school standard.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Apply'
        }).then((result) => {
            if (result.isConfirmed) {
                const infor = `rootuser=<?php echo $rootuser; ?>&id=${id}&sccode=<?php echo $sccode; ?>`;
                $.ajax({
                    type: "POST",
                    url: "backend/add-default.php",
                    data: infor,
                    success: function (html) {
                        submit_selection(); // লিস্ট রিফ্রেশ
                        Swal.fire('Applied!', 'Default subjects assigned.', 'success');
                    }
                });
            }
        });
    }

    // ৪র্থ বিষয় (Optional Subject) সেটআপ
    function fourth(id) {
        const cls_id = document.getElementById('cls').value;
        const infor = `rootuser=<?php echo $rootuser; ?>&id=${id}&sccode=<?php echo $sccode; ?>&cls=${cls_id}`;
        
        $.ajax({
            type: "POST",
            url: "backend/set-fourth.php",
            data: infor,
            success: function (html) {
                $(`#fff${id}`).html(html);
            }
        });
    }
</script>

<?php include 'footer.php'; ?>