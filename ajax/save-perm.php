<?php
include '../inc.light.php';

$tid=$_POST['tid'];

$stmt=$conn->prepare("
REPLACE INTO tattnd_manager
(tid,sccode,gps_st,bio_st,card_st,manual_st)
VALUES(?,?,?,?,?,?)
");

$stmt->bind_param(
 "iiiiii",
 $tid,$sccode,
 $_POST['gps_st'],
 $_POST['bio_st'],
 $_POST['card_st'],
 $_POST['manual_st']
);

$stmt->execute();
echo "ok";
