<?php

include '../inc.light.php';

$slot = $_POST['slot'] ?? '';
$session = $_POST['session'] ?? '';
$classname = $_POST['classname'] ?? '';
$sectionname = $_POST['sectionname'] ?? '';
$exam = $_POST['exam'] ?? '';

$session_param = "%" . $session . "%";

$sql = "
SELECT *
FROM examroutine


WHERE
sccode=?
AND sessionyear LIKE ?
AND examname=?
AND clsname=?
AND secname=?

ORDER BY date ASC,time ASC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssss",
    $sccode,
    $session_param,
    $exam,
    $classname,
    $sectionname
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $exam_date = date('d M Y', strtotime($row['date']));

        ?>

        <div class="m3-routine-card">

            <div class="d-flex justify-content-between align-items-start gap-2">


                <div class="m3-date-chip">
                    <i class="bi bi-calendar-event"></i>
                    <?= $exam_date ?>
                </div>
                
                <div class="flex-grow-1"></div>

                <div class="m3-meta-pill">
                    <i class="bi bi-clock-history me-1"></i>
                    <?= htmlspecialchars($row['time']) ?>
                </div>




            </div>


            <div class="m3-subcode">
                <?= htmlspecialchars($row['subj']) ?>
            </div>



        </div>

        <?php

    }

} else {

    ?>

    <div class="m3-empty-state">

        <i class="bi bi-calendar-x"></i>

        <div class="fw-bold mt-3">
            No Routine Found
        </div>

        <div class="small text-muted mt-1">
            No exam schedule configured yet
        </div>

    </div>

    <?php

}

$stmt->close();
?>