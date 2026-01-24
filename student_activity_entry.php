<?php
$page_title = "Student Activity Entry";
include 'inc.php';

/* ---------------- LOAD DATA ---------------- */
$categories = $conn->query("SELECT * FROM activity_categories WHERE status=1 ORDER BY name");
$levels     = $conn->query("SELECT * FROM activity_levels WHERE status=1 ORDER BY id");
$roles      = $conn->query("SELECT * FROM activity_roles WHERE status=1 ORDER BY id");
$awards     = $conn->query("SELECT * FROM activity_awards WHERE status=1 ORDER BY id");

/* ---------------- SAVE ENTRY ---------------- */
$msg_status = "";
if (isset($_POST['save'])) {
    $stid    = mysqli_real_escape_string($conn, $_POST['main-stid']);
    $act     = $_POST['activity'];
    $year    = $_POST['year'];
    $level   = $_POST['level'];
    $role    = $_POST['role'];
    $award   = $_POST['award'];
    $teacher = mysqli_real_escape_string($conn, $_POST['teacher']);
    $rmk     = mysqli_real_escape_string($conn, $_POST['remarks']);

    $q = "INSERT INTO student_activities (stid, activity_id, sessionyear, level, role, award, teacher, remarks)
          VALUES ('$stid','$act','$year','$level','$role','$award','$teacher','$rmk')";
    if($conn->query($q)) { $msg_status = "saved"; }
}

