<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$page_title = "Marks Entry Wizard";

// ২. এক্সাম লিস্ট ফেচ করা (Prepared Statement)
$exam_options = "";
$exn_default = 'Annual';
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

// ৩. ক্লাস লিস্ট ফেচ করা
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
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Standard M3 Top Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Selection Card (8px Radius) */
    .selection-card {
        background: #FFFFFF; border-radius: 8px; padding: 20px;
        margin: 12px 8px; border: 1px solid #eee;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Condensed Input Fields */
    .form-floating > .form-select, .form-floating > .form-control {
        border-radius: 8px; border: 1px solid #79747E; background-color: transparent;
        height: 52px; font-size: 0.85rem; font-weight: 600;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }

    .step-header { display: flex; align-items: center; margin-bottom: 16px; color: #6750A4; }
    .step-icon { width: 32px; height: 32px; border-radius: 6px; background: #F3EDF7; display: flex; align-items: center; justify-content: center; margin-right: 10px; }

    /* Button M3 (8px Radius) */
    .btn-m3-primary {
        background-color: #6750A4; color: #FFFFFF; border-radius: 8px;
        padding: 12px; font-weight: 700; border: none; width: 100%;
        transition: transform 0.1s;
    }
    .btn-m3-primary:active { transform: scale(0.97); }

    .loading-hint { font-size: 0.7rem; color: #6750A4; text-align: center; margin-top: 5px; font-weight: 600; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="tools.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;">
            SY: <?php echo $current_session; ?>
        </span>
    </div>
</header>

<main class="pb-5">
    <div class="selection-card shadow-sm">
        <div class="step-header">
            <div class="step-icon"><i class="bi bi-filter-circle-fill"></i></div>
            <h6 class="fw-bold mb-0">Select Parameters</h6>
        </div>

         <?php
            $chain_param = '-c 4 -t Choose Values -u -r -b View List -h exam,subject';
            include 'component/tree-ui.php';
            ?>

        <div class="form-floating mb-3">
            <select class="form-select" id="exam">
                <?php echo $exam_options; ?>
            </select>
            <label for="exam">Examination Name</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" id="classname" onchange="fetchsection();">
                <option value="" disabled selected>Select Class</option>
                <?php echo $class_options; ?>
            </select>
            <label for="classname">Academic Class</label>
        </div>

        <div id="sectionblock">
            <div class="form-floating mb-3">
                <select class="form-select" id="sectionname" disabled>
                    <option value="">Select Class First</option>
                </select>
                <label for="sectionname">Section / Group</label>
            </div>
        </div>

        <div id="subblock">
            <div class="form-floating mb-4">
                <select class="form-select" id="subject" disabled>
                    <option value="">Select Section First</option>
                </select>
                <label for="subject">Subject List</label>
            </div>
        </div>

        <button class="btn-m3-primary shadow-sm" onclick="gob();">
            <i class="bi bi-pencil-square me-2"></i> START MARK ENTRY
        </button>
    </div>

    <div class="px-4 mt-1">
        <div class="d-flex align-items-start text-muted opacity-75">
            <i class="bi bi-info-circle me-2 mt-1 fs-6"></i>
            <p style="font-size: 0.7rem; line-height: 1.3;">
                Ensure all student profiles are up to date for session <?php echo $current_session; ?> before starting mark entry.
            </p>
        </div>
    </div>
</main>

<div style="height: 70px;"></div>

<script>
    function gob() {
        const cls = document.getElementById("classname").value;
        const sec = document.getElementById("sectionname")?.value;
        const sub = document.getElementById("subject")?.value;
        const exam = document.getElementById("exam").value;

        if(!cls || !sec || !sub || !exam) {
            Swal.fire({ title: 'Missing Info', text: 'All fields are mandatory.', icon: 'warning', confirmButtonColor: '#6750A4' });
            return;
        }

        window.location.href = `markentry.php?exam=${exam}&cls=${cls}&sec=${sec}&sub=${sub}&year=<?php echo $current_session; ?>`;
    }

    function fetchsection() {
        const cls = document.getElementById("classname").value;
        $.ajax({
            type: "POST",
            url: "backend/fetch-section-mark.php",
            data: { user: '<?php echo $rootuser; ?>', cls: cls },
            beforeSend: function () {
                $('#sectionblock').html('<div class="loading-hint"><div class="spinner-border spinner-border-sm me-2"></div>Loading...</div>');
            },
            success: function (html) { $("#sectionblock").html(html); }
        });
    }

    // Note: fetchsubject() সাধারণত section dropdown এর onchange এ কল হবে যা fetch-section-mark.php থেকে আসবে।
    function fetchsubject() {
        const cls = document.getElementById("classname").value;
        const sec = document.getElementById("sectionname").value;
        $.ajax({
            type: "POST",
            url: "backend/fetch-subject-mark.php",
            data: { sccode: '<?php echo $sccode; ?>', tid: '<?php echo $userid; ?>', cls: cls, sec: sec },
            beforeSend: function () {
                $('#subblock').html('<div class="loading-hint"><div class="spinner-border spinner-border-sm me-2"></div>Fetching...</div>');
            },
            success: function (html) { $("#subblock").html(html); }
        });
    }
</script>

<?php include 'footer.php'; ?>