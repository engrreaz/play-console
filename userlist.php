<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-teacher.php';

$page_title = "User Manager";
$lbl = '';
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */

    /* Standard M3 Top App Bar */
    .m3-app-bar {
        background-color: #FFFFFF;
        height: 64px;
        display: flex; align-items: center;
        padding: 0 16px;
        position: sticky; top: 0; z-index: 1050;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 20px 20px;
    }
    .m3-app-bar .back-btn { color: #1C1B1F; font-size: 1.5rem; margin-right: 16px; text-decoration: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .m3-app-bar .page-title { font-size: 1.2rem; font-weight: 600; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Category Label */
    .section-label {
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        color: #6750A4; margin: 24px 20px 12px; letter-spacing: 1px;
    }

    /* M3 User Card */
    .user-card {
        background: white; border-radius: 24px; padding: 20px;
        margin: 0 16px 12px; border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .user-card:active { transform: scale(0.98); background: #F7F2FA; }

    .user-meta { display: flex; align-items: center; margin-bottom: 15px; }
    .avatar-icon {
        width: 48px; height: 48px; border-radius: 14px;
        background: #F3EDF7; color: #6750A4;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px; font-size: 1.4rem;
    }

    .form-select { border-radius: 12px; border: 1px solid #79747E; background: #fff; font-size: 0.9rem; padding: 10px; }

    .btn-pill { border-radius: 100px; font-weight: 700; font-size: 0.8rem; padding: 8px 16px; border: none; transition: 0.2s; }
    .btn-m3-tonal { background: #EADDFF; color: #21005D; }
    .btn-m3-danger { background: #F2B8B5; color: #601410; }
    .btn-m3-primary { background: #6750A4; color: white; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="javascript:history.back()" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-person-plus-fill fs-4"></i></div>
</header>

<main class="pb-5">
    <?php
    $stmt1 = $conn->prepare("SELECT id, email FROM usersapp WHERE sccode = ? AND (userlevel='Guest' OR userlevel='Visitor')");
    $stmt1->bind_param("s", $sccode);
    $stmt1->execute();
    $res_new = $stmt1->get_result();

    if ($res_new->num_rows > 0): ?>
        <div class="section-label">Pending Approval</div>
        <?php while ($row = $res_new->fetch_assoc()): 
            $id = $row['id']; 
        ?>
            <div class="user-card shadow-sm" id="usr<?php echo $id; ?>">
                <div class="user-meta">
                    <div class="avatar-icon"><i class="bi bi-person-plus"></i></div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-dark text-truncate"><?php echo $row['email']; ?></div>
                        <div class="text-muted small">Wants to join as Member</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-pill btn-m3-tonal flex-grow-1" onclick="upd(<?php echo $id; ?>, 0);">Teacher</button>
                    <button class="btn btn-pill btn-m3-danger flex-grow-1" onclick="upd(<?php echo $id; ?>, 1);">Admin</button>
                    <button class="btn btn-pill bg-secondary-subtle text-muted" disabled><i class="bi bi-trash"></i></button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; $stmt1->close(); ?>

    <div class="section-label">Active System Users</div>
    <?php
    $stmt2 = $conn->prepare("SELECT id, email, profilename, userid, userlevel, hiddenuser FROM usersapp WHERE sccode = ? AND (userlevel='Teacher' OR userlevel='Administrator' OR userlevel='Super Administrator') ORDER BY userlevel DESC");
    $stmt2->bind_param("s", $sccode);
    $stmt2->execute();
    $res_reg = $stmt2->get_result();

    while ($row = $res_reg->fetch_assoc()):
        $hidn = $row["hiddenuser"];
        // ফিল্টারিং লজিক (অরিজিনাল অনুযায়ী)
        if ($hidn == 1 && $usr != $row['email'] && $reallevel != 'Super Administrator') continue;
    ?>
        <div class="user-card shadow-sm" id="usr<?php echo $row['id']; ?>">
            <div class="user-meta">
                <div class="avatar-icon">
                    <i class="bi <?php echo ($row['userlevel'] == 'Teacher') ? 'bi-person-badge' : 'bi-shield-lock-fill text-danger'; ?>"></i>
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="fw-bold text-dark text-truncate"><?php echo $row['profilename']; ?></div>
                    <div class="text-muted small"><?php echo $row['email']; ?> <i class="bi bi-dot"></i> <b><?php echo $row['userlevel']; ?></b></div>
                </div>
                <?php if($row['userlevel'] != 'Teacher'): ?>
                    <i class="bi bi-patch-check-fill text-primary"></i>
                <?php endif; ?>
            </div>

            <div class="mt-3 pt-3 border-top">
                <label class="label-small text-muted fw-bold mb-2 d-block" style="font-size: 0.65rem;">LINK TO TEACHER PROFILE</label>
                <div class="row g-2 align-items-center">
                    <div class="col-8">
                        <select class="form-select" id="a<?php echo $row['id']; ?>">
                            <option value="">-- No Link --</option>
                            <?php
                            $stmt_t = $conn->prepare("SELECT tid, tname FROM teacher WHERE sccode = ? AND status = '1' ORDER BY ranks, id");
                            $stmt_t->bind_param("s", $sccode);
                            $stmt_t->execute();
                            $res_t = $stmt_t->get_result();
                            while ($t = $res_t->fetch_assoc()) {
                                $selected = ($t['tid'] == $row['userid']) ? 'selected' : '';
                                echo "<option value='".$t['tid']."' $selected>".$t['tname']."</option>";
                            }
                            $stmt_t->close();
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-pill btn-m3-primary w-100" onclick="bind(<?php echo $row['id']; ?>);">BIND</button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <div id="pro<?php echo $row['id']; ?>" class="small text-success fw-bold"></div>
                <?php if ($row['userlevel'] != 'Super Administrator'): ?>
                    <button class="btn btn-sm text-danger fw-bold" onclick="rem(<?php echo $row['id']; ?>);">
                        <i class="bi bi-person-x me-1"></i> REMOVE ACCESS
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; $stmt2->close(); ?>

</main>

<div style="height: 60px;"></div>

<script>
    // ১. ইউজার লেভেল আপডেট (Teacher/Admin)
    function upd(id, rank) {
        $.ajax({
            type: "POST",
            url: "backend/user-update.php",
            data: { id: id, ch: rank },
            beforeSend: function () {
                $('#usr' + id).css('opacity', '0.5');
            },
            success: function (html) {
                Swal.fire('Updated!', 'User access level changed.', 'success').then(() => location.reload());
            }
        });
    }

    // ২. টিচার প্রোফাইল বাইন্ড করা
    function bind(id) {
        const tid = document.getElementById('a' + id).value;
        $.ajax({
            type: "POST",
            url: "backend/user-update.php",
            data: { id: id, tid: tid, ch: 1 },
            beforeSend: function () {
                $('#pro' + id).html('<div class="spinner-border spinner-border-sm text-primary"></div>');
            },
            success: function (html) {
                $('#pro' + id).html('✓ Bound');
                setTimeout(() => location.reload(), 1000);
            }
        });
    }

    // ৩. ইউজার রিমুভ করা
    function rem(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will lose all access to the portal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, remove them'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "backend/user-update.php",
                    data: { id: id, ch: 2 },
                    success: function (html) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>