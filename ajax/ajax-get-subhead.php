<?php
include '../inc.light.php'; // conn ready

$type = $_POST['type'] ?? '';

$cond = "";

if ($type == 'Income') {
    $cond = "AND income = 1";
} elseif ($type == 'Expenditure') {
    $cond = "AND expenditure = 1";
}

$sql = "SELECT s.id, s.sub_head, h.account_head, s.account_head_id
        FROM account_sub_head s
        LEFT JOIN account_head h ON s.account_head_id = h.id
        WHERE s.sccode='$sccode' $cond
        ORDER BY h.account_head, s.sub_head";

$res = $conn->query($sql);

echo '<option value="">Select Account Sector</option>';

$current_head = '';

while ($row = $res->fetch_assoc()) {

    // নতুন group শুরু
    if ($current_head != $row['account_head']) {

        // আগের group close
        if ($current_head != '') {
            echo '</optgroup>';
        }

        echo '<optgroup class="text-info" label="'.$row['account_head'].'">';

        $current_head = $row['account_head'];
    }

    // option
    echo '<option class="text-dark" value="'.$row['id'].'" data-head="'.$row['account_head_id'].'">';
    echo $row['sub_head'];
    echo '</option>';
}

// শেষ group close
if ($current_head != '') {
    echo '</optgroup>';
}
