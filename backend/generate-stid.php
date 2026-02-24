<?php
include('../inc.light.php');

// ১. ইনপুট স্যানিটাইজেশন
$sccode = (int)$_POST['sccode'];
$rootuser = $_POST['rootuser'];
$id = (int)$_POST['id'];
$from = (int)$_POST['from'];
$to = (int)$_POST['to'];

// ২. এরিয়া তথ্য সংগ্রহ
$stmt = $conn->prepare("SELECT areaname, subarea, sessionyear FROM areas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$area = $stmt->get_result()->fetch_assoc();

if (!$area) die("Invalid Area ID");

$cls = $area["areaname"];
$sec = $area["subarea"];
$sy  = $area["sessionyear"];

// ৩. এরিয়া টেবিল আপডেট
$stmt_upd = $conn->prepare("UPDATE areas SET rollfrom=?, rollto=? WHERE id=?");
$stmt_upd->bind_param("iii", $from, $to, $id);
$stmt_upd->execute();

// ৪. লাস্ট আইডি জেনারেশন (ব্রুট ফোর্স এড়াতে)
$res_last = $conn->query("SELECT MAX(stid) as maxid FROM sessioninfo WHERE sccode='$sccode'");
$row_last = $res_last->fetch_assoc();
$lastid = ($row_last['maxid'] > 0) ? $row_last['maxid'] + 1 : ($sccode * 10000) + 1;

$generated_count = 0;

// ৫. আইডি লুপ (ID Generation)
for ($x = $from; $x <= $to; $x++) {
    // আগে থেকেই আছে কি না চেক
    $stmt_check = $conn->prepare("SELECT stid FROM sessioninfo WHERE sessionyear=? AND classname=? AND sectionname=? AND rollno=? AND sccode=?");
    $stmt_check->bind_param("sssii", $sy, $cls, $sec, $x, $sccode);
    $stmt_check->execute();
    $existing = $stmt_check->get_result()->fetch_assoc();

    if (!$existing) {
        // নতুন এন্ট্রি (Transaction style safe insert)
        $conn->begin_transaction();
        try {
            $stmt_info = $conn->prepare("INSERT INTO sessioninfo (stid, sessionyear, classname, sectionname, rollno, sccode, religion) VALUES (?,?,?,?,?,?, 'Islam')");
            $stmt_info->bind_param("isssii", $lastid, $sy, $cls, $sec, $x, $sccode);
            $stmt_info->execute();

            $stmt_std = $conn->prepare("INSERT INTO students (stid, sccode, religion) VALUES (?,?, 'Islam')");
            $stmt_std->bind_param("ii", $lastid, $sccode);
            $stmt_std->execute();

            $conn->commit();
            $lastid++;
            $generated_count++;
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
}
?>

<div class="alert alert-success rounded-4 border-0 d-flex align-items-center gap-3">
    <i class="bi bi-check-circle-fill fs-4"></i>
    <div>
        <div class="fw-black small">PROCESS COMPLETE</div>
        <div class="small opacity-75"><?= $generated_count ?> IDs generated/verified for Roll <?= $from ?> - <?= $to ?>.</div>
    </div>
</div>