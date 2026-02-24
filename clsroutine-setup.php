<?php
$page_title = 'Class Routing';
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. ইনপুট প্যারামিটার হ্যান্ডলিং (Secure)
$cls2 = $_COOKIE['chain-class'] ?? '';
$sec2 = $_COOKIE['chain-section'] ?? '';
?>

<style>
    /* M3 Surface Background */



    .loading-block {
        text-align: center;
        padding: 40px;
        color: #6750A4;
    }
</style>


<style>
    /* Material 3 Modal Design */
    .m3-modal-container {
        border-radius: 28px !important;
        background-color: #FEF7FF !important;
        border: none;
    }

    .m3-icon-circle-tonal {
        width: 48px;
        height: 48px;
        background-color: #EADDFF;
        color: #21005D;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Input Card Style */
    .m3-input-card {
        background: #F3EDF7;
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid #E7E0EC;
    }

    .m3-input-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #6750A4;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 2px;
    }

    .m3-minimal-select {
        border: none;
        background: transparent;
        width: 100%;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
        padding: 4px 0;
        cursor: pointer;
    }

    /* M3 Day Chips Styling */
    .m3-day-chip-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .m3-day-input {
        display: none;
    }

    .m3-day-label {
        padding: 6px 12px;
        border-radius: 8px;
        background: #F3EDF7;
        color: #49454F;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid #CAC4D0;
        cursor: pointer;
        transition: 0.2s all;
    }

    .m3-day-input:checked+.m3-day-label {
        background: #6750A4;
        color: white;
        border-color: #6750A4;
        box-shadow: 0 2px 4px rgba(103, 80, 164, 0.2);
    }

    /* Primary Button */
    .btn-m3-primary {
        background-color: #6750A4 !important;
        color: white !important;
        border-radius: 100px !important;
        font-weight: 700 !important;
        border: none !important;
    }
</style>

<main class="">


    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>


        <?php
        $chain_param = '-c 4 -t Choose Options -u -b View List -h ';
        include 'component/tree-ui.php';
        ?>

        <div class="m3-section-title">Active Timetable</div>
        <div id="block" class="px-2">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-clock-history display-1"></i>
                <p class="mt-2 fw-bold small">Select Class & Section to start</p>
            </div>
        </div>

    <?php else: ?>
        <div class="container text-center py-5">
            <i class="bi bi-shield-lock display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">Access restricted to Administrators.</p>
        </div>
    <?php endif; ?>

</main>


