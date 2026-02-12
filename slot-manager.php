<?php
/**
 * Slot Manager - M3-EIM Professional Standard
 * Refactored with Hero Container and Solid Modal Fix
 */
$page_title = "Slot Manager";
include_once 'inc.php';
?>

<style>
  

  

    /* ১. প্রিমিয়াম হিরো কন্টেইনার */
    .m3-hero-section {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        padding: 40px 20px 60px;
        color: #fff;
        border-radius: 0 0 12px 12px;
        margin-bottom: -30px;
        /* নিচের কন্টেন্টের সাথে ওভারল্যাপ */
    }

    .m3-hero-section h2 {
        font-weight: 900;
        letter-spacing: -1px;
        margin-bottom: 5px;
    }

    .m3-hero-section p {
        opacity: 0.8;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* ২. মেইন লিস্ট কন্টেইনার */
    .content-wrapper {
        padding: 0 15px;
        position: relative;
        z-index: 10;
    }

    /* ৩. স্লট কার্ড ডিজাইন */
    .slot-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 12px;
        border: 1px solid var(--m3-outline-variant);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    .slot-card:hover {
        border-color: var(--m3-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.1);
    }

    .slot-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1C1B1F;
        margin-bottom: 6px;
    }

    .slot-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .m3-chip {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        background: var(--m3-surface-container);
        color: var(--m3-primary);
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* ৪. ফ্লোটিং অ্যাকশন বাটন (FAB) */
    .fab-slot {
        position: fixed;
        bottom: 90px;
        right: 25px;
        width: 64px;
        height: 64px;
        background: var(--m3-primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.4);
        cursor: pointer;
        z-index: 1000;
        transition: 0.3s;
    }

    .fab-slot:hover {
        transform: scale(1.05) rotate(90deg);
    }

    /* ৫. মডাল ফিক্স (স্বচ্ছতা দূর করার জন্য) */
    .modal-content {
        background-color: #FFFFFF !important;
        /* মডাল আর স্বচ্ছ দেখাবে না */
        border-radius: 8px !important;
        border: none !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2) !important;
    }

    .m3-input-group {
        margin-bottom: 15px;
    }

    .m3-field-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--m3-primary);
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .m3-input {
        border-radius: 8px !important;
        border: 2px solid var(--m3-outline-variant) !important;
        padding: 12px 16px !important;
        font-weight: 600 !important;
        background: #fff !important;
    }

    .m3-input:focus {
        border-color: var(--m3-primary) !important;
        box-shadow: none !important;
    }
</style>

<div class="m3-hero-section">
    <div class="container">
        <h2>Slot Manager</h2>
        <p>Define institutional branches, merit systems, and time protocols.</p>
        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 px-3 py-1 rounded-pill mt-2">
            <span class="small fw-bold">Institution ID: <?php echo $sccode; ?></span>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="container">
        <div id="slot-list-wrapper">
            <div class="text-center py-5">
                <div class="spinner-border text-light" style="width: 3rem; height: 3rem;"></div>
            </div>
        </div>
    </div>
</div>

<div class="fab-slot" onclick="openSlotModal()">
    <i class="bi bi-plus-lg fs-3"></i>
</div>

<div class="modal fade" id="slotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black" id="modalTitle">Manage Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="slotForm">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="m3-input-group">
                        <label class="m3-field-label">Slot Display Name</label>
                        <input type="text" name="slotname" id="m_slotname" class="form-control m3-input"
                            placeholder="e.g. School Section" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="m3-field-label">Merit Calculation</label>
                            <select name="merit" id="m_merit" class="form-select m3-input">
                                <option value="0">Total Marks</option>
                                <option value="1">GPA System</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="m3-field-label">Parent Identity</label>
                            <select name="parents" id="m_parents" class="form-select m3-input">
                                <option value="DOSO">Guardian Info (DOSO)</option>
                                <option value="FM">FM Style</option>
                            </select>
                        </div>
                    </div>

                    <div class="m3-input-group mt-3">
                        <label class="m3-field-label">Report Template ID</label>
                        <input type="text" name="cus_report" id="m_report" class="form-control m3-input"
                            placeholder="temp-01">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="m3-field-label text-success">Required In Time</label>
                            <input type="time" name="reqin" id="m_reqin" class="form-control m3-input">
                        </div>
                        <div class="col-6">
                            <label class="m3-field-label text-danger">Required Out Time</label>
                            <input type="time" name="reqout" id="m_reqout" class="form-control m3-input">
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3">
                        <div class="m3-field-label mb-2">Translation Settings</div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="trans_name_eng" id="m_eng" value="1">
                            <label class="form-check-label small fw-bold" for="m_eng">Enable English Translation</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="trans_name_ben" id="m_ben" value="1">
                            <label class="form-check-label small fw-bold" for="m_ben">Enable Bengali Translation</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 mt-4 rounded-pill fw-bold shadow">
                        SAVE CONFIGURATION
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div style="height:75px;"></div>

<?php include 'footer.php'; ?>

<script>
    const sModal = new bootstrap.Modal(document.getElementById('slotModal'));

    function fetchSlots() {
        $.post('ajax/fetch_slots.php', function (data) {
            $('#slot-list-wrapper').hide().html(data).fadeIn(400);
        });
    }

    $(document).ready(fetchSlots);

    function openSlotModal() {
        $('#slotForm')[0].reset();
        $('#edit_id').val('');
        $('#modalTitle').text('Add New Slot');
        sModal.show();
    }

    // ৩. এডিট ফেচ
    function editSlot(id) {
        $.post('ajax/get_slot_item.php', { id: id }, function (res) {
            const d = JSON.parse(res);
            $('#edit_id').val(d.id);
            $('#m_slotname').val(d.slotname);
            $('#m_merit').val(d.merit);
            $('#m_parents').val(d.parents);
            $('#m_report').val(d.cus_report);
            $('#m_reqin').val(d.reqin);
            $('#m_reqout').val(d.reqout);
            $('#m_eng').prop('checked', d.trans_name_eng == 1);
            $('#m_ben').prop('checked', d.trans_name_ben == 1);
            $('#modalTitle').text('Edit: ' + d.slotname);
            sModal.show();
        });
    }

    // ৪. সেভ লজিক
    $('#slotForm').submit(function (e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('SAVING...');

        $.post('ajax/save_slot.php', $(this).serialize(), function (res) {
            if (res.trim() === 'success') {
                sModal.hide();
                fetchSlots();
                Swal.fire({
                    icon: 'success',
                    title: 'Slot Updated',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            submitBtn.prop('disabled', false).text('SAVE CONFIGURATION');
        });
    });

    // ৫. ডিলিট লজিক
    function deleteSlot(id) {
        Swal.fire({
            title: 'Delete Slot?',
            text: "This will affect associated class routines!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ajax/delete_slot.php', { id: id }, () => {
                    fetchSlots();
                    Swal.fire('Deleted!', '', 'success');
                });
            }
        });
    }
</script>