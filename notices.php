<?php
$page_title = "Notice Board";
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

$current_session = $sy; // ডিফল্ট সেশন

// ২. ডাটা ফেচিং (Prepared Statement - Secure)
$notices = [];
$sql = "SELECT n.title, n.descrip, n.icon, n.color, n.entrytime, u.profilename 
        FROM notice n
        LEFT JOIN usersapp u ON n.entryby = u.email AND n.sccode = u.sccode
        WHERE n.sccode = ? 
        ORDER BY n.entrytime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}
$stmt->close();
?>

<style>
    /* Notice Specific M3 Style */
    .notice-item-card {
        background: #fff;
        border-radius: 8px !important; /* Strict 8px */
        margin: 0 12px 10px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        box-shadow: var(--m3-shadow);
    }

    .accordion-button {
        padding: 14px 16px;
        background: #fff !important;
        border: none !important;
        box-shadow: none !important;
        display: flex;
        align-items: center;
    }

    .accordion-button:not(.collapsed) {
        background: var(--m3-tonal-surface) !important;
        color: var(--m3-primary);
        border-bottom: 1px dashed var(--m3-tonal-container) !important;
    }

    .notice-icon-box {
        width: 40px; height: 40px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0;
        font-size: 1.2rem;
    }

    .notice-details {
        padding: 16px;
        font-size: 0.88rem;
        line-height: 1.6;
        color: #444;
        background: #fff;
    }

    .notice-footer {
        display: flex; justify-content: space-between;
        margin-top: 12px; padding-top: 8px;
        border-top: 1px solid #f5f5f5;
        font-size: 0.7rem; font-weight: 700; color: #888;
    }

    /* FAB for Admin */
    .m3-fab-notice {
        position: fixed; bottom: 85px; right: 20px;
        background: var(--m3-primary-gradient);
        color: #fff; width: 56px; height: 56px;
        border-radius: 16px; display: flex;
        align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.3);
        z-index: 1050; border: none;
    }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" >
                    <i class="bi bi-chat-left-dots"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Notice Board</div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Latest Announcements</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.8rem; font-weight: 900; line-height: 1;"><?php echo count($notices); ?></div>
                <div style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Active News</div>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                ACADEMIC YEAR: <?php echo $current_session; ?>
            </span>
        </div>
    </div>

    <div class="px-2" style="margin-top: 15px; padding-bottom: 80px;">
        <div class="accordion" id="noticeAccordion">
            <?php 
            if (count($notices) > 0):
                $sl = 0;
                foreach ($notices as $notice):
                    $sl++;
                    $icon = $notice['icon'] ?: 'megaphone';
                    $color = $notice['color'] ?: '#6750A4';
                    $author = $notice['profilename'] ?: 'Admin';
            ?>
                <div class="notice-item-card accordion-item">
                    <h2 class="accordion-header" id="head<?php echo $sl; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#not<?php echo $sl; ?>">
                            <div class="notice-icon-box shadow-sm" style="background: <?php echo $color; ?>15; color: <?php echo $color; ?>;">
                                <i class="bi bi-<?php echo $icon; ?>"></i>
                            </div>
                            <div style="flex-grow: 1; overflow: hidden;">
                                <div style="font-size: 0.9rem; font-weight: 800; color: #1C1B1F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo $notice['title']; ?>
                                </div>
                                <div style="font-size: 0.7rem; color: #888; font-weight: 600;">
                                    <?php echo date('d M, Y', strtotime($notice['entrytime'])); ?>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="not<?php echo $sl; ?>" class="accordion-collapse collapse" data-bs-parent="#noticeAccordion">
                        <div class="notice-details">
                            <p style="margin-bottom: 15px;"><?php echo nl2br($notice['descrip']); ?></p>
                            
                            <div class="notice-footer">
                                <span><i class="bi bi-person-circle me-1"></i> Posted by: <?php echo $author; ?></span>
                                <span><i class="bi bi-clock me-1"></i> <?php echo date('h:i A', strtotime($notice['entrytime'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                    <i class="bi bi-chat-left-dots" style="font-size: 3.5rem;"></i>
                    <div style="font-weight: 800; margin-top: 10px;">No Notices Found</div>
                    <div style="font-size: 0.75rem;">Check back later for updates.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($userlevel == 'Administrator'): ?>
        <button class="m3-fab-notice shadow-lg" onclick="location.href='add-notice.php'">
            <i class="bi bi-plus-lg" style="font-size: 1.6rem;"></i>
        </button>
    <?php endif; ?>
</main>

<div style="height: 40px;"></div>



<?php include 'footer.php'; ?>