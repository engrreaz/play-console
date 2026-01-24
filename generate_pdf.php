<?php
// এখানে ডাটাবেজ থেকে ডাটা ফেচ করার লজিক থাকবে (ID অনুযায়ী)
// উদাহরণস্বরূপ কিছু ডামি ডাটা:
$student_name = "ARIFUR RAHMAN";
$activity_title = "Annual Debate Competition";
$category = "Cultural";
$level = "National Level";
$award = "Champion";
$issue_date = date('d F, Y');
$inst_name = "EIM BOX MODEL SCHOOL";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Montserrat:wght@400;700;900&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }

        .certificate-container {
            width: 800px;
            height: 560px;
            padding: 40px;
            margin: 20px auto;
            background: #fff;
            position: relative;
            border: 15px solid #6750A4;
            /* M3 Primary Color Border */
            box-sizing: border-box;
            background-image: radial-gradient(circle at 50% 50%, rgba(103, 80, 164, 0.03) 0%, transparent 80%);
        }

        /* ভেতরের অলঙ্কৃত বর্ডার */
        .inner-border {
            border: 2px solid #EADDFF;
            height: 100%;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            position: relative;
        }

        .header-logo {
            font-size: 24px;
            font-weight: 900;
            color: #6750A4;
            letter-spacing: 2px;
        }

        .title {
            font-size: 42px;
            font-weight: 700;
            color: #1C1B1F;
            margin: 20px 0 10px;
        }

        .sub-title {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: #777;
            margin-bottom: 30px;
        }

        .presented-to {
            font-size: 16px;
            font-style: italic;
            color: #444;
        }

        .student-name {
            font-family: 'Pinyon Script', cursive;
            /* ক্যালিগ্রাফি ফন্ট */
            font-size: 52px;
            color: #6750A4;
            margin: 10px 0;
            border-bottom: 1px solid #eee;
            display: inline-block;
            min-width: 300px;
        }

        .description {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            width: 80%;
            margin: 20px auto;
        }

        .achievement-highlight {
            font-weight: 700;
            color: #1C1B1F;
            text-transform: uppercase;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding: 0 40px;
        }

        .sign-box {
            width: 150px;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #aaa;
            margin-bottom: 5px;
        }

        .sign-text {
            font-size: 11px;
            font-weight: 700;
            color: #777;
        }

        /* কর্নার ডেকোরেশন */
        .corner-element {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 5px solid #6750A4;
        }

        .top-left {
            top: -5px;
            left: -5px;
            border-right: none;
            border-bottom: none;
        }

        .bottom-right {
            bottom: -5px;
            right: -5px;
            border-left: none;
            border-top: none;
        }

        /* ডিজিটাল সিল */
        .seal {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            background: #EADDFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #6750A4;
            opacity: 0.6;
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="inner-border">
            <div class="corner-element top-left"></div>
            <div class="corner-element bottom-right"></div>

            <div class="header-logo"><?php echo $inst_name; ?></div>

            <div class="title">Certificate of Merit</div>
            <div class="sub-title">Outstanding Achievement</div>

            <div class="presented-to">This certificate is proudly presented to</div>
            <div class="student-name"><?php echo $student_name; ?></div>

            <div class="description">
                For their remarkable performance and contribution in the
                <span class="achievement-highlight"><?php echo $activity_title; ?></span>
                held during the <span class="achievement-highlight"><?php echo $level; ?></span>
                competition. We recognize their dedication as a
                <span class="achievement-highlight"><?php echo $award; ?></span>.
            </div>

            <div class="seal">
                <span style="font-size: 10px; font-weight: 800; color: #6750A4;">OFFICIAL<br>SEAL</span>
            </div>

            <div class="footer">
                <div class="sign-box">
                    <div class="sign-line"></div>
                    <div class="sign-text">Principal Signature</div>
                </div>
                <div style="font-size: 12px; color: #999; padding-top: 15px;">Date: <?php echo $issue_date; ?></div>
                <div class="sign-box">
                    <div class="sign-line"></div>
                    <div class="sign-text">Event In-Charge</div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>