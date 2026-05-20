<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';
?>

<style>
    /* হিরো সেকশন */
    .hero-container {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        margin-top: -10px;
        color: white;
        padding: 25px 20px;
        border-radius: 0 0 28px 28px;
    }

    .pill {
        border: none;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 700;
        font-size: .7rem;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    .pill.active {
        background: #fff;
        color: #8479a5;
    }

    /* কার্ড ডিজাইন */
    .perm-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 16px;
        margin: 12px 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #E0E0E0;
        cursor: pointer;
        transition: 0.2s;
    }

    .perm-card:active {
        transform: scale(0.97);
        background: #F3EDF7;
    }

    .perm-card.unassigned {
        border-left: 5px solid #F9DEDC;
    }

    .perm-card.mapped {
        border-left: 5px solid #E8F5E9;
    }

    .icon-box-m3 {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .c-mapped {
        background-color: #EADDFF;
        color: #21005D;
    }

    .c-unassigned {
        background-color: #F9DEDC;
        color: #410E0B;
    }

    .file-name {
        font-size: 0.9rem;
        font-weight: 800;
        color: #1C1B1F;
        margin-bottom: 2px;
    }

    .file-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #49454F;
    }

    .module-label {
        font-size: 0.65rem;
        font-weight: 500;
        color: #79747E;
    }

    .m3-tonal-pill {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .pill-mapped {
        background-color: #E8F5E9;
        color: #2E7D32;
    }

    .pill-unassigned {
        background-color: #FDE7E9;
        color: #B3261E;
    }

    /* মডাল স্টাইল */
    .m3-modal-content {
        border-radius: 12px !important;
        border: none !important;
    }

    .m3-input-floating {
        width: 100%;
        background: #F3EDF7;
        border: none;
        border-bottom: 2px solid #79747E;
        border-radius: 8px 8px 4px 4px;
        padding: 12px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .role-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 6px;
    }

    .role-row select {
        background: #6750A4;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 700;
        outline: none;
    }

    #loader {
        text-align: center;
        padding: 30px;
        font-weight: bold;
        color: #6750A4;
    }
</style>


