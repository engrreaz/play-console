<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. এক্সাম লিস্ট ফেচ করা (Prepared Statement)
$exam_options = "";
$exn_default = 'Annual';
$sy_param = "%$sy%";

$stmt_ex = $conn->prepare("SELECT examtitle FROM examlist WHERE sccode = ? AND sessionyear LIKE ? ORDER BY id ASC");
$stmt_ex->bind_param("ss", $sccode, $sy_param);
$stmt_ex->execute();
$res_ex = $stmt_ex->get_result();
while ($row = $res_ex->fetch_assoc()) {
    $exname = $row["examtitle"];
    $selected = ($exname == $exn_default) ? "selected" : "";
    $exam_options .= "<option value='$exname' $selected>$exname</option>";
}
$stmt_ex->close();

// ২. ক্লাস লিস্ট ফেচ করা
$class_options = "";
$stmt_cl = $conn->prepare("SELECT areaname FROM areas WHERE sessionyear LIKE ? AND user = ? GROUP BY areaname ORDER BY idno ASC");
$stmt_cl->bind_param("ss", $sy_param, $rootuser);
$stmt_cl->execute();
$res_cl = $stmt_cl->get_result();
while ($row = $res_cl->fetch_assoc()) {
    $aname = $row["areaname"];
    $class_options .= "<option value='$aname'>$aname</option>";
}
$stmt_cl->close();
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

    /* Form Container Card */
    .selection-card {
        background: #FFFFFF;
        border-radius: 28px;
        padding: 24px;
        margin: 15px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Floating Labels M3 Style */
    .form-floating > .form-select, .form-floating > .form-control {
        border-radius: 16px;
        border: 1px solid #79747E;
        background-color: transparent;
    }

    .form-floating > .form-select:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
    }

    /* Step Indicator Icon */
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background-color: #EADDFF;
        color: #21005D;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    /* Button M3 Style */
    .btn-m3-primary {
        background-color: #6750A4;
        color: #FFFFFF;
        border-radius: 100px;
        padding: 14px 24px;
        font-weight: 700;
        border: none;
        width: 100%;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.3);
        transition: transform 0.2s;
    }
    .btn-m3-primary:active { transform: scale(0.96); opacity: 0.9; }

    .loading-overlay {
        font-size: 0.85rem;
        color: #6750A4;
        font-weight: 600;
        text-align: center;
        padding: 10px;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="tools.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Marks Entry</h4>
                <small class="text-muted">Result Management Wizard</small>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="selection-card shadow-sm">
            <div class="d-flex align-items-center mb-4">
                <div class="step-icon">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark">Selection Criteria</h6>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="exam">
                    <?php echo $exam_options; ?>
                </select>
                <label for="exam" class="text-muted"><i class="bi bi-calendar-event me-2"></i>Select Examination</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="classname" onchange="fetchsection();">
                    <option value="" disabled selected>Choose a Class</option>
                    <?php echo $class_options; ?>
                </select>
                <label for="classname" class="text-muted"><i class="bi bi-mortarboard me-2"></i>Academic Class</label>
            </div>

            <div id="sectionblock">
                <div class="form-floating mb-3">
                    <select class="form-select" id="sectionname" disabled>
                        <option value="">Select Class First</option>
                    </select>
                    <label for="sectionname" class="text-muted"><i class="bi bi-diagram-3 me-2"></i>Section/Group</label>
                </div>
            </div>

            <div id="subblock">
                <div class="form-floating mb-4">
                    <select class="form-select" id="subject" disabled>
                        <option value="">Select Section First</option>
                    </select>
                    <label for="subject" class="text-muted"><i class="bi bi-book me-2"></i>My Subjects</label>
                </div>
            </div>

            <button class="btn-m3-primary shadow" onclick="gob();">
                <i class="bi bi-arrow-right-circle-fill me-2"></i> PROCEED TO ENTRY
            </button>
        </div>

        <div class="px-4 mt-2">
            <div class="d-flex align-items-start text-muted">
                <i class="bi bi-info-circle me-2 mt-1"></i>
                <p style="font-size: 0.75rem; line-height: 1.4;">
                    Only subjects assigned to your ID will appear in the list. For corrections, contact your system administrator.
                </p>
            </div>
        </div>
    </div>
</main>

<div style="height: 70px;"></div>



<script>
    // মেইন নেভিগেশন লজিক
    function gob() {
        const cls = document.getElementById("classname").value;
        const sec = document.getElementById("sectionname")?.value;
        const sub = document.getElementById("subject")?.value;
        const exam = document.getElementById("exam").value;

        if(!cls || !sec || !sub || !exam) {
            Swal.fire({
                title: 'Selection Required',
                text: 'Please fill out all fields before proceeding.',
                icon: 'warning',
                confirmButtonColor: '#6750A4'
            });
            return;
        }

        const tail = `?exam=${exam}&cls=${cls}&sec=${sec}&sub=${sub}&assess=n/a`;
        window.location.href = "markentry.php" + tail;
    }

    // সেকশন ফেচ করার AJAX
    function fetchsection() {
        const cls = document.getElementById("classname").value;
        const infor = `user=<?php echo $rootuser; ?>&cls=${cls}`;

        $.ajax({
            type: "POST",
            url: "backend/fetch-section-mark.php",
            data: infor,
            beforeSend: function () {
                $('#sectionblock').html('<div class="loading-overlay"><div class="spinner-border spinner-border-sm me-2"></div> Fetching Sections...</div>');
            },
            success: function (html) {
                $("#sectionblock").html(html);
            }
        });
    }

    // সাবজেক্ট ফেচ করার AJAX
    function fetchsubject() {
        const cls = document.getElementById("classname").value;
        const sec = document.getElementById("sectionname").value;
        const infor = `sccode=<?php echo $sccode; ?>&tid=<?php echo $userid; ?>&cls=${cls}&sec=${sec}`;

        $.ajax({
            type: "POST",
            url: "backend/fetch-subject-mark.php",
            data: infor,
            beforeSend: function () {
                $('#subblock').html('<div class="loading-overlay"><div class="spinner-border spinner-border-sm me-2"></div> Retriving Subjects...</div>');
            },
            success: function (html) {
                $("#subblock").html(html);
            }
        });
    }
</script>

<?php include 'footer.php'; ?>