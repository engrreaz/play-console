<?php
include '../inc.light.php';
require_once dirname(__DIR__) . '/component/sms-func.php';

// ১. ইনপুট রিসিভ করা (স্যানিটাইজেশনসহ)
$stid       = $_POST['stid'] ?? '';
$prno       = $_POST['prno'] ?? '';
$prdate     = $_POST['prdate'] ?? date('Y-m-d');
$mobileno   = $_POST['mobileno'] ?? '';
$sessionyear = $_POST['sessionyear'] ?? $sy;

// স্টুডেন্টের বেসিক তথ্য (SMS এর জন্য)
$cls        = $_POST['cls'] ?? '';
$sec        = $_POST['sec'] ?? '';
$rollno     = $_POST['rollno'] ?? '';
$nben       = $_POST['nben'] ?? '';

// লুপ কাউন্ট (কতগুলো আইটেম সিলেক্ট করা হয়েছে)
$count      = intval($_POST['count'] ?? 0);

if (!$stid || !$prno || $count === 0) {
    die("Invalid Request: Missing Information. Count: $count, STID: $stid, PRNO: $prno");
}

$tamt = 0;
$conn->begin_transaction(); // ডেটাবেস ট্রানজ্যাকশন শুরু (সব সেভ হবে নাহলে কিছুই হবে না)

try {
    // ২. ফি আইটেমগুলো আপডেট করা
    for ($lp = 0; $lp < $count; $lp++) {
        $fid = $_POST['fid' . $lp] ?? 0;
        $amt = floatval($_POST['amt' . $lp] ?? 0);

        if ($fid > 0 && $amt > 0) {
            // আইটেমটি চেক করা (pr1 নাকি pr2 তে সেভ হবে)
            $check_q = $conn->prepare("SELECT pr1 FROM stfinance WHERE id = ? LIMIT 1");
            $check_q->bind_param("i", $fid);
            $check_q->execute();
            $res = $check_q->get_result()->fetch_assoc();
            
            if ($res['pr1'] > 0) {
                // pr1 ভর্তি থাকলে pr2 তে যাবে
                $upd_stmt = $conn->prepare("UPDATE stfinance SET pr2=?, pr2no=?, pr2date=?, pr2by=?, paid=paid+?, dues=dues-? WHERE id=?");
            } else {
                // pr1 খালি থাকলে সেখানেই যাবে
                $upd_stmt = $conn->prepare("UPDATE stfinance SET pr1=?, pr1no=?, pr1date=?, pr1by=?, paid=paid+?, dues=dues-? WHERE id=?");
            }

            $upd_stmt->bind_param("dssiddi", $amt, $prno, $prdate, $usr, $amt, $amt, $fid);
            $upd_stmt->execute();
            $tamt += $amt;
        }
    }

    // ৩. পেমেন্ট রিসিট (stpr) ইনসার্ট করা
    $stpr_stmt = $conn->prepare("INSERT INTO stpr (sessionyear, sccode, classname, sectionname, stid, rollno, prno, prdate, amount, entryby, entrytime, mobileno, smsstatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stpr_stmt->bind_param("sisssissdsss", $sessionyear, $sccode, $cls, $sec, $stid, $rollno, $prno, $prdate, $tamt, $usr, $cur, $mobileno);
    $stpr_stmt->execute();

    // ৪. সেশন ইনফোতে লাস্ট রিসিট আপডেট
    $si_stmt = $conn->prepare("UPDATE sessioninfo SET lastpr=? WHERE stid=? AND sessionyear LIKE ?");
    $sy_like = "%" . $sessionyear . "%";
    $si_stmt->bind_param("sss", $prno, $stid, $sy_like);
    $si_stmt->execute();

    $conn->commit(); // সব কাজ সফল হলে ডেটাবেসে স্থায়ীভাবে সেভ হবে
    echo "success";

} catch (Exception $e) {
    $conn->rollback(); // কোনো সমস্যা হলে আগের অবস্থায় ফিরে যাবে
    die("Database Error: " . $e->getMessage());
}

/* -----------------------
   ৫. SMS পাঠানোর লজিক
------------------------ */
$ins_list = []; // নির্দিষ্ট প্রতিষ্ঠানের লিস্ট

if (in_array($sccode, $ins_list) && $mobileno != '' && $tamt > 0) {
    
    $message = "প্রিয় অভিভাবক, " . $nben . ", " . $cls . " (" . $sec . ") - " . $rollno . " এর নামে = " . number_format($tamt, 2) . " টাকা জমা হয়েছে। ধন্যবাদ।\n" . $short;
    
    $len = mb_strlen($message);
    $sms_res = json_decode(sms_send($mobileno, $message));

    $response_code = $sms_res->response_code ?? '';
    $message_id = $sms_res->message_id ?? '';
    $sms_parts = ceil($len / 155);
    $cost = 0.50 * $sms_parts;

    if ($response_code == 202) {
        $sms_log = $conn->prepare("INSERT INTO sms (sccode, sessionyear, date, campaign, sms_type, mobile_number, sms_text, sms_len, count, send_by, send_time, cost, response_code, message_id, status) VALUES (?, ?, ?, '0', 'Payment Info', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Sent')");
        $sms_log->bind_param("issssiisidss", $sccode, $sessionyear, $prdate, $mobileno, $message, $len, $sms_parts, $usr, $cur, $cost, $response_code, $message_id);
        $sms_log->execute();
    }
}
?>