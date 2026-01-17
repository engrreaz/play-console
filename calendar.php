<?php
include 'inc.php'; 
include_once 'datam/datam-calendar.php'; // Provides $datam_calendar_events

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;

// ২. উইকেন্ড সেটিংস ফেচ করা
$wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
$wday_text = ($wday_ind !== false) ? $ins_all_settings[$wday_ind]['settings_value'] : '';

// ৩. FullCalendar-এর জন্য ইভেন্ট ডাটা ফরম্যাট করা
$fullcalendar_events = [];
foreach ($datam_calendar_events as $event) {
    $day_name = date('l', strtotime($event['date']));
    $is_holiday = str_contains($wday_text, $day_name);

    $fullcalendar_events[] = [
        'title' => $event['descrip'],
        'start' => $event['date'],
        'allDay' => true,
        'color' => $is_holiday ? '#B3261E' : ($event['color'] ?: '#6750A4'), // M3 Red for holidays, Purple for events
        'extendedProps' => [
            'icon' => $is_holiday ? 'calendar-x' : ($event['icon'] ?: 'dot')
        ]
    ];
}

$page_title = "Academic Calendar";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.85rem; }

    /* M3 Standard App Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Compact Calendar Container (8px Radius) */
    .calendar-card {
        background: #fff; border-radius: 8px; padding: 8px;
        margin: 12px 8px; border: 1px solid #eee;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* FullCalendar M3 Customization */
    .fc { font-family: inherit; }
    .fc .fc-toolbar-title { font-size: 1rem !important; font-weight: 800; color: #6750A4; }
    .fc .fc-button-primary { 
        background-color: #F3EDF7 !important; border: none !important; color: #6750A4 !important; 
        font-weight: 700 !important; font-size: 0.8rem !important; border-radius: 8px !important;
    }
    .fc .fc-button-active { background-color: #6750A4 !important; color: #fff !important; }
    
    .fc-daygrid-day-number { font-weight: 700; font-size: 0.8rem; color: #49454F; text-decoration: none !important; }
    .fc-event { border-radius: 4px !important; border: none !important; padding: 1px 4px !important; font-size: 0.65rem !important; }
    
    /* Highlight Weekends/Holidays */
    .holiday-cell { background-color: #FFF0F0 !important; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;"><?php echo $current_session; ?></span>
    </div>
</header>

<main class="pb-5">
    <div class="calendar-card shadow-sm">
        <div id='calendar'></div>
    </div>

    <div class="px-3 d-flex gap-3 justify-content-center opacity-75">
        <div class="small d-flex align-items-center"><span class="badge bg-danger rounded-circle p-1 me-1">&nbsp;</span> Holiday</div>
        <div class="small d-flex align-items-center"><span class="badge bg-primary rounded-circle p-1 me-1">&nbsp;</span> Event</div>
    </div>
</main>

<div style="height: 65px;"></div> <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsData = <?php echo json_encode($fullcalendar_events); ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'today'
        },
        height: 'auto',
        events: eventsData,
        firstDay: 6, // Saturday start (BD Standard)
        eventContent: function(arg) {
            let iconName = arg.event.extendedProps.icon || 'dot-fill';
            return {
                html: `<div class="d-flex align-items-center px-1">
                        <i class="bi bi-${iconName} me-1" style="font-size: 0.6rem;"></i>
                        <span class="text-truncate">${arg.event.title}</span>
                      </div>`
            };
        },
        dayCellClassNames: function(arg) {
            // উইকেন্ড হাইলাইট করার লজিক (JS এ)
            const day = arg.date.getDay(); 
            const weekends = "<?php echo $wday_text; ?>";
            const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            if (weekends.includes(dayNames[day])) { return ['holiday-cell']; }
        }
    });

    calendar.render();
});
</script>

<?php include 'footer.php'; ?>