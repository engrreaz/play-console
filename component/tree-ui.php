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


$chain_md = 12;


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

echo $chain;
$col = 12;


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
 * Node Tree Modal - Refactored to "M3-EIM-Floating" Style
 * Standards: Floating Labels, Leading Icons, 8px Radius, Tonal M3 Palette
 */
?>

<style>
    /* ১. মোডাল কন্টেইনার এবং ওভারলে */
    #nodeTreeModal .modal-content {
        background-color: #FEF7FF; /* M3 Surface */
        border-radius: 8px !important; /* Strict 8px Radius */
        border: 1px solid #EADDFF;
        box-shadow: 0 12px 32px rgba(103, 80, 164, 0.12);
    }

    #nodeTreeModal .modal-header {
        border-bottom: 1px solid #F3EDF7;
        padding: 16px 20px;
    }

    .m3-modal-title {
        font-size: 0.95rem; font-weight: 800; color: #21005D;
        display: flex; align-items: center; gap: 8px;
    }

    /* ২. "M3-EIM-Floating" ইনপুট স্টাইল (সার্চ বা চেইন ইনপুট) */
    .m3-floating-group {
        position: relative;
        margin: 16px 20px 8px; /* মোডালের ভেতরে মার্জিন */
    }

    .m3-field-icon {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%); color: #6750A4;
        font-size: 1.2rem; z-index: 10;
    }

    .m3-floating-label {
        position: absolute; left: 44px; top: -10px;
        background: #FEF7FF; padding: 0 6px;
        font-size: 0.7rem; font-weight: 800; color: #6750A4;
        z-index: 15; letter-spacing: 0.5px; text-transform: uppercase;
    }

    .m3-input-floating {
        width: 100%; height: 48px;
        padding: 10px 16px 10px 48px;
        font-size: 0.9rem; font-weight: 600; color: #1C1B1F;
        background-color: transparent;
        border: 2px solid #CAC4D0;
        border-radius: 8px !important;
        transition: all 0.2s ease;
    }

    .m3-input-floating:focus {
        border-color: #6750A4; outline: none; box-shadow: 0 0 0 1px #6750A4;
    }

    /* ৩. ট্রি এবং লিস্ট আইটেম (M3 Tonal) */
    .tree-root { list-style: none; padding: 0; margin: 0; }
    
    .m3-tree-item {
        background: #fff; border-radius: 8px; padding: 12px 16px;
        margin-bottom: 8px; border: 1px solid #F3EDF7;
        display: flex; align-items: center; transition: all 0.2s;
        cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
    .m3-tree-item:active { background-color: #EADDFF; transform: scale(0.98); }
    
    .m3-tree-item i { color: #6750A4; margin-right: 14px; font-size: 1.2rem; }
    
    .m3-tree-text { font-size: 0.85rem; font-weight: 700; color: #1C1B1F; }

    /* সাবজেক্ট লিস্ট (Condensed but Elegant) */
    .m3-subject-item {
        border-radius: 8px !important; border: 1px solid #EADDFF !important;
        background: #fff; margin-bottom: 6px; padding: 14px;
        font-size: 0.85rem; font-weight: 700; color: #21005D;
        display: flex; justify-content: space-between; align-items: center;
    }
    .m3-subject-item:active { background: #F3EDF7; }

    /* ৪. মোডাল বডি স্ক্রলবার */
    .modal-body::-webkit-scrollbar { width: 4px; }
    .modal-body::-webkit-scrollbar-thumb { background: #EADDFF; border-radius: 10px; }
</style>

<div class="modal fade" id="nodeTreeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-<?= $size ?> modal-dialog-scrollable">
        <div class="modal-content">
            
            
            <div class="m3-floating-group">
                <label class="m3-floating-label">Search / Chain Data</label>
                <i class="bi bi-search m3-field-icon"></i>
                <input type="text" id="chainInput" class="m3-input-floating" value="<?php echo htmlspecialchars($chain); ?>" placeholder="Type to filter...">
            </div>
            
            <div class="modal-header border-0">
                <h6 class="m3-modal-title">
                    <i class="bi bi-diagram-3 text-primary"></i>
                    Select <span class="text-primary"><?= htmlspecialchars($Title) ?></span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4">
                <div class="row g-2">

                    <div class="col-<?= $col ?>" id="treeColumn">
                        <div class="small fw-bold text-muted mb-3 text-uppercase" style="font-size: 0.6rem; letter-spacing: 1.5px;">Hierarchy Tree</div>
                        <ul id="treeRoot" class="tree-root">
                            </ul>
                    </div>

                    <div class="col-12 d-none" id="subjectColumn">
                        <div class="d-flex align-items-center mb-3">
                            <button class="btn btn-sm btn-outline-primary border-0 rounded-circle me-2" id="backToTree">
                                <i class="bi bi-arrow-left-short fs-4"></i>
                            </button>
                            <h6 class="mb-0 fw-bold text-secondary" style="font-size: 0.85rem;">Select Subject</h6>
                        </div>
                        <ul id="subjectList" class="list-group list-group-flush">
                            </ul>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 p-3 justify-content-center">
                <small class="text-muted fw-bold" style="font-size: 0.55rem; text-transform: uppercase; opacity: 0.6;">
                    EIMBox Intelligence System
                </small>
            </div>

        </div>
    </div>
</div>

<script>
    /**
     * M3-EIM-Floating Logic for Tree Interaction
     */
    document.getElementById('backToTree')?.addEventListener('click', function() {
        const subCol = document.getElementById('subjectColumn');
        const treeCol = document.getElementById('treeColumn');
        
        // হালকা ট্রানজিশন ইফেক্ট
        subCol.style.opacity = '0';
        setTimeout(() => {
            subCol.classList.add('d-none');
            treeCol.classList.remove('d-none');
            treeCol.style.opacity = '1';
        }, 150);
    });
</script>


















<div class="m3-floating-card shadow-sm">
    <div class="m3-header-tonal">
        <h6 class="m3-header-title">
            <i class="bi bi-funnel-fill me-2"></i><?= htmlspecialchars($box_title) ?>
        </h6>
        <button type="button" class="tonal-icon-btn shadow-sm" id="openTree">
            <i class="bi bi-diagram-3-fill"></i>
        </button>
    </div>

    <div class="m3-body-floatingx px-0">
        <div class="row">
            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Slot / Unit</label>
                    <i class="bi bi-layers m3-field-icon"></i>
                    <select id="slot-main" class="m3-select-floating">
                        <option value="">Select Slot</option>
                        <?php
                        $q = $conn->query("SELECT slotname FROM slots WHERE sccode='$sccode' ORDER BY slotname");
                        while ($r = $q->fetch_assoc()) {
                            echo "<option value='".htmlspecialchars($r['slotname'])."'>".htmlspecialchars($r['slotname'])."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Session</label>
                    <i class="bi bi-calendar-event m3-field-icon"></i>
                    <select id="session-main" class="m3-select-floating">
                        <option value="">Select Session</option>
                        <?php
                        $q = $conn->query("SELECT syear FROM sessionyear WHERE sccode='$sccode' AND active=1 ORDER BY syear DESC");
                        while ($r = $q->fetch_assoc()) {
                            echo "<option value='".htmlspecialchars($r['syear'])."'>".htmlspecialchars($r['syear'])."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Examination</label>
                    <i class="bi bi-journal-check m3-field-icon"></i>
                    <select id="exam-main" class="m3-select-floating">
                        <option value="">Select Exam</option>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Class</label>
                    <i class="bi bi-mortarboard m3-field-icon"></i>
                    <select id="class-main" class="m3-select-floating">
                        <option value="">Select Class</option>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Section</label>
                    <i class="bi bi-people m3-field-icon"></i>
                    <select id="section-main" class="m3-select-floating">
                        <option value="">Select Section</option>
                    </select>
                </div>
            </div>

            <div class="col-md-<?= $chain_md ?> col-12">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">Subject</label>
                    <i class="bi bi-book m3-field-icon"></i>
                    <select id="subject-main" class="m3-select-floating">
                        <option value="">Select Subject</option>
                    </select>
                </div>
            </div>

            <div class="col-12">
                <button type="button" class="btn-m3-submit shadow" id="btn-chain">
                    <span><?= htmlspecialchars($chain_button_text) ?></span>
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>

        </div>
    </div>
</div>