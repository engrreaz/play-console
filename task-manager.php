<?php
$page_title = "Task Manager";
include 'inc.php'; ?>

<?php
/* ================= STATUS COLORS ================= */
function statusColor($s)
{
    switch ($s) {
        case 'queue':
            return '#f31616';
        case 'On Hold':
            return '#f1f526';
        case 'Processing':
            return '#0288D1';
        case 'Trial':
            return '#23cfc1';
        case 'Beta':
            return '#7937dd';
        case 'RC':
            return '#30ee6f';
        case 'Stable':
            return '#2e8d4e';
        default:
            return '#79747E';
    }
}

/* ================= DATALIST ================= */
function datalist($field, $value = '')
{
    global $conn;
    $q = mysqli_query($conn, "SELECT DISTINCT $field FROM task_manager WHERE $field IS NOT NULL AND $field != ''");
    echo "<input list='$field' name='$field' class='form-control form-control-sm' placeholder='Select or type...' value='" . htmlspecialchars($value) . "'>
          <datalist id='$field'>";
    while ($r = mysqli_fetch_row($q)) {
        echo "<option value='" . htmlspecialchars($r[0]) . "'>";
    }
    echo "</datalist>";
}
?>

<!-- ================= CSS (Design as Before) ================= -->
<style>
    .filter-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        margin: -30px 20px 20px;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .task-card-m3 {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #F0F0F0;
        transition: transform 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .task-card-m3:active {
        transform: scale(0.98);
    }

    .platform-divider {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--m3-primary);
        margin: 24px 16px 12px;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .platform-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--m3-primary-tonal);
    }

    .m3-badge {
        font-size: 0.65rem;
        padding: 4px 12px;
        border-radius: 100px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .response-note {
        background: #F7F2FA;
        border-left: 3px solid var(--m3-primary);
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-top: 8px;
    }

    .m3-floating-group {
        position: relative;
        margin-bottom: 12px;
    }

    .m3-floating-label {
        position: absolute;
        left: 12px;
        top: -10px;
        background: white;
        padding: 0 5px;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--m3-primary);
    }

    .m3-field-icon {
        position: absolute;
        right: 12px;
        top: 12px;
        color: var(--m3-primary);
    }


    /* Generic modal size */
    .modal-dialog {
        max-width: 85%;
        max-height: 70%;
        margin: auto;
    }

    .modal-content {
        height: 100%;
        border-radius: 12px;
    }

    /* Modal body scroll */
    .modal-body {
        overflow-y: auto;
        max-height: calc(85vh - 120px);
        /* Adjust depending on header/footer height */
    }
</style>


<!-- ================= HERO ================= -->
<div class="hero-container">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold m-0">Task Manager</h4>
            <p class="small m-0 opacity-75">Track development & modules</p>
        </div>

        <button id="newTaskBtn" class="btn btn-light btn-sm fw-bold rounded-pill px-3" onclick="openNewTaskModal()"  style="z-index:999;" >
            <i class="bi bi-plus-lg me-1"></i> New Task
        </button>

    </div>
</div>

<!-- ================= FILTER ================= -->
<div class="filter-card">
    <form class="row g-2" method="post">
        <div class="col-12 col-md-6">
            <div class="m3-floating-group">
                <label class="m3-floating-label">Search Module</label>
                <input type="text" name="q" class="form-control form-control-sm rounded-3"
                    placeholder="Search module / topic" value="<?= $_POST['q'] ?? '' ?>">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="platform" class="form-select form-select-sm rounded-3">
                <option value="">All Platform</option>
                <?php foreach (['play', 'web', 'android', 'console'] as $p): ?>
                    <option value="<?= $p ?>" <?= (isset($_POST['platform']) && $_POST['platform'] == $p) ? 'selected' : '' ?>>
                        <?= $p ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select form-select-sm rounded-3">
                <option value="">All Status</option>
                <?php foreach (['Queue', 'Processing', 'Trial', 'Beta', 'RC', 'Stable'] as $s): ?>
                    <option value="<?= $s ?>" <?= (isset($_POST['status']) && $_POST['status'] == $s) ? 'selected' : '' ?>>
                        <?= $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 mt-2">
            <button class="btn btn-dark w-100 btn-sm fw-bold rounded-3 py-2">Apply Filters</button>
        </div>
    </form>
