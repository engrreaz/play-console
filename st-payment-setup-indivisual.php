<?php
$page_title = "Individual Payment Setup";
include 'inc.php';

// ১. প্যারামিটার এবং সেশন হ্যান্ডলিং
$sessionyear = $_GET['sessionyear'] ?? $_GET['year'] ?? $sessionyear;
$stid = trim($_GET['stid'] ?? '');
$cls2 = $_GET['cls'] ?? '';
$sec2 = $_GET['sec'] ?? '';
$roll2 = $_GET['roll'] ?? '';

// ২. স্টুডেন্ট বেসিক ডাটা (students table)
$stname_eng = "";
if ($stid != '') {
    $st_q = $conn->prepare("SELECT stnameeng FROM students WHERE sccode=? AND stid=?");
    $st_q->bind_param("is", $sccode, $stid);
    $st_q->execute();
    $res = $st_q->get_result();
    if ($r = $res->fetch_assoc())
        $stname_eng = $r['stnameeng'];
}

// ৩. মাস্টার আইটেম লিস্ট (financesetup)
$finsetup = [];
$stmt_fin = $conn->prepare("SELECT itemcode, particulareng, particularben, month, slot FROM financesetup WHERE sccode = ? AND sessionyear LIKE ? ORDER BY slno ASC");
$stmt_fin->bind_param("is", $sccode, $sessionyear_param);
$stmt_fin->execute();
$res_fin = $stmt_fin->get_result();
while ($row = $res_fin->fetch_assoc())
    $finsetup[] = $row;

// ৪. ডিফল্ট ভ্যালু ফেচ করা (financesetupvalue - ক্লাসের জন্য নির্ধারিত ফি)
$default_values = [];
$stmt_def = $conn->prepare("SELECT itemcode, amount FROM financesetupvalue WHERE sccode = ? AND sessionyear LIKE ? AND classname = ?");
$stmt_def->bind_param("iss", $sccode, $sessionyear_param, $cls2);
$stmt_def->execute();
$res_def = $stmt_def->get_result();
while ($row = $res_def->fetch_assoc())
    $default_values[$row['itemcode']] = $row['amount'];

// ৫. ব্যক্তিগত কাস্টম ফি (financesetupind - ওভাররাইড ভ্যালু)
$ind_values = [];
if ($stid != '') {
    $stmt_ind = $conn->prepare("SELECT id, itemcode, amount FROM financesetupind WHERE sccode = ? AND sessionyear LIKE ? AND stid = ?");
    $stmt_ind->bind_param("iss", $sccode, $sessionyear_param, $stid);
    $stmt_ind->execute();
    $res_ind = $stmt_ind->get_result();
    while ($row = $res_ind->fetch_assoc())
        $ind_values[$row['itemcode']] = $row;
}

