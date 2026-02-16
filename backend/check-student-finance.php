<?php
date_default_timezone_set('Asia/Dhaka');
include('../inc.light.php'); // আপনার পাথ অনুযায়ী inc.back.php বা inc.light.php ব্যবহার করুন

// ১. ইনপুট রিসিভ করা
$stidg = $_POST['stid'] ?? '';
$typeg = $_POST['type'] ?? ''; // stid, item etc.
$current_session = $sy; 
$stime = date("Y-m-d H:i:s");
$new = $update = $noneed = 0;

if ($stidg == '') die("Student ID Missing");

// ২. শিক্ষার্থীর সেশন তথ্য (classname, sectionname, rate) সংগ্রহ
$stmt_s = $conn->prepare("SELECT stid, sessionyear, classname, sectionname, rollno, rate FROM sessioninfo WHERE sccode=? AND sessionyear LIKE ? AND stid=? AND validate>=0 LIMIT 1");
$sy_param = "%$current_session%";
$stmt_s->bind_param("iss", $sccode, $sy_param, $stidg);
$stmt_s->execute();
$sinfo = $stmt_s->get_result()->fetch_assoc();

if (!$sinfo) die("Session record not found.");

$cls  = $sinfo['classname'];
$sec  = $sinfo['sectionname'];
$roll = $sinfo['rollno'];
$rate = floatval($sinfo['rate']); // Tuition Fee ডিসকাউন্ট রেট

// ৩. ডাটা রিসেট (পুরাতন অবৈধ্য ডাটা মার্ক করা)
// এখানে শুধু সেই রেকর্ডগুলো validate=0 করা হচ্ছে যেগুলো এখনো পেইড হয়নি
$stmt_reset = $conn->prepare("UPDATE stfinance SET validate = 0 WHERE stid=? AND sccode=? AND sessionyear LIKE ? AND paid = 0");
$stmt_reset->bind_param("sis", $stidg, $sccode, $sy_param);
$stmt_reset->execute();

// ৪. মাস্টার সেটআপ ডাটা সংগ্রহ (financesetup, financesetupvalue, financesetupind)
// সবগুলোকে অ্যারেতে নিয়ে নিচ্ছি যাতে লুপের ভেতর বারবার কুয়েরি করতে না হয় (Performance Optimization)

// ৪.১ financesetup (আইটেম লিস্ট)
$fin_items = [];
$res_fin = $conn->query("SELECT * FROM financesetup WHERE sccode='$sccode' AND sessionyear LIKE '$sy_param' ORDER BY slno ASC");
while ($r = $res_fin->fetch_assoc()) $fin_items[] = $r;

// ৪.২ financesetupvalue (মাস্টার ক্লাস ভ্যালু)
$master_values = [];
$res_val = $conn->query("SELECT itemcode, amount FROM financesetupvalue WHERE sccode='$sccode' AND sessionyear LIKE '$sy_param' AND classname='$cls'");
while ($r = $res_val->fetch_assoc()) $master_values[$r['itemcode']] = $r['amount'];

// ৪.৩ financesetupind (ব্যক্তিগত ওভাররাইড ভ্যালু)
$ind_values = [];
$res_ind = $conn->query("SELECT itemcode, amount FROM financesetupind WHERE sccode='$sccode' AND sessionyear LIKE '$sy_param' AND stid='$stidg'");
while ($r = $res_ind->fetch_assoc()) $ind_values[$r['itemcode']] = $r['amount'];


/* ---------------------------------------------------------
   ৫. ম্যাপিং লজিক শুরু (আইটেম এবং মাস অনুযায়ী লুপ)
--------------------------------------------------------- */