<style>
    .bg-surface {
        background-color: #FEF7FF;
    }

    /* Search Box M3 Style */
    .m3-search-box {
        background: #F3EDF7;
        border-radius: 100px;
        /* Fully Rounded */
        display: flex;
        align-items: center;
        border: 1px solid #E7E0EC;
    }

    .m3-search-box input:focus {
        box-shadow: none;
    }

    /* Selection Card Style */
    .m3-selection-card {
        background: #fff;
        border: 1px solid #E7E0EC;
        border-radius: 20px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: 0.2s cubic-bezier(0.2, 0, 0, 1);
    }

    .m3-selection-card:hover {
        background-color: #EADDFF;
        border-color: #6750A4;
        transform: translateY(-2px);
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .bg-tonal-purple {
        background: #F3EDF7;
        color: #6750A4;
    }

    .card-title {
        font-weight: 800;
        color: #1C1B1F;
        font-size: 0.95rem;
    }

    .card-path {
        font-size: 0.75rem;
        color: #49454F;
        font-family: monospace;
    }
</style>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-outline: #79747E;
    }

    .m3-modal-main {
        background-color: var(--m3-surface);
        border-radius: 28px !important;
        /* Extra Large Radius */
    }

    .m3-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-primary-container {
        background-color: var(--m3-primary-container);
    }

    /* Tonal Input Container */
    .m3-input-box {
        background-color: var(--m3-secondary-container);
        border-radius: 12px;
        padding: 8px 16px;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .m3-input-box:focus-within {
        background-color: #fff;
        border-color: var(--m3-primary);
        box-shadow: 0 0 0 1px var(--m3-primary);
    }

    .m3-input-box.locked {
        background-color: #E0E0E0;
        opacity: 0.8;
    }

    .m3-field-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .m3-field-input {
        width: 100%;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
        padding: 2px 0;
    }

    /* Section Divider */
    .m3-section-divider {
        font-size: 0.75rem;
        font-weight: 900;
        color: var(--m3-outline);
        margin: 20px 0 12px 4px;
        letter-spacing: 1px;
    }

    /* Custom Input Group for Browse */
    .m3-input-group-custom {
        display: flex;
        gap: 8px;
        align-items: stretch;
    }

    .m3-tonal-btn-icon {
        background-color: var(--m3-primary-container);
        color: var(--m3-primary);
        border-radius: 12px;
        width: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
    }

    /* Action Buttons */
    .m3-btn-primary {
        background-color: var(--m3-primary);
        color: var(--m3-on-primary);
        border-radius: 100px;
        font-weight: 800;
        border: none;
    }

    .m3-btn-tonal {
        background-color: var(--m3-secondary-container);
        color: var(--m3-primary);
        border-radius: 100px;
        font-weight: 800;
        border: none;
    }

    .modal-backdrop {
        z-index: 1040 !important;
    }

    .modal {
        z-index: 1050 !important;
    }

    body.modal-open {
        overflow: auto !important;
        padding-right: 0 !important;
    }
</style>


<main>
    <div class="hero-container shadow">
        <div class="d-flex justify-content-between align-items-center">
            <div style="font-size:1.4rem;font-weight:900;">Permission Mapper</div>
            <div class="text-end">
                <div id="unassignedCount" style="font-size:1.5rem;font-weight:900;color:#FFD8D6;">0</div>
                <div style="font-size:.6rem;font-weight:800;">UNASSIGNED</div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button class="pill active" onclick="filterCards('all', this)">All</button>
            <button class="pill" onclick="filterCards('mapped', this)">Mapped</button>
            <button class="pill" onclick="filterCards('unassigned', this)">New</button>
            <div class="flex-grow-1"></div>
            <button class="pill" onclick="manualSync(this)">Sync</button>

        </div>
    </div>

    <div id="loader">
        <div class="spinner-border spinner-border-sm me-2"></div> Loading Data...
    </div>
    <div id="cardContainer" style="padding-bottom: 100px;"></div>
</main>





<div class="modal fade" id="permModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-main shadow-lg border-0">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-box bg-primary-container text-primary">
                        <i class="bi bi-shield-lock-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-black m-0 text-dark">Access Control</h5>
                        <p class="small text-muted fw-bold mb-0">Manage page visibility and role rights</p>
                    </div>
                </div>
                <button class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form id="saveForm" method="post" action="">
                <div class="modal-body px-4 py-3" style="max-height: 65vh; overflow-y: auto;">

                    <div class="m3-input-box locked mb-3">
                        <label class="m3-field-label">PAGE FILENAME (READ-ONLY)</label>
                        <input type="text" name="page_name" id="m_page" class="m3-field-input" readonly>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
                            <div class="m3-input-box mb-3">
                                <label class="m3-field-label">PAGE TITLE</label>
                                <input type="text" name="page_title" id="m_title" class="m3-field-input"
                                    placeholder="e.g. Student List">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="m3-input-box mb-3">
                                <label class="m3-field-label">SHORT DESCRIPTION</label>
                                <input type="text" name="description" id="m_desc" class="m3-field-input"
                                    placeholder="Briefly explain the page purpose">
                            </div>
                        </div>
                    </div>

                    <div class="m3-section-divider">NAVIGATION & STRUCTURE</div>

                    <div class="m3-input-box mb-3">
                        <label class="m3-field-label">ASSIGNED MODULE</label>
                        <select name="module" id="m_module" class="m3-field-input cursor-pointer">
                            <option value="">Select Module</option>
                        </select>
                    </div>

                    <div class="m3-input-group-custom mb-3">
                        <div class="m3-input-box flex-grow-1">
                            <label class="m3-field-label">ROOT PAGE PATH</label>
                            <input type="text" name="root_page" id="m_root" class="m3-field-input"
                                placeholder="index.php">
                        </div>
                        <button type="button" class="btn m3-tonal-btn-icon" id="browse_file" title="Browse Modules">
                            <i class="bi bi-folder-plus fs-4"></i>
                        </button>
                    </div>

                    <div class="m3-input-box mb-4">
                        <label class="m3-field-label">YOUTUBE GUIDE ID (OPTIONAL)</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-youtube text-danger me-2"></i>
                            <input type="text" name="video_id" id="m_video" class="m3-field-input"
                                placeholder="dQw4w9WgXcQ">
                        </div>
                    </div>

                    <div class="m3-section-divider">ROLE PERMISSIONS</div>
                    <div id="roleFields" class="row g-2">
                    </div>
                </div>

                <!-- <div style="height:20px;"></div> -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn m3-btn-tonal flex-fill" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" name="save_all_permissions" class="btn m3-btn-primary flex-fill shadow">
                            <i class="bi bi-cloud-check-fill me-2"></i>SAVE PERMISSIONS
                        </button>
                    </div>
                    <div style="height:20px;"></div>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="moduleBrowserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable"
        style="max-width:90%; margin:auto;">
        <div class="modal-content border-0 rounded-5 shadow-lg bg-surface">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="fw-black m-0 text-primary">Module Browser</h5>
                    <p class="small text-muted mb-0">Select a related page to link</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <div class="m3-search-box mb-4 shadow-sm">
                    <i class="bi bi-search ms-3 text-muted"></i>
                    <input type="text" id="moduleSearch" class="form-control border-0 bg-transparent py-3 ps-2"
                        placeholder="Search by title or page path...">
                </div>

                <div class="row g-3" id="moduleCardGrid">

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const STORAGE_KEY = 'm3_mapper_cache';
    let masterData = [];
    let filteredData = [];
    let roles = [];
    let visibleCount = 0;
    const BATCH_SIZE = 30;

    const container = document.getElementById('cardContainer');
    const pModal = new bootstrap.Modal(document.getElementById('permModal'));

    // ১. ডাটা লোড (LocalStorage + AJAX)
    async function init() {
        const cached = localStorage.getItem(STORAGE_KEY);
        if (cached) {
            const parsed = JSON.parse(cached);
            setupData(parsed);
            // ব্যাকগ্রাউন্ডে ডাটা আপডেট করা
            fetchData();
        } else {
            fetchData();
        }
    }

    async function fetchData() {
        try {
            const res = await fetch('backend/get-mapper-data.php');
            const data = await res.json();
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            setupData(data);
        } catch (e) { console.error("Load failed"); }
    }

    function setupData(data) {
        masterData = data.files;
        roles = data.roles;
        document.getElementById('unassignedCount').innerText = masterData.filter(d => d.unassigned).length;

        // মডিউল ড্রপডাউন লোড
        let modHtml = '<option value="">Select Module</option>';
        data.modules.forEach(m => modHtml += `<option value="${m}">${m}</option>`);
        document.getElementById('m_module').innerHTML = modHtml;

        document.getElementById('loader').style.display = 'none';
        filterCards('all', document.querySelector('.pill'));
    }

    // ২. কার্ড রেন্ডারিং (Memory Efficient)


    function renderBatch() {

        const batch = filteredData.slice(visibleCount, visibleCount + BATCH_SIZE);
        if (batch.length === 0) return;

        const fragment = document.createDocumentFragment();

        batch.forEach(item => {

            // ⭐ NEW LOGIC
            const isUnassigned =
                item.unassigned ||
                !item.title ||
                item.title.trim() === "";

            const div = document.createElement('div');
            div.className = `perm-card ${isUnassigned ? 'unassigned' : 'mapped'}`;

            div.onclick = () => openEditor(item.file);

            div.innerHTML = `
            <div class="icon-box-m3 ${isUnassigned ? 'c-unassigned' : 'c-mapped'}"
                 onclick="openPage(event, '${item.file}')">

                <i class="bi ${isUnassigned ? 'bi-file-plus' : 'bi-file-check-fill'}"></i>
            </div>

            <div class="flex-grow-1 overflow-hidden">
                <div class="file-name text-truncate">${item.file}</div>
                <div class="file-title text-truncate">${item.title || ''}</div>
                <div class="file-title text-truncate"
                     style="font-size:10px; font-weight:400;">
                     ${item.desc || 'Untitled'}
                </div>
                <div class="module-label">
                    <i class="bi bi-box-seam me-1"></i>
                    ${item.module || 'No Module'}
                </div>
            </div>

            ${(!isUnassigned && item.video_id)
                    ? `<i class="bi bi-youtube text-danger me-2"
           style="font-size:18px;"></i>`
                    : ''
                }


            <span class="m3-tonal-pill ${isUnassigned ? 'pill-unassigned' : 'pill-mapped'}">
                ${isUnassigned ? 'New' : 'Mapped'}
            </span>
        `;

            fragment.appendChild(div);
        });

        container.appendChild(fragment);
        visibleCount += BATCH_SIZE;
    }




    // ৩. ফিল্টার ও মডাল
    function filterCards(type, btn) {
        document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');

        if (type === 'all') filteredData = masterData;
        else if (type === 'mapped') filteredData = masterData.filter(d => !d.unassigned);
        else filteredData = masterData.filter(d => d.unassigned);

        container.innerHTML = "";
        visibleCount = 0;
        renderBatch();
        // window.scrollTo(0, 0);
    }

    function openEditor(file) {
        const item = masterData.find(d => d.file === file);
        document.getElementById('m_page').value = item.file;
        document.getElementById('m_title').value = item.title;
        document.getElementById('m_desc').value = item.desc;
        document.getElementById('m_module').value = item.module;
        document.getElementById('m_root').value = item.root;
        document.getElementById('m_video').value = item.video_id || "";


        let roleHtml = "";
        roles.forEach(r => {
            const p = item.perm[r] || 0;
            roleHtml += `
                <div class="role-row">
                    <div class="fw-bold small">${r.toUpperCase()}</div>
                    <select name="perm[${r}]">
                        <option value="3" ${p == 3 ? 'selected' : ''}>Full</option>
                        <option value="2" ${p == 2 ? 'selected' : ''}>Partial</option>
                        <option value="1" ${p == 1 ? 'selected' : ''}>Read</option>
                        <option value="0" ${p == 0 ? 'selected' : ''}>None</option>
                    </select>
                </div>
            `;
        });
        document.getElementById('roleFields').innerHTML = roleHtml;
        pModal.show();
    }


    document.getElementById('saveForm').addEventListener('submit', async function (e) {

        e.preventDefault();

        const btn = this.querySelector('button[type=submit]');
        btn.innerText = "Saving...";
        btn.disabled = true;

        const formData = new FormData(this);

        const res = await fetch('backend/save-permission.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.ok) {

            pModal.hide();

            // শুধু লোকাল ডাটা আপডেট
            const index = masterData.findIndex(x => x.file === data.item.file);

            if (index !== -1) {
                masterData[index] = data.item;
            }

        } else {
            alert("Save Failed");
        }

        btn.innerText = "SAVE";
        btn.disabled = false;
    });



    async function manualSync(btn) {

        btn.innerText = "Syncing...";
        btn.disabled = true;

        try {
            const res = await fetch('backend/get-mapper-data.php?ts=' + Date.now());
            const data = await res.json();

            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));

            masterData = data.files;

        } catch (e) {
            alert("Sync Failed");
        }

        btn.innerText = "Sync";
        btn.disabled = false;
    }


    function openPage(e, file) {
        e.stopPropagation();
        window.open(file, '_blank');
    }

    // ৪. স্ক্রল হ্যান্ডলার
    window.onscroll = () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            renderBatch();
        }
    };

    init();


    setTimeout(() => {
        manualSync(document.querySelector('.pill:last-child'));
    }, 5000);
