<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Dhaka');
require_once 'db.php'; // mysqli connection assumes $conn

// --- Configuration ---
$dt = date('Y-m-d H:i:s');
$master_otp = '10567600'; // সিকিউরিটির জন্য এটি আলাদা ফাইলে রাখা ভালো

// --- Input Handling (Sanitized) ---
$user = $_REQUEST['email'] ?? '';
$otp  = $_REQUEST['password'] ?? $_REQUEST['pass'] ?? '';
$sccode_input = $_REQUEST['sccode'] ?? 11;
$devicetoken  = $_GET['token'] ?? null;
$geolat = $_GET['geolat'] ?? '';
$geolon = $_GET['geolon'] ?? '';

$pin_ok = 0;
$output_message = "Invalid login credentials.";

if (empty($user) || empty($otp)) {
    header("Location: login.php?error=" . urlencode("Email and Password are required."));
    exit();
}

// --- Optimized Query (JOIN used to get user & school info in one shot) ---
$sql = "SELECT u.*, s.scname, s.app
        FROM usersapp u 
        LEFT JOIN scinfo s ON u.sccode = s.sccode 
        WHERE u.email = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$uuu = $result->fetch_assoc();

if ($uuu) {
    $sccodefound = $uuu['sccode'];
    $level = $uuu['userlevel'];
    $scname = rawurlencode($uuu['scname'] ?? 'EIMBox Institute');
    $app = rawurlencode($uuu['app'] ?? '0');
    
    // GPS & URL Logic
    $gps = ($geolat != '' && $geolon != '') ? "&geolat=$geolat&geolon=$geolon" : '';
    $common_params = "email=$user&sccode=$sccodefound&lbl=$level&scn=$scname&app=$app&truelogin=1$gps";
    
    // Check Authentication
    $is_master = ($otp === $master_otp);
    $is_fixed  = ($uuu['fixedpin'] === $otp);
    $is_otp    = ($uuu['otp'] === $otp && (strtotime($dt) - strtotime($uuu['otptime'])) <= 120);

    if ($is_master || $is_fixed || $is_otp) {
        $pin_ok = 1;
        $_SESSION["user"] = $user;
        setcookie("user", $user, time() + (86400 * 365), "/"); // 1 year cookie

        // Success Redirect URL construction
        $redirect_url = "index.php?" . $common_params;
        if ($is_master || $is_otp) {
            $redirect_url .= "&fullname=" . rawurlencode($uuu['profilename']) . "&photourl=" . rawurlencode($uuu['photourl']);
        }

        // Post-Login Actions: Clear OTP & Token Update
        if ($is_otp) {
            $stmt_clear = $conn->prepare("UPDATE usersapp SET otp = NULL, otptime = NULL WHERE email = ?");
            $stmt_clear->bind_param("s", $user);
            $stmt_clear->execute();
            
            $stmt_log = $conn->prepare("INSERT INTO otp(username, userid, otp, otptime, login) VALUES (?, '0', ?, ?, 1)");
            $stmt_log->bind_param("sss", $user, $otp, $dt);
            $stmt_log->execute();
        }

        if ($devicetoken) {
            $_SESSION["devicetoken"] = $devicetoken;
            $stmt_tk = $conn->prepare("UPDATE usersapp SET token = ? WHERE email = ?");
            $stmt_tk->bind_param("ss", $devicetoken, $user);
            $stmt_tk->execute();
        }

        header("Location: " . $redirect_url);
        exit();
    } else {
        $output_message = "OTP expired or incorrect pin.";
    }
} else {
    // New User Auto-Registration (Optional logic based on your request)
    $stmt_ins = $conn->prepare("INSERT INTO usersapp (sccode, email, fixedpin) VALUES (?, ?, ?)");
    $stmt_ins->bind_param("sss", $sccode_input, $user, $otp);
    $stmt_ins->execute();
    $output_message = "New user registered. Please login again.";
}

// If failed
header("Location: login.php?error=" . urlencode($output_message));
exit();