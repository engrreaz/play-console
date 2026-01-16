<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
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
        z-index: 1000;
    }

    /* Add Teacher Form Card */
    .add-teacher-card {
        border-radius: 28px;
        border: none;
        background-color: #F3EDF7;
        margin-bottom: 24px;
        transition: 0.3s;
    }
    
    .form-floating > .form-control {
        border-radius: 12px;
        border: 1px solid #79747E;
        background: transparent;
    }

    /* Teacher List Card (M3 Style) */
    .teacher-card {
        background: #fff;
        border-radius: 24px;
        border: none;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .teacher-card:active { transform: scale(0.98); }

    .avatar-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: #EADDFF;
        color: #21005D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .btn-m3 {
        border-radius: 100px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .btn-edit { background-color: #EADDFF; color: #21005D; border: none; }
    .btn-delete { background-color: #F8D7DA; color: #842029; border: none; }
    
    .status-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6750A4;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-4">
        <div class="d-flex align-items-center">
            <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Teacher Manager</h4>
                <small class="text-muted">Manage institution staff profiles</small>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3">
        
        <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>
            <div class="add-teacher-card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between" onclick="shi();" style="cursor:pointer;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
                            <i class="bi bi-person-plus-fill fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-bold">Register New Staff</h6>
                    </div>
                    <i class="bi bi-chevron-down" id="form-icon"></i>
                </div>

                <div id="teacher-form-container" style="display:none;" class="mt-4">
                    <input type="hidden" id="tid" value="">
                    
                    <div class="form-floating mb-3">
                        <input type="text" id="tname" class="form-control" placeholder="Name">
                        <label for="tname">Full Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="pos">
                            <option value="">Select Position</option>
                            <option value="Principal">Principal</option>
                            <option value="Head Teacher">Head Teacher</option>
                            <option value="Asstt. Head Teacher">Asstt. Head Teacher</option>
                            <option value="Senior Teacher">Senior Teacher</option>
                            <option value="Lecturer">Lecturer</option>
                            <option value="Asstt. Teacher">Asstt. Teacher</option>
                            <option value="Office Assistant">Office Assistant</option>
                            <option value="Accountant">Accountant</option>
                        </select>
                        <label for="pos">Designation</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="tel" id="mno" class="form-control" placeholder="Mobile">
                        <label for="mno">Mobile Number</label>
                    </div>

                    <button class="btn btn-primary w-100 btn-m3 py-3 shadow-sm" onclick="submit_teacher();">
                        <i class="bi bi-cloud-check-fill me-2"></i> Save Staff Profile
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <h6 class="text-secondary fw-bold small text-uppercase ms-2 mb-3">Honourable Teaching Staff</h6>
        
        <div id="teacher-list-block">
            <?php
            // ৩. প্রিপেড স্টেটমেন্ট ব্যবহার করে ডাটা ফেচিং (Secure & Optimized)
            $stmt = $conn->prepare("SELECT * FROM teacher WHERE sccode = ? ORDER BY ranks ASC, tid DESC");
            $stmt->bind_param("s", $sccode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $tid2 = $row["tid"];
                    $tname2 = $row["tname"];
                    $pos2 = $row["position"];
                    $mno2 = $row["mobile"];
            ?>
                <div class="teacher-card shadow-sm d-flex align-items-start">
                    <div class="avatar-circle me-3">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="status-badge mb-1">ID: <span id="tid<?php echo $tid2; ?>"><?php echo $tid2; ?></span></div>
                        <h6 class="fw-bold text-dark mb-0 text-truncate" id="tname<?php echo $tid2; ?>"><?php echo $tname2; ?></h6>
                        <div class="text-muted small mb-1" id="pos<?php echo $tid2; ?>"><?php echo $pos2; ?></div>
                        <div class="text-primary small fw-medium" id="mno<?php echo $tid2; ?>"><i class="bi bi-telephone me-1"></i><?php echo $mno2; ?></div>
                        
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-m3 btn-edit py-1 px-3" onclick="edit_teacher(<?php echo $tid2; ?>);">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>
                            
                            <div id="del-group-<?php echo $tid2; ?>">
                                <button class="btn btn-m3 btn-delete py-1 px-3" onclick="confirm_delete(<?php echo $tid2; ?>);">
                                    <i class="bi bi-trash3 me-1"></i> Delete
                                </button>
                            </div>
                            <div id="conf-group-<?php echo $tid2; ?>" style="display:none;">
                                <button class="btn btn-danger btn-m3 py-1 px-3" onclick="final_delete(<?php echo $tid2; ?>);">
                                    Sure?
                                </button>
                                <button class="btn btn-link btn-sm text-muted" onclick="cancel_delete(<?php echo $tid2; ?>);">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
                echo '<div class="text-center py-5 opacity-50"><i class="bi bi-people fs-1"></i><br>No staff members found.</div>';
            endif;
            $stmt->close();
            ?>
        </div>

    </div>
</main>

<div style="height:70px;"></div>

<script>
    // ফর্ম শো/হাইড লজিক
    function shi() {
        const container = document.getElementById("teacher-form-container");
        const icon = document.getElementById("form-icon");
        if (container.style.display === "none") {
            container.style.display = "block";
            icon.className = "bi bi-chevron-up";
        } else {
            container.style.display = "none";
            icon.className = "bi bi-chevron-down";
            clear_form();
        }
    }

    function clear_form() {
        document.getElementById("tid").value = "";
        document.getElementById("tname").value = "";
        document.getElementById("pos").value = "";
        document.getElementById("mno").value = "";
    }

    // সাবমিট লজিক
    function submit_teacher() {
        const tid = document.getElementById("tid").value;
        const tname = document.getElementById("tname").value;
        const pos = document.getElementById("pos").value;
        const mno = document.getElementById("mno").value;

        if(!tname || !pos) {
            Swal.fire('Error', 'Name and Position are required.', 'error');
            return;
        }

        const infor = `rootuser=<?php echo $sccode; ?>&tname=${tname}&pos=${pos}&mno=${mno}&tid=${tid}&action=1`;

        $.ajax({
            type: "POST",
            url: "addeditteacher.php",
            data: infor,
            beforeSend: function () {
                $('#teacher-list-block').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            },
            success: function (html) {
                $("#teacher-list-block").html(html);
                shi(); // ফর্ম বন্ধ করা
                Swal.fire('Success', 'Profile updated successfully.', 'success');
            }
        });
    }

    // এডিট ফাংশন
    function edit_teacher(id) {
        document.getElementById("tid").value = document.getElementById("tid" + id).innerText;
        document.getElementById("tname").value = document.getElementById("tname" + id).innerText;
        document.getElementById("pos").value = document.getElementById("pos" + id).innerText.trim();
        document.getElementById("mno").value = document.getElementById("mno" + id).innerText;
        
        // ফর্ম ওপেন করা
        const container = document.getElementById("teacher-form-container");
        if(container.style.display === "none") shi();
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
        document.getElementById("tname").focus();
    }

    // ডিলিট কনফার্মেশন লজিক
    function confirm_delete(id) {
        document.getElementById("del-group-" + id).style.display = 'none';
        document.getElementById("conf-group-" + id).style.display = 'block';
    }

    function cancel_delete(id) {
        document.getElementById("del-group-" + id).style.display = 'block';
        document.getElementById("conf-group-" + id).style.display = 'none';
    }

    function final_delete(id) {
        const infor = `rootuser=<?php echo $sccode; ?>&tid=${id}&action=0`;
        $.ajax({
            type: "POST",
            url: "addeditteacher.php",
            data: infor,
            success: function (html) {
                $("#teacher-list-block").html(html);
                Swal.fire('Deleted', 'Teacher removed from records.', 'info');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>