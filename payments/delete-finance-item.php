<?php
include '../inc.light.php'; // DB কানেকশন এবং $sccode এখানে আছে

if (isset($_POST['id']) && intval($_POST['id']) > 0) {
    $id = intval($_POST['id']);

    // ১. প্রথমে আইটেম কোডটি খুঁজে বের করা (যাতে অন্য টেবিলের ডাটা ডিলিট করা যায়)
    $stmt_get = $conn->prepare("SELECT itemcode FROM financesetup WHERE id = ? AND sccode = ?");
    $stmt_get->bind_param("ii", $id, $sccode);
    $stmt_get->execute();
    $res = $stmt_get->get_result();

    if ($res->num_rows > 0) {
        $itemcode = $res->fetch_assoc()['itemcode'];

        // ২. মূল আইটেম ডিলিট করা (financesetup থেকে)
        $stmt_del1 = $conn->prepare("DELETE FROM financesetup WHERE id = ? AND sccode = ?");
        $stmt_del1->bind_param("ii", $id, $sccode);
        $stmt_del1->execute();

        // ৩. আইটেমের সাথে যুক্ত সব অ্যামাউন্ট ডিলিট করা (financesetupvalue থেকে)
        // এটি অত্যন্ত গুরুত্বপূর্ণ যাতে ডাটাবেসে অপ্রয়োজনীয় ডাটা জমা না থাকে
        $stmt_del2 = $conn->prepare("DELETE FROM financesetupvalue WHERE itemcode = ? AND sccode = ?");
        $stmt_del2->bind_param("si", $itemcode, $sccode);
        $stmt_del2->execute();

        echo "success";
    } else {
        echo "Item not found or unauthorized access.";
    }
} else {
    echo "Invalid Request.";
}