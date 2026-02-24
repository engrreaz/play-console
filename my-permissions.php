<?php
$page_title = "My Permissions";
include 'inc.php'; // এখানে $usr (email), $userlevel, $sccode ডিফাইন করা আছে ধরে নিচ্ছি

/**
 * পারমিশন প্রায়োরিটি লজিক:
 * ১. নির্দিষ্ট ইমেইল (Priority 1)
 * ২. ইউজার লেভেল (Priority 2)
 * ৩. নির্দিষ্ট স্কুল কোড (Priority 3)
 * ৪. ডিফল্ট/গ্লোবাল (Priority 4)
 */

$total_access = 0;
$my_permissions = [];

// সকল সম্ভাব্য ম্যাচিং রো ফেচ করা
$sql = "SELECT page_name, page_title, module, permission,
        CASE 
            WHEN email = '$usr' THEN 1
            WHEN userlevel = '$userlevel' AND (email IS NULL OR email = '') THEN 2
            WHEN sccode = '$sccode' AND (email IS NULL OR email = '') AND (userlevel IS NULL OR userlevel = '') THEN 3
            WHEN sccode = 0 THEN 4
            ELSE 5
        END as priority
        FROM permission_map_app
        WHERE email = '$usr' 
           OR userlevel = '$userlevel' 
           OR sccode = '$sccode' 
           OR sccode = 0
        ORDER BY priority ASC"; // কম ভ্যালু মানে বেশি প্রায়োরিটি

$result = $conn->query($sql);





$module_groups = []; // নতুন গ্রুপড অ্যারে

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $p_name = $row['page_name'];
        $mod_name = $row['module'] ?: 'General/System'; // মডিউল নাম না থাকলে ডিফল্ট

        if (!isset($my_permissions[$p_name])) {
            $permission_data = [
                'title' => $row['page_title'] ?? $row['page_name'],
                'module' => $mod_name,
                'value' => (int) $row['permission']
            ];

            // ১. ফ্ল্যাট অ্যারে রাখা (has_permission ফাংশনের জন্য)
            $my_permissions[$p_name] = $permission_data;

            // ২. মডিউল অনুযায়ী গ্রুপিং করা (ডিসপ্লের জন্য)
            $module_groups[$mod_name][] = $permission_data;

            if ((int) $row['permission'] > 0) {
                $total_access++;
            }
        }
    }
}
ksort($module_groups); // মডিউল নাম অনুযায়ী অ্যালফাবেটিকাল সর্ট
// Fallback: যদি কোন নির্দিষ্ট পেজ ম্যাপে না থাকে, তার পারমিশন ০ হবে।
?>

<style>
    .perm-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #e0e0e0;
    }

    .perm-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .bg-view {
        background: #e3f2fd;
        color: #1976d2;
    }

    /* Permission 1 */
    .bg-edit {
        background: #fff3e0;
        color: #ef6c00;
    }

    /* Permission 2 */
    .bg-full {
        background: #e8f5e9;
        color: #2e7d32;
    }

    /* Permission 3 */
    .bg-none {
        background: #ffebee;
        color: #c62828;
    }

    /* Permission 0 */
</style>

<style>
    /* Hero Section Styling */
    .perm-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 40px 24px 65px;
        border-radius: 0 0 36px 36px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(103, 80, 164, 0.2);
    }

    .perm-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .hero-avatar {
        width: 72px;
        height: 72px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 16px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
    }

    .hero-subtitle {
        opacity: 0.8;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .hero-chips {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        justify-content: center;
    }

    .m3-chip {
        background: rgba(255, 255, 255, 0.1);
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Background Decoration */
    .hero-bg-icon {
        position: absolute;
        font-size: 10rem;
        right: -30px;
        bottom: -40px;
        opacity: 0.1;
        transform: rotate(-15deg);
        pointer-events: none;
    }
</style>


<style>
    .m3-section-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #6750A4;
        margin: 25px 0 12px 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .m3-section-title::after {
        content: "";
        flex-grow: 1;
        height: 1px;
        background: #EADDFF;
    }

    .module-container {
        margin-bottom: 20px;
    }
</style>


<div class="perm-hero">
    <div class="perm-hero-content">
        <div class="hero-avatar shadow-sm">
            <i class="bi bi-shield-check"></i>
        </div>

        <h4 class="hero-title">Access Permission Map</h4>
        <p class="text-info small mb-0">Priority : Email > Role > Institute > Global</p>

        <p class="hero-subtitle mb-0"><?php echo $usr; ?></p>

        <div class="hero-chips">
            <div class="m3-chip shadow-sm">
                <i class="bi bi-person-badge"></i> <?php echo $userlevel; ?>
            </div>
            <div class="m3-chip shadow-sm">
                <i class="bi bi-layers"></i> <?php echo $total_access; ?> Modules
            </div>
            <?php if ($sccode > 0): ?>
                <div class="m3-chip shadow-sm">
                    <i class="bi bi-building"></i><?php echo $sccode; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <i class="bi bi-safe hero-bg-icon"></i>
</div>



<main class="container py-2 pb-5">
    <?php foreach ($module_groups as $module => $permissions): ?>
        <div class="module-container">
            <div class="m3-section-title">
                <i class="bi bi-collection"></i>
                <?= htmlspecialchars($module) ?>
                <span class="badge rounded-pill bg-light text-primary border" style="font-size: 0.6rem;">
                    <?= count($permissions) ?> Items
                </span>
            </div>

            <div class="row g-2">
                <?php foreach ($permissions as $data):
                    $p_val = $data['value'];
                    $status_text = "No Access";
                    $status_class = "bg-none";

                    if ($p_val == 1) {
                        $status_text = "View Only";
                        $status_class = "bg-view";
                    } elseif ($p_val == 2) {
                        $status_text = "Edit Access";
                        $status_class = "bg-edit";
                    } elseif ($p_val >= 3) {
                        $status_text = "Full Control";
                        $status_class = "bg-full";
                    }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="perm-card shadow-sm d-flex justify-content-between align-items-center mb-0 h-100">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                    <?= htmlspecialchars($data['title']) ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.65rem;">
                                    Perm Level: <?= $p_val ?>
                                </div>
                            </div>
                            <span class="perm-badge <?= $status_class ?>"><?= $status_text ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<?php

include 'footer.php';
/**
 * পারমিশন চেক ফাংশন
 * @param string $page_name (যেমন: index.php)
 * @param int $required_level (যেমন: ১ ভিউ এর জন্য)
 * @return bool
 */
function has_permission($page_name, $required_level = 1)
{
    global $my_permissions;

    if (isset($my_permissions[$page_name])) {
        return $my_permissions[$page_name]['value'] >= $required_level;
    }

    return false; // Fallback 0
}
