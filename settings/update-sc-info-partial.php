<?php
/**
 * Universal Partial Update Handler for scinfo
 */
include '../inc.light.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['card_type'])) {
    
    $type = $_POST['card_type'];
    $fields = [];

    // টেবিলের সব ডেটা কভার করার জন্য ফিল্ড লিস্ট
    switch ($type) {
        case 'identity':
            $fields = ['scname', 'short', 'sccategory', 'headname', 'headtitle'];
            break;
        case 'contact':
            $fields = ['mobile', 'scmail', 'scmail2', 'scweb'];
            break;
        case 'location':
            $fields = ['scadd1', 'ps', 'dist', 'postal_code', 'geolat', 'geolon'];
            break;
        case 'protocol':
            $fields = ['intime', 'outtime', 'dista_differ', 'time_differ'];
            break;
        case 'payments':
            $fields = ['bkash', 'nagad', 'rocket', 'bank'];
            break;
        case 'sms':
            $fields = ['sms_gateway', 'sms_in', 'sms_absent', 'sms_payment'];
            break;
        case 'system':
            $fields = ['algorithm', 'api_key', 'backup_mail_2', 'daily_backup'];
            break;
        default:
            die("Unauthorized Section.");
    }

    $updates = [];
    $params = [];
    $types = "";

    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            $updates[] = "$f = ?";
            $params[] = $_POST[$f];
            $types .= "s";
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE scinfo SET " . implode(", ", $updates) . " WHERE sccode = ?";
        $params[] = $sccode;
        $types .= "s";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo $conn->error;
        }
        $stmt->close();
    }
}
?>