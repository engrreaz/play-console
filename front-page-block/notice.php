<?php
// ফাইল: front-page-block/notice.php

// --- Data Fetching & Logic (Prepared & Optimized) ---
$notice_authors = [];
if (!empty($notices)) {
    $author_emails = array_filter(array_unique(array_column($notices, 'entryby')));

    if (!empty($author_emails)) {
        $placeholders = implode(',', array_fill(0, count($author_emails), '?'));
        $stmt = $conn->prepare("SELECT email, profilename FROM usersapp WHERE email IN ($placeholders)");
        $stmt->bind_param(str_repeat('s', count($author_emails)), ...$author_emails);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $notice_authors[$row['email']] = $row['profilename'];
            }
        }
        $stmt->close();
    }
}
?>

<style>
    .m3-notice-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .notice-item-m3 {
        background-color: #fff; border-radius: 8px; padding: 10px;
        margin-bottom: 8px; border: 1px solid #f0f0f0; transition: 0.2s;
        position: relative;
    }
    .notice-item-m3:active { background-color: #F7F2FA; transform: scale(0.99); }

    .notice-icon-box {
        width: 36px; height: 36px; border-radius: 8px; /* আপনার নির্দেশিত ৮ পিক্সেল */
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0; margin-right: 12px;
    }
    
    .n-title { font-size: 0.85rem; font-weight: 800; color: #1C1B1F; line-height: 1.2; }
    .n-meta { font-size: 0.65rem; font-weight: 600; color: #79747E; margin-top: 2px; }
    
    .notice-desc-box {
        font-size: 0.75rem; color: #49454F; line-height: 1.4;
        padding-top: 10px; border-top: 1px dashed #EADDFF; margin-top: 8px;
    }

    .btn-all-notices {
        background: #F3EDF7; color: #6750A4; border-radius: 8px;
        font-size: 0.7rem; font-weight: 800; padding: 8px;
        text-decoration: none !important; display: block; text-align: center;
        margin-top: 8px; border: 1px solid #EADDFF;
    }
</style>

<div class="m3-notice-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">
            <i class="bi bi-megaphone-fill me-1 text-primary"></i> Notice Board
        </span>
        <?php if (!empty($notices)): ?>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.6rem;">Latest</span>
        <?php endif; ?>
    </div>

    <?php if (empty($notices)): ?>
        <div class="text-center py-4 opacity-25">
            <i class="bi bi-chat-left-dots display-6"></i>
            <p class="small fw-bold mt-2 mb-0">No active notices.</p>
        </div>
    <?php else: ?>
        <div class="notice-list">
            <?php foreach (array_slice($notices, 0, 3) as $index => $notice): 
                $author = $notice_authors[$notice['entryby']] ?? 'System';
                $n_id = 'n_collapse_' . $index;
                $icon = htmlspecialchars($notice['icon'] ?? 'bell-fill');
                $color = htmlspecialchars($notice['color'] ?? '#6750A4');
            ?>
                <div class="notice-item-m3 shadow-sm">
                    <div class="d-flex align-items-center" data-bs-toggle="collapse" href="#<?php echo $n_id; ?>" role="button">
                        <div class="notice-icon-box" style="background: <?php echo $color; ?>15; color: <?php echo $color; ?>;">
                            <i class="bi bi-<?php echo $icon; ?>"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="n-title text-truncate"><?php echo htmlspecialchars($notice['title']); ?></div>
                            <div class="n-meta">
                                <?php echo date('d M, Y', strtotime($notice['entrytime'])); ?> 
                                <i class="bi bi-dot"></i> By <?php echo htmlspecialchars($author); ?>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down text-muted small ms-2" hidden></i>
                    </div>

                    <div class="collapse" id="<?php echo $n_id; ?>" hidden>
                        <div class="notice-desc-box">
                            <?php echo nl2br(htmlspecialchars($notice['descrip'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($notices) > 3): ?>
            <a href="notices.php?year=<?php echo $current_session; ?>" class="btn-all-notices shadow-sm">
                VIEW ALL NOTICES (<?php echo count($notices); ?>)
            </a>
        <?php endif; ?>
    <?php endif; ?>
</div>