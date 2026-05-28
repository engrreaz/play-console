<?php
$page_title = "Exam Hall Invigilator Setup";
include "inc.php";

$chain_session = $_COOKIE["chain-session"] ?? $sessionyear;
$chain_exam = $_COOKIE["chain-exam"] ?? '';
$chain_type = $_COOKIE["chain-type"] ?? 'room';
$chain_params = $_COOKIE["chain-params"] ?? '';
?>


<style>
    :root {
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-surface: #FEF7FF;
        --m3-surface-variant: #E7E0EC;
        --m3-on-surface: #1D1B20;
        --m3-on-surface-variant: #49454F;
        --m3-outline: #79747E;
        /* Tonal Containers */
        --m3-surface-container-low: #F7F2FA;
        --m3-surface-container: #F3EDF7;
        --m3-surface-container-high: #ECE6F0;
        --m3-secondary-container: #E8DEF8;
        --m3-on-secondary-container: #1D192B;
    }



    /* M3 Elevateless Tonal Card Dashboard */
    .m3-main-card {
        background-color: var(--m3-surface-container-low);
        border-radius: 12px;
        /* M3 Extra Large Radius */
        padding: 16px;
        margin-bottom: 24px;
        border: none;
    }

    .m3-title {
        font-size: 22px;
        font-weight: 500;
        color: var(--m3-on-surface);
        margin-top: 0;
        margin-bottom: 20px;
        letter-spacing: 0.15px;
    }

    /* Form Fields: M3 Outlined Dropdown */
    .m3-field-wrapper {
        position: relative;
        margin-bottom: 16px;
    }

    .m3-select {
        width: 100%;
        height: 40px;
        padding: 8px 16px;
        font-size: 16px;
        color: var(--m3-on-surface);
        background-color: transparent;
        border: 1px solid var(--m3-outline);
        border-radius: 100px;
        /* M3 Outlined Field Radius */
        outline: none;
        appearance: none;
        transition: border 0.2s, cubic-bezier(0.2, 0, 0, 1);
    }

    .m3-select:focus {
        border: 2px solid var(--m3-primary);
        padding: 8px 15px;
        /* Adjust border offset */
    }

    /* M3 Buttons */
    .m3-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.1px;
        padding: 0 24px;
        height: 40px;
        border-radius: 20px;
        /* Fully Rounded */
        border: none;
        cursor: pointer;
        transition: box-shadow 0.2s, background-color 0.2s;
    }

    /* Filled / Primary Button */
    .m3-btn-primary {
        background-color: var(--m3-primary);
        color: var(--m3-on-primary);
    }

    .m3-btn-primary:hover {
        background-color: #5a4394;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* Tonal/Outline Button */
    .m3-btn-tonal {
        background-color: var(--m3-secondary-container);
        color: var(--m3-on-secondary-container);
        border: none;
    }

    .m3-btn-tonal:hover {
        background-color: #dfd5ef;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    .m3-btn-outline {
        background-color: transparent;
        color: var(--m3-primary);
        border: 1px solid var(--m3-outline);
    }

    .m3-btn-outline:hover {
        background-color: rgba(103, 80, 164, 0.08);
    }

    /* Result Container Card */
    .m3-result-card {
        background-color: var(--m3-surface-container);
        border-radius: 12px;
        padding: 12px;
        margin-top: 24px;
    }

    /* Grid Layout for AJAX generated cards */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    /* Utility Helpers for Bootstrap Compatibility */
    .m3-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -8px;
        margin-left: -8px;
    }

    .m3-col-6 {
        flex: 0 0 auto;
        width: 50%;
        padding-right: 8px;
        padding-left: 8px;
        box-sizing: border-box;
    }

    .m-1 {
        margin: 4px;
    }

    .mt-0 {
        margin-top: 0;
    }

    .mt-3 {
        margin-top: 16px;
    }

    .mb-2 {
        margin-bottom: 8px;
    }

    .p-2 {
        padding: 8px;
    }

    .w-100 {
        width: 100%;
    }
