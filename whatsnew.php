<?php
$page_title = "What's New";
include 'inc.php';

/**
 * লজিক ব্যাখ্যা:
 * ১. ইউজার চেঞ্জলগটি দেখেছে কি না তা 'user_timeline_seen' টেবিল থেকে চেক করা হয়।
 * ২. ইউজার ওই ফিচারের পেজটি আপডেটের পর ভিজিট করেছে কি না তা 'logbook' টেবিল থেকে চেক করা হয়।
 * ৩. যদি (Seen = True) এবং (Last Visit < Update Time) হয়, তবে 'Warning/Unexplored' মার্ক দেখাবে।
 */

// ১. ইউজারের লাস্ট সিন আইডি বের করা
$last_seen_id = 0;
$stmt_seen = $conn->prepare("SELECT last_seen_id FROM user_timeline_seen WHERE email = ?");
$stmt_seen->bind_param("s", $usr);
$stmt_seen->execute();
$res_seen = $stmt_seen->get_result();
if ($row_seen = $res_seen->fetch_assoc()) {
    $last_seen_id = $row_seen['last_seen_id'];
}
$stmt_seen->close();

// ২. ইউজারের প্রতিটি পেজে শেষ ভিজিট করার সময় বের করা (Logbook থেকে)
$user_visits = [];
$stmt_visits = $conn->prepare("SELECT pagename, MAX(entrytime) as last_visit FROM logbook WHERE email = ?  and platform='Android' GROUP BY pagename");
$stmt_visits->bind_param("s", $usr); // $usr সাধারণত inc.php তে ইমেইল হিসেবে থাকে
$stmt_visits->execute();
$res_visits = $stmt_visits->get_result();
while ($v = $res_visits->fetch_assoc()) {
    $user_visits[$v['pagename']] = $v['last_visit'];
}
$stmt_visits->close();

// ৩. অ্যান্ড্রয়েড প্ল্যাটফর্মের টাইমলাইন ফেচ করা
$timeline = [];
$max_id = 0;
$sql = "SELECT * FROM dev_timeline WHERE platform = 'Android' ORDER BY created_at DESC";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $timeline[] = $row;
    if ($row['id'] > $max_id)
        $max_id = $row['id'];
}

// ৪. লাস্ট সিন আইডি আপডেট করা
if ($max_id > $last_seen_id) {
    $stmt_upd = $conn->prepare("INSERT INTO user_timeline_seen (email, last_seen_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE last_seen_id = ?");
    $stmt_upd->bind_param("sii", $usr, $max_id, $max_id);
    $stmt_upd->execute();
    $stmt_upd->close();
}
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FEF7FF;
        --m3-warning: #F9A825;
    }

    body {
        background: var(--m3-surface);
    }

    .timeline-container {
        padding: 16px;
        position: relative;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        left: 28px;
        top: 20px;
        bottom: 20px;
        width: 2px;
        background: #EADDFF;
        z-index: 0;
    }

    .log-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-left: 36px;
        margin-bottom: 20px;
        position: relative;
        border: 1px solid #E7E0EC;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        cursor: pointer;
        transition: 0.2s;
    }

    .log-card:active {
        transform: scale(0.97);
        background: #F3EDF7;
    }

 

    /* ব্যাজ স্টাইল */
    .status-badge {
        font-size: 0.55rem;
        font-weight: 900;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        margin-left: 5px;
    }

    .badge-new {
        background: #B3261E;
        color: white;
    }

    .badge-warning {
        background: var(--m3-warning);
        color: black;
    }

    .type-chip {
        font-size: 0.6rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
    }

    .type-implement {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .type-bug_fix {
        background: #FFEBEE;
        color: #B3261E;
    }

    .type-security_patch {
        background: #FFF3E0;
        color: #E65100;
    }

    /* Unexplored কার্ডের জন্য বিশেষ ব্যাকগ্রাউন্ড */


    /* হোভার বা একটিভ অবস্থায় টোনটি একটু গভীর হবে */
    .bg-unexplored:active {
        background-color: #FFF9C4 !important;
    }


    /* টাইমলাইন কন্টেইনার এবং লাইন */
    .timeline-container {
        padding: 16px;
        position: relative;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        left: 24px;
        /* লাইন পজিশন */
        top: 20px;
        bottom: 20px;
        width: 2px;
        background: #EADDFF;
        z-index: 0;
    }

    /* ডিফল্ট ডট স্টাইল ও পজিশন */
    .log-card::before {
        content: '';
        position: absolute;
        left: -36px;
        /* লাইনের সাথে নিখুঁত অ্যালাইনমেন্ট */
        top: 24px;
        /* টাইটেলের সাথে সমান্তরাল */
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--m3-primary);
        /* ডিফল্ট বেগুনি */
        border: 3px solid var(--m3-surface);
        z-index: 2;
        transition: 0.3s;
    }

    /* স্ট্যাটাস অনুযায়ী ডটের আলাদা কালার */
    .card-new::before {
        background: #B3261E !important;
    }

    /* নতুন হলে লাল */
    .card-unexplored::before {
        background: #F9A825 !important;
    }

    /* আন-এক্সপ্লোরড হলে হলুদ */
    .card-normal::before {
        background: var(--m3-primary) !important;
    }

    /* সাধারণ বেগুনি */

    /* Unexplored কার্ডের ব্যাকগ্রাউন্ড (আগের মতোই) */
    .bg-unexplored {
        background-color: #FFFDE7 !important;
        border: 1px solid #FFF59D !important;
    }
