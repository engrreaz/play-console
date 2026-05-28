<?php
include '../inc.light.php';



$examtitle = $_POST['examtitle'];
$slot = $_POST['slot'];

// Safety check
if (!$examtitle || !$slot) {
    echo "invalid";
    exit;
}

// 1. Get matching seat plan ids
$get = mysqli_query($conn, "
    SELECT id 
    FROM seat_plans 
    WHERE examtitle='$examtitle'
    AND slot='$slot'
    AND sccode='$sccode'
    AND sessionyear='$sessionyear'
");

$ids = [];

while ($row = mysqli_fetch_assoc($get)) {
    $ids[] = $row['id'];
}

if (count($ids) == 0) {
    echo "not_found";
    exit;
}

$idList = implode(",", $ids);

// 2. Delete allocations
mysqli_query($conn, "
    DELETE FROM seat_plan_allocations 
    WHERE plan_id IN ($idList)
");

// 3. Delete invigilator data
mysqli_query($conn, "
    DELETE FROM invigilators 
    WHERE sessionyear='$sessionyear'
    AND examname='$examtitle'
    AND sccode='$sccode'
");

// 4. Delete seat plans
mysqli_query($conn, "
    DELETE FROM seat_plans 
    WHERE sessionyear='$sessionyear' AND examtitle='$examtitle' AND sccode='$sccode' AND slot='$slot'
");

echo "success";
?>