<?php
$page_title = "Advanced Permission Mapper";
include 'inc.php';
?>

<style>
    :root {
        --m3-surface: #F7F2FA;
        --m3-primary: #6750A4;
        --m3-primary-tonal: #EADDFF;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Roboto', sans-serif;
        margin: 0;
    }

    /* হিরো সেকশন */
    .hero-container {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
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
        border-radius: 28px !important;
        border: none !important;
    }

    .m3-input-floating {
        width: 100%;
        background: #F3EDF7;
        border: none;
        border-bottom: 2px solid #79747E;
        border-radius: 12px 12px 4px 4px;
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

<div class="modal fade" id="permModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 92%; margin: 10px auto;">
        <div class="modal-content m3-modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Access Control</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="saveForm" method="post" action="">
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    <input type="text" name="page_name" id="m_page" class="m3-input-floating" readonly
                        style="background: #e0e0e0;">
                    <input type="text" name="page_title" id="m_title" class="m3-input-floating"
                        placeholder="Page Title">
                    <input type="text" name="description" id="m_desc" class="m3-input-floating"
                        placeholder="Description">

                    <select name="module" id="m_module" class="m3-input-floating">
                        <option value="">Select Module</option>
                    </select>

                    <input type="text" name="root_page" id="m_root" class="m3-input-floating"
                        placeholder="Root (e.g. index.php)">

                    <div style="font-size: 0.75rem; font-weight: 800; color: #6750A4; margin: 15px 5px 10px;">ROLE
                        PERMISSIONS</div>
                    <div id="roleFields"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill flex-fill fw-bold"
                        data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" name="save_all_permissions"
                        class="btn btn-primary rounded-pill flex-fill fw-bold shadow">SAVE</button>
                </div>
            </form>
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
            const div = document.createElement('div');
            div.className = `perm-card ${item.unassigned ? 'unassigned' : 'mapped'}`;
            div.onclick = () => openEditor(item.file);
            div.innerHTML = `
                <div class="icon-box-m3 ${item.unassigned ? 'c-unassigned' : 'c-mapped'}" onclick="openPage(event, '${item.file}')">
                    <i class="bi ${item.unassigned ? 'bi-file-plus' : 'bi-file-check-fill'}"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="file-name text-truncate">${item.file}</div>
                    <div class="file-title text-truncate">${item.title || 'Untitled'}</div>
                    <div class="file-title text-truncate" style="font-size:10px; font-weight:400;">${item.desc || 'Untitled'}</div>
                    <div class="module-label"><i class="bi bi-box-seam me-1"></i>${item.module || 'No Module'}</div>
                </div>
                <span class="m3-tonal-pill ${item.unassigned ? 'pill-unassigned' : 'pill-mapped'}">${item.unassigned ? 'New' : 'Mapped'}</span>
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
        window.scrollTo(0, 0);
    }

    function openEditor(file) {
        const item = masterData.find(d => d.file === file);
        document.getElementById('m_page').value = item.file;
        document.getElementById('m_title').value = item.title;
        document.getElementById('m_desc').value = item.desc;
        document.getElementById('m_module').value = item.module;
        document.getElementById('m_root').value = item.root;

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

            await manualSync(document.querySelector('.pill:last-child'));

            pModal.hide();

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

            setupData(data);

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