<div class="modal fade" id="routineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-container shadow-lg">

            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-circle-tonal">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-black m-0 text-dark" id="modalHeaderTitle">Edit Routine</h6>
                        <p class="small text-muted mb-0">Modify period assignment</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <input type="hidden" id="edit_id">
                <input type="hidden" id="edit_period">
                <input type="hidden" id="edit_wday">

                <div class="m3-input-card mb-3">
                    <label class="m3-input-label">ACADEMIC SUBJECT</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-book text-primary me-2"></i>
                        <select id="edit_subcode" class="m3-minimal-select">
                            <option value="0">Choose Subject</option>
                            <?php
                            // subjects টেবিল থেকে নির্দিষ্ট ক্যাটাগরি অনুযায়ী ডাটা ফেচ করা হচ্ছে
                            $sql_s = "SELECT subcode, subject FROM subjects WHERE sccategory = '$sctype' ORDER BY subject ASC";
                            $res_s = $conn->query($sql_s);

                            if ($res_s && $res_s->num_rows > 0) {
                                while ($s = $res_s->fetch_assoc()) {
                                    // subcode-কে ভ্যালু হিসেবে রাখা হয়েছে
                                    echo "<option value='{$s['subcode']}'>{$s['subcode']} &mdash; {$s['subject']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="m3-input-card mb-4">
                    <label class="m3-input-label">ASSIGNED TEACHER</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        <select id="edit_tid" class="m3-minimal-select">
                            <option value="0">Choose Teacher</option>
                            <?php
                            $res_t = $conn->query("SELECT tid, tname FROM teacher WHERE sccode='$sccode' ORDER BY ranks, tname");
                            while ($t = $res_t->fetch_assoc())
                                echo "<option value='{$t['tid']}'>{$t['tname']}</option>";
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="m3-input-label mb-2 px-1">APPLY FOR DAYS</label>
                    <div class="m3-day-chip-grid">
                        <?php
                        $dayNames = [1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat'];
                        foreach ($dayNames as $val => $name): ?>
                            <input type="checkbox" name="days[]" value="<?= $val ?>" id="day_<?= $val ?>"
                                class="m3-day-input">
                            <label for="day_<?= $val ?>" class="m3-day-label"><?= $name ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button class="btn btn-m3-primary w-100 py-3 shadow-sm" onclick="saveRoutine();">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> UPDATE TIMETABLE
                </button>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>

<script>
    // ১. পেজ রিলোড নেভিগেশন
    function goo() {
        const cls = document.getElementById("cls").value;
        const sec = document.getElementById("sec").value;
        window.location.href = `clsroutine-setup.php?cls=${encodeURIComponent(cls)}&sec=${encodeURIComponent(sec)}`;
    }

    // ২. রুটিন ফেচ করার AJAX
    function btn_chain_function() {
        const cls = document.getElementById("class-main").value;
        const sec = document.getElementById("section-main").value;

        if (!cls || !sec) {
            Swal.fire('Input Required', 'Please select both Class and Section.', 'warning');
            return;
        }

        const infor = `rootuser=<?php echo $rootuser; ?>&cls=${cls}&sec=${sec}&id=0&action=1`;

        $.ajax({
            type: "POST",
            url: "backend/show-routine.php",
            data: infor,
            beforeSend: function () {
                $('#block').html('<div class="loading-block"><div class="spinner-border text-primary"></div><br><span class="small fw-bold mt-2 d-block">Generating Grid...</span></div>');
            },
            success: function (html) {
                $("#block").html(html);
            }
        });
    }




    setTimeout(function () {

        if ($('#class-main').val() && $('#section-main').val()) {
            btn_chain_function();
        }

    }, 100);

</script>


<script>
    let routineModal;

    document.addEventListener("DOMContentLoaded", function () {
        routineModal = new bootstrap.Modal(
            document.getElementById('routineModal')
        );
    });

    function openRoutineModal(period, wday, dayName, subCode, tid, id) {

        $('#edit_id').val(id);
        $('#edit_period').val(period);
        $('#edit_wday').val(wday);
        $('#edit_subcode').val(subCode);
        $('#edit_tid').val(tid);

        $('#modalHeaderTitle').text(`Period ${period} | ${dayName}`);

        $('input[name="days[]"]').prop('checked', false);

        // 🟢 current day auto check
        $('input[name="days[]"][value="' + wday + '"]').prop('checked', true);

        routineModal.show();
    }

    function saveRoutine() {

        let days = [];
        $('input[name="days[]"]:checked').each(function () {
            days.push($(this).val());
        });

        $.post("backend/save-routine.php", {
            id: $('#edit_id').val(),
            period: $('#edit_period').val(),
            days: days,
            sub: $('#edit_subcode').val(),
            tid: $('#edit_tid').val(),
            cls: $('#class-main').val(),
            sec: $('#section-main').val(),
        }, function () {

            routineModal.hide();
            refreshPeriod($('#edit_period').val());

        });

    }

    function refreshPeriod(period) {

        $.post("backend/show-routine.php", {
            cls: $('#class-main').val(),
            sec: $('#section-main').val(),
            period: period,
            refresh: 1
        }, function (html) {

            let block = $(html).find("#period-" + period).html();
            $("#period-" + period).html(block);

        });
    }



    function toggleExpand(period) {

        $.post("backend/show-routine.php", {
            cls: $('#class-main').val(),
            sec: $('#section-main').val(),
            expand: 1,
            period: period
        }, function (html) {

            let block = $(html).find("#period-" + period).html();
            $("#period-" + period).html(block);

        });

    }

</script>