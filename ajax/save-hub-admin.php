<?php
include '../inc.light.php';

$module_id = intval($_POST['module_id']);
$sccode = intval($_POST['sccode'] ?? 0);
$roles = $_POST['role'] ?? []; // সরাসরি array
// var_dump($roles);
$roles = array_unique($roles); // duplicate remove
// var_dump($roles);
// exit;
// পুরোনো permission ডিলিট
$conn->query("DELETE FROM hub_module_permissions WHERE module_id=$module_id");



if (!empty($roles)) {
    foreach ($roles as $r) {
        $role = $conn->real_escape_string($r);
        $conn->query("INSERT INTO hub_module_permissions (module_id, role, sccode) VALUES ($module_id, '$role', $sccode)");
    }
}

echo "ok"; // JS success handle
exit;