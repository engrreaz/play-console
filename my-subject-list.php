<?php
$page_title = "My Subjects";
include 'inc.php'; // DB সংযোগ এবং সেশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং ( Priority: GET > COOKIE > Default $sy)



// ২. ডাটা ফেচিং (Secure Prepared Statement)
// echo $sessionyear_param . '/' . $sccode . '/' . $userid . '/';

$subjects_taught = [];

$sql = "
    SELECT distinct subcode, classname, sectionname FROM clsroutine  WHERE sccode = ? AND sessionyear LIKE ? AND tid = ? ORDER BY subcode;
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sss", $sccode,
    $sessionyear_param,
    $userid
);

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $subjects_taught[] = $row;
}
$stmt->close();

// var_dump($subjects_taught);


// সাবজেক্ট বিস্তারিত ডাটা ম্যাপ
include_once 'datam/datam-subject-list.php'; 
?>

<style>
    /* Subject Page Specific M3 Enhancements */
    .book-wrapper {
        width: 58px; height: 76px;
        border-radius: 8px; /* Strict 8px */
        overflow: hidden;
        margin-right: 16px;
        flex-shrink: 0;
        background: var(--m3-tonal-surface);
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .book-wrapper img { width: 100%; height: 100%; object-fit: cover; }

    .sub-code-badge {
        font-size: 0.6rem;
        background: var(--m3-tonal-surface);
        color: var(--m3-primary);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 800;
        border: 1px dashed var(--m3-outline);
    }

    .m3-subject-chip {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
        
                <div>
                    <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">My Subjects</div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Teaching Assignments</div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="widget-grid" style="margin-top: 15px; padding: 0 12px;">
        <div class="m3-section-title" style="margin-left: 4px;">Assigned Subjects</div>

        <?php if (!empty($subjects_taught)): ?>
            <?php 
            foreach ($subjects_taught as $info):
                $subcode = $info['subcode'];
                $stind = array_search($subcode, array_column($datam_subject_list, 'subcode'));

                if ($stind === false) continue;

                $seng = $datam_subject_list[$stind]["subject"];
                $sben = $datam_subject_list[$stind]["subben"];
                $clsname = $info['classname'];
                $secname = $info['sectionname'];

                // ইমেজ পাথ জেনারেশন
                $img_name = strtolower($sctype . '_' . $clsname . '_' . $subcode . '_cover.jpg');
                $display_path = $BASE_PATH_URL_FILE . 'assets/books/allbook.webp';
            ?>
                <div class="m3-list-item" style="padding: 12px; margin-bottom: 10px; align-items: center;">
                    <div class="book-wrapper">
                        <img src="<?php echo $display_path; ?>" 
                      
                             alt="Book Cover">
                    </div>

                    <div class="item-info">
                        <div class="st-title" style="font-size: 1rem; color: #1C1B1F; line-height: 1.2;">
                            <?php echo htmlspecialchars($seng); ?>
                        </div>
                        <div class="st-desc" style="font-size: 0.82rem; color: #49454F; margin-bottom: 8px;">
                            <?php echo htmlspecialchars($sben); ?>
                        </div>

                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <span class="m3-subject-chip c-inst">
                                <i class="bi bi-mortarboard-fill"></i> <?php echo htmlspecialchars($clsname); ?>
                            </span>
                            <span class="m3-subject-chip c-acad">
                                <i class="bi bi-diagram-2-fill"></i> <?php echo htmlspecialchars($secname); ?>
                            </span>
                        </div>
                    </div>

                    <div style="text-align: right; min-width: 50px;">
                        <div class="sub-code-badge">#<?php echo $subcode; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                <i class="bi bi-journal-x" style="font-size: 3.5rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No Subjects Found</div>
                <div style="font-size: 0.75rem;">Contact administrator for assignments.</div>
            </div>
        <?php endif; ?>
    </div>
</main>

<div style="height: 80px;"></div>

<?php include_once 'footer.php'; ?>





</body>
</html>