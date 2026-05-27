<?php
$page_title = "Exam Hall Invigilator Setup";
include "inc.php";

$chain_session = $_COOKIE["chain-session"] ?? $sessionyear;
$chain_exam = $_COOKIE["chain-exam"] ?? '';
$chain_type = $_COOKIE["chain-type"] ?? 'room';
$chain_params = $_COOKIE["chain-params"] ?? '';
?>


<style>
    body {
        font-family: Roboto, Arial;
        background: #f6f6f9;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 16px;
        /* padding:16px; */
    }

    .ton-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 14px 16px;
        background: #E8DEF8;
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .sub {
        display: block;
        font-size: 13px;
        color: #555;
    }

    .card-body {
        padding: 14px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        font-size: 13px;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .table td {
        padding: 8px;
    }

    .md-select {
        width: 100%;
        padding: 8px;
        border-radius: 10px;
        border: 1px solid #ccc;
        background: #fff;
    }

    .actions {
        margin-top: 10px;
        text-align: right;
    }

    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
    }

    .primary {
        background: #6750A4;
        color: #fff;
    }
</style>



</head>

<body>

    <div class="card m-1 mt-0">
        <div class="card-body">

            <div class="row mb-2">

                <!-- Session -->
                <div class="col-6"><select id="session" class="form-select  mb-2">
                        <option value="" class="text-danger">Select Session</option>
                        <?php
                        $q = mysqli_query($conn, "SELECT DISTINCT sessionyear FROM seat_plans");
                        while ($r = mysqli_fetch_assoc($q)) {
                            echo "<option value='{$r['sessionyear']}'>{$r['sessionyear']}</option>";
                        }
                        ?>
                    </select></div>
                <div class="col-6"> <!-- Exam -->
                    <select id="exam" class="form-select  mb-2">
                        <option value="">Select Exam</option>
                    </select>
                </div>
                <div class="col-6"> <!-- Mode -->
                    <select id="view-type" class="form-select  mb-2">
                        <option value="">Choose Type</option>
                        <option value="room">By Room</option>
                        <option value="day">By Day</option>
                        <option value="teacher">By Teacher</option>
                    </select>
                </div>
                <div class="col-6"><select id="params" class="form-select  mb-2">
                        <option value="">Select Parameters</option>
                    </select></div>








            </div>

            <button class="btn btn-outline-primary " onclick="loadData()">Load Invigilating Schedule</button>
        </div>

    </div>

    <div class="card m-1 mt-3 p-2 " id="resultArea">
        <h3>Room Allocation</h3>
        <div id="rooms"></div>
    </div>


    <button class="outline" onclick="autoAssign()">Auto Assign</button>


    <?php include "footer.php"; ?>


    <script>
        document.getElementById("session").addEventListener("change", function () {
            let session = this.value;
            alert(session);
            fetch("exam/load_exam.php?session=" + session)
                .then(res => res.json())
                .then(data => {
                    let exam = document.getElementById("exam");
                    exam.innerHTML = "<option value=''>Select Exam</option>";
                    data.forEach(e => {
                        exam.innerHTML += `<option value="${e.id}">${e.examtitle}</option>`;
                    });
                });
        });


        document.getElementById("view-type").addEventListener("change", function () {
            let session = document.getElementById("session").value;
            let exam = document.getElementById("exam").value;
            let type = this.value;
            alert(type);
            fetch("exam/load_params.php?session=" + session + "&exam=" + exam + "&type=" + type)
                .then(res => res.json())
                .then(data => {
                    let exam = document.getElementById("params");
                    exam.innerHTML = "<option value=''>Select Parameters</option>";
                    data.forEach(e => {
                        exam.innerHTML += `<option value="${e.value}">${e.title}</option>`;
                    });
                });
        });

        function loadData() {
            let session = document.getElementById("session").value;
            let exam = document.getElementById("exam").value;
            let type = document.getElementById("view-type").value;
            let params = document.getElementById("params").value;

            setCookie("chain-session", session);
            setCookie("chain-exam", exam);
            setCookie("chain-type", type);
            setCookie("chain-params", params);

            fetch(`exam/load_rooms.php?session=${session}&planid=${exam}&type=${type}&params=${params}`)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("rooms").innerHTML = data;
                });
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
                .then(res => res.text())
                .then(res => {
                    location.reload();
                });
        }



        $('#session').val('').trigger('change');
        $('#session').val('<?= $chain_session ?>').trigger('change');
        $('#view-type').val('<?= $chain_type ?>').trigger('change');

        setTimeout(() => {
            $('#exam').val('<?= $chain_exam ?>');
            $('#params').val('<?= $chain_params ?>');
        }, 3000);

    </script>
</body>

</html>