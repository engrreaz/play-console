<?php
$page_title = "Student List";
include 'inc.php';
include 'datam/datam-stprofile.php';

$classname = $_GET['cls'] ?? '';
$sectionname = $_GET['sec'] ?? '';
$sy_param = "%" . $sy . "%";

// ডাটা ফেচিং (JOIN ব্যবহার করে সব তথ্য একসাথে আনা)
$sql0 = "SELECT si.*, s.stnameeng, s.stnameben, s.gender, s.religion as s_rel, s.guarmobile as s_mob , s.guaremail
         FROM sessioninfo si 
         JOIN students s ON si.stid = s.stid 
         WHERE si.sessionyear LIKE ? AND si.sccode = ? AND si.classname = ? AND si.sectionname = ? 
         ORDER BY si.rollno ASC";
$stmt = $conn->prepare($sql0);
$stmt->bind_param("siss", $sy_param, $sccode, $classname, $sectionname);
$stmt->execute();
$stslist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-tonal: #EADDFF;
    }

    body {
        background: #FEF7FF;
    }

    /* Hero Section */
    .hero-st-list {
        background: linear-gradient(135deg, var(--m3-primary) 0%, #4F378B 100%);
        margin: 12px;
        padding: 24px 20px;
        border-radius: 16px;
        color: white;
    }

    /* Card Styling */
    .st-card {
        background: white;
        border-radius: 12px;
        margin: 0 12px 10px;
        border: 1px solid #E7E0EC;
        overflow: hidden;
        transition: 0.3s;
    }

    .st-info-row {
        display: flex;
        align-items: center;
        padding: 12px;
        cursor: pointer;
    }

    /* ২-লাইনের আইকন গ্রিড */
    .action-drawer {
        display: none;
        background: #F7F2FA;
        padding: 16px;
        border-top: 1px dashed var(--m3-tonal);
    }

    .icon-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    .icon-grid.row-1 {
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #49454F;
        gap: 4px;
        border: none;
        background: none;
        padding: 0;
    }

    .btn-action i {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: 0.2s;
    }

    .btn-action span {
        font-size: 0.52rem;
        font-weight: 800;
        text-transform: uppercase;
        text-align: center;
    }

    .btn-action:active i {
        transform: scale(0.9);
    }

    /* থিম কালার */
    .c-comm i {
        background: #EADDFF;
        color: #21005D;
    }

    /* যোগাযোগ ও প্রোফাইল */
    .c-edu i {
        background: #D2E3FC;
        color: #1967D2;
    }

    /* শিক্ষা ও পেমেন্ট */
</style>


<style>
    /* টেমপ্লেট আইটেম স্টাইল */
    .m3-template-item {
        display: flex;
        align-items: center;
        width: 100%;
        border: 1px solid #E7E0EC;
        background: white;
        padding: 10px 16px;
        border-radius: 12px;
        transition: 0.2s;
        text-align: left;
        gap: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #1D1B20;
    }

    .m3-template-item:active {
        transform: scale(0.98);
        background: #F3EDF7;
    }

    .m3-template-item i {
        font-size: 1.2rem;
    }

    /* আইকন কালার কোড */
    .c-in {
        color: #2E7D32;
    }

    .c-out {
        color: #1976D2;
    }

    .c-abs {
        color: #D32F2F;
    }

    .c-pay {
        color: #F57C00;
    }

    .c-res {
        color: #7B1FA2;
    }

    .c-not {
        color: #455A64;
    }

    .c-rep {
        color: #00796B;
    }

    .c-cus {
        color: #6750A4;
    }

    /* স্ক্রলবার হাইড */
    .scroll-hide::-webkit-scrollbar {
        display: none;
    }
</style>

<style>
    /* মডালের বডিকে স্ক্রলযোগ্য করার মেইন ক্লাস */
    .m3-scrollable-body {
        max-height: 65vh; /* স্ক্রিনের উচ্চতার ৬৫% এর বেশি হবে না */
        overflow-y: auto;  /* কন্টেন্ট বেশি হলে স্ক্রলবার আসবে */
        overflow-x: hidden;
        padding-right: 8px; /* স্ক্রলবারের জন্য জায়গা রাখা */
    }

    /* কাস্টম এম৩ স্ক্রলবার স্টাইল (Chrome/Safari/Edge) */
    .m3-scrollable-body::-webkit-scrollbar {
        width: 5px; /* চিকন স্ক্রলবার */
    }

    .m3-scrollable-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .m3-scrollable-body::-webkit-scrollbar-thumb {
        background: #EADDFF; /* Tonal Container Color */
        border-radius: 10px;
    }

    .m3-scrollable-body::-webkit-scrollbar-thumb:hover {
        background: #6750A4; /* Primary Color on Hover */
    }
</style>

<main class="pb-5">
    <div class="hero-st-list shadow-sm">
        <h5 class="fw-black m-0"><?= $classname ?> &mdash; <?= $sectionname ?></h5>
        <div class="small opacity-75">Total <?= count($stslist) ?> Students • Session <?= $sessionyear ?></div>
    </div>

    <div class="list-container mt-3">
        <?php foreach ($stslist as $st):
            $stid = $st['stid'];
            $pth = student_profile_image_path($stid);
            ?>
            <div class="st-card shadow-sm" id="card-<?= $stid ?>">
                <div class="st-info-row" onclick="toggleDrawer(<?= $stid ?>)">
                    <img src="<?= $pth ?>" class="rounded-3 me-3" style="width:50px; height:50px; object-fit:cover;"
                        onerror="this.src='https://eimbox.com/students/noimg.jpg';">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark small text-truncate"><?= $st['stnameeng'] ?></div>
                        <div class="text-dark small text-truncate"><?= $st['stnameben'] ?></div>
                        <div class="text-muted" style="font-size: 0.7rem;">ID: <?= $stid ?> | Roll:
                            <b><?= $st['rollno'] ?></b>
                        </div>
                    </div>
                    <button class="btn btn-light btn-sm rounded-3 border py-2 px-3"
                        onclick="event.stopPropagation(); openEditModal(<?= htmlspecialchars(json_encode($st)) ?>)">
                        <i class="bi bi-pencil-square text-primary"></i>
                    </button>
                </div>

                <div class="action-drawer" id="drawer-<?= $stid ?>">

                    <div class="icon-grid row-1 c-comm">
                        <!-- Call -->
                        <a href="make-call.php?mobilenumber=<?= $st['s_mob'] ?>" class="btn-action">
                            <i class="bi bi-telephone-fill"></i>
                        </a>

                        <!-- SMS Modal -->
                        <button class="btn-action"
                            onclick="prepareSMS('<?= $stid ?>', '<?= $st['stnameeng'] ?>', '<?= $st['s_mob'] ?>')">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </button>

                        <!-- Notify -->
                        <a href="notification.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-bell-fill"></i>
                        </a>

                        <!-- Email Modal -->
                        <button class="btn-action"
                            onclick="prepareEmail('<?= $stid ?>', '<?= $st['stnameeng'] ?>', '<?= $st['guaremail'] ?>')">
                            <i class="bi bi-envelope-at-fill"></i>
                        </button>

                        <!-- Profile -->
                        <a href="student-my-profile.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-person-bounding-box"></i>
                        </a>
                    </div>

                    <div class="icon-grid c-edu">
                        <!-- Attendance -->
                        <a href="stguarattnd.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-calendar-check-fill"></i>
                        </a>

                        <!-- Payment -->
                        <a href="stfinancedetails.php?id=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-wallet2"></i>
                        </a>

                        <!-- Result -->
                        <a href="stguarresult.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </a>

                        <!-- Co-curricular -->
                        <a href="stcca.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-trophy-fill"></i>
                        </a>

                        <!-- Miscellaneous -->
                        <a href="st-misc-report.php?stid=<?= $stid ?>" class="btn-action">
                            <i class="bi bi-three-dots"></i>
                        </a>
                    </div>
                </div>



            </div>
        <?php endforeach; ?>
    </div>
</main>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black text-primary"><i class="bi bi-person-gear me-2"></i>Edit Student Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body p-4">
                <form id="editForm">
                    <input type="hidden" name="stid" id="m_stid">

                    <!-- Guardian Mobile -->
                    <div class="m3-floating-group mb-3">
                        <i class="bi bi-telephone-fill m3-field-icon"></i>
                        <input type="text" class="m3-input-floating" name="guarmobile" id="m_mobile" placeholder=" ">
                        <label class="m3-floating-label">GUARDIAN MOBILE NO.</label>
                    </div>

                    <!-- Gender & Religion -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <i class="bi bi-gender-ambiguous m3-field-icon"></i>
                                <select class="m3-select-floating" name="gender" id="m_gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label class="m3-floating-label">GENDER</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <i class="bi bi-bookmark-heart m3-field-icon"></i>
                                <select class="m3-select-floating" name="religion" id="m_religion">
                                    <option value="Islam">Islam</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Christian">Christian</option>
                                    <option value="Buddist">Buddist</option>
                                </select>
                                <label class="m3-floating-label">RELIGION</label>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Status -->
                    <div class="p-3 mb-4" style="background: #F3EDF7; border-radius: 12px; border: 1px solid #EADDFF;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-check2-square me-2"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">ACADEMIC STATUS</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Enable or Disable this record</div>
                            </div>
                            <div class="form-check form-switch">

                                <input class="form-check-input" type="checkbox" name="status" id="m_status"
                                    style="width: 40px; height: 20px;">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow"
                        onclick="updateStudent()">
                        SAVE INFORMATION
                    </button>
                </form>
            </div>


        </div>
    </div>
</div>




<!-- SMS Modal -->
<div class="modal fade" id="sharedSmsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down" style=" width:90%; margin:auto;">
        <div class="modal-content  border-0 shadow-lg" style="border-radius: 16px; background: #FEF7FF; max-height:75%;">

            <div class="modal-header border-0 px-4 pt-4">
                <div>
                    <h5 class="fw-black text-primary m-0"><i class="bi bi-chat-square-dots-fill me-2"></i>Send SMS</h5>
                    <div id="sms_st_name" class="small fw-bold text-muted mt-1"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="d-flex align-items-center mb-4 p-2 bg-white rounded-4 border border-light shadow-sm">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div id="sms_st_mobile" class="fw-black text-primary" style="font-size: 0.9rem;"></div>
                        <div class="text-muted" style="font-size: 0.65rem;">Recipient Contact Number</div>
                    </div>
                </div>

                <div class="template-list-container scroll-hide mb-4" style="max-height: 250px; overflow-y: auto;">
                    <label class="small fw-bold text-secondary mb-2 d-block">CHOOSE TEMPLATE</label>

                    <div class="list-group gap-2 border-0">
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('in')">
                            <i class="bi bi-box-arrow-in-right c-in"></i> <span>Present (In-Time)</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('out')">
                            <i class="bi bi-box-arrow-left c-out"></i> <span>Present (Out-Time)</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('absent')">
                            <i class="bi bi-person-x c-abs"></i> <span>Absent Alert</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('pay')">
                            <i class="bi bi-cash-stack c-pay"></i> <span>Payment Receipt</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('result')">
                            <i class="bi bi-file-earmark-bar-graph c-res"></i> <span>Result Published</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('notice')">
                            <i class="bi bi-megaphone c-not"></i> <span>General Notice</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('report')">
                            <i class="bi bi-calendar-check c-rep"></i> <span>Monthly Report</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setSmsTemplate('custom')">
                            <i class="bi bi-pencil-square c-cus"></i> <span>Custom Message</span>
                        </button>
                    </div>
                </div>

                <form id="smsForm">
                    <input type="hidden" name="stid" id="sms_stid">
                    <input type="hidden" name="mobileno" id="sms_mobile_val">

                    <div class="m3-floating-group mb-4">
                        <textarea class="m3-input-floating shadow-sm" name="message" id="sms_msg"
                            style="height: 120px; border-radius: 16px; border: 2px solid #EADDFF;" placeholder=" "
                            required></textarea>
                        <label class="m3-floating-label">MESSAGE CONTENT</label>
                        <div class="text-end mt-1">
                            <small id="char_count" class="text-muted" style="font-size: 10px;">0 characters</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-send-fill me-2"></i>SEND SMS NOW
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="sharedEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down"  style=" width:90%; margin:auto;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: #FEF7FF;  max-height:75%;">

            <div class="modal-header border-0 px-4 pt-4">
                <div>
                    <h5 class="fw-black text-primary m-0"><i class="bi bi-envelope-paper-heart-fill me-2"></i>Send Email
                    </h5>
                    <div id="email_st_name" class="small fw-bold text-muted mt-1"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="template-list-container scroll-hide mb-4"
                    style="max-height: 200px; overflow-y: auto; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                    <label class="small fw-bold text-secondary mb-2 d-block">SELECT EMAIL TYPE</label>

                    <div class="list-group gap-2 border-0">
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('result')">
                            <i class="bi bi-file-earmark-bar-graph c-res"></i> <span>Academic Result</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('dues')">
                            <i class="bi bi-exclamation-octagon c-abs"></i> <span>Outstanding Dues</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('meeting')">
                            <i class="bi bi-people-fill c-in"></i> <span>Meeting Request</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('congratulations')">
                            <i class="bi bi-stars c-pay"></i> <span>Congratulations</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('notice')">
                            <i class="bi bi-megaphone c-not"></i> <span>Official Notice</span>
                        </button>
                        <button type="button" class="m3-template-item" onclick="setEmailTemplate('custom')">
                            <i class="bi bi-pencil-square c-cus"></i> <span>Draft from Scratch</span>
                        </button>
                    </div>
                </div>

                <form id="emailForm">
                    <input type="hidden" name="stid" id="email_stid">

                    <div class="m3-floating-group mb-3">
                        <input type="email" class="m3-input-floating" name="to_email" id="email_val" placeholder=" "
                            required>
                        <label class="m3-floating-label">RECIPIENT EMAIL</label>
                    </div>

                    <div class="m3-floating-group mb-3">
                        <input type="text" class="m3-input-floating" name="subject" id="email_sub" placeholder=" "
                            required>
                        <label class="m3-floating-label">SUBJECT LINE</label>
                    </div>

                    <div class="m3-floating-group mb-4">
                        <textarea class="m3-input-floating shadow-sm" name="body" id="email_body"
                            style="height: 150px; border-radius: 16px; border: 2px solid #EADDFF;" placeholder=" "
                            required></textarea>
                        <label class="m3-floating-label">EMAIL BODY CONTENT</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-send-check-fill me-2"></i>SEND EMAIL
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>

<script>
    // ১. ড্রয়ার টগল লজিক (অ্যানিমেশনসহ)
    function toggleDrawer(id) {
        document.querySelectorAll('.action-drawer').forEach(el => {
            if (el.id !== 'drawer-' + id) $(el).slideUp(200);
        });
        $('#drawer-' + id).slideToggle(300);
    }

    // ২. মডাল ডাটা পপুলেট করা
    function openEditModal(data) {
        $("#m_stid").val(data.stid);
        $("#m_mobile").val(data.s_mob);
        $("#m_gender").val(data.gender);
        $("#m_religion").val(data.s_rel);
        $("#m_status").prop('checked', data.status == 1);

        var myModal = new bootstrap.Modal(document.getElementById('editModal'));
        myModal.show();
    }

    // ৩. ডাটা আপডেট AJAX
    function updateStudent() {
        const formData = {
            stid: $("#m_stid").val(),
            guarmobile: $("#m_mobile").val(),
            gender: $("#m_gender").val(),
            religion: $("#m_religion").val(),
            status: $("#m_status").is(":checked") ? 1 : 0
        };

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "backend/update-student-info.php",
            type: "POST",
            data: formData,
            success: function (res) {
                if (res.trim() === "success") {
                    Swal.fire({ icon: 'success', title: 'Saved!', timer: 800, showConfirmButton: false })
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Update Failed: ' + res, 'error');
                }
            }
        });
    }
