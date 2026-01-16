<?php
include 'inc.php'; // Contains header, DB connection, and session data

// --- Filter Handling ---
$classname = isset($_GET['cls']) ? $_GET['cls'] : 'Ten'; // Default to 'Ten' if not set
$sectionname = isset($_GET['sec']) ? $_GET['sec'] : 'Humanities'; // Default to 'Humanities'

$sccode = $_SESSION['sccode'];
$sy = $_SESSION['sessionyear'];

// --- Data Fetching (Optimized with JOIN) ---
$students_tracking_data = [];
$sql = "SELECT 
            si.stid, 
            si.rollno, 
            si.tracktoday, 
            si.trackyesterday,
            st.stnameeng,
            st.stnameben
        FROM sessioninfo si
        LEFT JOIN students st ON si.stid = st.stid AND si.sccode = st.sccode
        WHERE si.sessionyear = ?
          AND si.sccode = ?
          AND si.classname = ?
          AND si.sectionname = ?
          AND si.status = '1'
        ORDER BY si.rollno";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sy, $sccode, $classname, $sectionname);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $students_tracking_data[] = $row;
}
$stmt->close();

$total_students = count($students_tracking_data);
$responded_today = count(array_filter($students_tracking_data, fn($s) => !empty($s['tracktoday'])));
$responded_yesterday = count(array_filter($students_tracking_data, fn($s) => !empty($s['trackyesterday'])));

// For dropdowns, ideally, you'd have a helper function to get all classes/sections
// For now, using the hardcoded values as a starting point.
$temp_classes = ['Six', 'Seven', 'Eight', 'Nine', 'Ten'];
$temp_sections = ['A', 'B', 'C', 'Science', 'Arts', 'Humanities'];
?>

<style>
    body { background-color: #f0f2f5; }
    .table-responsive { max-height: 80vh; }
    .sticky-col { position: sticky; left: 0; z-index: 1; background-color: #f8f9fa; }
    .student-photo-sm { width: 45px; height: 45px; border-radius: 50%; }
    .tracking-dot {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        margin: 0 0.2rem;
        text-align: center;
        line-height: 1rem;
    }
</style>

<main class="container-fluid mt-3">

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <i class="bi bi-graph-up-arrow text-primary me-3" style="font-size: 2.5rem;"></i>
            <div>
                <h1 class="h4 mb-0">Student Tracking Report</h1>
                <p class="mb-0 text-muted">Daily performance tracking for students.</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="cls" class="form-label">Class</label>
                    <select id="cls" name="cls" class="form-select">
                        <?php foreach ($temp_classes as $class_opt): ?>
                            <option value="<?php echo $class_opt; ?>" <?php echo ($class_opt == $classname) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class_opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="sec" class="form-label">Section</label>
                    <select id="sec" name="sec" class="form-select">
                        <?php foreach ($temp_sections as $section_opt): ?>
                            <option value="<?php echo $section_opt; ?>" <?php echo ($section_opt == $sectionname) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section_opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">View</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Report Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Report for: <?php echo htmlspecialchars($classname . ' - ' . $sectionname); ?></h5>
            <div>
                <span class="badge bg-info me-2">Total: <?php echo $total_students; ?></span>
                <span class="badge bg-success me-2">Today's Response: <?php echo $responded_today; ?></span>
                <span class="badge bg-secondary">Yesterday's Response: <?php echo $responded_yesterday; ?></span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="sticky-col">Student</th>
                            <th class="text-center" colspan="14">Today's Tracking Status (by Period/Task)</th>
                            <th class="text-center" colspan="14">Yesterday's Tracking Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_students > 0) {
                            foreach ($students_tracking_data as $student) {
                                $photo_url = "https://eimbox.com/students/noimg.jpg";
                                if (file_exists($BASE_PATH_URL . 'students/' . $student['stid'] . ".jpg")) {
                                    $photo_url = $BASE_PATH_URL_FILE . 'students/' . $student['stid'] . ".jpg";
                                }
                        ?>
                            <tr>
                                <td class="sticky-col">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $photo_url; ?>" alt="Photo" class="student-photo-sm me-2">
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($student['stnameeng']); ?></div>
                                            <small class="text-muted">Roll: <?php echo htmlspecialchars($student['rollno']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <?php // Today's Tracking
                                for ($i = 0; $i < 14; $i++) {
                                    $status = substr($student['tracktoday'], $i, 1);
                                    $class = 'bg-light';
                                    if ($status === '1') $class = 'bg-success';
                                    if ($status === '0') $class = 'bg-danger';
                                    echo '<td><span class="tracking-dot ' . $class . '"></span></td>';
                                }
                                ?>
                                <?php // Yesterday's Tracking
                                for ($i = 0; $i < 14; $i++) {
                                    $status = substr($student['trackyesterday'], $i, 1);
                                    $class = 'bg-light';
                                    if ($status === '1') $class = 'bg-success';
                                    if ($status === '0') $class = 'bg-danger';
                                    echo '<td><span class="tracking-dot ' . $class . '"></span></td>';
                                }
                                ?>
                            </tr>
                        <?php
                            } // end foreach
                        } else {
                            echo '<tr><td colspan="29" class="text-center p-5">
                                <div class="alert alert-warning mb-0">No students found for the selected class and section.</div>
                            </td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex gap-3">
             <div class="d-flex align-items-center"><span class="tracking-dot bg-success me-2"></span> Completed</div>
             <div class="d-flex align-items-center"><span class="tracking-dot bg-danger me-2"></span> Not Completed</div>
             <div class="d-flex align-items-center"><span class="tracking-dot bg-light me-2"></span> No Data</div>
        </div>
    </div>
</main>

<div style="height:52px;"></div>

<?php include 'footer.php'; ?>
