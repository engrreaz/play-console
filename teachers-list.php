<?php
$page_title = "Teachers & Staff Directory";
include 'inc.php';
include 'datam/datam-teacher.php';

// ১. র‍্যাঙ্ক রেজোলিউশনের জন্য ডেজিগনেশন ম্যাপ তৈরি (যাতে লুপের ভেতর বারবার কুয়েরি না করতে হয়)
$designation_map = [];
$res_desig = $conn->query("SELECT title, ranks FROM designation");
while ($d = $res_desig->fetch_assoc()) {
    $designation_map[$d['title']] = $d['ranks'];
}

// ২. টিচার এবং স্টাফদের জন্য আলাদা অ্যারে
$faculty_list = [];
$support_staff_list = [];
$total_count = 0;

foreach ($datam_teacher_profile as $t) {
    $tid = $t["tid"];

    // র‍্যাঙ্ক নির্ধারণ লজিক
    $eff_rank = $t['ranks'];
    if (empty($eff_rank)) {
        $pos = $t['position'] ?? '';
        $eff_rank = $designation_map[$pos] ?? 99; // না পাওয়া গেলে ডিফল্ট বড় মান
    }

    $t['effective_rank'] = (int) $eff_rank;

    if ($t['effective_rank'] < 40) {
        $faculty_list[] = $t;
    } else {
        $support_staff_list[] = $t;
    }
    $total_count++;
}

// র‍্যাঙ্ক অনুযায়ী সর্টিং
usort($faculty_list, fn($a, $b) => $a['effective_rank'] <=> $b['effective_rank']);
usort($support_staff_list, fn($a, $b) => $a['effective_rank'] <=> $b['effective_rank']);
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FEF7FF;
    }

    body {
        background: var(--m3-surface);
    }

    /* Enhanced Hero */
    .hero-dir {
        background: linear-gradient(135deg, #6750A4 0%, #4527A0 100%);
        color: white;
        padding: 40px 24px 60px;
        border-radius: 0 0 32px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(103, 80, 164, 0.2);
    }

    .hero-dir::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* List Containers */
    .dir-container {
        margin-top: -35px;
        padding: 0 16px 100px;
        position: relative;
        z-index: 10;
    }

    .section-header-m3 {
        font-size: 0.7rem;
        font-weight: 900;
        color: #49454F;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 25px 0 12px 10px;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-header-m3::after {
        content: '';
        flex-grow: 1;
        height: 1px;
        background: #E7E0EC;
    }

    /* M3 Teacher Card */
    .m3-staff-card {
        background: white;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        border: 1px solid #E7E0EC;
        transition: 0.3s;
        text-decoration: none !important;
    }

    .m3-staff-card:active {
        transform: scale(0.97);
        background: #F3EDF7;
    }

    .staff-avatar {
        width: 62px;
        height: 62px;
        border-radius: 12px;
        object-fit: cover;
        background: #EADDFF;
        border: 1.5px solid #F3EDF7;
        flex-shrink: 0;
    }

    .staff-info {
        margin-left: 15px;
        flex-grow: 1;
        min-width: 0;
    }

    .staff-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1D1B20;
        line-height: 1.2;
        display: block;
    }

    .staff-pos {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--m3-primary);
        display: block;
        margin-top: 2px;
    }

    .badge-row {
        display: flex;
        gap: 6px;
        margin-top: 6px;
    }

    .id-tag {
        font-size: 0.55rem;
        font-weight: 800;
        background: #F4EFF4;
        color: #49454F;
        padding: 4px 12px;
        border-radius: 4px;
    }

    .status-inactive {
        opacity: 0.5;
        filter: grayscale(1);
    }


    /* সার্চ হাইলাইট ইফেক্ট */
    .search-highlight {
        border: 2px solid #6750A4 !important;
        background-color: #F3EDF7 !important; /* হালকা বেগুনি আভা */
        box-shadow: 0 0 15px rgba(103, 80, 164, 0.4) !important;
        transform: scale(1.02);
        z-index: 100;
    }

    /* সার্চ চলাকালীন অন্য কার্ডগুলো ঝাপসা দেখাবে (ঐচ্ছিক) */
    .dim-card {
        opacity: 0.4;
        filter: blur(1px);
    }

    /* সার্চ বার স্টিকি করার ম্যাজিক */
    #sticky-search-anchor {
        position: sticky;
        top: 60px; /* আপনার মেইন অ্যাপ-বার যদি ৬০ পিক্সেল হয়, তবে এটি তার নিচে থাকবে */
        z-index: 999999;
        border-radius:8px;
        margin: 0 -16px; /* কন্টেইনারের প্যাডিং অ্যাডজাস্ট করার জন্য */
        padding: 10px 16px;
        transition: all 0.3s ease;
    }

    /* যখন সার্চ বার একটিভ হবে, তখন ব্যাকগ্রাউন্ড আসবে */
    .search-active-bg {
        background: #6750A4; /* হিরোর কালারের সাথে মিল রেখে */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    /* সার্চ চলাকালীন কার্ডের ওপর যাতে ওভারল্যাপ না হয় */
    .m3-staff-card {
        scroll-margin-top: 130px; /* স্টিকি বারের জন্য অফসেট */
    }

    #teacherSearch {
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        border: 2px solid transparent;
    }

    #teacherSearch:focus {
        border-color: #EADDFF;
        background-color: #fff;
    }



</style>

