<?php
include '../inc.light.php';

if(!isset($_POST['page_name'])){
    echo json_encode(['ok'=>false]);
    exit;
}

$page  = $_POST['page_name'];
$title = $_POST['page_title'];
$desc  = $_POST['description'];
$module= $_POST['module'];
$root  = $_POST['root_page'];
$perm  = $_POST['perm'];

$sccode = '0';

/*
    পুরোনো permission delete
*/
$stmt = $conn->prepare("DELETE FROM permission_map_app WHERE page_name=? AND sccode=?");
$stmt->bind_param("ss",$page,$sccode);
$stmt->execute();

/*
    নতুন insert
*/
$stmt = $conn->prepare("
INSERT INTO permission_map_app
(page_name,userlevel,permission,page_title,module,root_page,description,sccode)
VALUES (?,?,?,?,?,?,?,?)
");

foreach($perm as $role=>$val){

    $val = intval($val);

    $stmt->bind_param(
        "ssisssss",
        $page,
        $role,
        $val,
        $title,
        $module,
        $root,
        $desc,
        $sccode
    );

    $stmt->execute();
}

echo json_encode(['ok'=>true]);
