<?php
// ১. সেশন শুরু করা (অবশ্যই সবার আগে থাকতে হবে)
session_start();

// ২. চেক করা হচ্ছে ইউজার ইতিমধ্যে লগড ইন কি না
// মনে করুন লগিন করার সময় আপনি $_SESSION['user_id'] সেট করেছিলেন
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // লগড ইন থাকলে ইনডেক্স পেজে পাঠিয়ে দিবে
    exit(); // কোড এক্সিকিউশন এখানে বন্ধ করবে
}




$error_message = '';
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Login - EIMBox</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --md-sys-color-primary: #6750A4;
            /* Material M3 Primary */
            --md-sys-color-on-primary: #FFFFFF;
            --md-sys-color-surface: #FEF7FF;
            --md-sys-color-outline: #79747E;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--md-sys-color-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 28px;
            /* Material 3 Roundness */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            border: 1px solid #eee;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .form-floating>.form-control {
            border: 1px solid var(--md-sys-color-outline);
            border-radius: 8px;
        }

        .form-floating>.form-control:focus {
            border-color: var(--md-sys-color-primary);
            box-shadow: 0 0 0 1px var(--md-sys-color-primary);
        }

        .btn-material {
            background-color: var(--md-sys-color-primary);
            color: var(--md-sys-color-on-primary);
            border-radius: 100px;
            /* Material Pill shape */
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-material:hover {
            box-shadow: 0 2px 8px rgba(103, 80, 164, 0.3);
            opacity: 0.9;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--md-sys-color-outline);
        }
    </style>
</head>

<body>

    <div class="login-card text-center mx-3">
        <img src="iimg/logo.png" alt="EIMBox" class="login-logo">
        <h1 class="h4 fw-bold mb-1">Welcome Back</h1>
        <p class="text-muted mb-4">Sign in to EIMBox System</p>

        <?php if ($error_message): ?>
            <div class="alert alert-danger d-flex align-items-center rounded-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?php echo $error_message; ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="checkeiin.php">
            <div class="form-floating mb-3">
                <input type="text" name="email" class="form-control" id="userInput" placeholder="Email/Username"
                    required>
                <label for="userInput"><i class="bi bi-person me-2"></i>Email or Username</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" name="password" class="form-control" id="passInput" placeholder="Password"
                    required>
                <label for="passInput"><i class="bi bi-shield-lock me-2"></i>Password or OTP</label>
            </div>

            <button type="submit" class="btn btn-material w-100 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
        </form>

        <div class="mt-4">
            <small class="text-muted">Fast & Secure School Management</small>
        </div>
    </div>

</body>

</html>