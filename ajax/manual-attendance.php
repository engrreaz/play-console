<?php
include '../inc.light.php';

$tid = $_POST['tid'];
$user = $usr;

$chk = $conn->prepare("
    SELECT manual_st FROM tattnd_manager 
    WHERE tid=? AND sccode=? order by id desc LIMIT 1
");
$chk->bind_param("ii",$tid,$sccode);
$chk->execute();
$st = $chk->get_result()->fetch_assoc()['manual_st'];

if(!$st){
    echo "disabled";
    exit;
}

$today = date('Y-m-d');
$now = date('H:i:s');

$conn->query("
INSERT INTO teacherattnd
(tid,adate,realin,detectin,entryby,sccode,st)
VALUES
('$tid','$today','$now','Manual','$user','$sccode','IN')
");

echo "ok";
