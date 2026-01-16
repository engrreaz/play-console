<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>EIMBox - School Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --md-primary: #6750A4;
            --md-on-primary: #FFFFFF;
            --md-surface: #F7F2FA;
            --md-surface-variant: #E7E0EC;
            --md-secondary: #625B71;
            --md-outline: #79747E;
            --md-error: #B3261E;
        }

        body {
            font-family: 'Roboto', 'Noto Sans Bengali', sans-serif;
            background-color: var(--md-surface);
            color: #1C1B1F;
            -webkit-tap-highlight-color: transparent;
            /* Mobile Optimization */
            padding-bottom: 70px;
            /* Space for Bottom Nav */
        }

        /* Material Card Style */
        .m-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            border: none;
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 12px;
        }

        /* Elevation Effect */
        .elevation-1 {
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.12), 0px 1px 2px rgba(0, 0, 0, 0.24);
        }

        .noprint {
            @media print {
                display: none !important;
            }
        }

        /* Fixed Header for Android View */
        .app-bar {
            background: #fff;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }


        /* Standard M3 Top App Bar Styling */
        .m3-app-bar {
            background-color: #FFFFFF;
            height: 64px;
            /* M3 Standard Height */
            display: flex;
            align-items: center;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            /* হালকা শ্যাডো */
            border-radius: 0 0 20px 20px;
            /* নিচের দিকটা হালকা রাউন্ডেড */
        }

        .m3-app-bar .back-btn {
            color: #1C1B1F;
            font-size: 1.5rem;
            margin-right: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .m3-app-bar .back-btn:active {
            background-color: #EADDFF;
            /* টাচ করলে হালকা কালার হবে */
        }

        .m3-app-bar .page-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1C1B1F;
            flex-grow: 1;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .m3-app-bar .action-icons {
            display: flex;
            gap: 8px;
            color: #49454F;
        }
    </style>

    <?php if (basename($_SERVER['PHP_SELF']) == 'calendar.php'): ?>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <?php endif; ?>

    <script src="assets/pre-load.js"></script>
    <?php include 'js.php'; ?>
</head>

<body>

    <header class="m3-app-bar shadow-sm">
        <a href="javascript:history.back()" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>

        <h1 class="page-title">
            <?php echo $page_title ?? 'EIMBox'; ?>
        </h1>

        <div class="action-icons">
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </header>

    <div style="margin-top: 10px;"></div>