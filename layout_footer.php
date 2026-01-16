<style>
    /* বটম বারটিকে নিচে ফিক্সড করার জন্য */
    .bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #6750A4; /* আপনার প্রাইমারি কালার (Material M3) */
        z-index: 1000;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        border-top-left-radius: 20px; /* অ্যাপের মতো কার্ভ লুক দিতে */
        border-top-right-radius: 20px;
    }

    /* আইকনগুলোর স্টাইল */
    .footer-nav-icon, .material-icons {
        font-size: 24px !important;
        color: white;
    }

    /* বডির নিচে গ্যাপ রাখা যাতে কন্টেন্ট বারের নিচে ঢাকা না পড়ে */
    body {
        padding-bottom: 80px; 
    }

    /* টেবিল লেআউট ঠিক করা */
    .bottom-bar table {
        margin-bottom: 0;
    }

    /* একটিভ বা হোভার ইফেক্ট (ঐচ্ছিক) */
    .bottom-bar td a:active {
        opacity: 0.6;
    }
</style>

<?php
// ইউজার লগইন না থাকলে সরাসরি রিডাইরেক্ট
if ($usr == '' || $userlevel == 'Guest') {
    echo '<meta http-equiv="refresh" content="0; url=\'login.php\'" />';
    exit();
}
?>

<div class="fixed-bottom bg-white border-top d-flex justify-content-around py-2 shadow-lg d-md-none">
    <a href="index.php" class="nav-item text-center text-decoration-none <?php echo ($curfile == 'index.php') ? 'text-primary' : 'text-muted'; ?>">
        <i class="bi bi-house-door-fill fs-4"></i>
        <div style="font-size: 10px;">Home</div>
    </a>

    <?php if (in_array($userlevel, ['Administrator', 'Head Teacher', 'Teacher', 'Class Teacher'])): ?>
        <a href="reporthome.php" class="nav-item text-center text-decoration-none text-muted">
            <i class="bi bi-mortarboard-fill fs-4"></i>
            <div style="font-size: 10px;">Reports</div>
        </a>
        <a href="tools.php" class="nav-item text-center text-decoration-none text-muted">
            <i class="bi bi-grid-fill fs-4"></i>
            <div style="font-size: 10px;">Tools</div>
        </a>
    <?php endif; ?>

    <?php if ($userlevel == 'Student'): ?>
        <a href="my-profile.php" class="nav-item text-center text-decoration-none text-muted">
            <i class="bi bi-person-badge fs-4"></i>
            <div style="font-size: 10px;">Profile</div>
        </a>
    <?php endif; ?>

    <a href="settings.php" class="nav-item text-center text-decoration-none text-muted">
        <i class="bi bi-gear-wide-connected fs-4"></i>
        <div style="font-size: 10px;">Settings</div>
    </a>
</div>

<style>
    body { padding-bottom: 70px; background-color: #f8f9fa; }
    .fixed-bottom { border-radius: 20px 20px 0 0; }
    .nav-item i { transition: transform 0.2s; }
    .nav-item:active i { transform: scale(0.8); }
    .text-primary { color: #6750A4 !important; } /* Material M3 Primary */
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="assets/post-load.js"></script>
</body>
</html>