<?php
$page_title = "Exam Hall Invigilator Setup";
include "inc.php";
?>


<style>



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

            fetch(`exam/load_rooms.php?session=${session}&planid=${exam}`)
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


</body>

</html>