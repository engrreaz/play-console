<?php
$page_title = "Institution Settings";
include 'inc.php'; // DB এবং $sccode এখানে আছে


// সেভিং হ্যান্ডলার (POST রিকোয়েস্টের জন্য)
// সেভিং হ্যান্ডলার (POST রিকোয়েস্টের জন্য)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['Session']) || isset($_POST['Weekends']))) {

    foreach ($_POST as $title => $value) {
        // সেশন আলাদা টেবিল তাই মেইন সেটিংস লুপে এটি স্কিপ হবে
        if ($title == 'Session') continue; 

        $val_string = is_array($value) ? implode('.', $value) : $value;
        $clean_title = str_replace('_', ' ', $title);

        $stmt = $conn->prepare("SELECT id FROM settings WHERE sccode=? AND setting_title=?");
        $stmt->bind_param("is", $sccode, $clean_title);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();

        if ($exists) {
            $upd = $conn->prepare("UPDATE settings SET settings_value=?, modifieddate=NOW() WHERE id=?");
            $upd->bind_param("si", $val_string, $exists['id']);
        } else {
            $upd = $conn->prepare("INSERT INTO settings (setting_title, settings_value, sccode, modifieddate) VALUES (?, ?, ?, NOW())");
            $upd->bind_param("ssi", $clean_title, $val_string, $sccode);
        }
        $upd->execute();
    }

    // --- সেশন ইয়ার আপডেট লজিক (একাধিক সেশন সাপোর্ট) ---
    // প্রথমে সব সেশন ইন-অ্যাক্টিভ (0) করা
    $conn->query("UPDATE sessionyear SET active=0 WHERE sccode='$sccode'");

    if (isset($_POST['Session']) && is_array($_POST['Session'])) {
        foreach ($_POST['Session'] as $active_syear) {
            // সিলেক্ট করা প্রতিটি সেশনকে অ্যাক্টিভ করা
            $conn->query("UPDATE sessionyear SET active=1 WHERE sccode='$sccode' AND syear='$active_syear'");
        }
    }

    // সাইলেন্ট ক্লিনআপ: ডুপ্লিকেট মুছে ফেলা (সবচেয়ে ছোট ID রেখে বাকি সব ডিলিট হবে)
    // $cleanup_sql = "DELETE t1 FROM sessionyear t1
    //                 INNER JOIN sessionyear t2 
    //                 WHERE t1.id > t2.id 
    //                 AND t1.syear = t2.syear 
    //                 AND t1.sccode = '$sccode'";
    // $conn->query($cleanup_sql);

    echo "success";
    exit;
}





// ১. সেটিংস ডাটা ফেচ করা (সব সেটিংস একবারেই আনা)
$set_res = $conn->query("SELECT * FROM settings WHERE sccode = '$sccode'");
$current_settings = [];
while ($row = $set_res->fetch_assoc()) {
    $current_settings[$row['setting_title']] = $row['settings_value'];
}