</script>


<script>
    // ১. SMS মডাল প্রস্তুত করা
    function prepareSMS(stid, name, mobile) {
        $("#sms_stid").val(stid);
        $("#sms_mobile_val").val(mobile);
        $("#sms_st_name").text(name);
        $("#sms_st_mobile").text(mobile);
        $("#sms_msg").val(''); // আগের টেক্সট ক্লিয়ার করা

        var smsModal = new bootstrap.Modal(document.getElementById('sharedSmsModal'));
        smsModal.show();
    }

    // ২. Email মডাল প্রস্তুত করা
    function prepareEmail(stid, name, email) {
        $("#email_stid").val(stid);
        $("#email_val").val(email || '');

        var emailModal = new bootstrap.Modal(document.getElementById('sharedEmailModal'));
        emailModal.show();
    }

    // ৩. AJAX সাবমিশন
    $(document).ready(function () {
        // SMS সাবমিট
        $("#smsForm").on('submit', function (e) {
            e.preventDefault();
            const btn = $(this).find('button');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Sending...');

            $.post("backend/send-sms.php", $(this).serialize(), function (res) {
                Swal.fire({ icon: 'success', title: 'SMS Sent!', text: res, timer: 1500, showConfirmButton: false });
                bootstrap.Modal.getInstance(document.getElementById('sharedSmsModal')).hide();
                btn.prop('disabled', false).html('<i class="bi bi-send me-2"></i>SEND NOW');
            });
        });

        // Email সাবমিট
        $("#emailForm").on('submit', function (e) {
            e.preventDefault();
            const btn = $(this).find('button');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

            $.post("backend/send-email.php", $(this).serialize(), function (res) {
                Swal.fire({ icon: 'success', title: 'Email Sent!', text: res, timer: 1500, showConfirmButton: false });
                bootstrap.Modal.getInstance(document.getElementById('sharedEmailModal')).hide();
                btn.prop('disabled', false).html('<i class="bi bi-send-check me-2"></i>SEND EMAIL');
            });
        });
    });
