<?php
include_once '../inc.light.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // টাস্কের মূল তথ্য আনা (ঐচ্ছিক, যদি হেডার দেখাতে চান)
    $task_info = mysqli_query($conn, "SELECT module, panel FROM task_manager WHERE id='$id'");
    $ti = mysqli_fetch_assoc($task_info);

    $q = mysqli_query($conn, "SELECT * FROM task_response WHERE task_id='$id' ORDER BY id DESC");

    if (mysqli_num_rows($q) > 0) {
        echo '<div class="history-container p-2">';
        
        // হেডার ইনফো
        echo '<div class="mb-3 px-2">
                <h6 class="fw-black m-0 text-primary">'. $ti['module'] .'</h6>
                <small class="text-muted">History for '. $ti['panel'] .' panel</small>
              </div>';

        while ($row = mysqli_fetch_assoc($q)) {
            // স্ট্যাটাস অনুযায়ী কালার নির্ধারণ (আগের লজিক অনুযায়ী)
            $status = $row['response_status'];
            $color = match ($status) {
                'Stable', 'RC' => '#146C32',
                'Processing' => '#0288D1',
                'Beta', 'Trial' => '#6750A4',
                'On Hold' => '#FF9800',
                default => '#79747E'
            };

            echo '
            <div class="history-item-m3 mb-3 d-flex gap-3">
                <div class="d-flex flex-column align-items-center">
                    <div class="status-dot" style="background: '.$color.';"></div>
                    <div class="timeline-line"></div>
                </div>

                <div class="flex-grow-1 history-card-tonal" style="border-left: 4px solid '.$color.';">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-bold" style="color: '.$color.'; font-size: 0.85rem;">'.$status.'</span>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-clock me-1"></i>'. date("d M, h:i A", strtotime($row['entry_at'] ?? 'now')) .'
                        </small>
                    </div>
                    <p class="m-0 text-dark" style="font-size: 0.65rem; line-height: 1.4;">
                        '. nl2br(htmlspecialchars($row['notes'])) .'
                    </p>
                </div>
            </div>';
        }
        echo '</div>';
    } else {
        echo '
        <div class="text-center py-5 opacity-50">
            <i class="bi bi-chat-left-dots display-4"></i>
            <p class="mt-2 fw-bold">No history available for this task.</p>
        </div>';
    }
}
?>

<style>
    /* History Specific CSS */
    .history-card-tonal {
        background: #F7F2FA;
        padding: 12px 16px;
        border-radius: 8px 16px 16px 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: 6px;
        z-index: 2;
        box-shadow: 0 0 0 4px #F3EDF7;
    }
    
    .timeline-line {
        width: 2px;
        background: #EADDFF;
        flex-grow: 1;
        margin-top: 4px;
        border-radius: 1px;
    }

    .history-item-m3:last-child .timeline-line {
        display: none;
    }
    
    .fw-black { font-weight: 900; }
</style>