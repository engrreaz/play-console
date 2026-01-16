<?php
include 'inc.php'; // header.php এবং কানেকশন লোড করবে

// ১. ফিল্টার হ্যান্ডলিং (Security & Default values)
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

// ২. পিরিয়ড ডিটেইলস ফেচ করা (Prepared Statement)
$class_schedule = [];
$stmt_sc = $conn->prepare("SELECT period, timestart, timeend FROM classschedule WHERE sccode = ? AND sessionyear LIKE ? ORDER BY period ASC");
$sy_param = "%$sy%";
$stmt_sc->bind_param("ss", $sccode, $sy_param);
$stmt_sc->execute();
$res_sc = $stmt_sc->get_result();
while ($row = $res_sc->fetch_assoc()) {
    $class_schedule[$row['period']] = $row;
}
$stmt_sc->close();

// ৩. পূর্ণ রুটিন ফেচ করা (Weekly Data)
$routine_data = [];
$stmt_rt = $conn->prepare("SELECT period, wday, subcode, tid FROM clsroutine WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ?");
$stmt_rt->bind_param("ssss", $sccode, $sy_param, $classname, $sectionname);
$stmt_rt->execute();
$res_rt = $stmt_rt->get_result();
while ($row = $res_rt->fetch_assoc()) {
    $routine_data[$row['wday']][$row['period']] = $row;
}
$stmt_rt->close();

// সাবজেক্ট এবং টিচার ডাটা লোড (Assuming these arrays are available from inc.php)
include_once 'datam/datam-subject-list.php'; 
include_once 'datam/datam-teacher-list.php';

$week_days = [
    1 => 'Sat', 2 => 'Sun', 3 => 'Mon', 4 => 'Tue', 5 => 'Wed', 6 => 'Thu', 7 => 'Fri'
];
$today_num = date('N') == 6 ? 1 : date('N') + 2; // Saturday=1 adjust for PHP date format
if($today_num > 7) $today_num -= 7;
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */
    
    /* Day Tabs Styling */
    .day-tabs-container {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: #fff;
        padding: 10px 0;
        border-bottom: 1px solid #E7E0EC;
    }
    .nav-pills-m3 {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        padding: 0 15px;
        gap: 8px;
    }
    .nav-pills-m3::-webkit-scrollbar { display: none; }
    
    .nav-pills-m3 .nav-link {
        border-radius: 12px;
        padding: 8px 20px;
        background: #F3EDF7;
        color: #49454F;
        font-weight: 600;
        border: none;
    }
    .nav-pills-m3 .nav-link.active {
        background-color: #6750A4 !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Period Card Styling */
    .period-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        margin-bottom: 12px;
        transition: 0.2s;
        display: flex;
        align-items: center;
        padding: 16px;
    }
    .time-slot {
        min-width: 85px;
        border-right: 2px solid #E7E0EC;
        margin-right: 15px;
        padding-right: 10px;
    }
    .time-slot .start { font-weight: 700; color: #1C1B1F; font-size: 0.95rem; }
    .time-slot .end { font-size: 0.75rem; color: #79747E; }
    
    .subject-info .sub-name { font-weight: 700; color: #21005D; font-size: 1.05rem; margin-bottom: 2px; }
    .subject-info .teacher-name { font-size: 0.85rem; color: #49454F; display: flex; align-items: center; }
    
    .period-badge {
        background: #EADDFF;
        color: #21005D;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 5px;
        display: inline-block;
    }
</style>

<main class="pb-5">
    <div class="p-3 bg-white d-flex align-items-center">
        <a href="reporthome.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <div>
            <h5 class="fw-bold mb-0">Class Routine</h5>
            <small class="text-muted"><?php echo htmlspecialchars($classname . " - " . $sectionname); ?></small>
        </div>
    </div>

    <div class="day-tabs-container shadow-sm">
        <ul class="nav nav-pills-m3" id="routineTab" role="tablist">
            <?php foreach ($week_days as $num => $name): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($num == $today_num) ? 'active' : ''; ?>" 
                            id="day-tab-<?php echo $num; ?>" 
                            data-bs-toggle="pill" 
                            data-bs-target="#day-<?php echo $num; ?>" 
                            type="button">
                        <?php echo $name; ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="tab-content container mt-3" id="routineTabContent">
        <?php foreach ($week_days as $wday_num => $day_name): ?>
            <div class="tab-pane fade <?php echo ($wday_num == $today_num) ? 'show active' : ''; ?>" 
                 id="day-<?php echo $wday_num; ?>" role="tabpanel">
                
                <?php 
                $periods_found = false;
                if (isset($routine_data[$wday_num])):
                    foreach ($class_schedule as $p_num => $p_info):
                        if (isset($routine_data[$wday_num][$p_num])):
                            $periods_found = true;
                            $entry = $routine_data[$wday_num][$p_num];
                            
                            // Subject Lookup
                            $s_idx = array_search($entry['subcode'], array_column($datam_subject_list, 'subcode'));
                            $sub_name = ($s_idx !== false) ? $datam_subject_list[$s_idx]['subject'] : 'Unknown';
                            
                            // Teacher Lookup
                            $t_idx = array_search($entry['tid'], array_column($datam_teacher_list, 'tid'));
                            $t_name = ($t_idx !== false) ? $datam_teacher_list[$t_idx]['tname'] : 'N/A';
                ?>
                    <div class="period-card shadow-sm">
                        <div class="time-slot text-center">
                            <div class="start"><?php echo date('h:i', strtotime($p_info['timestart'])); ?></div>
                            <div class="end"><?php echo date('h:i A', strtotime($p_info['timeend'])); ?></div>
                        </div>
                        <div class="subject-info flex-grow-1">
                            <span class="period-badge">PERIOD <?php echo $p_num; ?></span>
                            <div class="sub-name"><?php echo htmlspecialchars($sub_name); ?></div>
                            <div class="teacher-name">
                                <i class="bi bi-person-video3 me-2 text-primary"></i>
                                <?php echo htmlspecialchars($t_name); ?>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <i class="bi bi-chevron-right text-muted opacity-50"></i>
                        </div>
                    </div>

                <?php 
                        endif;
                    endforeach; 
                endif;

                if (!$periods_found): ?>
                    <div class="text-center py-5">
                        <div class="rounded-circle bg-light d-inline-flex p-4 mb-3">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                        </div>
                        <h6 class="text-muted">No classes scheduled for <?php echo $day_name; ?></h6>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<div style="height: 70px;"></div>

<?php include 'footer.php'; ?>