</script>

<script>
    function setSmsTemplate(type) {
        const name = $("#sms_st_name").text(); // শিক্ষার্থীর নাম
        let msg = "";

        switch (type) {
            case 'in': msg = `Dear Parent, your child ${name} has entered school at ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}. Thanks.`; break;
            case 'out': msg = `Dear Parent, your child ${name} has left school at ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}. Thanks.`; break;
            case 'absent': msg = `Dear Parent, your child ${name} is ABSENT today. Please ensure attendance. Thanks.`; break;
            case 'pay': msg = `Dear Parent, we have received payment for ${name}. Digital receipt is available on portal. Thanks.`; break;
            case 'result': msg = `Dear Parent, the academic result of ${name} has been published. Please check the profile. Thanks.`; break;
            case 'notice': msg = `Important Notice: School will remain closed tomorrow for special occasion. Regards, Principal.`; break;
            case 'report': msg = `Dear Parent, monthly performance report of ${name} is ready. Kindly visit school on coming Sat.`; break;
            case 'custom': msg = ""; $("#sms_msg").focus(); break;
        }

        $("#sms_msg").val(msg);
        updateCharCount();
    }

    // ক্যারেক্টার কাউন্টার
    $("#sms_msg").on('input', updateCharCount);

    function updateCharCount() {
        const len = $("#sms_msg").val().length;
        $("#char_count").text(len + " characters | " + Math.ceil(len / 160) + " SMS");
    }
