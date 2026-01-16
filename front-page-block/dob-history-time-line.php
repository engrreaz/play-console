<?php
// File: front-page-block/dob-history-time-line.php

// --- Data Fetching & Logic ---
$students_with_birthday_today = [];
if (isset($conn, $sccode, $sy)) {
    $like_date = '%' . date('-m-d');
    $sy_like = "%$sy%";

    $stmt = $conn->prepare("
        SELECT
            s.stid,
            s.stnameeng,
            si.classname,
            si.sectionname
        FROM
            students s
        JOIN
            sessioninfo si ON s.stid = si.stid
        WHERE
            s.sccode = ?
            AND s.dob LIKE ?
            AND si.sccode = ?
            AND si.sessionyear LIKE ?
    ");
    $stmt->bind_param("ssss", $sccode, $like_date, $sccode, $sy_like);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $students_with_birthday_today = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}


// --- Presentation ---
if (!empty($students_with_birthday_today)):
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-gift-fill text-danger me-2" style="font-size: 1.5rem;"></i>
            <h6 class="card-title mb-0 fw-bold">Today's Birthdays</h6>
        </div>
        <div class="row gx-3">
            <?php foreach ($students_with_birthday_today as $student): 
                $photo_path = "students/" . htmlspecialchars($student['stid']) . ".jpg";
            ?>
                <div class="col-auto text-center">
                    <a href="student-my-profile.php?stid=<?php echo htmlspecialchars($student['stid']); ?>" class="text-decoration-none text-dark">
                        <img src="<?php echo $photo_path; ?>" 
                             class="rounded-circle" 
                             style="width: 50px; height: 50px; object-fit: cover;" 
                             alt="<?php echo htmlspecialchars($student['stnameeng']); ?>"
                             onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                        <div class="small mt-1" style="font-size: 0.75rem;"><?php echo strtok(htmlspecialchars($student['stnameeng']), " "); ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php 
endif; 
?>
