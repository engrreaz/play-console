<?php
/**
 * Staff Manager - M3-EIM-Floating Style
 * Standards: 8px Radius | Floating Labels | Leading Icons | Modal UI | Photo Integration
 */
$page_title = "Staff Management";
$drop_down_menu_1 = "👤 New Teacher";
include 'inc.php';


// ১. সেশন ইয়ার হ্যান্ডলিং
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

// ২. ফটো ডিরেক্টরি পাথ (Web Accessible Path)
// dirname(dirname(__DIR__)) সাধারণত রুট ফোল্ডার বোঝায়। 
// আপনার ওয়েব সার্ভারের পাথ অনুযায়ী এটি সমন্বয় করে নিন (যেমন: /photos/staff/)
$photo_dir = $BASE_PATH_URL_FILE . 'teacher/';

?>

<style>
    body {
        background-color: #FEF7FF;
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
    }

    /* M3 App Bar */
    .m3-app-bar {
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1050;
        background: #fff;
        height: 56px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .m3-app-bar .page-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1C1B1F;
        flex-grow: 1;
        margin: 0;
    }

    /* Teacher List Styling */
    .teacher-card {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        margin: 0 12px 10px;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .staff-img-box {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        /* 8px strict */
        background: #F3EDF7;
        overflow: hidden;
        margin-right: 14px;
        border: 1px solid #EADDFF;
        flex-shrink: 0;
    }

    .staff-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .staff-id-badge {
        font-size: 0.6rem;
        background: #EADDFF;
        color: #21005D;
        padding: 1px 6px;
        border-radius: 4px;
        font-weight: 800;
    }

    /* Modal Styling (M3-EIM-Floating) */
    .modal-content {
        border-radius: 8px !important;
        border: none;
        background: #fff;
    }

    .modal-header {
        border-bottom: 1px solid #F3EDF7;
        padding: 16px 20px;
    }

    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 24px 20px;
    }

    /* Custom Scrollbar for Modal Body */
    .modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #EADDFF;
        border-radius: 10px;
    }

    /* M3-EIM-Floating Input Styles (As established) */
    .m3-floating-group {
        position: relative;
        margin-bottom: 20px;
    }

    .m3-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6750A4;
        font-size: 1.2rem;
        z-index: 10;
    }

    .m3-floating-label {
        position: absolute;
        left: 44px;
        top: -10px;
        background: #fff;
        padding: 0 6px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6750A4;
        z-index: 15;
        text-transform: uppercase;
    }

    .m3-input-floating {
        width: 100%;
        height: 52px;
        padding: 12px 16px 12px 48px;
        font-size: 0.95rem;
        font-weight: 600;
        border: 2px solid #CAC4D0;
        border-radius: 8px !important;
    }

    .m3-input-floating:focus {
        border-color: #6750A4;
        outline: none;
    }

    .btn-m3-tonal {
        background: #EADDFF;
        color: #21005D;
        border-radius: 8px;
        font-weight: 800;
        border: none;
    }

    .btn-m3-primary {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: #fff;
        border-radius: 8px;
        font-weight: 800;
        border: none;
    }


    /* Drag visual polish */
    .teacher-card {
        cursor: default;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    /* When dragging */
    .sortable-chosen {
        transform: scale(1.02);
    }

    /* Floating card while moving */
    .sortable-drag {
        box-shadow:
            0 10px 20px rgba(0, 0, 0, .15),
            0 6px 6px rgba(0, 0, 0, .10);
        transform: rotate(1deg) scale(1.03);
        background: #fff;
    }

    /* Ghost placeholder */
    .sortable-ghost {
        opacity: .25;
        background: #EADDFF !important;
    }

    /* Handle feel */
    .drag-handle {
        cursor: grab;
    }

    .drag-handle:active {
        cursor: grabbing;
    }


    /* Drop position line */
    .drop-indicator {
        height: 3px;
        background: #6750A4;
        margin: 4px 12px;
        border-radius: 3px;
        transition: all .12s ease;
    }

    /* Snackbar */
    .undo-bar {
        position: fixed;
        left: 50%;
        bottom: 20px;
        transform: translateX(-50%);
        background: #323232;
        color: #fff;
        padding: 12px 18px;
        border-radius: 8px;
        display: none;
        align-items: center;
        gap: 16px;
        z-index: 9999;
        box-shadow: 0 8px 18px rgba(0, 0, 0, .25);
    }

    .undo-bar button {
        background: #EADDFF;
        border: none;
        color: #21005D;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
    }
</style>




<main class="pb-5 mt-3">
    <div class="px-3 mb-3 small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Honourable Staff Members
    </div>

    <div id="teacher-list-data">
        <?php
        $stmt = $conn->prepare("SELECT * FROM teacher WHERE sccode = ? ORDER BY sl, ranks ASC, tid DESC");
        $stmt->bind_param("s", $sccode);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()):
            $tid = $row["tid"];
            // ফটোর পাথ চেক লজিক
            $photo_path = $photo_dir . $tid . ".jpg";
            $photo_path_alt = $photo_dir . "no-img.png";

            $display_photo = (file_exists($photo_path)) ? $photo_path : $photo_path_alt;
            ?>
            <div class="teacher-card shadow-sm showDetails" data-tid="<?php echo $tid; ?>" id="card-<?php echo $tid; ?>">
                <div class="staff-img-box shadow-sm">
                    <img src="<?php echo $display_photo; ?>" alt="Profile">
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="staff-id-badge">ID: <?php echo $tid; ?></span>
                        <div class="dropdown" onclick="event.stopPropagation();">
                            <i class="bi bi-three-dots-vertical text-muted px-2 drag-handle" data-bs-toggle="dropdown"
                                style="cursor:grab;"></i>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 m3-8px">
                                <li><a class="dropdown-item fw-bold small" onclick="editTeacher(<?php echo $tid; ?>);"><i
                                            class="bi bi-pencil-square me-2 text-primary"></i> Edit Profile</a></li>
                                <li><a class="dropdown-item fw-bold small text-danger"
                                        onclick="showDeleteConfirm(<?php echo $tid; ?>);"><i class="bi bi-trash3 me-2"></i>
                                        Remove Staff</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="fw-bold text-dark text-truncate mt-1" id="tname<?php echo $tid; ?>">
                        <?php echo $row["tname"]; ?>
                    </div>
                    <div class="text-muted small fw-bold" id="pos<?php echo $tid; ?>"><?php echo $row["position"]; ?></div>
                    <div class="text-primary small fw-bold mt-1" id="mno<?php echo $tid; ?>">
                        <i class="bi bi-telephone-outbound me-1"></i><?php echo $row["mobile"]; ?>
                    </div>
                </div>
            </div>
        <?php endwhile;
        $stmt->close(); ?>
    </div>