</div>

<!-- ================= TASK CARDS ================= -->
<div class="container-fluid px-3">
    <?php
    $where = "1";
    if (!empty($_POST['q']))
        $where .= " AND (module LIKE '%" . mysqli_real_escape_string($conn, $_POST['q']) . "%' OR root_topic LIKE '%" . mysqli_real_escape_string($conn, $_POST['q']) . "%')";
    if (!empty($_POST['platform']))
        $where .= " AND platform='" . mysqli_real_escape_string($conn, $_POST['platform']) . "'";
    if (!empty($_POST['status']))
        $where .= " AND status='" . mysqli_real_escape_string($conn, $_POST['status']) . "'";

    $q = mysqli_query($conn, "SELECT * FROM task_manager WHERE $where ORDER BY platform,module");
    $current = '';

    while ($row = mysqli_fetch_assoc($q)):
        if ($current != $row['platform']):
            echo "<div class='platform-divider'>{$row['platform']}</div>";
            $current = $row['platform'];
        endif;
        ?>
        <div class="task-card-m3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold m-0"><?= $row['module'] ?> <small class="text-muted fw-normal">|
                            <?= $row['panel'] ?></small></h6>
                    <p class="text-muted mb-1" style="font-size:0.75rem;">
                        <i class="bi bi-folder2-open me-1"></i><?= $row['root_topic'] ?> <i
                            class="bi bi-chevron-right mx-1"></i><?= $row['sub_level_1'] ?>
                    </p>
                </div>
                <span class="m3-badge"
                    style="background: <?= statusColor($row['status']) ?>20; color: <?= statusColor($row['status']) ?>;">
                    <?= $row['status'] ?>
                </span>
            </div>

            <?php
            $rq = mysqli_query($conn, "SELECT * FROM task_response WHERE task_id='{$row['id']}' ORDER BY id DESC LIMIT 2");
            while ($rr = mysqli_fetch_assoc($rq)):
                ?>
                <div class="response-note">
                    <strong><?= $rr['response_status'] ?>:</strong> <?= $rr['notes'] ?>
                </div>
            <?php endwhile; ?>

            <div class="mt-3 d-flex justify-content-end gap-1">
                <button class="btn btn-light btn-sm rounded-pill" onclick="showHistory(<?= $row['id'] ?>)"><i
                        class="bi bi-clock-history"></i></button>
                <button class="btn btn-light btn-sm rounded-pill text-warning" onclick="editTask(<?= $row['id'] ?>)"><i
                        class="bi bi-pencil-square"></i></button>
                <button class="btn btn-light btn-sm rounded-pill text-danger" onclick="deleteTask(<?= $row['id'] ?>)"><i
                        class="bi bi-trash3"></i></button>
                <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="openResponseModal(<?= $row['id'] ?>)"><i
                        class="bi bi-chat-dots-fill me-1"></i> Update</button>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- ================= TASK MODAL ================= -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content border-0 shadow-lg">
            <form id="taskForm">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-black">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="task_id">

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Platform</label>
                            <select name="platform" class="form-select rounded-3">
                                <?php foreach (['play', 'web', 'android', 'console'] as $p): ?>
                                    <option value="<?= $p ?>"><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Module</label>
                            <select name="module" class="form-select rounded-3">
                                <?php
                                $m = mysqli_query($conn, "SELECT module_name FROM modulelist");
                                while ($mm = mysqli_fetch_assoc($m))
                                    echo "<option>" . htmlspecialchars($mm['module_name']) . "</option>";
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Panel</label>
                            <select name="panel" class="form-select rounded-3">
                                <?php foreach (['developement', 'administrator', 'cheif', 'teacher', 'accountant', 'student'] as $pan): ?>
                                    <option><?= $pan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Root Topic</label>
                            <select name="root_topic" class="form-select rounded-3">
                                <?php foreach (['Home', 'Report', 'Tools', 'Settings', 'Profile', 'Drawer', 'AppBar'] as $root): ?>
                                    <option><?= $root ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4"><?php datalist("sub_level_1"); ?></div>
                        <div class="col-4"><?php datalist("sub_level_2"); ?></div>
                        <div class="col-4"><?php datalist("sub_level_3"); ?></div>
                    </div>

                    <div class="m3-floating-group mb-3">
                        <label class="m3-floating-label">Notes</label>
                        <textarea name="notes" class="form-control rounded-3" rows="3"
                            placeholder="Explain the task..."></textarea>
                    </div>


                    <div class="row g-2 mb-">
                        <div class="col-4">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Status</label>
                                <select name="status" class="form-select rounded-3">
                                    <?php foreach (['queue', 'On Hold', 'Processing', 'Trial', 'Beta', 'RC', 'Stable'] as $s): ?>
                                        <option><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-6"> <button type="submit" class="btn btn-primary  shadow">Save Task
                                Settings</button></div>
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>

