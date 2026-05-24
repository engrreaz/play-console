<?php
$page_title = "Notice Board";
include 'inc.guest.php'; 

$current_session = $sy; 

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
        border-radius: 12px !important; 
        margin: 0 16px 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .accordion-button {
        padding: 16px;
        background: #fff !important;
        border: none !important;
        box-shadow: none !important;
        display: flex;
        align-items: center;
    }

    .accordion-button:not(.collapsed) {
        background: #EADDFF !important; /* M3 Tonal Surface */
        color: #21005D !important;
        border-bottom: 1px dashed #D0BCFF !important;
    }

    .notice-icon-box {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 16px; flex-shrink: 0;
        font-size: 1.4rem;
    }

    .notice-details {
        padding: 20px 16px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #49454F;
        background: #FAF8FC;
    }

    .notice-footer {
        display: flex; justify-content: space-between;
        margin-top: 16px; padding-top: 12px;
        border-top: 1px solid #ECE6F0;
        font-size: 0.75rem; font-weight: 700; color: #79747E;
    }
</style>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner" style="background: #FCE4EC; color: #31111D; border-bottom: 1px solid #F8BBD0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div class="icon-box-flat shadow-sm" style="background: #F8BBD0; color: #880E4F; width: 56px; height: 56px; font-size: 1.5rem;" >
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <div class="inst-title" style="color: #31111D;">Notice Board</div>
                    <div class="inst-meta" style="color: #880E4F; margin-bottom: 0;">Latest Announcements</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 2rem; font-weight: 900; line-height: 1; color: #31111D;"><?php echo count($notices); ?></div>
                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #880E4F;">Active News</div>
            </div>
        </div>
    </div>

    <!-- NOTICES LIST -->
    <div class="section-lbl">Announcements</div>
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
                        <div class="notice-icon-box" style="background: <?php echo $color; ?>20; color: <?php echo $color; ?>;">
                            <i class="bi bi-<?php echo $icon; ?>"></i>
                        </div>
                        <div style="flex-grow: 1; overflow: hidden;">
                            <div style="font-size: 0.95rem; font-weight: 800; color: #1C1B1F; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo $notice['title']; ?>
                            </div>
                            <div style="font-size: 0.75rem; color: #79747E; font-weight: 600; margin-top: 4px;">
                                <i class="bi bi-calendar-event me-1"></i> <?php echo date('d M, Y', strtotime($notice['entrytime'])); ?>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="not<?php echo $sl; ?>" class="accordion-collapse collapse" data-bs-parent="#noticeAccordion">
                    <div class="notice-details">
                        <p style="margin-bottom: 0;"><?php echo nl2br($notice['descrip']); ?></p>
                        
                        <div class="notice-footer">
                            <span><i class="bi bi-person-circle me-1"></i> <?php echo $author; ?></span>
                            <span><i class="bi bi-clock me-1"></i> <?php echo date('h:i A', strtotime($notice['entrytime'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; color: #79747E;">
                <i class="bi bi-chat-left-dots" style="font-size: 3.5rem; opacity: 0.5;"></i>
                <div style="font-weight: 800; margin-top: 16px; font-size: 1.1rem; color: #49454F;">No Notices Found</div>
                <div style="font-size: 0.85rem; margin-top: 4px;">Check back later for updates.</div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer-guest.php'; ?>