<?php
$page_title = "Academics Hub";
include 'inc.php';

// কারেন্ট সেশন বা ইয়ার ট্র্যাকিং (প্রয়োজন হলে ব্যাকএন্ড ভেরিয়েবল দিয়ে রিপ্লেস করে নেবেন)
$current_academic_year = date('Y');
?>

<style>
    body {
        background-color: #FEF7FF; /* M3 Light Surface Tint */
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    /* 1. Premium M3 Tonal Hero Section (No Card Outlines) */
    .academics-hero {
        background: #F3EDF7; /* M3 Secondary Container / Tonal Surface */
        padding: 40px 24px 32px 24px;
        border-radius: 0 0 28px 28px; /* Smooth M3 Large Corner Shape */
        color: #1D192B;
        margin-bottom: 24px;
    }

    .hero-pre-title {
        font-size: 0.72rem;
        font-weight: 800;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 6px;
    }

    .hero-main-title {
        font-size: 1.75rem;
        font-weight: 900;
        color: #1C1B1F;
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .hero-sub-text {
        font-size: 0.85rem;
        color: #49454F;
        font-weight: 500;
        max-width: 500px;
        line-height: 1.4;
    }

    /* 2. Flat Tonal Nav Row Layout (No Cards, Pure M3 List) */
    .hub-section-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #49454F;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 24px;
        margin-bottom: 8px;
    }

    .tonal-links-container {
        display: flex;
        flex-direction: column;
        background: #FFFFFF;
        border-bottom: 1px solid #ECE6F0;
        margin-bottom: 20px;
    }

    .m3-tonal-row {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        text-decoration: none !important;
        color: #1C1B1F;
        border-bottom: 1px solid #F4EFF4;
        transition: background-color 0.15s ease;
    }

    .m3-tonal-row:last-child {
        border-bottom: none;
    }

    .m3-tonal-row:active {
        background-color: #EADDFF; /* M3 Tonal State Layer */
    }

    /* Tonal Icons Background Mapping */
    .row-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px; /* Medium Component Shape */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 16px;
        flex-shrink: 0;
    }

    /* Dynamic Color Palettes for Categories */
    .color-subjects  { background: #E8DEF8; color: #1D192B; } /* Purple Tonal */
    .color-exam      { background: #FCE4EC; color: #C2185B; } /* Pink Tonal */
    .color-calendar  { background: #E0F2F1; color: #004D40; } /* Teal Tonal */
    .color-routine   { background: #E0F7FA; color: #006064; } /* Cyan Tonal */
    .color-results   { background: #E8F5E9; color: #1B5E20; } /* Green Tonal */
    .color-fees      { background: #FFF3E0; color: #E65100; } /* Amber Tonal */
    .color-archive   { background: #E6E1E5; color: #49454F; } /* Neutral Tonal */

    .row-text-block {
        flex-grow: 1;
        overflow: hidden;
    }

    .row-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1C1B1F;
        margin-bottom: 1px;
    }

    .row-desc {
        font-size: 0.72rem;
        color: #79747E;
        font-weight: 500;
    }

    .row-chevron {
        color: #79747E;
        font-size: 1rem;
        margin-left: 8px;
    }
</style>

<main class="pb-5">

    <!-- 1. M3 TONAL HERO BLOCK -->
    <div class="academics-hero">
        <div class="hero-pre-title">Student Portal</div>
        <div class="hero-main-title"><?php echo $page_title; ?></div>
        <div class="hero-sub-text">আপনার পাঠ্যসূচি, পরীক্ষার সময়সূচী, একাডেমিক ক্যালেন্ডার এবং পূর্ববর্তী শিক্ষাবর্ষের সমস্ত তথ্য এক জায়গায় অ্যাক্সেস করুন।</div>
    </div>

    <!-- 2. CORE ACADEMICS CATEGORY -->
    <div class="hub-section-label">Core Academics</div>
    <div class="tonal-links-container">
        
        <!-- My Subjects -->
        <a href="my-assigned-subjects.php" class="m3-tonal-row">
            <div class="row-icon-box color-subjects">
                <i class="bi bi-book-half"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">My Subjects</div>
                <div class="row-desc">Assigned teachers, syllabus, and subject resources</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Class Routine / Schedule -->
        <a href="my-class-routine.php" class="m3-tonal-row">
            <div class="row-icon-box color-routine">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Class Routine</div>
                <div class="row-desc">Daily timetable and period distributions</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Academic Calendar -->
        <a href="academic-schedule.php" class="m3-tonal-row">
            <div class="row-icon-box color-calendar">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Academic Calendar</div>
                <div class="row-desc">Holidays, events, and important academic dates</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

    </div>

    <!-- 3. EVALUATIONS & ACCOUNTS CATEGORY -->
    <div class="hub-section-label">Assessments & Ledger</div>
    <div class="tonal-links-container">

        <!-- Examination -->
        <a href="my-exam-schedule.php" class="m3-tonal-row">
            <div class="row-icon-box color-exam">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Examination Schedule</div>
                <div class="row-desc">Seat plans, exam dates, and instructions</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Exam Results -->
        <a href="my-results.php" class="m3-tonal-row">
            <div class="row-icon-box color-results">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Report Card / Results</div>
                <div class="row-desc">Term finals, progress reports, and GPA sheet</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

        <!-- Tuition Fees -->
        <a href="tuition-fees-ledger.php" class="m3-tonal-row">
            <div class="row-icon-box color-fees">
                <i class="bi bi-credit-card-2-front"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Fees & Accounts Summary</div>
                <div class="row-desc">Payable dues, invoice slips, and online payment</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

    </div>

    <!-- 4. ARCHIVE / HISTORY CATEGORY -->
    <div class="hub-section-label">History & Logs</div>
    <div class="tonal-links-container">

        <!-- Academic Archive -->
        <a href="academic-archive.php" class="m3-tonal-row">
            <div class="row-icon-box color-archive">
                <i class="bi bi-archive-fill"></i>
            </div>
            <div class="row-text-block">
                <div class="row-title">Academic Archive</div>
                <div class="row-desc">Previous academic years data, old reports & logs</div>
            </div>
            <div class="row-chevron"><i class="bi bi-chevron-right"></i></div>
        </a>

    </div>

</main>

<?php include 'footer.php'; ?>