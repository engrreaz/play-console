<?php

include '../inc.light.php';

$building_id = $_POST['building_id'];

?>

<option value="">Select Floor</option>

<?php

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_floors
    WHERE building_id='$building_id'
");

while($row = mysqli_fetch_assoc($q)){
?>

<option value="<?= $row['id'] ?>">
    <?= $row['floor_name'] ?>
</option>

<?php } ?>