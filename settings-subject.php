<?php
/**
 * Subject Setup - M3-EIM-Floating Style (FAB Enabled)
 * Standards: 8px Radius | Tonal Containers | Contextual FAB | AJAX Sync
 */
$page_title = "Subject Setup";
include 'inc.php';

// সেশন ইয়ার হ্যান্ডলিং
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;
$sy_param = '%' . $current_session . '%';
?>

<style>
    body {
        background-color: #FEF7FF;
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
    }

    /* M3 App Bar (8px Bottom Radius) */
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

    /* Contextual FAB (Initial hidden) */
    .m3-fab-context {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 8px !important;
        background-color: #6750A4;
        color: white;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.4);
        z-index: 1000;
        border: none;
        transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .m3-fab-context:active {
        transform: scale(0.9);
    }

    /* M3 Components Styles */
    .m3-filter-card {
        background: #F3EDF7;
        border-radius: 8px !important;
        padding: 16px;
        margin: 12px;
        border: 1px solid #EADDFF;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-floating>.form-select,
    .form-floating>.form-control {
        border-radius: 8px !important;
        border: 2px solid #CAC4D0;
        background-color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-floating>label {
        font-size: 0.75rem;
        color: #6750A4;
        font-weight: 700;
        text-transform: uppercase;
    }

    .m3-section-label {
        font-size: 0.65rem;
        font-weight: 900;
        text-transform: uppercase;
        color: #6750A4;
        margin: 24px 0 8px 16px;
        letter-spacing: 1px;
    }

    .btn-m3-primary {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        border-radius: 8px !important;
        padding: 14px;
        font-weight: 800;
        border: none;
        width: 100%;
    }
</style>


<button class="m3-fab-context shadow-lg" id="fab-add-sub" onclick="openAddSubjectModal();">
    <i class="bi bi-plus-lg fs-2"></i>
</button>

<main class="pb-5 mt-2">


        <div class="selection-card shadow-sm">
            <?php
            $chain_param = '-c 4 -t Choose Values -u  -b View Subjects';
            include 'component/tree-ui.php';
            ?>
        </div>



        <div class="m3-section-label">Institutional Curriculum</div>

        <div id="subject-list-block" class="px-1">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-journal-plus display-1"></i>
                <p class="fw-bold mt-2">Select a class to manage syllabus</p>
            </div>
        </div>


</main>

<div class="modal fade" id="subModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-black" id="modalTitle">Subject Details</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="sub_id" value="">

                <input type="hidden" id="setup_id">

<div class="form-floating mb-3">
    <select id="subject_code" class="form-select"></select>
    <label>Select Subject</label>
</div>

<div class="form-floating mb-3">
    <select id="teacher_id" class="form-select"></select>
    <label>Assigned Teacher</label>
</div>

                <div class="row g-2 mb-4" id="marks_row">
                    <div class="col-3">
                        <div class="form-floating"><input type="number" id="ss" class="form-control"
                                value="0"><label>SUB</label></div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating"><input type="number" id="oo" class="form-control"
                                value="0"><label>OBJ</label></div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating"><input type="number" id="pp" class="form-control"
                                value="0"><label>PRA</label></div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating"><input type="number" id="fm" class="form-control"
                                value="100"><label>FULL</label></div>
                    </div>
                </div>

                <button class="btn-m3-primary shadow-sm" onclick="saveSubjectInfo();">
                    <i class="bi bi-cloud-check-fill me-2"></i> CONFIRM CHANGES
                </button>
            </div>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>

<script>
    const subModal = new bootstrap.Modal(document.getElementById('subModal'));

    function loadAssignedSubjects() {
        const slot = document.getElementById("slot-main").value;
        const session = document.getElementById("session-main").value;
        const clsf = document.getElementById("class-main").value;
        const secf = document.getElementById("section-main").value;

        alert(clsf + " | " + secf);
        if (!secf || !clsf) {
            $('#fab-add-sub').fadeOut();
            return;
        }

        // ক্লাস সিলেক্ট হলে FAB শো করবে
        $('#fab-add-sub').css('display', 'flex').hide().fadeIn(300);

        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: { rootuser: '<?php echo $rootuser; ?>', slot, session, clsf, secf, sccode: '<?php echo $sccode; ?>', tail: 2 },
            beforeSend: function () {
                $('#subject-list-block').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><br><small class="fw-bold mt-2 d-block">Configuring Modules...</small></div>');
            },
            success: function (res) {
                $("#subject-list-block").hide().html(res).fadeIn(300);
            }
        });
    }

  function openAddSubjectModal() {

    $('#setup_id').val('');

    $('#ss').val(0);
    $('#oo').val(0);
    $('#pp').val(0);
    $('#fm').val(100);

    $('#modalTitle').text('Add Subject');

    loadSubjectDropdown();
    loadTeacherDropdown();

    subModal.show();
}

   function editSubject(
    id,
    subject,
    tid,
    subj,
    obj,
    pra,
    fullmarks
) {

    $('#setup_id').val(id);

    $('#ss').val(subj);
    $('#oo').val(obj);
    $('#pp').val(pra);
    $('#fm').val(fullmarks);

    loadSubjectDropdown(subject);
    loadTeacherDropdown(tid);

    $('#modalTitle').text('Update Subject');

    subModal.show();
}
    function saveSubjectInfo() {

    const payload = {
        tail: $('#setup_id').val() ? 3 : 2,

        setup_id: $('#setup_id').val(),

        slot: $('#slot-main').val(),
        session: $('#session-main').val(),
        clsf: $('#class-main').val(),
        secf: $('#section-main').val(),

        subject: $('#subject_code').val(),
        tid: $('#teacher_id').val(),

        subj: $('#ss').val(),
        obj: $('#oo').val(),
        pra: $('#pp').val(),
        fullmarks: $('#fm').val(),

        rootuser: '<?php echo $rootuser; ?>',
        sccode: '<?php echo $sccode; ?>'
    };

    if (!payload.subject) {
        Swal.fire('Error', 'Select Subject', 'error');
        return;
    }

    $.ajax({
        type: "POST",
        url: "backend/add-edit-subject.php",
        data: payload,

        beforeSend: function () {

            $('.btn-m3-primary').html(`
                <span class="spinner-border spinner-border-sm"></span>
                Saving...
            `);

        },

        success: function (res) {

            bootstrap.Modal
                .getInstance(document.getElementById('subModal'))
                .hide();

            loadAssignedSubjects();

            Swal.fire({
                icon: 'success',
                title: 'Saved',
                timer: 1200,
                showConfirmButton: false
            });

            $('.btn-m3-primary').html(`
                <i class="bi bi-cloud-check-fill me-2"></i>
                CONFIRM CHANGES
            `);

        }
    });

}

    function toggleSubject(subId, tail) {
        const classId = document.getElementById("cls_selector").value;
        $.ajax({
            type: "POST",
            url: "backend/add-edit-subject.php",
            data: { rootuser: '<?php echo $rootuser; ?>', id: classId, tail: tail, sccode: '<?php echo $sccode; ?>', sub_id: subId },
            success: function (res) { $("#subject-list-block").html(res); }
        });
    }


    function btn_chain_function() {
        loadAssignedSubjects();
    }
</script>
<script>
    function loadSubjectDropdown(selected = '') {

    $.post(
        'backend/add-edit-subject.php',
        {
            tail: 99,
            type: 'subject',
            sccode: '<?php echo $sccode; ?>'
        },

        function(res){

            $('#subject_code').html(res);

            if(selected){
                $('#subject_code').val(selected);
            }

        }
    );

}



function loadTeacherDropdown(selected = '') {

    $.post(
        'backend/add-edit-subject.php',
        {
            tail: 99,
            type: 'teacher',
            sccode: '<?php echo $sccode; ?>'
        },

        function(res){

            $('#teacher_id').html(res);

            if(selected){
                $('#teacher_id').val(selected);
            }

        }
    );

}


function deleteSubject(id){

    Swal.fire({
        title:'Delete Subject?',
        icon:'warning',
        showCancelButton:true
    }).then((r)=>{

        if(r.isConfirmed){

            $.post(
                'backend/add-edit-subject.php',
                {
                    tail:4,
                    id:id
                },

                function(){

                    loadAssignedSubjects();

                }
            );

        }

    });

}
</script>