<?php
// ফাইল: front-page-block/schedule.php

$day_today = date('l'); 
$now_time = date('H:i:s');
$current_class = null;
$next_class = null;

if (isset($conn, $sccode)) {
    // ১. বর্তমান ক্লাস এবং পরবর্তী ক্লাস একসাথে বের করার অপ্টিমাইজড কুয়েরি
    $sql_sched = "
        (SELECT *, 'current' as status FROM classroutine 
         WHERE sccode = ? AND day = ? AND ? BETWEEN periodtime AND periodtimeend LIMIT 1)
        UNION
        (SELECT *, 'next' as status FROM classroutine 
         WHERE sccode = ? AND day = ? AND periodtime > ? 
         ORDER BY periodtime ASC LIMIT 1)
    ";

    $stmt_sched = $conn->prepare($sql_sched);
    $stmt_sched->bind_param("ssssss", $sccode, $day_today, $now_time, $sccode, $day_today, $now_time);
    $stmt_sched->execute();
    $res_sched = $stmt_sched->get_result();

    while ($row = $res_sched->fetch_assoc()) {
        if ($row['status'] == 'current') $current_class = $row;
        else $next_class = $row;
    }
    $stmt_sched->close();
}
?>

<style>
    .m3-schedule-card { background: #fff; border-radius: 8px; padding: 12px; }
    
    .ongoing-box { 
        background: #F3EDF7; border-radius: 8px; padding: 12px; 
        border: 1px solid #EADDFF; position: relative;
    }
    
    .status-pill {
        font-size: 0.55rem; font-weight: 800; padding: 2px 8px; 
        border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;
        background: #C8E6C9; color: #1B5E20; display: inline-flex; align-items: center;
    }

    .sub-name-m3 { font-size: 1.1rem; font-weight: 800; color: #1C1B1F; margin: 4px 0; }
    .time-rem { font-size: 0.7rem; font-weight: 700; color: #6750A4; }

    .m3-progress-slim { background: #E7E0EC; height: 4px; border-radius: 2px; overflow: hidden; margin-top: 10px; }
    .m3-progress-fill-active { 
        background: #6750A4; height: 100%; width: 60%; 
        background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem; animation: progress-bar-stripes 1s linear infinite;
    }

    .next-strip {
        background: #f8f9fa; border-radius: 8px; padding: 8px 12px;
        margin-top: 8px; border: 1px solid #eee; display: flex; align-items: center;
    }
    .next-lbl { font-size: 0.6rem; font-weight: 800; color: #79747E; text-transform: uppercase; margin-right: 8px; }
    .next-val { font-size: 0.75rem; font-weight: 700; color: #1D1B20; }
</style>

<div class="m3-schedule-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.8px;">
            <i class="bi bi-clock-fill me-1 text-primary"></i> Class Schedule
        </span>
        <span class="badge bg-light text-dark border-0" style="font-size: 0.6rem; font-weight: 700;"><?php echo date('h:i A'); ?></span>
    </div>

    <?php if ($current_class): ?>
        <div class="ongoing-box shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="status-pill mb-1">
                        <span class="spinner-grow spinner-grow-sm me-1" style="width: 8px; height: 8px;"></span> Ongoing
                    </div>
                    <div class="sub-name-m3 text-truncate"><?php echo htmlspecialchars($current_class['subject']); ?></div>
                    <div class="small text-muted fw-medium">
                        <i class="bi bi-geo-alt me-1"></i> Room: <?php echo $current_class['room_no'] ?? 'N/A'; ?>
                    </div>
                </div>
                <div class="text-end">
                    <div class="time-rem"><?php echo date('h:i A', strtotime($current_class['periodtimeend'])); ?></div>
                    <div style="font-size: 0.55rem; font-weight: 700; color: #79747E; text-transform: uppercase;">Ends At</div>
                </div>
            </div>
            
            <div class="m3-progress-slim">
                <div class="m3-progress-fill-active"></div>
            </div>
        </div>

        <?php if ($next_class): ?>
            <div class="next-strip">
                <span class="next-lbl">Up Next:</span>
                <span class="next-val flex-grow-1 text-truncate"><?php echo htmlspecialchars($next_class['subject']); ?></span>
                <span class="badge bg-white text-dark border px-2" style="font-size: 0.6rem;"><?php echo date('h:i A', strtotime($next_class['periodtime'])); ?></span>
            </div>
        <?php endif; ?>

    <?php elseif ($next_class): ?>
        <div class="text-center py-3 bg-light rounded-3 border border-dashed">
            <i class="bi bi-hourglass-split text-primary fs-3 opacity-50"></i>
            <div class="fw-bold text-dark mt-1" style="font-size: 0.9rem;"><?php echo htmlspecialchars($next_class['subject']); ?></div>
            <div class="small text-muted fw-bold" style="font-size: 0.65rem;">Starts at <?php echo date('h:i A', strtotime($next_class['periodtime'])); ?></div>
            <a href="clsroutine.php" class="btn btn-link btn-sm text-decoration-none fw-bold p-0 mt-2" style="font-size: 0.7rem;">VIEW FULL ROUTINE</a>
        </div>

    <?php else: ?>
        <div class="text-center py-4 opacity-50">
            <i class="bi bi-calendar-check display-6"></i>
            <p class="fw-bold mt-2 mb-0" style="font-size: 0.75rem;">All classes finished for today.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // প্রগ্রেস বার সিমুলেশন (প্রকৃত সময়ের ওপর ভিত্তি করে আপডেট করা সম্ভব)
    document.addEventListener("DOMContentLoaded", function() {
        const bar = document.querySelector('.m3-progress-fill-active');
        if(bar) {
            // এখানে আপনি PHP দিয়ে (বর্তমান সময় - শুরু সময়) / মোট সময় এর পারসেন্টেজ বসাতে পারেন
            bar.style.width = '65%'; 
        }
    });
</script>