<?php
if (!isset($_SESSION)) {
  session_start();
}
date_default_timezone_set('Asia/Dhaka');

include_once '../db.php';

$BASE_PATH_URL = 'https://eimbox.com/';

// ---------------------------------------------------

include_once __DIR__ . '/functions.php';

$SY = date('Y');
$sy = date('y');

$td = date('Y-m-d');
$cur = date('Y-m-d H:i:s');


$usr = $_SESSION['user'] ?? null;
$sccode = (int) $usr / 10000;

$scinfo = [];
$qry = $conn->query("SELECT * FROM `scinfo` WHERE `sccode` = '$sccode' ");
if ($qry->num_rows > 0) {
    $scinfo = $qry->fetch_assoc();
}



?>


<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'EIMBox Dashboard'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>


    <?php

    $page_title = "Welcome Guest";

    
    $institution_name = "EIMBox International School";
    $institution_code = "EIIN: $sccode | Estd: 2012";
    $welcome_msg = "আমাদের ডিজিটাল ক্যাম্পাসে আপনাকে স্বাগতম। একজন অতিথি (Guest) হিসেবে আপনি প্রতিষ্ঠানের পাবলিক রিসোর্স, নোটিশ এবং সাধারণ তথ্যাদি সরাসরি ব্রাউজ করতে পারবেন।";
    ?>

    <style>
        body {
            background-color: #FAF8FC;
            /* M3 Light Surface Tint */
            font-size: 0.9rem;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* 1. Modern Minimalist Institution Banner (No Cards / No Shadows) */
        .guest-hero-banner {
            background: #EADDFF;
            /* M3 Tonal Purple Container */
            color: #21005D;
            padding: 40px 24px;
            border-radius: 0 0 24px 24px;
            border-bottom: 1px solid #D0BCFF;
        }

     

        .inst-title {
            font-size: 1.4rem;
            font-weight: 900;
            color: #1C1B1F;
            letter-spacing: -0.4px;
            margin-bottom: 2px;
        }

        .inst-meta {
            font-size: 0.72rem;
            font-weight: 700;
            color: #4F378B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .inst-desc {
            font-size: 0.85rem;
            color: #49454F;
            line-height: 1.4;
            font-weight: 500;
        }

        /* 2. Flat Layout Section Headings */
        .section-lbl {
            font-size: 0.75rem;
            font-weight: 800;
            color: #49454F;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 24px 24px 10px 24px;
            background: #FAF8FC;
        }

        /* 3. Primary Guest Gate (Tonal Active Panel) */
        .guest-gate-panel {
            background: #FFFFFF;
            padding: 24px;
            border-bottom: 1px solid #ECE6F0;
            text-align: center;
        }

        .btn-m3-primary-tonal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #6750A4;
            color: #FFFFFF;
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100%;
            max-width: 320px;
            text-decoration: none !important;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }

        .btn-m3-primary-tonal:active {
            background: #4F378B;
            transform: scale(0.98);
        }

        .gate-warning-text {
            font-size: 0.7rem;
            color: #79747E;
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }

        /* 4. Flat Navigation List Group (Card-less Linear Design) */
        .m3-flat-list-group {
            background: #FFFFFF;
            border-bottom: 1px solid #ECE6F0;
        }

        .m3-list-flat-item {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            background: #FFFFFF;
            border-bottom: 1px solid #F4EFF4;
            text-decoration: none !important;
            color: #1C1B1F;
            transition: background-color 0.15s ease;
        }

        .m3-list-flat-item:last-child {
            border-bottom: none;
        }

        .m3-list-flat-item:active {
            background-color: #EADDFF;
            /* M3 State Layer */
        }

        /* Tonal Icons Configurations */
        .icon-box-flat {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 16px;
            flex-shrink: 0;
        }

        /* Category Specific Tonal Shades */
        .c-about {
            background: #E8DEF8;
            color: #1D192B;
        }

        /* Purple */
        .c-notice {
            background: #FCE4EC;
            color: #C2185B;
        }

        /* Pink */
        .c-gallery {
            background: #E0F2F1;
            color: #004D40;
        }

        /* Teal */
        .c-contact {
            background: #FFF3E0;
            color: #E65100;
        }

        /* Amber */
        .c-portal {
            background: #E8F5E9;
            color: #1B5E20;
        }

        /* Green */

        .item-info-block {
            flex-grow: 1;
            overflow: hidden;
        }

        .st-flat-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: #1C1B1F;
            margin-bottom: 1px;
        }

        .st-flat-desc {
            font-size: 0.72rem;
            color: #79747E;
            font-weight: 500;
        }

        .flat-chevron {
            color: #79747E;
            font-size: 0.95rem;
            opacity: 0.5;
            margin-left: 8px;
        }
    </style>
