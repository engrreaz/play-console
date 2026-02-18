<?php
$page_title = "My Subjects";
include 'inc.php';

$subjects_taught = [];

// ========================
// Step 1: Routine Query (Logic Intact)
// ========================
$sql = "
    SELECT DISTINCT subcode, classname, sectionname 
    FROM clsroutine 
    WHERE sccode = ? AND sessionyear LIKE ? AND tid = ? 
    ORDER BY subcode;
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sccode, $sessionyear_param, $userid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $subjects_taught[] = $row;
}
$stmt->close();

include_once 'datam/datam-subject-list.php';
?>

<style>
    :root {
        --m3-primary: #6750A4;
        --m3-surface: #FEF7FF;
        --m3-on-surface: #1C1B1F;
        --m3-outline: #79747E;
        --m3-tonal-container: #EADDFF;
    }

    body {
        background: var(--m3-surface);
    }

    /* Hero Section Polish */
    .hero-container {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        border-radius: 0 0 28px 28px;
        padding: 30px 20px;
        margin-bottom: 20px;
    }

    /* Card Styling */
    .subject-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        border: 1px solid #E7E0EC;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .subject-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: var(--m3-primary);
    }

    .book-wrapper {
        width: 65px;
        height: 85px;
        border-radius: 12px;
        overflow: hidden;
        margin-right: 18px;
        flex-shrink: 0;
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .m3-subject-chip {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--m3-tonal-container);
        color: #21005D;
    }

    /* New Modal UI */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-backdrop {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(28, 27, 31, 0.4);
        backdrop-filter: blur(6px);
    }


    @keyframes m3SlideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        border: none;
        background: #F4EFF4;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Marks Grid */





    /* আরও কম্প্যাক্ট মোডাল ডিজাইন */
    .modal-content {
        position: relative;
        width: 92%;
        max-width: 380px;
        padding: 20px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    /* ৩-কলামের টাইট গ্রিড */
    .marks-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /* ৩টি কলাম */
        gap: 8px;
        margin-top: 15px;
    }

    .mark-item {
        background: #F7F2FA;
        padding: 6px 4px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid #F0E7F5;
    }

    .mark-val {
        font-size: 0.95rem;
        /* ফন্ট একটু ছোট করা হয়েছে */
        font-weight: 900;
        color: var(--m3-primary);
        display: block;
        line-height: 1;
    }

    .mark-label {
        font-size: 0.55rem;
        /* লেবেল আরও ছোট */
        color: var(--m3-outline);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* CA আইটেমটি পুরো নিচের লাইন জুড়ে থাকবে */
    .ca-wide {
        grid-column: span 3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 12px;
    }
</style>

<main>
    <div class="hero-container shadow">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 15px;">
                <i class="bi bi-journal-bookmark-fill fs-3"></i>
            </div>
            <div>
                <div style="font-size: 1.6rem; font-weight: 900; line-height: 1.1;">My Subjects</div>
                <div style="font-size: 0.85rem; opacity: 0.85;">Current Academic Session: <?php echo $sessionyear; ?>
                </div>
            </div>
        </div>
    </div>

    <div style="padding: 0 16px;">
        <h6
            style="font-weight: 800; color: var(--m3-outline); margin: 20px 0 15px 5px; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">
            Teaching Assignments</h6>

        <?php if (!empty($subjects_taught)): ?>
            <?php foreach ($subjects_taught as $info):
                $subcode = $info['subcode'];
                $stind = array_search($subcode, array_column($datam_subject_list, 'subcode'));
                if ($stind === false)
                    continue;

                $seng = $datam_subject_list[$stind]["subject"];
                $sben = $datam_subject_list[$stind]["subben"];
                $clsname = $info['classname'];
                $secname = $info['sectionname'];
                $display_path = $BASE_PATH_URL_FILE . 'assets/books/allbook.webp';

                // SQL remains exactly as requested
                $sql_subsetup = "SELECT * FROM subsetup WHERE classname = ? AND sectionname = ? AND subject = ? AND tid = ? AND sessionyear LIKE ? and sccode = ? LIMIT 1";
                $stmt_sub = $conn->prepare($sql_subsetup);
                $stmt_sub->bind_param("sssisi", $clsname, $secname, $subcode, $userid, $sessionyear_param, $sccode);
                $stmt_sub->execute();
                $subsetup_details = $stmt_sub->get_result()->fetch_assoc();
                $stmt_sub->close();
                ?>
                <div class="subject-card shadow-sm" data-title="<?php echo htmlspecialchars($seng); ?>"
                    data-desc="<?php echo htmlspecialchars($sben); ?>" data-class="<?php echo htmlspecialchars($clsname); ?>"
                    data-section="<?php echo htmlspecialchars($secname); ?>" data-subcode="<?php echo $subcode; ?>"
                    data-fullmarks="<?php echo $subsetup_details['fullmarks'] ?? 'NA'; ?>"
                    data-ctest="<?php echo $subsetup_details['ctest'] ?? 'NA'; ?>"
                    data-mtest="<?php echo $subsetup_details['mtest'] ?? 'NA'; ?>"
                    data-subj="<?php echo $subsetup_details['subj'] ?? 'NA'; ?>"
                    data-obj="<?php echo $subsetup_details['obj'] ?? 'NA'; ?>"
                    data-pra="<?php echo $subsetup_details['pra'] ?? 'NA'; ?>"
                    data-ca="<?php echo $subsetup_details['ca'] ?? 'NA'; ?>">
                    <div class="book-wrapper">
                        <img src="<?php echo $display_path; ?>" alt="Book">
                    </div>

                    <div style="flex-grow: 1;">
                        <div style="font-size: 1.1rem; font-weight: 800; color: #1D1B20;"><?php echo htmlspecialchars($seng); ?>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--m3-outline); font-weight: 500; margin-bottom: 8px;">
                            <?php echo htmlspecialchars($sben); ?></div>

                        <div style="display:flex; gap:8px;">
                            <span class="m3-subject-chip" style="background:#F3EDF7;"><i class="bi bi-mortarboard"></i>
                                <?php echo $clsname; ?></span>
                            <span class="m3-subject-chip" style="background:#E8F0FF;"><i class="bi bi-layers"></i>
                                <?php echo $secname; ?></span>
                        </div>
                    </div>

                    <div style="text-align:right;">
                        <div class="sub-code-badge"
                            style="background: var(--m3-tonal-container); border:none; padding: 4px 16px; border-radius: 8px;;">
                            <i class="bi bi-book"></i>
                            <?php echo $subcode; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; margin-top: 50px; color: var(--m3-outline);">
                <i class="bi bi-journal-x" style="font-size:4rem; opacity: 0.3;"></i>
                <p class="fw-bold mt-2">No assigned subjects found.</p>
            </div>
        <?php endif; ?>
    </div>
