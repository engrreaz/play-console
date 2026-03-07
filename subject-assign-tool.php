<?php
$page_title = "Assign Subject";
include 'inc.php';
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-secondary-container: #F3EDF7;
        --m3-tertiary-container: #eea142;
    }


    .selection-card {
        background-color: var(--m3-secondary-container);
        padding: 20px;
        border-radius: 0 0 32px 32px;
        margin-bottom: 24px;
    }

    /* Student Card Styling */
    .student-card {
        background: white;
        border-radius: 16px;
        border: 2px solid transparent;
        padding: 16px;
        transition: 0.3s;
        cursor: pointer;
        position: relative;
    }

    .student-card.selected {
        border-color: var(--m3-primary);
        background-color: var(--m3-primary-container);
    }

    .student-card .check-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
        color: var(--m3-primary);
    }

    .student-card.selected .check-icon {
        display: block;
    }

    .st-avatar {
        width: 48px;
        height: 48px;
        background: var(--m3-secondary-container);
        color: var(--m3-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
    }

    /* Subject Item Styling */
    .subject-item {
        background: var(--m3-secondary-container);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 8px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .subject-item.selected {
        background: var(--m3-primary);
        color: white;
    }

    /* FAB */
    .fab-assign {
        position: fixed;
        bottom: 80px;
        right: 30px;
        text-align: center;
        border-radius: 50% !important;
        width: 50px;
        height: 50px;
        background: var(--m3-tertiary-container);
        color: #291800;
        font-weight: 900;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        /* Initially hidden */
        z-index: 1000;
        font-size: 24px;
        ;
    }
</style>

<style>
    /* Fourth Subject Container */
    .fourth-sub-container {
        background-color: #FFFBFF;
        /* Surface */
        border: 1px solid #EADDFF;
        border-radius: 24px;
        background-image: linear-gradient(to bottom, #FFFBFF, var(--m3-secondary-container));
    }

    /* M3 Filter Chips Grid */
    .m3-chip-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* Individual Chip Style */
    .m3-filter-chip {
        padding: 8px 16px;
        border-radius: 8px;
        /* M3 small shape */
        background: #fff;
        border: 1px solid var(--m3-outline);
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: 0.2s cubic-bezier(0.2, 0, 0, 1);
        color: #49454F;
    }

    .m3-filter-chip:hover {
        background-color: var(--m3-secondary-container);
    }

    /* Selected State (Tonal Tertiary) */
    .m3-filter-chip.selected {
        background-color: #7D5260;
        /* Tertiary Color */
        color: #FFFFFF;
        border-color: transparent;
        box-shadow: 0 2px 6px rgba(125, 82, 96, 0.3);
    }

    .m3-filter-chip.selected .chip-icon::before {
        content: "\F272";
        /* bi-check-lg */
    }

    .m3-icon-circle {
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<style>
    /* ফোর্থ সাবজেক্ট চিপের ডিফল্ট স্টাইল */
    .m3-filter-chip {
        padding: 8px 16px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid #79747E;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        color: #49454F;
    }

    /* সিলেক্ট করার পর যে স্টাইল হবে (Tertiary Tonal) */
    .m3-filter-chip.selected {
        background-color: #7D5260 !important;
        /* M3 Tertiary Color */
        color: #FFFFFF !important;
        border-color: transparent;
        box-shadow: 0 2px 8px rgba(125, 82, 96, 0.4);
    }

    /* আইকন পরিবর্তন (সিলেক্ট হলে প্লাস থেকে টিক চিহ্ন) */
    .m3-filter-chip.selected .chip-icon::before {
        content: "\F272" !important;
        /* bi-check-lg */
    }
</style>

<style>
    .btn-m3-text {
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: 0.2s;
    }

    .btn-m3-text:hover {
        background: rgba(103, 80, 164, 0.08);
    }

    #student-count {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--m3-primary);
    }

    .checkAllNone {
        background: #291800;
        color: white;
        border-radius: 100px;
        padding: 6px 12px;
        font-size: 12px;
    }
</style>


<main class="pb-5">
    <div class="selection-card shadow-sm">
        <?php
        $chain_param = '-c 6 -t Choose Parameters -u -b Get Student List -h ';
        include 'component/tree-ui.php';
        ?>
        <div class="d-flex" id="blblock" hidden>
            <div class="flex-grow-1 py-2" id="student-count"></div>
            <button class="checkAllNone nav-pills" onclick="syncCardSelection()">Select All Students</button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row g-3" id="student-grid">
        </div>
    </div>

    <button class="btn fab-assign border-0 shadow-lg" id="fab-assign" onclick="openSubjectModal()">
        <i class="bi bi-journal-plus me-2"></i>
    </button>
</main>

<div class="modal fade" id="subjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg bg-surface">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-black m-0">Select Subjects</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4" id="subject-list">
            </div>
            <div class="modal-footer border-0 p-4">
                <button class="btn btn-dark rounded-pill w-100 py-3 fw-black" onclick="processAssignment()">
                    CONFIRM & ASSIGN
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // শুরুতে ভেরিয়েবলগুলো গ্লোবাল হিসেবে ডিফাইন করুন


    window.selectedStudents = [];
    window.selectedSubjects = [];
    window.selectedFourthSubjects = [];
    window.card = [];

    // ১. মেইন সাবজেক্ট টোগল
    function toggleSubject(subcode) {
        const card = $(`.sub-${subcode}`);
        const icon = card.find('.check-box-icon');

        card.toggleClass('selected');

        // সব জায়গায় window. ব্যবহার নিশ্চিত করুন
        if (card.hasClass('selected')) {
            icon.removeClass('bi-circle').addClass('bi-check-circle-fill');
            if (!window.selectedSubjects.includes(subcode)) {
                window.selectedSubjects.push(subcode);
            }
        } else {
            icon.removeClass('bi-check-circle-fill').addClass('bi-circle');
            window.selectedSubjects = window.selectedSubjects.filter(id => id !== subcode);
        }
        console.log("Global Main Update:", window.selectedSubjects);
    }

    // ২. ফোর্থ সাবজেক্ট চিপ টোগল
    function toggleFourthSubject(subcode) {
        const chip = $(`.chip-${subcode}`);
        const icon = chip.find('.chip-icon');

        chip.toggleClass('selected');

        if (chip.hasClass('selected')) {
            icon.removeClass('bi-plus-lg').addClass('bi-check-lg');
            if (!window.selectedFourthSubjects.includes(subcode)) {
                window.selectedFourthSubjects.push(subcode);
            }
        } else {
            icon.removeClass('bi-check-lg').addClass('bi-plus-lg');
            window.selectedFourthSubjects = window.selectedFourthSubjects.filter(id => id !== subcode);
        }
        console.log("Global Fourth Update:", window.selectedFourthSubjects);
    }

    // ৩. সেভ করার ফাংশন (অবশ্যই window. দিয়ে চেক করবেন)
    function processAssignment() {
        console.log("Attempting to save:", window.selectedSubjects); // চেক করার জন্য

        if (window.selectedSubjects.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Wait!',
                text: 'Please select at least one main subject.',
                confirmButtonColor: '#6750A4'
            });
            return;
        }

        Swal.fire({
            title: 'Updating Subjects...',
            text: 'Please wait while we process the bulk assignment.',
            allowOutsideClick: false, // বাইরে ক্লিক করলে যেন বন্ধ না হয়
            didOpen: () => {
                Swal.showLoading(); // স্পিনার দেখাবে
            }
        });

        $.post('backend/assign-subject-logic.php', {
            stids: window.selectedStudents,
            subjects: window.selectedSubjects.join(','),
            fourth_subject: window.selectedFourthSubjects.join(','),
            slot: $("#slot-main").val(),
            session: $("#session-main").val()
        }, function (res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Success', text: res.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                bootstrap.Modal.getInstance(document.getElementById('subjectModal')).hide();
                btn_chain_function();
            }
        }, 'json');
    }
