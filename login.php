<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ২. ইউজার লগড ইন থাকলে সরাসরি ড্যাশবোর্ডে পাঠানো
if (isset($_SESSION['user_id']) || isset($_SESSION['usr'])) {
    header("Location: index.php");
    exit();
}

$error_message = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - EIMBox System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --md-primary: #6750A4;
            --md-surface: #FEF7FF;
            --md-outline: #79747E;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--md-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* আপনার নির্দেশিত ৮ পিক্সেল রেডিয়াস গ্লোবালি সেট করা হলো */
        .login-card {
            background: #ffffff;
            border-radius: 8px; /* গাইডলাইন অনুযায়ী ৮ পিক্সেল */
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(103, 80, 164, 0.08);
            border: 1px solid #eee;
        }

        .login-logo {
            width: 56px;
            height: 56px;
            border-radius: 8px; /* ৮ পিক্সেল রেডিয়াস */
            margin-bottom: 1.5rem;
            object-fit: contain;
        }

        /* M3 Input Styles */
        .form-floating > .form-control {
            border: 1px solid var(--md-outline);
            border-radius: 8px; /* ৮ পিক্সেল রেডিয়াস */
        }

        .form-floating > .form-control:focus {
            border-color: var(--md-primary);
            box-shadow: 0 0 0 1px var(--md-primary);
        }

        .form-floating > label {
            color: #49454F;
            font-weight: 500;
        }

        /* M3 Primary Button (8px Radius) */
        .btn-m3-login {
            background-color: var(--md-primary);
            color: white;
            border-radius: 8px; /* ৮ পিক্সেল রেডিয়াস */
            padding: 12px;
            font-weight: 700;
            border: none;
            transition: all 0.2s;
            letter-spacing: 0.5px;
        }

        .btn-m3-login:active {
            transform: scale(0.97);
            opacity: 0.9;
        }

        .error-alert {
            background-color: #FFF0F0;
            color: #B3261E;
            border: 1px solid #F9DEDC;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="login-card text-center">
        <img src="iimg/logo.png" alt="EIMBox Logo" class="login-logo shadow-sm" onerror="this.src='https://eimbox.com/iimg/logo.png'">
        <h1 class="h5 fw-bold mb-1">EIMBox System</h1>
        <p class="text-muted small mb-4">Fast & Secure School Management</p>

        <?php if ($error_message): ?>
            <div class="alert error-alert d-flex align-items-center mb-4 py-2 px-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div><?php echo htmlspecialchars($error_message); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="checkeiin.php">
            <div class="form-floating mb-3">
                <input type="text" name="email" class="form-control" id="userInput" placeholder="Email/Username" required autocomplete="username">
                <label for="userInput"><i class="bi bi-person-fill me-2"></i>Username or Email</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required autocomplete="current-password">
                <label for="passInput"><i class="bi bi-shield-lock-fill me-2"></i>Security Password</label>
            </div>

            <button type="submit" class="btn btn-m3-login w-100 shadow-sm">
                SIGN IN <i class="bi bi-arrow-right-short ms-1"></i>
            </button>
        </form>

        <div class="mt-4 pt-2">
            <p class="text-muted mb-0" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 700;">
                © 2026 EIMBox Global Service
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>