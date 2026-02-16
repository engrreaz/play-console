<?php
$page_title = "Leave Approval Management";
include 'inc.php';

// পেন্ডিং আবেদনগুলো ফেচ করা
$q = $conn->prepare("SELECT l.*, s.stnameeng FROM student_leave_app l JOIN students s ON l.stid = s.stid WHERE l.sccode=? AND l.status='Pending' ORDER BY l.apply_date DESC");
$q->bind_param("i", $sccode);
$q->execute();
$pending_list = $q->get_result();
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-tonal: #F3EDF7;
        --m3-success: #E8F5E9;
        --m3-danger: #F9DEDC;
    }

    body {
        background: var(--m3-surface);
        font-family: 'Segoe UI', sans-serif;
    }

    /* Leave Request Card */
    .approval-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin: 12px;
        border: 1px solid #F0F0F0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .student-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--m3-tonal);
        color: var(--m3-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.2rem;
        margin-right: 12px;
    }

    .date-pill {
        background: #F1F0F4;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #49454F;
        display: inline-block;
    }

    /* Action Buttons */
    .btn-approve {
        background: #2E7D32;
        color: white;
        border-radius: 100px;
        font-weight: 700;
        border: none;
        padding: 8px 20px;
    }

    .btn-reject {
        background: #B3261E;
        color: white;
        border-radius: 100px;
        font-weight: 700;
        border: none;
        padding: 8px 20px;
    }

    .btn-approve:active,
    .btn-reject:active {
        transform: scale(0.95);
    }
</style>

<main class="pb-5">
    <div class="hero-container"
        style="margin:12px; padding:24px 20px; border-radius:24px; background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%); color:white;">
        <h4 class="fw-black m-0">Leave Approvals</h4>
        <p class="small m-0 opacity-75">Review and respond to leave requests</p>
    </div>

    <div class="px-3 mt-4 mb-2">
        <span class="fw-black text-muted small text-uppercase" style="letter-spacing:1px;">Pending Requests</span>
    </div>

    <div id="approval-container">
        <?php if ($pending_list->num_rows > 0): ?>
            <?php while ($row = $pending_list->fetch_assoc()): ?>
                <div class="approval-card" id="row-<?= $row['id'] ?>">
                    <div class="d-flex align-items-center mb-3">
                        <div class="student-avatar"><?= substr($row['stnameeng'], 0, 1) ?></div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold m-0 text-dark"><?= $row['stnameeng'] ?></h6>
                            <div class="small text-muted">Roll: <?= $row['rollno'] ?> | Class: <?= $row['classname'] ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="date-pill mb-2">
                            <i class="bi bi-calendar-range me-1"></i>
                            <?= date('d M', strtotime($row['date_from'])) ?> to
                            <?= date('d M, Y', strtotime($row['date_to'])) ?>
                        </div>
                        <div class="p-3 bg-light rounded-3 small text-dark border">
                            <b>Reason:</b> <?= $row['apply_by'] ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn-reject" onclick="processLeave(<?= $row['id'] ?>, 'Rejected')">Reject</button>
                        <button class="btn-approve shadow-sm"
                            onclick="processLeave(<?= $row['id'] ?>, 'Approved')">Approve</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5 opacity-50">
                <i class="bi bi-check2-circle display-1"></i>
                <p class="fw-bold mt-2">No pending applications!</p>
            </div>
        <?php endif; ?>
    </div>
</main>


<?php include 'footer.php'; ?>
<script>
    function processLeave(id, status) {
        if (!confirm(`Are you sure you want to set this as ${status}?`)) return;

        const row = $(`#row-${id}`);

        $.ajax({
            url: 'ajax/process_leave.php',
            type: 'POST',
            data: { id: id, status: status },
            beforeSend: function () { row.css('opacity', '0.5'); },
            success: function (res) {
                if (res.trim() === 'success') {
                    row.slideUp(300, function () { $(this).remove(); });
                } else {
                    alert('Error: ' + res);
                    row.css('opacity', '1');
                }
            }
        });
    }
</script>