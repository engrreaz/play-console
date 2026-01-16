<?php
include 'inc.back.php';
$y = $_POST['y'];
$m = $_POST['m'];
$d = $_POST['d'];
$t = $_POST['t'];
$td = $y . '-' . $m . '-' . $d;

$datam_calendar_events_today = array();
$sql0 = "SELECT * FROM calendar where  sccode='$sccode' and date = '$td'  and descrip!='' order by date, id  ";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $datam_calendar_events_today[] = $row0;
    }
}
// var_dump($datam_calendar_events);


$workday_flag = 1;
$event_block_hide = 'hidden';
$wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
$wday_text = $ins_all_settings[$wday_ind]['settings_value'];

$bar = date('l', strtotime($td));
if (str_contains($wday_text, $bar) === true) {
    $workday_flag = 0;
} else {

    $count_event = count($datam_calendar_events_today);

    if ($count_event > 0) {
        $event_block_hide = '';
        foreach ($datam_calendar_events_today as $eve) {
            $workday_flag *= $eve['class'];

        }
    }
}
$sch_block = '';
if ($workday_flag == 0) {
    $sch_block = 'hidden';
    $sch_block = '';
}

// Count Students
/*
$sql0 = "SELECT count(*) as stcnt FROM sessioninfo where sccode = '$sccode' and sessionyear='$sy' ;";
$result0rt = $conn->query($sql0);
if ($result0rt->num_rows > 0) {
    while ($row0 = $result0rt->fetch_assoc()) {
        $total_students = $row0["stcnt"];
    }
}
$sql0 = "SELECT count(*) as attndcnt FROM stattnd where sccode = '$sccode' and adate='$td' and yn=1 ;";
$result0rtt = $conn->query($sql0);
if ($result0rtt->num_rows > 0) {
    while ($row0 = $result0rtt->fetch_assoc()) {
        $today_st_attnd = $row0["attndcnt"];
    }
}
    */
?>
<div class="card-body p-3 mt-1" style="" <?php echo $event_block_hide; ?>>

    <?php
    foreach ($datam_calendar_events_today as $cal_event) {
        $ee_date = $cal_event['date'];
        $ee_descrip = $cal_event['descrip'];
        $ee_category = $cal_event['category'];
        $ee_icon = $cal_event['icon'];
        $ee_color = $cal_event['color'];
        ?>
        <div class="d-flex mb-1">
            <div class="event-icon mb-3 " style="color:<?php echo $ee_color; ?>">
                <i class="bi bi-<?php echo $ee_icon; ?>"></i>
            </div>
            <div class="flex-grow-1 " style="color:<?php echo $ee_color; ?>">
                <div class="st-id text-muted  p-0 m-0"><?php echo $ee_category; ?></div>
                <div class="stname-ben fw-bold p-0 m-0"><?php echo $ee_descrip; ?></div>
            </div>
        </div>
        <?php
    }
    ?>
</div>