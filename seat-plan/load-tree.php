<?php

include '../inc.light.php';

?>

<ul class="tree-root">

<?php

$total_capacity = 0;

$buildings = mysqli_query($conn,"
    SELECT *
    FROM seat_buildings
    ORDER BY building_name
");

while($building = mysqli_fetch_assoc($buildings)){

?>

    <li class="tree-item">

        <!-- BUILDING -->

         <div class="tree-node tree-toggle">

            <div class="node-header">

                <div class="node-left tree-toggle">

                    <span class="toggle-btn">

                        <i class="bi bi-chevron-down toggle-icon"></i>

                    </span>

                    <div class="node-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <div>

                        <div class="node-title">
                            <?= htmlspecialchars($building['building_name']) ?>
                        </div>

                        <div class="node-sub">
                            Building
                        </div>

                    </div>

                </div>

                <div class="node-actions">

                    <button
                        class="icon-btn edit-btn"
                        onclick="event.stopPropagation(); openFloorModal('<?= $building['id'] ?>')">

                        <i class="bi bi-plus"></i>

                    </button>

                    <button
                        class="icon-btn edit-btn"
                        onclick="event.stopPropagation(); openBuildingModal(
                            '<?= $building['id'] ?>',
                            '<?= htmlspecialchars($building['building_name'],ENT_QUOTES) ?>'
                        )">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        class="icon-btn delete-btn"
                        onclick="event.stopPropagation(); deleteBuilding('<?= $building['id'] ?>')">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

        </div>

        <!-- FLOORS -->

        <ul class="tree-children show">

        <?php

        $floors = mysqli_query($conn,"
            SELECT *
            FROM seat_floors
            WHERE building_id='{$building['id']}'
            ORDER BY floor_no
        ");

        while($floor = mysqli_fetch_assoc($floors)){

        ?>

            <li class="tree-item">

                <div class="tree-node">

                    <div class="node-header">

                        <div class="node-left tree-toggle">

                            <span class="toggle-btn">

                                <i class="bi bi-chevron-down toggle-icon"></i>

                            </span>

                            <div class="node-icon">
                                <i class="bi bi-layers"></i>
                            </div>

                            <div>

                                <div class="node-title">
                                    <?= htmlspecialchars($floor['floor_name']) ?>
                                </div>

                                <div class="node-sub">
                                    Floor <?= $floor['floor_no'] ?>
                                </div>

                            </div>

                        </div>

                        <div class="node-actions">

                            <button
                                class="icon-btn edit-btn"
                                onclick="event.stopPropagation(); openRoomModal('<?= $floor['id'] ?>')">

                                <i class="bi bi-plus"></i>

                            </button>

                            <button
                                class="icon-btn edit-btn"
                                onclick="event.stopPropagation(); openFloorModal(
                                    '<?= $building['id'] ?>',
                                    '<?= $floor['id'] ?>',
                                    '<?= htmlspecialchars($floor['floor_name'],ENT_QUOTES) ?>',
                                    '<?= $floor['floor_no'] ?>'
                                )">

                                <i class="bi bi-pencil"></i>

                            </button>

                            <button
                                class="icon-btn delete-btn"
                                onclick="event.stopPropagation(); deleteFloor('<?= $floor['id'] ?>')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </div>

                </div>

                <!-- ROOMS -->

                <ul class="tree-children show">

                <?php

                $rooms = mysqli_query($conn,"
                    SELECT
                        sr.*,

                        COALESCE(SUM(
                            CASE
                                WHEN srb.is_blocked=0
                                THEN srb.capacity
                                ELSE 0
                            END
                        ),0) AS total_capacity

                    FROM seat_rooms sr

                    LEFT JOIN seat_room_benches srb
                        ON sr.id = srb.room_id

                    WHERE sr.floor_id='{$floor['id']}'

                    GROUP BY sr.id

                    ORDER BY sr.room_name
                ");

                while($room = mysqli_fetch_assoc($rooms)){

                    $total_capacity += (int)$room['total_capacity'];

                ?>

                    <li>

                        <div
                            class="tree-node room-active"
                            onclick="loadBenchMap('<?= $room['id'] ?>')">

                            <div class="node-header">

                                <div class="node-left">

                                    <div class="node-icon">
                                        <i class="bi bi-door-closed"></i>
                                    </div>

                                    <div>

                                        <div class="node-title">
                                            <?= htmlspecialchars($room['room_name']) ?>
                                        </div>

                                        <div class="node-sub">

                                            <?= $room['total_rows'] ?>
                                            ×
                                            <?= $room['total_cols'] ?>

                                            &bull;

                                            <strong>

                                                <i class="bi bi-person-fill"></i>

                                                <?= $room['total_capacity'] ?>

                                            </strong>

                                        </div>

                                    </div>

                                </div>

                                <div class="node-actions">

                                    <button
                                        class="icon-btn edit-btn"

                                        onclick="event.stopPropagation();

                                        openRoomModal(
                                            '<?= $floor['id'] ?>',
                                            '<?= $room['id'] ?>',
                                            '<?= htmlspecialchars($room['room_name'],ENT_QUOTES) ?>',
                                            '<?= $room['total_rows'] ?>',
                                            '<?= $room['total_cols'] ?>'
                                        )">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                    <button
                                        class="icon-btn delete-btn"

                                        onclick="event.stopPropagation();

                                        deleteRoom('<?= $room['id'] ?>')">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </li>

                <?php } ?>

                </ul>

            </li>

        <?php } ?>

        </ul>

    </li>

<?php } ?>

</ul>

<div id="totalCapacity" hidden>
    <?= $total_capacity ?>
</div>