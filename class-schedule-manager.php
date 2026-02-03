<?php
/**
 * Class Schedule Manager - M3-EIM Ultimate Redesign
 * Standards: Floating Labels, 8px/16px Hybrid Radius, Mesh Hero
 */
$page_title = "Class Schedule Manager";
include_once 'inc.php';

// Filter Data
$sessions = $conn->query("SELECT DISTINCT sessionyear FROM classschedule WHERE sccode='$sccode' ORDER BY sessionyear DESC");
$slots_list = $conn->query("SELECT DISTINCT slots FROM classschedule WHERE sccode='$sccode' ORDER BY slots ASC");
?>

<style>
    /* Mesh Hero Section */


    .m3-input-floating:focus {
        border-color: var(--m3-primary);
        background: #fff;
    }

    /* Schedule List Item */
    .schedule-item-m3 {
        background: var(--m3-surface);
        border-radius: 12px;
        padding: 16px;
        margin: 0 12px 10px;
        border: 1px solid #F0F0F0;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 4px var(--m3-shadow);
        transition: 0.2s;
    }

    .schedule-item-m3:active {
        transform: scale(0.98);
    }

    .period-tonal-badge {
        width: 48px;
        height: 48px;
        background: var(--m3-primary-tonal);
        color: var(--m3-on-tonal);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Duration Chip */
    .duration-pill {
        font-size: 0.65rem;
        background: #E8F5E9;
        color: #2E7D32;
        padding: 4px 10px;
        border-radius: 100px;
        font-weight: 800;
    }

    /* FAB */
    .m3-fab {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: var(--m3-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.4);
        z-index: 1000;
    }

    .m3-section-title {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--m3-primary);
        margin: 20px 16px 8px;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .m3-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--m3-primary-tonal);
    }

    /* Modal Styling */
    .modal-m3 {
        border-radius: 12px !important;
        border: none;
    }

    .duration-display-box {
        background: #F3EDF7;
        border: 2px dashed var(--m3-primary-tonal);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        color: var(--m3-primary);
    }
</style>

<main class="pb-5">
    <div class="hero-container">
        <h4 class="fw-bold m-0">Schedule Manager</h4>
        <p class="small opacity-75 m-0">Define periods and sync with routine</p>
    </div>

    <div class="px-3">
        <div class="row g-2">
            <div class="col-6">
                <div class="m3-floating-group">
                    <i class="bi bi-calendar3 m3-field-icon"></i>
                    <label class="m3-floating-label">Session</label>
                    <select id="session-main" class="m3-select-floating" onchange="fetchSchedule()">
                        <option value="">Year</option>
                        <?php while ($s = $sessions->fetch_assoc()): ?>
                            <option value="<?= $s['sessionyear']; ?>"><?= $s['sessionyear']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="m3-floating-group">
                    <i class="bi bi-clock m3-field-icon"></i>
                    <label class="m3-floating-label">Slot</label>
                    <select id="slot-main" class="m3-select-floating" onchange="fetchSchedule()">
                        <option value="">Slot</option>
                        <?php while ($sl = $slots_list->fetch_assoc()): ?>
                            <option value="<?= $sl['slots']; ?>"><?= $sl['slots']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <h3 class="m3-section-title">Timeline</h3>

    <div id="schedule-list-container">
        <div class="text-center py-5 opacity-25">
            <i class="bi bi-view-stacked display-4"></i>
            <p class="fw-bold mt-2">Select filters to view schedule</p>
        </div>
    </div>

    <div class="m3-fab" onclick="openScheduleModal()">
        <i class="bi bi-plus-lg fs-4"></i>
    </div>
</main>

