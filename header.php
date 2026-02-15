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

    <!-- Bootstrap Bundle JS (JS + Popper) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <?php if (basename($_SERVER['PHP_SELF']) == 'calendar.php'): ?>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <?php endif; ?>


    <link rel="icon" type="image/png" href="iimg/logo.png">

    <!-- <link rel="stylesheet" href="assets/style.css"> -->
    <!-- <link rel="stylesheet" href="assets/console.css"> -->
    <link rel="stylesheet" href="assets/css/m3_eim_style.css">

    <style>
        :root {
            --md-primary: #6750A4;
            --md-surface: #FEF7FF;
            --md-on-surface: #1C1B1F;
            --md-outline: #79747E;
        }

        body {
            font-family: 'Roboto', 'Noto Sans Bengali', sans-serif;
            background-color: var(--md-surface);
            color: var(--md-on-surface);
            -webkit-tap-highlight-color: transparent;
            margin: 0;
            padding: 0;
        }

        /* আপনার নির্দেশিত ৮ পিক্সেল রেডিয়াস গ্লোবালি সেট করা হলো */
        .m-card,
        .card,
        .btn,
        .form-control,
        .form-select,
        .m3-app-bar {
            border-radius: 8px !important;
        }

        /* ফুল-ওয়াইড টপ বার ফিক্স */
        .m3-app-bar {
            width: 100%;
            height: 56px;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eee;
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .m3-app-bar .back-btn {
            color: #1C1B1F;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-decoration: none;
        }

        .m3-app-bar .back-btn:active {
            background-color: #EADDFF;
        }

        .m3-app-bar .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            flex-grow: 1;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .ytclose {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 22px;
            cursor: pointer;
            font-size: 1rem;
            color: white;
        }


        .avatar-ring-red {
            box-shadow: 0 0 0 2px #dc3545 !important;
                        padding:1px;

        }

        .avatar-ring-orange {
            box-shadow: 0 0 0 2px #fd7e14 !important;
                        padding:1px;

        }

        .avatar-ring-blue {
            box-shadow: 0 0 0 2px #0d6efd !important;
                        padding:1px;

        }

        .avatar-ring-green {
            box-shadow: 0 0 0 2px #198754 !important;
            padding:1px;
        }
    </style>

    <script src="assets/pre-load.js"></script>
</head>

<body>

    <?php


    $ringClass = '';

    if ($permission == 0) {
        $ringClass = 'avatar-ring-red';
    } elseif ($permission == 1) {
        $ringClass = 'avatar-ring-orange';
    } elseif ($permission == 2) {
        $ringClass = 'avatar-ring-blue';
    } elseif ($permission == 3) {
        $ringClass = 'avatar-ring-green';
    }

    if (basename($_SERVER['PHP_SELF']) != 'index.php') {
        ?>

        <header class="m3-app-bar" style="z-index: 25999;;">
            <a href="javascript:history.back()" class="back-btn">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h1 class="page-title">
                <?php echo $page_title ?? 'EIMBox'; ?>
            </h1>

            <div class="rounded-circle overflow-hidden border top-avatar shadow-sm <?= $ringClass ?>"
                style="width:34px;height:34px;cursor:pointer;z-index:25999;" onclick="toggleAvatarMenu()">

                <img src="<?= $pth ?>" style="width:100%;border-radius:50%;" >

            </div>



            <?php include_once 'core/avatar_menu.php'; ?>

        </header>


        <div id="videoModal" class="modal">
            <div class="modal-dialog modal-dialog-centered modal-lg" >
                <div class="modal-content ">
                    <span class="ytclose" onclick="closeModal()">×</span>

                    <iframe id="ytPlayer" width="100%" height="500px" src="" frameborder="0" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>

        <div style="margin-top: 8px;"></div>



        <?php
    }
    ?>