</style>



</head>

<body>



    <div class="m3-main-card m-1 mt-0">
        <button class="btn btn-link float-end" onclick="toggleBlock();"><i class="bi bi-arrow-down-up"></i></button>

        <h3 class="m3-title"><i class="bi bi-events"></i> Schedule Filter</h3>

        <div id="label-block" style="display:block;">
            <div class="row">
                <div class="col-auto me-4">
                    <div class="fs-6 fw-bold">Session</div>
                    <div class="text-secondary" id="aaa"><?= $chain_session ?? '' ?></div>
                </div>
                <div class="col-auto flex-grow-1">
                    <div class="fs-6 fw-bold">Exam</div>
                    <div class="text-secondary" id="bbb"><?= $chain_exam ?? '' ?></div>
                </div>
                <div class="col-auto">
                    <div class="fs-6 fw-bold">Filter by</div>
                    <div class="d-flex">
                        <div class="text-secondary" id="ccc"><?= $chain_type ?? '' ?></div>
                        <div> &nbsp; &mdash; &nbsp; </div>
                        <div class="text-secondary" id="ddd"><?= $chain_params ?? '' ?></div>
                    </div>

                </div>
            </div>

        </div>

        <div id="selection-block" style="display:none;">
            <div class="m3-row">
                <div class="m3-col-6">
                    <div class="m3-field-wrapper">
                        <select id="session" class="m3-select mb-2">
                            <option value="" style="color: #ba1a1a;">Select Session</option>
                            <?php
                            $q = mysqli_query($conn, "SELECT DISTINCT sessionyear FROM seat_plans");
                            while ($r = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$r['sessionyear']}'>{$r['sessionyear']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="m3-col-6">
                    <div class="m3-field-wrapper">
                        <select id="exam" class="m3-select mb-2">
                            <option value="">Select Exam</option>
                        </select>
                    </div>
                </div>

                <div class="m3-col-6">
                    <div class="m3-field-wrapper">
                        <select id="view-type" class="m3-select mb-2">
                            <option value="">Choose Type</option>
                            <option value="room">By Room</option>
                            <option value="day">By Day</option>
                            <option value="teacher">By Teacher</option>
                        </select>
                    </div>
                </div>

                <div class="m3-col-6">
                    <div class="m3-field-wrapper">
                        <select id="params" class="m3-select mb-2">
                            <option value="">Select Parameters</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap;">
                <button class="m3-btn m3-btn-primary" onclick="loadData()">
                    Load Invigilating Schedule
                </button>
                <button class="m3-btn m3-btn-tonal" onclick="autoAssign()">
                    ✨ Auto Assign
                </button>
            </div>
        </div>


    </div>

    <div class="m3-result-card m-1 mt-3" id="resultArea">
        <h3 class="m3-title text-center" style="margin-bottom: 8px;"><i class="bi bi-buildings"></i> Room Allocation
            Blueprint</h3>
        <div id="rooms" class="grid"></div>
    </div>


    <?php include "footer.php"; ?>


    <script>
        function labelBlock() {
            document.getElementById("selection-block").style.display = "none";
            document.getElementById("label-block").style.display = "block";
        }
        function selectionBlock() {
            document.getElementById("selection-block").style.display = "block";
            document.getElementById("label-block").style.display = "none";
        }

        function toggleBlock() {
            if (document.getElementById("selection-block").style.display == "none") {
                selectionBlock();
            } else {
                labelBlock();
            }
        }
    </script>

    <script>
        document.getElementById("session").addEventListener("change", function () {
            let session = this.value;
            // alert(session);
            fetch("exam/load_exam.php?session=" + session)
                .then(res => res.json())
                .then(data => {
                    let exam = document.getElementById("exam");
                    exam.innerHTML = "<option value=''>Select Exam</option>";
                    data.forEach(e => {
                        exam.innerHTML += `<option value="${e.id}" data-title="${e.examtitle}">${e.examtitle}</option>`;
                    });
                });
            $('#exam').val('<?= $chain_exam ?>');
        });


        document.getElementById("view-type").addEventListener("change", function () {
            let session = document.getElementById("session").value;
            let exam = document.getElementById("exam").value;
            let type = this.value;
            // alert(type);
            fetch("exam/load_params.php?session=" + session + "&exam=" + exam + "&type=" + type)
                .then(res => res.json())
                .then(data => {
                    let exam = document.getElementById("params");
                    exam.innerHTML = "<option value=''>Select Parameters</option>";
                    data.forEach(e => {
                        exam.innerHTML += `<option value="${e.value}">${e.title}</option>`;
                    });
                });
            $('#params').val('<?= $chain_params ?>');
        });

        function loadData() {
            let session = document.getElementById("session").value;
            let exam = document.getElementById("exam").value;
            let type = document.getElementById("view-type").value;
            let params = document.getElementById("params").value;

            setCookie("chain-session", session);
            if (exam != '') {
                setCookie("chain-exam", exam);
            }

            setCookie("chain-type", type);
            if (params != '') {
                setCookie("chain-params", params);
            }


            fetch(`exam/load_rooms.php?session=${session}&planid=${exam}&type=${type}&params=${params}`)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("rooms").innerHTML = data;
                });


            document.getElementById("aaa").innerHTML = session;

            let el = document.getElementById("exam");
            let selectedOption = el.options[el.selectedIndex];

            document.getElementById("bbb").innerHTML =
                selectedOption.dataset.title || '';

            document.getElementById("ccc").innerHTML = type;
            let ddd = document.getElementById("ddd");

            if (ddd) {
                ddd.innerHTML = params || '';
            }
            labelBlock();
        }

        function autoAssign() {
            let session = document.getElementById("session").value;
            let examSelect = document.getElementById("exam");

            let planid = examSelect.value;
            let examname = examSelect.options[examSelect.selectedIndex].text;

            fetch(`exam/auto_assign.php?session=${session}&planid=${planid}&examname=${encodeURIComponent(examname)}`)
                .then(res => res.text())
                .then(res => {
                    alert("Auto assigned successfully");
                    loadData();
                });
        }
    </script>


    <script>
        function editMode(room, date, shift) {
            document.getElementById(`view-${room}-${date}-${shift}`).style.display = 'none';
            document.getElementById(`edit-${room}-${date}-${shift}`).style.display = 'block';
        }

        function saveAssign(room, date, shift, tid) {

            fetch("exam/update_invigilator.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `room=${room}&date=${date}&shift=${shift}&tid=${tid}`
            })
                .then(res => res.text());

            document.getElementById(`view-${room}-${date}-${shift}`).style.display = 'block';

            document.getElementById(`edit-${room}-${date}-${shift}`).style.display = 'none';

            let el = document.getElementById(`tidx-${room}-${date}-${shift}`);
            let selectedOption = el.options[el.selectedIndex];

            document.getElementById("tid-" + room + "-" + date + "-" + shift).innerHTML =
                selectedOption.dataset.tname || '';

            // .then(res => {
            //     location.reload();
            // });
        }





        // ------------------------- Auto load ---------------------------
        $('#session').val('<?= $chain_session ?>');
        $('#view-type').val('<?= $chain_type ?>');

        document.getElementById('session').dispatchEvent(new Event('change'));

        setTimeout(() => {
            console.log(1);
            $('#exam').val('<?= $chain_exam ?>');
        }, 800);

        setTimeout(() => {
            console.log(2);
            document.getElementById('view-type').dispatchEvent(new Event('change'));
        }, 900);

        setTimeout(() => {
            console.log(3);
            $('#params').val('<?= $chain_params ?>');
            loadData();
        }, 1500);




    </script>

</body>

</html>