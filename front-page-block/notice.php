<?php
// File: front-page-block/notice.php

// --- Data Fetching & Logic ---

$notice_authors = [];
if (!empty($notices)) {
    // 1. Collect all unique author emails from the notices
    $author_emails = array_unique(array_column($notices, 'entryby'));

    // Filter out any empty email strings
    $author_emails = array_filter($author_emails);

    if (!empty($author_emails)) {
        // 2. Create placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($author_emails), '?'));
        
        // 3. Fetch all required author profiles in a single, secure query
        $stmt = $conn->prepare("SELECT email, profilename FROM usersapp WHERE email IN ($placeholders)");
        $stmt->bind_param(str_repeat('s', count($author_emails)), ...$author_emails);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // 4. Map emails to profile names for easy lookup
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $notice_authors[$row['email']] = $row['profilename'];
            }
        }
        $stmt->close();
    }
}


// --- Presentation ---
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
             <h6 class="card-title fw-bold mb-0">Notice Board</h6>
             <i class="bi bi-megaphone-fill text-muted" style="font-size: 1.5rem;"></i>
        </div>
       
        <?php if (empty($notices)): ?>
            <p class="text-muted small">No notices to display.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notices as $index => $notice):
                    $author_name = $notice_authors[$notice['entryby']] ?? 'System';
                    $notice_id = 'notice-desc-' . $index;
                    $icon = htmlspecialchars($notice['icon'] ?? 'bell');
                    $color = htmlspecialchars($notice['color'] ?? 'var(--bs-primary)');
                ?>
                    <div class="list-group-item list-group-item-action" aria-current="true">
                        <a class="text-decoration-none text-dark stretched-link" data-bs-toggle="collapse" href="#<?php echo $notice_id; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $notice_id; ?>">
                            <div class="d-flex w-100 align-items-center">
                                <i class="bi bi-<?php echo $icon; ?> me-3" style="font-size: 1.5rem; color: <?php echo $color; ?>;"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($notice['title']); ?></h6>
                                        <small class="text-muted flex-shrink-0 ms-2"><?php echo date('d/m/y', strtotime($notice['entrytime'])); ?></small>
                                    </div>
                                    <small class="text-muted">By <?php echo htmlspecialchars($author_name); ?></small>
                                </div>
                            </div>
                        </a>
                        <div class="collapse mt-2" id="<?php echo $notice_id; ?>">
                            <div class="text-muted small pt-2 border-top">
                                <?php echo nl2br(htmlspecialchars($notice['descrip'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($notices) > 3): ?>
            <div class="text-center mt-3">
                <a href="notices.php" class="btn btn-outline-primary btn-sm">Show All Notices</a>
            </div>
        <?php endif; ?>
    </div>
</div>