$frval = array('10', '11', '12', '22', '33', '44', '66', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
$frtxt = array('Oct', 'Nov', 'Dec', '2 Mo.', 'Quarter', '4 Mo.', 'Half-Yr.', 'Monthly', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep');
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-tonal-container: #EADDFF;
        --m3-custom-bg: #F0EBF7;
        /* কাস্টম ভ্যালুর জন্য হালকা টোন */
    }

    body {
        background: var(--m3-surface);
        font-family: 'Segoe UI', sans-serif;
    }

    /* Hero Section */
    .hero-profile {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        margin: 12px;
        padding: 24px 20px;
        border-radius: 24px;
        color: white;
    }

    /* Fee Card Design */
    .fee-card {
        background: white;
        border-radius: 16px;
        padding: 14px;
        margin: 0 12px 10px;
        display: flex;
        align-items: center;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    /* কাস্টম (Override) হলে কার্ডের স্টাইল পরিবর্তন */
    .fee-card-overridden {
        background: var(--m3-custom-bg) !important;
        border-left: 5px solid var(--m3-primary) !important;
    }

    .m3-amount-box {
        width: 90px;
        height: 44px;
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-align: center;
        font-weight: 900;
        color: var(--m3-primary);
    }

    .m3-amount-box.dirty {
        border-color: #FF9800;
        background: #FFF3E0;
    }

    .freq-badge {
        font-size: 0.65rem;
        font-weight: 800;
        background: var(--m3-tonal-container);
        color: #21005D;
        padding: 2px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* Floating Toolbar for Back */
    .top-toolbar {
        padding: 10px 15px;
        display: flex;
        align-items: center;
    }
</style>

<style>
    .fee-card {
        transition: border 0.3s ease;
    }

    /* ইনপুট পরিবর্তন করলে কার্ডে ড্যাশ বর্ডার আসবে */
    .fee-card:has(.dirty) {
        border: 2px dashed #FF9800 !important;
        background: #FFFBF2 !important;
    }

    .m3-amount-box.dirty {
        border-color: #FF9800;
        box-shadow: 0 0 5px rgba(255, 152, 0, 0.2);
    }
</style>


<div class="top-toolbar">
    <button class="btn btn-light rounded-circle shadow-sm" onclick="handleBack()">
        <i class="bi bi-arrow-left fs-5"></i>
    </button>
    <h6 class="m-0 ms-3 fw-bold">Individual Setup</h6>
</div>

<main>
    <div class="hero-profile shadow">
        <div class="d-flex align-items-center">
            <div class="avatar-icon bg-white bg-opacity-25 rounded-4 p-3 me-3">
                <i class="bi bi-person-fill-gear fs-2"></i>
            </div>
            <div>
                <h5 class="fw-black m-0"><?= $stname_eng ?: "Select Student" ?></h5>
                <p class="small m-0 opacity-75">
                    <?= $stid ? "ID: $stid • $cls2 ($sec2) • Roll: $roll2" : "Configure individual fee structures" ?>
                </p>
            </div>
        </div>
    </div>

    <?php if ($stid == ''): ?>
        <div class="p-3">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-search me-2"></i>Find Student</h6>
                <div class="row g-2">
                    <div class="col-6"><input type="number" id="roll" class="form-control" placeholder="Roll No"></div>
                    <div class="col-6">
                        <select id="year" class="form-select">
                            <?php for ($y = date('Y'); $y >= 2024; $y--)
                                echo "<option>$y</option>"; ?>
                        </select>
                    </div>
                    <div class="col-12 mt-2">
                        <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold" onclick="go()">LOAD DATA</button>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="px-3 mt-4 mb-2 d-flex justify-content-between align-items-center">
            <span class="fw-black text-muted small text-uppercase">Fee Items (Session <?= $sessionyear ?>)</span>
            <button class="btn btn-sm btn-tonal text-primary fw-bold" onclick="syncNow('stid', '<?= $stid ?>')">
                <i class="bi bi-arrow-repeat me-1"></i> SYNC ALL
            </button>
        </div>

        <div id="fee-container">
            <?php foreach ($finsetup as $finitem):
                $icode = $finitem['itemcode'];
                $master_amt = $default_values[$icode] ?? 0;

                // ইন্ডিভিজুয়াল ডাটা আছে কি না চেক
                $ind_id = 0;
                $current_amt = $master_amt;
                $is_overridden = false;

                if (isset($ind_values[$icode])) {
                    $ind_id = $ind_values[$icode]['id'];
                    $current_amt = $ind_values[$icode]['amount'];
                    // যদি ইন্ডিভিজুয়াল অ্যামাউন্ট মাস্টারের চেয়ে আলাদা হয়
                    if ($current_amt != $master_amt)
                        $is_overridden = true;
                }

                $freq_text = str_replace($frval, $frtxt, $finitem['month']);
                ?>
                <div class="fee-card shadow-sm <?= $is_overridden ? 'fee-card-overridden' : '' ?>" id="card-<?= $icode ?>">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;"><?= $finitem['particulareng'] ?></div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="freq-badge"><?= $freq_text ?></span>
                            <small class="text-muted fw-bold"><?= $finitem['particularben'] ?></small>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="amt<?= $icode ?>" class="m3-amount-box" data-original="<?= $current_amt ?>"
                                value="<?= $current_amt ?>" oninput="checkDirty('<?= $icode ?>')"
                                onblur="saveInd('<?= $finitem['slot'] ?>', '<?= $sessionyear ?>', '<?= $icode ?>', <?= $ind_id ?>)">
                            <div id="status<?= $icode ?>" style="min-width: 24px;">
                                <i class="bi bi-check2-circle text-muted opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>


<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let isDirty = false;
    // অরিজিনাল ডাটা স্টোর করার জন্য অবজেক্ট (রোলব্যাক এর জন্য)
    const originalData = {};


    // ১. ইনপুট বক্স চেঞ্জ চেক (ডার্টি স্টেট)

    // ২. সিঙ্ক ফাংশন (মাস্টার রুলস অনুযায়ী সব রিসেট করবে)
    function syncNow(type, stid) {
        Swal.fire({
            title: 'Recalculate Data?',
            text: 'এটি এই শিক্ষার্থীর জন্য মাস্টার সেটআপ অনুযায়ী সব ফি রিসেট/সিঙ্ক করবে। আপনি কি নিশ্চিত?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6750A4',
            confirmButtonText: 'হ্যাঁ, সিঙ্ক করুন',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Syncing...', didOpen: () => { Swal.showLoading(); } });

                $.ajax({
                    type: "POST",
                    url: "backend/check-student-finance.php", // এই ফাইলটি মাস্টার ভ্যালু দিয়ে টেবিল আপডেট করে
                    data: { type: type, stid: stid },
                    success: function () {
                        isDirty = false;
                        Swal.fire({ title: 'Success', text: 'সব ফি সিঙ্ক করা হয়েছে!', icon: 'success', timer: 1500, showConfirmButton: false })
                            .then(() =>
                                window.location.href = "stfinancedetails.php?id=" + stid
                            );


                    }
                });
            }
        });
    }

    // ৩. ব্যাক করার সময় সতর্কতা (Sync, Rollback, Cancel)

    // ৪. রোলব্যাক লজিক

    // ৫. ইন্ডিভিজুয়াল সেভ ফাংশন (onblur)

    function go() {
        const y = document.getElementById('year').value;
        const r = document.getElementById('roll').value;
        if (!r) { Swal.fire("Error", "রোল নম্বর দিন", "error"); return; }
        window.location.href = `st-payment-setup-indivisual.php?year=${y}&roll=${r}`;
    }
</script>




<script>
    // ১. পেজ খোলার সময়কার অরিজিনাল ডাটা (এটা কখনোই পরিবর্তন হবে না)
    const sessionInitialState = {};
    let isModifiedSinceOpen = false;

    $(document).ready(function () {
        // পেজ লোড হওয়ার সময় সব ইনপুটের ভ্যালু চিরস্থায়ীভাবে সেভ করে রাখা (রোলব্যাক এর জন্য)
        document.querySelectorAll('.m3-amount-box').forEach(el => {
            const id = el.id.replace('amt', '');
            sessionInitialState[id] = el.value;
        });
    });

    // ২. বর্তমান অবস্থা চেক করা (পেজ খোলার সময়ের সাথে তুলনা)
    function checkGlobalDirty() {
        let modified = false;
        document.querySelectorAll('.m3-amount-box').forEach(el => {
            const id = el.id.replace('amt', '');
            if (el.value !== sessionInitialState[id]) {
                modified = true;
                el.classList.add('dirty'); // ভিজ্যুয়াল মার্কার
            } else {
                el.classList.remove('dirty');
            }
        });
        isModifiedSinceOpen = modified;
        return modified;
    }

    // ইনপুট বক্সে টাইপ করার সময় সাথে সাথে চেক করা
    function checkDirty(icode) {
        checkGlobalDirty();
    }

    // ৩. ব্যাক করার সময় সতর্কতা (Sync, Rollback, Cancel)
    function handleBack() {
        // রিয়েল টাইম চেক
        const hasChanges = checkGlobalDirty();

        if (!hasChanges) {
            window.location.href = "st-payment-setup-indivisual.php"; // সরাসরি আগের লিস্টে চলে যাবে
            return;
        }

        Swal.fire({
            title: 'অসংরক্ষিত পরিবর্তন!',
            text: 'আপনি কিছু ফি পরিবর্তন করেছেন। আপনি কি এগুলো নিশ্চিত করতে চান?',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'সিঙ্ক ও ব্যাক',      // বর্তমান অবস্থা রেখে চলে যাবে
            denyButtonText: 'রোলব্যাক ও ব্যাক',   // পেজ খোলার অবস্থায় ফিরিয়ে নিয়ে যাবে
            cancelButtonText: 'ক্যান্সেল',         // এখানেই থাকবে
            confirmButtonColor: '#6750A4',
            denyButtonColor: '#B3261E',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // সিঙ্ক ও ব্যাক: অলরেডি সেভ হয়ে আছে (Blur এর মাধ্যমে), তাই সরাসরি চলে যাবে
                Swal.fire({ title: 'Saving...', timer: 500, showConfirmButton: false, didOpen: () => Swal.showLoading() })
                    .then(() => {
                        window.location.href = "st-payment-setup-indivisual.php";
                    });
            } else if (result.isDenied) {
                // রোলব্যাক ও ব্যাক: অরিজিনাল অবস্থায় ফিরিয়ে নিয়ে সেভ করবে
                rollbackAndBack();
            }
            // Cancel হলে কিছুই হবে না, ইউজার পেজেই থাকবে।
        });
    }

    // ৪. রোলব্যাক লজিক (পেজ খোলার ভ্যালুগুলোতে ফিরিয়ে নেওয়া)
    async function rollbackAndBack() {
        Swal.fire({ title: 'রোলব্যাক হচ্ছে...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        const promises = [];
        document.querySelectorAll('.m3-amount-box').forEach(input => {
            const icode = input.id.replace('amt', '');
            const originalVal = sessionInitialState[icode];

            // শুধু যেগুলো পরিবর্তন হয়েছে সেগুলো আগের অবস্থায় ফিরিয়ে সেভ করা হবে
            if (input.value !== originalVal) {
                promises.push($.ajax({
                    url: "backend/crud-set-financed-ind.php",
                    type: "POST",
                    data: {
                        item: icode,
                        amt: originalVal,
                        stid: '<?= $stid ?>',
                        sy: '<?= $sessionyear ?>',
                        cls: '<?= $cls2 ?>',
                        sec: '<?= $sec2 ?>',
                        slot: 'School'
                    }
                }));
            }
        });

        if (promises.length > 0) {
            await Promise.all(promises);
        }
        window.location.href = "st-payment-setup-indivisual.php";
    }

    // ৫. ইন্ডিভিজুয়াল সেভ (onblur)
    function saveInd(slot, sy, item, indid) {
        const input = document.getElementById('amt' + item);
        const amt = input.value;
        const statusIcon = document.getElementById('status' + item);

        statusIcon.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';

        $.ajax({
            url: "backend/crud-set-financed-ind.php",
            type: "POST",
            data: { slot: slot, sy: sy, item: item, amt: amt, stid: '<?= $stid ?>', indid: indid, cls: '<?= $cls2 ?>', sec: '<?= $sec2 ?>' },
            success: function (res) {
                statusIcon.innerHTML = '<i class="bi bi-cloud-check-fill text-success fs-5"></i>';
                // এখানে data-original আপডেট করার দরকার নেই, কারণ আমরা sessionInitialState ব্যবহার করছি
                checkGlobalDirty();
            }
        });
    }
</script>