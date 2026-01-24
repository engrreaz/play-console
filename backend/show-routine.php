<?php

include '../inc.light.php';
include '../datam/datam-subject-list.php';

// ইনপুট প্যারামিটার
$rootuser = $_POST['rootuser'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];

$today_num = date('w'); // 0=Sunday
$today_map = [0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 7];
$today_wday = $today_map[$today_num] ?? 1;


// দিনগুলোর নাম ম্যাপিং
$days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
?>

<style>
    /* M3 Routine Specific Styles */
    .period-section {
        background-color: #F3EDF7;
        /* Tonal Surface */
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #EADDFF;
    }

    .period-title {
        font-size: 0.75rem;
        font-weight: 900;
        text-transform: uppercase;
        color: #6750A4;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .routine-card {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        transition: 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .routine-card:active {
        background-color: #F7F2FA;
        transform: scale(0.98);
    }

    .day-box {
        width: 45px;
        text-align: center;
        border-right: 1px solid #eee;
        margin-right: 12px;
        padding-right: 8px;
        flex-shrink: 0;
    }

    .day-name {
        font-size: 0.6rem;
        font-weight: 900;
        text-transform: uppercase;
        color: #79747E;
    }

    .routine-info {
        flex-grow: 1;
        overflow: hidden;
    }

    .sub-name {
        font-weight: 800;
        color: #1C1B1F;
        font-size: 0.85rem;
    }

    .teacher-name {
        font-size: 0.7rem;
        color: #6750A4;
        font-weight: 700;
    }

    .btn-edit-tonal {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #F3EDF7;
        color: #6750A4;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Modal Floating Style */
    .modal-content {
        border-radius: 8px !important;
        border: none;
    }




</style>

<main class="px-2 pt-3">
    <?php
    // পিরিয়ড লুপ (১ থেকে ৮)
    
    for ($i = 1; $i <= 8; $i++):
        ?>
        <div class="period-section shadow-sm" id="period-<?php echo $i; ?>">
            <div class="period-title">
                <i class="bi bi-clock-fill me-2"></i> Period <?php echo $i; ?>

                <button class="btn btn-sm ms-auto" onclick="toggleExpand(<?php echo $i; ?>)">
                    <i class="bi bi-arrows-angle-expand"></i>
                </button>
            </div>


            <?php
            // দিন লুপ (রবি থেকে বৃহস্পতি)
            $show_days = [$today_wday];

            if (isset($_POST['expand']) && $_POST['expand'] == 1) {
                $show_days = [1, 2, 3, 4, 5];
            }
            foreach ($show_days as $j):
                $day_name = $days[$j];

                // ডাটাবেজ থেকে রুটিন ফেচ (Prepared Statement recommended, keeping logic for now)
                $sql = "SELECT r.*, s.subject as sub_text, t.tname 
                        FROM clsroutine r 
                        LEFT JOIN subjects s ON r.subcode = s.subcode 
                        LEFT JOIN teacher t ON r.tid = t.tid 
                        WHERE r.sccode='$sccode' AND r.sessionyear='$sy' AND r.classname='$cls' 
                        AND r.sectionname='$sec' AND r.period = '$i' AND r.wday='$j' LIMIT 1";

                $res = $conn->query($sql);
                $row = $res->fetch_assoc();

                $id = $row['id'] ?? 0;
                $sub_code = $row['subcode'] ?? 0;
                $teacher_id = $row['tid'] ?? 0;
                $display_sub = $row['sub_text'] ?? '<span class="opacity-25">No Subject</span>';
                $display_teacher = $row['tname'] ?? 'Not Assigned';
                ?>
                <div class="routine-card shadow-sm">
                    <div class="day-box">
                        <div class="day-name"><?php echo substr($day_name, 0, 3); ?></div>
                    </div>
                    <div class="routine-info">
                        <div class="sub-name text-truncate"><?php echo $display_sub; ?></div>
                        <div class="teacher-name text-truncate">
                            <i class="bi bi-person-badge me-1"></i><?php echo $display_teacher; ?>
                        </div>
                    </div>
                    <button class="btn-edit-tonal shadow-sm"
                        onclick="openRoutineModal('<?php echo $i; ?>', '<?php echo $j; ?>', '<?php echo $day_name; ?>', '<?php echo $sub_code; ?>', '<?php echo $teacher_id; ?>', '<?php echo $id; ?>');">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endfor; ?>
</main>

<div style="height: 60px;"></div>