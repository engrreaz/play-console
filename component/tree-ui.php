<?php


$chain = '';
// $nodes = explode(' ', $chain);


function parseChainParam(string $param): array
{
    $out = [
        'col' => '',
        'title' => '',
        'url' => false,
        'hidden' => [],
        'reload' => false
    ];

    if (!$param)
        return $out;

    // -u flag
    if (strpos($param, '-u') !== false) {
        $out['url'] = true;
    }
    if (strpos($param, '-r') !== false) {
        $out['reload'] = true;
    }

    // -t Title
    if (preg_match('/-c\s+([^-\n]+)/', $param, $m)) {
        $out['col'] = trim($m[1]);
    }
    if (preg_match('/-t\s+([^-\n]+)/', $param, $m)) {
        $out['title'] = trim($m[1]);
    }
    if (preg_match('/-b\s+([^-\n]+)/', $param, $m)) {
        $out['button'] = trim($m[1]);
    }

    // -h hidden list
    if (preg_match('/-h\s+([a-zA-Z0-9_,]+)/', $param, $m)) {
        $out['hidden'] = array_filter(
            array_map('trim', explode(',', $m[1]))
        );
    }

    return $out;
}


$my_chain_params = parseChainParam($chain_param);



$box_title = $my_chain_params['title'] ?? 'Choose Values';
$chain_button_text = $my_chain_params['button'] ?? 'View';


$chain_md = 3;


if ($my_chain_params['url'] == true) {
    $chain .= ' url ';
}
if ($my_chain_params['reload'] == true) {
    $chain .= ' reload ';
}

foreach ($my_chain_params['hidden'] as $ygl) {
    $chain .= ' ' . $ygl . ' ';
}


// --------------------------------------------------------------

$Title = 'Slot → Session';
if (strpos($chain, 'exam') !== false) {
    $Title .= ' → Exam';
}

$Title .= ' → Class → Section';

if (strpos($chain, 'subject') !== false) {
    $Title .= ' →  Subject';
}

if (strpos($chain, 'class') !== false) {
    $Title = str_replace(' → Class → Section', '', $Title);
}


?>

<input id="selectedTree" type="text" value="333">


<?php
/**
 * Node Tree & Subject Selector Modal
 * Refactored for Android WebView | Material 3 Style
 * Strict 8px Radius | High Data Density
 */
?>

<style>
    /* M3 Modal Customization */
    #nodeTreeModal .modal-content {
        background-color: #FEF7FF;
        /* M3 Surface */
        border-radius: 8px !important;
        /* আপনার নির্দেশিত ৮ পিক্সেল */
        border: 1px solid #EADDFF;
        box-shadow: 0 4px 15px rgba(103, 80, 164, 0.1);
        width: 90%;
        margin: auto;
        /* WebView-তে সাইড স্পেসিং ঠিক রাখার জন্য */
    }

    #nodeTreeModal .modal-header {
        background-color: #fff;
        border-bottom: 1px solid #F3EDF7;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0 !important;
    }

    .m3-modal-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: #1C1B1F;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Chain Input Styling (Hidden carrier or Styled bar) */
    #chainInput {
        background: #F3EDF7;
        border: none;
        border-radius: 4px;
        font-size: 0.7rem;
        padding: 4px 12px;
        margin: 8px 16px 0;
        color: #6750A4;
        font-weight: 600;
        pointer-events: none;
    }

    /* Tree Navigation (M3 List Style) */
    .tree-root {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tree-item {
        padding: 10px 14px;
        margin-bottom: 4px;
        border-radius: 8px;
        /* Strict 8px */
        background: #fff;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        transition: background 0.2s;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .tree-item:active {
        background-color: #EADDFF;
        transform: scale(0.98);
    }

    .tree-item i {
        color: #6750A4;
        margin-right: 12px;
        font-size: 1.1rem;
    }

    /* Subject List (Condensed) */
    .subject-list-item {
        border-radius: 8px !important;
        margin-bottom: 6px;
        border: 1px solid #EADDFF !important;
        background: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 12px;
    }

    .subject-list-item:active {
        background: #F3EDF7;
    }

    /* Scrollbar for WebView */
    .modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #EADDFF;
        border-radius: 10px;
    }
</style>

