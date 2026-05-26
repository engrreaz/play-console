<?php
$page_title = "Exam Hall Map";
include 'inc.php';
?>


<style>
    :root {
        /* M3 Tonal Palette */
        --m3-primary: #006494;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #CBE6FF;
        --m3-on-primary-container: #001E30;

        --m3-secondary: #50606E;
        --m3-on-secondary: #FFFFFF;
        --m3-secondary-container: #D3E4F5;
        --m3-on-secondary-container: #0C1D29;

        --m3-tertiary: #65587B;
        --m3-on-tertiary: #FFFFFF;
        --m3-tertiary-container: #EBE0FF;
        --m3-on-tertiary-container: #201634;

        --m3-error: #BA1A1A;
        --m3-on-error: #FFFFFF;
        --m3-error-container: #FFDAD6;
        --m3-on-error-container: #410002;

        --m3-success: #146C2E;
        --m3-on-success: #FFFFFF;
        --m3-success-container: #9FF5A9;
        --m3-on-success-container: #002107;

        --m3-surface: #FCFCFF;
        --m3-on-surface: #1A1C1E;
        --m3-surface-variant: #DDE3EA;
        --m3-on-surface-variant: #41474D;
        --m3-surface-container-low: #F0F4F8;
        --m3-surface-container: #EAEFF4;
        --m3-surface-container-high: #E4E9EF;

        --m3-outline: #72787E;
        --m3-outline-variant: #C1C7CE;

        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 24px;
        --radius-full: 9999px;

        --elevation-1: 0px 1px 3px 1px rgba(0, 0, 0, 0.15), 0px 1px 2px 0px rgba(0, 0, 0, 0.30);
    }

    body {
        background-color: var(--m3-surface);
        color: var(--m3-on-surface);
        font-family: 'Roboto', 'Segoe UI', system-ui, sans-serif;
        line-height: 1.5;
    }

    .page-wrapper {
        display: grid;
        gap: 24px;
    }

    /* M3 Card (Elevated or Filled) */
    .m3-card {
        background-color: var(--m3-surface-container-low);
        color: var(--m3-on-surface);
        border-radius: var(--radius-xl);
        padding: 24px;
        box-shadow: none;
        /* Tonal typically uses container colors rather than heavy shadows */
        border: 1px solid var(--m3-surface-variant);
        transition: background-color 0.2s ease;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 500;
        letter-spacing: 0.1px;
        color: var(--m3-on-surface);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title .bi {
        color: var(--m3-primary);
        font-size: 28px;
    }

    /* M3 Text Field */
    .m3-input,
    .m3-select {
        width: 100%;
        background-color: var(--m3-surface-variant);
        color: var(--m3-on-surface);
        border: none;
        border-bottom: 1px solid var(--m3-outline);
        border-radius: var(--radius-sm);
        padding: 8px;
        font-size: 0.95rem;
        outline: none;
        margin-bottom: 8px;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }

    .m3-input:focus,
    .m3-select:focus {
        border-bottom: 2px solid var(--m3-primary);
        background-color: var(--m3-surface-container-high);
        padding-bottom: 11px;
        /* compensate for 2px border */
    }

    /* M3 Buttons - Tonal style */
    .m3-btn {
        border: none;
        border-radius: var(--radius-full);
        padding: 10px 24px;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.1px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        min-height: 40px;
    }

    /* Filled Tonal Button */
    .m3-btn-primary {
        background-color: var(--m3-secondary-container) !important;
        color: var(--m3-on-secondary-container) !important;
    }

    .m3-btn-primary:hover {
        background-color: #C3D5E7 !important;
        /* Slightly darker container */
        box-shadow: var(--elevation-1);
    }

    /* Error Tonal Button */
    .m3-btn-danger {
        background-color: var(--m3-error-container) !important;
        color: var(--m3-on-error-container) !important;
    }

    .m3-btn-danger:hover {
        background-color: #FFC9C4 !important;
        box-shadow: var(--elevation-1);
    }

    /* Success Tonal Button */
    .m3-btn-success {
        background-color: var(--m3-success-container) !important;
        color: var(--m3-on-success-container) !important;
    }

    .m3-btn-success:hover {
        background-color: #8DE698 !important;
        box-shadow: var(--elevation-1);
    }

    /* Tree structures */
    .tree {
        margin-top: 24px;
    }

    .tree ul {
        list-style: none;
        margin: 0;
        padding-left: 24px;
        position: relative;
    }

    

    .tree li {
        position: relative;
        margin-bottom: 16px;
    }

   

    .tree-node {
        background-color: var(--m3-surface);
        border: 1px solid var(--m3-outline-variant);
        border-radius: var(--radius-lg);
        padding: 12px 16px;
        transition: background-color 0.2s ease;
    }

    .tree-node:hover {
        background-color: var(--m3-surface-container);
    }

    .node-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .node-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .node-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .node-title {
        font-size: 1rem;
        font-weight: 500;
        color: var(--m3-on-surface);
    }

    .node-sub {
        font-size: 0.875rem;
        color: var(--m3-on-surface-variant);
        margin-top: 2px;
    }

    .node-actions {
        display: flex;
        gap: 8px;
    }

    /* Icon Buttons - Standard standard tonal */
    .icon-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: var(--radius-full);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .edit-btn {
        background-color: var(--m3-secondary-container);
        color: var(--m3-on-secondary-container);
    }

    .edit-btn:hover {
        background-color: #C3D5E7;
    }

    .delete-btn {
        background-color: var(--m3-error-container);
        color: var(--m3-on-error-container);
    }

    .delete-btn:hover {
        background-color: #FFC9C4;
    }

    .bench-map {
        margin-top: 24px;
        padding: 16px;
        background-color: var(--m3-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--m3-outline-variant);
        overflow-x: auto;
    }

    .bench-grid {
        display: grid;
        gap: 16px;
        justify-content: start;
    }

    /* Bench - Tonal representation */
    .bench {
        width: 80px;
        height: 64px;
        border-radius: var(--radius-md);
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .bench:hover {
        background-color: #B3D9FF;
        box-shadow: var(--elevation-1);
    }

    .bench.blocked {
        background-color: var(--m3-surface-variant);
        color: var(--m3-on-surface-variant);
        border-color: var(--m3-outline-variant);
    }

    .bench.blocked:hover {
        background-color: var(--m3-surface-container-high);
    }

    .bench small {
        position: absolute;
        bottom: 6px;
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .top-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    /* Input Group */
    .m3-input-group {
        margin-bottom: 16px;
    }

    .m3-input-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--m3-on-surface-variant);
        margin-bottom: 8px;
    }

    /* Modals override */
    .modal-content.m3-card {
        padding: 0;
        overflow: hidden;
    }

    .modal-header {
        padding: 24px 24px 16px;
        border-bottom: none;
    }

    .modal-title {
        font-size: 1.5rem;
        color: var(--m3-on-surface);
        font-weight: 400;
    }

    .modal-body {
        padding: 16px 24px 24px;
    }

    .btn-sm {
        border-radius: 50% !important;
    }

    .top-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .fab-btn {
        width: 52px;
        height: 52px;
        border: none;
        border-radius: 50px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        padding: 0 16px;
        cursor: pointer;
        transition: all .25s ease;
        white-space: nowrap;
        position: relative;
    }

    .fab-btn i {
        font-size: 20px;
        min-width: 20px;
    }

    .fab-btn .fab-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
        transition: all .25s ease;
    }

    .fab-btn.expanded {
        width: auto;
        padding-right: 18px;
    }

    .fab-btn.expanded .fab-text {
        opacity: 1;
        width: auto;
    }

    .fab-primary {
        background: var(--m3-secondary-container);
        color: var(--m3-on-secondary-container);
    }

    .fab-success {
        background: var(--m3-success-container);
        color: var(--m3-on-success-container);
    }



    .tree-toggle {
        cursor: pointer;
        user-select: none;
    }

    .tree-children {
        display: none;
    }

    .tree-children.show {
        display: block;
    }

    /* First level expanded by default */

    .tree>ul>li>.tree-children {
        display: block;
    }

    .toggle-icon {
        transition: .2s;
    }

    .toggle-icon.rotate {
        transform: rotate(90deg);
    }


    .tree-children {
        display: none;
    }

    .tree-children.show {
        display: block;
    }

    .tree-item.collapsed>.tree-node .toggle-icon {
        transform: rotate(-90deg);
        transition: 0.2s;
    }
