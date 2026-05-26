<?php
include '../inc.light.php';

$class_name = $_POST['class_name'] ?? '';

$options = '<option value="">-- Select Section --</option>';

if ($class_name != '') {
    $stmt = $conn->prepare("SELECT DISTINCT subarea FROM areas WHERE sccode = ? AND areaname = ?");
    $stmt->bind_param("ss", $sccode, $class_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $sec = htmlspecialchars($row['subarea']);
        $options .= "<option value=\"$sec\">$sec</option>";
    }
}

echo $options;
?>