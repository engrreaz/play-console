<?php
/**
 * Smart Drag & Drop Routine Builder - M3 Standard
 */
$page_title = "Smart Routine Builder";
include_once 'inc.php';

// ডাটা লোড (টিচার এবং সাবজেক্ট)
$teachers = $conn->query("SELECT tid, tname FROM teacher WHERE sccode='$sccode' ORDER BY tname ASC");
$subjects = ['Bangla', 'English', 'Math', 'Science', 'BGS', 'Religion', 'ICT', 'Physical Ed']; // বা টেবিল থেকে আনতে পারেন
?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    body {
        background-color: #F7F2FA;
    }

    .builder-container {
        display: flex;
        gap: 20px;
        padding: 20px;
        height: calc(100vh - 100px);
    }

    /* ১. সাইডবার (ড্র্যাগেবল আইটেম) */
    .item-sidebar {
        width: 250px;
        background: #fff;
        border-radius: 24px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        overflow-y: auto;
    }

    .draggable-chip {
        background: #EADDFF;
        color: #21005D;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 8px;
        cursor: grab;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid transparent;
    }

    .draggable-chip:active {
        cursor: grabbing;
    }

    .chip-teacher {
        background: #E8F5E9;
        color: #1B5E20;
    }

    /* টিচারের জন্য আলাদা কালার */

    /* ২. রুটিন গ্রিড */
    .routine-grid {
        flex-grow: 1;
        background: #fff;
        border-radius: 24px;
        padding: 20px;
        overflow: auto;
        border: 1px solid #eee;
    }

    .timetable {
        width: 100%;
        border-collapse: separate;
        border-spacing: 10px;
    }

    .day-header {
        font-weight: 900;
        color: #6750A4;
        text-align: center;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .drop-zone {
        min-width: 150px;
        min-height: 100px;
        background: #fcfaff;
        border: 2px dashed #EADDFF;
        border-radius: 16px;
        padding: 10px;
        transition: 0.3s;
    }

    .drop-zone.active {
        background: #F3EDF7;
        border-color: #6750A4;
    }

    .assigned-item {
        background: #fff;
        border: 1px solid #ddd;
        padding: 8px;
        border-radius: 10px;
        position: relative;
        margin-bottom: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #B3261E;
        color: #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        border: none;
        cursor: pointer;
    }
</style>

<main class="routine-grid shadow-sm" id="routine-capture-area"> <div class="d-flex justify-content-between align-items-center mb-4" data-html2canvas-ignore="true"> <h5 class="fw-bold mb-0">Weekly Routine Builder (Class: Nine)</h5>
        <div>
            <button class="btn btn-outline-primary rounded-pill px-3 me-2 fw-bold" onclick="downloadRoutineImage()">
                <i class="bi bi-card-image me-1"></i> DOWNLOAD IMG
            </button>
            <button class="btn btn-primary rounded-pill px-4 fw-bold" onclick="saveFullRoutine()">
                <i class="bi bi-cloud-check me-1"></i> SAVE CHANGES
            </button>
        </div>
    </div>

    <table class="timetable">
       </table>
</main>


<div class="builder-container">
    <aside class="item-sidebar shadow-sm">
        <div>
            <h6 class="fw-bold mb-3 text-primary">Subjects</h6>
            <div id="subject-pool">
                <?php foreach ($subjects as $sub): ?>
                    <div class="draggable-chip" data-type="subject" data-value="<?php echo $sub; ?>">
                        <i class="bi bi-book"></i> <?php echo $sub; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <hr>

        <div>
            <h6 class="fw-bold mb-3 text-success">Teachers</h6>
            <div id="teacher-pool">
                <?php while ($t = $teachers->fetch_assoc()): ?>
                    <div class="draggable-chip chip-teacher" data-type="teacher" data-value="<?php echo $t['tid']; ?>">
                        <i class="bi bi-person-badge"></i> <?php echo $t['tname']; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </aside>

    <main class="routine-grid shadow-sm">
        <div class="d-flex justify-content-between mb-4">
            <h5 class="fw-bold">Weekly Routine Builder (Class: Nine)</h5>
            <button class="btn btn-primary rounded-pill px-4" onclick="saveFullRoutine()">
                <i class="bi bi-cloud-check me-1"></i> SAVE CHANGES
            </button>
        </div>

        <table class="timetable">
            <thead>
                <tr>
                    <th></th>
                    <?php
                    $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
                    foreach ($days as $day)
                        echo "<th class='day-header'>$day</th>";
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // পিরিয়ড লিস্ট আনা
                $periods = $conn->query("SELECT DISTINCT period, timestart, timeend FROM classschedule WHERE sccode='$sccode' ORDER BY period ASC");
                while ($p = $periods->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="text-center">
                            <div class="fw-bold" style="font-size:0.8rem;">Period <?php echo $p['period']; ?></div>
                            <small class="text-muted"
                                style="font-size:0.6rem;"><?php echo date('h:i', strtotime($p['timestart'])); ?></small>
                        </td>
                        <?php foreach ($days as $day):
                            // ডাটাবেজ থেকে বর্তমান ডাটা আনা
                            $current = $conn->query("SELECT * FROM classroutine WHERE sccode='$sccode' AND day='$day' AND period='" . $p['period'] . "' AND classname='Nine' LIMIT 1")->fetch_assoc();
                            ?>
                            <td>
                                <div class="drop-zone" data-day="<?php echo $day; ?>" data-period="<?php echo $p['period']; ?>"
                                    id="slot-<?php echo $day . '-' . $p['period']; ?>">
                                    <?php if ($current && $current['subject'] != 'TBD'): ?>
                                        <div class="assigned-item" data-type="subject"><?php echo $current['subject']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>


<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script></script></script>

<script>
    // ১. ড্র্যাগেবল পুল ইনিশিয়ালাইজেশন
    const pools = ['subject-pool', 'teacher-pool'];
    pools.forEach(id => {
        new Sortable(document.getElementById(id), {
            group: { name: 'routine', pull: 'clone', put: false },
            sort: false,
            animation: 150
        });
    });

    // ২. প্রতিটি ড্রপ জোন ইনিশিয়ালাইজেশন
    document.querySelectorAll('.drop-zone').forEach(el => {
        new Sortable(el, {
            group: 'routine',
            animation: 150,
            onAdd: function (evt) {
                const itemEl = evt.item;
                const type = itemEl.getAttribute('data-type');
                const val = itemEl.getAttribute('data-value');
                const day = el.getAttribute('data-day');
                const period = el.getAttribute('data-period');

                // ড্রপ হওয়ার পর আইটেমটিকে কাস্টম ডিজাইন দেওয়া
                itemEl.innerHTML = (type === 'subject' ? val : 'Teacher ID: ' + val) +
                    '<button class="remove-btn" onclick="this.parentElement.remove()">×</button>';
                itemEl.className = 'assigned-item';

                // যদি আগে থেকে কিছু থাকে তবে একটা লিমিট সেট করা (যেমন: ১টি সাবজেক্ট ও ১টি টিচার)
                if (el.children.length > 2) itemEl.remove();
            }
        });
    });

    // ৩. ডাটা সেভ ফাংশন (AJAX)
    function saveFullRoutine() {
        const routineData = [];
        document.querySelectorAll('.drop-zone').forEach(zone => {
            const items = Array.from(zone.children).map(c => c.innerText.replace('×', '').trim());
            if (items.length > 0) {
                routineData.push({
                    day: zone.getAttribute('data-day'),
                    period: zone.getAttribute('data-period'),
                    data: items
                });
            }
        });

        $.post('ajax/save_bulk_routine.php', { routine: JSON.stringify(routineData) }, function (res) {
            if (res.trim() === 'success') {
                Swal.fire({
                    title: 'রুটিন সেভ হয়েছে!',
                    text: 'আপনার সাপ্তাহিক রুটিন সফলভাবে আপডেট করা হয়েছে।',
                    icon: 'success',
                    confirmButtonColor: '#6750A4'
                });
            } else {
                Swal.fire('Error', 'কিছু সমস্যা হয়েছে: ' + res, 'error');
            }
        });
    }
</script>

<script>
    // ৪. রুটিন ইমেজ ডাউনলোড ফাংশন
function downloadRoutineImage() {
    // ইউজারকে ফিডব্যাক দেওয়া যে কাজ চলছে
    Swal.fire({
        title: 'Generating Image...',
        html: 'Please wait while we capture the routine.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const captureElement = document.getElementById('routine-capture-area');

    html2canvas(captureElement, {
        scale: 2, // উচ্চ মানের ইমেজের জন্য স্কেল বাড়ানো হয়েছে (Retina quality)
        backgroundColor: '#ffffff', // ব্যাকগ্রাউন্ড সাদা নিশ্চিত করা
        logging: false, // কনসোল লগ বন্ধ রাখা
        useCORS: true // যদি কোনো এক্সটার্নাল ইমেজ থাকে (নিরাপদ থাকার জন্য)
    }).then(canvas => {
        // ক্যানভাস থেকে ইমেজ ডাটা তৈরি
        const image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
        
        // একটি অস্থায়ী ডাউনলোড লিঙ্ক তৈরি
        const link = document.createElement('a');
        link.download = 'Class_Routine_Nine_' + new Date().toISOString().slice(0, 10) + '.png'; // ফাইলের নাম
        link.href = image;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // লিঙ্কটি মুছে ফেলা

        // লোডিং বন্ধ করা
        Swal.close();
        
        // সফলতার মেসেজ (অপশনাল)
        // Swal.fire('Downloaded!', 'Routine image saved to your device.', 'success');
    }).catch(err => {
        Swal.fire('Error', 'Image generation failed: ' + err, 'error');
    });
}
</script>