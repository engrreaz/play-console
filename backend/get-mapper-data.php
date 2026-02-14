<?php
include '../inc.light.php'; // পাথ ঠিক আছে কিনা নিশ্চিত করুন

// ১. রোল এবং মডিউল লিস্ট লোড
$roles = [];
$r = $conn->query("SELECT userlevel FROM rolemanager WHERE sccode='0' ORDER BY id");
while ($row = $r->fetch_assoc()) $roles[] = $row['userlevel'];

$modules = [];
$m = $conn->query("SELECT module_name FROM modulelist ORDER BY slno");
while ($row = $m->fetch_assoc()) $modules[] = $row['module_name'];

// ২. ফাইল স্ক্যান এবং ম্যাপ ডাটা
$files = glob("../*.php"); // আপনার রুট ডিরেক্টরি অনুযায়ী পাথ দিন
$exclude = ['inc.php', 'footer.php', 'header.php', 'db.php', 'config.php', 'permission-mapper.php'];

$mapped = [];
$q = $conn->query("SELECT * FROM permission_map_app WHERE sccode='0'");
while ($row = $q->fetch_assoc()) {
    $mapped[$row['page_name']][$row['userlevel']] = $row;
}

$final_data = [];
foreach ($files as $f) {
    $file = basename($f);
    if (in_array($file, $exclude)) continue;

    $data = $mapped[$file] ?? [];
    $is_un = empty($data);

    $perms = [];
    foreach ($roles as $role) {
        $perms[$role] = $data[$role]['permission'] ?? 0;
    }

    $final_data[] = [
        'file' => $file,
        'title' => reset($data)['page_title'] ?? '',
        'module' => reset($data)['module'] ?? '',
        'root' => reset($data)['root_page'] ?? 'index.php',
        'desc' => reset($data)['description'] ?? '',
        'unassigned' => $is_un,
        'perm' => $perms
    ];
}

// JSON হিসেবে ডাটা পাঠানো
header('Content-Type: application/json');
echo json_encode([
    'roles' => $roles,
    'modules' => $modules,
    'files' => $final_data
]);