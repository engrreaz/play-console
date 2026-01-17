<?php
include 'inc.php'; // DB সংযোগ এবং সেশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

$page_title = "My Subjects";

// ২. ডাটা ফেচিং (Prepared Statement - Secure)
$subjects_taught = [];
$sql = "SELECT DISTINCT subject, classname, sectionname 
        FROM subsetup 
        WHERE sessionyear LIKE ? AND sccode = ? AND tid = ? 
        ORDER BY classname, sectionname, subject";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sy_param, $sccode, $userid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $subjects_taught[] = $row;
}
$stmt->close();

// সাবজেক্টের বিস্তারিত তথ্যের জন্য ডাটা ম্যাপ ইনক্লুড
include_once 'datam/datam-subject-list.php'; 
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* Full Width M3 Top Bar (8px Bottom Radius) */
    .m3-app-bar {
        width: 100%; height: 56px; background: #fff; display: flex; align-items: center; 
        padding: 0 16px; position: sticky; top: 0; z-index: 1050; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Condensed Subject Card (8px Radius) */
    .subject-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 10px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, background 0.15s;
        text-decoration: none !important; color: inherit;
    }
    .subject-card:active { transform: scale(0.98); background-color: #F3EDF7; }

    /* Thumbnail with 8px Radius */
    .book-thumb {
        width: 52px; height: 68px; border-radius: 8px; 
        object-fit: cover; background-color: #E7E0EC;
        margin-right: 14px; flex-shrink: 0; border: 1px solid #eee;
    }

    /* Tonal Chips (8px Radius) */
    .m3-chip {
        font-size: 0.65rem; font-weight: 800; padding: 2px 10px;
        border-radius: 6px; text-transform: uppercase;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .chip-class { background-color: #EADDFF; color: #21005D; }
    .chip-section { background-color: #E3F2FD; color: #1976D2; }
    
    .sub-name-eng { font-weight: 800; color: #1C1B1F; font-size: 0.95rem; line-height: 1.2; }
    .sub-name-ben { font-size: 0.8rem; color: #49454F; margin-bottom: 6px; }

    .session-badge {
        font-size: 0.65rem; background: #EADDFF; color: #21005D;
        padding: 2px 10px; border-radius: 4px; font-weight: 800;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="session-badge"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5 mt-3">
    <div class="px-3 mb-2 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">My Assignments</div>

    <div class="list-container px-1">
        <?php if (!empty($subjects_taught)): ?>
            <?php 
            foreach ($subjects_taught as $info):
                $subcode = $info['subject'];
                $stind = array_search($subcode, array_column($datam_subject_list, 'subcode'));

                if ($stind === false) continue;

                $seng = $datam_subject_list[$stind]["subject"];
                $sben = $datam_subject_list[$stind]["subben"];
                $clsname = $info['classname'];
                $secname = $info['sectionname'];

                // ইমেজ পাথ জেনারেশন
                $img_name = strtolower($sctype . '_' . $clsname . '_' . $subcode . '_cover.jpg');
                $display_path = $BASE_PATH_URL_FILE . 'books/' . $img_name;
            ?>
                <div class="subject-card shadow-sm">
                    <img src="<?php echo $display_path; ?>" class="book-thumb" alt="Cover" 
                         onerror="this.src='https://eimbox.com/images/no-book-cover.png';">
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="sub-name-eng text-truncate"><?php echo htmlspecialchars($seng); ?></div>
                        <div class="sub-name-ben"><?php echo htmlspecialchars($sben); ?></div>
                        
                        <div class="d-flex flex-wrap gap-1">
                            <span class="m3-chip chip-class">
                                <i class="bi bi-mortarboard-fill"></i> <?php echo htmlspecialchars($clsname); ?>
                            </span>
                            <span class="m3-chip chip-section">
                                <i class="bi bi-diagram-2-fill"></i> <?php echo htmlspecialchars($secname); ?>
                            </span>
                        </div>
                    </div>

                    <div class="ms-2 text-end opacity-50">
                        <div style="font-size: 0.6rem; font-weight: 800;">CODE</div>
                        <div class="fw-bold" style="font-size: 0.8rem;">#<?php echo $subcode; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-journal-x display-1"></i>
                <p class="fw-bold mt-2">No subjects assigned yet.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 75px;"></div> <?php include 'footer.php'; ?>