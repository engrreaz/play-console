<?php 
include '../inc.light.php';


$id = intval($_GET['load_perm']);
$q = $conn->query("SELECT * FROM hub_module_permissions WHERE module_id=$id");

$roles = [];
$sccode = 0;

while ($r = $q->fetch_assoc()) {
    $roles[] = $r['role'];
    $sccode = $r['sccode'];
}

header('Content-Type: application/json');
echo json_encode(['roles'=>$roles,'sccode'=>$sccode]);
exit;
