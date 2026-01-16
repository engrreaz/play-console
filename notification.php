<?php
session_start();

// Essential includes and session checks
// The inc.php file should ideally handle database connection ($conn) 
// and global variables like $usr, $sccode, and user-level details.
include_once 'inc.inc.php'; 
include_once 'header.php';

// --- Data Fetching ---
$notifications = [];
$unread_count = 0;

// Fetch notifications from the database
$sql = "SELECT id, title, smstext, datetime, rwstatus, icon, color, value, fromuserid 
        FROM notification 
        WHERE tomail = ? AND sccode = ? 
        ORDER BY rwstatus ASC, datetime DESC"; // Unread (0) first, then by date

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $usr, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['rwstatus'] == 0) {
            $unread_count++;
        }
        $notifications[] = $row;
    }
    $stmt->close();
}
?>

<!-- Material Design styles for this page -->
<style>
    .notification-list-item.unread {
        background-color: #f0f8ff; /* A light blue for unread items */
    }
    .notification-list-item .material-icons {
        vertical-align: middle;
    }
    .notification-body {
        flex-grow: 1;
        margin-left: 16px;
        margin-right: 16px;
    }
    .mdc-list-item__meta {
        align-self: center;
    }
</style>

<main class="container my-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">Notifications</h4>
        <?php if ($unread_count > 0): ?>
            <!-- MDB Text Button -->
            <button id="mark-all-read-btn" class="btn btn-link" onclick="markAllAsRead();">
                <i class="material-icons me-1" style="font-size:16px; vertical-align: text-bottom;">done_all</i>
                Mark all as read
            </button>
        <?php endif; ?>
    </div>

    <!-- Notifications Card -->
    <div class="card">
        <div class="card-body p-0">
            <?php if (count($notifications) > 0): ?>
                <!-- MDB List -->
                <ul class="list-group list-group-flush" id="notification-list">
                    <?php 
                    foreach ($notifications as $notification):
                        $is_unread = $notification['rwstatus'] == 0;
                        // Default to a standard notification icon if not specified
                        $icon = $notification['icon'] ?: 'notifications';
                    ?>
                        <li id="notification-<?php echo $notification['id']; ?>" class="list-group-item d-flex align-items-center notification-list-item <?php echo $is_unread ? 'unread fw-bold' : ''; ?>">
                            
                            <!-- Icon -->
                            <i class="material-icons text-primary"><?php echo htmlspecialchars($icon); ?></i>

                            <!-- Notification Text -->
                            <div class="notification-body">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                    <small class="text-muted"><?php echo date('d M, h:i A', strtotime($notification['datetime'])); ?></small>
                                </div>
                                <p class="mb-1 small text-muted"><?php echo htmlspecialchars($notification['smstext']); ?></p>
                            </div>
                            
                            <!-- Action Button -->
                            <?php if ($is_unread): ?>
                                <!-- MDB Icon Button (simulated with btn-link) -->
                                <button class="btn btn-link btn-sm mark-read-btn" title="Mark as read" onclick="markAsRead(<?php echo $notification['id']; ?>, event);">
                                    <i class="material-icons">check</i>
                                </button>
                            <?php endif; ?>

                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center p-5">
                    <i class="material-icons" style="font-size: 4rem; color: #aaa;">notifications_off</i>
                    <h5 class="mt-3">All Caught Up!</h5>
                    <p class="text-muted">You have no new notifications.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<div style="height:60px;"></div> <!-- Spacer for bottom nav bar -->

<?php include_once 'footer.php'; ?>

<!-- JavaScript for handling notification actions -->
<script>
function markAsRead(notificationId, event) {
    if (event) {
        event.stopPropagation();
    }

    fetch('ajax/mark_notification_read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.getElementById('notification-' + notificationId);
            if (notificationItem) {
                notificationItem.classList.remove('unread', 'fw-bold');
                const btn = notificationItem.querySelector('.mark-read-btn');
                if (btn) {
                    btn.remove();
                }
            }
            // You might want to update the global unread count here as well
        } else {
            // Using Swal for a better user experience, since it's in footer.php
            Swal.fire('Error', 'Failed to mark as read. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'An error occurred while communicating with the server.', 'error');
    });
}

function markAllAsRead() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to mark all notifications as read?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark all as read!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('ajax/mark_notification_read.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'all=true'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationList = document.getElementById('notification-list');
                    const unreadItems = notificationList.querySelectorAll('.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread', 'fw-bold');
                        const btn = item.querySelector('.mark-read-btn');
                        if (btn) {
                            btn.remove();
                        }
                    });
                    const markAllBtn = document.getElementById('mark-all-read-btn');
                    if(markAllBtn) {
                        markAllBtn.remove();
                    }
                    Swal.fire('Success', 'All notifications have been marked as read.', 'success');
                } else {
                    Swal.fire('Error', 'Failed to mark all as read. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while communicating with the server.', 'error');
            });
        }
    });
}
</script>
