<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = "%" . $current_session . "%";

$sccode = $_SESSION['sccode'];
$page_title = "Notice Board";

// ২. ডাটা ফেচিং (Prepared Statement - Secure)
// নোটিশ যদি সেশন ভিত্তিক হয় তবে LIKE ব্যবহার করা হয়েছে
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
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* M3 Standard App Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* M3 Accordion Notice Card (8px Radius) */
    .notice-card {
        background: #fff; border: 1px solid #eee; border-radius: 8px !important;
        margin: 0 8px 8px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    
    .accordion-item { border: none !important; background: transparent !important; }
    
    .accordion-button {
        padding: 12px 16px; font-size: 0.9rem; font-weight: 700; color: #1C1B1F;
        background-color: #fff !important; box-shadow: none !important;
    }
    .accordion-button:not(.collapsed) { 
        color: #6750A4; background-color: #F3EDF7 !important; 
        border-bottom: 1px solid #EADDFF; 
    }
    .accordion-button::after { transform: scale(0.8); }

    .notice-icon-wrapper {
        width: 32px; height: 32px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; flex-shrink: 0;
    }

    .notice-body { background: #fff; padding: 16px; font-size: 0.85rem; color: #49454F; line-height: 1.5; }
    
    .notice-meta {
        font-size: 0.7rem; color: #79747E; font-weight: 500;
        margin-bottom: 8px; display: flex; justify-content: space-between;
    }

    .btn-fab {
        position: fixed; bottom: 80px; right: 20px;
        width: 56px; height: 56px; border-radius: 16px;
        background: #EADDFF; color: #21005D;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); border: none;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <i class="bi bi-search fs-5 me-2"></i>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-2">
    <?php if (count($notices) > 0): ?>
        <div class="accordion" id="noticesAccordion">
            <?php 
            $sl = 0;
            foreach ($notices as $notice):
                $sl++;
                $icon = $notice['icon'] ?: 'megaphone';
                $color = $notice['color'] ?: '#6750A4';
                $author = $notice['profilename'] ?: 'Admin';
            ?>
                <div class="notice-card shadow-sm accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $sl; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?php echo $sl; ?>" aria-expanded="false">
                            <div class="notice-icon-wrapper shadow-sm" style="background: <?php echo $color; ?>20; color: <?php echo $color; ?>;">
                                <i class="bi bi-<?php echo htmlspecialchars($icon); ?>"></i>
                            </div>
                            <span class="text-truncate"><?php echo htmlspecialchars($notice['title']); ?></span>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $sl; ?>" class="accordion-collapse collapse" 
                         data-bs-parent="#noticesAccordion">
                        <div class="notice-body">
                            <div class="notice-meta border-bottom pb-2">
                                <span><i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($author); ?></span>
                                <span><i class="bi bi-calendar3 me-1"></i> <?php echo date('d M, y', strtotime($notice['entrytime'])); ?></span>
                            </div>
                            <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($notice['descrip'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 opacity-25">
            <i class="bi bi-chat-left-dots display-1"></i>
            <p class="fw-bold mt-2">No active notices for session <?php echo $current_session; ?></p>
        </div>
    <?php endif; ?>
</main>

<?php if($userlevel == 'Administrator'): ?>
    <button class="btn-fab shadow"><i class="bi bi-pencil-fill fs-4"></i></button>
<?php endif; ?>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>