<main>
    <div class="hero-dir">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-black m-0" style="letter-spacing: -1px;">Staff Directory</h3>
                <p class="small m-0 opacity-75 fw-bold text-uppercase" style="font-size: 0.7rem;">Session
                    <?php echo $sessionyear; ?></p>
            </div>
            <div class="text-end">
                <div class="h2 fw-black m-0"><?= $total_count ?></div>
                <div class="small fw-bold opacity-75" style="font-size: 0.6rem; text-transform: uppercase;">Total
                    Members</div>
            </div>
        </div>

        
        
<div class="mt-4" id="sticky-search-anchor">
    <div class="d-flex justify-content-between align-items-center position-relative">
         <div id="search-wrapper" style="display: none; flex-grow: 1; margin-right: 10px; z-index:999999;">
            <input type="text" id="teacherSearch" class="form-control shadow-sm" 
                   placeholder="Type name or ID..." 
                   style="border-radius: 12px; border: none; padding: 12px 15px; font-weight: 600;"
                   onkeyup="searchTeacher()">
         </div>

         <div id="filter-label" style="background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.2); color: white;">
            <i class="bi bi-funnel-fill me-1"></i> Organized by Rank
         </div>

         <button class="tonal-icon-btn shadow-sm" id="searchTrigger" onclick="toggleSearch()"
                 style="background: white; color: var(--m3-primary); border-radius: 14px; width:45px; height:45px; flex-shrink: 0; border:none;">
            <i class="bi bi-search" id="search-icon" style="font-size: 1.2rem;"></i>
         </button>
    </div>
</div>



    </div>

    <div class="dir-container">

        <?php if (!empty($faculty_list)): ?>
            <div class="section-header-m3" style="margin-top:48px;">Academic Faculty</div>
            <?php foreach ($faculty_list as $t):
                $tid = $t['tid'];
                $is_inactive = ($t['status'] ?? 1) == 0;
                ?>
                <a href="hr-profile.php?id=<?= $tid ?>"
                    class="m3-staff-card shadow-sm <?= $is_inactive ? 'status-inactive' : '' ?>">
                    <img src="<?= teacher_profile_image_path($tid) ?>" class="staff-avatar"
                        onerror="this.src='iimg/default_teacher.png'">
                    <div class="staff-info">
                        <span class="staff-name"><?= $t['tname'] ?></span>
                        <span class="staff-pos"><?= $t['position'] ?></span>
                        <div class="badge-row">
                            <span class="id-tag">ID: <?= $tid ?></span>
                            <?php if ($is_inactive): ?><span class="id-tag bg-danger text-white">Inactive</span><?php endif; ?>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-25 fs-5"></i>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($support_staff_list)): ?>
            <div class="section-header-m3">Administration & Support</div>
            <?php foreach ($support_staff_list as $s):
                $tid = $s['tid'];
                $is_inactive = ($s['status'] ?? 1) == 0;
                ?>
                <a href="hr-profile.php?id=<?= $tid ?>"
                    class="m3-staff-card shadow-sm <?= $is_inactive ? 'status-inactive' : '' ?>">
                    <img src="<?= teacher_profile_image_path($tid) ?>" class="staff-avatar"
                        onerror="this.src='iimg/default_staff.png'">
                    <div class="staff-info">
                        <span class="staff-name"><?= $s['tname'] ?></span>
                        <span class="staff-pos"><?= $s['position'] ?></span>
                        <div class="badge-row">
                            <span class="id-tag">ID: <?= $tid ?></span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted opacity-25 fs-5"></i>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</main>

<!-- <div style="height: 80px;"></div> -->
<?php include 'footer.php'; ?>


<script>
    // ১. সার্চ বার অন/অফ করা


</script>


<script>
    function toggleSearch() {
        const anchor = document.getElementById('sticky-search-anchor');
        const wrapper = document.getElementById('search-wrapper');
        const label = document.getElementById('filter-label');
        const icon = document.getElementById('search-icon');
        const input = document.getElementById('teacherSearch');

        if (wrapper.style.display === 'none') {
            wrapper.style.display = 'block';
            label.style.display = 'none';
            anchor.classList.add('search-active-bg'); // ব্যাকগ্রাউন্ড অন
            icon.classList.replace('bi-search', 'bi-x-lg');
            input.focus();
        } else {
            wrapper.style.display = 'none';
            label.style.display = 'block';
            anchor.classList.remove('search-active-bg'); // ব্যাকগ্রাউন্ড অফ
            icon.classList.replace('bi-x-lg', 'bi-search');
            resetSearch();
        }
    }

    function searchTeacher() {
        const query = document.getElementById('teacherSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.m3-staff-card');
        let firstMatch = null;

        if (query.length < 2) {
            cards.forEach(c => c.classList.remove('search-highlight', 'dim-card'));
            return;
        }

        cards.forEach(card => {
            const name = card.querySelector('.staff-name').innerText.toLowerCase();
            const id = card.querySelector('.id-tag').innerText.toLowerCase(); // ID সার্চও কাজ করবে
            
            if (name.includes(query) || id.includes(query)) {
                card.classList.add('search-highlight');
                card.classList.remove('dim-card');
                if (!firstMatch) firstMatch = card;
            } else {
                card.classList.remove('search-highlight');
                card.classList.add('dim-card');
            }
        });

        if (firstMatch) {
            // block: 'start' ব্যবহার করা হয়েছে এবং CSS-এ scroll-margin-top দেওয়া হয়েছে
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function resetSearch() {
        const cards = document.querySelectorAll('.m3-staff-card');
        cards.forEach(card => card.classList.remove('search-highlight', 'dim-card'));
        document.getElementById('teacherSearch').value = '';
    }
</script>