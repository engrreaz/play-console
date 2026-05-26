<?php

include '../inc.light.php';

$floor_id = $_POST['floor_id'];

?>

<option value="">Select Room</option>

<?php

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_rooms
    WHERE floor_id='$floor_id'
");

while($row = mysqli_fetch_assoc($q)){
?>

<option value="<?= $row['id'] ?>">
    <?= $row['room_name'] ?>
</option>

<?php } ?>