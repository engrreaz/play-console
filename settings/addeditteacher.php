<?php
/**
 * Staff Manager Backend - Optimized for Single Block Update
 * Logic: Prepared Statements | M3 Style | Photo Check
 */
include('../inc.light.php');

// ১. ইনপুট ডাটা সংগ্রহ
$sccode = $_POST['rootuser'];
$tid = $_POST['tid'];
$tname = $_POST['tname'];
$pos = $_POST['pos'];
$mno = $_POST['mno'];
$action = $_POST['action'];

// ২. র‍্যাঙ্ক নির্ধারণ (Designation wise sorting)
$ranks_map = [
    'Principal' => 1,
    'Head Teacher' => 2,
    'Asstt. Head Teacher' => 3,
    'Senior Teacher' => 4,
    'Lecturer' => 5,
    'Asstt. Teacher' => 6,
    'Accountant' => 7,
    'Office Assistant' => 8
];
$rank = $ranks_map[$pos] ?? 9;

// ৩. ডাটাবেজ অপারেশন
if ($action == 1) { // SAVE or UPDATE
    if (empty($tid)) {
        // নতুন আইডি জেনারেশন লজিক (আপনার লজিক ঠিক রেখে সিকিউর করা হয়েছে)
        $tidstart = $sccode * 10000 + 9000;
        $tidend = $sccode * 10000 + 9998;

        $stmt_id = $conn->prepare("SELECT tid FROM teacher WHERE sccode=? AND tid BETWEEN ? AND ? ORDER BY tid ASC LIMIT 1");
        $stmt_id->bind_param("sii", $sccode, $tidstart, $tidend);
        $stmt_id->execute();
        $res_id = $stmt_id->get_result();

        $ltid = ($res_id->num_rows > 0) ? $res_id->fetch_assoc()["tid"] : ($sccode * 10000 + 9999);
        $new_tid = $ltid - 1;
        $stmt_id->close();

        // ইনসার্ট কোয়েরি
        $stmt_ins = $conn->prepare("INSERT INTO teacher (tid, tname, position, mobile, sccode, ranks, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt_ins->bind_param("issssi", $new_tid, $tname, $pos, $mno, $sccode, $rank);
        $stmt_ins->execute();
        $target_tid = $new_tid;
        $stmt_ins->close();
    } else {
        // আপডেট কোয়েরি
        $stmt_upd = $conn->prepare("UPDATE teacher SET tname=?, position=?, mobile=?, ranks=? WHERE tid=? AND sccode=?");
        $stmt_upd->bind_param("ssssis", $tname, $pos, $mno, $rank, $tid, $sccode);
        $stmt_upd->execute();
        $target_tid = $tid;
        $stmt_upd->close();
    }

    // ৪. শুধুমাত্র এই নির্দিষ্ট টিচারের ডাটা ফেচ করা (কার্ড রিটার্ন করার জন্য)
    renderSingleTeacherCard($conn, $sccode, $target_tid);

} else if ($action == 0) { // DELETE
    $stmt_del = $conn->prepare("DELETE FROM teacher WHERE tid=? AND sccode=?");
    $stmt_del->bind_param("is", $tid, $sccode);
    if ($stmt_del->execute()) {
        echo "DELETED"; // জাভাস্ক্রিপ্ট এই টেক্সট দেখে ডোম থেকে কার্ড রিমুভ করবে
    }
    $stmt_del->close();
}

/**
 * একটি নির্দিষ্ট টিচারের জন্য M3 কার্ড রেন্ডার করার ফাংশন
 */
function renderSingleTeacherCard($conn, $sccode, $tid)
{
    // ফটো ডিরেক্টরি
    $photo_dir = "../../photos/staff/";

    $stmt = $conn->prepare("SELECT * FROM teacher WHERE sccode=? AND tid=?");
    $stmt->bind_param("si", $sccode, $tid);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $photo_path = $photo_dir . $tid . ".jpg";
        $display_photo = (file_exists($photo_path)) ? $photo_path : "https://eimbox.com/images/no-image.png";
        ?>
        <div class="teacher-card shadow-sm animated-fade-in" id="card-<?php echo $tid; ?>">
            <div class="staff-img-box shadow-sm">
                <img src="<?php echo $display_photo; ?>?t=<?php echo time(); ?>" alt="Profile">
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="staff-id-badge">ID: <?php echo $tid; ?></span>
                    <div class="dropdown">
                        <i class="bi bi-three-dots-vertical text-muted px-2" data-bs-toggle="dropdown"
                            style="cursor:pointer;"></i>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 m3-8px">
                            <li><a class="dropdown-item fw-bold small" onclick="editTeacher(<?php echo $tid; ?>);"><i
                                        class="bi bi-pencil-square me-2 text-primary"></i> Edit Profile</a></li>
                            <li><a class="dropdown-item fw-bold small text-danger"
                                    onclick="showDeleteConfirm(<?php echo $tid; ?>);"><i class="bi bi-trash3 me-2"></i> Remove
                                    Staff</a></li>
                        </ul>
                    </div>
                </div>
                <div class="fw-bold text-dark text-truncate mt-1" id="tname<?php echo $tid; ?>">
                    <?php echo htmlspecialchars($row["tname"]); ?></div>
                <div class="text-muted small fw-bold" id="pos<?php echo $tid; ?>">
                    <?php echo htmlspecialchars($row["position"]); ?></div>
                <div class="text-primary small fw-bold mt-1" id="mno<?php echo $tid; ?>">
                    <i class="bi bi-telephone-outbound me-1"></i><?php echo htmlspecialchars($row["mobile"]); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
$conn->close();