<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;
$sy_param = '%' . $current_session . '%';

// ২. ফিল্টার হ্যান্ডলিং (Class & Section)
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');

$page_title = "Class Routine";

// ৩. রুটিন ডাটা ফেচিং (Prepared Statement - Secure)
$routine_data = [];
$days_order = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

$sql = "SELECT * FROM classschedule 
        WHERE sccode = ? AND sessionyear LIKE ? AND classname = ? AND sectionname = ? 
        ORDER BY FIELD(dayname, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), timestart ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sccode, $sy_param, $classname, $sectionname);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $routine_data[$row['dayname']][] = $row;
}
$stmt->close();
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Full Width M3 App Bar */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 56px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Filter Card (Condensed) */
    .filter-card {
        background: #fff; border-radius: 8px; padding: 12px; margin: 10px 12px;
        border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .form-select-sm { border-radius: 6px; border: 1px solid #79747E; font-size: 0.8rem; font-weight: 600; height: 38px; }

    /* Day Card (M3 Medium) */
    .day-card {
        background: #fff; border-radius: 8px; margin: 0 12px 12px;
        border: 1px solid #f0f0f0; overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .day-header {
        background: #F3EDF7; padding: 10px 16px; 
        font-weight: 800; color: #6750A4; font-size: 0.85rem;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* Period Row Item */
    .period-item {
        display: flex; align-items: center; padding: 12px 16px;
        border-bottom: 1px solid #F7F2FA; position: relative;
    }
    .period-item:last-child { border-bottom: none; }
    
    .time-box {
        width: 75px; flex-shrink: 0; text-align: center;
        border-right: 2px solid #EADDFF; margin-right: 15px;
    }
    .time-start { font-weight: 800; color: #1C1B1F; font-size: 0.8rem; display: block; }
    .time-end { font-size: 0.65rem; color: #79747E; font-weight: 600; }

    .sub-info { flex-grow: 1; overflow: hidden; }
    .sub-name { font-weight: 700; color: #1D1B20; font-size: 0.9rem; margin-bottom: 2px; display: block; }
    .teacher-name { font-size: 0.75rem; color: #49454F; font-weight: 500; }

    .period-badge {
        font-size: 0.6rem; background: #EADDFF; color: #21005D;
        padding: 2px 6px; border-radius: 4px; font-weight: 800; margin-left: 8px;
    }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.65rem;"><?php echo $current_session; ?></span>
</header>

<main class="pb-5 mt-2">
    <div class="filter-card">
        <form method="GET" class="row gx-2 align-items-center">
            <div class="col-5">
                <select name="cls" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <select name="sec" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" style="border-radius: 6px; height: 38px;">VIEW</button>
            </div>
            <input type="hidden" name="year" value="<?php echo $current_session; ?>">
        </form>
    </div>

    <div class="routine-container">
        <?php if (empty($routine_data)): ?>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-calendar-x display-1"></i>
                <p class="fw-bold mt-2">No routine found for <?php echo $classname; ?>.</p>
            </div>
        <?php else: ?>
            <?php foreach ($days_order as $day): 
                if (!isset($routine_data[$day])) continue;
            ?>
                <div class="day-card shadow-sm">
                    <div class="day-header">
                        <span><i class="bi bi-calendar-check me-2"></i><?php echo strtoupper($day); ?></span>
                        <span class="small opacity-50"><?php echo count($routine_data[$day]); ?> Classes</span>
                    </div>
                    
                    <div class="periods-list">
                        <?php foreach ($routine_data[$day] as $p): 
                            $time_s = date('h:i A', strtotime($p['timestart']));
                            $time_e = date('h:i A', strtotime($p['timeend']));
                        ?>
                            <div class="period-item">
                                <div class="time-box">
                                    <span class="time-start"><?php echo $time_s; ?></span>
                                    <span class="time-end"><?php echo $time_e; ?></span>
                                </div>
                                <div class="sub-info">
                                    <span class="sub-name text-truncate">
                                        <?php echo $p['subject']; ?>
                                        <span class="period-badge">P-<?php echo $p['period']; ?></span>
                                    </span>
                                    <span class="teacher-name"><i class="bi bi-person me-1"></i><?php echo $p['teachername'] ?: 'Not Assigned'; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<div style="height: 75px;"></div> <?php include 'footer.php'; ?>