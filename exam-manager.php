<?php 
include 'inc.php'; 
// সেশন এবং ক্লাসের ফিল্টার ডাটা
$sessions = $conn->query("SELECT DISTINCT syear  FROM sessionyear WHERE sccode='$sccode' ORDER BY syear DESC");
$classes = $conn->query("SELECT DISTINCT classname FROM examlist WHERE sccode='$sccode' ORDER BY classname ASC");
?>

<style>
    /* Hero & Exam Specific Styles */
  
    
    .exam-card {
        background: #fff; border-radius: 16px; border: 1px solid #F0F0F0;
        padding: 16px; margin-bottom: 12px; transition: 0.3s;
        display: flex; align-items: center; justify-content: space-between;
    }
    .exam-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }

    /* Circular Progress for Exam Card */
    .circular-progress-sm {
        position: relative; width: 60px; height: 60px;
    }
    .circular-progress-sm svg { transform: rotate(-90deg); width: 60px; height: 60px; }
    .circular-progress-sm circle { fill: none; stroke-width: 5; stroke-linecap: round; }
    .bg-ring { stroke: #F3EDF7; }
    .progress-ring { stroke: var(--m3-primary); stroke-dasharray: 157; transition: 1s; }

    .exam-info { flex-grow: 1; margin-left: 15px; }
    .exam-title { font-weight: 800; color: #1C1B1F; font-size: 1rem; margin-bottom: 2px; }
    .exam-meta { font-size: 0.75rem; color: #79747E; font-weight: 600; }
    
    .status-badge {
        font-size: 0.65rem; padding: 4px 10px; border-radius: 100px; font-weight: 800;
    }
</style>

<main class="pb-5">
    <div class="hero-container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-black m-0">Exam Manager</h3>
                <p class="opacity-75 small">Schedule and manage academic assessments</p>
            </div>
            <i class="bi bi-journal-check display-4 opacity-25"></i>
        </div>
        
        <div class="row g-2 mt-3">
            <div class="col-6">
                <div class="m3-floating-group mb-0">
                    <label class="m3-floating-label" style="background: transparent; color: white; border:none;">Session</label>
                    <select id="f_session" class="m3-select-floating bg-white" onchange="fetchExams()">
                        <option value="">All Years</option>
                        <?php while($s = $sessions->fetch_assoc()) echo "<option>{$s['syear']}</option>"; ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="m3-floating-group mb-0">
                    <label class="m3-floating-label" style="background: transparent; color: white; border:none;">Class</label>
                    <select id="f_class" class="m3-select-floating bg-white" onchange="fetchExams()">
                        <option value="">All Class</option>
                        <?php while($c = $classes->fetch_assoc()) echo "<option>{$c['classname']}</option>"; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3">
        <div class="m3-section-title d-flex justify-content-between align-items-center">
            <span>Academic Exam List</span>
            <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="openExamModal()">
                <i class="bi bi-plus-lg me-1"></i> New Exam
            </button>
        </div>

        <div id="exam-list-container">
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-search display-1"></i>
                <p class="fw-bold mt-2">Loading Exams...</p>
            </div>
        </div>
    </div>
</main>

<div class="m3-fab" onclick="openExamModal()">
    <i class="bi bi-plus-lg fs-4"></i>
</div>

<div class="modal fade" id="examModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-m3 px-2" style="border-radius: 28px;">
            <div class="modal-header border-0">
                <h5 class="fw-black" id="modalTitle">Create New Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <form id="examForm">
                    <input type="hidden" name="id" id="exam_id">
                    
                    <div class="m3-floating-group">
                        <i class="bi bi-type m3-field-icon"></i>
                        <label class="m3-floating-label">Exam Title</label>
                        <input type="text" name="examtitle" id="m_title" class="m3-input-floating" placeholder="e.g. Annual Exam 2026" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Class</label>
                                <select name="classname" id="m_class" class="m3-select-floating" style="padding-left:15px;" required>
                                    <option>Six</option><option>Seven</option><option>Eight</option><option>Nine</option><option>Ten</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="m3-floating-group">
                                <label class="m3-floating-label">Exam Type</label>
                                <select name="exam_type" id="m_type" class="m3-select-floating" style="padding-left:15px;">
                                    <option value="PE">Public</option><option value="IE">Internal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="m3-floating-group">
                        <i class="bi bi-calendar-event m3-field-icon"></i>
                        <label class="m3-floating-label">Start Date</label>
                        <input type="date" name="datestart" id="m_date" class="m3-input-floating" required>
                    </div>

                    <button type="submit" class="btn-m3-submit w-100 mt-2">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> SAVE EXAM SETTINGS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="routineModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 28px; background: #FEF7FF;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-black"><i class="bi bi-calendar2-week me-2"></i>Exam Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="routine-display-body">
                </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
const examModal = new bootstrap.Modal('#examModal');
$('#f_session').val(<?= $sessionyear ?>);

function fetchExams() {
    const session = $('#f_session').val();
    const cls = $('#f_class').val();
    $('#exam-list-container').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
    
    $.post('ajax/fetch_exams.php', { session, cls }, data => {
        $('#exam-list-container').html(data);
    });
}

function openExamModal() {
    $('#examForm')[0].reset();
    $('#exam_id').val('');
    $('#modalTitle').text('Create New Exam');
    examModal.show();
}

function deleteExam(id) {
    Swal.fire({
        title: 'Delete Exam?',
        text: "This will remove all routine data linked to this exam!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#B3261E',
        confirmButtonText: 'Yes, Delete',
        borderRadius: '16px'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('ajax/delete_exam.php', { id }, () => {
                fetchExams();
                Swal.fire('Deleted!', 'Exam has been removed.', 'success');
            });
        }
    });
}

$(document).ready(() => fetchExams());


function viewRoutine(examName, cls) {
    // রুটিন দেখানোর জন্য একটি কন্টেইনার বা মোডাল ওপেন করা
    $('#routineModal').modal('show');
    
    // লোডিং স্পিনার দেখানো
    $('#routine-display-body').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

    // AJAX কল
    $.post('ajax/fetch_exams_routine.php', { 
        exam_name: examName, 
        cls: cls 
    }, function(data) {
        $('#routine-display-body').html(data);
    });
}
</script>