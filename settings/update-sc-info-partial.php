<?php
/**
 * Universal Partial Update Handler for scinfo
 */
include '../inc.light.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['card_type'])) {
    
    $type = $_POST['card_type'];
    $fields = [];

    // কার্ড টাইপ অনুযায়ী কলাম নির্ধারণ
    switch ($type) {
        case 'identity':
            $fields = ['scname', 'short', 'headname', 'headtitle'];
            break;
        case 'contact':
            $fields = ['mobile', 'scmail', 'scweb'];
            break;
        case 'location':
            $fields = ['scadd1', 'ps', 'dist', 'geolat', 'geolon'];
            break;
        case 'protocol':
            $fields = ['intime', 'outtime', 'dista_differ', 'time_differ'];
            break;
        case 'payments':
            $fields = ['bkash', 'nagad', 'bank'];
            break;
        case 'sms':
            $fields = ['sms_gateway', 'sms_in', 'sms_absent'];
            break;
        case 'system':
            $fields = ['algorithm', 'secret_key', 'daily_backup'];
            break;
        default:
            die("Invalid update request.");
    }

    // ডাইনামিক কোয়েরি বিল্ডিং
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