</script>


<script>
    // ১. ইমেল মডাল ওপেন করার সময় শিক্ষার্থীর নাম সেট করা
    function prepareEmail(stid, name, email) {
        $("#email_stid").val(stid);
        $("#email_st_name").text(name); // নাম ডিসপ্লে করা
        $("#email_val").val(email || '');
        $("#email_sub").val('');
        $("#email_body").val('');

        var emailModal = new bootstrap.Modal(document.getElementById('sharedEmailModal'));
        emailModal.show();
    }

    // ২. ইমেল টেমপ্লেট সেট করার ফাংশন
    function setEmailTemplate(type) {
        const name = $("#email_st_name").text();
        let subject = "";
        let body = "";

        switch (type) {
            case 'result':
                subject = `Academic Performance Report - ${name}`;
                body = `Dear Parent,\n\nWe are pleased to inform you that the recent examination results for your child, ${name}, have been published. You can view the detailed marksheet on our online portal.\n\nRegards,\nAcademic Department.`;
                break;
            case 'dues':
                subject = `Urgent: Outstanding Fee Clearance - ${name}`;
                body = `Dear Parent,\n\nThis is a friendly reminder regarding the outstanding academic fees for ${name}. We kindly request you to clear the dues by the end of this week to avoid any inconvenience.\n\nThank you for your cooperation.`;
                break;
            case 'meeting':
                subject = `Parent-Teacher Meeting Invitation`;
                body = `Dear Parent,\n\nYou are cordially invited to a Parent-Teacher Meeting to discuss the academic progress of ${name}. \n\nDate: Coming Saturday\nTime: 10:00 AM\nVenue: School Meeting Hall.`;
                break;
            case 'congratulations':
                subject = `Congratulations on Outstanding Performance!`;
                body = `Dear Parent,\n\nWe are thrilled to share that ${name} has performed exceptionally well in the recent activities. We appreciate the hard work and dedication shown by the student.\n\nKeep it up!`;
                break;
            case 'notice':
                subject = `Important School Notice`;
                body = `Dear Parents,\n\nPlease take note of the attached notice regarding the upcoming school events and schedule changes. \n\nBest regards,\nOffice of the Principal.`;
                break;
            case 'custom':
                subject = ""; body = ""; $("#email_sub").focus();
                break;
        }

        $("#email_sub").val(subject);
        $("#email_body").val(body);
    }
</script>