</style>

<div class="page-wrapper row">

    <div class="col m3-card">

        <div class="section-title ms-3">
            <i class="bi bi-diagram-3-fill"></i>
            Hall Structure
        </div>

        <div class="top-toolbar ms-3">

            <button class="fab-btn fab-primary" ondblclick="toggleFab(this)" onclick="openBuildingModal()">

                <i class="bi bi-plus-lg"></i>

                <span class="fab-text">
                    Building
                </span>

            </button>


            <button class="fab-btn fab-success" ondblclick="toggleFab(this)" onclick="openSeatPlanModal()">

                <i class="bi bi-magic"></i>

                <span class="fab-text">
                    Generate Plan
                </span>

            </button>


            <button class="fab-btn fab-primary" ondblclick="toggleFab(this)" onclick="openViewMapModal()">

                <i class="bi bi-map-fill"></i>

                <span class="fab-text">
                    View Map
                </span>

            </button>
            <div class="flex-grow-1"></div>
            <div class="vr"></div>
            <div class="border-info me-1  ">
                <div class="text-center fs-6 text-success fw-bold" id="totalCapacityMain"></div>
                <div class="fs-tiny small text-muted">Capacity</div>
            </div>



        </div>



        <div class="tree" id="hall_tree">............</div>

    </div>








