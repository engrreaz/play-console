<?php
include_once 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$page_title = "Notifications";
$notifications = [];
$unread_count = 0;

// ২. ডাটা ফেচিং (Prepared Statement - Secure)
$sql = "SELECT id, title, smstext, datetime, rwstatus, icon, color, value 
        FROM notification 
        WHERE tomail = ? AND sccode = ? 
        ORDER BY rwstatus ASC, datetime DESC LIMIT 50";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $usr, $sccode);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['rwstatus'] == 0) $unread_count++;
        $notifications[] = $row;
    }
    $stmt->close();
}
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* M3 Standard App Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Notification Item Card (8px Radius) */
    .notif-item {
        background-color: #fff; border-radius: 8px; padding: 12px;
        margin: 0 8px 6px; border: 1px solid #eee;
        display: flex; align-items: flex-start;
        transition: background 0.2s; position: relative;
    }
    .notif-item.unread { background-color: #F3EDF7; border-color: #EADDFF; }
    .notif-item:active { background-color: #EADDFF; }

    .notif-icon-box {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; flex-shrink: 0;
    }

    .notif-content { flex-grow: 1; overflow: hidden; }
    .notif-title { font-weight: 800; font-size: 0.85rem; color: #1C1B1F; margin-bottom: 2px; }
    .notif-text { font-size: 0.75rem; color: #49454F; line-height: 1.3; }
    .notif-time { font-size: 0.65rem; color: #79747E; margin-top: 4px; font-weight: 600; }

    .unread-dot {
        width: 8px; height: 8px; background-color: #6750A4;
        border-radius: 50%; position: absolute; top: 12px; right: 12px;
    }

    .btn-mark-all {
        background: transparent; color: #6750A4; border: none;
        font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 8px;
    }
    .btn-mark-all:active { background: #EADDFF; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <?php if ($unread_count > 0): ?>
            <button class="btn-mark-all" onclick="markAllAsRead();">
                <i class="bi bi-check2-all me-1"></i> READ ALL
            </button>
        <?php endif; ?>
    </div>
</header>

<main class="pb-5 mt-2">
    <?php if (count($notifications) > 0): ?>
        <div id="notification-list">
            <?php foreach ($notifications as $n): 
                $is_unread = ($n['rwstatus'] == 0);
                $icon = $n['icon'] ?: 'bell-fill';
                $color = $n['color'] ?: '#6750A4';
            ?>
                <div class="notif-item shadow-sm <?php echo $is_unread ? 'unread' : ''; ?>" 
                     id="notif-<?php echo $n['id']; ?>" onclick="markAsRead(<?php echo $n['id']; ?>);">
                    
                    <div class="notif-icon-box shadow-sm" style="background: <?php echo $color; ?>20; color: <?php echo $color; ?>;">
                        <i class="bi bi-<?php echo $icon; ?> fs-5"></i>
                    </div>

                    <div class="notif-content">
                        <div class="notif-title text-truncate"><?php echo htmlspecialchars($n['title']); ?></div>
                        <div class="notif-text"><?php echo htmlspecialchars($n['smstext']); ?></div>
                        <div class="notif-time"><?php echo date('d M, h:i A', strtotime($n['datetime'])); ?></div>
                    </div>

                    <?php if($is_unread): ?>
                        <div class="unread-dot"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 opacity-25">
            <i class="bi bi-app-indicator display-1"></i>
            <p class="fw-bold mt-2">No notifications yet.</p>
        </div>
    <?php endif; ?>
</main>

<div style="height: 65px;"></div> <script>
function markAsRead(id) {
    $.ajax({
        url: 'ajax/mark_notification_read.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            const item = document.getElementById('notif-' + id);
            item.classList.remove('unread');
            const dot = item.querySelector('.unread-dot');
            if(dot) dot.remove();
        }
    });
}

function markAllAsRead() {
    Swal.fire({
        title: 'Mark all as read?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6750A4',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('ajax/mark_notification_read.php', { all: true }, function() {
                location.reload();
            });
        }
    });
}
</script>

<?php include 'footer.php'; ?>