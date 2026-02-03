<?php 
include '../inc.light.php';

if($_POST['id']==''){

mysqli_query($conn,"
INSERT INTO task_manager
(platform,module,panel,root_topic,
sub_level_1,sub_level_2,sub_level_3,notes,status,submit_time)
VALUES(
'$_POST[platform]','$_POST[module]','$_POST[panel]',
'$_POST[root_topic]','$_POST[sub_level_1]',
'$_POST[sub_level_2]','$_POST[sub_level_3]',
'$_POST[notes]','$_POST[status]',NOW())
");

}else{

mysqli_query($conn,"
UPDATE task_manager SET
platform='$_POST[platform]',
module='$_POST[module]',
panel='$_POST[panel]',
root_topic='$_POST[root_topic]',
sub_level_1='$_POST[sub_level_1]',
sub_level_2='$_POST[sub_level_2]',
sub_level_3='$_POST[sub_level_3]',
notes='$_POST[notes]',
status='$_POST[status]'
WHERE id='$_POST[id]'
");

}
