<?php
/**
 * Today's Birthdays - M3 Refactored
 * Features: Horizontal Scroll, Tonal Surface, Adaptive Avatars
 */

$students_with_birthday_today = [];
if (isset($conn, $sccode, $sy)) {
    $like_date = '%' . date('-m-d');
    $sy_like = "%$sy%";

    $stmt = $conn->prepare("
        SELECT s.stid, s.stnameeng, si.classname, si.sectionname
        FROM students s
        JOIN sessioninfo si ON s.stid = si.stid
        WHERE s.sccode = ? AND s.dob LIKE ? AND si.sccode = ? AND si.sessionyear LIKE ?
    ");
    $stmt->bind_param("ssss", $sccode, $like_date, $sccode, $sy_like);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $students_with_birthday_today = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}

if (!empty($students_with_birthday_today)):
?>

<style>
    /* M3 Surface Container */
    .m3-birthday-card {
        background-color: #F3EDF7; /* M3 Primary Tonal Container */
        border-radius: 8px; /* M3 Extra Large Shape */
        padding: 20px;
        border: none;
    }

    .m3-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .m3-icon-badge {
        width: 40px;
        height: 40px;
        background: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #B3261E; /* M3 Error Red */
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* Horizontal Scroll Layout */
    .m3-scroll-container {
        display: flex;
        overflow-x: auto;
        gap: 16px;
        padding: 8px 0;
        scrollbar-width: none; /* Hide scrollbar for clean look */
    }
    .m3-scroll-container::-webkit-scrollbar { display: none; }

    .m3-birthday-item {
        flex: 0 0 auto;
        width: 60px;
        text-align: center;
        transition: transform 0.2s ease;
    }
    .m3-birthday-item:active { transform: scale(0.9); }

    .m3-avatar-wrapper {
        position: relative;
        width: 48px;
        height: 48px;
        margin: 0 auto;
    }

    .m3-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(103, 80, 164, 0.15);
    }

    /* Celebration Ring */
    .m3-celebration-ring {
        position: absolute;
        inset: -3px;
        border: 2px dashed #6750A4;
        border-radius: 50%;
        animation: rotate-ring 10s linear infinite;
    }

    @keyframes rotate-ring {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
  

    .m3-student-name {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1C1B1F;
        margin-top: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="m3-birthday-card shadow-sm">
    <div class="m3-card-header">
        <div class="m3-icon-badge">
            <i class="bi bi-cake2-fill fs-5"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size: 0.95rem; color: #1C1B1F;">Today's Birthdays</div>
            <div class="small opacity-75" style="font-size: 0.7rem;">Celebrating <?php echo count($students_with_birthday_today); ?> students</div>
        </div>
    </div>

    <div class="m3-scroll-container">
        <?php foreach ($students_with_birthday_today as $student): 
            $photo_path = student_profile_image_path($student['stid']);
            $first_name = strtok($student['stnameeng'], " ");
        ?>
            <div class="m3-birthday-item">
                <a href="student-my-profile.php?stid=<?php echo htmlspecialchars($student['stid']); ?>" class="text-decoration-none">
                    <div class="m3-avatar-wrapper">
                        <div class="m3-celebration-ring"></div>
                        <img src="<?php echo $photo_path; ?>" 
                             class="m3-avatar-img" 
                             alt="Student"
                             onerror="this.src='https://eimbox.com/teacher/no-img.jpg';">
                    </div>
                    <div class="m3-student-name"><?php echo htmlspecialchars($first_name); ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>
dddd