<?php
$page_title = "Slot Manager";
include_once 'inc.php';
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-surface: #FEF7FF;
        --m3-surface-container: #F3EDF7;
        --m3-surface-container-high: #ECE6F0;
        --m3-outline: #79747E;
        --m3-outline-variant: #CAC4D0;
    }



    /* ১. ম্যাটেরিয়াল ৩ হিরো কন্টেইনার (Tonal Palette) */
    .m3-hero-card {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        padding: 48px 24px;
        border-radius: 0 0 32px 32px;
        margin-bottom: -40px;
        position: relative;
        z-index: 1;
    }

    .m3-hero-card h1 {
        font-weight: 900;
        font-size: 2.2rem;
        letter-spacing: -1px;
    }

    /* ২. কন্টেন্ট লেআউট */
    .m3-container {
        padding: 0 16px;
        position: relative;
        z-index: 10;
    }

    /* ৩. M3 আউটলাইন্ড কার্ড ডিজাইন */
    .m3-slot-card {
        background: #FFFFFF;
        border-radius: 16px;
        /* M3 Medium Shape */
        padding: 20px;
        margin-bottom: 12px;
        border: 1px solid var(--m3-outline-variant);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.2s cubic-bezier(0, 0, 0.2, 1);
    }

    .m3-slot-card:hover {
        background-color: var(--m3-surface-container);
        border-color: var(--m3-primary);
    }

    .m3-headline {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1C1B1F;
        margin-bottom: 8px;
    }

    /* ৪. M3 টোনাল চিপস */
    .m3-tonal-chip {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        background: var(--m3-surface-container-high);
        color: var(--m3-on-primary-container);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ৫. মডার্ন FAB (M3 Squircle) */
    .m3-fab {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 56px;
        height: 56px;
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 16px;
        /* M3 FAB Radius */
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        z-index: 1000;
        border: none;
        transition: 0.3s;
    }

    .m3-fab:hover {
        background-color: var(--m3-primary);
        color: white;
        transform: scale(1.1);
    }

    /* ৬. M3 ডায়ালগ (মডাল) ডিজাইন */
    .modal-content {

        border-radius: 16px !important;
        /* M3 Large Shape */
        border: none !important;
        padding: 8px;
    }

    .m3-input-field {
        background: #FFFFFF !important;
        border: 1px solid var(--m3-outline) !important;
        border-radius: 12px !important;
        padding: 14px 16px !important;
        font-size: 1rem !important;
        color: #1C1B1F !important;
    }

    .m3-input-field:focus {
        border: 2px solid var(--m3-primary) !important;
        box-shadow: none !important;
    }

    .m3-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--m3-primary);
        margin-left: 4px;
        margin-bottom: 4px;
        display: block;
    }

    /* ৭. সেভ বাটন (Pill Shape) */
    .btn-m3-primary {
        background-color: var(--m3-primary) !important;
        color: white !important;
        border-radius: 100px !important;
        /* Pill Shape */
        padding: 12px 24px !important;
        font-weight: 700 !important;
        border: none !important;
    }

    /* ৮. সুইচ ডিজাইন */
    .form-switch .form-check-input {
        width: 3em !important;
        height: 1.5em !important;
        background-color: var(--m3-outline-variant);
        border: none;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--m3-primary);
    }
</style>

