<?php
include_once '../inc.light.php';

$session = $_POST['session'];
$slot = $_POST['slot'];

$res = $conn->query("SELECT * FROM classschedule 
                    WHERE sccode='$sccode' AND sessionyear='$session' AND slots='$slot' 
                    ORDER BY timestart ASC");

if ($res->num_rows > 0) {
    // সিঙ্ক বাটনকে আরও আধুনিক লুক দেওয়া হয়েছে
?>
    <div class="text-center text-tiny fw-bold  mb-3 text-info"><?= $slot ?> | <?= $session ?></div>
<?php 

    while ($row = $res->fetch_assoc()) {
        $p = $row['period'];
        
        // পিরিয়ড লেবেল লজিক
        if ($p == 0) {
            $display_period = '<i class="bi bi-cup-hot fs-5"></i>';
            $period_name = "Interval";
            $badge_style = "background: #F9DEDC; color: #410002; border: 1px solid #F2B8B5;";
        } else {
            $suffix = ['th','st','nd','rd','th','th','th','th','th','th'];
            $ext = ($p <= 3) ? $suffix[$p] : "th";
            $display_period = $p . '<small style="font-size:0.6rem">'.$ext.'</small>';
            $period_name = "Period";
            $badge_style = "background: var(--m3-primary-tonal); color: var(--m3-on-tonal);";
        }

        echo '
        <div class="schedule-item-m3" style="border-radius: 12px; border: 1px solid #EEE; padding: 12px; margin: 0 12px 10px; display: flex; align-items: center; background: #FFF;">
            <div style="width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; '.$badge_style.'">
                '.$display_period.'
            </div>

            <div class="ms-3" style="flex-grow: 1;">
                <div style="font-size: 0.9rem; font-weight: 800; color: #1C1B1F;">
                    '.date("h:i A", strtotime($row['timestart'])).' - '.date("h:i A", strtotime($row['timeend'])).'
                </div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span style="font-size: 0.65rem; background: #E8F5E9; color: #2E7D32; padding: 2px 8px; border-radius: 100px; font-weight: 800;">
                        '.$row['duration'].' MINS
                    </span>
                    <span style="font-size: 0.65rem; font-weight: 700; color: #938F99; text-transform: uppercase;">'.$period_name.'</span>
                </div>
            </div>

            <div class="d-flex gap-1">
                <button class="btn" onclick="editSchedule('.$row['id'].')" style="padding: 8px; border-radius: 8px; background: #F3EDF7; color: #6750A4; border: none;">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn" onclick="deleteSchedule('.$row['id'].')" style="padding: 8px; border-radius: 8px; background: #FFF0F0; color: #B3261E; border: none;">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>';
    }
} else {
    echo '<div class="text-center py-5 opacity-50"><i class="bi bi-calendar2-x display-4"></i><p class="fw-bold">No periods set.</p></div>';
}
?>