<?php
$page_title = "Exam Hall Invigilator Setup";
include "inc.php";
?>


<style>
body {
    font-family: Roboto, Arial;
    background:#f5f5f5;
}

.card {
    background:white;
    padding:16px;
    border-radius:16px;
    margin:12px 0;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.row {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

select, input {
    padding:10px;
    border-radius:12px;
    border:1px solid #ddd;
    min-width:200px;
}

button {
    padding:10px 16px;
    border:none;
    border-radius:12px;
    cursor:pointer;
}

.primary {
    background:#6750A4;
    color:white;
}

.outline {
    border:1px solid #6750A4;
    background:white;
    color:#6750A4;
}

.table {
    width:100%;
    border-collapse:collapse;
}

.table th, .table td {
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:left;
}
</style>
</head>

<body>

<div class="card">
    <h2>Exam Invigilator Setup</h2>

    <div class="row">

        <!-- Session -->
        <select id="session">
            <option value="">Select Session</option>
            <?php
            $q = mysqli_query($conn,"SELECT DISTINCT sessionyear FROM seat_plans");
            while($r = mysqli_fetch_assoc($q)){
                echo "<option value='{$r['sessionyear']}'>{$r['sessionyear']}</option>";
            }
            ?>
        </select>

        <!-- Exam -->
        <select id="exam">
            <option value="">Select Exam</option>
        </select>

        <!-- Mode -->
        <select id="view-type">
            <option value="room">By Room</option>
            <option value="day">By Day</option>
            <option value="teacher">By Teacher</option>
        </select>

        <button class="primary" onclick="loadData()">Load</button>

    </div>
</div>

<div class="card" id="resultArea">
    <h3>Room Allocation</h3>
    <div id="rooms"></div>
</div>
 

<button class="outline" onclick="autoAssign()">Auto Assign</button>


<?php include "footer.php"; ?>


<script>
document.getElementById("session").addEventListener("change", function(){
    let session = this.value;
    fetch("exam/load_exam.php?session="+session)
    .then(res=>res.json())
    .then(data=>{
        let exam = document.getElementById("exam");
        exam.innerHTML = "<option value=''>Select Exam</option>";
        data.forEach(e=>{
            exam.innerHTML += `<option value="${e.id}">${e.examtitle}</option>`;
        });
    });
});


document.getElementById("view-type").addEventListener("change", function(){
    let session = document.getElementById("session").value;
    let exam = document.getElementById("exam").value;
let type = this.value;
    fetch("exam/load_params.php?session="+session+"&exam="+exam+"&type="+type)
    .then(res=>res.json())
    .then(data=>{
        let exam = document.getElementById("params");
        exam.innerHTML = "<option value=''>Select Parameters</option>";
        data.forEach(e=>{
            exam.innerHTML += `<option value="${e.value}">${e.title}</option>`;
        });
    });
});

function loadData(){
    let session = document.getElementById("session").value;
    let exam = document.getElementById("exam").value;

    fetch(`exam/load_rooms.php?session=${session}&planid=${exam}`)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("rooms").innerHTML = data;
    });
}

function autoAssign(){
    let session = document.getElementById("session").value;
    let examSelect = document.getElementById("exam");

    let planid = examSelect.value;
    let examname = examSelect.options[examSelect.selectedIndex].text;

    fetch(`exam/auto_assign.php?session=${session}&planid=${planid}&examname=${encodeURIComponent(examname)}`)
    .then(res=>res.text())
    .then(res=>{
        alert("Auto assigned successfully");
        loadData();
    });
}
</script>


</body>
</html>