<style>
    /* ১. মডার্ন ম্যাটেরিয়াল ৩ হিরো কন্টেইনার */
    .m3-hero-modern {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        color: white;
        padding: 60px 24px 100px;
        border-radius: 0 0 48px 48px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    /* ২. ব্যাকগ্রাউন্ড ডেকোরেশন (Subtle Shapes) */
    .m3-hero-modern::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .m3-hero-modern::after {
        content: "\F3EE";
        /* Bootstrap Icon code for grid */
        font-family: "bootstrap-icons";
        position: absolute;
        bottom: -20px;
        left: 20px;
        font-size: 8rem;
        color: rgba(255, 255, 255, 0.05);
        transform: rotate(-15deg);
    }

    /* ৩. মেইন কন্টেন্ট স্টাইলিং */
    .hero-icon-box {
        width: 72px;
        height: 72px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        /* M3 Squircle */
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .m3-hero-modern h1 {
        font-weight: 900;
        font-size: 2.5rem;
        letter-spacing: -1.5px;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .m3-hero-modern p {
        font-size: 0.95rem;
        font-weight: 500;
        opacity: 0.85;
        max-width: 300px;
        margin: 0 auto;
        line-height: 1.4;
    }

    /* ৪. স্টাইলিশ আইডি ব্যাজ */
    .inst-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 0, 0, 0.2);
        padding: 8px 16px;
        border-radius: 100px;
        margin-top: 25px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-family: 'monospace';
        letter-spacing: 1px;
        font-weight: 800;
        font-size: 0.75rem;
    }

    .inst-id-badge i {
        color: #D0BCFF;
        /* M3 Light Purple */
    }
</style>

<div class="m3-hero-modern shadow-lg">
    <div class="container">
        <div class="hero-icon-box">
            <i class="bi bi-grid-1x2-fill"></i>
        </div>

        <h1>Slot Manager</h1>
        <p>Define institutional branches, merit systems, and time protocols.</p>

        <div class="inst-id-badge">
            <i class="bi bi-shield-lock-fill"></i>
            <span>INSTITUTION ID: <?php echo $sccode; ?></span>
        </div>
    </div>
</div>

<div class="m3-container">
    <div class="container">
        <div id="slot-list-wrapper" class="mt-5">
            <div class="text-center py-5">
                <div class="spinner-grow text-primary" role="status"></div>
            </div>
        </div>
    </div>
</div>

<button class="m3-fab shadow" onclick="openSlotModal()">
    <i class="bi bi-plus-lg fs-3"></i>
</button>

<div class="modal fade" id="slotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black text-dark" id="modalTitle">Manage Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="slotForm">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label class="m3-label">SLOT DISPLAY NAME</label>
                        <input type="text" name="slotname" id="m_slotname" class="form-control m3-input-field"
                            placeholder="e.g. Morning Shift" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="m3-label">MERIT SYSTEM</label>
                            <select name="merit" id="m_merit" class="form-select m3-input-field">
                                <option value="0">Total Marks</option>
                                <option value="1">GPA System</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="m3-label">PARENT STYLE</label>
                            <select name="parents" id="m_parents" class="form-select m3-input-field">
                                <option value="DOSO">Guardian Info</option>
                                <option value="FM">FM Style</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <label class="m3-label text-success">REQUIRED IN</label>
                            <input type="time" name="reqin" id="m_reqin" class="form-control m3-input-field">
                        </div>
                        <div class="col-6">
                            <label class="m3-label text-danger">REQUIRED OUT</label>
                            <input type="time" name="reqout" id="m_reqout" class="form-control m3-input-field">
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-white bg-opacity-50 rounded-4">
                        <label class="m3-label mb-3">LANGUAGE TRANSLATION</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="trans_name_eng" id="m_eng" value="1">
                            <label class="form-check-label small fw-bold ms-2" for="m_eng">English Translation</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="trans_name_ben" id="m_ben" value="1">
                            <label class="form-check-label small fw-bold ms-2" for="m_ben">Bengali Translation</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-m3-primary w-100 py-3 mt-4 shadow-sm">
                        SAVE CONFIGURATION
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include_once 'footer.php'; ?>

<script>
    const sModal = new bootstrap.Modal(document.getElementById('slotModal'));

    // ১. স্লট লিস্ট ফেচ করা
    function fetchSlots() {
        $.post('ajax/fetch_slots.php', function (data) {
            $('#slot-list-wrapper').hide().html(data).fadeIn(400);
        });
    }

    $(document).ready(fetchSlots);

    // ২. নতুন স্লট যোগ করার জন্য মডাল ওপেন
    function openSlotModal() {
        $('#slotForm')[0].reset();
        $('#edit_id').val('');
        $('#modalTitle').text('Add New Slot');
        sModal.show();
    }

    // ৩. এডিট করার জন্য ডাটা ফেচ করা
    function editSlot(id) {
        $.post('ajax/get_slot_item.php', { id: id }, function (res) {
            const d = JSON.parse(res);
            $('#edit_id').val(d.id);
            $('#m_slotname').val(d.slotname);
            $('#m_merit').val(d.merit);
            $('#m_parents').val(d.parents);
            $('#m_reqin').val(d.reqin);
            $('#m_reqout').val(d.reqout);
            $('#m_eng').prop('checked', d.trans_name_eng == 1);
            $('#m_ben').prop('checked', d.trans_name_ben == 1);
            $('#modalTitle').text('Edit Slot: ' + d.slotname);
            sModal.show();
        });
    }

    // ৪. স্লট সেভ/আপডেট করা
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
                    title: 'Configuration Saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire('Error', res, 'error');
            }
            submitBtn.prop('disabled', false).text('SAVE CONFIGURATION');
        });
    });

    // ৫. স্লট ডিলিট করা
    function deleteSlot(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Associated routines might be affected!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ajax/delete_slot.php', { id: id }, function (res) {
                    fetchSlots();
                    Swal.fire('Deleted!', '', 'success');
                });
            }
        });
    }
</script>