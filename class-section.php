<?php
$page_title = "Classes & Sections";
include 'inc.php';

// ১. সেটিংস থেকে ক্লাসের তালিকা এবং সর্টিং অর্ডার বের করা
$class_order_raw = "";
foreach ($ins_all_settings as $setting) {
    if ($setting['setting_title'] === 'Classes') {
        $class_order_raw = $setting['settings_value'];
        break;
    }
}

// অর্ডার স্ট্রিং তৈরি করা (SQL FIELD এর জন্য)
$class_order_arr = explode(',', $class_order_raw);
$sql_field_order = "'" . implode("','", $class_order_arr) . "'";

// ২. চলমান সেশনের মোট শিক্ষার্থী সংখ্যা বের করা (Hero Section এর জন্য)
$sy_param = "%" . $sy . "%";
$stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM sessioninfo WHERE sccode = ? AND sessionyear LIKE ?");
$stmt_total->bind_param("is", $sccode, $sy_param);
$stmt_total->execute();
$total_students = $stmt_total->get_result()->fetch_assoc()['total'] ?? 0;
$stmt_total->close();
?>

<main>
    <div class="hero-container shadow-sm"
        style="background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%); color: white;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border-radius: 12px;">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; line-height: 1.1;">Classes & Sections</div>
                <div style="font-size: 0.75rem; opacity: 0.8; font-style: italic;">Academic Structure Overview</div>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <span class="session-pill"
                    style="background: rgba(238, 235, 238, 0.15); border: 1px solid rgba(247, 244, 248, 0.3); color:white;">
                    SESSION <?php echo $sessionyear; ?>
                </span>
            </div>
            <div style="text-align: right;">
                <div id="cnt" style="font-size: 1.8rem; font-weight: 900; line-height: 1;">
                    <?php echo $total_students; ?></div>
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; opacity: 0.9;">Total
                    Students</div>
            </div>
        </div>
    </div>

    <?php
    // ৩. ক্লাস, শাখা এবং শাখার শিক্ষার্থী সংখ্যা ফেচ করা (Joined Query for performance)
    $classes = [];
    $sql0 = "SELECT a.*, 
            (SELECT COUNT(*) FROM sessioninfo s 
             WHERE s.sccode = a.sccode 
               AND s.classname = a.areaname 
               AND s.sectionname = a.subarea 
               AND s.sessionyear LIKE '%$sessionyear_param%') as section_st_count
            FROM areas a 
            WHERE a.sessionyear LIKE '%$sessionyear_param%' 
              AND a.user='$rootuser'
            ORDER BY FIELD(a.areaname, $sql_field_order), a.subarea, a.idno";

    $result0 = $conn->query($sql0);

    if ($result0 && $result0->num_rows > 0) {
        while ($row0 = $result0->fetch_assoc()) {
            $cls = $row0["areaname"];
            $classes[$cls][] = $row0;
        }

        // ৪. আউটপুট গ্রুপিং
        foreach ($classes as $className => $sections) {
            echo '<div class="m3-section-title" style="padding: 12px 16px 4px; font-weight: 900; color: #6750A4; letter-spacing: 1px;">' . strtoupper($className) . '</div>';
            echo '<div class="widget-gridx" >';

            foreach ($sections as $row0) {
                $cls = $row0["areaname"];
                $sec = $row0["subarea"];
                $st_count = $row0["section_st_count"];

                // আইকন লজিক
                $ico = 'iimg/' . strtolower(substr($sec, 0, 5)) . '.png';
                if (!file_exists($ico)) {
                    $ico = 'iimg/default.png';
                }

                $lnk = "cls=" . $cls . '&sec=' . $sec;
                ?>

                <div class="tool-card shadow-sm"
                    style="background: white; border-radius: 12px; padding: 12px; display: flex; align-items: center; border: 1px solid #F0F0F0; cursor: pointer;"
                    onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">

                    <div class="icon-box"
                        style="width: 40px; height: 40px; background: #F3EDF7; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <img src="<?php echo $ico; ?>" onerror="this.src='iimg/default.png'"
                            style="width:24px; height:24px; object-fit:contain;" />
                    </div>

                    <div class="item-info" style="flex-grow: 1;">
                        <div class="st-title" style="font-size: 0.85rem; font-weight: 800; color: #1C1B1F;"><?php echo $sec; ?>
                        </div>
                        <div class="st-desc" style="font-size: 0.7rem; color: #79747E; font-weight: 600;">
                            Students: <span style="color: #6750A4;"><?php echo $st_count; ?></span>
                        </div>
                    </div>

                    <div style="color: #CAC4D0; font-size: 1rem;">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>

                <?php
            }
            echo '</div>'; // Grid End
        }
    } else { ?>
        <div style="text-align: center; padding: 60px 20px; color: #79747E;">
            <i class="bi bi-inbox-fill" style="font-size: 4rem; opacity: 0.2;"></i>
            <p style="font-weight: 600; margin-top: 10px;">No sections found in this session.</p>
        </div>
    <?php } ?>
</main>

<div style="height:80px;"></div>
<?php include_once 'footer.php'; ?>