<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'EIMBox Dashboard'; ?></title>

    <!-- ১. বুটস্ট্র্যাপ ৫ সিএসএস (Bootstrap 5 CSS CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- ২. বুটস্ট্র্যাপ আইকন (Bootstrap Icons CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- ৩. সুইট অ্যালার্ট ২ (SweetAlert2) - যদি লগআউট বা অ্যালার্টে ব্যবহার করতে চান -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>


<?php
$sccode = $_SESSION['sccode'] ?? '00000';
$page_title = "Welcome Guest";
// include 'inc.php'; // আপনার গ্লোবাল হেডার ফাইল বা ডাটাবেজ কানেকশন থাকলে আনকমেন্ট করুন

// প্রতিষ্ঠানের কিছু ডামি ইনফরমেশন (আপনার ডাটাবেজ বা ভেরিয়েবল দিয়ে রিপ্লেস করে নেবেন)
$institution_name = "EIMBox International School";
$institution_code = "EIIN: $sccode | Estd: 2012";
$welcome_msg = "আমাদের ডিজিটাল ক্যাম্পাসে আপনাকে স্বাগতম। একজন অতিথি (Guest) হিসেবে আপনি প্রতিষ্ঠানের পাবলিক রিসোর্স, নোটিশ এবং সাধারণ তথ্যাদি সরাসরি ব্রাউজ করতে পারবেন।";
?>

<style>
    body {
        background-color: #FAF8FC; /* M3 Light Surface Tint */
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* 1. Modern Minimalist Institution Banner (No Cards / No Shadows) */
    .guest-hero-banner {
        background: #EADDFF; /* M3 Tonal Purple Container */
        color: #21005D;
        padding: 40px 24px;
        border-radius: 0 0 24px 24px;
        border-bottom: 1px solid #D0BCFF;
    }

    .inst-logo-squircle {
        width: 64px;
        height: 64px;
        border-radius: 20px; /* Material 3 Squircle Shape Metric */
        background: #6750A4;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 16px;
        border: 1px solid #D0BCFF;
    }

    .inst-title {
        font-size: 1.4rem;
        font-weight: 900;
        color: #1C1B1F;
        letter-spacing: -0.4px;
        margin-bottom: 2px;
    }

    .inst-meta {
        font-size: 0.72rem;
        font-weight: 700;
        color: #4F378B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .inst-desc {
        font-size: 0.85rem;
        color: #49454F;
        line-height: 1.4;
        font-weight: 500;
    }

    /* 2. Flat Layout Section Headings */
    .section-lbl {
        font-size: 0.75rem;
        font-weight: 800;
        color: #49454F;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 24px 24px 10px 24px;
        background: #FAF8FC;
    }

    /* 3. Primary Guest Gate (Tonal Active Panel) */
    .guest-gate-panel {
        background: #FFFFFF;
        padding: 24px;
        border-bottom: 1px solid #ECE6F0;
        text-align: center;
    }

    .btn-m3-primary-tonal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #6750A4;
        color: #FFFFFF;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 100%;
        max-width: 320px;
        text-decoration: none !important;
        transition: background-color 0.15s ease, transform 0.1s ease;
    }

    .btn-m3-primary-tonal:active {
        background: #4F378B;
        transform: scale(0.98);
    }

    .gate-warning-text {
        font-size: 0.7rem;
        color: #79747E;
        font-weight: 600;
        margin-top: 10px;
        display: block;
    }

    /* 4. Flat Navigation List Group (Card-less Linear Design) */
    .m3-flat-list-group {
        background: #FFFFFF;
        border-bottom: 1px solid #ECE6F0;
    }

    .m3-list-flat-item {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        background: #FFFFFF;
        border-bottom: 1px solid #F4EFF4;
        text-decoration: none !important;
        color: #1C1B1F;
        transition: background-color 0.15s ease;
    }

    .m3-list-flat-item:last-child {
        border-bottom: none;
    }

    .m3-list-flat-item:active {
        background-color: #EADDFF; /* M3 State Layer */
    }

    /* Tonal Icons Configurations */
    .icon-box-flat {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Category Specific Tonal Shades */
    .c-about    { background: #E8DEF8; color: #1D192B; } /* Purple */
    .c-notice   { background: #FCE4EC; color: #C2185B; } /* Pink */
    .c-gallery  { background: #E0F2F1; color: #004D40; } /* Teal */
    .c-contact  { background: #FFF3E0; color: #E65100; } /* Amber */
    .c-portal   { background: #E8F5E9; color: #1B5E20; } /* Green */

    .item-info-block {
        flex-grow: 1;
        overflow: hidden;
    }

    .st-flat-title {
        font-weight: 700;
        font-size: 0.92rem;
        color: #1C1B1F;
        margin-bottom: 1px;
    }

    .st-flat-desc {
        font-size: 0.72rem;
        color: #79747E;
        font-weight: 500;
    }

    .flat-chevron {
        color: #79747E;
        font-size: 0.95rem;
        opacity: 0.5;
        margin-left: 8px;
    }
</style>

<main class="pb-5">
    
    <!-- 1. MODERN INSTITUTION HERO BANNER (GUEST CONTEXT) -->
    <div class="guest-hero-banner">
        <div class="inst-logo-squircle">
            <i class="bi bi-building-house"></i>
        </div>
        <div class="hero-info-block">
            <div class="inst-title"><?php echo htmlspecialchars($institution_name); ?></div>
            <div class="inst-meta"><?php echo htmlspecialchars($institution_code); ?></div>
            <div class="inst-desc"><?php echo htmlspecialchars($welcome_msg); ?></div>
        </div>
    </div>

    <!-- 2. QUICK GATEWAY ACCESS -->
    <div class="section-lbl">Instant Gateway</div>
    <div class="guest-gate-panel">
        <a href="guest-dashboard.php" class="btn-m3-primary-tonal">
            <i class="bi bi-person-bounding-box"></i> Enter As Guest
        </a>
        <span class="gate-warning-text">* কোনো পাসওয়ার্ড বা রেজিস্ট্রেশন ছাড়াই পাবলিক মডিউলগুলো দেখুন</span>
    </div>

    <!-- 3. PUBLIC INFORMATION EXPLORER (FLAT LINIAR ROWS) -->
    <div class="section-lbl">Explore Campus Data</div>
    <div class="m3-flat-list-group">
        
        <!-- About Institution -->
        <a href="about-us.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-about"><i class="bi bi-info-square-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">About Our Institution</div>
                <div class="st-flat-desc">History, achievements, governing body, and vision</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Public Notices -->
        <a href="public-notices.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-notice"><i class="bi bi-megaphone-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Public Notices & News</div>
                <div class="st-flat-desc">General circulars, admission updates, and event news</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Academic Gallery -->
        <a href="campus-gallery.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-gallery"><i class="bi bi-images"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Campus Gallery & Media</div>
                <div class="st-flat-desc">Glimpses of campus events, sports, and infrastructure</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Contact & Location -->
        <a href="contact-details.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-contact"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Contact & Location Map</div>
                <div class="st-flat-desc">Official helpdesk numbers, email, and location tracker</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

    </div>

    <!-- 4. ACADEMIC PORTALS (ADDITIONAL REFERENCE) -->
    <div class="section-lbl">Official Access Ports</div>
    <div class="m3-flat-list-group">

        <!-- Student/Teacher Login Prompt -->
        <a href="login.php" class="m3-list-flat-item">
            <div class="icon-box-flat c-portal"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Regular Portal Login</div>
                <div class="st-flat-desc">Authorized access point for students, guardians, and teachers</div>
            </div>
            <div class="flat-chevron"><i class="bi bi-box-arrow-in-right"></i></div>
        </a>

    </div>
</main>

<?php
include 'footer-guest.php'; 
?>



<script>

</script>

</body>
</html>