<div class="modal fade" id="nodeTreeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow">

            <input type="text" id="chainInput" class="shadow-sm" value="<?php echo htmlspecialchars($chain); ?>"
                readonly>

            <div class="modal-header">
                <div class="m3-modal-title">
                    Select <span class="text-primary"><?= htmlspecialchars($Title) ?></span>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                    style="font-size: 0.7rem;"></button>
            </div>

            <div class="modal-body p-3">
                <div class="row g-2">

                    <div class="col-12" id="treeColumn">
                        <div class="small fw-bold text-muted mb-2 text-uppercase"
                            style="font-size: 0.6rem; letter-spacing: 1px;">Navigation Hierarchy</div>
                        <ul id="treeRoot" class="tree-root">
                        </ul>
                    </div>

                    <div class="col-12 d-none" id="subjectColumn">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-sm btn-outline-primary border-0 py-0 me-2" id="backToTree">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <h6 class="mb-0 fw-bold text-secondary" style="font-size: 0.85rem;">Available Subjects</h6>
                        </div>
                        <ul id="subjectList" class="list-group list-group-flush">
                        </ul>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 p-2 d-flex justify-content-center">
                <div class="text-muted" style="font-size: 0.55rem; font-weight: 700; text-transform: uppercase;">
                    Powered by EIMBox Logic Console
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    /**
     * Tree Navigation Logic for WebView
     * স্পর্শজনিত অভিজ্ঞতার জন্য এখানে হালকা ট্রানজিশন যোগ করা হয়েছে।
     */
    document.getElementById('backToTree')?.addEventListener('click', function () {
        document.getElementById('subjectColumn').classList.add('d-none');
        document.getElementById('treeColumn').classList.remove('d-none');
    });
</script>




<style>
    .tree-root,
    .tree-root ul {
        list-style: none;
        padding-left: 18px;
    }

    .tree-node {
        cursor: pointer;
        padding: 4px 0;
    }

    .tree-node .toggle {
        font-weight: bold;
        margin-right: 6px;
    }

    .tree-node.section {
        color: #0d6efd;
    }

    .tree-node.section:hover {
        text-decoration: underline;
    }


    .tree-root,
    #subjectList {
        max-height: 60vh;
        overflow-y: auto;
    }

    #subjectList .list-group-item {
        cursor: pointer;
    }

    #subjectList .list-group-item.active {
        font-weight: bold;
        background: #0d6efd;
        color: #fff;
    }
</style>



<?php
/**
 * Chain Selector Block - Refactored for Android WebView
 * M3 Standards | 8px Radius | Dynamic PHP Logic
 */
?>

