<?php
// ১. সেশন এবং প্যারামিটার হ্যান্ডলিং
$eiin = $_GET['sccode'] ?? 0;

// ২. ডাটা ফেচিং এবং অটো-আপডেট লজিক (Prepared Statement - Secure)
if ($eiin > 0) {
    $stmt = $conn->prepare("SELECT app FROM scinfo WHERE sccode = ? LIMIT 1");
    $stmt->bind_param("s", $eiin);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if ($row["app"] == 1) {
            $upd = $conn->prepare("UPDATE usersapp SET sccode = ?, userlevel='Visitor' WHERE email = ?");
            $upd->bind_param("ss", $eiin, $usr);
            $upd->execute();
            $upd->close();
            header("Location: index.php");
            exit();
        }
    }
    $stmt->close();
}

// ৩. ইউজার অথেন্টিকেশন চেক
if (empty($usr) || (is_numeric(substr($usr, 0, 1)) && substr($usr, 0, 1) > 0)) {
    include 'login.php';
    exit();
}
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full-Width Top App Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Identification Card (8px Radius) */
    .m3-id-card {
        background: #fff; border-radius: 8px; padding: 24px 16px;
        margin: 16px 12px; border: 1px solid #eee;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03); text-align: center;
    }

    /* M3 Input Group (8px Radius) */
    .form-floating > .form-control {
        border-radius: 8px !important; border: 1px solid #79747E;
        background: transparent; font-weight: 700; font-size: 1rem; color: #6750A4;
    }
    .form-floating > label { font-size: 0.75rem; color: #6750A4; font-weight: 700; }
    .form-floating > .form-control:focus { border-color: #6750A4; box-shadow: 0 0 0 1px #6750A4; }

    /* M3 Primary Button (8px Radius) */
    .btn-m3-submit {
        background-color: #6750A4; color: #fff; border-radius: 8px;
        padding: 12px; font-weight: 800; border: none; width: 100%;
        margin-top: 16px; letter-spacing: 0.5px; transition: 0.2s;
    }
    .btn-m3-submit:active { transform: scale(0.97); background-color: #4F378B; }

    /* Instruction List (Condensed M3 style) */
    .m3-instruction-box {
        background: #F3EDF7; border-radius: 8px; padding: 16px;
        margin: 0 12px 20px; border: 1px solid #EADDFF;
    }
    .step-item { display: flex; gap: 12px; margin-bottom: 12px; }
    .step-num {
        width: 24px; height: 24px; border-radius: 50%; background: #6750A4;
        color: #fff; font-size: 0.7rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .step-text { font-size: 0.75rem; color: #49454F; line-height: 1.4; }
    .step-text b { color: #1C1B1F; }

    #status-msg { font-size: 0.8rem; font-weight: 700; color: #6750A4; }
</style>

<header class="m3-app-bar shadow-sm">
    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h1 class="page-title">Identify Institute</h1>
</header>

<main class="pb-5">
    <div class="m3-id-card shadow-sm">
        <p class="text-muted small mb-4">
            <?php echo ($pxx == '') ? "Identify yourself by providing your institute's 6-digit EIIN number below." : $pxx; ?>
        </p>

        <div class="form-floating mb-2">
            <input type="number" class="form-control text-center" id="eiin_val" placeholder="EIIN" value="<?php echo $sccode; ?>">
            <label for="eiin_val"><i class="bi bi-bank2 me-1"></i> ENTER 6-DIGIT EIIN</label>
        </div>

        <button type="button" class="btn-m3-submit shadow-sm" onclick="verifyEiin();">
            SUBMIT & CONTINUE <i class="bi bi-arrow-right-short fs-4"></i>
        </button>

        <div id="status-msg" class="mt-3"></div>
    </div>

    <div class="px-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Getting Started</div>
    <div class="m3-instruction-box shadow-sm">
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-text">Enter your <b>6-digit EIIN</b>. If you're the first user, you'll be the <b>Administrator</b>.</div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-text">After submission, click the <b>Proceed</b> button that appears to continue.</div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-text">Update your institute name, address, and mobile number in the profile page.</div>
        </div>
        <div class="step-item" style="margin-bottom: 0;">
            <div class="step-num">4</div>
            <div class="step-text">You're ready! Your account is now synced with your institute records.</div>
        </div>
    </div>
</main>

<div style="height: 65px;"></div> <script>
    function verifyEiin() {
        const eiin = document.getElementById("eiin_val").value;
        if (eiin.length >= 4) {
            const dataString = `user=<?php echo $usr; ?>&eiin=${eiin}`;
            
            $.ajax({
                type: "POST",
                url: "checkeiin.php",
                data: dataString,
                beforeSend: function () {
                    $('#status-msg').html('<div class="spinner-border spinner-border-sm"></div> Validating...');
                },
                success: function (res) {
                    $("#status-msg").html(res);
                }
            });
        } else {
            Swal.fire('Invalid ID', 'Please enter a valid 6-digit EIIN number.', 'warning');
        }
    }

    function proceed() {
        window.location.href = 'index.php?email=<?php echo $usr; ?>';
    }
</script>

<?php include 'footer.php'; ?>