<style>
    /* হিরো অ্যাকশন বাটন স্টাইল */
    .hero-action-row {
        display: flex;
        justify-content: flex-end;
        /* ডান পাশে নেয়ার জন্য */
        gap: 10px;
        margin-top: -10px;
        /* হিরো ব্লকের সাথে অ্যাডজাস্ট করার জন্য */
        padding-right: 5px;
    }

    .action-icon-btn {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        /* হালকা ট্রান্সপারেন্ট সাদা */
        color: #fff;
        border-radius: 12px;
        /* M3 স্টাইল কার্ভ */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        /* গ্লাস ইফেক্ট */
    }

    .action-icon-btn:hover {
        background: #fff;
        color: var(--m3-primary);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* নটিফিকেশন ডট (লাল বিন্দু) */
    .btn-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: #FF3B30;
        border-radius: 50%;
        border: 1.5px solid #fff;
    }
</style>

<style>
    .btn-badge-count {
        position: absolute;
        top: -5px;
        right: -5px;
        color: white;
        font-size: 0.65rem;
        font-weight: 900;
        padding: 2px 6px;
        border-radius: 10px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .badge-red {
        background: #B3261E;
    }

    /* New updates - Alert */
    .badge-amber {
        background: #F9A825;
        color: #000;
    }

    /* Unexplored - Warning */
</style>



<main class="pb-0">

    <div class="hero-container">
        <div class="small fw-bold opacity-75 text-uppercase mb-1" style="letter-spacing: 1px;">
            <?php echo $greeting; ?>, Admin
        </div>
        <div class="h3 fw-bold mb-0"><?php echo date('l'); ?></div>
        <div class="small opacity-90"><?php echo date('d M, Y'); ?></div>

        <div class="mt-3 d-flex gap-2">
            <span class="badge bg-white text-warning rounded-pill px-3 py-2 border-0 shadow-sm"
                style="font-size: 1.0rem;">
                <i class="bi bi-shield-check"></i>
                <span class="vr"></span>
                <i class="bi bi-shield-fill-check"></i>
            </span>

            <div class="flex-grow-1"></div>

            <div class="hero-action-row text-right">
                <div class="action-icon-btn position-relative" title="What's New" onclick="navigateTo('whatsnew.php')">
                    <i class="bi bi-stars"></i>
                    <?php
                    if ($new_updates_count > 0):
                        // অগ্রাধিকার ১: নতুন আপডেট থাকলে লাল ব্যাজ
                        echo '<span class="btn-badge-count badge-red">' . $new_updates_count . '</span>';
                    elseif ($unexplored_count > 0):
                        // অগ্রাধিকার ২: নতুন নেই কিন্তু আন-এক্সপ্লোরড থাকলে হলুদ ব্যাজ
                        echo '<span class="btn-badge-count badge-amber">' . $unexplored_count . '</span>';
                    endif;
                    ?>
                </div>

                <div class="action-icon-btn" title="Messages">
                    <i class="bi bi-chat-dots"></i>
                </div>

                <div class="action-icon-btn" title="To-Do List">
                    <i class="bi bi-list-check"></i>
                </div>

                <div class="action-icon-btn position-relative" title="Notifications"
                    onclick="navigateTo('notification.php')">
                    <i class="bi bi-bell"></i>
                    <?php if ($unread_count > 0): ?>
                        <span class="btn-badge"></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>

    <div class="widget-grid">

        <?php
        foreach ($blocks as $id => $info):
            ?>
            <div class="block-unit shadow-sm">
                <?php include 'front-page-block/' . $info['link']; ?>
            </div>
            <?php
        endforeach;
        ?>


    </div>





</main>



<script>
    // প্রোফেশনাল নেভিগেশন স্ক্রিপ্ট
    function navigateTo(url) {
        if (!url) return;
        const session = '<?php echo $sessionyear; ?>';
        const target = url.includes('?') ? `${url}&year=${session}` : `${url}?year=${session}`;
        window.location.href = target;
    }

    // আপনার বিদ্যমান রাউট গুলো এখানে হ্যান্ডেল হবে
    function goclsatt(cls, sec) {
        window.location.href = `stattnd.php?cls=${cls}&sec=${sec}&year=<?php echo $current_session; ?>`;
    }
</script>