<?php
ob_start();
$page_title = "Academic Calendar";
include 'inc.php';


// ১. সেশন ইয়ার হ্যান্ডলিং (একটু গুছিয়ে)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_COOKIE['query-session'] ?? $sy ?? date('Y');

// ২. ক্যালেন্ডার ডেটা ফেচ করা
include_once 'datam/datam-calendar.php';



// ---------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------

// ১. ডাটা ফেচিং (AJAX)
// ১. ডাটা ফেচিং (AJAX) - FIXED VERSION
if (isset($_GET['get_event_date'])) {
    ob_clean(); // অন্য কোনো টেক্সট বা ওয়ার্নিং আউটপুট বন্ধ করা
    header('Content-Type: application/json'); // ব্রাউজারকে জানানো যে এটি JSON ডাটা
// $sccode = 103187;
    $date = $_GET['get_event_date'];
    $stmt = $conn->prepare("SELECT * FROM calendar WHERE (sccode=? OR sccode=0) AND date=?");
    $stmt->bind_param("is", $sccode, $date);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    // ডাটা না থাকলে একটি ডিফল্ট অবজেক্ট পাঠানো
    echo json_encode($res ?: ['date' => $date, 'new' => true]);
    exit;
}

// ২. ডাটা সেভিং (AJAX POST)
if (isset($_POST['save_calendar_event'])) {
    $date = $_POST['date'];
    $dateto = !empty($_POST['dateto']) ? $_POST['dateto'] : NULL; // dateto রিসিভ করা
    $descrip = $_POST['descrip'];
    $category = $_POST['category'];
    $work = $_POST['work'];
    $class = $_POST['class'];
    $sccode = 103187;

    // ক্যাটাগরি অনুযায়ী আইকন এবং কালার ম্যাপিং
    $mapping = [
        'Exam' => ['icon' => 'pencil-square', 'color' => '#FF9800'],
        'Sports' => ['icon' => 'trophy-fill', 'color' => '#4CAF50'],
        'National' => ['icon' => 'flag-fill', 'color' => '#B3261E'],
        'Cultural' => ['icon' => 'music-note-beamed', 'color' => '#6750A4'],
        'General' => ['icon' => 'calendar-event', 'color' => '#79747E']
    ];

    $icon = $mapping[$category]['icon'] ?? 'calendar3';
    $color = $mapping[$category]['color'] ?? '#79747E';

    // চেক করা হচ্ছে আগে ডাটা আছে কি না
    $check = $conn->prepare("SELECT id FROM calendar WHERE sccode=? AND date=?");
    $check->bind_param("is", $sccode, $date);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();

    if ($exists) {
        $stmt = $conn->prepare("UPDATE calendar SET dateto=?, descrip=?, category=?, work=?, class=?, icon=?, color=? WHERE id=?");
        $stmt->bind_param("sssiissi", $dateto, $descrip, $category, $work, $class, $icon, $color, $exists['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO calendar (sccode, date, dateto, descrip, category, work, class, icon, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssiiss", $sccode, $date, $dateto, $descrip, $category, $work, $class, $icon, $color);
    }


    $res = $stmt->execute();
    echo $res ? "1" : "0";
    exit;
}

// ---------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------





// ৩. উইকেন্ড সেটিংস (Array তে রূপান্তর করা লজিক সহজ করার জন্য)
$wday_ind = array_search('Weekends', array_column($ins_all_settings, 'setting_title'));
$wday_text = ($wday_ind !== false) ? $ins_all_settings[$wday_ind]['settings_value'] : '';
$weekend_array = explode('.', $wday_text); // Friday.Saturday -> ['Friday', 'Saturday']

// ৪. FullCalendar-এর জন্য ইভেন্ট ডাটা ফরম্যাট
$fullcalendar_events = [];
foreach ($datam_calendar_events as $event) {
    $is_holiday = ($event['work'] == 0);

    $fullcalendar_events[] = [
        'id' => $event['id'],
        'title' => $event['descrip'],
        'start' => $event['date'],
        // যদি dateto থাকে, তবে ১ দিন বাড়িয়ে end সেট করা হচ্ছে
        'end' => (!empty($event['dateto'])) ? date('Y-m-d', strtotime($event['dateto'] . ' +1 day')) : null,
        'allDay' => true,
        'backgroundColor' => $is_holiday ? '#F9DEDC' : '#EADDFF',
        'borderColor' => $is_holiday ? '#B3261E' : '#6750A4',
        'extendedProps' => [
            'icon' => $event['icon'],
            'dateto_actual' => $event['dateto'] // অরিজিনাল ডেটটি মডালের জন্য রাখা হলো
        ]
    ];
}




// --- হিরো কন্টেইনারের জন্য ইভেন্ট খুঁজে বের করা ---
$today = date('Y-m-d');
$ongoing_event = null;
$next_event = null;

foreach ($datam_calendar_events as $event) {
    $start = $event['date'];
    $end = $event['dateto'] ?: $start;

    // ১. চলমান ইভেন্ট চেক
    if ($today >= $start && $today <= $end) {
        $ongoing_event = $event;
    }

    // ২. পরবর্তী ইভেন্ট চেক (আজকের পরে প্রথম ইভেন্ট)
    if ($start > $today && $next_event === null) {
        $next_event = $event;
    }
}
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-error: #B3261E;
        --m3-on-surface: #1C1B1F;
        --m3-outline: #79747E;
    }



    /* M3 Card Style */
    .calendar-container {
        background: #fff;
        border-radius: 12px;
        padding: 12px;
        margin: 0px;
        border: 1px solid #CAC4D0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* FullCalendar Customization */
    .fc {
        --fc-border-color: #E7E0EC;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 800;
        color: var(--m3-primary);
    }

    .fc .fc-button {
        border-radius: 100px !important;
        padding: 8px 16px !important;
        text-transform: capitalize !important;
        font-weight: 600 !important;
        transition: all 0.2s;
    }

    .fc .fc-button-primary {
        background-color: #F3EDF7 !important;
        color: var(--m3-primary) !important;
        border: none !important;
    }

    .fc .fc-button-primary:hover {
        background-color: #EADDFF !important;
    }

    .fc .fc-button-active {
        background-color: var(--m3-primary) !important;
        color: #fff !important;
    }

    /* Day Cells */
    .fc-daygrid-day-number {
        font-weight: 700;
        padding: 8px !important;
        color: var(--m3-on-surface);
    }

    .fc-col-header-cell {
        background: #F3EDF7;
        padding: 10px 0 !important;
        border-radius: 8px 8px 0 0;
    }

    .fc-col-header-cell-cushion {
        color: var(--m3-primary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    /* Events */
    .fc-event {
        border-radius: 8px !important;
        border-width: 1px !important;
        margin: 2px 4px !important;
        padding: 2px 6px !important;
        cursor: pointer;
        transition: transform 0.1s;
    }

    .fc-event:hover {
        transform: scale(1.02);
    }

    /* Holiday Highlighting */
    .weekend-cell {
        background-color: #f8cfcf !important;
    }

    .fc-day-today {
        background-color: #EADDFF !important;
    }
</style>


<style>
    /* Hero Section Styles */
    .hero-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 16px;
    }

    .hero-card {
        background: #fff;
        border-radius: 20px;
        padding: 16px;
        border: 1px solid #E7E0EC;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }

    .hero-card.ongoing { background-color: #F3EDF7; border-color: #D0BCFF; }
    .hero-card.next { background-color: #E8F0FF; border-color: #B2D7FF; }

    .hero-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }
    .ongoing .hero-label { color: #6750A4; }
    .next .hero-label { color: #004A77; }

    .hero-title { font-size: 1rem; font-weight: 900; margin: 0; line-height: 1.2; color: #1C1B1F; }
    .hero-date { font-size: 0.75rem; font-weight: 600; color: #49454F; margin-top: 4px; }
    
    .hero-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 4rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    @media (max-width: 600px) {
        .hero-container { grid-template-columns: 1fr; }
    }
</style>

<div class="hero-container">
    <div class="hero-card ongoing shadow-sm">
        <div>
            <span class="hero-label"><i class="bi bi-broadcast-pin me-1"></i> Ongoing Now</span>
            <h5 class="hero-title"><?= $ongoing_event ? $ongoing_event['descrip'] : 'Regular Class Day' ?></h5>
            <p class="hero-date"><?= $ongoing_event ? date('d M', strtotime($ongoing_event['date'])) : 'Keep it up!' ?></p>
        </div>
        <i class="bi bi-stars hero-icon-bg"></i>
    </div>

    <div class="hero-card next shadow-sm">
        <div>
            <span class="hero-label"><i class="bi bi-calendar2-check me-1"></i> Upcoming Next</span>
            <h5 class="hero-title"><?= $next_event ? $next_event['descrip'] : 'No upcoming events' ?></h5>
            <p class="hero-date">
                <?= $next_event ? date('d M', strtotime($next_event['date'])) : '---' ?>
                <?php if ($next_event): ?>
                    <span class="ms-2 badge rounded-pill bg-primary" style="font-size: 10px;">
                        In <?= floor((strtotime($next_event['date']) - strtotime($today)) / 86400) ?> Days
                    </span>
                <?php endif; ?>
            </p>
        </div>
        <i class="bi bi-arrow-right-circle hero-icon-bg"></i>
    </div>
</div>

<main class="container-fluid">
    <div class="calendar-container shadow-sm">
        <div id='calendar'></div>
    </div>

    <div class="px-4 d-flex gap-4 justify-content-start mb-5 overflow-auto">
        <div class="small d-flex align-items-center fw-bold">
            <span class="badge me-2"
                style="background:#F9DEDC; border:1px solid #B3261E; width:12px; height:12px; border-radius:4px;">&nbsp;</span>
            Holiday
        </div>
        <div class="small d-flex align-items-center fw-bold">
            <span class="badge me-2"
                style="background:#EADDFF; border:1px solid #6750A4; width:12px; height:12px; border-radius:4px;">&nbsp;</span>
            Event
        </div>
        <div class="small d-flex align-items-center fw-bold">
            <span class="badge me-2"
                style="background:#F2F0F4; width:12px; height:12px; border-radius:4px;">&nbsp;</span> Weekend
        </div>
    </div>
</main>








<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3-redesign">
            <form id="eventForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar-plus me-2"></i> Manage Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="m3-label">Event Date</label>
                        <div id="display_date" class="fw-bold text-primary p-2 bg-light rounded-3 text-center"></div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="m3-label">Start Date</label>
                            <input type="date" name="date" id="event_date" class="m3-input-field w-100" >
                        </div>
                        <div class="col-6">
                            <label class="m3-label">End Date (Optional)</label>
                            <input type="date" name="dateto" id="event_dateto" class="m3-input-field w-100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="m3-label">Description</label>
                        <input type="text" name="descrip" id="event_desc" class="m3-input-field w-100"
                            placeholder="e.g. Annual Sports Day" required>
                    </div>

                    <div class="mb-3">
                        <label class="m3-label">Category</label>
                        <select name="category" id="event_cat" class="form-select m3-input-field">
                            <option value="General">General</option>
                            <option value="Exam">Exam</option>
                            <option value="Sports">Sports</option>
                            <option value="National">National</option>
                            <option value="Cultural">Cultural</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="m3-label">Official Work?</label>
                            <select name="work" id="event_work" class="form-select m3-input-field">
                                <option value="1">✅ Yes (Office Open)</option>
                                <option value="0">❌ No (Holiday)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="m3-label">Class Status</label>
                            <select name="class" id="event_class" class="form-select m3-input-field">
                                <option value="1">📖 Regular Class</option>
                                <option value="0">🔇 No Class</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-decoration-none fw-bold"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="m3-btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>






<?php include 'footer.php'; ?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ২. মডাল এবং এলিমেন্টগুলো আগে ভেরিয়েবলে নিয়ে নিন
        const modalEl = document.getElementById('eventModal');
        if (!modalEl) {
            console.error("Error: eventModal element not found in DOM!");
            return;
        }
        const eventModalObj = new bootstrap.Modal(modalEl);
        const eventForm = document.getElementById('eventForm');

        const calendarEl = document.getElementById('calendar');
        const eventsData = <?php echo json_encode($fullcalendar_events); ?>;
        const weekendDays = <?php echo json_encode($weekend_array); ?>;

        // ৩. ক্যালেন্ডার কনফিগারেশন
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            height: 'auto',
            events: eventsData,
            firstDay: 0, // শনিবার থেকে শুরু
            fixedWeekCount: false,
            selectable: true, // তারিখ সিলেক্ট করার অনুমতি দিন

            // তারিখ ক্লিক করলে যা হবে
            dateClick: function (info) {
                console.log("Date Clicked: " + info.dateStr); // ডিবাগিংয়ের জন্য

                // ডাটা ফেচ করার আগে ইনপুটগুলো ক্লিয়ার করে নেওয়া ভালো
                document.getElementById('event_date').value = info.dateStr;
                document.getElementById('display_date').innerText = info.dateStr;
                document.getElementById('event_desc').value = '';

                // ডাটাবেস থেকে ওই তারিখের ডাটা আনা
                fetch('?get_event_date=' + info.dateStr)
                    .then(r => r.json())
                    .then(data => {
                        console.log("Data Received:", data);

                        if (data && !data.new) {
                            document.getElementById('event_desc').value = data.descrip || '';
                            document.getElementById('event_cat').value = data.category || 'General';
                            document.getElementById('event_work').value = data.work ?? '1';
                            document.getElementById('event_class').value = data.class ?? '1';

                            document.getElementById('event_date').value = info.dateStr;
                            document.getElementById('event_dateto').value = data.dateto || '';
                        }

                        // মডালটি এখন নিশ্চিতভাবে দেখাবে
                        eventModalObj.show();
                    })
                    .catch(err => {
                        console.error("AJAX Error:", err);
                        // ডাটা না আসলেও মডালটি অন্তত খালি অবস্থায় খুলুক
                        eventModalObj.show();
                    });
            },

            // ইভেন্ট রেন্ডারিং স্টাইল
            eventContent: function (arg) {
                let icon = arg.event.extendedProps.icon || 'dot';
                return {
                    html: `<div class="d-flex align-items-center px-1 overflow-hidden">
                        <i class="bi bi-${icon} me-1" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold text-truncate" style="font-size:0.65rem;">${arg.event.title}</span>
                       </div>`
                };
            },

            // উইকেন্ড কালার
            dayCellClassNames: function (arg) {
                const dayName = new Intl.DateTimeFormat('en-US', { weekday: 'long' }).format(arg.date);
                if (weekendDays.includes(dayName)) return ['weekend-cell'];
            }
        });

        calendar.render();

        // ৪. ফর্ম সেভ করার লজিক
        eventForm.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({ title: 'সংরক্ষণ করা হচ্ছে...', didOpen: () => Swal.showLoading() });

            let fd = new FormData(this);
            fd.append('save_calendar_event', 1);

            fetch('', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(t => {
                    if (t.includes("1")) {
                        Swal.fire({ icon: 'success', title: 'সংরক্ষিত হয়েছে!', showConfirmButton: false, timer: 1000 })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', 'সেভ করা সম্ভব হয়নি।', 'error');
                    }
                });
        });
    });
</script>