// ২. সেশন ইয়ার ফেচ করা (sessionyear টেবিল থেকে)
// ২. সেশন ইয়ার ফেচ করা (ডুপ্লিকেট ফিল্টার করে)
$session_list = [];
$session_res = $conn->query("SELECT syear, MAX(active) as active 
                             FROM sessionyear 
                             WHERE sccode = '$sccode' 
                             GROUP BY syear 
                             ORDER BY syear DESC");

while ($s = $session_res->fetch_assoc()) {
    $session_list[] = $s;
}

// ৩. ডাটা প্রসেসিং (স্ট্রিং থেকে অ্যারেতে রূপান্তর)
function getSetArray($title, $settings)
{
    $val = $settings[$title] ?? '';
    // কমা বা ডট যেটাই থাক, অ্যারে করবে
    return preg_split('/[.,]+/', $val, -1, PREG_SPLIT_NO_EMPTY);
}

$active_weekends = getSetArray('Weekends', $current_settings);
$active_mediums = getSetArray('Medium', $current_settings);
$active_versions = getSetArray('Version', $current_settings);
$active_classes = getSetArray('Classes', $current_settings);
$active_collect = getSetArray('Collection', $current_settings);
$active_pentry = getSetArray('Profile Entry', $current_settings);

// অপশন লিস্ট
$days = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
$mediums = ['Bengali', 'English', 'Arabic'];
$versions = ['Bengali', 'English'];
$classes = ['Play', 'Nursery', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'SSC', 'Eleven', 'Twelve', 'HSC'];
$roles = ['Administrator', 'Chief', 'Teacher', 'Accountants', 'Head Teacher'];


// হিরো সেকশনে দেখানোর জন্য কিছু কুইক কাউন্ট
$count_classes = count($active_classes);
$count_sessions = count(array_filter($session_list, fn($s) => $s['active'] == 1));
$count_holidays = count($active_weekends);
?>

<style>
    .m3-settings-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid #E7E0EC;
    }

    .m3-chip-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .m3-chip-input {
        display: none;
    }

    .m3-chip-label {
        padding: 8px 16px;
        border-radius: 12px;
        background: #F3EDF7;
        color: #1C1B1F;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #CAC4D0;
    }

    .m3-chip-input:checked+.m3-chip-label {
        background: #6750A4;
        color: white;
        border-color: #6750A4;
    }

    .save-fab {
        position: fixed;
        bottom: 80px;
        right: 20px;
        background: #6750A4;
        color: white;
        border-radius: 16px;
        padding: 16px 24px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 100;
        font-weight: 900;
    }
</style>

<style>
    /* এই রুলটি চেক বক্স এবং রেডিও বাটন দুটোর জন্যই কাজ করবে */
    .m3-chip-input {
        display: none;
    }

    .m3-chip-label {
        padding: 10px 20px;
        border-radius: 14px;
        background: #F3EDF7;
        color: #1C1B1F;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid #CAC4D0;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .m3-chip-input:checked+.m3-chip-label {
        background: var(--m3-primary);
        color: white;
        border-color: var(--m3-primary);
        box-shadow: 0 2px 8px rgba(103, 80, 164, 0.3);
    }
</style>