</div>


<div class="modal fade" id="benchMapModal">

    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content m3-card">

            <div class="modal-header">

                <h5 class="modal-title">
                    Bench Map
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="room_info"></div>

                <div class="bench-map" style="overflow:auto; max-height:75vh;">

                    <div class="bench-grid" id="bench_grid">
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- BUILDING MODAL -->

<div class="modal fade" id="buildingModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">

                <input type="hidden" id="building_id">

                <input type="text" id="building_name" class="m3-input" placeholder="Building Name">

                <button class="m3-btn m3-btn-primary w-100" onclick="saveBuilding()">
                    Save Building
                </button>

            </div>

        </div>
    </div>
</div>





<!-- FLOOR MODAL -->

<div class="modal fade" id="floorModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">

                <input type="hidden" id="floor_id">
                <input type="hidden" id="floor_building_id">

                <input type="text" id="floor_name" class="m3-input" placeholder="Floor Name">

                <input type="number" id="floor_no" class="m3-input" placeholder="Floor No">

                <button class="m3-btn m3-btn-primary w-100" onclick="saveFloor()">
                    Save Floor
                </button>

            </div>

        </div>
    </div>
</div>






<!-- ROOM MODAL -->

<div class="modal fade" id="roomModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">

                <input type="hidden" id="room_id">
                <input type="hidden" id="room_floor_id">

                <input type="text" id="room_name" class="m3-input" placeholder="Room Name">

                <input type="number" id="total_rows" class="m3-input" placeholder="Total Rows">

                <input type="number" id="total_cols" class="m3-input" placeholder="Total Columns">

                <button class="m3-btn m3-btn-primary w-100" onclick="saveRoom()">
                    Save Room
                </button>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="benchModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-card">

            <div class="modal-header">
                <h5 class="modal-title">Bench Settings</h5>
            </div>

            <div class="modal-body">

                <input type="hidden" id="bench_id">

                <div class="m3-input-group">
                    <label>Capacity</label>
                    <select id="capacity" class="m3-select">
                        <option value="1">1 Student</option>
                        <option value="2">2 Students</option>
                        <option value="3">3 Students</option>
                    </select>
                </div>

                <div class="m3-input-group">
                    <label>Status</label>
                    <select id="is_blocked" class="m3-select">
                        <option value="0">Active</option>
                        <option value="1">Blocked</option>
                    </select>
                </div>

                <div class="m3-input-group">
                    <label>Reason (if blocked)</label>
                    <input type="text" id="blocked_reason" class="m3-input" placeholder="Door / Pillar / Gap">
                </div>

                <div class="m3-input-group">
                    <label>Label (optional)</label>
                    <input type="text" id="bench_label" class="m3-input" placeholder="A1 / VIP / Teacher">
                </div>

                <button class="m3-btn m3-btn-primary w-100" onclick="saveBench()">
                    Save Bench
                </button>

            </div>

        </div>
    </div>
