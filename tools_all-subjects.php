<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-teacher.php';

// ১. প্যারামিটার এবং পেজ টাইটেল
$page_title = "Subjects Repository";
$sy_param = "%$sy%";
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* M3 Card Style */
    .class-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 16px;
        margin: 0 16px 10px;
        border: none;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }

    .class-card:active {
        background-color: #F3EDF7;
        transform: scale(0.98);
    }

    .class-icon-wrapper {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: #F3EDF7;
        padding: 4px;
        margin-right: 16px;
        flex-shrink: 0;
    }
    .class-icon-wrapper img { width: 100%; height: 100%; object-fit: contain; }

    .class-info { flex-grow: 1; overflow: hidden; }
    .class-name { font-weight: 800; color: #1C1B1F; font-size: 1rem; }
    .teacher-name { font-size: 0.75rem; color: #6750A4; font-weight: 600; margin-top: 2px; }
    
    /* Subject List Container (AJAX Target) */
    .subject-box {
        margin-top: 12px;
        border-top: 1px dashed #E7E0EC;
        padding-top: 12px;
        display: none; /* Default hidden */
    }

    .badge-m3 {
        background: #EADDFF; color: #21005D;
        padding: 4px 12px; border-radius: 8px;
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="javascript:history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <i class="bi bi-funnel"></i>
    </div>
</header>

<main class="pb-5 mt-3">
    <div class="container-fluid p-0">
        <?php
        // ২. ডাটা ফেচিং (Prepared Statement)
        // ক্লাসের লিস্ট নিয়ে আসা (৬ষ্ঠ থেকে ১০ম ক্রমানুসারে)
        $sql = "SELECT * FROM areas WHERE sessionyear LIKE ? AND user = ? ORDER BY FIELD(areaname,'Six', 'Seven', 'Eight', 'Nine', 'Ten'), idno, subarea";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $sy_param, $rootuser);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
                $cls = $row["areaname"];
                $sec = $row["subarea"];
                $clstid = $row["classteacher"];
                $id = $row["id"];

                // ক্লাস আইকন পাথ লজিক
                $icon_url = "https://eimbox.com/class-icons/" . strtolower($cls) . ".png";
                
                // ক্লাস টিচারের নাম বের করা
                $t_idx = array_search($clstid, array_column($datam_teacher_profile, 'tid'));
                $teacher_name = ($t_idx !== false) ? $datam_teacher_profile[$t_idx]['tname'] : "Teacher Not Assigned";
        ?>
            <div class="class-card shadow-sm" onclick="toggleSubjects(<?php echo $id; ?>)">
                <div class="d-flex align-items-center">
                    <div class="class-icon-wrapper shadow-sm">
                        <img src="<?php echo $icon_url; ?>" onerror="this.src='https://eimbox.com/images/default-class.png'">
                    </div>
                    
                    <div class="class-info">
                        <div class="class-name"><?php echo strtoupper($cls); ?> <span class="text-muted opacity-50 fw-normal">|</span> <?php echo $sec; ?></div>
                        <div class="teacher-name">
                            <i class="bi bi-person-workspace me-1"></i> <?php echo $teacher_name; ?>
                        </div>
                    </div>

                    <div class="ms-2">
                        <i class="bi bi-chevron-down text-muted" id="icon_<?php echo $id; ?>"></i>
                    </div>
                </div>

                <div class="subject-box" id="tailbox<?php echo $id; ?>"></div>
            </div>
        <?php endwhile; else: ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-folder-x display-1"></i>
                <p class="mt-2 fw-bold">No classes found for this session.</p>
            </div>
        <?php endif; $stmt->close(); ?>
    </div>
</main>

<div style="height: 60px;"></div>

<script>
    function toggleSubjects(id) {
        const box = $("#tailbox" + id);
        const icon = $("#icon_" + id);

        // যদি অলরেডি ওপেন থাকে তবে ক্লোজ করো
        if (box.is(":visible")) {
            box.slideUp(200);
            icon.removeClass("bi-chevron-up").addClass("bi-chevron-down");
        } else {
            // ডাটা ফেচ করা এবং ওপেন করা
            fetchSubList(id);
            box.slideDown(300);
            icon.removeClass("bi-chevron-down").addClass("bi-chevron-up");
        }
    }

    function fetchSubList(id) {
        const box = $("#tailbox" + id);
        
        // যদি ডাটা আগে থেকেই লোড করা থাকে তবে আবার রিকোয়েস্ট করবে না
        if (box.html() !== "") return;

        $.ajax({
            url: "backend/fetch-sub-list.php",
            type: "POST",
            data: { sccode: '<?php echo $sccode; ?>', id: id },
            beforeSend: function () {
                box.html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div><br><small class="text-muted">Loading Subjects...</small></div>');
            },
            success: function (html) {
                box.html(html);
            }
        });
    }

    function go(id) {
        window.location.href = "students.php?id=" + id;
    }
</script>

<?php include 'footer.php'; ?>