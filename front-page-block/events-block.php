<?php
// File: front-page-block/events-block.php

// --- Data Fetching & Logic ---
$events_today = [];
if (isset($conn, $sccode, $td)) {
    $stmt_events = $conn->prepare("SELECT * FROM calendar WHERE sccode = ? AND date = ? AND descrip != '' ORDER BY id");
    $stmt_events->bind_param("ss", $sccode, $td);
    $stmt_events->execute();
    $result_events = $stmt_events->get_result();
    if ($result_events->num_rows > 0) {
        $events_today = $result_events->fetch_all(MYSQLI_ASSOC);
    }
    $stmt_events->close();
}

$event_block_visible = !empty($events_today);

// This block should not control the visibility of other blocks.
// We will determine if today is a class day based on events.
$is_class_day_based_on_events = true;
if ($event_block_visible) {
    foreach ($events_today as $event) {
        if (isset($event['class']) && $event['class'] == 0) { // Assuming 0 means no classes
            $is_class_day_based_on_events = false;
            break;
        }
    }
}


// --- Presentation ---
if ($event_block_visible):
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6 class="card-title fw-bold">Today's Events</h6>
        <ul class="list-group list-group-flush">
            <?php foreach ($events_today as $event): ?>
                <li class="list-group-item px-0 d-flex align-items-start">
                    <i class="bi bi-<?php echo htmlspecialchars($event['icon'] ?? 'calendar-event'); ?> me-3" style="font-size: 1.5rem; color: <?php echo htmlspecialchars($event['color'] ?? '#6c757d'); ?>;"></i>
                    <div class="flex-grow-1">
                        <div class="fw-bold"><?php echo htmlspecialchars($event['descrip']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($event['category']); ?></small>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php 
endif; 
?>
