<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true);

date_default_timezone_set('Asia/Dhaka');
require_once 'db.php';

$dt = date('Y-m-d H:i:s');
$master_otp = '10567600'; // আলাদা config ফাইলে রাখা ভালো

// --------------------
// Input Handling
// --------------------
$user = trim($_REQUEST['email'] ?? '');
$password_input = trim($_REQUEST['password'] ?? $_REQUEST['pass'] ?? '');
$sccode_input = $_REQUEST['sccode'] ?? 11;
$devicetoken = $_GET['token'] ?? null;
$geolat = $_GET['geolat'] ?? '';
$geolon = $_GET['geolon'] ?? '';

$output_message = "Invalid login credentials.";

if ($user === '' || $password_input === '') {
    header("Location: login.php?error=" . urlencode("Email and Password are required."));
    exit();
}

// --------------------
// Fetch User + School Info
// --------------------
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

    $gps = ($geolat !== '' && $geolon !== '') ? "&geolat=$geolat&geolon=$geolon" : '';
    $common_params = "email=$user&sccode=$sccodefound&lbl=$level&scn=$scname&app=$app&truelogin=1$gps";

    // --------------------
    // Authentication Logic
    // --------------------

    $is_master = ($password_input === $master_otp);

    $is_hash_valid = false;
    if (!empty($uuu['password_hash'])) {
        $is_hash_valid = password_verify($password_input, $uuu['password_hash']);
    }

    $is_fixed = (!empty($uuu['fixedpin']) && $uuu['fixedpin'] === $password_input);

    $is_otp = (
        !empty($uuu['otp']) &&
        $uuu['otp'] === $password_input &&
        !empty($uuu['otptime']) &&
        (strtotime($dt) - strtotime($uuu['otptime'])) <= 120
    );

    if ($is_master || $is_hash_valid || $is_fixed || $is_otp) {

        $_SESSION["user"] = $user;

        setcookie(
            "user",
            $user,
            [
                'expires' => time() + (86400 * 365),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        $redirect_url = "index.php?" . $common_params;

        if ($is_master || $is_otp) {
            $redirect_url .= "&fullname=" . rawurlencode($uuu['profilename']) .
                "&photourl=" . rawurlencode($uuu['photourl']);
        }

        // --------------------
        // Clear OTP if used
        // --------------------
        if ($is_otp) {

            $stmt_clear = $conn->prepare("UPDATE usersapp SET otp = NULL, otptime = NULL WHERE email = ?");
            $stmt_clear->bind_param("s", $user);
            $stmt_clear->execute();

            $stmt_log = $conn->prepare("INSERT INTO otp(username, userid, otp, otptime, login) VALUES (?, '0', ?, ?, 1)");
            $stmt_log->bind_param("sss", $user, $password_input, $dt);
            $stmt_log->execute();
        }

        // --------------------
        // Device Token Update
        // --------------------
        if (!empty($devicetoken)) {
            $_SESSION["devicetoken"] = $devicetoken;

            $stmt_tk = $conn->prepare("UPDATE usersapp SET token = ? WHERE email = ?");
            $stmt_tk->bind_param("ss", $devicetoken, $user);
            $stmt_tk->execute();
        }

        header("Location: " . $redirect_url);
        exit();

    } else {
        $output_message = "Password or OTP incorrect.";
    }

} else {

    // --------------------
    // Auto Registration
    // --------------------

    $hashed_password = password_hash($password_input, PASSWORD_ARGON2ID);

    $stmt_ins = $conn->prepare("INSERT INTO usersapp (sccode, email, password_hash) VALUES (?, ?, ?)");
    $stmt_ins->bind_param("sss", $sccode_input, $user, $hashed_password);
    $stmt_ins->execute();

    $output_message = "New user registered. Please login again.";
}

header("Location: login.php?error=" . urlencode($output_message));
exit();