<!-- ================= RESPONSE MODAL ================= -->
<div class="modal fade" id="responseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <form id="responseForm">
                <input type="hidden" name="task_id" id="res_task_id">
                <div class="modal-header">
                    <h5>Task Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea name="notes" class="form-control mb-2" placeholder="Add notes..."></textarea>
                    <select name="response_status" class="form-select">
                        <?php foreach (['Processing', 'Trial', 'Beta', 'RC', 'Stable'] as $s): ?>
                            <option><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= HISTORY MODAL ================= -->
<div class="modal fade" id="historyModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Task Response History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="historyBody"></div>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>



<script>
    function openResponseModal(id) { $('#res_task_id').val(id); $('#responseModal').modal('show'); }
    $('#taskForm').submit(function (e) { e.preventDefault(); $.post("task/task-save.php", $(this).serialize(), () => location.reload()); });
    $('#responseForm').submit(function (e) { e.preventDefault(); $.post("task/task-response-save.php", $(this).serialize(), () => location.reload()); });
    function deleteTask(id) { if (confirm("Delete this task permanently?")) $.post("task/task-delete.php", { id }, () => location.reload()); }
    function showHistory(id) { $.get("task/task-history.php", { id }, d => { $("#historyBody").html(d); $('#historyModal').modal('show'); }); }
    function editTask(id) {
        $.get("task/task-edit-fetch.php", { id }, res => {
            var data = JSON.parse(res);
            $('#task_id').val(data.id);
            $('[name=platform]').val(data.platform);
            $('[name=module]').val(data.module);
            $('[name=panel]').val(data.panel);
            $('[name=root_topic]').val(data.root_topic);
            $('[name=sub_level_1]').val(data.sub_level_1);
            $('[name=sub_level_2]').val(data.sub_level_2);
            $('[name=sub_level_3]').val(data.sub_level_3);
            $('[name=notes]').val(data.notes);
            $('[name=status]').val(data.status);
            $('#taskModal').modal('show');
        });
    }
</script>


<script>
    function openNewTaskModal() {
        console.log('Opening New Task Modal...');

        // ১. ফর্মটি রিসেট করুন যাতে আগের এডিট করা ডেটা চলে যায়
        const form = document.getElementById('taskForm');
        if (form) form.reset();

        // ২. হিডেন আইডি (task_id) খালি করুন (যাতে নতুন এন্ট্রি হিসেবে সেভ হয়)
        const taskIdField = document.getElementById('task_id');
        if (taskIdField) taskIdField.value = '';

        // ৩. মডালটি ওপেন করুন (Bootstrap 5 স্ট্যান্ডার্ড)
        const modalEl = document.getElementById('taskModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            console.error('taskModal element not found!');
        }
    }
</script>


</body>

</html>


<!-- document.addEventListener('DOMContentLoaded', function () { const btn = document.getElementById('newTaskBtn'); if (!btn) { console.error('newTaskBtn not found'); return; } console.log('NEW TASK CLICKED'); btn.addEventListener('click', function () { console.log('Clicked'); // const form = document.getElementById('taskForm'); // form.reset(); // document.getElementById('task_id').value = ''; // const modalEl = document.getElementById('taskModal'); // const modal = new bootstrap.Modal(modalEl); // modal.show(); }); }); বাটন ক্লিক করলে NEW TASK CLICKED প্রিন্ট হয়, কিন্তু, Clicked প্রিন্ট হয় না -->