<?php
/**
 * Settings Hub - M3 Full Width with Profile Hero
 * Includes: Logo, SC Name, SC Code, Category
 */
$page_title = "Institution Settings";
include 'inc.php'; 

// ডাটা ফেচিং (নিশ্চিত করা যে $row ভেরিয়েবলটি scinfo টেবিল থেকে আসছে)
$stmt = $conn->prepare("SELECT scname, sccategory, logo FROM scinfo WHERE sccode = ? LIMIT 1");
$stmt->bind_param("s", $sccode);
$stmt->execute();
$sc_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<style>
 


    .m3-hero-profile {
        background: linear-gradient(180deg, #6750A4 0%, #4F378B 100%);
        padding: 50px 24px 70px;
        color: #fff;
        border-radius: 0 0 25% 25%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(103, 80, 164, 0.2);
        margin-bottom: 30px;
    }

    .hero-logo-box {
        width: 100px; height: 100px;
        background: #fff;
        border-radius: 28px; /* M3 Large Shape */
        padding: 10px;
        margin: 0 auto 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .hero-logo-box img { width: 100%; height: 100%; object-fit: contain; }

    .hero-sc-name { font-weight: 900; font-size: 1.4rem; letter-spacing: -0.5px; margin-bottom: 4px; }
    
    .hero-sc-meta {
        background: rgba(255, 255, 255, 0.15);
        display: inline-flex;
        gap: 12px;
        padding: 4px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* ২. সেটিংস লিস্ট (Full Width) */
    .settings-container {
        padding: 0 16px 50px;
        margin-top: 20px; /* হিরো প্যানেলের উপর হালকা ওভারল্যাপ */
        position: relative;
        z-index: 10;
    }

    .m3-full-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        text-decoration: none !important;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.2s cubic-bezier(0.2, 0, 0, 1);
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .m3-full-card:hover {
        background-color: #F3EDF7;
        transform: scale(1.01);
        border-color: var(--m3-primary);
    }

    .m3-full-card:active { transform: scale(0.97); }

    /* আইকন এবং টেক্সট */
    .icon-box-m3 {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .text-box-m3 { flex-grow: 1; }
    .title-m3 { font-size: 1rem; font-weight: 800; color: #1C1B1F; margin-bottom: 2px; }
    .desc-m3 { font-size: 0.75rem; color: #79747E; font-weight: 600; }

    /* ক্যাটাগরি কালারস */
    .c-id { background: #E3F2FD; color: #1565C0; }
    .c-pk { background: #F3E5F5; color: #7B1FA2; }
    .c-ms { background: #E8F5E9; color: #2E7D32; }
    .c-py { background: #FFF3E0; color: #EF6C00; }
    .c-bk { background: #E0F7FA; color: #00838F; }
    .c-mi { background: #FBE9E7; color: #D84315; }

</style>

<main>
    <div class="hero-container" style="margin:0;">
        <div class="hero-logo-box">
            <img src="<?= $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>" onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <div class="hero-sc-name"><?= $sc_data['scname'] ?></div>
        <div class="hero-sc-meta">
            <span>ID: <?= $sccode ?></span>
            <span style="opacity: 0.5;">|</span>
            <span>Category: <?= $sc_data['sccategory'] ?></span>
        </div>
    </div>

    <div class="settings-container">
        
        <a href="institute-identity.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-id"><i class="bi bi-bank"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Institute Identity</div>
                <div class="desc-m3">Edit name, category and institutional branding</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

        <a href="institute-subcription.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-pk"><i class="bi bi-stars"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Package & Subscription</div>
                <div class="desc-m3">Manage active plan, renewal and subscription limits</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

        <a href="institute-sms.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-ms"><i class="bi bi-chat-left-dots-fill"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Messaging Gateways</div>
                <div class="desc-m3">Configure SMS API, URL and notification templates</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

        <a href="institute-payments.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-py"><i class="bi bi-wallet2"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Payment Gateways</div>
                <div class="desc-m3">Connect bKash, Nagad, Rocket and Bank accounts</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

        <a href="institute-backup.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-bk"><i class="bi bi-cloud-check-fill"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Data Backup & Security</div>
                <div class="desc-m3">Database backup, API keys and security algorithm</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

        <a href="institute-protocol.php" class="m3-full-card shadow-sm">
            <div class="icon-box-m3 c-mi"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div class="text-box-m3">
                <div class="title-m3">Miscellaneous</div>
                <div class="desc-m3">GPS fence radius, time differ and system protocols</div>
            </div>
            <i class="bi bi-chevron-right text-muted opacity-50"></i>
        </a>

    </div>
</main>

<?php include 'footer.php'; ?>