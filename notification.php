<?php
/**
 * Notifications Center - M3-EIM Standard
 * Table: notifications (id, user_id, type, title, message, link, is_read, created_at)
 */
$page_title = "Notifications";
include_once 'inc.php';

$feed_item = 10;
$unread_count = 0;

// ২. ডাটা ফেচিং (Prepared Statement)
$notifications = [];

// ২. প্রাথমিক ডাটা ফেচিং (শুধু প্রথম ১০টি)
$sql = "SELECT id, type, title, message, link, is_read, created_at 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY is_read ASC, created_at DESC LIMIT ?"; // এখানে Offset নেই, প্রথম ১০টি আনবে

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ii", $user_id_no, $feed_item);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    $stmt->close();
}

// আনরিড কাউন্টের জন্য আলাদা কুয়েরি (যা সব ডাটা চেক করবে)
$unread_q = $conn->query("SELECT COUNT(id) as total FROM notifications WHERE user_id = '$user_id_no' AND is_read = 0");

$unread_count = $unread_q->fetch_assoc()['total'];

?>

<style>
    /* List Container */
    .notif-container {
        padding: 12px 8px 100px;
    }

    /* M3 Card Style */
    .notif-item {
        background: #fff;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 8px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        gap: 14px;
        transition: 0.2s;
        cursor: pointer;
        position: relative;
    }

    .notif-item.unread {
        background-color: #F3EDF7;
        border-color: #EADDFF;
    }

    .notif-item:active {
        transform: scale(0.98);
        background: #EADDFF;
    }

    .notif-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.2rem;
    }

    .notif-content {
        flex-grow: 1;
        overflow: hidden;
    }

    .notif-title {
        font-weight: 850;
        font-size: 0.9rem;
        color: #1C1B1F;
        margin-bottom: 2px;
    }

    .notif-msg {
        font-size: 0.8rem;
        color: #49454F;
        line-height: 1.4;
    }

    .notif-time {
        font-size: 0.7rem;
        color: #79747E;
        margin-top: 6px;
        font-weight: 600;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #6750A4;
        border-radius: 50%;
        position: absolute;
        top: 16px;
        right: 16px;
    }

    .btn-read-all {
        background: #EADDFF;
        color: #21005D;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
    }

    /* M3 Stylish Load More Button */
    .load-more-container {
        padding: 20px 0 40px;
        text-align: center;
    }

    .btn-m3-load {
        background-color: #EADDFF;
        /* M3 Primary Tonal Container */
        color: #21005D;
        /* M3 On-Tonal Container */
        border: none;
        padding: 12px 32px;
        border-radius: 100px;
        /* Pill Shape */
        font-size: 0.85rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .btn-m3-load:hover {
        background-color: #D0BCFF;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .btn-m3-load:active {
        transform: scale(0.95);
        background-color: #B69DF8;
    }

    .btn-m3-load:disabled {
        background-color: #F3EDF7;
        color: #938F99;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* লোডিং স্পিনারের জন্য কাস্টম অ্যানিমেশন */
    .spinner-m3 {
        width: 18px;
        height: 18px;
        border: 3px solid rgba(33, 0, 93, 0.2);
        border-top: 3px solid #21005D;
        border-radius: 50%;
        animation: spin-m3 0.8s linear infinite;
    }

    @keyframes spin-m3 {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<header class="m3-app-bar">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h5 class="fw-bold mb-0">Notifications</h5>
        <?php if ($unread_count > 0): ?>
            <button class="btn-read-all" onclick="markAllAsRead();">
                <i class="bi bi-check-all"></i> MARK ALL
            </button>
        <?php endif; ?>
    </div>
</header>


<main class="notif-container">
    <div id="notif-list-wrapper">
        <?php foreach ($notifications as $n):
            $meta = getNotifMeta($n['type']);
            $is_unread = ($n['is_read'] == 0);
            ?>
            <div class="notif-item shadow-sm <?php echo $is_unread ? 'unread' : ''; ?>" id="notif-<?php echo $n['id']; ?>"
                onclick="handleNotification(<?php echo $n['id']; ?>, '<?php echo $n['link']; ?>');">

                <div class="notif-icon-box"
                    style="background: <?php echo $meta['color']; ?>15; color: <?php echo $meta['color']; ?>;">
                    <i class="bi bi-<?php echo $meta['icon']; ?>"></i>
                </div>

                <div class="notif-content">
                    <div class="notif-title"><?php echo htmlspecialchars($n['title']); ?></div>
                    <div class="notif-msg"><?php echo htmlspecialchars($n['message']); ?></div>
                    <div class="notif-time">
                        <i class="bi bi-clock me-1"></i>
                        <?php echo date('d M, h:i A', strtotime($n['created_at'])); ?>
                    </div>
                </div>

                <?php if ($is_unread): ?>
                    <div class="unread-dot"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="load-more-container" id="load-more-sec">
        <?php if (count($notifications) >= $feed_item): ?>
            <button class="btn-m3-load shadow-sm" id="loadMoreBtn" onclick="loadMoreNotif();">
                <i class="bi bi-arrow-down-short fs-5"></i>
                <span>SHOW OLDER NOTIFICATIONS</span>
            </button>
        <?php endif; ?>
    </div>
</main>


<?php include 'footer.php'; ?>

<script>
    /**
     * নোটিফিকেশন হ্যান্ডলিং (Mark Read + Redirect)
     */
    function handleNotification(id, link) {
        $.post('ajax/mark_notification_read.php', { id: id }, function () {
            if (link && link !== '#') {
                window.location.href = link;
            } else {
                // লিঙ্ক না থাকলে জাস্ট UI আপডেট
                const item = document.getElementById('notif-' + id);
                item.classList.remove('unread');
                const dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();
            }
        });
    }

    function markAllAsRead() {
        Swal.fire({
            title: 'Mark all as read?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ajax/mark_notification_read.php', { all: true }, function () {
                    location.reload();
                });
            }
        });
    }

    let currentOffset = <?php echo $feed_item; ?>;
    const limit = <?php echo $feed_item; ?>;

    function loadMoreNotif() {
        const btn = document.getElementById('loadMoreBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        btn.disabled = true;

        $.post('ajax/load_more_notifications.php', {
            offset: currentOffset,
            limit: limit
        }, function (data) {
            if (data.trim() === "done") {
                document.getElementById('load-more-sec').innerHTML = '<p class="small text-muted">No more notifications</p>';
            } else {
                $('#notif-list-wrapper').append(data); // নতুন ডাটা নিচে যোগ হবে
                currentOffset += limit; // অফসেট বাড়ানো হলো
                btn.innerHTML = '<i class="bi bi-arrow-down-short"></i> LOAD MORE';
                btn.disabled = false;
            }
        });
    }
</script>