<style>
    /* Settings Hero Styling */
    .settings-hero {
        background: linear-gradient(135deg, #6750A4 0%, #311B92 100%);
        color: white;
        padding: 40px 24px 65px;
        border-radius: 0 0 36px 36px;
        position: relative;
        text-align: center;
        box-shadow: 0 10px 30px rgba(103, 80, 164, 0.2);
    }

    .hero-icon-circle {
        width: 64px; height: 64px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-stat-chips {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .mini-chip {
        background: rgba(255, 255, 255, 0.15);
        padding: 5px 12px;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 800;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-transform: uppercase;
        display: flex; align-items: center; gap: 5px;
    }

    /* Floating effect for main cards below hero */
    .main-content-wrapper {
        margin-top: -35px;
        position: relative;
        z-index: 11;
    }
</style>

<div class="settings-hero">
    <div class="hero-icon-circle shadow-sm">
        <i class="bi bi-gear-wide-connected"></i>
    </div>
    <h4 class="fw-black mb-1">System Configuration</h4>
    <p class="small opacity-75 fw-bold mb-0">Institution ID: <?= $sccode ?></p>

    <div class="hero-stat-chips">
        <div class="mini-chip shadow-sm">
            <i class="bi bi-mortarboard-fill"></i> <?= $count_classes ?> Classes
        </div>
        <div class="mini-chip shadow-sm">
            <i class="bi bi-calendar3-range"></i> <?= $count_sessions ?> Active Sessions
        </div>
        <div class="mini-chip shadow-sm">
            <i class="bi bi-sun-fill"></i> <?= $count_holidays ?> Holidays
        </div>
    </div>
</div>

<div class="main-content-wrapper">
    </div>

<main class="container py-4 mb-5">
    <form id="settingsForm">
        <div class="m3-settings-card shadow-sm">
            <h6 class="fw-bold"><i class="bi bi-calendar-x me-2"></i> Weekly Holidays</h6>
            <div class="m3-chip-group">
                <?php foreach ($days as $d): ?>
                    <input type="checkbox" name="Weekends[]" value="<?= $d ?>" id="day_<?= $d ?>" class="m3-chip-input"
                        <?= in_array($d, $active_weekends) ? 'checked' : '' ?>>
                    <label for="day_<?= $d ?>" class="m3-chip-label"><?= $d ?></label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6">
                <div class="m3-settings-card shadow-sm h-100">
                    <h6 class="fw-bold">Medium</h6>
                    <?php foreach ($mediums as $m): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="Medium[]" value="<?= $m ?>"
                                id="m_<?= $m ?>" <?= in_array($m, $active_mediums) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold small" for="m_<?= $m ?>"><?= $m ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-6">
                <div class="m3-settings-card shadow-sm h-100">
                    <h6 class="fw-bold">Version</h6>
                    <?php foreach ($versions as $v): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="Version[]" value="<?= $v ?>"
                                id="v_<?= $v ?>" <?= in_array($v, $active_versions) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold small" for="v_<?= $v ?>"><?= $v ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="m3-settings-card shadow-sm mt-3">
            <h6 class="fw-bold"><i class="bi bi-mortarboard me-2"></i> Active Classes</h6>
            <div class="m3-chip-group">
                <?php foreach ($classes as $c): ?>
                    <input type="checkbox" name="Classes[]" value="<?= $c ?>" id="c_<?= $c ?>" class="m3-chip-input"
                        <?= in_array($c, $active_classes) ? 'checked' : '' ?>>
                    <label for="c_<?= $c ?>" class="m3-chip-label"><?= $c ?></label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="m3-settings-card shadow-sm">
    <h6 class="fw-bold"><i class="bi bi-calendar-check me-2"></i> Academic Session</h6>
    <div class="m3-chip-group">
        <?php foreach ($session_list as $sl): ?>
            <input type="checkbox" name="Session[]" value="<?= $sl['syear'] ?>" 
                   id="sess_<?= $sl['syear'] ?>" class="m3-chip-input" 
                   <?= $sl['active'] == 1 ? 'checked' : '' ?>>

            <label for="sess_<?= $sl['syear'] ?>" class="m3-chip-label">
                Session <?= $sl['syear'] ?>
            </label>
        <?php endforeach; ?>
    </div>
    <div class="mt-2 px-1" style="font-size: 0.7rem; color: var(--m3-outline); font-weight: 600;">
        <i class="bi bi-info-circle me-1"></i> You can select multiple active sessions simultaneously.
    </div>
</div>

        <div class="m3-settings-card shadow-sm">
            <h6 class="fw-bold">Permissions (Roles)</h6>
            <div class="row mt-3">
                <div class="col-6 border-end">
                    <small class="text-muted fw-bold d-block mb-2">PAYMENT COLLECTION</small>
                    <?php foreach ($roles as $r): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="Collection[]" value="<?= $r ?>"
                                id="coll_<?= $r ?>" <?= in_array($r, $active_collect) ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="coll_<?= $r ?>"><?= $r ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-6">
                    <small class="text-muted fw-bold d-block mb-2">PROFILE ENTRY</small>
                    <?php foreach ($roles as $r): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="Profile_Entry[]" value="<?= $r ?>"
                                id="pent_<?= $r ?>" <?= in_array($r, $active_pentry) ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="pent_<?= $r ?>"><?= $r ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="submit" class="save-fab shadow-lg">
            <i class="bi bi-cloud-upload me-2"></i> SAVE SETTINGS
        </button>
    </form>
</main>


<?php include 'footer.php'; ?>

<script>
    document.getElementById('settingsForm').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({ title: 'Updating...', didOpen: () => Swal.showLoading() });

        fetch('', { method: 'POST', body: new FormData(this) })
            .then(r => r.text())
            .then(t => {
                if (t.includes("success")) {
                    Swal.fire({ icon: 'success', title: 'Settings Saved', timer: 1500, showConfirmButton: false });
                }
            });
    });
</script>