</main>

<div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold" id="modalTitle">Register New Staff</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                    style="font-size: 0.7rem;"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tid" value="">

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Full Legal Name</label>
                    <i class="bi bi-person-badge m3-field-icon"></i>
                    <input type="text" id="tname" class="m3-input-floating" placeholder="Enter Name">
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Position / Rank</label>
                    <i class="bi bi-briefcase m3-field-icon"></i>
                    <select id="pos" class="m3-input-floating">
                        <option value="">Select Position</option>
                        <option value="Head Teacher">Head Teacher</option>
                        <option value="Asstt. Head Teacher">Asstt. Head Teacher</option>
                        <option value="Senior Teacher">Senior Teacher</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="Asstt. Teacher">Asstt. Teacher</option>
                        <option value="Office Assistant">Office Assistant</option>
                    </select>
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Mobile Number</label>
                    <i class="bi bi-phone m3-field-icon"></i>
                    <input type="tel" id="mno" class="m3-input-floating" placeholder="017xx-xxxxxx">
                </div>

                <div class="small text-muted mb-4 px-2" style="font-size: 0.65rem; line-height: 1.3;">
                    <i class="bi bi-info-circle me-1"></i> Ensure all information is accurate as per official service
                    records.
                </div>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light fw-bold px-4 m3-8px text-muted"
                    data-bs-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-m3-primary px-4 py-2 shadow-sm" onclick="saveTeacherProfile();">
                    <i class="bi bi-cloud-check-fill me-2"></i>SAVE CHANGES
                </button>
            </div>
        </div>
    </div>
</div>

<div id="undoBar" class="undo-bar">
    Order updated
    <button onclick="undoReorder()">UNDO</button>
</div>

<div style="height: 175px;"></div>

<?php include 'footer.php'; ?>

