<?php
include '../inc.light.php';


$cls=$_POST['cls'] ?? '';
$sec=$_POST['sec'] ?? '';
$day=$_POST['day'] ?? 'today';

if($day=='today'){
$day=date('l');
}

$q=mysqli_query($conn,"
SELECT * FROM clsroutine
WHERE classname='$cls'
AND sectionname='$sec'
AND day='$day'
ORDER BY period
");

while($r=mysqli_fetch_assoc($q)){
echo "
<div class='routine-card'>
<b>Period {$r['period']}</b>
<br>
{$r['subcode']}
<br>
{$r['tid']}
</div>
";
}
?>
