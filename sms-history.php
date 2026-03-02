<?php
$page_title = 'SMS History';
include 'inc.php';
?>

<style>
    :root {
        /* M3 Tonal Palette colors */
        --m3-surface: #F7F9FF;
        --m3-on-surface: #1A1C1E;
        --m3-primary-container: #D1E4FF;
        --m3-on-primary-container: #001D36;
        --m3-surface-variant: #E0E2EC;
        --m3-on-surface-variant: #44474E;
        --m3-success: #198754;
        --m3-error: #B3261E;
        --m3-card-outline: #C4C6D0;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Roboto', sans-serif;
        color: var(--m3-on-surface);
        margin: 0;
    }

    .m3-main { padding: 16px; max-width: 600px; margin: 0 auto; }

    /* M3 Header Card */
    .header-box {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .header-info h2 { margin: 0; font-size: 20px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    
    .counter-section { text-align: right; }
    .counter-val { font-size: 28px; font-weight: 700; line-height: 1; }
    .counter-label { font-size: 11px; text-transform: uppercase; opacity: 0.8; letter-spacing: 0.5px; }

    /* SMS History Cards */
    .history-card {
        background: #FFFFFF;
        border: 1px solid var(--m3-card-outline);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        transition: background-color 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .history-card:active {
        background-color: var(--m3-surface-variant);
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .mobile-no {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-icon { font-size: 18px; }

    .sms-content {
        font-size: 14px;
        color: var(--m3-on-surface);
        line-height: 1.5;
        margin-bottom: 10px;
        word-wrap: break-word;
    }

    .meta-info {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: var(--m3-on-surface-variant);
        font-weight: 500;
    }

    /* Status Specific Colors */
    .status-success { color: var(--m3-success); }
    .status-failed { color: var(--m3-error); }
</style>

<main class="m3-main">
    <div class="header-box">
        <div class="header-info">
            <h2><i class="bi bi-clock-history"></i> SMS History</h2>
            <div style="font-size: 12px; opacity: 0.7; margin-top: 4px;">Logs of sent messages</div>
        </div>
        <div class="counter-section">
            <div id="cnt" class="counter-val">0</div>
            <div class="counter-label">Total Sent</div>
        </div>
    </div>

    <?php
    $sql0 = "SELECT * FROM sms WHERE sccode = '$sccode' ORDER BY send_time DESC LIMIT 50";
    $result0 = $conn->query($sql0);
    $total_sent = 0;

    if ($result0 && $result0->num_rows > 0) {
        $total_sent = $result0->num_rows; 
        while ($row = $result0->fetch_assoc()) {
            $is_success = ($row['response_code'] == 1002);
            $status_class = $is_success ? 'status-success' : 'status-failed';
            $status_icon = $is_success ? 'check-circle-fill' : 'x-circle-fill';
            ?>
            
            <div class="history-card" onclick="/* Handle Click */">
                <div class="card-top">
                    <div class="mobile-no <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($row['mobile_number']); ?>
                    </div>
                    <div class="status-icon <?php echo $status_class; ?>">
                        <i class="bi bi-<?php echo $status_icon; ?>"></i>
                    </div>
                </div>

                <div class="sms-content">
                    <?php echo nl2br(htmlspecialchars($row['sms_text'])); ?>
                </div>

                <div class="meta-info">
                    <span><i class="bi bi-calendar3"></i> <?php echo date('d M, Y', strtotime($row['send_time'])); ?></span>
                    <span><i class="bi bi-alarm"></i> <?php echo date('h:i A', strtotime($row['send_time'])); ?></span>
                </div>
            </div>

            <?php
        }
    } else {
        echo '<div style="text-align:center; padding:40px; color:gray;">No history found.</div>';
    }
    ?>
</main>

<?php include 'footer.php'; ?>


<script>
    // Update counter safely
    document.getElementById("cnt").innerHTML = "<?php echo $total_sent; ?>";
</script>