foreach ($fin_items as $item) {
    $itemcode = $item['itemcode'];
    $partid   = $item['id'];
    $month_cfg = intval($item['month']);
    $parte    = $item['particulareng'];
    $partb    = $item['particularben'];

    // সঠিক অ্যামাউন্ট নির্ধারণ (Individual > Master > 0)
    $base_amt = $ind_values[$itemcode] ?? ($master_values[$itemcode] ?? 0);
    
    // টিউশন ফি হলে ডিসকাউন্ট রেট অ্যাপ্লাই করা
    $final_amt = (str_contains(strtolower($parte), 'tution')) ? ($base_amt * $rate / 100) : $base_amt;

    // মাসের তালিকা তৈরি (আপনার দ্বিতীয় স্ক্রিপ্টের লজিক অনুযায়ী)
    $months_to_process = [];
    if ($month_cfg == 0) {
        $months_to_process = range(1, 12); // প্রতি মাসে
    } elseif ($month_cfg >= 1 && $month_cfg <= 12) {
        $months_to_process = [$month_cfg]; // নির্দিষ্ট মাসে
    } else {
        // পিরিওডিক লজিক (যেমন: ২২/১১ = ২ মাস পর পর)
        $lstep = floor($month_cfg / 11);
        if ($lstep < 1) $lstep = 1;
        for ($m = $lstep; $m <= 12; $m += $lstep) {
            $months_to_process[] = $m;
        }
    }

    foreach ($months_to_process as $m_num) {
        // নামের সাথে মাস যোগ করা (যদি মান্থলি বা পিরিওডিক হয়)
        $month_label = date('F/Y', strtotime("$current_session-$m_num-01"));
        $display_eng = ($month_cfg == 0 || $month_cfg > 12) ? "$parte : $month_label" : $parte;
        $display_ben = ($month_cfg == 0 || $month_cfg > 12) ? "$partb : $month_label" : $partb;

        // stfinance এ ডাটা চেক করা
        $check_q = $conn->prepare("SELECT id, paid FROM stfinance WHERE sccode=? AND stid=? AND itemcode=? AND month=? AND sessionyear LIKE ? LIMIT 1");
        $check_q->bind_param("isiss", $sccode, $stidg, $itemcode, $m_num, $sy_param);
        $check_q->execute();
        $f_data = $check_q->get_result()->fetch_assoc();

        if ($f_data) {
            if ($f_data['paid'] == 0) {
                // আপডেট (টাকা জমা না হয়ে থাকলে নতুন রুল অনুযায়ী আপডেট হবে)
                $upd = $conn->prepare("UPDATE stfinance SET amount=?, dues=?, particulareng=?, particularben=?, validate=1, partid=? WHERE id=?");
                $upd->bind_param("ddssii", $final_amt, $final_amt, $display_eng, $display_ben, $partid, $f_data['id']);
                $upd->execute();
                $update++;
            } else {
                // টাকা পেইড থাকলে শুধু ভ্যালিডেশন অন করে দাও যাতে মুছে না যায়
                $conn->query("UPDATE stfinance SET validate=1 WHERE id=" . $f_data['id']);
                $noneed++;
            }
        } else {
            // নতুন রেকর্ড ইনসার্ট
            $ins = $conn->prepare("INSERT INTO stfinance (sccode, sessionyear, stid, classname, sectionname, partid, itemcode, particulareng, particularben, amount, dues, month, validate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
            $ins->bind_param("issssisssddi", $sccode, $current_session, $stidg, $cls, $sec, $partid, $itemcode, $display_eng, $display_ben, $final_amt, $final_amt, $m_num);
            $ins->execute();
            $new++;
        }
    }
}

// ৬. ক্লিনআপ: যেসব আইটেম মাস্টার থেকে বাদ গেছে (validate=0) সেগুলো ডিজেবল/আর্কাইভ করা
$sccodes_archived = $sccode * 10;
$stmt_clean = $conn->prepare("UPDATE stfinance SET sccode=?, deleteby='Sync', deletetime=? WHERE stid=? AND sccode=? AND validate=0 AND paid=0");
$stmt_clean->bind_param("isis", $sccodes_archived, $stime, $stidg, $sccode);
$stmt_clean->execute();

// ৭. সেশন ভ্যালিডেশন আপডেট
$conn->query("UPDATE sessioninfo SET validate=1, validationtime='$stime' WHERE stid='$stidg' AND sccode='$sccode'");

// ৮. আউটপুট (Front-end Stat Box)
$time_elapsed = strtotime(date("Y-m-d H:i:s")) - strtotime($stime);
?>

<div class="m3-sync-status p-3" style="border-radius: 12px; background: #f8f9fa; border: 1px solid #e0e0e0;">
    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
        <b style="color: var(--m3-primary);">SYNC COMPLETED</b>
        <span class="badge bg-dark"><?= $time_elapsed ?>s</span>
    </div>
    <div style="font-size: 13px; line-height: 1.6;">
        <b>Student:</b> <?= $stidg ?> | <b>Class:</b> <?= strtoupper($cls) ?><br>
        <span class="text-success">● New Items: <?= $new ?></span><br>
        <span class="text-primary">● Updated: <?= $update ?></span><br>
        <span class="text-muted">● No Change: <?= $noneed ?></span>
    </div>
</div>
<div id="totaltotal" hidden>1</div>