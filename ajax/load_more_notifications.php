<?php
include_once '../inc.light.php';



$offset = intval($_POST['offset'] ?? 10);
$limit = intval($_POST['limit'] ?? 10);
$user_id = $user_id_no; // inc.php থেকে প্রাপ্ত

$sql = "SELECT id, type, title, message, link, is_read, created_at 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY is_read ASC, created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($n = $result->fetch_assoc()) {
        $meta = getNotifMeta($n['type']); // এই ফাংশনটি inc.php তে থাকতে হবে
        $is_unread = ($n['is_read'] == 0);
        
        // এখানে কার্ডের HTML টেমপ্লেটটি ইকো করুন
        echo '
        <div class="notif-item shadow-sm '.($is_unread ? 'unread' : '').'" id="notif-'.$n['id'].'" 
             onclick="handleNotification('.$n['id'].', \''.$n['link'].'\');">
            <div class="notif-icon-box" style="background: '.$meta['color'].'15; color: '.$meta['color'].';">
                <i class="bi bi-'.$meta['icon'].'"></i>
            </div>
            <div class="notif-content">
                <div class="notif-title">'.htmlspecialchars($n['title']).'</div>
                <div class="notif-msg">'.htmlspecialchars($n['message']).'</div>
                <div class="notif-time"><i class="bi bi-clock me-1"></i>'.date('d M, h:i A', strtotime($n['created_at'])).'</div>
            </div>
        </div>';
    }
} else {
    echo "done"; // আর কোন ডাটা নেই
}
?>