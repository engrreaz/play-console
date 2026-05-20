<?php
$page_title = "About Us";
include 'inc.php';
?>


<style>
    :root {
        /* Material 3 Tonal Palette */
        --md-sys-color-background: #F4F0F8;
        --md-sys-color-surface: #FCF8FF;
        --md-sys-color-primary: #6750A4;

        /* Tonal Containers */
        --md-sys-color-primary-container: #EADDFF;
        --md-sys-color-on-primary-container: #21005D;
        --md-sys-color-secondary-container: #E8DEF8;
        --md-sys-color-on-secondary-container: #1D192B;
        --md-sys-color-tertiary-container: #FFD8E4;
        --md-sys-color-on-tertiary-container: #31111D;

        --md-sys-color-on-surface: #1D1B20;
        --md-sys-color-on-surface-variant: #49454F;
        --md-sys-color-outline-variant: #CAC4D0;

        /* M3 Geometric Tokens */
        --md-shape-corner-small: 8px;
        --md-shape-corner-medium: 16px;
        --md-shape-corner-large: 28px;
        --md-shape-corner-full: 9999px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }


    /* Main Container */
    .academic-container {
        max-width: 900px;
        width: 100%;
        background-color: var(--md-sys-color-surface);
        padding: 40px;
        border-radius: var(--md-shape-corner-large);
        box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, 0.1), 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
    }

    /* Header */
    .page-header {
        text-align: center;
        margin-bottom: 36px;
    }

    .page-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--md-sys-color-primary);
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 1rem;
        color: var(--md-sys-color-on-surface-variant);
    }

    /* M3 Tonal Chips Group */
    .chip-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 32px;
    }

    .m3-chip {
        background-color: var(--md-sys-color-secondary-container);
        color: var(--md-sys-color-on-secondary-container);
        padding: 8px 20px;
        border-radius: var(--md-shape-corner-full);
        font-size: 0.9rem;
        font-weight: 500;
        border: none;
    }

    /* Section Headings */
    h2.section-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--md-sys-color-primary);
        margin-top: 32px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Horizontal Grid Cards */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-card {
        background-color: var(--md-sys-color-background);
        padding: 20px;
        border-radius: var(--md-shape-corner-medium);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .info-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--md-sys-color-on-surface);
    }

    .info-card p {
        font-size: 0.95rem;
        color: var(--md-sys-color-on-surface-variant);
    }

    /* Timeline Section for Academic Calendar */
    .timeline {
        margin-top: 16px;
    }

    .timeline-item {
        display: flex;
        gap: 20px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px dashed var(--md-sys-color-outline-variant);
    }

    .timeline-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .date-badge {
        background-color: var(--md-sys-color-tertiary-container);
        color: var(--md-sys-color-on-tertiary-container);
        padding: 6px 12px;
        border-radius: var(--md-shape-corner-small);
        font-size: 0.85rem;
        font-weight: 700;
        min-width: 100px;
        text-align: center;
        align-self: flex-start;
    }

    .event-details h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--md-sys-color-on-surface);
    }

    .event-details p {
        font-size: 0.9rem;
        color: var(--md-sys-color-on-surface-variant);
    }

    /* Full-width Tonal Alert Card */
    .alert-card {
        background-color: var(--md-sys-color-primary-container);
        color: var(--md-sys-color-on-primary-container);
        padding: 20px;
        border-radius: var(--md-shape-corner-medium);
        margin-top: 32px;
    }

    .alert-card h3 {
        font-size: 1.15rem;
        margin-bottom: 6px;
        font-weight: 600;
    }

    /* M3 Divider */
    hr {
        border: none;
        height: 1px;
        background-color: var(--md-sys-color-outline-variant);
        margin: 32px 0;
    }

    /* Responsive */
    @media (max-width: 650px) {
        body {
            padding: 16px 8px;
        }

        .academic-container {
            padding: 24px 16px;
            border-radius: var(--md-shape-corner-medium);
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .timeline-item {
            flex-direction: column;
            gap: 8px;
        }

        .date-badge {
            min-width: auto;
            width: max-content;
        }

        .page-header h1 {
            font-size: 1.8rem;
        }
    }
</style>
</head>

<body>

    <div class="academic-container">

        <!-- Page Header -->
        <div class="page-header">
            <h1>অ্যাকাডেমিক তথ্যাদি</h1>
            <p>ইআইএমবক্স স্কুল অ্যান্ড কলেজ-এর পাঠ্যক্রম, মূল্যায়ন পদ্ধতি ও শিক্ষা বর্ষপঞ্জি</p>
        </div>

        <!-- M3 Chips Group (শোভন পিলস) -->
        <div class="chip-group">
            <span class="m3-chip">প্রাথমিক শাখা</span>
            <span class="m3-chip">মাধ্যমিক শাখা</span>
            <span class="m3-chip">উচ্চ মাধ্যমিক শাখা</span>
            <span class="m3-chip">ডিজিটাল কারিকুলাম</span>
        </div>

        <!-- সেকশন ১: শিক্ষা কার্যক্রম -->
        <h2 class="section-title">📚 আমাদের শিক্ষাক্রম ও পাঠ্যসূচি</h2>
        <div class="info-grid">
            <div class="info-card">
                <h3>নিয়মিত পাঠদান ও কারিকুলাম</h3>
                <p>জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) কর্তৃক প্রণীত সর্বাধুনিক যোগ্যতাভিত্তিক মূল্যায়ন পদ্ধতি
                    ও নতুন কারিকুলাম শতভাগ অনুসরণ করে আমাদের পাঠদান পরিচালনা করা হয়।</p>
            </div>
            <div class="info-card">
                <h3>ডিজিটাল ক্লাসরুম ও ল্যাব</h3>
                <p>সাপ্তাহিক তাত্ত্বিক ক্লাসের পাশাপাশি প্রতিটি ক্লাসের জন্য রয়েছে মাল্টিমিডিয়া প্রজেক্টর ও গ্রাফিক্স
                    সমৃদ্ধ ল্যাব সেশন, যা কঠিন বিষয়গুলোকে সহজ ও প্রায়োগিক করে তোলে।</p>
            </div>
        </div>

        <!-- সেকশন ২: পরীক্ষা ও মূল্যায়ন -->
        <h2 class="section-title">📝 পরীক্ষা ও মূল্যায়ন পদ্ধতি</h2>
        <div class="info-grid">
            <div class="info-card">
                <h3>ধারাবাহিক শিখনকালীন মূল্যায়ন</h3>
                <p>নতুন কারিকুলাম অনুযায়ী শিক্ষার্থীদের শুধুমাত্র সামষ্টিক পরীক্ষার ওপর মূল্যায়ন না করে, সারা বছর
                    ক্লাসের পারফরম্যান্স, অ্যাসাইনমেন্ট ও প্রজেক্টের মাধ্যমে শিখনকালীন মূল্যায়ন করা হয়।</p>
            </div>
            <div class="info-card">
                <h3>সামষ্টিক মূল্যায়ন উৎসব</h3>
                <p>প্রতিটি সেমিস্টারের শেষে বছরের ২টি মূল্যায়নের (ষাণ্মাসিক ও বার্ষিক) মাধ্যমে শিক্ষার্থীদের সামগ্রিক
                    পারদর্শিতার রিপোর্ট বা ট্রান্সক্রিপ্ট প্রস্তুত করা হয়।</p>
            </div>
        </div>

        <hr>

        <!-- সেকশন ৩: বর্ষপঞ্জি / টাইমলাইন -->
        <h2 class="section-title">📅 অ্যাকাডেমিক ক্যালেন্ডার ও গুরুত্বপূর্ণ তারিখ</h2>
        <div class="timeline">

            <div class="timeline-item">
                <div class="date-badge">০১ জানুয়ারি</div>
                <div class="event-details">
                    <h4>বই উৎসব ও নতুন শিক্ষাবর্ষের সূচনা</h4>
                    <p>নতুন শিক্ষাবর্ষের প্রথম দিনে সকল শিক্ষার্থীর মাঝে সরকারি পাঠ্যপুস্তক বিতরণ এবং ওরিয়েন্টেশন
                        ক্লাস।</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="date-badge">২২ - ৩০ মে</div>
                <div class="event-details">
                    <h4>ষাণ্মাসিক সামষ্টিক মূল্যায়ন</h4>
                    <p>বছরের প্রথম অর্ধবার্ষিকের শিখন মেধা যাচাইয়ের জন্য বিশেষ সামষ্টিক মূল্যায়ন উৎসব ও প্রজেক্ট
                        সাবমিশন।</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="date-badge">১৫ অক্টোবর</div>
                <div class="event-details">
                    <h4>বার্ষিক বিজ্ঞান ও প্রযুক্তি মেলা</h4>
                    <p>শিক্ষার্থীদের সৃজনশীলতা ও উদ্ভাবনী ক্ষমতা বিকাশের লক্ষ্যে বার্ষিক বিজ্ঞান প্রজেক্ট প্রদর্শনী ও
                        প্রতিযোগিতা।</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="date-badge">১১ - ২০ নভেম্বর</div>
                <div class="event-details">
                    <h4>বার্ষিক সামষ্টিক মূল্যায়ন</h4>
                    <p>শিক্ষাবর্ষের চূড়ান্ত মূল্যায়ন পরীক্ষা এবং পরবর্তী শ্রেণিতে প্রমোশনের জন্য পারদর্শিতার রিপোর্ট
                        তৈরি।</p>
                </div>
            </div>

        </div>

        <!-- বিশেষ নির্দেশনা কার্ড (Primary Tonal Alert) -->
        <div class="alert-card">
            <h3>⚠️ নোটিশ ও বিশেষ নির্দেশনাবলী</h3>
            <p>প্রত্যেক শিক্ষার্থীর জন্য ন্যূনতম ৮০% ক্লাসে উপস্থিতি বাধ্যতামূলক। কোনো শিক্ষার্থী অনুপস্থিত থাকলে
                ইআইএমবক্স অ্যাপের মাধ্যমে তাৎক্ষণিকভাবে অভিভাবককে অবহিত করা হয়। যেকোনো অ্যাকাডেমিক পরিবর্তনের সিদ্ধান্ত
                নোটিশ বোর্ডের মাধ্যমে অগ্রিম জানানো হবে।</p>
        </div>

    </div>


    <?php include 'footer.php'; ?>
</body>

</html>