<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 px-2">
            <div class="modal-header border-0">
                <h5 class="fw-bold" id="modalTitle">New Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <form id="scheduleForm">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="m3-floating-group">
                        <i class="bi bi-hash m3-field-icon"></i>
                        <label class="m3-floating-label">Period Type</label>
                        <select name="period" id="m_period" class="m3-select-floating" required>
                            <option value="0">Interval / Break</option>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                <option value="<?= $i; ?>">Period <?= $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <i class="bi bi-clock-fill m3-field-icon text-success"></i>
                                <label class="m3-floating-label">Start</label>
                                <input type="time" name="timestart" id="m_start" class="m3-input-floating"
                                    onchange="calculateDuration()" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <i class="bi bi-clock-fill m3-field-icon text-danger"></i>
                                <label class="m3-floating-label">End</label>
                                <input type="time" name="timeend" id="m_end" class="m3-input-floating"
                                    onchange="calculateDuration()" required>
                            </div>
                        </div>
                    </div>

                    <div class="duration-display-box mb-4">
                        <span class="small text-uppercase fw-bold">Duration</span><br>
                        <span id="duration_display" class="fs-3 fw-black">0</span>
                        <span class="fw-bold">MINUTES</span>
                        <input type="hidden" name="duration" id="m_duration">
                    </div>

                    <button type="submit" class="btn-m3-submit w-100 border-0 text-white p-3 fw-bold"
                        style="background: var(--m3-primary-gradient); border-radius: 12px;">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> SAVE PERIOD
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const schedModal = new bootstrap.Modal('#scheduleModal');

    function calculateDuration() {
        const start = $('#m_start').val();
        const end = $('#m_end').val();
        if (start && end) {
            const startTime = new Date(`2000-01-01T${start}`);
            const endTime = new Date(`2000-01-01T${end}`);
            let diff = (endTime - startTime) / 60000;
            if (diff < 0) diff += 1440;
            $('#m_duration').val(diff);
            $('#duration_display').text(diff);
        }
    }

    function fetchSchedule() {
        const session = $('#session-main').val();
        const slot = $('#slot-main').val();
        if (!session || !slot) return;

        $('#schedule-list-container').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        $.post('ajax/fetch_class_schedule.php', { session, slot }, data => {
            $('#schedule-list-container').html(data);
        });
    }

    function openScheduleModal() {
        $('#scheduleForm')[0].reset();
        $('#edit_id').val('');
        $('#modalTitle').text('Add Period');
        schedModal.show();
    }

    // Form Submit handling (Already exists in your logic)


    // ১. এডিট ফাংশন
    function editSchedule(id) {
        $.post('ajax/get_schedule_item.php', { id: id }, function (data) {
            const d = JSON.parse(data);

            // মোডাল ফিল্ডে ডাটা সেট করা
            $('#edit_id').val(d.id);
            $('#m_period').val(d.period);
            $('#m_slot').val(d.slots);
            $('#m_session').val(d.sessionyear);
            $('#m_start').val(d.timestart);
            $('#m_end').val(d.timeend);

            // মোডাল টাইটেল আপডেট ও ডিউরেশন ক্যালকুলেশন
            $('#modalTitle').text('Update Period Settings');
            calculateDuration();

            schedModal.show();
        });
    }

    // ২. ডিলিট ফাংশন (SweetAlert2 ব্যবহার করে)
    function deleteSchedule(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This period will be removed permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            cancelButtonColor: '#79747E',
            confirmButtonText: 'Yes, delete it!',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ajax/delete_class_schedule.php', { id: id }, function (res) {
                    if (res.trim() === 'success') {
                        fetchSchedule(); // লিস্ট রিফ্রেশ করা
                        Swal.fire({
                            title: 'Deleted!',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }


    // ফরম সাবমিশন (POST মেথড ব্যবহার করে)
$('#scheduleForm').on('submit', function(e) {
    e.preventDefault(); // পেজ রিফ্রেশ বন্ধ করা

    const btn = $(this).find('button[type="submit"]');
    const originalBtnText = btn.html();

    // বাটন ডিজেবল করা ও লোডিং স্টেট দেখানো
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> SAVING...');

    // সেশন এবং স্লট এর ভ্যালু ইনপুট ফিল্ড থেকে নেওয়া (যদি হিডেন থাকে)
    const formData = $(this).serialize() + 
                     '&sessionyear=' + $('#session-main').val() + 
                     '&slots=' + $('#slot-main').val();

    $.post('ajax/save_class_schedule.php', formData, function(res) {
        if (res.trim() === 'success') {
            schedModal.hide(); // মোডাল বন্ধ করা
            fetchSchedule();   // লিস্ট রিফ্রেশ করা
            
            // সুইট অ্যালার্ট সাকসেস মেসেজ
            Swal.fire({
                icon: 'success',
                title: 'Successfully Saved',
                text: 'Schedule has been updated.',
                timer: 1500,
                showConfirmButton: false,
                borderRadius: '16px'
            });
        } else {
            Swal.fire('Error!', res, 'error');
        }
        
        // বাটন আগের অবস্থায় ফিরিয়ে আনা
        btn.prop('disabled', false).html(originalBtnText);
    });
});


</script>