</script>

<script>


    // ১. স্টুডেন্ট লিস্ট ফেচ করা
    function btn_chain_function() {
        let params = {
            slot: $("#slot-main").val(),
            session: $("#session-main").val(),
            class: $("#class-main").val(),
            section: $("#section-main").val()
        };

        $("#student-grid").html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>');

        $.post('backend/fetch-students-subjects.php', params, function (res) {
            $("#student-grid").html(res);
            $("#fab-assign").fadeIn();
            selectedStudents = []; // Reset selection
        });
    }

    // ২. স্টুডেন্ট কার্ড টোগল
    function toggleStudent(stid) {
        $(`.card-${stid}`).toggleClass('selected');
        if (selectedStudents.includes(stid)) {
            selectedStudents = selectedStudents.filter(id => id !== stid);

        } else {
            selectedStudents.push(stid);
        }
        let count = selectedStudents.length;
        $('#student-count').text(count + ' Student(s) Selected');
    }

    // ৩. সাবজেক্ট মডাল ওপেন করা
    function openSubjectModal() {
        if (selectedStudents.length === 0) {
            Swal.fire('Error', 'Please select at least one student', 'error');
            return;
        }

        let params = {
            slot: $("#slot-main").val(),
            session: $("#session-main").val(),
            class: $("#class-main").val(),
            section: $("#section-main").val(),
            stids: selectedStudents,
        };

        $.post('backend/fetch-available-subjects.php', params, function (res) {
            $("#subject-list").html(res);
            new bootstrap.Modal(document.getElementById('subjectModal')).show();
            // selectedSubjects = [];
        });
    }

    // ৪. সাবজেক্ট টোগল






    // ৫. ফাইনাল অ্যাসাইনমেন্ট
    function processAssignmentx() {
        // ১. ভ্যালিডেশন: কমপক্ষে একটি মেইন সাবজেক্ট সিলেক্ট করা আছে কিনা
        if (selectedSubjects.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Wait!',
                text: 'Please select at least one main subject.',
                confirmButtonColor: '#6750A4'
            });
            return;
        }

        // ২. ডেটা প্রসেসিং (অ্যারে থেকে কমা-সেপারেটেড স্ট্রিং তৈরি)
        let mainSubjectsString = selectedSubjects.join(',');
        let fourthSubjectsString = selectedFourthSubjects.join(',');

        Swal.fire({
            title: 'Updating Subjects...',
            text: 'Please wait while we process the bulk assignment.',
            allowOutsideClick: false, // বাইরে ক্লিক করলে যেন বন্ধ না হয়
            didOpen: () => {
                Swal.showLoading(); // স্পিনার দেখাবে
            }
        });

        // ৩. ব্যাকএন্ডে ডেটা পাঠানো
        $.post('backend/assign-subject-logic.php', {
            stids: selectedStudents,          // নির্বাচিত শিক্ষার্থীদের আইডি (Array)
            subjects: mainSubjectsString,     // মেইন সাবজেক্ট কোডসমূহ (String)
            fourth_subject: fourthSubjectsString, // ফোর্থ সাবজেক্ট কোডসমূহ (String)
            slot: $("#slot-main").val(),
            session: $("#session-main").val()
        }, function (res) {
            if (res.status === 'success') {
                // ৪. সাকসেস ফিডব্যাক
                Swal.fire({
                    icon: 'success',
                    title: 'Assignment Complete',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });

                // ৫. মডাল বন্ধ করা এবং লিস্ট রিফ্রেশ করা
                const modalEl = document.getElementById('subjectModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                // ডাটাবেস আপডেট হওয়ার পর স্টুডেন্ট লিস্ট আবার লোড করা
                btn_chain_function();

                // সিলেকশন ক্লিয়ার করা (ঐচ্ছিক)
                selectedStudents = [];
                selectedSubjects = [];
                selectedFourthSubjects = [];
            } else {
                Swal.fire('Error', res.message || 'Something went wrong!', 'error');
            }
        }, 'json').fail(function () {
            Swal.fire('Error', 'Server communication failed!', 'error');
        });
    }

</script>

<script>
    function syncCardSelection() {
        let rawText = document.getElementById('cardList').innerText;
        let cardArray = rawText.split('.').filter(item => item.trim() !== "");

        cardArray.forEach(val => {
            let cardElement = $(`.card-${val}`);

            if (cardElement.length > 0) {
                if (cardElement.hasClass('selected')) {
                    // 'selected' ক্লাস থাকলে এখানে আপনার লজিক (something)
                    console.log(`Card ${val} is already selected.`);
                    // executeSomething(val);
                } else {
                    // 'selected' ক্লাস না থাকলে এখানে অন্য লজিক
                    toggleStudent(val);
                    console.log(`Card ${val} is NOT selected.`);
                    // executeOtherThing(val);
                }
            }
        });
    }

    document.getElementById("blblock").hidden = true;
</script>