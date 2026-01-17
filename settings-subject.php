<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "Subject Setup";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Filter Card (8px Radius) */
    .m3-filter-card {
        background: #F3EDF7; border-radius: 8px; padding: 16px; 
        margin: 12px; border: 1px solid #EADDFF;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* M3 Floating Select (8px Radius) */
    .form-floating > .form-select {
        border-radius: 8px !important; border: 1px solid #79747E;
        background-color: white; font-weight: 600; font-size: 0.9rem;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }
    .form-floating > .form-select:focus { border-color: #6750A4; box-shadow: 0 0 0 1px #6750A4; }

    /* FAB Style Button (8px Radius) */
    .btn-m3-action {
        width: 48px; height: 48px; border-radius: 8px;
        background-color: #6750A4; color: white; border: none;
        display: flex; align-items: center; justify-content: center;
        transition: transform 0.1s;
    }
    .btn-m3-action:active { transform: scale(0.95); }

    /* List Header Label */
    .m3-section-lbl {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
        color: #6750A4; margin: 20px 0 8px 16px; letter-spacing: 1px;
    }

    .loader-container { padding: 50px; text-align: center; color: #6750A4; }
    
    .session-indicator {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-indicator"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>
        
        <div class="m3-filter-card shadow-sm">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-funnel-fill text-primary me-2"></i>
                <span class="small fw-bold text-muted text-uppercase">Configure Scope</span>
            </div>

            <div class="d-flex gap-2">
                <div class="form-floating flex-grow-1">
                    <select class="form-select" id="cls_selector" onchange="loadAssignedSubjects();">
                        <option value="">Choose Class & Section</option>
                        <?php
                        // সুরক্ষিতভাবে এরিয়া/ক্লাস ফেচ করা
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
                    <label for="cls_selector">Class | Section</label>
                </div>
                
                <button class="btn-m3-action shadow-sm" onclick="loadAssignedSubjects();">
                    <i class="bi bi-chevron-right fs-4"></i>
                </button>
            </div>
        </div>

        <div class="m3-section-lbl">Assigned Subjects</div>
        
        <div id="subject-list-block" class="px-1">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-journal-check display-1"></i>
                <p class="fw-bold mt-2">Select a class above to begin</p>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5 opacity-50">
            <i class="bi bi-shield-lock display-1"></i>
            <p class="fw-bold mt-3">Access Denied</p>
            <p class="small">Administrator privileges required.</p>
        </div>
    <?php endif; ?>
</main>

<div style="height: 75px;"></div> <script>
    // সাবজেক্ট লোড করার ফাংশন
    function loadAssignedSubjects() {
        const classId = document.getElementById("cls_selector").value;
        if(!classId) return;

        const dataString = `rootuser=<?php echo $rootuser; ?>&id=${classId}&sccode=<?php echo $sccode; ?>&tail=2`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: dataString,
            beforeSend: function () {
                $('#subject-list-block').html('<div class="loader-container"><div class="spinner-border text-primary"></div><br><small class="fw-bold mt-2 d-block">Fetching Modules...</small></div>');
            },
            success: function (res) {
                $("#subject-list-block").html(res);
            }
        });
    }

    // সাবজেক্ট যুক্ত বা অপসারণ করার ফাংশন (Toggle)
    function toggleSubject(subId, tail) {
        const classId = document.getElementById("cls_selector").value;
        const dataString = `rootuser=<?php echo $rootuser; ?>&id=${classId}&tail=${tail}&sccode=<?php echo $sccode; ?>&sub_id=${subId}`;

        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: dataString,
            success: function (res) {
                $("#subject-list-block").html(res);
                // সাফল্যের সংকেত হিসেবে ছোট্ট নোটিফিকেশন যোগ করা যেতে পারে
            }
        });
    }

    // ডিফল্ট সাবজেক্ট সেটআপ
    function applyDefaultSetup(classId) {
        Swal.fire({
            title: 'Apply Defaults?',
            text: "This will reset subjects to institute standards.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Yes, Reset'
        }).then((result) => {
            if (result.isConfirmed) {
                const dataString = `rootuser=<?php echo $rootuser; ?>&id=${classId}&sccode=<?php echo $sccode; ?>`;
                $.ajax({
                    type: "POST",
                    url: "backend/add-default.php",
                    data: dataString,
                    success: function () {
                        loadAssignedSubjects();
                        Swal.fire({ title: 'Standard Applied!', icon: 'success', timer: 1000, showConfirmButton: false });
                    }
                });
            }
        });
    }

    // অপশনাল (৪র্থ) বিষয় সেটআপ
    function setOptionalSubject(subId) {
        const classId = document.getElementById('cls_selector').value;
        const dataString = `rootuser=<?php echo $rootuser; ?>&id=${subId}&sccode=<?php echo $sccode; ?>&cls=${classId}`;
        
        $.ajax({
            type: "POST",
            url: "backend/set-fourth.php",
            data: dataString,
            success: function (res) {
                $(`#opt-indicator-${subId}`).html(res);
            }
        });
    }
</script>

<?php include 'footer.php'; ?>