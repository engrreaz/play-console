<?php
$page_title = 'SMS Campaign';
include 'inc.php';
?>

<style>
    :root {
        /* M3 Tonal Palette */
        --m3-surface: #F7F9FF;
        --m3-primary-container: #D1E4FF;
        --m3-on-primary-container: #001D36;
        --m3-secondary-container: #E1E2EC;
        --m3-on-secondary-container: #191C20;
        --m3-surface-variant: #E0E2EC;
        --m3-on-surface-variant: #44474E;
        --m3-outline: #74777F;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Roboto', sans-serif;
        margin: 0;
    }

    .m3-wrapper { padding: 16px; max-width: 600px; margin: 0 auto; }

    /* Top Stats Card */
    .hero-card {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 28px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hero-title { display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 500; }
    .hero-title i { font-size: 24px; }

    .hero-stat { text-align: right; }
    .stat-num { font-size: 32px; font-weight: 700; line-height: 1; }
    .stat-label { font-size: 11px; text-transform: uppercase; opacity: 0.8; margin-top: 4px; }

    /* Campaign Card Style */
    .camp-card {
        background-color: #FFFFFF;
        border: 1px solid var(--m3-surface-variant);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 16px;
        transition: transform 0.1s;
        -webkit-tap-highlight-color: transparent;
    }

    .camp-card:active {
        background-color: var(--m3-surface-variant);
        transform: scale(0.99);
    }

    .camp-header {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--m3-surface-variant);
        padding-bottom: 12px;
        margin-bottom: 12px;
    }

    .label-small {
        font-size: 11px;
        color: var(--m3-on-surface-variant);
        text-transform: uppercase;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .value-main { font-size: 15px; font-weight: 700; color: #1A1C1E; }
    .value-sub { font-size: 13px; color: var(--m3-on-surface-variant); }

    .msg-body-box {
        background-color: var(--m3-secondary-container);
        color: var(--m3-on-secondary-container);
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.5;
        font-style: italic;
    }

    .chip-group { display: flex; gap: 6px; margin-top: 10px; }
    .m3-chip {
        background: white;
        border: 1px solid var(--m3-outline);
        border-radius: 8px;
        padding: 2px 8px;
        font-size: 11px;
        color: var(--m3-on-surface-variant);
    }
</style>

<main class="m3-wrapper">
    <div class="hero-card">
        <div class="hero-title">
            <i class="bi bi-vr"></i>
            <span>Campaigns</span>
        </div>
        <div class="hero-stat">
            <div id="cnt" class="stat-num">0</div>
            <div class="stat-label">Total Sent</div>
        </div>
    </div>

    <?php
    $datam_campaign = array();
    $sql0 = "SELECT * FROM sms_campaign WHERE sccode = '$sccode' ORDER BY date DESC, id DESC";
    $result0 = $conn->query($sql0);
    
    $total_qnt = 0;
    if ($result0 && $result0->num_rows > 0) {
        while ($row = $result0->fetch_assoc()) {
            $total_qnt += $row['total_count'];
            ?>
            <div class="camp-card" onclick="/* function here */">
                <div class="camp-header">
                    <div>
                        <div class="label-small">Campaign Name</div>
                        <div class="value-main"><?php echo htmlspecialchars($row['camp_name']); ?></div>
                        <div class="value-sub">ID: #<?php echo $row['camp_id']; ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div class="label-small">Audience</div>
                        <div class="value-main"><?php echo $row['total_count']; ?> <small>SMS</small></div>
                    </div>
                </div>

                <div class="label-small">Audience Parameters</div>
                <div class="value-sub mb-2">
                    <?php echo $row['audi_param_1'] . ' • ' . $row['audi_param_2'] . ' → ' . $row['audi_param_3']; ?>
                </div>

                <div class="label-small">Message Body</div>
                <div class="msg-body-box">
                    "<?php echo nl2br(htmlspecialchars($row['sms_text'])); ?>"
                </div>
                
                <div class="chip-group">
                    <span class="m3-chip"><i class="bi bi-calendar-event"></i> <?php echo date('d M, y', strtotime($row['date'])); ?></span>
                </div>
            </div>
            <?php 
        }
    } else {
        echo '<div style="text-align:center; padding:40px; color:var(--m3-outline);">No campaigns found.</div>';
    }
    ?>
</main>

<?php include 'footer.php'; ?>


<script>
    document.getElementById("cnt").innerHTML = "<?php echo number_format($total_qnt); ?>";
</script>