</div>



<!-- SEAT PLAN SETUP MODAL -->
<div class="modal fade" id="seatPlanSetupModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-card">

            <div class="modal-header">
                <h5 class="modal-title">Generate Seat Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="seatPlanModalBody">
                <!-- Loaded via AJAX -->
                <div class="text-center">Loading...</div>
            </div>

        </div>
    </div>
</div>

<!-- VIEW MAP MODAL -->
<div class="modal fade" id="viewMapModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content m3-card">

            <div class="modal-header">
                <h5 class="modal-title">View Exam Seat Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="m3-input-group mb-3">
                    <label>Select Exam</label>
                    <select id="view_map_exam" class="m3-select" onchange="loadAllocatedRooms()">
                        <option value="">-- Select Exam --</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4" id="allocated_rooms_list" style="max-height: 500px; overflow-y: auto;">
                        <!-- Loaded via AJAX -->
                    </div>
                    <div class="col-md-8" style="max-height: 500px; overflow-y: auto;">
                        <div id="allocated_room_info"></div>
                        <div class="bench-map">
                            <div class="bench-grid" id="allocated_bench_grid">xx</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>

    $(document).ready(function () {

        loadTree();

    });


    let pressTimer;

    $(document).on('touchstart mousedown', '.fab-btn', function () {

        let btn = this;

        pressTimer = setTimeout(function () {

            toggleFab(btn);

        }, 500);

    });

    $(document).on('touchend mouseup mouseleave', '.fab-btn', function () {

        clearTimeout(pressTimer);

    });


    function toggleFab(el) {

        $(".fab-btn").not(el).removeClass("expanded");

        $(el).toggleClass("expanded");

    }

    // ================= TREE LOAD =================

    function loadTree() {

        $.ajax({

            url: 'seat-plan/load-tree.php',
            type: 'GET',

            success: function (res) {

                $("#hall_tree").html(res);

                let total_capacity = parseInt($("#totalCapacity").text()) || 0;
                $('#totalCapacityMain').text(total_capacity).show();

            }

        });

    }



    // ================= BUILDING =================

    function openBuildingModal(id = '', name = '') {

        $("#building_id").val(id);
        $("#building_name").val(name);

        $("#buildingModal").modal('show');

    }



    function saveBuilding() {

        $.ajax({

            url: 'seat-plan/save-building.php',
            type: 'POST',

            data: {
                id: $("#building_id").val(),
                building_name: $("#building_name").val()
            },

            success: function (res) {

                $("#buildingModal").modal('hide');

                loadTree();

            }

        });

    }



    function deleteBuilding(id) {

        Swal.fire({
            title: 'Delete Building?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({

                    url: 'seat-plan/delete-building.php',
                    type: 'POST',

                    data: { id: id },

                    success: function () {

                        loadTree();
                        Swal.fire('Deleted!', 'Building has been deleted.', 'success');

                    }

                });

            }
        });

    }



    // ================= FLOOR =================

    function openFloorModal(building_id, id = '', name = '', floor_no = '') {

        $("#floor_building_id").val(building_id);
        $("#floor_id").val(id);
        $("#floor_name").val(name);
        $("#floor_no").val(floor_no);

        $("#floorModal").modal('show');

    }



    function saveFloor() {

        $.ajax({

            url: 'seat-plan/save-floor.php',
            type: 'POST',

            data: {
                id: $("#floor_id").val(),
                building_id: $("#floor_building_id").val(),
                floor_name: $("#floor_name").val(),
                floor_no: $("#floor_no").val()
            },

            success: function () {

                $("#floorModal").modal('hide');

                loadTree();

            }

        });

    }



    function deleteFloor(id) {

        Swal.fire({
            title: 'Delete Floor?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({

                    url: 'seat-plan/delete-floor.php',
                    type: 'POST',

                    data: { id: id },

                    success: function () {

                        loadTree();
                        Swal.fire('Deleted!', 'Floor has been deleted.', 'success');

                    }

                });

            }
        });

    }




    // ================= ROOM =================

    function openRoomModal(floor_id, id = '', name = '', rows = '', cols = '') {

        $("#room_floor_id").val(floor_id);
        $("#room_id").val(id);

        $("#room_name").val(name);
        $("#total_rows").val(rows);
        $("#total_cols").val(cols);

        $("#roomModal").modal('show');

    }



    function saveRoom() {

        $.ajax({

            url: 'seat-plan/save-room.php',
            type: 'POST',

            data: {
                id: $("#room_id").val(),
                floor_id: $("#room_floor_id").val(),
                room_name: $("#room_name").val(),
                total_rows: $("#total_rows").val(),
                total_cols: $("#total_cols").val(),
                default_capacity: $("#default_capacity").val()
            },

            success: function () {

                $("#roomModal").modal('hide');

                loadTree();

            }

        });

    }



    function deleteRoom(id) {

        Swal.fire({
            title: 'Delete Room?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({

                    url: 'seat-plan/delete-room.php',
                    type: 'POST',

                    data: { id: id },

                    success: function () {

                        loadTree();
                        Swal.fire('Deleted!', 'Room has been deleted.', 'success');

                    }

                });

            }
        });

    }




    // ================= BENCH MAP =================

    let current_room_id = null;

    function loadBenchMap(room_id) {

        current_room_id = room_id;

        $("#benchMapModal").modal('show');

        $.ajax({

            url: 'seat-plan/load-bench-map.php',
            type: 'POST',

            data: { room_id: room_id },
            dataType: 'json',

            success: function (res) {
                res.room_id = room_id; // Pass room_id to renderBenchMap
                renderBenchMap(res);
            }
        });
    }



    function renderBenchMap(data) {

        $("#room_info").html(`
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
            <div>
                <h4>${data.room_name}</h4>
                <p style="margin-bottom:0;">
                    ${data.total_rows} Rows ×
                    ${data.total_cols} Columns
                </p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <input type="number" id="bulk_capacity" class="m3-input" style="width: 70px; margin-bottom: 0; padding: 8px;" value="2" min="1">
                <button class="m3-btn m3-btn-primary" style="padding: 9px 15px;" onclick="setRoomCapacity(${data.room_id})">Set All</button>
            </div>
        </div>
    `);

        let grid = $("#bench_grid");

        grid.html('');

        grid.css({
            gridTemplateColumns: `repeat(${data.total_cols}, 80px)`
        });

        data.benches.forEach(function (bench) {

            let blocked = bench.is_blocked == 1 ? 'blocked' : '';

            let cap = parseInt(bench.capacity) || 0;
            let icons = '';
            if (cap > 0 && cap <= 4) {
                icons = '<i class="bi bi-person-fill"></i>'.repeat(cap);
            } else if (cap > 4) {
                icons = cap + '&times;<i class="bi bi-person-fill"></i>';
            } else {
                icons = '<i class="bi bi-dash"></i>';
            }

            let html = `
            <div class="bench ${blocked}" id="bench_${bench.id}"
                 onclick="handleBenchClick(${bench.id}, ${bench.is_blocked})">

                ${bench.row_no}-${bench.col_no}

                <small style="display:flex; gap:2px; font-size:12px; color:inherit; opacity:0.8;">${icons}</small>

            </div>
        `;

            grid.append(html);

        });

    }


    let benchClickTimer = null;

    function handleBenchClick(id, status) {
        if (benchClickTimer) {
            clearTimeout(benchClickTimer);
            benchClickTimer = null;
            openBenchModal(id);
        } else {
            benchClickTimer = setTimeout(function () {
                toggleBench(id, status);
                benchClickTimer = null;
            }, 250);
        }
    }

    function setRoomCapacity(room_id) {
        let cap = $("#bulk_capacity").val();
        if (confirm("Set capacity to " + cap + " for all benches in this room?")) {
            $.ajax({
                url: 'seat-plan/set-room-capacity.php',
                type: 'POST',
                data: { room_id: room_id, capacity: cap },
                success: function () {
                    loadBenchMap(room_id);
                }
            });
        }
    }

    function toggleBench(id, status) {

        $.ajax({

            url: 'seat-plan/toggle-bench.php',
            type: 'POST',

            data: {
                id: id,
                status: status
            },

            success: function () {

                if (current_room_id) loadBenchMap(current_room_id);

            }

        });

    }

