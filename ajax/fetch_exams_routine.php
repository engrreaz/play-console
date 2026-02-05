<?php
include_once '../inc.light.php';

// পরীক্ষার ইউনিক কোড বা নাম রিসিভ করা
$exam_name = $_POST['exam_name'] ?? ''; // examlist টেবিলের 'examcode'
$cls = $_POST['cls'] ?? '';

$sql = "SELECT * FROM examroutine 
        WHERE sccode = '$sccode' 
        AND examname = '$exam_name' 
        AND clsname = '$cls'
        ORDER BY date ASC, time ASC";

$res = $conn->query($sql);

if ($res->num_rows > 0) {
    echo '<div class="routine-timeline px-2">';
    
    $current_date = '';
    
    while ($row = $res->fetch_assoc()) {
        $exam_date = $row['date'];
        
        // তারিখ পরিবর্তন হলে একটি ডেট স্ট্যাম্প বা ডিভাইডার দেখানো
        if ($current_date != $exam_date) {
            echo '<div class="m3-section-title mt-4 mb-2" style="color: var(--m3-primary);">
                    <i class="bi bi-calendar-check me-2"></i>'.date("d F, Y (l)", strtotime($exam_date)).'
                  </div>';
            $current_date = $exam_date;
        }

        // প্রগ্রেস স্ট্যাটাস ক্যালকুলেশন
        $prog = $row['progress'];
        $prog_color = ($prog >= 100) ? '#146C32' : (($prog > 0) ? '#0288D1' : '#79747E');
        $status_text = ($prog >= 100) ? 'Completed' : (($prog > 0) ? 'In Progress' : 'Pending');

        echo '
        <div class="m3-routine-card shadow-sm mb-3">
            <div class="d-flex align-items-center">
                <div class="time-badge text-center me-3">
                    <div class="fw-black text-primary" style="font-size: 1.1rem;">'.date("h:i", strtotime($row['time'])).'</div>
                    <div class="small text-muted fw-bold">'.date("A", strtotime($row['time'])).'</div>
                </div>

                <div class="flex-grow-1 border-start ps-3">
                    <div class="fw-black text-dark" style="font-size: 1rem;">'.$row['subj'].'</div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="small text-muted fw-bold"><i class="bi bi-hash"></i> '.$row['subcode'].'</span>
                        <span class="badge rounded-pill" style="background:'. $prog_color .'20; color:'. $prog_color .'; font-size: 0.6rem;">
                            '.$status_text.' ('.$prog.'%)
                        </span>
                    </div>
                </div>

                <div class="d-flex gap-1">
                    <button class="btn btn-icon-m3" onclick="editRoutine('.$row['id'].')" style="background:#F3EDF7; color:#6750A4;">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-icon-m3" onclick="deleteRoutine('.$row['id'].')" style="background:#FFF0F0; color:#B3261E;">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
            
            <div class="progress mt-3" style="height: 4px; border-radius: 10px; background: #EEE;">
                <div class="progress-bar" style="width: '.$prog.'%; background: '.$prog_color .';"></div>
            </div>
        </div>';
    }
    echo '</div>';
} else {
    echo '
    <div class="text-center py-5 opacity-50">
        <i class="bi bi-calendar2-x display-4"></i>
        <p class="fw-bold mt-2">Routine not generated yet for this exam.</p>
        <button class="btn btn-primary btn-sm rounded-pill px-4" onclick="generateRoutineModal()">
            <i class="bi bi-magic me-1"></i> Generate Now
        </button>
    </div>';
}
?>

<style>
    .m3-routine-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #F0F0F0;
        position: relative;
        overflow: hidden;
    }
    .time-badge {
        min-width: 65px;
    }
    .btn-icon-m3 {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: 0.2s;
    }
    .btn-icon-m3:active { transform: scale(0.9); }
    .fw-black { font-weight: 900; }
</style>