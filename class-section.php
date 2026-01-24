<?php
$page_title = "Classes & Sections";
include 'inc.php';
?>

<main>
    <div class="hero-container">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff;">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; line-height: 1.1;">Classes & Sections</div>
                <div style="font-size: 0.75rem; opacity: 0.8; font-style: italic;">Academic Structure Overview</div>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <span class="session-pill">SESSION <?php echo $sessionyear; ?></span>
            </div>
            <div style="text-align: right;">
                <div id="cnt" style="font-size: 1.8rem; font-weight: 900; line-height: 1;">0</div>
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.9;">Total
                    Students</div>
            </div>
        </div>
    </div>

    <?php
    // ---------- LOAD CLASSES ----------
    $classes = [];

    $sql0 = "SELECT * FROM areas 
            WHERE sessionyear LIKE '%$sessionyear_param%' 
              AND user='$rootuser'
            ORDER BY FIELD(areaname,'Six','Seven','Eight','Nine','Ten'),
                     subarea, idno";

    $result0 = $conn->query($sql0);

    if ($result0->num_rows > 0) {

        while ($row0 = $result0->fetch_assoc()) {

            $cls = $row0["areaname"];

            $classes[$cls][] = $row0;
        }

        // ---------- OUTPUT GROUPED ----------
        foreach ($classes as $className => $sections) {

            echo '<div class="m3-section-title" style="padding: 0 8px;">' . strtoupper($className) . '</div>';
            echo '<div class="widget-gridx">';

            foreach ($sections as $row0) {

                $cls = $row0["areaname"];
                $sec = $row0["subarea"];

                $ico = 'iimg/' . strtolower(substr($sec, 0, 5)) . '.png';
                if (!file_exists($ico)) {
                    $ico = 'iimg/default.png';
                }

                $lnk = "cls=" . $cls . '&sec=' . $sec;
                ?>

                <div class="tool-card shadow-sm" onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">

                    <div class="icon-box c-inst">
                        <img src="<?php echo $ico; ?>" onerror="this.src='iimg/default.png'"
                            style="width:28px;height:28px;object-fit:contain;" />
                    </div>

                    <div class="item-info">
                        <div class="st-title" style="text-transform: uppercase;">
                            <?php echo $cls; ?>
                        </div>
                        <div class="st-desc">
                            Section:
                            <span style="color: var(--m3-primary); font-weight:600;">
                                <?php echo $sec; ?>
                            </span>
                        </div>
                    </div>

                    <div style="color: var(--m3-outline); font-size: 1.2rem;">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>

                <?php
            }

            echo '</div>';
        }

    } else { ?>

        <div style="text-align: center; padding: 40px; color: var(--m3-outline);">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p>No sections found for this session.</p>
        </div>

    <?php } ?>
</main>

<div style="height:80px;"></div>

<?php include_once 'footer.php'; ?>