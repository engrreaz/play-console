<?php
/**
 * স্মার্ট হাজিরা ব্লক - সামারি না থাকলে র-ডেটা থেকে ক্যালকুলেট করবে
 * মার্কিং: "Not Synced" (যদি stattndsummery তে ডেটা না থাকে)
 */

$class_attendance_data = [];

if (isset($cteacher_data, $conn, $sccode, $sy_param, $td) && !empty($cteacher_data)) {
    
    foreach ($cteacher_data as $class) {
        $cls = $class['cteachercls'];
        $sec = $class['cteachersec'];
        
        // ১. প্রথমে stattndsummery তে চেক করা
        $stmt_sum = $conn->prepare("SELECT totalstudent, attndstudent, bunk, attndrate FROM stattndsummery WHERE sccode = ? AND date = ? AND classname = ? AND sectionname = ? LIMIT 1");
        $stmt_sum->bind_param("isss", $sccode, $td, $cls, $sec);
        $stmt_sum->execute();
        $res_sum = $stmt_sum->get_result();
        $sum_data = $res_sum->fetch_assoc();
        $stmt_sum->close();

        if ($sum_data) {
            // ডেটা সিঙ্ক করা আছে
            $row = [
                'classname' => $cls,
                'sectionname' => $sec,
                'total' => $sum_data['totalstudent'],
                'present' => $sum_data['attndstudent'],
                'bunk' => $sum_data['bunk'],
                'rate' => $sum_data['attndrate'],
                'is_synced' => true
            ];
        } else {
            // ২. ডেটা সিঙ্ক করা নেই, stattnd থেকে র-ক্যালকুলেশন
            $stmt_raw = $conn->prepare("SELECT COUNT(*) as total, SUM(yn) as present, SUM(bunk) as bunks FROM stattnd WHERE sccode = ? AND adate = ? AND classname = ? AND sectionname = ?");
            $stmt_raw->bind_param("isss", $sccode, $td, $cls, $sec);
            $stmt_raw->execute();
            $raw_data = $stmt_raw->get_result()->fetch_assoc();
            $stmt_raw->close();

            $total = $raw_data['total'] ?? 0;
            $present = $raw_data['present'] ?? 0;
            $bunk = $raw_data['bunks'] ?? 0;
            $rate = ($total > 0) ? round(($present * 100) / $total) : 0;

            $row = [
                'classname' => $cls,
                'sectionname' => $sec,
                'total' => $total,
                'present' => $present,
                'bunk' => $bunk,
                'rate' => $rate,
                'is_synced' => false
            ];
        }

        // বাংকিং পার্সেন্টেজ ক্যালকুলেশন
        $row['bunk_percent'] = ($row['total'] > 0) ? ceil(($row['bunk'] * 100) / $row['total']) : 0;
        $row['effective_rate'] = max(0, $row['rate'] - $row['bunk_percent']);
        
        $class_attendance_data[] = $row;
    }
}

if (!empty($class_attendance_data)):
?>

<style>
    .m3-att-block { background: #fff; border-radius: 12px; padding: 16px; border: 1px solid #eee; margin-bottom: 15px; }
    .att-card { background: #fff; border-radius: 12px; padding: 12px; margin-bottom: 10px; border: 1px solid #f0f0f0; position: relative; }
    
    /* সিঙ্ক মার্কিং */
    .sync-badge { font-size: 0.5rem; padding: 2px 6px; border-radius: 4px; font-weight: 800; text-transform: uppercase; }
    .not-synced { background: #FFE0B2; color: #E65100; border: 1px solid #FFCC80; }
    .is-synced { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }

    /* প্রোগ্রেস বার */
    .att-progress-track { background: #F1F1F1; height: 6px; border-radius: 10px; overflow: hidden; display: flex; margin: 8px 0; }
    .bar-present { background: #6750A4; height: 100%; transition: 0.5s; }
    .bar-bunk { background: #FFB300; height: 100%; transition: 0.5s; }

    .stat-lbl { font-size: 0.55rem; color: #757575; font-weight: 700; text-transform: uppercase; }
    .stat-val { font-size: 0.85rem; font-weight: 800; color: #212121; }

    /* সিঙ্ক বাটন */
    .sync-btn { border: none; background: #6750A4; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.6rem; font-weight: 700; }
    .sync-btn:disabled { background: #ccc; }
</style>

<div class="m3-att-block shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-black m-0" style="font-size: 0.9rem; color: #6750A4;"><i class="bi bi-calendar2-check-fill me-2"></i>Attendance Status</h6>
        <div class="small fw-bold text-muted" style="font-size: 0.7rem;"><?= date('d M, Y', strtotime($td)) ?></div>
    </div>

    <?php foreach ($class_attendance_data as $att): ?>
        <div class="att-card shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div>
                    <span class="fw-black text-dark" style="font-size: 0.85rem;"><?= $att['classname'] ?> - <?= $att['sectionname'] ?></span>
                    <div class="mt-1">
                        <?php if($att['is_synced']): ?>
                            <span class="sync-badge is-synced"><i class="bi bi-check-circle-fill me-1"></i>Synced</span>
                        <?php else: ?>
                            <span class="sync-badge not-synced"><i class="bi bi-cloud-slash-fill me-1"></i>Not Synced</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-black" style="font-size: 1.2rem; color: #6750A4; line-height:1;"><?= $att['effective_rate'] ?>%</div>
                    <?php if(!$att['is_synced'] && $att['total'] > 0): ?>
                        <button class="sync-btn mt-2" onclick="syncNow('<?= $att['classname'] ?>', '<?= $att['sectionname'] ?>', this)">
                            <i class="bi bi-arrow-repeat"></i> Sync
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="att-progress-track">
                <div class="bar-present" style="width: <?= $att['effective_rate'] ?>%"></div>
                <div class="bar-bunk" style="width: <?= $att['bunk_percent'] ?>%"></div>
            </div>

            <div class="row g-0 text-center mt-2 border-top pt-2">
                <div class="col-4 border-end">
                    <div class="stat-lbl">Present</div>
                    <div class="stat-val"><?= $att['present'] ?></div>
                </div>
                <div class="col-4 border-end">
                    <div class="stat-lbl">Total</div>
                    <div class="stat-val"><?= $att['total'] ?></div>
                </div>
                <div class="col-4">
                    <div class="stat-lbl text-danger">Bunk</div>
                    <div class="stat-val text-danger"><?= $att['bunk'] ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
/**
 * হাজিরা সিঙ্ক করার ফাংশন
 */
function syncNow(cls, sec, btn) {
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    $.ajax({
        url: 'backend/sync-attendance.php', // এই ফাইলটি আপনাকে তৈরি করতে হবে
        type: 'POST',
        data: { classname: cls, sectionname: sec, date: '<?= $td ?>' },
        success: function(response) {
            if(response.trim() === 'success') {
                Swal.fire({ icon: 'success', title: 'Synced!', timer: 1000, showConfirmButton: false });
                setTimeout(() => location.reload(), 1000);
            } else {
                Swal.fire('Error', response, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}
</script>

<?php endif; ?>