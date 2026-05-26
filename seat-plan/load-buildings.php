<?php

include '../inc.light.php';

$q = mysqli_query($conn,"
    SELECT *
    FROM seat_buildings
    ORDER BY id DESC
");

while($row = mysqli_fetch_assoc($q)){

    ?>

    <option value="<?= $row['id'] ?>">
        <?= $row['building_name'] ?>
    </option>

    <?php
}
?>