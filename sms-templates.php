<?php
$page_title = 'SMS Templates';
include 'inc.php';
?>

<style>
    /* M3 Tonal Color System */
    :root {
        --m3-surface: #F7F9FF; /* Light Tonal Background */
        --m3-primary-container: #D1E4FF;
        --m3-on-primary-container: #001D36;
        --m3-secondary-container: #E1E2EC;
        --m3-on-secondary-container: #191C20;
        --m3-surface-variant: #E0E2EC;
        --m3-outline: #74777F;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
    }

    .m3-container {
        padding: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Top Header Card (Tonal Style) */
    .header-card {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 28px; /* M3 Large Corner */
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .header-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-top i {
        font-size: 24px;
    }

    .header-title {
        font-size: 22px;
        font-weight: 500;
        letter-spacing: 0.1px;
    }

    .stats-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding-top: 12px;
    }

    .stat-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    /* Template Cards (Secondary Tonal Style) */
    .template-card {
        background-color: white; /* Surface color */
        border: 1px solid var(--m3-surface-variant);
        border-radius: 16px; /* M3 Medium Corner */
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.1s, background-color 0.2s;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }

    /* অ্যান্ড্রয়েড টাচ ইফেক্ট (Ripple Alternative) */
    .template-card:active {
        background-color: var(--m3-secondary-container);
        transform: scale(0.98);
    }

    .template-text {
        font-size: 14px;
        color: var(--m3-on-secondary-container);
        line-height: 1.5;
        font-weight: 400;
        flex: 1;
        padding-right: 12px;
    }

    .template-icon-box img {
        width: 32px;
        height: 32px;
        border-radius: 8px;
    }
</style>

<main class="m3-container">
    <div class="header-card">
        <div class="header-top">
            <i class="bi bi-chat-left-text-fill"></i>
            <span class="header-title">SMS Templates</span>
        </div>

        <div class="stats-row">
            <div>
                <div class="stat-label">Class & Section</div>
                <div style="font-size: 16px; font-weight: 700;">Active Templates</div>
            </div>
            <div style="text-align: right;">
                <div id="cnt" style="font-size: 32px; font-weight: 700; line-height: 1;">0</div>
                <div class="stat-label">Total SMS</div>
            </div>
        </div>
    </div>

    <?php
    $sql0 = "SELECT * FROM sms_templete where (sccode = '$sccode' or sccode='0') order by id";
    $result0 = $conn->query($sql0);
    
    if ($result0 && $result0->num_rows > 0) {
        while ($row0 = $result0->fetch_assoc()) {
            $smstext = $row0["smstemp"];
            ?>
            <div class="template-card" onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">
                <div class="template-text">
                    <?php echo nl2br(htmlspecialchars($smstext)); ?>
                </div>
                <div class="template-icon-box">
                    <img src="<?php echo $ico; ?>" alt="icon" onerror="this.style.display='none'">
                </div>
            </div>
            <?php 
        }
    } else {
        echo '<div style="text-align:center; padding:20px; color:var(--m3-outline);">No templates found.</div>';
    }
    ?>
</main>

<?php include 'footer.php'; ?>