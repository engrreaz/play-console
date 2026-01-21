<?php
/**
 * Institution Profile Settings - Refactored to "M3-EIM-Floating"
 * Standards: 8px Radius | Floating Labels | Leading Icons | Android Webview Optimized
 */
$page_title = "Institution Profile";
include 'inc.php';

// ডাটা ফেচিং লজিক (আপনার কুয়েরি চমৎকার, তাই একই রাখা হলো)
$stmt = $conn->prepare("SELECT * FROM scinfo WHERE sccode = ? LIMIT 1");
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$scname = $row["scname"] ?? "";
$mobile = $row["mobile"] ?? "";
?>

<style>
    /* ১. মেইন সারফেস ও অ্যাপ বার */
    body {
        background-color: #FEF7FF;
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
    }

    .m3-app-bar {
        width: 100%;
        height: 64px;
        background: #fff;
        display: flex;
        align-items: center;
        padding: 0 16px;
        position: sticky;
        top: 0;
        z-index: 1050;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .m3-app-bar .page-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1C1B1F;
        flex-grow: 1;
        margin: 0;
    }

    /* ২. প্রিমিয়াম হিরো সেকশন (Identity Card) */
    .m3-identity-hero {
        background: #fff;
        padding: 32px 16px;
        text-align: center;
        margin-bottom: 12px;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .logo-container {
        width: 90px;
        height: 90px;
        background: #F3EDF7;
        border-radius: 12px;
        padding: 10px;
        margin: 0 auto 16px;
        border: 2px solid #EADDFF;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.1);
    }

    .logo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .eiin-badge {
        font-size: 0.7rem;
        background: #6750A4;
        color: #fff;
        padding: 4px 16px;
        border-radius: 100px;
        font-weight: 800;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(103, 80, 164, 0.2);
    }

    /* ৩. "M3-EIM-Floating" ইনপুট ফিল্ড সিস্টেম */
    .m3-form-container {
        padding: 0 16px;
    }

    .m3-floating-group {
        position: relative;
        margin-bottom: 22px;
    }

    .m3-field-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6750A4;
        font-size: 1.2rem;
        z-index: 10;
    }

    .m3-floating-label {
        position: absolute;
        left: 48px;
        top: -10px;
        background: #FEF7FF;
        padding: 0 6px;
        font-size: 0.75rem;
        font-weight: 800;
        color: #6750A4;
        z-index: 15;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .m3-input-floating {
        width: 100%;
        height: 56px;
        padding: 12px 16px 12px 52px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1C1B1F;
        background-color: transparent;
        border: 2px solid #CAC4D0;
        border-radius: 8px !important;
        /* Strict 8px */
        transition: all 0.2s cubic-bezier(0, 0, 0.2, 1);
    }

    .m3-input-floating:focus {
        border-color: #6750A4;
        outline: none;
        box-shadow: 0 0 0 1px #6750A4;
    }

    /* ৪. প্রিমিয়াম সেভ বাটন */
    .btn-m3-gradient {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: #fff;
        border-radius: 8px !important;
        padding: 16px;
        font-weight: 800;
        border: none;
        width: 100%;
        font-size: 0.95rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
    }

    .btn-m3-gradient:active {
        transform: scale(0.98);
        opacity: 0.9;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <span class="badge bg-light text-primary border rounded-pill px-3 py-1" style="font-size: 0.65rem;">SC:
        <?php echo $sccode; ?></span>
</header>

<main class="pb-5">
    <div class="m3-identity-hero shadow-sm">
        <div class="logo-container shadow-sm">
            <img src="<?php echo $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>"
                onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <div class="inst-name fw-bolder mb-1" style="font-size: 1.15rem;"><?php echo $scname; ?></div>
        <div class="eiin-badge">EIIN: <?php echo $sccode; ?></div>
    </div>

    <div class="m3-form-container">
        <div class="mb-4 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px; padding-left: 4px;">Update
            Basic Details</div>

        <div class="m3-floating-group">
            <label class="m3-floating-label">Full Institution Name</label>
            <i class="bi bi-bank m3-field-icon"></i>
            <input type="text" id="scname" class="m3-input-floating" value="<?php echo $scname; ?>">
        </div>

        <div class="m3-floating-group">
            <label class="m3-floating-label">Primary Address</label>
            <i class="bi bi-geo-alt m3-field-icon"></i>
            <input type="text" id="add1" class="m3-input-floating" value="<?php echo $row['scadd1']; ?>">
        </div>
        <div class="m3-floating-group">
            <label class="m3-floating-label">Primary Address</label>
            <i class="bi bi-geo-alt m3-field-icon"></i>
            <input type="text" id="add2" class="m3-input-floating" value="<?php echo $row['scadd2']; ?>">
        </div>

        <div class="row gx-2">
            <div class="col-6">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Upazila</label>
                    <input type="text" id="ps" class="m3-input-floating" style="padding-left: 16px;"
                        value="<?php echo $row['ps']; ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">District</label>
                    <input type="text" id="dist" class="m3-input-floating" style="padding-left: 16px;"
                        value="<?php echo $row['dist']; ?>">
                </div>
            </div>
        </div>

        <div class="m3-floating-group">
            <label class="m3-floating-label">Official Mobile</label>
            <i class="bi bi-phone m3-field-icon"></i>
            <input type="tel" id="mno" class="m3-input-floating" value="<?php echo $mobile; ?>">
        </div>

        <button type="button" class="btn-m3-gradient shadow" onclick="saveInstituteProfile();">
            <i class="bi bi-cloud-check-fill fs-5"></i>
            UPDATE INFORMATION
        </button>

        <div id="syncStatus" class="mt-4 text-center"></div>
    </div>
</main>

<div style="height: 80px;"></div>



<?php include 'footer.php'; ?>

<script>

    function saveInstituteProfile() {
        // ১. ইনপুট ফিল্ড থেকে ভ্যালু সংগ্রহ
        const payload = {
            sccode: '<?php echo $sccode; ?>',
            scname: document.getElementById("scname").value,
            add1: document.getElementById("add1").value,
            add2: document.getElementById("add2").value,
            ps: document.getElementById("ps").value,
            dist: document.getElementById("dist").value,
            mno: document.getElementById("mno").value
        };
        // ২. AJAX রিকোয়েস্ট শুরু
        $.ajax({
            type: "POST",
            url: "settings/update-sc-info.php",
            data: payload, // অবজেক্ট হিসেবে ডেটা পাঠানো হচ্ছে (jQuery স্বয়ংক্রিয়ভাবে সিরিয়ালাইজ করবে)
            beforeSend: function () {
                // সাবমিট বাটনের নিচে স্ট্যাটাস দেখানো
                $('#syncStatus').fadeOut(100, function () {
                    $(this).html(`
                    <div class="d-inline-flex align-items-center bg-primary-subtle px-3 py-2 m3-8px border border-primary-subtle shadow-sm">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="fw-bold text-primary" style="font-size: 0.75rem; letter-spacing: 0.5px;">SYNCING WITH SERVER...</span>
                    </div>
                `).fadeIn();
                });
            },
            success: function (response) {
                // ৩. সফল হলে ফিডব্যাক
                Swal.fire({
                    title: 'Update Successful!',
                    text: 'Institution profile has been securely updated.',
                    icon: 'success',
                    confirmButtonColor: '#6750A4',
                    confirmButtonText: 'DONE',
                    customClass: {
                        popup: 'm3-8px',
                        confirmButton: 'm3-8px px-4 py-2'
                    }
                }).then((result) => {
                    // ইউজারকে রিডাইরেক্ট করা হচ্ছে
                    window.location.href = 'settings_admin.php';
                });
            },
            error: function (xhr, status, error) {
                // ৪. কোনো ত্রুটি হলে মেসেজ
                $('#syncStatus').html('<span class="text-danger fw-bold small">Update Failed. Please try again.</span>');

                Swal.fire({
                    title: 'Sync Error',
                    text: 'Could not connect to the server. Please check your network.',
                    icon: 'error',
                    confirmButtonColor: '#B3261E'
                });
            }
        });
    }
</script>