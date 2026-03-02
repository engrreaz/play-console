<?php
// আজকের দিন এবং মাস
$today_d = date('d');
$today_m = date('m');

// আজকের অন্তত ৩টি গুরুত্বপূর্ণ ইভেন্ট ফেচ করা
$sql_history = "SELECT * FROM history WHERE day = $today_d AND month = $today_m ORDER BY priority DESC LIMIT 3";
$res_history = $conn->query($sql_history);
?>

<style>
        .history-block-container {
                height: 100%;
                display: flex;
                flex-direction: column;
                padding:12px;
        }

        .event-mini-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
        }

        .event-mini-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 8px;
                background: #F7F2FA;
                border-radius: 12px;
                border: 1px solid rgba(103, 80, 164, 0.05);
        }

        .mini-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                flex-shrink: 0;
        }

        .mini-details {
                font-size: 0.75rem;
                line-height: 1.3;
                color: #49454F;
                font-weight: 600;
                display: -webkit-box;
                line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
        }

        .type-tag {
                font-size: 0.55rem;
                font-weight: 900;
                text-transform: uppercase;
                padding: 1px 6px;
                border-radius: 4px;
                margin-bottom: 2px;
                display: inline-block;
        }
            .history-lbl-header { font-size: 0.65rem; font-weight: 800; color: #146C32; text-transform: uppercase; letter-spacing: 0.5px; }

</style>

<div class="history-block-container">
        <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="history-lbl-header"><i class="bi bi-calendar-event-fill me-1"></i> History of the day
                </div>
                <a href="history-manager.php" class="btn btn-sm p-0 text-muted fs-6"><i
                                class="bi bi-arrow-right-circle"></i></a>
        </div>

        <div class="event-mini-list">
                <?php
                if ($res_history->num_rows > 0):
                        while ($ev = $res_history->fetch_assoc()):
                                // আইকন সিলেকশন
                                $icon = match ($ev['category']) {
                                        'Scientist' => 'bi-atom',
                                        'Poet', 'Writer' => 'bi-pen',
                                        'Politicial' => 'bi-bank',
                                        'Sports' => 'bi-trophy',
                                        default => 'bi-info-circle'
                                };

                                // টাইপ কালার
                                $t_cls = match ($ev['type']) {
                                        'Birth' => 'bg-success-subtle text-success',
                                        'Death' => 'bg-danger-subtle text-danger',
                                        default => 'bg-primary-subtle text-primary'
                                };
                                ?>
                                <div class="event-mini-item">
                                        <div class="mini-icon <?= $t_cls ?> shadow-xs">
                                                <i class="bi <?= $icon ?>"></i>
                                        </div>
                                        <div>
                                                <span class="type-tag <?= $t_cls ?>"><?= $ev['type'] ?></span>
                                                <div class="mini-details">
                                                        <?= $ev['details'] ?>
                                                </div>
                                        </div>
                                </div>
                        <?php
                        endwhile;
                else:
                        // যদি ডাটা না থাকে তবে একটি প্লেসহোল্ডার মেসেজ
                        echo '<div class="text-center py-3 opacity-25">
                    <i class="bi bi-hourglass-split display-6"></i>
                    <div class="small fw-bold">No events recorded for today</div>
                  </div>';
                endif;
                ?>
        </div>
</div>