</main>





<div id="subjectModal" class="modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content" style="z-index:1999;" >
        <button class="close-btn close" style="top: 12px; right: 12px;"><i class="bi bi-x-lg"></i></button>

        <div style="text-align: left; padding-right: 30px;">
            <div id="modalSubCode"
                style="font-size:0.6rem; font-weight:900; color:var(--m3-primary); text-transform:uppercase; margin-bottom:2px;">
            </div>
            <h5 id="modalSubjectTitle" style="font-weight: 900; margin:0; color:#1D1B20; font-size: 1.1rem;"></h5>
            <p id="modalSubjectDesc" style="color:var(--m3-outline); font-size:0.8rem; margin:0;"></p>
        </div>

        <div style="display:flex; gap:6px; margin-top:12px;">
            <span class="m3-subject-chip" id="modalClass" style="font-size: 0.65rem; padding: 2px 8px;"></span>
            <span class="m3-subject-chip" id="modalSection" style="font-size: 0.65rem; padding: 2px 8px;"></span>
        </div>

        <div class="marks-grid">
            <div class="mark-item">
                <span class="mark-label">Full</span>
                <span id="modalFullmarks" class="mark-val"></span>
            </div>
            <div class="mark-item">
                <span class="mark-label">CT</span>
                <span id="modalCtest" class="mark-val"></span>
            </div>
            <div class="mark-item">
                <span class="mark-label">MT</span>
                <span id="modalMtest" class="mark-val"></span>
            </div>
            <div class="mark-item">
                <span class="mark-label">Subj</span>
                <span id="modalSubj" class="mark-val"></span>
            </div>
            <div class="mark-item">
                <span class="mark-label">Obj</span>
                <span id="modalObj" class="mark-val"></span>
            </div>
            <div class="mark-item">
                <span class="mark-label">Pra</span>
                <span id="modalPra" class="mark-val"></span>
            </div>
            <div class="mark-item ca-wide">
                <span class="mark-label" style="margin:0;">Continuous Assess. (CA)</span>
                <span id="modalCA" class="mark-val"></span>
            </div>
        </div>

        <div style="margin-top: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;" hidden>
            <button class="btn btn-primary btn-sm rounded-pill fw-bold py-2" style="font-size: 0.7rem;">
                <i class="bi bi-pencil-square me-1"></i> MARKS ENTRY
            </button>
            <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold py-2" style="font-size: 0.7rem;">
                <i class="bi bi-file-earmark-pdf me-1"></i> SYLLABUS
            </button>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('subjectModal');
        const titleEl = document.getElementById('modalSubjectTitle');
        const descEl = document.getElementById('modalSubjectDesc');
        const classEl = document.getElementById('modalClass');
        const sectionEl = document.getElementById('modalSection');
        const subCodeEl = document.getElementById('modalSubCode');

        // IDs for marks
        const markIds = ['Fullmarks', 'Ctest', 'Mtest', 'Subj', 'Obj', 'Pra', 'CA'];

        document.querySelectorAll('.subject-card').forEach(card => {
            card.addEventListener('click', () => {
                titleEl.textContent = card.dataset.title;
                descEl.textContent = card.dataset.desc;
                classEl.innerHTML = `<i class="bi bi-mortarboard"></i> ${card.dataset.class}`;
                sectionEl.innerHTML = `<i class="bi bi-layers"></i> ${card.dataset.section}`;
                subCodeEl.textContent = 'CODE: ' + card.dataset.subcode;

                markIds.forEach(id => {
                    document.getElementById('modal' + id).textContent = card.dataset[id.toLowerCase()];
                });

                modal.style.display = 'flex';
            });
        });

        const closeModal = () => modal.style.display = 'none';
        modal.querySelector('.close').onclick = closeModal;
        modal.querySelector('.modal-backdrop').onclick = closeModal;
    });
</script>