<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --m3-bg: #FEF7FF;
            --m3-primary: #6750A4;
            --m3-primary-gradient: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
            --m3-tonal: #F3EDF7;
            --m3-outline: #CAC4D0;
        }

        body {
            background-color: var(--m3-bg);
            font-family: 'Segoe UI', Roboto, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* 404 Canvas */
        .error-container {
            text-align: center;
            max-width: 500px;
            padding: 20px;
        }

        /* Big 404 with Mesh Gradient */
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: var(--m3-primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 10px;
            filter: drop-shadow(0 10px 20px rgba(103, 80, 164, 0.2));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1C1B1F;
            margin-bottom: 10px;
        }

        .error-desc {
            color: #49454F;
            font-size: 0.95rem;
            margin-bottom: 30px;
        }

        /* M3 Floating Search Box */
        .m3-floating-group {
            position: relative;
            margin-bottom: 24px;
        }

        .m3-input-floating {
            width: 100%;
            height: 56px;
            padding: 12px 16px 12px 48px;
            font-size: 0.95rem;
            border: 2px solid var(--m3-outline);
            border-radius: 12px !important;
            outline: none;
            background: white;
            transition: 0.3s;
        }

        .m3-input-floating:focus {
            border-color: var(--m3-primary);
            box-shadow: 0 0 0 4px rgba(103, 80, 164, 0.1);
        }

        .m3-field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--m3-primary);
            font-size: 1.2rem;
        }

        /* M3 Primary Button */
        .btn-m3-home {
            background: var(--m3-primary-gradient);
            color: white;
            border: none;
            border-radius: 16px !important;
            padding: 14px 28px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
            transition: 0.3s;
        }

        .btn-m3-home:hover {
            transform: scale(1.05);
            color: white;
            box-shadow: 0 8px 20px rgba(103, 80, 164, 0.4);
        }

        /* Glass Circles Background */
        .glass-circle {
            position: absolute;
            z-index: -1;
            background: var(--m3-primary-tonal);
            filter: blur(60px);
            border-radius: 50%;
            opacity: 0.5;
        }
    </style>
</head>

<body>

    <div class="glass-circle" style="width: 300px; height: 300px; top: -50px; left: -50px;"></div>
    <div class="glass-circle" style="width: 250px; height: 250px; bottom: -50px; right: -50px; background: #EADDFF;">
    </div>

    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Oops! Page not found</h1>
        <p class="error-desc">
            অপেক্ষা করুন! আপনি যে পৃষ্ঠাটি খুঁজছেন সেটি হয়তো স্থানান্তরিত হয়েছে অথবা এটি আর বিদ্যমান নেই।
        </p>

        <div class="m3-floating-group">
            <i class="bi bi-search m3-field-icon"></i>
            <input type="text" class="m3-input-floating" placeholder="কি খুঁজছেন? এখানে লিখুন...">
        </div>

        <div class="d-flex flex-column gap-2">
            <a href="index.php" class="btn-m3-home">
                <i class="bi bi-house-door-fill"></i>
                মূল পাতায় ফিরে যান
            </a>
            <button class="btn btn-link text-decoration-none text-muted fw-bold mt-2" onclick="history.back()">
                <i class="bi bi-arrow-left"></i> পূর্ববর্তী পাতায় যান
            </button>
        </div>
    </div>

</body>

</html>