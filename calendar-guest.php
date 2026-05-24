<?php 
$page_title = "Academic Calendar";
include 'inc.guest.php'; 

// ১. সেশন ইয়ার হ্যান্ডলিং (calendar.php এর মতো)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_COOKIE['query-session'] ?? $sy ?? date('Y');

// ২. ক্যালেন্ডার ডেটা ফেচ করা
include_once 'datam/datam-calendar.php';

$today = date('Y-m-d');
$upcoming_events = [];
$exam_schedules = [];

if(isset($datam_calendar_events) && is_array($datam_calendar_events)) {
    foreach ($datam_calendar_events as $event) {
        // Find upcoming events or exams
        if ($event['date'] >= $today) {
            if (isset($event['category']) && $event['category'] === 'Exam') {
                $exam_schedules[] = $event;
            } else {
                $upcoming_events[] = $event;
            }
        }
    }
}

// সর্টিং করা (কাছাকাছি ডেট আগে দেখানোর জন্য)
usort($upcoming_events, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
usort($exam_schedules, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));

// গেস্ট ভিউতে শুধু প্রথম ৫টি করে দেখাবো
$upcoming_events = array_slice($upcoming_events, 0, 5);
$exam_schedules = array_slice($exam_schedules, 0, 5);
?>

<main class="pb-5">
    <!-- HERO BANNER -->
    <div class="guest-hero-banner text-center" style="background: #E8F5E9; color: #1B5E20; border-bottom: 1px solid #CAC4D0;">
        <div class="mb-3">
            <div class="icon-box-flat mx-auto" style="background: #A5D6A7; color: #1B5E20; width: 72px; height: 72px; font-size: 2rem;">
                <i class="bi bi-calendar3"></i>
            </div>
        </div>
        <div class="inst-title">Academic Calendar</div>
        <div class="inst-meta"><?php echo htmlspecialchars($scinfo['scname'] ?? $institution_name); ?></div>
        <div class="inst-desc mt-2">Upcoming events, exams, and holidays for the current academic year.</div>
    </div>

    <!-- UPCOMING EVENTS -->
    <div class="section-lbl">Upcoming Events</div>
    <div class="m3-flat-list-group">
        <?php if(empty($upcoming_events)): ?>
            <div class="p-4 text-center text-muted" style="font-weight: 600;">No upcoming events currently scheduled.</div>
        <?php else: ?>
            <?php foreach($upcoming_events as $event): 
                $month = date('M', strtotime($event['date']));
                $day = date('d', strtotime($event['date']));
                $bg_color = !empty($event['color']) ? $event['color'] : '#E65100'; // Default
                $bg_color_light = $bg_color . '20'; // Add transparency
            ?>
            <div class="m3-list-flat-item">
                <div class="icon-box-flat" style="background: <?= $bg_color_light ?>; color: <?= $bg_color ?>; flex-direction: column; justify-content: center;">
                    <div style="font-size: 0.75rem; font-weight: bold; line-height: 1;"><?= strtoupper($month) ?></div>
                    <div style="font-size: 1.1rem; font-weight: bold; line-height: 1; margin-top: 2px;"><?= $day ?></div>
                </div>
                <div class="item-info-block">
                    <div class="st-flat-title"><?= htmlspecialchars($event['descrip']) ?></div>
                    <div class="st-flat-desc"><?= (isset($event['work']) && $event['work'] == 0) ? 'Holiday / Campus Closed' : 'Event / Regular Activity' ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ACADEMIC SCHEDULE -->
    <div class="section-lbl">Exam & Term Schedule</div>
    <div class="m3-flat-list-group">
        <?php if(empty($exam_schedules)): ?>
            <div class="p-4 text-center text-muted" style="font-weight: 600;">No exams currently scheduled.</div>
        <?php else: ?>
            <?php foreach($exam_schedules as $exam): 
                $month = date('M', strtotime($exam['date']));
                $day = date('d', strtotime($exam['date']));
                $icon = !empty($exam['icon']) ? $exam['icon'] : 'pencil-square';
                $bg_color = !empty($exam['color']) ? $exam['color'] : '#C2185B'; // Default Exam color
                $bg_color_light = $bg_color . '20'; // Add transparency
            ?>
            <div class="m3-list-flat-item">
                <div class="icon-box-flat" style="background: <?= $bg_color_light ?>; color: <?= $bg_color ?>;">
                    <i class="bi bi-<?= htmlspecialchars($icon) ?>"></i>
                </div>
                <div class="item-info-block">
                    <div class="st-flat-title"><?= htmlspecialchars($exam['descrip']) ?></div>
                    <div class="st-flat-desc">Scheduled for <?= date('d M, Y', strtotime($exam['date'])) ?>.</div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