<style>
    /* M3 Selection Card Styling */
    .m3-selection-card {
        background-color: #fff;
        border-radius: 8px !important;
        /* আপনার নির্দেশিত ৮ পিক্সেল */
        border: 1px solid #f0f0f0;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .m3-card-header {
        padding: 12px 16px;
        background-color: #fff;
        border-bottom: 1px solid #F3EDF7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .m3-card-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #6750A4;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 0;
    }

    .m3-card-body {
        padding: 16px;
    }

    /* M3 Styled Form Controls */
    .m3-input-group {
        margin-bottom: 12px;
    }

    .m3-label-sm {
        font-size: 0.65rem;
        font-weight: 700;
        color: #49454F;
        margin-bottom: 4px;
        display: block;
        text-transform: uppercase;
    }

    .m3-select-sm {
        border-radius: 8px !important;
        /* Strict 8px */
        border: 1px solid #E7E0EC;
        background-color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 12px;
        color: #1C1B1F;
        width: 100%;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .m3-select-sm:focus {
        border-color: #6750A4;
        background-color: #F7F2FA;
        outline: none;
        box-shadow: none;
    }

    /* Primary Action Button (8px Radius) */

    .btn-m3-primary:active {
        transform: scale(0.97);
        opacity: 0.9;
    }

    /* IconButton (Tonal) */
    .btn-tonal-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #F3EDF7;
        color: #6750A4;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>


<?php
/**
 * Refined Floating Chain Selector - Material 3
 * Features: Floating Labels, Optimized Icon Padding, 8px Radius
 */
?>

<style>
    /* মেইন কার্ড - আরও প্রফেশনাল ফ্লোটিং লুক */
    .m3-floating-card {
        background-color: #fff;
        border-radius: 8px !important;
        border: 1px solid #E0E0E0;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.08);
        /* Soft Elevation */
        overflow: hidden;
    }

    .m3-header-tonal {
        padding: 16px 20px;
        background-color: #F7F2FA;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #EADDFF;
    }

    .m3-header-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: #21005D;
        margin: 0;
    }

    .m3-body-floating {
        padding: 24px 20px;
    }

    /* Floating Label Container */
    .m3-floating-group {
        position: relative;
        margin-bottom: 20px;
    }

    /* আইকন প্যাডিং ফিক্স - বর্ডার থেকে দূরত্ব বাড়ানো হয়েছে */
    .m3-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6750A4;
        font-size: 1.2rem;
        z-index: 10;
        pointer-events: none;
        transition: color 0.2s;
    }

    /* Floating Label Styling */


    /* সিলেক্ট বক্স - আইকনের জন্য বামে পর্যাপ্ত জায়গা (Padding) */


    .m3-select-floating:focus {
        border-color: #6750A4;
        box-shadow: 0 0 0 1px #6750A4;
        outline: none;
    }

    /* বাটন ডিজাইন - Gradient & Elevation */
    .btn-m3-submit {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: #fff;
        border: none;
        border-radius: 8px !important;
        height: 52px;
        font-size: 0.95rem;
        font-weight: 700;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.2);
        transition: 0.2s;
        margin-top: 4px;
    }

    .btn-m3-submit:active {
        transform: scale(0.98);
        opacity: 0.9;
    }

    .tonal-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #EADDFF;
        color: #21005D;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="m3-floating-card shadow-sm">
    <div class="m3-header-tonal">
        <h6 class="m3-header-title">
            <i class="bi bi-funnel-fill me-2"></i><?= htmlspecialchars($box_title) ?>
        </h6>
        <button type="button" class="tonal-icon-btn shadow-sm" id="openTree">
            <i class="bi bi-diagram-3-fill"></i>
        </button>
    </div>

    <div class="m3-body-floating">
        <div class="row">

            <div class="col-md-<?= $chain_md ?> ">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Slot / Unit</label>
                    <i class="bi bi-layers m3-field-icon"></i>
                    <select id="slot-main" class="m3-select-floating">
                        <option value="">Select Slot</option>
                        <?php
                        $q = $conn->query("SELECT slotname FROM slots WHERE sccode='$sccode' ORDER BY slotname");
                        while ($r = $q->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($r['slotname']) . "'>" . htmlspecialchars($r['slotname']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> ">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Session</label>
                    <i class="bi bi-calendar-event m3-field-icon"></i>
                    <select id="session-main" class="m3-select-floating">
                        <option value="">Select Session</option>
                        <?php
                        $q = $conn->query("SELECT syear FROM sessionyear WHERE sccode='$sccode' AND active=1 ORDER BY syear DESC");
                        while ($r = $q->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($r['syear']) . "'>" . htmlspecialchars($r['syear']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php $hide = (strpos($chain, 'exam') === false) ? 'hidden' : ''; ?>
            <div class="col-md-<?= $chain_md ?> " <?php echo $hide; ?>>
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Examination</label>
                    <i class="bi bi-journal-check m3-field-icon"></i>
                    <select id="exam-main" class="m3-select-floating">
                        <option value="">Select Exam</option>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> ">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Class</label>
                    <i class="bi bi-mortarboard m3-field-icon"></i>
                    <select id="class-main" class="m3-select-floating">
                        <option value="">Select Class</option>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> ">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Section</label>
                    <i class="bi bi-people m3-field-icon"></i>
                    <select id="section-main" class="m3-select-floating">
                        <option value="">Select Section</option>
                    </select>
                </div>
            </div>

            <?php $hide = (strpos($chain, 'subject') === false) ? 'hidden' : ''; ?>

            <div class="col-md-<?= $chain_md ?> " <?php echo $hide; ?>>
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Subject</label>
                    <i class="bi bi-book m3-field-icon"></i>
                    <select id="subject-main" class="m3-select-floating">
                        <option value="">Select Subject</option>
                    </select>
                </div>
            </div>

            <div class="col-12">
                <button type="button" class="btn-m3-primary shadow-sm" id="btn-chain">

                    <i class="bi bi-arrow-right-circle-fill"></i>
                    <span><?= htmlspecialchars($chain_button_text) ?></span>
                </button>
            </div>

        </div>
    </div>
</div>