<script>
    const staffModal = new bootstrap.Modal(document.getElementById('staffModal'));

    function drop_down_menu_1() {
        if (<?php echo $permission; ?> == 3) {
            document.getElementById('tid').value = "";
            document.getElementById('modalTitle').innerText = "Register New Staff";
            document.getElementById('tname').value = "";
            document.getElementById('pos').value = "";
            document.getElementById('mno').value = "";
            staffModal.show();
        } else {
            Swal.fire('Access Denied', 'You do not have permission to add new staff members.', 'error');
        }

    }

    function editTeacher(id) {
        document.getElementById("tid").value = id;
        document.getElementById('modalTitle').innerText = "Edit Staff Profile";
        document.getElementById("tname").value = document.getElementById("tname" + id).innerText.trim();
        document.getElementById("pos").value = document.getElementById("pos" + id).innerText.trim();
        document.getElementById("mno").value = document.getElementById("mno" + id).innerText.trim();
        staffModal.show();
    }

    function saveTeacherProfile() {
        const payload = {
            tid: document.getElementById("tid").value,
            tname: document.getElementById("tname").value,
            pos: document.getElementById("pos").value,
            mno: document.getElementById("mno").value,
            rootuser: '<?php echo $sccode; ?>',
            action: 1
        };

        if (!payload.tname || !payload.pos) {
            Swal.fire('Warning', 'Name and Position are required.', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: "settings/addeditteacher.php",
            data: payload,
            success: function (res) {
                const tid = document.getElementById("tid").value;

                if (tid === "") {
                    // নতুন টিচার হলে লিস্টের সবার উপরে অ্যাড করো
                    $("#teacher-list-data").prepend(res);
                } else {
                    // এডিট হলে পুরনো কার্ডটি নতুন কার্ড দিয়ে রিপ্লেস করো
                    $("#card-" + tid).replaceWith(res);
                }

                staffModal.hide();
                Swal.fire({ title: 'Staff Record Updated', icon: 'success', timer: 1500, showConfirmButton: false });
            }
        });
    }

    // ডিলিট কনফার্মেশন (SweetAlert2 ব্যবহার করলে আরও আধুনিক হবে)
    function showDeleteConfirm(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently remove the staff member.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            cancelButtonColor: '#79747E',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTeacher(id);
            }
        });
    }

    function deleteTeacher(id) {
        $.ajax({
            type: "POST",
            url: "addeditteacher.php",
            data: { rootuser: '<?php echo $sccode; ?>', tid: id, action: 0 },
            success: function (res) {
                if (res === "DELETED") {
                    $("#card-" + id).fadeOut(300, function () { $(this).remove(); });
                    Swal.fire('Removed', 'Staff member deleted.', 'success');
                }
            }
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".showDetails").forEach(function (card) {
            card.addEventListener("click", function () {

                let teacherId = this.getAttribute("data-tid");

                if (teacherId) {
                    window.location.href = "teacher-profile.php?tid=" + teacherId;
                }

            });
        });

    });
</script>


<script>


    document.addEventListener("DOMContentLoaded", function () {

        const container = document.getElementById("teacher-list-data");

        let lastOrder = [];
        let indicator = document.createElement("div");
        indicator.className = "drop-indicator";

        function captureOrder() {
            return [...container.children].map(c => c.dataset.tid);
        }

        lastOrder = captureOrder();

        new Sortable(container, {

            animation: 180,
            easing: "cubic-bezier(.2,.8,.2,1)",

            handle: ".drag-handle",

            delay: 160,
            delayOnTouchOnly: true,
            touchStartThreshold: 6,

            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",

            fallbackTolerance: 8,

            // ⭐ Drop indicator
            onMove: function (evt) {
                let related = evt.related;
                if (!related) return;

                container.insertBefore(indicator, related);

                // ⭐ Auto scroll
                let y = evt.originalEvent.clientY;
                let winH = window.innerHeight;

                if (y > winH - 80)
                    window.scrollBy(0, 18);
                else if (y < 80)
                    window.scrollBy(0, -18);
            },

            onStart: function () {

                document.body.style.userSelect = "none";

                // ⭐ Haptic feedback
                if (navigator.vibrate)
                    navigator.vibrate(30);

                lastOrder = captureOrder();
            },

            onEnd: function () {

                document.body.style.userSelect = "";
                indicator.remove();

                // Save new order
                let order = [];

                document.querySelectorAll("#teacher-list-data .teacher-card")
                    .forEach(function (card, index) {
                        order.push({
                            tid: card.dataset.tid,
                            sl: index + 1
                        });
                    });

                $.post("settings/update-teacher-order.php", {
                    order: JSON.stringify(order)
                });

                showUndo();
            }
        });


        /* =========================
           Undo Snackbar Logic
        ========================== */

        function showUndo() {
            let bar = document.getElementById("undoBar");
            bar.style.display = "flex";

            setTimeout(() => bar.style.display = "none", 4000);
        }

        window.undoReorder = function () {

            let map = {};
            [...container.children].forEach(el => map[el.dataset.tid] = el);

            lastOrder.forEach(tid => {
                container.appendChild(map[tid]);
            });

            document.getElementById("undoBar").style.display = "none";

            // Save reverted order
            let order = [];
            document.querySelectorAll("#teacher-list-data .teacher-card")
                .forEach(function (card, index) {
                    order.push({
                        tid: card.dataset.tid,
                        sl: index + 1
                    });
                });

            $.post("settings/update-teacher-order.php", {
                order: JSON.stringify(order)
            });
        }

    });


</script>