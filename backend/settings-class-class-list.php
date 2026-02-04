<?php
$sessionyear = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] ?? $_COOKIE['query-session'] ?? $SY;

/**
 * Class List View - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Backgrounds | Dynamic Icons
 */

// ১. ইউনিক ক্লাসগুলো ফেচ করা (Group by areaname)
$sql_classes = "
    SELECT DISTINCT areaname
    FROM areas
    WHERE (user='$rootuser' OR sccode='$sccode')
    AND sessionyear LIKE '%$sessionyear%'
    ORDER BY areaname
    ";
$res_classes = $conn->query($sql_classes);

if ($res_classes->num_rows > 0) {
    while ($row_cls = $res_classes->fetch_assoc()) {
        $class_name = $row_cls["areaname"];
        
        // ২. ক্লাসের আইকন লজিক (8px Radius)
        $icon_url = $BASE_PATH_URL . 'class-icons/' . strtolower($class_name) . ".png";
        $display_icon = (file_exists($icon_url)) ? $icon_url : "https://eimbox.com/teacher/no-img.jpg";
        ?>

        <div class="m3-class-card shadow-sm mb-3">
            <div class="card-body p-3">
                
                <div class="d-flex align-items-center mb-3">
                    <div class="class-avatar-box me-3 shadow-sm">
                        <img src="<?php echo $display_icon; ?>" alt="Class Icon" 
                             onerror="this.src='https://eimbox.com/teacher/no-img.jpg'">
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark fs-5" style="line-height: 1.1;"><?php echo $class_name; ?></div>
                        <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Institutional Class</div>
                    </div>
                </div>

                <div class="section-container">
                    <?php
                    // ৩. বর্তমান ক্লাসের অধীনে থাকা সেকশনগুলো ফেচ করা
                    $sql_sections = "SELECT * FROM areas WHERE user='$rootuser' AND sessionyear LIKE '%$sessionyear%' AND areaname= '$class_name' ORDER BY idno, id";
                    $res_sections = $conn->query($sql_sections);
                    
                    if ($res_sections->num_rows > 0) {
                        while ($row_sec = $res_sections->fetch_assoc()) {
                            $sid = $row_sec["id"];
                            $sname = $row_sec["subarea"];
                            ?>
                            
                            <div class="m3-section-row d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <span id="cls_name_<?php echo $sid; ?>" hidden><?php echo $class_name; ?></span>
                                    <div class="fw-bold text-dark text-truncate" id="sec_name_<?php echo $sid; ?>">
                                        <i class="bi bi-layers-half me-1 text-primary"></i> <?php echo $sname; ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;">SECTION / GROUP ID: <?php echo $sid; ?></div>
                                </div>
                                
                                <div class="d-flex gap-1">
                                    <button class="btn btn-m3-icon text-primary" onclick="editClassEntry(<?php echo $sid; ?>);">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-m3-icon text-danger" onclick="deleteClassEntry(<?php echo $sid; ?>);">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    <?php }
} else {
    echo '<div class="text-center py-5 opacity-25"><i class="bi bi-folder-x display-1"></i><p class="fw-bold">No classes defined yet.</p></div>';
}
?>

<style>
    /* M3-EIM-Floating Class Specific Styles */
    .m3-class-card {
        background: #fff;
        border-radius: 8px !important; /* Strict 8px */
        border: 1px solid #f0f0f0;
        transition: transform 0.2s ease;
    }

    .class-avatar-box {
        width: 48px; height: 48px;
        border-radius: 8px !important;
        overflow: hidden;
        background: #F3EDF7;
        flex-shrink: 0;
        border: 1px solid #EADDFF;
    }
    .class-avatar-box img { width: 100%; height: 100%; object-fit: cover; }

    .m3-section-row {
        background-color: #F7F2FA; /* Tonal Surface */
        border-radius: 8px !important;
        padding: 10px 12px;
        margin-bottom: 6px;
        border: 1px solid transparent;
        transition: 0.2s;
    }
    .m3-section-row:hover { border-color: #EADDFF; background-color: #F3EDF7; }

    .btn-m3-icon {
        width: 32px; height: 32px;
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        background: #fff;
        border: 1px solid #eee;
        padding: 0;
        font-size: 0.9rem;
    }
    .btn-m3-icon:active { background: #EADDFF; transform: scale(0.9); }

    /* Animation */
    .animated-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>