</script>

<script>

    // ================================
    // Modal Controller (Clean Version)
    // ================================



    // মডাল ইন্সট্যান্সগুলো একবারই ডিক্লেয়ার করুন
    const permModalEl = document.getElementById('permModal');
    const moduleBrowserEl = document.getElementById('moduleBrowserModal');

    // ইন্সট্যান্স তৈরি (সতর্কতা: বারবার 'new' করবেন না)
    const permModalInstance = bootstrap.Modal.getOrCreateInstance(permModalEl);
    const moduleBrowserInstance = bootstrap.Modal.getOrCreateInstance(moduleBrowserEl);

    // ব্যাকড্রপ পরিষ্কার করার জন্য একটি হেল্পার ফাংশন
    function forceCleanupBackdrop() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // ১️⃣ Browse Button ক্লিক করলে
    document.getElementById('browse_file').addEventListener('click', function () {
        // বর্তমান মডাল হাইড করুন
        permModalInstance.hide();

        // ব্যাকড্রপ ক্লিনিং নিশ্চিত করতে সামান্য দেরি করে দ্বিতীয়টি ওপেন করুন
        setTimeout(() => {
            forceCleanupBackdrop(); // জম্বি ব্যাকড্রপ রিমুভ করুন
            moduleBrowserInstance.show();
            loadModules();
        }, 400); // মডাল ট্রানজিশন টাইম (৩০০মি.সে) এর চেয়ে একটু বেশি
    });

    // ২️⃣ Module Browser ক্লোজ হলে Permission Modal ফিরিয়ে আনা
    moduleBrowserEl.addEventListener('hidden.bs.modal', function () {
        // যদি আমরা পেজ সিলেক্ট করে ক্লোজ করি, তবেই প্রথম মডাল ফেরাবো
        // নতুবা ক্লোজ বাটন প্রেস করলে এমনিতেই বন্ধ হবে
        setTimeout(() => {
            forceCleanupBackdrop();
            // চেক করুন ইউজার কি কোনো ইনপুট ফিল্ড এডিট করছিল কি না (ঐচ্ছিক)
            permModalInstance.show();
        }, 400);
    });

    // ৩️⃣ Close বাটন কাজ না করার সমাধান (ম্যানুয়াল ক্লোজ লজিক)
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetModal = this.closest('.modal');
            if (targetModal) {
                const inst = bootstrap.Modal.getInstance(targetModal);
                if (inst) inst.hide();
                setTimeout(forceCleanupBackdrop, 500);
            }
        });
    });

    // ৪️⃣ পেজ সিলেক্ট করার পর একশন
    function selectPage(pagePath) {
        document.getElementById('m_root').value = pagePath;

        // ব্রাউজার মডাল হাইড করুন
        moduleBrowserInstance.hide();

        Swal.fire({
            icon: 'success',
            title: 'Selected',
            text: pagePath,
            timer: 800,
            showConfirmButton: false
        });

        // এখানে hidden.bs.modal ইভেন্ট অটোমেটিক permModal ফেরৎ আনবে
    }




    // ================================
    // Load Module Cards (AJAX)
    // ================================

    async function loadModules() {

        const grid = document.getElementById('moduleCardGrid');
        grid.innerHTML = '<div class="text-center p-4">Loading...</div>';

        try {

            const res = await fetch('backend/get-modules.php');
            const json = await res.json();

            console.log(json); // 🔥 check structure

            grid.innerHTML = "";

            // যদি json.ok থাকে
            const modules = Array.isArray(json) ? json : (json.data || []);

            modules.forEach(item => {

                const title = item.nav_title ? item.nav_title.toLowerCase() : '';
                const page = item.related_pages ? item.related_pages.toLowerCase() : '';

                const col = document.createElement('div');
                col.className = "col-md-6 module-card-item";

                col.setAttribute('data-title', title);
                col.setAttribute('data-page', page);

                col.innerHTML = `
        <div class="m3-selection-card w-100"
            data-page="${item.related_pages || ''}">
            
            <div class="icon-circle bg-tonal-purple">
                <i class="bi bi-${item.nav_icon || 'file-earmark-code'}"></i>
            </div>

            <div class="info flex-grow-1 ms-2">
                <div class="card-title">${item.nav_title || 'Untitled'}</div>
                <div class="card-path">${item.related_pages || ''}</div>
            </div>
        </div>
    `;

                grid.appendChild(col);
            });

        } catch (e) {
            console.error(e);
            grid.innerHTML = "<div class='text-danger p-3'>Load Failed</div>";
        }

    }

    document.getElementById('moduleCardGrid')
        .addEventListener('click', function (e) {

            const card = e.target.closest('.m3-selection-card');
            if (!card) return;

            const pagePath = card.getAttribute('data-page');

            document.getElementById('m_root').value = pagePath;

            moduleBrowserInstance.hide();
        });


    document.getElementById('moduleSearch')
        .addEventListener('input', function () {

            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.module-card-item');

            cards.forEach(card => {

                const title = card.getAttribute('data-title');
                const page = card.getAttribute('data-page');

                const match = title.includes(query) || page.includes(query);

                card.style.display = match ? 'block' : 'none';

                if (match && query !== '') {
                    card.querySelector('.m3-selection-card')
                        .style.border = "2px solid #6750A4";
                } else {
                    card.querySelector('.m3-selection-card')
                        .style.border = "1px solid #E7E0EC";
                }

            });

        });
</script>