</style>

<main class="pb-5">
    <div class="hero-container shadow-sm"
        style="background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%); color:white; border-radius: 0 0 28px 28px;">
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
        <?php foreach ($timeline as $log):
            $is_new = ($log['id'] > $last_seen_id);
            $page = $log['page_name'];
            $created = $log['created_at'];

            $last_visit = $user_visits[$page] ?? '1970-01-01 00:00:00';
            $not_visited_yet = (strtotime($last_visit) < strtotime($created));

            $show_warning = (!$is_new && $not_visited_yet);

            // কার্ড এবং ডটের কালার ক্লাস নির্ধারণ
            $bg_class = ($show_warning) ? 'bg-unexplored' : '';

            // ডটের কালার ক্লাস
            $dot_status_class = 'card-normal';
            if ($is_new)
                $dot_status_class = 'card-new';
            elseif ($show_warning)
                $dot_status_class = 'card-unexplored';
            ?>

            <div class="log-card shadow-sm <?= $bg_class ?> <?= $dot_status_class ?>"
                onclick="window.location.href='<?= $page ?>'">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="type-chip type-<?= $log['action_type'] ?>">
                            <?= str_replace('_', ' ', $log['action_type']) ?>
                        </span>
                        <?php if ($is_new): ?>
                            <span class="status-badge badge-new">NEW</span>
                        <?php elseif ($show_warning): ?>
                            <span class="status-badge badge-warning"><i class="bi bi-exclamation-triangle-fill"></i>
                                UNEXPLORED</span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-muted fw-bold" style="font-size: 0.6rem;">
                        <?= date('M d, Y', strtotime($created)) ?>
                    </div>
                </div>

                <h6 class="fw-black text-dark m-0 mt-1"><?= $log['feature_name'] ?></h6>
                <div class="text-primary small fw-bold mb-2" style="font-size: 0.7rem;">
                    <i class="bi bi-link-45deg"></i> <?= $page ?>
                </div>

                <p class="text-muted small mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                    <?= !empty($log['description']) ? $log['description'] : "Optimization and feature enhancements for Android platform." ?>
                </p>

                <div class="mt-3 d-flex align-items-center justify-content-between opacity-75">
                    <span class="badge rounded-pill bg-light text-dark border"
                        style="font-size: 0.55rem; font-weight: 800;">
                        STATUS: <?= strtoupper($log['status']) ?>
                    </span>
                    <div class="small fw-bold" style="font-size: 0.55rem;">By: <?= $log['logged_by'] ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>