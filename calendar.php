<?php
include 'inc.php'; // Contains header, DB connection, and session data

// Fetch calendar events and weekend settings
include_once 'datam/datam-calendar.php'; // Provides $datam_calendar_events
$wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
$wday_text = ($wday_ind !== false) ? $ins_all_settings[$wday_ind]['settings_value'] : '';

// Format events for FullCalendar
$fullcalendar_events = [];
foreach ($datam_calendar_events as $event) {
    $is_holiday = false;
    $day_name = date('l', strtotime($event['date']));
    if (str_contains($wday_text, $day_name)) {
        $is_holiday = true;
    }

    $fullcalendar_events[] = [
        'title' => $event['descrip'],
        'start' => $event['date'],
        'allDay' => true, // Assuming all events are all-day events
        'color' => $is_holiday ? '#dc3545' : ($event['color'] ?: '#0d6efd'), // Red for holidays, default blue otherwise
        'extendedProps' => [
            'icon' => $is_holiday ? 'x-square-fill' : $event['icon']
        ]
    ];
}
?>

<style>
    body { background-color: #f0f2f5; }
    #calendar {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.46875rem 2.1875rem rgba(4,9,20,0.03), 0 0.9375rem 1.40625rem rgba(4,9,20,0.03), 0 0.25rem 0.53125rem rgba(4,9,20,0.05);
    }
    .fc-event-title {
        white-space: normal !important; /* Allow event titles to wrap */
    }
</style>

<main class="container-fluid mt-4">

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <i class="bi bi-calendar3-week text-primary me-3" style="font-size: 2.5rem;"></i>
            <div>
                <h1 class="h4 mb-0">Academic Calendar</h1>
                <p class="mb-0 text-muted">View institution events, holidays, and schedules.</p>
            </div>
        </div>
    </div>

    <div id='calendar'></div>

</main>

<div style="height:52px;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Get events data from PHP
    var eventsData = <?php echo json_encode($fullcalendar_events); ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        initialView: 'dayGridMonth',
        weekends: true, // Show weekends
        events: eventsData,
        eventContent: function(arg) {
            // Custom event rendering to include an icon
            let iconEl = document.createElement('i');
            let iconName = arg.event.extendedProps.icon || 'dot';
            iconEl.className = 'bi bi-' + iconName + ' me-2';

            let titleEl = document.createElement('span');
            titleEl.innerHTML = arg.event.title;
            
            let arrayOfDomNodes = [ iconEl, titleEl ];
            return { domNodes: arrayOfDomNodes };
        }
    });

    calendar.render();
});
</script>

<?php include 'footer.php'; ?>
