<?php
include('../inc.light.php');

// ১. ইনপুট রিসিভ করা
$stid   = $_POST['stid'] ?? '';
$item   = $_POST['item'] ?? '';

$amt    = isset($_POST['amt']) ? floatval($_POST['amt']) : 0;
$slot   = $_POST['slot'] ?? 'School';
$cls    = $_POST['cls'] ?? '';
$sec    = $_POST['sec'] ?? '';
$indid  = intval($_POST['indid'] ?? 0);



if ($stid == '' || $item == '') {
    die("Invalid Student or Item");
}

/* লজিক: 
   ১. প্রথমে 'financesetupvalue' থেকে ওই ক্লাসের ডিফল্ট (Master) অ্যামাউন্ট কত তা জেনে নেব।
   ২. যদি ইউজারের দেওয়া $amt এবং মাস্টার অ্যামাউন্ট সমান হয়, তবে 'financesetupind' থেকে রেকর্ডটি মুছে দেব।
   ৩. যদি $amt এবং মাস্টার অ্যামাউন্ট আলাদা হয়, তবে আপডেট বা ইনসার্ট করব।
*/

// ২. মাস্টার অ্যামাউন্ট ফেচ করা
$master_amt = -1;
$q_master = $conn->prepare("SELECT amount FROM financesetupvalue WHERE sccode=? AND sessionyear LIKE? AND itemcode=? AND classname=? LIMIT 1");
$q_master->bind_param("isss", $sccode, $sessionyear_param, $item, $cls);
$q_master->execute();
$res_master = $q_master->get_result();
if($row_m = $res_master->fetch_assoc()){
    $master_amt = floatval($row_m['amount']);
}
$q_master->close();

// ৩. ডাটা প্রসেসিং (Insert/Update/Delete)
if ($amt == $master_amt) {
    // যদি ভ্যালু মাস্টারের সমান হয়, তবে আলাদা রেকর্ডের দরকার নেই (Delete)
    $stmt = $conn->prepare("DELETE FROM financesetupind WHERE sccode=? AND stid=? AND itemcode=? AND sessionyear LIKE ?");
    $stmt->bind_param("isss", $sccode, $stid, $item, $sessionyear_param);
    $stmt->execute();
    $action = "deleted";
} else {
    // মাস্টার থেকে আলাদা হলে রেকর্ড থাকবে। প্রথমে চেক করি আগে থেকে আছে কি না।
    $check = $conn->prepare("SELECT id FROM financesetupind WHERE sccode=? AND stid=? AND itemcode=? AND sessionyear LIKE ? LIMIT 1");
    $check->bind_param("isss", $sccode, $stid, $item, $sessionyear_param);
    $check->execute();
    $exist_id = $check->get_result()->fetch_assoc()['id'] ?? 0;
    $check->close();

    if ($exist_id > 0) {
        // আপডেট
        $stmt = $conn->prepare("UPDATE financesetupind SET amount=?, update_time=?, classname=?, sectionname=? WHERE id=?");
        $stmt->bind_param("dsssi", $amt, $cur, $cls, $sec, $exist_id);
        $action = "updated";
    } else {
        // নতুন ইনসার্ট
        $stmt = $conn->prepare("INSERT INTO financesetupind (sccode, slot, sessionyear, stid, itemcode, classname, sectionname, amount, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $sccode, $slot, $sessionyear, $stid, $item, $cls, $sec, $amt, $cur);
        $action = "inserted";
    }
    $stmt->execute();
}

// ৪. রেসপন্স আইকন পাঠানো (M3 স্টাইলে)
?>
<i class="bi bi-cloud-check-fill text-success fs-5"></i>
<script>
    // গুরুত্বপূর্ণ: যদি নতুন ইনসার্ট হয়, তবে পরবর্তী এডিটের জন্য indid আপডেট করা দরকার হতে পারে।
    // তবে আমরা যেহেতু stid + itemcode দিয়ে চেক করছি, তাই indid ছাড়াও কাজ করবে।
</script>