/* ---------------- LOAD PREVIOUS ACTIVITIES ---------------- */
$student_activities = [];
$current_stid = $_POST['main-stid'] ?? "";
if ($current_stid != "") {
    $res = $conn->query("SELECT sa.*, am.title, ac.name AS cat_name FROM student_activities sa 
                         LEFT JOIN activities_master am ON sa.activity_id=am.id 
                         LEFT JOIN activity_categories ac ON am.category=ac.name 
                         WHERE sa.stid='$current_stid' ORDER BY sa.created_at DESC");
    while ($r = $res->fetch_assoc()) { $student_activities[] = $r; }
}
?>

<style>
    .m3-form-card { background: #fff; border-radius: 8px; padding: 24px 16px; margin: -25px 12px 20px; border: 1px solid #f0f0f0; box-shadow: var(--m3-shadow); position: relative; z-index: 10; }
    .m3-input-floating, .m3-select-floating { padding-left: 46px !important; }
    .m3-field-icon { position: absolute; left: 14px; top: 14px; color: var(--m3-primary); font-size: 1.2rem; z-index: 10; }
    textarea.m3-input-floating { height: 100px; padding-top: 15px !important; }
    
    /* History List Items */
    .hist-card { background: #fff; border-radius: 8px; padding: 12px; margin-bottom: 8px; border: 1px solid rgba(0,0,0,0.04); display: flex; gap: 12px; align-items: flex-start; }
    .hist-icon { width: 40px; height: 40px; border-radius: 8px; background: var(--m3-tonal-container); color: var(--m3-on-tonal-container); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff;" onclick="history.back()"><i class="bi bi-arrow-left"></i></div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 900; line-height: 1.1;">Student Activities</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">Assign Co-Curricular Achievements</div>
                </div>
            </div>
            <div class="session-pill" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><?php echo $sessionyear; ?></div>
        </div>
    </div>

    <div class="m3-form-card">
        <form method="post" id="actForm">
            <div class="m3-floating-group">
                <i class="bi bi-person-badge m3-field-icon"></i>
                <input name="main-stid" id="main-stid" class="m3-input-floating" value="<?php echo $current_stid; ?>" required readonly onclick="openStudentModal()" placeholder=" ">
                <label class="m3-floating-label">TAP TO SELECT STUDENT</label>
            </div>

            <div class="row g-2">
                <div class="col-6">
                    <div class="m3-floating-group">
                        <i class="bi bi-grid m3-field-icon"></i>
                        <select name="category" id="category" class="m3-select-floating" onchange="loadActivities()" required>
                            <option value=""></option>
                            <?php while ($c = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $c['name']; ?>"><?php echo $c['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <label class="m3-floating-label">CATEGORY</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="m3-floating-group">
                        <i class="bi bi-activity m3-field-icon"></i>
                        <select name="activity" id="activity" class="m3-select-floating" required>
                            <option value=""></option>
                        </select>
                        <label class="m3-floating-label">ACTIVITY</label>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-6">
                    <div class="m3-floating-group">
                        <i class="bi bi-bar-chart-steps m3-field-icon"></i>
                        <select name="level" class="m3-select-floating" required>
                            <option value=""></option>
                            <?php while ($l = $levels->fetch_assoc()): ?>
                                <option value="<?php echo $l['name']; ?>"><?php echo $l['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <label class="m3-floating-label">LEVEL</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="m3-floating-group">
                        <i class="bi bi-person-gear m3-field-icon"></i>
                        <select name="role" class="m3-select-floating" required>
                            <option value=""></option>
                            <?php while ($r = $roles->fetch_assoc()): ?>
                                <option value="<?php echo $r['name']; ?>"><?php echo $r['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <label class="m3-floating-label">ROLE</label>
                    </div>
                </div>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-trophy m3-field-icon"></i>
                <select name="award" class="m3-select-floating">
                    <option value=""></option>
                    <?php while ($w = $awards->fetch_assoc()): ?>
                        <option value="<?php echo $w['name']; ?>"><?php echo $w['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label class="m3-floating-label">AWARD / POSITION</label>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-person-check m3-field-icon"></i>
                <input name="teacher" class="m3-input-floating" placeholder=" ">
                <label class="m3-floating-label">TEACHER IN-CHARGE</label>
            </div>

            <div class="m3-floating-group">
                <i class="bi bi-sticky m3-field-icon"></i>
                <textarea name="remarks" class="m3-input-floating" placeholder=" "></textarea>
                <label class="m3-floating-label">REMARKS / NOTES</label>
            </div>

            <input type="hidden" name="year" value="<?php echo $sessionyear; ?>">
            <button name="save" class="btn-m3-submit w-100" style="margin: 10px 0 0; height: 54px;">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> SAVE ACTIVITY RECORD
            </button>
        </form>
    </div>

    <?php if (!empty($student_activities)): ?>
        <div class="px-3">
            <div class="m3-section-title">History for ID: <?php echo $current_stid; ?></div>
            <div class="widget-grid" style="padding-bottom: 80px;">
                <?php foreach ($student_activities as $sa): ?>
                    <div class="m3-list-item hist-card shadow-sm">
                        <div class="hist-icon"><i class="bi bi-award"></i></div>
                        <div class="item-info">
                            <div class="st-title" style="font-size: 0.95rem; font-weight: 800;"><?php echo $sa['title']; ?></div>
                            <div class="st-desc" style="font-size: 0.8rem; font-weight: 600; color: var(--m3-primary);"><?php echo $sa['award'] ?: 'Participant'; ?> • <?php echo $sa['role']; ?></div>
                            <div style="font-size: 0.7rem; color: #777; margin-top: 4px;">
                                <i class="bi bi-geo-alt me-1"></i><?php echo $sa['level']; ?> | <i class="bi bi-calendar3 me-1"></i><?php echo date('d M, Y', strtotime($sa['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>



<div id="studentModal" class="m3-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; backdrop-filter: blur(4px); align-items:center; justify-content:center;">
    <div class="m3-modal-content" style="background:#fff; width: 92%; max-height: 85%; border-radius: 28px; padding: 24px; position:relative; overflow:hidden; display:flex; flex-direction:column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h5 class="fw-bold m-0">Select Student</h5>
            <div class="tonal-icon-btn" onclick="closeStudentModal()" style="width: 32px; height: 32px;"><i class="bi bi-x-lg"></i></div>
        </div>
        
        <div class="m3-floating-group" style="margin-bottom: 12px;">
            <i class="bi bi-search m3-field-icon"></i>
            <input type="text" id="studentSearch" class="m3-input-floating" placeholder=" ">
            <label class="m3-floating-label">SEARCH NAME/ROLL...</label>
        </div>

        <div id="studentTree" style="overflow-y:auto; flex-grow:1; border:1px solid #f0f0f0; border-radius:16px; padding:10px; background: #fafafa;">
            </div>
    </div>
</div>

<script>
    // Activity Loading AJAX
    function loadActivities() {
        var cat = document.getElementById('category').value;
        var actSelect = document.getElementById('activity');
        actSelect.innerHTML = '<option value="">Loading...</option>';
        if (cat == "") { actSelect.innerHTML = '<option value=""></option>'; return; }

        fetch("activities/get_activities.php?category=" + encodeURIComponent(cat))
            .then(r => r.json())
            .then(data => {
                actSelect.innerHTML = '<option value=""></option>';
                data.forEach(act => {
                    var opt = document.createElement('option');
                    opt.value = act.id; opt.text = act.title;
                    actSelect.add(opt);
                });
            });
    }

    // Modal & Tree Logic
    function openStudentModal() { 
                    document.getElementById('studentTree').innerHTML = '';
        document.getElementById('studentModal').style.display = 'flex'; loadSlots(); 
    }
    function closeStudentModal() { document.getElementById('studentModal').style.display = 'none'; }

    function loadSlots() {
        fetch('ajax/ajax_tree.php?level=slot')
            .then(r => r.json())
            .then(data => { renderNodes(data, document.getElementById('studentTree'), 'slot'); });
    }

    function renderNodes(nodes, container, level, parentParams = {}) {
        const ul = document.createElement('ul');
        ul.style.listStyle = 'none'; ul.style.paddingLeft = '15px';
        nodes.forEach(n => {
            const li = document.createElement('li');
            const span = document.createElement('span');
            span.className = 'tree-node';
            span.innerHTML = `<span class="tree-arrow">${(level === 'student') ? '●' : '▶'}</span> <span>${n.name}</span>`;
            li.appendChild(span);
            ul.appendChild(li);

            span.onclick = function (e) {
                e.stopPropagation();
                if (level === 'student') {
                    document.getElementById('main-stid').value = n.stid;
                    document.getElementById('actForm').submit(); // Auto-load history
                    return;
                }
                const existingUl = li.querySelector(':scope > ul');
                if (existingUl) { existingUl.remove(); span.querySelector('.tree-arrow').textContent = '▶'; return; }
                
                span.querySelector('.tree-arrow').textContent = '▼';
                let url = `ajax/ajax_tree.php?level=`;
                if(level==='slot') url += `session&slot=${encodeURIComponent(n.name)}`;
                else if(level==='session') url += `class&slot=${encodeURIComponent(parentParams.slot)}&session=${encodeURIComponent(n.name)}`;
                else if(level==='class') url += `section&slot=${encodeURIComponent(parentParams.slot)}&session=${encodeURIComponent(parentParams.session)}&class=${encodeURIComponent(n.name)}`;
                else if(level==='section') url += `students&slot=${encodeURIComponent(parentParams.slot)}&session=${encodeURIComponent(parentParams.session)}&class=${encodeURIComponent(parentParams.class)}&section=${encodeURIComponent(n.name)}`;
                
                fetch(url).then(r => r.json()).then(d => {
                    let nextLevel = (level==='slot')?'session':(level==='session')?'class':(level==='class')?'section':'student';
                    renderNodes(d, li, nextLevel, {...parentParams, [level]: n.name});
                });
            };
        });
        container.appendChild(ul);
    }

    // Search Filter
    document.getElementById('studentSearch').addEventListener('keyup', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#studentTree li').forEach(li => {
            li.style.display = li.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>

<?php include 'footer.php'; ?>