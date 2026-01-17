<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Teacher Manager";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full Width M3 App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Register Card (M3 Tonal Style - 8px Radius) */
    .m3-register-card {
        background: #F3EDF7; border-radius: 8px; padding: 16px; margin: 12px;
        border: 1px solid #EADDFF; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* M3 Inputs (8px Radius) */
    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 8px !important; border: 1px solid #79747E;
        background: transparent; font-size: 0.9rem; font-weight: 600;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }

    /* Teacher Profile Card (8px Radius) */
    .teacher-item-card {
        background: #fff; border-radius: 8px; padding: 12px; margin: 0 12px 10px;
        border: 1px solid #f0f0f0; display: flex; align-items: flex-start;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: 0.2s;
    }
    .teacher-item-card:active { background-color: #F7F2FA; }

    .staff-avatar {
        width: 50px; height: 50px; border-radius: 8px;
        background: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin-right: 14px; flex-shrink: 0;
    }

    /* Action Buttons (8px Radius) */
    .btn-m3-tool {
        border-radius: 8px; padding: 6px 12px; font-size: 0.7rem;
        font-weight: 800; border: none; text-transform: uppercase;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-m3-edit { background: #EADDFF; color: #21005D; }
    .btn-m3-del { background: #FFEBEE; color: #B3261E; }

    .staff-id-chip {
        font-size: 0.6rem; background: #eee; color: #666;
        padding: 1px 6px; border-radius: 4px; font-weight: 800;
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
        <div class="m3-register-card shadow-sm">
            <div class="d-flex align-items-center justify-content-between" onclick="toggleTeacherForm();" style="cursor:pointer;">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:36px; height:36px;">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                    </div>
                    <div class="fw-bold text-dark">Register New Staff</div>
                </div>
                <i class="bi bi-chevron-down" id="form-toggle-icon"></i>
            </div>

            <div id="teacher-form-ui" style="display:none;" class="mt-4">
                <input type="hidden" id="tid" value="">
                
                <div class="form-floating mb-3">
                    <input type="text" id="tname" class="form-control" placeholder="Name">
                    <label for="tname">Full Legal Name</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="pos">
                        <option value="">Select Designation</option>
                        <option value="Principal">Principal</option>
                        <option value="Head Teacher">Head Teacher</option>
                        <option value="Asstt. Head Teacher">Asstt. Head Teacher</option>
                        <option value="Senior Teacher">Senior Teacher</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="Asstt. Teacher">Asstt. Teacher</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Office Assistant">Office Assistant</option>
                    </select>
                    <label for="pos">Position / Rank</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="tel" id="mno" class="form-control" placeholder="Mobile">
                    <label for="mno">Mobile Number</label>
                </div>

                <button class="btn btn-primary w-100 py-3 shadow-sm fw-bold" style="border-radius: 8px;" onclick="saveTeacherProfile();">
                    <i class="bi bi-cloud-check-fill me-2"></i> UPDATE STAFF RECORDS
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="px-3 mb-3 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Honourable Staff Members</div>

    <div id="teacher-list-data">
        <?php
        $stmt = $conn->prepare("SELECT * FROM teacher WHERE sccode = ? ORDER BY ranks ASC, tid DESC");
        $stmt->bind_param("s", $sccode);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
                $tid_val = $row["tid"];
        ?>
            <div class="teacher-item-card shadow-sm">
                <div class="staff-avatar">
                    <i class="bi bi-person-badge"></i>
                </div>
                
                <div class="flex-grow-1 overflow-hidden">
                    <div class="staff-id-chip mb-1">ID: <span id="tid<?php echo $tid_val; ?>"><?php echo $tid_val; ?></span></div>
                    <div class="fw-bold text-dark text-truncate" id="tname<?php echo $tid_val; ?>"><?php echo $row["tname"]; ?></div>
                    <div class="text-muted small" id="pos<?php echo $tid_val; ?>" style="font-weight: 500;"><?php echo $row["position"]; ?></div>
                    <div class="text-primary small fw-bold mt-1" id="mno<?php echo $tid_val; ?>">
                        <i class="bi bi-telephone-outbound me-1"></i><?php echo $row["mobile"]; ?>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn-m3-tool btn-m3-edit shadow-sm" onclick="editTeacher(<?php echo $tid_val; ?>);">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        
                        <div id="del-box-<?php echo $tid_val; ?>">
                            <button class="btn-m3-tool btn-m3-del shadow-sm" onclick="showDeleteConfirm(<?php echo $tid_val; ?>);">
                                <i class="bi bi-trash3"></i> Remove
                            </button>
                        </div>
                        
                        <div id="conf-box-<?php echo $tid_val; ?>" style="display:none;">
                            <button class="btn btn-danger btn-sm fw-bold rounded-3 py-1" onclick="deleteTeacher(<?php echo $tid_val; ?>);">SURE?</button>
                            <button class="btn btn-link btn-sm text-muted" onclick="cancelDelete(<?php echo $tid_val; ?>);">No</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        else:
            echo '<div class="text-center py-5 opacity-25"><i class="bi bi-people-fill display-1"></i><p class="fw-bold mt-2">No records found.</p></div>';
        endif; $stmt->close();
        ?>
    </div>
</main>

<div style="height: 75px;"></div>

<script>
    function toggleTeacherForm() {
        const ui = document.getElementById("teacher-form-ui");
        const icon = document.getElementById("form-toggle-icon");
        if (ui.style.display === "none") {
            ui.style.display = "block";
            icon.className = "bi bi-chevron-up";
        } else {
            ui.style.display = "none";
            icon.className = "bi bi-chevron-down";
            resetForm();
        }
    }

    function resetForm() {
        document.getElementById("tid").value = "";
        document.getElementById("tname").value = "";
        document.getElementById("pos").value = "";
        document.getElementById("mno").value = "";
    }

    function saveTeacherProfile() {
        const tid = document.getElementById("tid").value;
        const tname = document.getElementById("tname").value;
        const pos = document.getElementById("pos").value;
        const mno = document.getElementById("mno").value;

        if(!tname || !pos) {
            Swal.fire('Required', 'Staff name and position are mandatory.', 'warning');
            return;
        }

        const data = `rootuser=<?php echo $sccode; ?>&tname=${encodeURIComponent(tname)}&pos=${encodeURIComponent(pos)}&mno=${mno}&tid=${tid}&action=1`;

        $.ajax({
            type: "POST",
            url: "addeditteacher.php",
            data: data,
            beforeSend: function () {
                $('#teacher-list-data').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            },
            success: function (res) {
                $("#teacher-list-data").html(res);
                toggleTeacherForm();
                Swal.fire({ title: 'Synced!', icon: 'success', timer: 1500, showConfirmButton: false });
            }
        });
    }

    function editTeacher(id) {
        document.getElementById("tid").value = id;
        document.getElementById("tname").value = document.getElementById("tname" + id).innerText;
        document.getElementById("pos").value = document.getElementById("pos" + id).innerText.trim();
        document.getElementById("mno").value = document.getElementById("mno" + id).innerText;
        
        if(document.getElementById("teacher-form-ui").style.display === "none") toggleTeacherForm();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showDeleteConfirm(id) {
        document.getElementById("del-box-" + id).style.display = 'none';
        document.getElementById("conf-box-" + id).style.display = 'block';
    }

    function cancelDelete(id) {
        document.getElementById("del-box-" + id).style.display = 'block';
        document.getElementById("conf-box-" + id).style.display = 'none';
    }

    function deleteTeacher(id) {
        const data = `rootuser=<?php echo $sccode; ?>&tid=${id}&action=0`;
        $.ajax({
            type: "POST",
            url: "addeditteacher.php",
            data: data,
            success: function (res) {
                $("#teacher-list-data").html(res);
                Swal.fire('Removed', 'Staff member deleted.', 'info');
            }
        });
    }
</script>

<?php include 'footer.php'; ?>