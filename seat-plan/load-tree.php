<?php
include '../inc.light.php';
?>

<ul class="tree-root">

    <?php
    $total_capacity = 0;

    $buildings = mysqli_query($conn, "
SELECT * FROM seat_buildings ORDER BY building_name
");

    while ($building = mysqli_fetch_assoc($buildings)) {
        ?>

        <li>

            <div class="tree-node node-building">

                <div class="node-header">

                    <div class="node-left">

                        <i class="bi bi-building"></i>

                        <div>
                            <div class="node-title">
                                <?= htmlspecialchars($building['building_name']) ?>
                            </div>
                            <div class="node-sub">Building</div>
                        </div>

                    </div>

                    <!-- MENU -->
                    <div class="tree-actions">

                        <button class="tree-menu-btn" onclick="event.stopPropagation(); toggleMenu('b<?= $building['id'] ?>')">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <div class="tree-menu" id="menu_b<?= $building['id'] ?>">

                            <button onclick="openFloorModal('<?= $building['id'] ?>')">+ Floor</button>

                            <button onclick="openBuildingModal(
                        '<?= $building['id'] ?>',
                        '<?= htmlspecialchars($building['building_name'], ENT_QUOTES) ?>'
                    )">Edit</button>

                            <button onclick="deleteBuilding('<?= $building['id'] ?>')">Delete</button>

                        </div>

                    </div>

                </div>
            </div>

            <ul>

                <?php
                $floors = mysqli_query($conn, "
        SELECT * FROM seat_floors
        WHERE building_id='{$building['id']}'
        ORDER BY floor_no
    ");

                while ($floor = mysqli_fetch_assoc($floors)) {
                    ?>

                    <li>

                        <div class="tree-node">

                            <div class="node-header">

                                <div class="node-left">

                                    <i class="bi bi-layers"></i>

                                    <div>
                                        <div class="node-title">
                                            <?= htmlspecialchars($floor['floor_name']) ?>
                                        </div>
                                        <div class="node-sub">Floor <?= $floor['floor_no'] ?></div>
                                    </div>

                                </div>

                                <div class="tree-actions">

                                    <button class="tree-menu-btn" onclick="event.stopPropagation(); toggleMenu('f<?= $floor['id'] ?>')">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <div class="tree-menu" id="menu_f<?= $floor['id'] ?>">

                                        <button onclick="openRoomModal('<?= $floor['id'] ?>')">+ Room</button>

                                        <button onclick="openFloorModal(
                            '<?= $building['id'] ?>',
                            '<?= $floor['id'] ?>',
                            '<?= htmlspecialchars($floor['floor_name'], ENT_QUOTES) ?>',
                            '<?= $floor['floor_no'] ?>'
                        )">Edit</button>

                                        <button onclick="deleteFloor('<?= $floor['id'] ?>')">Delete</button>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <ul>

                            <?php
                            $rooms = mysqli_query($conn, "
            SELECT sr.*,
            COALESCE(SUM(CASE WHEN srb.is_blocked=0 THEN srb.capacity ELSE 0 END),0) AS total_capacity
            FROM seat_rooms sr
            LEFT JOIN seat_room_benches srb ON sr.id=srb.room_id
            WHERE sr.floor_id='{$floor['id']}'
            GROUP BY sr.id
        ");

                            while ($room = mysqli_fetch_assoc($rooms)) {
                                $total_capacity += $room['total_capacity'];
                                ?>

                                <li>

                                    <div class="tree-node node-room room-active" onclick="loadBenchMap('<?= $room['id'] ?>')">

                                        <div class="node-header">

                                            <div class="node-left">

                                                <i class="bi bi-door-closed"></i>

                                                <div>
                                                    <div class="node-title">
                                                        <?= htmlspecialchars($room['room_name']) ?>
                                                    </div>
                                                    <div class="node-sub">
                                                        <?= $room['total_rows'] ?> × <?= $room['total_cols'] ?>
                                                        • <?= $room['total_capacity'] ?>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tree-actions">

                                                <button class="tree-menu-btn" onclick="event.stopPropagation(); toggleMenu('r<?= $room['id'] ?>')">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <div class="tree-menu" id="menu_r<?= $room['id'] ?>">

                                                    <button onclick="event.stopPropagation(); openRoomModal(
                                '<?= $floor['id'] ?>',
                                '<?= $room['id'] ?>',
                                '<?= htmlspecialchars($room['room_name'], ENT_QUOTES) ?>',
                                '<?= $room['total_rows'] ?>',
                                '<?= $room['total_cols'] ?>'
                            )">Edit</button>

                                                    <button
                                                        onclick="event.stopPropagation(); deleteRoom('<?= $room['id'] ?>')">Delete</button>

                                                </div>

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

<div id="totalCapacity" hidden><?= $total_capacity ?></div>