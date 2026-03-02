<?php
/**
 * Staff Attendance Log Report - Final Corrected Version
 * Logic: Present > Holiday > Weekend > Approved Leave > Absent
 */
$page_title = 'Staff Attendance Report';
include 'inc.php';
include 'datam/datam-calendar.php';

// ১. আজকের দিনের উইকেন্ড এবং হলিডে নির্ধারণ
$day_name = date('l', strtotime($td));
$wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
$wday_text = $ins_all_settings[$wday_ind]['settings_value'] ?? 'Friday';
$is_weekend = str_contains($wday_text, $day_name);

$holiday_title = "";
foreach ($datam_calendar_events as $eve) {
    if ($eve['date'] == $td && $eve['class'] == 0) {
        $holiday_title = $eve['title'] ?? "Institutional Holiday";
        break;
    }
}
?>

<style>
    :root {
        --m3-surface: #FDFBFF;
        --m3-primary: #6750A4;
        --status-present: #2E7D32;
        --status-late: #f3ef0a;
        --status-absent: #B3261E;
        --status-leave: #E65100;
        --status-holiday: #0061A4;
        --m3-outline-variant: #EADDFF;
    }



    .m3-app-bar {
        background: white;
        padding: 16px;
        border-radius: 0 0 16px 16px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .report-card {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        margin: 8px 12px;
        display: flex;
        align-items: center;
        border: 1px solid var(--m3-outline-variant);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 16px;
    }

    .bg-present {
        background: var(--status-present);
        box-shadow: 0 0 0 4px #E8F5E9;
    }

    .bg-late {
        background: var(--status-late);
        box-shadow: 0 0 0 4px #f7f7db;
    }

    .bg-absent {
        background: var(--status-absent);
        box-shadow: 0 0 0 4px #FFEBEE;
    }

    .bg-leave {
        background: var(--status-leave);
        box-shadow: 0 0 0 4px #FFF3E0;
    }

    .bg-holiday {
        background: var(--status-holiday);
        box-shadow: 0 0 0 4px #E3F2FD;
    }

    .search-filter {
        background: #F3EDF7;
        border-radius: 28px;
        padding: 12px 20px;
        margin: 16px;
        border: none;
        width: calc(100% - 32px);
        outline: none;
        box-sizing: border-box;
    }
</style>

<main>
    <div class="m3-app-bar d-flex align-items-center">
        <button onclick="history.back()" class="btn btn-link text-dark p-0 me-3"><i
                class="bi bi-arrow-left fs-4"></i></button>
        <div>
            <h5 class="mb-0 fw-bold">Attendance Log</h5>
            <small class="text-muted"><?= date('d M, Y', strtotime($td)) ?> • <?= $day_name ?></small>
        </div>
    </div>

    <input type="text" class="search-filter" placeholder="Search teacher name..." onkeyup="filterList(this.value)">

    <div id="attendanceList">
        <?php
        /**
         * SQL লজিক: 
         * ১. teacherattnd থেকে আজকের হাজিরা।
         * ২. leaveapp টেবিলের 'ldate' কলামের সাথে আজকের তারিখ ($td) ম্যাচ করা।
         */
        $sql = "SELECT t.tid, t.tname, t.position, ta.statusin, 
                (SELECT leavetype FROM leaveapp WHERE tid = t.tid AND ldate = '$td' AND status = 'Accept' LIMIT 1) as approved_leave
                FROM teacher t
                LEFT JOIN teacherattnd ta ON t.tid = ta.tid AND ta.sccode = '$sccode' AND ta.adate = '$td'
                WHERE t.sccode = '$sccode' 
                ORDER BY t.sl ASC";

        $res = $conn->query($sql);

        while ($row = $res->fetch_assoc()):
            $status = 'Absent';
            $dot_class = 'bg-absent';
            $info = 'No Record';
            $color = 'var(--status-absent)';

            // ১. হাজিরা থাকলে (Present)
            if ($row['statusin'] !== null) {

                $status = 'Present';
                $dot_class = 'bg-present';
                $info = 'Verified Presence';
                $color = 'var(--status-present)';

                if ($row['statusin'] == 'Late') {
                    $status = 'Late Entry';
                    $dot_class = 'bg-late';
                    $info = 'Late Presence';
                    $color = 'var(--status-late)';
                }
            }


            // ২. ক্যালেন্ডার হলিডে থাকলে
            else if ($holiday_title != "") {
                $status = 'Holiday';
                $dot_class = 'bg-holiday';
                $info = $holiday_title;
                $color = 'var(--status-holiday)';
            }
            // ৩. উইকেন্ড হলে
            else if ($is_weekend) {
                $status = 'Weekend';
                $dot_class = 'bg-holiday';
                $info = 'Weekly Break';
                $color = 'var(--status-holiday)';
            }
            // ৪. লিভ ডাটা ম্যাচ করলে (ldate = $td)
            else if ($row['approved_leave'] !== null) {
                $status = 'On Leave';
                $dot_class = 'bg-leave';
                $info = $row['approved_leave'] . ' Leave';
                $color = 'var(--status-leave)';
            }
            ?>
            <div class="report-card list-item" data-name="<?php echo strtolower($row['tname']); ?>">
                <div class="status-dot <?php echo $dot_class; ?>"></div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 14px;">
                        <?php echo htmlspecialchars($row['tname']); ?>
                    </div>
                    <div class="text-muted text-truncate" style="font-size: 11px;">
                        <?php echo htmlspecialchars($row['position']); ?>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold" style="font-size: 12px; color: <?php echo $color; ?>;"><?php echo $status; ?></div>
                    <div class="text-muted" style="font-size: 10px;"><?php echo $info; ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>



<?php include 'footer.php'; ?>

<script>
    function filterList(val) {
        let filter = val.toLowerCase();
        let items = document.querySelectorAll('.list-item');
        items.forEach(item => {
            let name = item.getAttribute('data-name');
            item.style.display = name.includes(filter) ? 'flex' : 'none';
        });
    }
</script>