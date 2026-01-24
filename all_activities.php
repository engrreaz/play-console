<?php
include 'inc.php';

/* ---------------- ১. সেশন লিস্ট ফেচিং ---------------- */
$sessions = $conn->query("SELECT DISTINCT sessionyear FROM student_activities ORDER BY sessionyear DESC");

/* ---------------- ২. ফিল্টার লজিক ---------------- */
$where = "WHERE 1";
$sessionSel = $_GET['session'] ?? "";
if (!empty($sessionSel)) {
    $sessionSel = mysqli_real_escape_string($conn, $sessionSel);
    $where .= " AND sa.sessionyear='$sessionSel'";
}

/* ---------------- ৩. ডাটা ফেচিং (Sorted) ---------------- */
$sql = "SELECT sa.*, st.stnameeng, am.title AS activity_name, ac.name AS category
        FROM student_activities sa
        LEFT JOIN students st ON st.stid = sa.stid
        LEFT JOIN activities_master am ON sa.activity_id = am.id
        LEFT JOIN activity_categories ac ON am.category = ac.name
        $where
        ORDER BY sa.sessionyear DESC, sa.created_at DESC";

$activities = $conn->query($sql);
?>

<style>
    /* ১. রিপোর্ট স্পেসিফিক স্টাইল */
    .report-hero { padding-bottom: 35px; border-radius: 0 0 24px 24px; }
    
    .filter-overlay { margin: -25px 12px 20px; position: relative; z-index: 10; }

    .activity-report-card {
        padding: 16px; margin-bottom: 12px;
        border: 1px solid rgba(0,0,0,0.04);
        display: flex; flex-direction: column; gap: 8px;
    }

    .badge-m3 {
        font-size: 0.65rem; font-weight: 800; padding: 3px 10px;
        border-radius: 6px; text-transform: uppercase;
    }
    .badge-session { background: var(--m3-tonal-container); color: var(--m3-on-tonal-container); }
    .badge-award { background: #FFF8E1; color: #FF8F00; border: 1px solid #FFECB3; }

    .info-grid {
        display: grid; grid-template-columns: 1.2fr 1fr; gap: 10px;
        margin-top: 8px; padding-top: 8px; border-top: 1px dashed #eee;
    }
    .info-label { font-size: 0.65rem; font-weight: 800; color: #999; text-transform: uppercase; }
    .info-val { font-size: 0.85rem; font-weight: 700; color: #444; }

    /* প্রিন্ট ভিউ অ্যাডজাস্টমেন্ট */
    @media print {
        .hero-container, .filter-overlay, .tonal-icon-btn, .m3-fab-print { display: none !important; }
        .activity-report-card { border: 1px solid #ccc; break-inside: avoid; }
        body { background: white; }
    }

    .m3-fab-print {
        position: fixed; bottom: 85px; right: 20px;
        background: var(--m3-primary-gradient); color: #fff;
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.3); z-index: 1050; border: none;
    }
</style>

<main>
    <div class="hero-container report-hero">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 950; line-height: 1.1;">Institutional Report</div>
                    <div style="font-size: 0.8rem; opacity: 0.9;">Co-Curricular Master Ledger</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.8rem; font-weight: 950; line-height: 1;"><?php echo $activities->num_rows; ?></div>
                <div style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Records</div>
            </div>
        </div>
    </div>

    <div class="filter-overlay">
        <div class="m3-card shadow-sm" style="padding: 12px 16px;">
            <form method="get" id="filterForm" class="row g-2 align-items-center">
                <div class="col-8">
                    <div class="m3-floating-group" style="margin-bottom: 0;">
                        <i class="bi bi-calendar3 m3-field-icon"></i>
                        <select name="session" class="m3-select-floating" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Sessions</option>
                            <?php 
                            $sessions->data_seek(0);
                            while($s=$sessions->fetch_assoc()): ?>
                                <option value="<?php echo $s['sessionyear']; ?>" <?php if($sessionSel==$s['sessionyear']) echo 'selected'; ?>>
                                    Academic Year <?php echo $s['sessionyear']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <label class="m3-floating-label">FILTER BY SESSION</label>
                    </div>
                </div>
                <div class="col-4">
                    <?php if($sessionSel != ""): ?>
                        <button type="button" class="btn btn-light w-100" style="border-radius: 8px; font-weight: 800; font-size: 0.75rem; height: 50px;" onclick="location.href='all_cocurricular_report.php'">
                            <i class="bi bi-x-circle me-1"></i> RESET
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="widget-grid" style="padding: 0 12px 100px;">
        <?php if($activities->num_rows > 0): ?>
            <?php while($r=$activities->fetch_assoc()): ?>
                <div class="m3-list-item activity-report-card shadow-sm">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 12px;">
                            <div class="icon-box c-inst" style="width: 44px; height: 44px; border-radius: 10px;">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.95rem; font-weight: 900; color: #1C1B1F;"><?php echo $r['stnameeng']; ?></div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #666;">ID: <?php echo $r['stid']; ?></div>
                            </div>
                        </div>
                        <span class="badge-m3 badge-session"><?php echo $r['sessionyear']; ?></span>
                    </div>

                    <div style="margin-top: 5px;">
                        <div style="font-size: 0.9rem; font-weight: 800; color: var(--m3-primary);"><?php echo $r['activity_name']; ?></div>
                        <div style="display: flex; gap: 8px; margin-top: 4px;">
                            <span class="badge-m3" style="background: #E8F5E9; color: #2E7D32;">Category: <?php echo $r['category']; ?></span>
                            <?php if($r['award']): ?>
                                <span class="badge-m3 badge-award"><?php echo $r['award']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div>
                            <div class="info-label">Level / Role</div>
                            <div class="info-val"><?php echo $r['level']; ?> • <?php echo $r['role']; ?></div>
                        </div>
                        <div>
                            <div class="info-label">Instructor</div>
                            <div class="info-val"><?php echo $r['teacher'] ?: 'N/A'; ?></div>
                        </div>
                    </div>

                    <?php if($r['remarks']): ?>
                        <div style="font-size: 0.75rem; font-style: italic; color: #777; margin-top: 5px; background: #f9f9f9; padding: 6px 10px; border-radius: 6px;">
                            "<?php echo $r['remarks']; ?>"
                        </div>
                    <?php endif; ?>
                    
                    <div style="text-align: right; margin-top: 5px;">
                        <button class="btn btn-link p-0 text-decoration-none" style="font-size: 0.7rem; font-weight: 800; color: var(--m3-primary);" onclick="location.href='st-activities-view.php?stid=<?php echo $r['stid']; ?>'">
                            VIEW PORTFOLIO <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                <i class="bi bi-search" style="font-size: 3.5rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">No Records Found</div>
            </div>
        <?php endif; ?>
    </div>

    <button class="m3-fab-print shadow-lg" onclick="window.print()">
        <i class="bi bi-printer-fill" style="font-size: 1.5rem;"></i>
    </button>
</main>



<?php include 'footer.php'; ?>