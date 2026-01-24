<?php
$page_title = "User Manager";
include 'inc.php'; 
include 'datam/datam-teacher.php';
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; margin: 0; padding: 0; }

    /* M3 Tabs Styling */
    .m3-tabs {
        display: flex; background: #fff; padding: 4px;
        margin: 12px; border-radius: 12px; border: 1px solid #f0f0f0;
    }
    .m3-tab-item {
        flex: 1; text-align: center; padding: 10px; border-radius: 8px;
        font-weight: 700; font-size: 0.85rem; color: #49454F; cursor: pointer; transition: 0.3s;
    }
    .m3-tab-item.active { background: #EADDFF; color: #21005D; }

    /* Sub-tabs (Pills) for Roles */
    .m3-sub-tabs {
        display: flex; gap: 8px; padding: 0 16px; margin-bottom: 16px; overflow-x: auto;
    }
    .sub-pill {
        white-space: nowrap; padding: 6px 16px; border-radius: 100px;
        background: #fff; border: 1px solid #79747E; font-size: 0.75rem; font-weight: 600; cursor: pointer;
    }
    .sub-pill.active { background: #6750A4; color: #fff; border-color: #6750A4; }

    /* Re-using your Elite M3 Card Style */
    .m3-user-card {
        background: #fff; border-radius: 8px; padding: 12px;
        margin: 0 12px 10px; border: 1px solid #f0f0f0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02); display: block;
    }
    .icon-box {
        width: 44px; height: 44px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; margin-right: 14px; font-size: 1.2rem;
    }
    .c-pend { background: #FFF3E0; color: #E65100; }
    .c-teach { background: #F3EDF7; color: #6750A4; }
    .c-admin { background: #F9DEDC; color: #B3261E; }
    .c-std { background: #E3F2FD; color: #1976D2; }

    .btn-m3 { border-radius: 8px; font-size: 0.75rem; font-weight: 700; padding: 8px 12px; border: none; }
    .btn-primary { background: #6750A4; color: white; }
    
    /* Animation */
    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<main class="pb-5 mt-2">

    <div class="m3-tabs">
        <div class="m3-tab-item active" onclick="switchMainTab('registered', this)">REGISTERED</div>
        <div class="m3-tab-item" onclick="switchMainTab('pending', this)">PENDING</div>
    </div>

    <div id="registered" class="tab-pane active">
        <div class="m3-sub-tabs">
            <div class="sub-pill active" onclick="filterRole('all', this)">All</div>
            <div class="sub-pill" onclick="filterRole('Administrator', this)">Admins</div>
            <div class="sub-pill" onclick="filterRole('Teacher', this)">Teachers</div>
            <div class="sub-pill" onclick="filterRole('Student', this)">Students</div>
        </div>

        <div id="active-users-list">
            <?php
            $stmt2 = $conn->prepare("SELECT id, email, profilename, userid, userlevel, hiddenuser FROM usersapp WHERE sccode = ? AND (userlevel='Teacher' OR userlevel='Administrator' OR userlevel='Super Administrator' OR userlevel='Student') ORDER BY userlevel DESC");
            $stmt2->bind_param("s", $sccode);
            $stmt2->execute();
            $res_reg = $stmt2->get_result();
            while ($row = $res_reg->fetch_assoc()):
                $level = $row['userlevel'];
                // Role color logic
                $color_cls = 'c-teach'; $icon = 'bi-person-badge';
                if($level == 'Administrator' || $level == 'Super Administrator') { $color_cls = 'c-admin'; $icon = 'bi-shield-lock-fill'; }
                if($level == 'Student') { $color_cls = 'c-std'; $icon = 'bi-mortarboard-fill'; }
            ?>
                <div class="m3-user-card shadow-sm role-card" data-role="<?php echo $level; ?>" id="usr<?php echo $row['id']; ?>">
                    <div class="d-flex align-items-center">
                        <div class="icon-box <?php echo $color_cls; ?>"><i class="bi <?php echo $icon; ?>"></i></div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;"><?php echo $row['profilename']; ?></div>
                            <div style="font-size: 0.7rem; color: #49454F;">
                                <?php echo $row['email']; ?> <span class="badge bg-light text-dark border ms-1"><?php echo $level; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-2 border-top row g-2 align-items-center">
                        <div class="col-8">
                            <select class="form-select form-select-sm shadow-none border-secondary-subtle" style="border-radius: 8px; font-size: 0.8rem;" id="a<?php echo $row['id']; ?>">
                                <option value="">-- Link Profile --</option>
                                <?php
                                $stmt_t = $conn->prepare("SELECT tid, tname FROM teacher WHERE sccode = ? AND status = '1' ORDER BY ranks");
                                $stmt_t->bind_param("s", $sccode); $stmt_t->execute(); $res_t = $stmt_t->get_result();
                                while ($t = $res_t->fetch_assoc()) {
                                    $sel = ($t['tid'] == $row['userid']) ? 'selected' : '';
                                    echo "<option value='".$t['tid']."' $sel>".$t['tname']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <button class="btn-m3 btn-primary w-100" onclick="bind(<?php echo $row['id']; ?>);">BIND</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; $stmt2->close(); ?>
        </div>
    </div>

    <div id="pending" class="tab-pane">
        <div id="pending-list">
            <?php
            $stmt1 = $conn->prepare("SELECT id, email FROM usersapp WHERE sccode = ? AND (userlevel='Guest' OR userlevel='Visitor')");
            $stmt1->bind_param("s", $sccode); $stmt1->execute(); $res_new = $stmt1->get_result();
            if ($res_new->num_rows > 0): 
                while ($row = $res_new->fetch_assoc()): $id = $row['id']; ?>
                    <div class="m3-user-card shadow-sm" id="usr<?php echo $id; ?>">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box c-pend"><i class="bi bi-person-plus-fill"></i></div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo $row['email']; ?></div>
                                <div style="font-size: 0.7rem; color: #E65100;">New Access Request</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn-m3 btn-primary flex-grow-1" style="background:#EADDFF; color:#21005D;" onclick="upd(<?php echo $id; ?>, 'Teacher');">TEACHER</button>
                            <button class="btn-m3 btn-primary flex-grow-1" onclick="upd(<?php echo $id; ?>, 'Administrator');">ADMIN</button>
                        </div>
                    </div>
                <?php endwhile; 
            else: ?>
                <div class="text-center mt-5 opacity-50">
                    <i class="bi bi-inbox fs-1"></i>
                    <p>No pending requests</p>
                </div>
            <?php endif; $stmt1->close(); ?>
        </div>
    </div>

</main>

<div style="height: 75px;"></div>

<?php include 'footer.php'; ?>

<script>
    // ১. মেইন ট্যাব সুইচিং
    function switchMainTab(tabId, el) {
        $('.tab-pane').removeClass('active');
        $('.m3-tab-item').removeClass('active');
        $('#' + tabId).addClass('active');
        $(el).addClass('active');
    }

    // ২. রোল ফিল্টারিং (Registered ট্যাবের জন্য)
    function filterRole(role, el) {
        $('.sub-pill').removeClass('active');
        $(el).addClass('active');

        if (role === 'all') {
            $('.role-card').fadeIn();
        } else {
            $('.role-card').hide();
            // Administrator এবং Super Administrator কে এক সাথে দেখাবে যদি Admin সিলেক্ট হয়
            if(role === 'Administrator') {
                $('.role-card').filter(function() {
                    return $(this).data('role').includes('Administrator');
                }).fadeIn();
            } else {
                $('.role-card[data-role="' + role + '"]').fadeIn();
            }
        }
    }

    // ৩. AJAX আপডেট ফাংশনসমূহ (আগের মতো)
    function upd(id, level) {
        $.ajax({
            type: "POST",
            url: "backend/user-update.php",
            data: { id: id, level: level, action: 'update_level' },
            success: function (res) {
                if(res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'User Approved', timer: 1000, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1100); // নতুন কার্ড জেনারেট করতে রিলোড সহজ পদ্ধতি
                }
            }
        });
    }

    function bind(id) {
        const tid = $('#a' + id).val();
        $.ajax({
            type: "POST",
            url: "backend/user-update.php",
            data: { id: id, tid: tid, action: 'bind_profile' },
            success: function (res) {
                if(res.status === 'success') {
                    $('#usr' + id).addClass('success-flash');
                    Swal.fire({ icon: 'success', title: 'Linked', timer: 800, showConfirmButton: false });
                    setTimeout(() => $('#usr' + id).removeClass('success-flash'), 2000);
                }
            }
        });
    }
</script>