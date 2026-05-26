<?php
$page_title = "Seat Plan Setup";
include 'inc.php';
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-surface: #F6F2FF;
        --m3-card: #FFFFFF;
        --m3-outline: #D7D0E0;
        --m3-text: #1D1B20;
        --m3-danger: #B3261E;
        --m3-success: #2E7D32;
        --radius: 24px;
    }

    body {
        background: var(--m3-surface);
        font-family: 'Segoe UI', sans-serif;
        color: var(--m3-text);
    }

    .m3-card {
        background: var(--m3-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
        padding: 20px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .m3-input,
    .m3-select {
        width: 100%;
        border: 1px solid var(--m3-outline);
        border-radius: 18px;
        padding: 13px 15px;
        outline: none;
        margin-bottom: 12px;
        background: #fff;
    }

    .m3-btn {
        border: none;
        border-radius: 18px;
        padding: 11px 18px;
        cursor: pointer;
        transition: .2s;
        font-weight: 600;
        display: inline-block;
        text-align: center;
    }

    .m3-btn-primary {
        background: var(--m3-primary);
        color: #fff;
    }
    
    .m3-btn-outline {
        background: transparent;
        border: 1px solid var(--m3-primary);
        color: var(--m3-primary);
    }

    .m3-btn:hover {
        transform: translateY(-1px);
    }

    .room-checkbox {
        display: none;
    }
    
    .room-label {
        display: block;
        padding: 15px;
        border: 1px solid var(--m3-outline);
        border-radius: 18px;
        cursor: pointer;
        transition: 0.2s;
        margin-bottom: 10px;
    }

    .room-checkbox:checked + .room-label {
        background: var(--m3-primary-container);
        border-color: var(--m3-primary);
    }
    
    .room-title {
        font-weight: 700;
        font-size: 16px;
    }
    
    .room-sub {
        font-size: 13px;
        color: #555;
    }

</style>

<div class="row">
    <div class="col-md-6">
        <div class="m3-card">
            <div class="section-title">
                <span class="material-symbols-rounded">settings</span>
                Plan Configuration
            </div>

            <form id="seatPlanForm">
                <div class="mb-3">
                    <label>Exam Name</label>
                    <select id="examtitle" class="m3-select" required>
                        <option value="">Select Exam</option>
                        <?php
                        $exams = mysqli_query($conn, "SELECT DISTINCT examtitle FROM examlist WHERE sccode='$sccode' AND sessionyear='$sy' AND slot='$slot'");
                        while ($exam = mysqli_fetch_assoc($exams)) {
                            echo '<option value="' . $exam['examtitle'] . '">' . $exam['examtitle'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Class</label>
                    <select id="classname" class="m3-select" required>
                        <option value="">Select Class</option>
                        <?php
                        $classes = mysqli_query($conn, "SELECT DISTINCT areaname FROM areas WHERE sccode='$sccode' AND sessionyear='$sy' AND slot='$slot' ORDER BY id");
                        while ($cls = mysqli_fetch_assoc($classes)) {
                            echo '<option value="' . $cls['areaname'] . '">' . $cls['areaname'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Section</label>
                    <select id="sectionname" class="m3-select">
                        <option value="All">All Sections</option>
                        <?php
                        $sections = mysqli_query($conn, "SELECT DISTINCT subarea FROM areas WHERE sccode='$sccode' AND sessionyear='$sy' AND slot='$slot' ORDER BY id");
                        while ($sec = mysqli_fetch_assoc($sections)) {
                            if (!empty($sec['subarea'])) {
                                echo '<option value="' . $sec['subarea'] . '">' . $sec['subarea'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Shift</label>
                    <select id="shift" class="m3-select" required>
                        <option value="Morning">Morning</option>
                        <option value="Day">Day</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="m3-card">
            <div class="section-title">
                <span class="material-symbols-rounded">meeting_room</span>
                Select Rooms
            </div>
            
            <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                <?php
                $rooms_query = mysqli_query($conn, "
                    SELECT sr.*, sb.building_name, sf.floor_name,
                           COALESCE(SUM(srb.capacity), 0) as total_capacity,
                           COUNT(srb.id) as active_benches
                    FROM seat_rooms sr
                    JOIN seat_floors sf ON sr.floor_id = sf.id
                    JOIN seat_buildings sb ON sf.building_id = sb.id
                    LEFT JOIN seat_room_benches srb ON sr.id = srb.room_id AND srb.is_blocked = 0
                    GROUP BY sr.id
                    ORDER BY sb.building_name, sf.floor_no, sr.room_name
                ");

                while ($room = mysqli_fetch_assoc($rooms_query)) {
                    ?>
                    <div class="room-selector">
                        <input type="checkbox" id="room_<?= $room['id'] ?>" name="rooms[]" value="<?= $room['id'] ?>" class="room-checkbox">
                        <label for="room_<?= $room['id'] ?>" class="room-label">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="room-title"><?= $room['room_name'] ?></div>
                                    <div class="room-sub"><?= $room['building_name'] ?> - <?= $room['floor_name'] ?></div>
                                </div>
                                <div>
                                    <span class="badge bg-primary rounded-pill p-2"><i class="bi bi-person-fill"></i> <?= $room['total_capacity'] ?></span>
                                </div>
                            </div>
                        </label>
                    </div>
                <?php } ?>
            </div>
            
            <div class="mt-4">
                <button type="button" class="m3-btn m3-btn-primary w-100" onclick="generatePlan()">Generate Seat Plan</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function generatePlan() {
        let examtitle = $("#examtitle").val();
        let classname = $("#classname").val();
        let sectionname = $("#sectionname").val();
        let shift = $("#shift").val();
        
        let selectedRooms = [];
        $(".room-checkbox:checked").each(function() {
            selectedRooms.push($(this).val());
        });
        
        if (!examtitle || !classname) {
            alert("Please select Exam and Class");
            return;
        }
        
        if (selectedRooms.length === 0) {
            alert("Please select at least one room");
            return;
        }
        
        let btn = event.target;
        btn.innerHTML = 'Generating...';
        btn.disabled = true;
        
        $.ajax({
            url: 'seat-plan/process-allocation.php',
            type: 'POST',
            data: {
                examtitle: examtitle,
                classname: classname,
                sectionname: sectionname,
                shift: shift,
                rooms: selectedRooms
            },
            success: function(res) {
                btn.innerHTML = 'Generate Seat Plan';
                btn.disabled = false;
                
                try {
                    let response = JSON.parse(res);
                    if (response.success) {
                        alert(response.message);
                        window.location.href = 'exam-hall-map.php';
                    } else {
                        alert(response.message);
                    }
                } catch(e) {
                    console.log(res);
                    alert("Error processing response");
                }
            }
        });
    }
</script>