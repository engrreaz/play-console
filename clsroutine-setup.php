<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. ইনপুট প্যারামিটার হ্যান্ডলিং (Secure)
$cls2 = $_GET['cls'] ?? '';
$sec2 = $_GET['sec'] ?? '';
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

    /* Selection Wizard Card */
    .wizard-card {
        background: white;
        border-radius: 28px;
        padding: 24px;
        margin: 15px;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Modern Select/Input Style */
    .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #79747E;
        background-color: transparent;
    }
    .form-floating > .form-select:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
    }

    .btn-m3-primary {
        background-color: #6750A4;
        color: white;
        border-radius: 100px;
        padding: 12px 24px;
        font-weight: 700;
        width: 100%;
        border: none;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.3);
        transition: transform 0.2s;
    }
    .btn-m3-primary:active { transform: scale(0.96); }

    /* Routine Container Label */
    .section-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 24px 20px 10px;
        letter-spacing: 1.2px;
    }

    .loading-block {
        text-align: center;
        padding: 40px;
        color: #6750A4;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="settings_admin.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Routine Setup</h4>
                <small class="text-muted">Class & Period Management</small>
            </div>
        </div>
    </div>

    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>
        
        <div class="wizard-card shadow-sm">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                    <i class="bi bi-ui-checks"></i>
                </div>
                <h6 class="fw-bold mb-0">Configure Scope</h6>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="cls" onchange="goo();">
                    <option value="">Choose Class</option>
                    <?php
                    // ২. প্রিপেড স্টেটমেন্ট ব্যবহার করে ক্লাস লিস্ট ফেচ করা
                    $sy_param = "%$sy%";
                    $stmt_cls = $conn->prepare("SELECT areaname FROM areas WHERE user = ? AND sessionyear LIKE ? GROUP BY areaname ORDER BY idno, id");
                    $stmt_cls->bind_param("ss", $rootuser, $sy_param);
                    $stmt_cls->execute();
                    $res_cls = $stmt_cls->get_result();
                    while ($row = $res_cls->fetch_assoc()) {
                        $ccc = $row["areaname"];
                        $selected = ($ccc == $cls2) ? 'selected' : '';
                        echo "<option value='$ccc' $selected>$ccc</option>";
                    }
                    $stmt_cls->close();
                    ?>
                </select>
                <label for="cls">Select Class</label>
            </div>

            <div class="form-floating mb-4">
                <select class="form-select" id="sec" onchange="goo();">
                    <option value="">Choose Section/Group</option>
                    <?php
                    if ($cls2 != '') {
                        $stmt_sec = $conn->prepare("SELECT subarea FROM areas WHERE user = ? AND sessionyear LIKE ? AND areaname = ? GROUP BY subarea ORDER BY idno, id");
                        $stmt_sec->bind_param("sss", $rootuser, $sy_param, $cls2);
                        $stmt_sec->execute();
                        $res_sec = $stmt_sec->get_result();
                        while ($row = $res_sec->fetch_assoc()) {
                            $s_val = $row["subarea"];
                            $selected = ($s_val == $sec2) ? 'selected' : '';
                            echo "<option value='$s_val' $selected>$s_val</option>";
                        }
                        $stmt_sec->close();
                    }
                    ?>
                </select>
                <label for="sec">Select Section</label>
            </div>

            <button class="btn btn-m3-primary shadow" onclick="submit_routine();">
                <i class="bi bi-calendar3-range me-2"></i> VIEW & EDIT ROUTINE
            </button>
        </div>

        <div class="section-label">Active Timetable</div>
        <div id="block" class="px-2">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-clock-history display-1"></i>
                <p class="mt-2 fw-bold small">Select Class & Section to start</p>
            </div>
        </div>

    <?php else: ?>
        <div class="container text-center py-5">
            <i class="bi bi-shield-lock display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">Access restricted to Administrators.</p>
        </div>
    <?php endif; ?>

</main>

<div style="height: 70px;"></div>



<script>
    // ১. পেজ রিলোড নেভিগেশন
    function goo() {
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;
        window.location.href = `clsroutine-setup.php?cls=${encodeURIComponent(cls)}&sec=${encodeURIComponent(sec)}`;
    }

    // ২. রুটিন ফেচ করার AJAX
    function submit_routine() {
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;

        if(!cls || !sec) {
            Swal.fire('Input Required', 'Please select both Class and Section.', 'warning');
            return;
        }

        const infor = `rootuser=<?php echo $rootuser; ?>&cls=${cls}&sec=${sec}&id=0&action=1`;

        $.ajax({
            type: "POST",
            url: "backend/show-routine.php",
            data: infor,
            beforeSend: function () {
                $('#block').html('<div class="loading-block"><div class="spinner-border text-primary"></div><br><span class="small fw-bold mt-2 d-block">Generating Grid...</span></div>');
            },
            success: function (html) {
                $("#block").html(html);
            }
        });
    }

    // ৩. রুটিন সেল আপডেট করার AJAX (লিস্ট থেকে ট্রিগার হবে)
    function edit(id) {
        const sub = document.getElementById("subj" + id).value;
        const tid = document.getElementById("tid" + id).value;
        const iid = document.getElementById("id" + id).innerText;
        const period = document.getElementById("per" + id).innerText;
        const wday = document.getElementById("wday" + id).innerText;
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;

        const infor = `cls=${cls}&sec=${sec}&sub=${sub}&tid=${tid}&id=${iid}&period=${period}&wday=${wday}`;

        $.ajax({
            type: "POST",
            url: "backend/save-routine.php",
            data: infor,
            beforeSend: function () {
                $('#exe' + id).html('<div class="spinner-border spinner-border-sm text-primary"></div>');
            },
            success: function (html) {
                $('#exe' + id).html('<i class="bi bi-check-circle-fill text-success"></i>');
                // Optional toast message
            },
            error: function() {
                $('#exe' + id).html('<i class="bi bi-exclamation-circle text-danger"></i>');
            }
        });
    }

    // ৪. অটো-লোড যদি URL এ প্যারামিটার থাকে
    window.onload = function() {
        if ('<?php echo $cls2; ?>' !== '' && '<?php echo $sec2; ?>' !== '') {
            submit_routine();
        }
    }
</script>

<?php include 'footer.php'; ?>