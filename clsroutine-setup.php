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

<main class="pb-5">


    <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher'): ?>


        <?php
        $chain_param = '-c 4 -t Choose Options -u -b View List -h ';
        include 'component/tree-ui.php';
        ?>

        <div class="section-label">Active Timetable</div>
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

<div style="height: 70px;"></div>


<div class="modal fade" id="routineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-black" id="modalHeaderTitle">Edit Routine</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="edit_id">
                <input type="hidden" id="edit_period">
                <input type="hidden" id="edit_wday">

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Subject</label>
                    <select id="edit_subcode" class="m3-select-floating">
                        <option value="0">Select Subject</option>
                        <?php
                        $sql_s = "SELECT subcode, subject FROM subjects WHERE sccategory='$sctype' ORDER BY subject";
                        $sql_s = "SELECT subject from subsetup where sccode='$sccode' and sessionyear like '$sessionyear_param' and classname='$cls2' and sectionname='$sec2'  ";
                        $res_s = $conn->query($sql_s);
                        while ($s = $res_s->fetch_assoc())
                            echo "<option value='{$s['subject']}'>{$s['subject']}</option>";
                        ?>
                    </select>
                </div>

                <div class="m3-floating-group">
                    <label class="m3-floating-label">Assigned Teacher</label>
                    <select id="edit_tid" class="m3-select-floating">
                        <option value="0">Select Teacher</option>
                        <?php
                        $sql_t = "SELECT tid, tname FROM teacher WHERE sccode='$sccode' ORDER BY ranks, tname";
                        $res_t = $conn->query($sql_t);
                        while ($t = $res_t->fetch_assoc())
                            echo "<option value='{$t['tid']}'>{$t['tname']}</option>";
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label>কবে কবে এই আপডেট হবে?</label><br>
                    <div class="day-selector">
                        <label><input type="checkbox" name="days[]" value="1"> Sun</label>
                        <label><input type="checkbox" name="days[]" value="2"> Mon</label>
                        <label><input type="checkbox" name="days[]" value="3"> Tue</label>
                        <label><input type="checkbox" name="days[]" value="4"> Wed</label>
                        <label><input type="checkbox" name="days[]" value="5"> Thu</label>
                        <label><input type="checkbox" name="days[]" value="6"> Fri</label>
                        <label><input type="checkbox" name="days[]" value="7"> Sat</label>
                    </div>
                </div>

                <button class="btn btn-primary w-100 py-3 m3-8px fw-bold shadow-sm" onclick="saveRoutine();">
                    <i class="bi bi-cloud-check-fill me-2"></i> UPDATE ROUTINE
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