</script>

<script>
    function openBenchModal(id) {

        $.ajax({
            url: 'seat-plan/load-bench.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (res) {

                $("#bench_id").val(res.id);
                $("#capacity").val(res.capacity);
                $("#is_blocked").val(res.is_blocked);
                $("#blocked_reason").val(res.blocked_reason);
                $("#bench_label").val(res.bench_label);

                $("#benchModal").modal('show');
            }
        });

    }


    function saveBench() {

        $.ajax({
            url: 'seat-plan/update-bench.php',
            type: 'POST',
            data: {
                id: $("#bench_id").val(),
                capacity: $("#capacity").val(),
                is_blocked: $("#is_blocked").val(),
                blocked_reason: $("#blocked_reason").val(),
                bench_label: $("#bench_label").val()
            },
            success: function () {

                $("#benchModal").modal('hide');
                if (current_room_id) loadBenchMap(current_room_id);

            }
        });

    }

    function openSeatPlanModal() {
        $("#seatPlanSetupModal").modal('show');
        $("#seatPlanModalBody").html('<div class="text-center">Loading...</div>');

        $.ajax({
            url: 'seat-plan/seat-plan-modal.php',
            type: 'GET',
            success: function (res) {
                $("#seatPlanModalBody").html(res);
            }
        });
    }

    function generateSeatPlan() {
        let exam = $("#gen_exam").val();
        let shift = $("#gen_shift").val();
        let layout = $("#gen_layout").val();
        let mixing = $("#gen_mixing").val();

        let classSections = [];
        let totalStudents = 0;
        $("input[name='gen_class_sections[]']:checked").each(function () {
            classSections.push($(this).val());
            totalStudents += parseInt($(this).data('students')) || 0;
        });

        let rooms = [];
        let totalCapacity = 0;
        $("input[name='gen_rooms[]']:checked").each(function () {
            rooms.push($(this).val());
            totalCapacity += parseInt($(this).data('capacity')) || 0;
        });

        if (!exam || !shift || classSections.length === 0 || rooms.length === 0) {
            Swal.fire('Warning', 'Please select Exam, Shift, at least 1 Class-Section, and at least 1 Room.', 'warning');
            return;
        }

        if (totalStudents > totalCapacity) {
            Swal.fire('Capacity Error', `Not enough seats! Selected Students: ${totalStudents}, Selected Capacity: ${totalCapacity}`, 'error');
            return;
        }

        let btn = $("#seatPlanModalBody .m3-btn-success");
        btn.text("Generating...").prop("disabled", true);

        $.ajax({
            url: 'seat-plan/generate-seat-plan.php',
            type: 'POST',
            data: {
                exam: exam,
                class_sections: classSections,
                shift: shift,
                rooms: rooms,
                layout: layout,
                mixing: mixing
            },
            dataType: 'json',
            success: function (res) {
                btn.html('<i class="bi bi-magic" style="vertical-align: middle; font-size: 18px;"></i> Generate Plan').prop("disabled", false);
                if (res.success) {
                    Swal.fire('Success', `Seat Plan Generated Successfully! ${res.allocated} students allocated.`, 'success').then(() => {
                        $("#seatPlanSetupModal").modal('hide');
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function (err) {
                console.log(err.responseText);
                Swal.fire('Error', 'Failed to generate plan.', 'error');
                btn.html('<i class="bi bi-magic" style="vertical-align: middle; font-size: 18px;"></i> Generate Plan').prop("disabled", false);
            }
        });
    }

    function openViewMapModal() {
        $("#viewMapModal").modal('show');
        $("#allocated_rooms_list").html('');
        $("#allocated_room_info").html('');
        $("#allocated_bench_grid").html('');

        // Load exams
        $.ajax({
            url: 'seat-plan/get-allocated-exams.php',
            type: 'GET',
            success: function (res) {
                $("#view_map_exam").html(res);
            }
        });
    }

    function loadAllocatedRooms() {
        let exam = $("#view_map_exam").val();
        $("#allocated_rooms_list").html('<div class="text-center">Loading rooms...</div>');
        $("#allocated_room_info").html('');
        $("#allocated_bench_grid").html('');

        if (!exam) {
            $("#allocated_rooms_list").html('');
            return;
        }

        $.ajax({
            url: 'seat-plan/get-allocated-rooms.php',
            type: 'POST',
            data: { examtitle: exam },
            success: function (res) {
                $("#allocated_rooms_list").html(res);
            }
        });
    }

    function loadAllocatedRoomMap(room_id, examtitle) {
        $("#allocated_room_info").html('<div class="text-center">Loading map...</div>');
        $("#allocated_bench_grid").html('');

        $.ajax({
            url: 'seat-plan/load-allocated-bench-map.php',
            type: 'POST',
            data: { room_id: room_id, examtitle: examtitle },
            dataType: 'json',
            success: function (res) {
                renderAllocatedBenchMap(res);
            }
        });
    }

    function renderAllocatedBenchMap(data) {
        $("#allocated_room_info").html(`
        <div style="margin-bottom: 15px;">
            <h4>${data.room_name}</h4>
            <p style="margin-bottom:0;">
                ${data.total_rows} Rows × ${data.total_cols} Columns
            </p>
        </div>
    `);

        let grid = $("#allocated_bench_grid");
        grid.html('');
        grid.css({
            gridTemplateColumns: `repeat(${data.total_cols}, 120px)`
        });

        data.benches.forEach(function (bench) {
            let blocked = bench.is_blocked == 1 ? 'blocked' : '';
            let allocationsHTML = '';

            if (bench.allocations && bench.allocations.length > 0) {
                bench.allocations.forEach(function (alloc) {
                    allocationsHTML += `<div style="font-size: 10px; background: rgba(0,0,0,0.1); padding: 2px; margin-bottom: 2px; border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    ${alloc.classname} - ${alloc.rollno}
                </div>`;
                });
            }

            let html = `
            <div class="bench ${blocked}" style="height: auto; min-height: 70px; flex-direction: column; justify-content: flex-start; padding: 5px;">
                <div style="font-size: 11px; margin-bottom: 5px;"><b>${bench.row_no}-${bench.col_no}</b></div>
                <div style="width: 100%; text-align: center;">${allocationsHTML}</div>
            </div>
        `;

            grid.append(html);
        });
    }

</script>


<script>
    $(document).on("click", ".tree-toggle", function (e) {

        e.stopPropagation();

        let parentLi = $(this).closest("li");

        let child = parentLi.children(".tree-children");

        child.toggleClass("show");

        $(this).find(".toggle-icon")
            .toggleClass("rotate");

    });
</script>

<script>
    function initTreeToggle() {

        $("#hall_tree .tree-toggle").off("click").on("click", function (e) {
            e.stopPropagation();

            let item = $(this).closest(".tree-item");
            let children = item.children(".tree-children");

            if (children.length) {

                children.toggleClass("show");
                item.toggleClass("collapsed");

            }
        });

    }
</script>