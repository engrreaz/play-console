<?php 
$page_title = "Academic Calendar";
include 'inc.guest.php'; 
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
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FFF3E0; color: #E65100; flex-direction: column; justify-content: center;">
                <div style="font-size: 0.75rem; font-weight: bold; line-height: 1;">APR</div>
                <div style="font-size: 1.1rem; font-weight: bold; line-height: 1; margin-top: 2px;">14</div>
            </div>
            <div class="item-info-block">
                <div class="st-flat-title">Pohela Boishakh</div>
                <div class="st-flat-desc">Bengali New Year Celebration - Campus closed for classes.</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E3F2FD; color: #1565C0; flex-direction: column; justify-content: center;">
                <div style="font-size: 0.75rem; font-weight: bold; line-height: 1;">MAY</div>
                <div style="font-size: 1.1rem; font-weight: bold; line-height: 1; margin-top: 2px;">01</div>
            </div>
            <div class="item-info-block">
                <div class="st-flat-title">May Day</div>
                <div class="st-flat-desc">International Workers' Day - National Holiday.</div>
            </div>
        </div>
    </div>

    <!-- ACADEMIC SCHEDULE -->
    <div class="section-lbl">Exam & Term Schedule</div>
    <div class="m3-flat-list-group">
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #FCE4EC; color: #C2185B;"><i class="bi bi-pencil-square"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Half Yearly Examination</div>
                <div class="st-flat-desc">Starts from 15th June. Routine will be published soon.</div>
            </div>
        </div>
        <div class="m3-list-flat-item">
            <div class="icon-box-flat" style="background: #E8DEF8; color: #381E72;"><i class="bi bi-journal-check"></i></div>
            <div class="item-info-block">
                <div class="st-flat-title">Result Publication</div>
                <div class="st-flat-desc">Half Yearly Exam results will be distributed on 10th July.</div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer-guest.php'; ?>
