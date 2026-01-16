<?php
include 'inc.php'; // DB সংযোগ এবং সেশন লোড করবে

$sccode = $_SESSION['sccode'];
$sy = $_SESSION['sessionyear'];
$userid = $_SESSION['userid'];

// ১. ডাটা ফেচিং (Prepared Statement)
$subjects_taught = [];
$sql = "SELECT DISTINCT subject, classname, sectionname 
        FROM subsetup 
        WHERE sessionyear = ? AND sccode = ? AND tid = ? 
        ORDER BY classname, sectionname, subject";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sy, $sccode, $userid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $subjects_taught[] = $row;
}
$stmt->close();

// সাবজেক্টের বিস্তারিত তথ্যের জন্য ইনক্লুড
include_once 'datam/datam-subject-list.php'; 
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* M3 Card Style */
    .subject-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 16px;
        margin-bottom: 12px;
        border: none;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }
    .subject-card:active { transform: scale(0.98); background-color: #F3EDF7; }

    /* Book Thumbnail */
    .book-thumb {
        width: 60px; height: 80px;
        border-radius: 12px;
        object-fit: cover;
        background-color: #E7E0EC;
        margin-right: 16px;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* M3 Tonal Chips/Badges */
    .m3-chip {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 4px;
    }
    .chip-class { background-color: #EADDFF; color: #21005D; }
    .chip-section { background-color: #E3F2FD; color: #1976D2; }
    .chip-code { background-color: #F3EDF7; color: #6750A4; border: 1px solid #CAC4D0; }

    .sub-title { font-size: 1rem; font-weight: 700; color: #1C1B1F; margin-bottom: 2px; }
    .sub-ben { font-size: 0.85rem; color: #49454F; margin-bottom: 4px; }

    /* Top App Bar */
    .m3-app-bar {
        background-color: white;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-4">
        <div class="d-flex align-items-center">
            <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">My Subjects</h4>
                <small class="text-muted">Academic Session <?php echo $sy; ?></small>
            </div>
        </div>
    </div>

    <div class="container px-3">
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

                // ইমেজ পাথ লজিক
                $img_name = strtolower($sctype . '_' . $clsname . '_' . $subcode . '_cover.jpg');
                $local_path = 'books/' . $img_name;
                $display_path = $BASE_PATH_URL_FILE . 'books/' . $img_name;

                if (!file_exists($local_path)) {
                    $display_path = $BASE_PATH_URL_FILE . 'books/no-image.png';
                }
            ?>
                <div class="subject-card shadow-sm">
                    <img src="<?php echo $display_path; ?>" class="book-thumb" alt="Cover" 
                         onerror="this.src='https://eimbox.com/images/no-book-cover.png';">
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="sub-title text-truncate"><?php echo htmlspecialchars($seng); ?></div>
                        <div class="sub-ben"><?php echo htmlspecialchars($sben); ?></div>
                        
                        <div class="d-flex flex-wrap gap-1">
                            <span class="m3-chip chip-class"><?php echo htmlspecialchars($clsname); ?></span>
                            <span class="m3-chip chip-section"><?php echo htmlspecialchars($secname); ?></span>
                        </div>
                    </div>

                    <div class="ms-2 text-end">
                        <div class="m3-chip chip-code">#<?php echo $subcode; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-book display-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">No subjects have been assigned to you yet.</p>
                <a href="reporthome.php" class="btn btn-outline-primary rounded-pill">Go Back</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 70px;"></div>

<?php include 'footer.php'; ?>