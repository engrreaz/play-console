<?php
$page_title = "What's New";
include 'inc.php'; 

// ১. ইউজারের লাস্ট সিন আইডি বের করা
$last_seen_id = 0;
$stmt_seen = $conn->prepare("SELECT last_seen_id FROM user_timeline_seen WHERE userid = ?");
$stmt_seen->bind_param("s", $userid);
$stmt_seen->execute();
$res_seen = $stmt_seen->get_result();
if($row_seen = $res_seen->fetch_assoc()) {
    $last_seen_id = $row_seen['last_seen_id'];
}
$stmt_seen->close();

// ২. অ্যান্ড্রয়েড প্ল্যাটফর্মের টাইমলাইন ফেচ করা
$timeline = [];
$max_id = 0;
$sql = "SELECT * FROM dev_timeline WHERE platform = 'Android' ORDER BY created_at DESC";
$res = $conn->query($sql);
while($row = $res->fetch_assoc()){
    $timeline[] = $row;
    if($row['id'] > $max_id) $max_id = $row['id'];
}

// ৩. এই পেজ ভিজিট করলে লাস্ট সিন আইডি আপডেট করা (Tracking logic)
if($max_id > $last_seen_id) {
    $stmt_upd = $conn->prepare("INSERT INTO user_timeline_seen (userid, last_seen_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE last_seen_id = ?");
    $stmt_upd->bind_param("sii", $userid, $max_id, $max_id);
    $stmt_upd->execute();
    $stmt_upd->close();
}
?>

<style>
    :root { --m3-primary: #6750A4; --m3-surface: #FEF7FF; }
    body { background: var(--m3-surface); }
    
    .timeline-container { padding: 16px; position: relative; }
    .timeline-container::before {
        content: ''; position: absolute; left: 28px; top: 20px; bottom: 20px;
        width: 2px; background: #EADDFF; z-index: 0;
    }

    .log-card {
        background: white; border-radius: 16px; padding: 16px;
        margin-left: 36px; margin-bottom: 20px; position: relative;
        border: 1px solid #E7E0EC; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .log-card::before {
        content: ''; position: absolute; left: -22px; top: 18px;
        width: 12px; height: 12px; border-radius: 50%;
        background: var(--m3-primary); border: 3px solid #FEF7FF; z-index: 1;
    }

    .new-badge {
        background: #B3261E; color: white; font-size: 0.6rem;
        font-weight: 900; padding: 2px 8px; border-radius: 4px;
        text-transform: uppercase; margin-left: 8px;
    }

    .type-chip {
        font-size: 0.65rem; font-weight: 800; padding: 4px 10px;
        border-radius: 8px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    
    /* Action Type Colors */
    .type-implement { background: #E8F5E9; color: #2E7D32; }
    .type-bug_fix { background: #FFEBEE; color: #B3261E; }
    .type-security_patch { background: #FFF3E0; color: #E65100; }
    .type-refactor { background: #E1F5FE; color: #0288D1; }
</style>

<main class="pb-5">
    <div class="hero-container shadow-sm" style="background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%); color:white; border-radius: 0 0 28px 28px;">
        <div class="d-flex align-items-center gap-3">
            <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 16px;">
                <i class="bi bi-rocket-takeoff-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-black m-0">What's New</h4>
                <div class="small opacity-75 fw-bold text-uppercase">Android Version Changelog</div>
            </div>
        </div>
    </div>

    <div class="timeline-container">
        <?php if(empty($timeline)): ?>
            <div class="text-center p-5 opacity-50">
                <i class="bi bi-clipboard-x fs-1"></i>
                <p class="mt-2 fw-bold">No updates recorded yet.</p>
            </div>
        <?php else: ?>
            <?php foreach($timeline as $log): 
                $is_new = ($log['id'] > $last_seen_id);
            ?>
            <div class="log-card shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="type-chip type-<?= $log['action_type'] ?>">
                            <?= str_replace('_', ' ', $log['action_type']) ?>
                        </span>
                        <?php if($is_new) echo '<span class="new-badge">NEW</span>'; ?>
                    </div>
                    <div class="small text-muted fw-bold" style="font-size: 0.65rem;">
                        <i class="bi bi-clock me-1"></i><?= date('M d, Y', strtotime($log['created_at'])) ?>
                    </div>
                </div>

                <h6 class="fw-black text-dark m-0 mt-2" style="font-size: 1.05rem;">
                    <?= $log['feature_name'] ?>
                </h6>
                <div class="small text-primary fw-bold mb-2"><?= $log['page_name'] ?></div>
                
                <p class="text-muted small mb-0" style="line-height: 1.5;">
                    <?= !empty($log['description']) ? $log['description'] : "New feature optimized and implemented for better user experience." ?>
                </p>

                <div class="mt-3 d-flex align-items-center justify-content-between">
                    <span class="badge rounded-pill bg-light text-dark border px-3" style="font-size: 0.6rem; font-weight: 800;">
                        STATUS: <?= strtoupper($log['status']) ?>
                    </span>
                    <div class="small fw-bold opacity-50" style="font-size: 0.6rem;">By: <?= $log['logged_by'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>