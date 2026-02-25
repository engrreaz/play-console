<?php
$page_title = "Smart Launchpad";
include 'inc.php';

// ব্লকের কনফিগারেশন: টাইটেল, আইকন এবং গন্তব্য লিঙ্ক
?>

<style>
    /* ১. হিরো সেকশন */
    .dashboard-hero {
        background: linear-gradient(180deg, #6750A4 0%, #4F378B 100%);
        padding: 60px 24px 100px;
        color: white;
        border-radius: 0 0 40px 40px;
        text-align: center;
    }

    /* ২. কার্ড ডিজাইন (Launchpad Style) */
    .m3-block-card {
        background: #fff;
        border-radius: 16px;
        margin-bottom: 20px;
        border: 1px solid #E7E0EC;
        overflow: hidden;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .m3-block-card:hover {
        transform: scale(1.02);
        border-color: #6750A4;
        box-shadow: 0 8px 20px rgba(103, 80, 164, 0.1);
    }

    .block-header {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #F7F2FA;
        border-bottom: 1px solid #E7E0EC;
    }

    .drag-handle {
        cursor: grab;
        color: #79747E;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    /* ৩. প্লেসহোল্ডার কনটেন্ট (মাঝখানে আইকন ও টাইটেল) */
    .block-placeholder {
        height: 150px;
        /* নির্দিষ্ট হাইট */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        color: inherit;
        gap: 15px;
        position: relative;
    }

    .placeholder-icon {
        width: 60px;
        height: 60px;
        background: #F3EDF7;
        color: #6750A4;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        transition: 0.3s;
    }

    .m3-block-card:hover .placeholder-icon {
        background: #6750A4;
        color: #fff;
    }

    .placeholder-title {
        font-weight: 900;
        font-size: 1.1rem;
        color: #1C1B1F;
    }

    .sortable-ghost {
        opacity: 0.2;
        background: #EADDFF !important;
        border: 2px dashed #6750A4;
    }
</style>

<main class="pb-5">
    <div class="dashboard-hero shadow">
        <h2 class="fw-black mb-1">EIMBox Dashboard</h2>
        <p class="small opacity-75 fw-bold">Manage your institution with ease</p>
    </div>

    <div id="blocksContainer" class="container mt-n5 px-3" style="margin-top: -60px; position: relative; z-index: 10;">
        <?php foreach ($blocks as $id => $info): ?>
            <div class="m3-block-card" data-id="<?= $id ?>" id="block-wrapper-<?= $id ?>">
                <div class="block-header">
                    <i class="bi bi-grid-3x3-gap-fill drag-handle"></i>
                    <span class="flex-grow-1 small fw-bold text-muted text-uppercase"
                        style="letter-spacing:1px;"><?= $info['title'] ?></span>

                    <div class="dropdown">
                        <i class="bi bi-three-dots-vertical text-muted pointer" data-bs-toggle="dropdown"></i>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                            <li><a class="dropdown-item fw-bold small text-danger" onclick="hideBlock('<?= $id ?>')"><i
                                        class="bi bi-eye-slash me-2"></i> Disable Card</a></li>
                            <li><a class="dropdown-item fw-bold small" href="<?= $info['link'] ?>"><i
                                        class="bi bi-box-arrow-up-right me-2"></i> Open Full Page</a></li>
                        </ul>
                    </div>
                </div>

                <a href="<?= $info['link'] ?>" class="block-placeholder">
                    <div class="placeholder-icon shadow-sm">
                        <i class="bi <?= $info['icon'] ?>"></i>
                    </div>
                    <div class="placeholder-title"><?= $info['title'] ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<div class="fixed-bottom p-4 text-center">
    <button class="btn btn-dark rounded-pill px-4 fw-black shadow-lg" data-bs-toggle="offcanvas"
        data-bs-target="#managerDrawer">
        <i class="bi bi-sliders me-2"></i> CUSTOMIZE LAYOUT
    </button>
</div>

<div class="offcanvas offcanvas-bottom" tabindex="-1" id="managerDrawer"
    style="height: 70vh; border-radius: 28px 28px 0 0;">
    <div class="offcanvas-header px-4 pt-4">
        <h5 class="fw-black m-0 text-primary">Manage Home Cards</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body px-4">
        <p class="small text-muted fw-bold mb-4">Toggle cards to show or hide them on the main dashboard.</p>
        <div id="configList">
            <?php foreach ($blocks as $id => $info): ?>
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-4 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box-m3 bg-white shadow-sm rounded-3"
                            style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi <?= $info['icon'] ?> text-primary"></i>
                        </div>
                        <span class="fw-bold text-dark"><?= $info['title'] ?></span>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input block-toggle" type="checkbox" data-id="<?= $id ?>" id="sw-<?= $id ?>"
                            checked>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="btn btn-m3-tonal w-100 rounded-pill py-3 mt-4 fw-bold" onclick="resetLayout()">RESET TO
            DEFAULT</button>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const container = document.getElementById('blocksContainer');
    const STORAGE_KEY = 'eimbox_dashboard_v1';

    // ১. লেআউট অ্যাপ্লাই করা
    function applyLayout() {
        const data = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!data) return;

        // অর্ডার অনুযায়ী এলিমেন্ট মুভ করা
        data.order.forEach(id => {
            const el = document.getElementById('block-wrapper-' + id);
            if (el) container.appendChild(el);
        });

        // ভিজিবিলিটি সেট করা
        data.visibility.forEach(item => {
            const el = document.getElementById('block-wrapper-' + item.id);
            const sw = document.getElementById('sw-' + item.id);
            if (el) {
                el.style.display = item.visible ? 'block' : 'none';
                if (sw) sw.checked = item.visible;
            }
        });
    }

    // ২. ডাটা সেভ করা
    function saveLayout() {
        const order = [...container.children].map(el => el.dataset.id);
        const visibility = [...document.querySelectorAll('.block-toggle')].map(input => ({
            id: input.dataset.id,
            visible: input.checked
        }));

        localStorage.setItem(STORAGE_KEY, JSON.stringify({ order, visibility }));
    }

    // ৩. ড্র্যাগ অ্যান্ড ড্রপ ইনিশিয়ালাইজেশন
    new Sortable(container, {
        handle: '.drag-handle',
        animation: 250,
        ghostClass: 'sortable-ghost',
        onEnd: saveLayout
    });

    // ৪. সুইচ ইভেন্ট লিসেনার
    document.querySelectorAll('.block-toggle').forEach(sw => {
        sw.addEventListener('change', function () {
            const id = this.dataset.id;
            document.getElementById('block-wrapper-' + id).style.display = this.checked ? 'block' : 'none';
            saveLayout();
        });
    });

    // ৫. হাইড ফাংশন (থ্রি-ডট মেনুর জন্য)
    function hideBlock(id) {
        document.getElementById('sw-' + id).checked = false;
        document.getElementById('block-wrapper-' + id).style.display = 'none';
        saveLayout();
        Swal.fire({ title: 'Card Hidden', text: 'You can enable it again from Customize Layout.', icon: 'info', timer: 2000, showConfirmButton: false });
    }

    function resetLayout() {
        localStorage.removeItem(STORAGE_KEY);
        location.reload();
    }

    document.addEventListener('DOMContentLoaded', applyLayout);
</script>