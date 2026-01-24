<?php
$page_title = "Activity Manager";
include 'inc.php';

/* ---------- ১. ডেটা প্রসেসিং (Add/Update) ---------- */
if(isset($_POST['add_activity'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $catid = $_POST['category'];
    $conn->query("INSERT INTO activities_master(title, category) SELECT '$title', name FROM activity_categories WHERE id='$catid'");
}

if(isset($_POST['update_activity'])){
    $id = $_POST['actid'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $catname = mysqli_real_escape_string($conn, $_POST['category']);
    $conn->query("UPDATE activities_master SET title='$title', category='$catname' WHERE id='$id'");
}

/* ---------- ২. ডেটা লোড (Category অনুযায়ী সর্ট করা) ---------- */
$res = $conn->query("SELECT * FROM activities_master ORDER BY category ASC, id DESC");
?>

<style>
    /* মডাল এবং ইউআই ফিক্স */
    .m3-cat-header {
        font-size: 0.75rem; font-weight: 900; color: var(--m3-primary);
        text-transform: uppercase; letter-spacing: 1.2px; margin: 25px 16px 12px;
        display: flex; align-items: center; gap: 10px;
    }
    .m3-cat-header::after { content: ""; flex: 1; height: 1px; background: var(--m3-tonal-container); }

    .m3-modal {
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,0.5); z-index:2000;
        backdrop-filter: blur(4px);
    }
    .m3-modal-content {
        background:#fff; width: 92%; max-width: 400px;
        margin: 12vh auto; padding: 24px; border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
</style>

<main>
    <div class="hero-container" style="padding-bottom: 30px; border-radius: 0 0 24px 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;">Activity Manager</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">Organization & Events</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.8rem; font-weight: 900; line-height: 1;"><?php echo $res->num_rows; ?></div>
                <div style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Total</div>
            </div>
        </div>
    </div>

    <div class="widget-grid" style="padding: 0 4px 100px;">
        <?php 
        if ($res->num_rows > 0):
            $current_category = ""; 
            while($r = $res->fetch_assoc()): 
                if ($r['category'] !== $current_category): 
                    $current_category = $r['category'];
        ?>
                    <div class="m3-cat-header">
                        <i class="bi bi-collection-fill"></i> <?php echo strtoupper($current_category); ?>
                    </div>
        <?php endif; ?>

                <div class="m3-list-item shadow-sm" style="margin: 0 12px 8px; padding: 12px 16px;">
                    <div class="icon-box c-acad" style="width: 40px; height: 40px;">
                        <i class="bi bi-dot" style="font-size: 2rem;"></i>
                    </div>
                    <div class="item-info">
                        <div class="st-title" style="font-size: 0.95rem; font-weight: 800;"><?php echo $r['title']; ?></div>
                        <div class="st-desc" style="font-size: 0.75rem; opacity: 0.7;">Reference ID: #<?php echo $r['id']; ?></div>
                    </div>
                    <div class="tonal-icon-btn c-info" style="width: 36px; height: 36px;" 
                         onclick="openEditModal('<?php echo $r['id']; ?>', '<?php echo htmlspecialchars($r['title']); ?>', '<?php echo htmlspecialchars($r['category']); ?>')">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 80px 20px; opacity: 0.4;">
                <i class="bi bi-folder-x" style="font-size: 4rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No Data Found</div>
            </div>
        <?php endif; ?>
    </div>

    <button class="m3-fab shadow-lg" style="position: fixed; bottom: 85px; right: 20px; background: var(--m3-primary-gradient); color: #fff; width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; z-index: 1050; border: none;" onclick="openAddModal()">
        <i class="bi bi-plus-lg" style="font-size: 1.6rem;"></i>
    </button>
</main>

<div id="addModal" class="m3-modal">
    <div class="m3-modal-content">
        <h5 class="fw-bold mb-4" style="color: var(--m3-primary);"><i class="bi bi-plus-circle me-2"></i>New Activity</h5>
        <form method="post">
            <div class="m3-floating-group">
                <i class="bi bi-pen m3-field-icon"></i>
                <input type="text" name="title" class="m3-input-floating" placeholder=" " required>
                <label class="m3-floating-label">ACTIVITY NAME</label>
            </div>
            <div class="m3-floating-group">
                <i class="bi bi-grid m3-field-icon"></i>
                <select name="category" class="m3-select-floating" required>
                    <option value=""></option>
                    <?php
                    $cats_res = $conn->query("SELECT * FROM activity_categories WHERE status=1");
                    while($c = $cats_res->fetch_assoc()): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label class="m3-floating-label">SELECT CATEGORY</label>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn btn-light flex-fill" style="border-radius:12px; font-weight:700;" onclick="closeModal()">CANCEL</button>
                <button name="add_activity" class="btn btn-primary flex-fill" style="border-radius:12px; font-weight:700;">SAVE</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="m3-modal">
    <div class="m3-modal-content">
        <h5 class="fw-bold mb-4" style="color: var(--m3-primary);"><i class="bi bi-pencil-square me-2"></i>Edit Activity</h5>
        <form method="post">
            <input type="hidden" name="actid" id="actid">
            <div class="m3-floating-group">
                <i class="bi bi-pen m3-field-icon"></i>
                <input type="text" name="title" id="edtitle" class="m3-input-floating" placeholder=" " required>
                <label class="m3-floating-label">ACTIVITY NAME</label>
            </div>
            <div class="m3-floating-group">
                <i class="bi bi-grid m3-field-icon"></i>
                <select name="category" id="edcategory" class="m3-select-floating" required>
                    <option value=""></option>
                    <?php
                    $cats_edit = $conn->query("SELECT * FROM activity_categories WHERE status=1");
                    while($c = $cats_edit->fetch_assoc()): ?>
                        <option value="<?php echo $c['name']; ?>"><?php echo $c['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label class="m3-floating-label">CATEGORY</label>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn btn-light flex-fill" style="border-radius:12px; font-weight:700;" onclick="closeModal()">CANCEL</button>
                <button name="update_activity" class="btn btn-primary flex-fill" style="border-radius:12px; font-weight:700;">UPDATE</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal(){ document.getElementById("addModal").style.display="block"; }
    function closeModal(){ 
        document.getElementById("addModal").style.display="none"; 
        document.getElementById("editModal").style.display="none"; 
    }
    function openEditModal(id, title, cat){
        document.getElementById("editModal").style.display="block";
        document.getElementById("actid").value = id;
        document.getElementById("edtitle").value = title;
        let sel = document.getElementById("edcategory");
        for(let i=0; i<sel.options.length; i++){ if(sel.options[i].value == cat){ sel.selectedIndex = i; } }
    }
    window.onclick = function(event) { if (event.target.className === "m3-modal") { closeModal(); } }
</script>

<?php include 'footer.php'; ?>