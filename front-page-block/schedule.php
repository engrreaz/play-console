<?php
// ১. বর্তমান সময় ও দিন নির্ধারণ
$today = date('l');
$now_str = date('H:i:s');
$now_ts = strtotime($now_str);

/**
 * ২. বর্তমান চলমান পিরিয়ড খুঁজে বের করা (Global Schedule)
 */
$sql_period = "SELECT * FROM classschedule 
               WHERE sccode = ? AND sessionyear LIKE ? AND ? BETWEEN timestart AND timeend LIMIT 1";
$stmt_p = $conn->prepare($sql_period);
$stmt_p->bind_param("sss", $sccode, $sessionyear_param, $now_str);
$stmt_p->execute();
$current_period = $stmt_p->get_result()->fetch_assoc();

if ($current_period):
    // পিরিয়ডের প্রগ্রেস ক্যালকুলেশন
    $p_start_ts = strtotime($current_period['timestart']);
    $p_end_ts = strtotime($current_period['timeend']);
    $p_total_sec = $p_end_ts - $p_start_ts;
    $p_passed_sec = $now_ts - $p_start_ts;

    $percent = ($p_total_sec > 0) ? round(($p_passed_sec / $p_total_sec) * 100) : 0;
    $percent = min(100, max(0, $percent)); // ০-১০০ এর মধ্যে রাখা

    $p_passed_min = floor($p_passed_sec / 60);
    $p_total_min = floor($p_total_sec / 60);

    /**
     * ৩. এই পিরিয়ডে কোন কোন ক্লাসে কী কী চলছে তা খুঁজে বের করা
     * (এখানে subjects এবং teachers টেবিলের সাথে JOIN করার অপশন রাখা হয়েছে)
     */



    $sql_classes = "
        SELECT 
            r.*,
            s.subject AS subject_name,
            s.subben,
            t.tname

        FROM clsroutine r

        LEFT JOIN subjects s 
            ON r.subcode = s.subcode 
            AND r.sccode = s.sccode
            AND s.sccategory = '$sctype'

        LEFT JOIN teacher t 
            ON r.tid = t.tid 
            AND r.sccode = t.sccode

        WHERE r.sccode = ?
        AND r.sessionyear = ?
        AND r.day = ?
        AND r.period = ?

        ORDER BY r.classname ASC, s.sccode DESC
    ";

    $stmt_c = $conn->prepare($sql_classes);

    $stmt_c->bind_param(
        "iisi",
        $sccode,
        $sessionyear,
        $today,
        $current_period['period']
    );

    $stmt_c->execute();

    $active_classes = $stmt_c->get_result();


    ?>

    <style>
        /* Global Period Progress Styling */
 

        .m3-global-progress-bg {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 100px;
            height: 24px;
            width: 100%;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .m3-global-progress-bar {
            background: #FFFFFF;
            height: 100%;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6750A4;
            font-size: 13px;
            font-weight: 900;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Compact Class Card */
        .compact-class-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            padding: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: 0.2s;
        }

        .class-badge {
            min-width: 50px;
            height: 50px;
            background: var(--m3-primary-tonal);
            color: var(--m3-on-tonal-container);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            margin-right: 12px;
        }

        .subject-info {
            flex-grow: 1;
        }

        .teacher-tag {
            font-size: 0.75rem;
            color: #79747E;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .live-indicator {
            width: 8px;
            height: 8px;
            background: #B3261E;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.3
            }
        }
    </style>

    <style>
        /* Hero Card Design */
        .m3-period-hero {
            background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
            border-radius: 8px;
            padding: 16px;

            color: white;
            box-shadow: 0 12px 30px rgba(103, 80, 164, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .m3-period-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .period-info {
            z-index: 1;
        }

        .period-number {
            font-size: 1.3rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 5px;
        }

        .time-label {
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 500;
            display: block;
        }

        /* Circular Progress Container */
        .circular-container {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* SVG Styling */
        svg {
            transform: rotate(-90deg);
            width: 100px;
            height: 100px;
        }

        circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .bg-circle {
            stroke: rgba(255, 255, 255, 0.2);
        }

        .progress-circle {
            stroke: #FFFFFF;
            /* Circumference = 2 * PI * r (r=40) ≈ 251.2 */
            stroke-dasharray: 251.2;
            transition: stroke-dashoffset 1s ease-out;
        }

        /* Inside Text */
        .min-left-text {
            position: absolute;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .min-val {
            font-size: 1.5rem;
            font-weight: 900;
            line-height: 1;
        }

        .min-lbl {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            opacity: 0.9;
        }
    </style>

    <div class="m3-period-hero">
        <div class="period-info">
            <span class="time-label ">CURRENTLY RUNNING</span>
            <div class="period-number">Period <?= $current_period['period'] ?></div>
            <div class="d-flex align-items-center gap-2 mt-1">
                <span class="badge" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="bi bi-clock me-1"></i>
                    <?= date("h:i A", $p_start_ts) ?> - <?= date("h:i A", $p_end_ts) ?>
                </span>
            </div>
        </div>

        <div class="circular-container">
            <?php
            // SVG Circumference for r=40 is 251.2
            $offset = 251.2 - ($percent / 100 * 251.2);
            ?>
            <svg>
                <circle class="bg-circle" cx="50" cy="50" r="40"></circle>
                <circle class="progress-circle" cx="50" cy="50" r="40" style="stroke-dashoffset: <?= $offset ?>;"></circle>
            </svg>

            <div class="min-left-text">
                <span class="min-val"><?= max(0, $p_total_min - $p_passed_min) ?></span>
                <span class="min-lbl">Min Left</span>
            </div>
        </div>
    </div>


    <div class="container-fluid px-3">
        <div class="m3-section-title d-flex justify-content-between align-items-center">
            <span><span class="live-indicator d-inline-block"></span> Active Classes Now</span>
            <span class="badge bg-light text-dark border"><?= $active_classes->num_rows ?> Classes Running</span>
        </div>

        <div class="row g-2">
            <?php while ($row = $active_classes->fetch_assoc()): ?>
                <div class="col-12 col-md-6">
                    <div class="compact-class-card">
                        <div class="class-badge">
                            <span style="font-size: 0.8rem;">CL</span>
                            <span><?= $row['classname'] ?></span>
                        </div>
                        <div class="subject-info">
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                Section <?= $row['sectionname'] ?> • <span
                                    class="text-primary"><?= $row['subject'] ?? $row['subben'] ?></span>
                            </div>
                            <div class="teacher-tag mt-1">
                                <i class="bi bi-person-badge"></i>
                                <?= $row['tname'] ?? 'No Teacher Assigned' ?>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

<?php else: ?>
    <div class="text-center py-5 mx-3 mt-4 border rounded-4 bg-white" style="border-style: dashed !important;">
        <i class="bi bi-calendar-x display-1 opacity-10"></i>
        <p class="text-muted fw-bold mt-3">No period is currently active.</p>
        <small class="text-muted">Break time or after school hours (<?= date("h:i A") ?>)</small>
    </div>
<?php endif; ?>