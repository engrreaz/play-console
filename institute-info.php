<?php
/**
 * Institution Profile View - M3-EIM-Floating Design
 * Optimized for: Android Webview | 8px Radius | Visual Hierarchy
 */
$page_title = "Institution Profile";
include 'inc.php';

// ডাটা ফেচিং লজিক
$stmt = $conn->prepare("SELECT * FROM scinfo WHERE sccode = ? LIMIT 1");
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) { die("Institution data not found."); }

// ডাটা প্রসেসিং
$valid_mods = explode(" | ", $row['valid_module'] ?? "");
$active_mods = explode(" | ", $row['active_module'] ?? "");
$is_expired = strtotime($row['expire']) < time();
?>

<style>
    body { background-color: #F7F2FA; } /* M3 Surface Tone */

    /* ১. প্রিমিয়াম হিরো সেকশন */
    .m3-profile-hero {
        background: linear-gradient(180deg, #6750A4 0%, #4F378B 100%);
        padding: 40px 16px 60px;
        text-align: center;
        color: #fff;
        border-radius: 0 0 28px 28px;
        position: relative;
    }

    .m3-logo-wrapper {
        width: 110px; height: 110px;
        background: #fff;
        border-radius: 20px;
        padding: 8px;
        margin: 0 auto 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .m3-logo-wrapper img { width: 100%; height: 100%; object-fit: contain; }

    /* ২. স্ট্যাটাস চিপস */
    .status-pill {
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
    }
    .status-active { background: #E8F5E9; color: #2E7D32; }
    .status-expired { background: #FDE7E9; color: #B3261E; }

    /* ৩. ইনফরমেশন কার্ড গ্রিড */
    .info-card {
        background: #fff;
        border-radius: 12px; /* Standard 8-12px M3 */
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .section-label {
        font-size: 0.7rem;
        font-weight: 900;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .data-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #f3f3f3;
    }
    .data-row:last-child { border-bottom: none; }
    
    .data-icon { color: #6750A4; width: 30px; font-size: 1.1rem; }
    .data-content { flex: 1; }
    .data-label { font-size: 0.7rem; color: #777; font-weight: 600; }
    .data-value { font-size: 0.9rem; color: #1C1B1F; font-weight: 700; }

    /* ৪. মডিউল চিপস */
    .mod-chip {
        background: #F3EDF7;
        color: #4F378B;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        margin: 2px;
        display: inline-block;
    }

    .floating-edit-btn {
        position: fixed;
        bottom: 84px;
        right: 24px;
        background: #6750A4;
        color: white;
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.4);
        text-decoration: none;
        z-index: 1000;
    }
</style>

<main class="pb-5">
    <div class="m3-profile-hero">
        <div class="m3-logo-wrapper">
            <img src="<?php echo $BASE_PATH_URL . 'logo/' . $sccode . '.png'; ?>" 
                 onerror="this.src='https://eimbox.com/images/no-image.png'">
        </div>
        <h4 class="fw-bold mb-1 px-3"><?php echo $row['scname']; ?></h4>
        <div class="small opacity-75">EIIN: <?php echo $sccode; ?> | <?php echo $row['sccategory']; ?></div>
        
        <div class="status-pill <?php echo ($row['active'] == 1 && !$is_expired) ? 'status-active' : 'status-expired'; ?>">
            <i class="bi <?php echo ($row['active'] == 1) ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?>"></i>
            <?php echo ($row['active'] == 1 && !$is_expired) ? 'SYSTEM ACTIVE' : 'SUBSCRIPTION ALERT'; ?>
        </div>
    </div>

    <div class="container-fluid mt-n4" style="margin-top: -25px; position: relative; z-index: 10;">
        
        <div class="info-card shadow-sm">
            <div class="section-label"><i class="bi bi-info-circle-fill"></i> General Identity</div>
            <div class="data-row">
                <i class="bi bi-person-badge data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Head of Institution</div>
                    <div class="data-value"><?php echo $row['headname']; ?> <small class="text-muted">(<?php echo $row['headtitle']; ?>)</small></div>
                </div>
            </div>
            <div class="data-row">
                <i class="bi bi-alphabet-uppercase data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Short Name / Code</div>
                    <div class="data-value"><?php echo $row['short']; ?></div>
                </div>
            </div>
        </div>

        <div class="info-card shadow-sm">
            <div class="section-label"><i class="bi bi-geo-alt-fill"></i> Contact & Location</div>
            <div class="data-row">
                <i class="bi bi-telephone data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Official Mobile</div>
                    <div class="data-value"><?php echo $row['mobile']; ?></div>
                </div>
            </div>
            <div class="data-row">
                <i class="bi bi-envelope-at data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Email Address</div>
                    <div class="data-value"><?php echo $row['scmail'] ?: 'N/A'; ?></div>
                </div>
            </div>
            <div class="data-row">
                <i class="bi bi-globe data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Website</div>
                    <div class="data-value text-primary small"><?php echo $row['scweb'] ?: 'N/A'; ?></div>
                </div>
            </div>
            <div class="data-row">
                <i class="bi bi-geo data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Location Address</div>
                    <div class="data-value"><?php echo $row['scadd1'] . ', ' . $row['ps'] . ', ' . $row['dist']; ?></div>
                </div>
            </div>
        </div>

<?php 
// মডিউল এবং প্যানেল ডাটা প্রসেসিং
$valid_mods = array_map('trim', explode("|", $row['valid_module']));
$active_mods = array_map('trim', explode("|", $row['active_module']));

// JSON থেকে প্যানেল ডাটা বের করা
$admin_json = json_decode($row['admin_data'], true);
$active_panels = $admin_json['panel'] ?? [];
?>

<style>
    /* মডিউল চিপস স্টাইল */
    .mod-chip {
        padding: 4px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800;
        margin: 3px; display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid #e0e0e0; transition: 0.3s;
    }
    .mod-active { background: #E8F5E9; color: #2E7D32; border-color: #A5D6A7; }
    .mod-inactive { background: #f5f5f5; color: #9e9e9e; opacity: 0.7; }
    
    .panel-pill {
        background: #e3f2fd; color: #1976d2; padding: 2px 10px;
        border-radius: 6px; font-size: 0.6rem; font-weight: 900; text-transform: uppercase;
    }
</style>

<div class="info-card shadow-sm">
    <div class="section-label"><i class="bi bi-box-seam-fill"></i> Subscription & Access</div>
    
    <div class="row mb-3 bg-light p-2 rounded-3 mx-0">
        <div class="col-6 border-end">
            <div class="data-label">Plan</div>
            <div class="data-value text-primary"><?php echo $row['package_name']; ?></div>
        </div>
        <div class="col-6">
            <div class="data-label">Expires</div>
            <div class="data-value <?php echo $is_expired ? 'text-danger' : ''; ?>">
                <?php echo date('d M, Y', strtotime($row['expire'])); ?>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="data-label mb-2"><i class="bi bi-person-workspace me-1"></i> Authorized Access Panels</div>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach($active_panels as $panel): ?>
                <span class="panel-pill"><?php echo $panel; ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="data-label mb-2"><i class="bi bi-grid-3x3-gap-fill me-1"></i> Service Modules Status</div>
    <div class="d-flex flex-wrap">
        <?php foreach($valid_mods as $v_mod): 
            $is_act = in_array($v_mod, $active_mods);
        ?>
            <span class="mod-chip <?php echo $is_act ? 'mod-active' : 'mod-inactive'; ?>">
                <i class="bi <?php echo $is_act ? 'bi-check-circle-fill' : 'bi-dash-circle'; ?>"></i>
                <?php echo $v_mod; ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>



<div class="info-card shadow-sm">
    <div class="section-label"><i class="bi bi-geo-alt-fill"></i> Geo-fencing & Tracking</div>
    
    <div class="row g-3">
        <div class="col-12">
            <div class="p-3 rounded-3" style="background: #FFF8E1; border: 1px dashed #FFD54F;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="data-label">GPS Coordinates</div>
                        <div class="data-value font-monospace" style="font-size: 0.8rem;">
                            <?php echo $row['geolat']; ?>, <?php echo $row['geolon']; ?>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <a href="https://www.google.com/maps?q=<?php echo $row['geolat'].','.$row['geolon']; ?>" target="_blank" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm">
                            <i class="bi bi-map"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="data-row border-0">
                <i class="bi bi-radar data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Fence Radius</div>
                    <div class="data-value"><?php echo $row['dista_differ']; ?> Meters</div>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="data-row border-0">
                <i class="bi bi-clock-history data-icon"></i>
                <div class="data-content">
                    <div class="data-label">Time Buffer</div>
                    <div class="data-value"><?php echo $row['time_differ'] / 60; ?> Mins</div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-0">
            <div class="d-flex gap-3 p-2 bg-light rounded-3 justify-content-center">
                <div class="text-center">
                    <div class="data-label">Standard In</div>
                    <div class="fw-bold text-success"><i class="bi bi-box-arrow-in-right"></i> <?php echo date('h:i A', strtotime($row['intime'])); ?></div>
                </div>
                <div class="vr"></div>
                <div class="text-center">
                    <div class="data-label">Standard Out</div>
                    <div class="fw-bold text-danger"><i class="bi bi-box-arrow-right"></i> <?php echo date('h:i A', strtotime($row['outtime'])); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="row gx-2">
            <div class="col-6">
                <div class="info-card shadow-sm text-center">
                    <div class="data-label">SMS Balance</div>
                    <div class="data-value">৳ <?php echo number_format($row['sms_balance'], 2); ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="info-card shadow-sm text-center">
                    <div class="data-label">Acc. Balance</div>
                    <div class="data-value">৳ <?php echo number_format($row['account_balance'], 2); ?></div>
                </div>
            </div>
        </div>

    </div>

    <a href="settings-institute-info.php" class="floating-edit-btn">
        <i class="bi bi-pencil-square fs-4"></i>
    </a>
</main>

<div style="height: 80px;"></div>

<?php include 'footer.php'; ?>