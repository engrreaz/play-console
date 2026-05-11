<?php
$page_title = "Cashbook Manager";
include 'inc.php';
$msg = '';
/** ---------------- ১. ডেটা প্রসেসিং (CRUD) ---------------- **/

// এন্ট্রি সেভ বা আপডেট
if (isset($_POST['save_entry'])) {
    $id = $_POST['entry_id'] ?? '';
    $date = $_POST['date'];
    $partid = $_POST['partid']; // sub_head_id
    $head_id = $_POST['head_code'];
    $particulars = mysqli_real_escape_string($conn, $_POST['particulars']);
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $memono = (int) $_POST['memono'] ?? '';
    $month = date('n', strtotime($date));
    $year = date('Y', strtotime($date));
    $entryby = $usr;

    // আপনার লজিক: নতুন সেভ করা ডেটা সবসময় পেন্ডিং হিসেবে থাকবে (sccode * 10)
    $pending_sccode = $sccode * 10;

    if (!empty($id)) {
        // আপডেট (আপডেটের সময় sccode পরিবর্তন করছি না, যাতে স্ট্যাটাস নষ্ট না হয়)
        $sql = "UPDATE cashbook SET date='$date', account_head='$head_id', partid='$partid', particulars='$particulars', amount='$amount', type='$type', memono='$memono', month='$month', year='$year' WHERE id='$id'";
    } else {
        // ইনসার্ট (পেন্ডিং হিসেবে সেভ হচ্ছে)
        $sql = "INSERT INTO cashbook (sccode, date, account_head, partid, particulars, amount, type, memono, month, year, entryby, entrytime, sessionyear) 
                VALUES ('$pending_sccode', '$date', '$head_id', '$partid', '$particulars', '$amount', '$type', '$memono', '$month', '$year', '$usr', '$cur', '$sessionyear')";
    }


    if ($conn->query($sql)) {
        $msg = "Record Updated";
    }
}

/** ---------------- ২. ডেটা লোড ---------------- **/

$oneEightyDaysAgo = date('Y-m-d', strtotime('-180 days'));
$janFirst = date('Y-01-01');
$datefrom = ($oneEightyDaysAgo < $janFirst) ? $oneEightyDaysAgo : $janFirst;
$dateto = date('Y-m-t');

// ড্রপডাউনের জন্য সাব-হেড
$sub_heads = $conn->query("SELECT s.id, s.sub_head, h.account_head, s.account_head_id
                           FROM account_sub_head s 
                           LEFT JOIN account_head h ON s.account_head_id = h.id 
                           WHERE s.sccode='$sccode' ORDER BY h.account_head, s.sub_head");

// সাব-হেড ডেটা লোড করার পর এই অংশটুকু যোগ করুন
$sub_heads_map = [];
$sub_heads->data_seek(0); // পয়েন্টার শুরুতে নেওয়া
while ($sh = $sub_heads->fetch_assoc()) {
    $sub_heads_map[$sh['id']] = [
        'sub_name' => $sh['sub_head'],
        'main_name' => $sh['account_head']
    ];
}


// ক্যাশবুক ডেটা লোড
$sql_main = "SELECT c.*, s.sub_head as head_name FROM cashbook c 
             LEFT JOIN account_sub_head s ON c.partid = s.id 
             WHERE (c.sccode='$sccode' OR c.sccode='" . ($sccode * 10) . "') 
             AND c.date BETWEEN '$datefrom' AND '$dateto' 
             ORDER BY c.memono DESC, c.date DESC, c.id DESC";
$res_main = $conn->query($sql_main);

$approved = [];
$pending = [];
$total_in = 0;
$total_ex = 0;

while ($row = $res_main->fetch_assoc()) {
    // লজিক ফিক্স: sccode (ছোট) হলে Approved, sccode*10 (বড়) হলে Pending
    if ($row['sccode'] == $sccode) {
        $approved[] = $row;
        if ($row['type'] == 'Income')
            $total_in += $row['amount'];
        else
            $total_ex += $row['amount'];
    } else if ($row['sccode'] == ($sccode * 10)) {
        $pending[] = $row;
    }
}
?>

<style>
    /* M3 Styles (সংক্ষিপ্ত রাখা হয়েছে) */
    .m3-tab-bar {
        background: #fff;
        padding: 10px;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .filter-chip-group {
        display: flex;
        gap: 8px;
        padding: 15px 12px;
        overflow-x: auto;
    }

    .chip {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 0.75rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .chip.active {
        background: #e3f2fd;
        color: #1976d2;
        border-color: #1976d2;
    }

    .v-card {
        padding: 15px;
        margin: 0 12px 12px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    .Income {
        border-left: 5px solid #2E7D32;
    }

    .Expenditure {
        border-left: 5px solid #B3261E;
    }

    .m3-fab {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #1976d2;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border: none;
        z-index: 1000;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .income-text {
        color: #2E7D32;
    }

    .expense-text {
        color: #B3261E;
    }
</style>

<main>
    <div class="hero-container" style="background: #1976d2; color: white; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div style="font-size: 1.6rem; font-weight: 900;">
                    ৳<?php echo number_format($total_in - $total_ex, 2); ?></div>
                <div style="font-size: 0.7rem; opacity: 0.9;">CASH IN HAND</div>
            </div>
            <div class="text-end">
                <div class="small fw-bold">In: +<?php echo number_format($total_in); ?></div>
                <div class="small fw-bold">Out: -<?php echo number_format($total_ex); ?></div>
            </div>
        </div>
    </div>

    <?php
    if (strlen($msg > 0)) {
        ?>
        <div class="alert alert-info text-center mx-3"><?= $msg ?></div>
        <?php
    }
    ?>

    <div class="m3-tab-bar">
        <ul class="nav nav-pills nav-justified" id="cb-tabs">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                    data-bs-target="#approved-list">SANCTIONED</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending-list">PENDING
                    (<?php echo count($pending); ?>)</button></li>
        </ul>
    </div>

    <div class="tab-content pb-5 mt-3">
        <!-- Sanctioned List -->
        <div class="tab-pane fade show active" id="approved-list">
            <div class="filter-chip-group">
                <div class="chip active" onclick="filterData('all', 'approved-list')">All Items</div>
                <div class="chip" onclick="filterData('Income', 'approved-list')">Incomes</div>
                <div class="chip" onclick="filterData('Expenditure', 'approved-list')">Expenditures</div>
            </div>
            <div class="list-container">
                <?php foreach ($approved as $v): ?>
                    <div class="v-card shadow-sm <?php echo $v['type']; ?>">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold"><?php echo $v['particulars']; ?></div>
                                <div class="small text-primary"><?php echo $v['head_name']; ?></div>
                                <div class="small text-muted"><?php echo date('d M, y', strtotime($v['date'])); ?> | Memo:
                                    <?php echo $v['memono']; ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div
                                    class="fw-bold <?php echo ($v['type'] == 'Income' ? 'income-text' : 'expense-text'); ?>">
                                    ৳<?php echo number_format($v['amount']); ?></div>
                                <div class="mt-2">
                                    <i class="bi bi-pencil-square text-primary me-2"
                                        onclick='editEntry(<?php echo json_encode($v); ?>)'></i>
                                    <i class="bi bi-trash3 text-danger" onclick="processVoucher(<?php echo $v['id']; ?>, 1)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pending List -->
        <div class="tab-pane fade" id="pending-list">
            <?php foreach ($pending as $v): ?>
                <div class="v-card shadow-sm <?php echo $v['type']; ?>" style="background: #FFF9C4;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?php echo $v['particulars']; ?></div>
                            <div class="fw-tiny" style="font-size: 0.7rem; color: #666;">
                                <?php
                                $id = $v['partid'];
                                if (isset($sub_heads_map[$id])) {
                                    echo $sub_heads_map[$id]['main_name'] . ' | ' . $sub_heads_map[$id]['sub_name'];
                                } else {
                                    echo "Unknown Head | ID: " . $id;
                                }
                                ?>
                            </div>
                            <div class="small text-muted">By: <?php echo $v['entryby']; ?> |
                                ৳<?php echo number_format($v['amount']); ?></div>
                        </div>
                        <div class="d-flex gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-4"
                                onclick="processVoucher(<?php echo $v['id']; ?>, 2)"></i>
                            <i class="bi bi-x-circle-fill text-danger fs-4"
                                onclick="processVoucher(<?php echo $v['id']; ?>, 1)"></i>
                            <i class="bi bi-pencil-square text-primary fs-4"
                                onclick='editEntry(<?php echo json_encode($v); ?>)'></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <button class="m3-fab" onclick="addEntry()"><i class="bi bi-plus-lg"></i></button>
</main>

<!-- Modal and Scripts (নিচে আগের মতোই থাকবে) -->
<div class="modal fade" id="entryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <h5 id="modalTitle" class="mb-4">Add Transaction</h5>
            <form method="post" id="entryForm">
                <input type="hidden" name="entry_id" id="entry_id">
                <input type="hidden" name="head_code" id="head_code">
                <div class="row g-2">
                    <div class="col-6 mb-3">
                        <label class="small">Date</label>
                        <input type="date" name="date" id="e_date" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small">Type</label>
                        <select name="type" id="e_type" class="form-control" required>
                            <option value="Income">Income</option>
                            <option value="Expenditure">Expenditure</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small">Account Sector</label>
                    <select name="partid" id="e_partid" class="form-control" required>
                        <option value="">Select Sector</option>
                        <?php
                        $sub_heads->data_seek(0); // লুপটি পুনরায় শুরু করার জন্য
                        $current_head = '';
                        while ($sh = $sub_heads->fetch_assoc()):
                            if ($current_head != $sh['account_head']) {
                                if ($current_head != '')
                                    echo '</optgroup>';
                                echo '<optgroup label="' . $sh['account_head'] . '">';
                                $current_head = $sh['account_head'];
                            }
                            ?>
                            <option value="<?php echo $sh['id']; ?>" data-head="<?php echo $sh['account_head_id']; ?>">
                                <?php echo $sh['sub_head']; ?>
                            </option>
                        <?php endwhile;
                        if ($current_head != '')
                            echo '</optgroup>';
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small">Description</label>
                    <input type="text" name="particulars" id="e_particulars" class="form-control" required>
                </div>
                <div class="row g-2">
                    <div class="col-6 mb-3">
                        <label class="small">Amount</label>
                        <input type="number" name="amount" id="e_amount" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small">Memo</label>
                        <input type="text" name="memono" id="e_memono" class="form-control">
                    </div>
                </div>
                <button type="submit" name="save_entry" class="btn btn-primary w-100 mt-3">SAVE RECORD</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
    const entryModal = new bootstrap.Modal('#entryModal');

    function filterData(type, tabId) {
        const tab = document.getElementById(tabId);
        const cards = tab.querySelectorAll('.v-card');
        tab.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
        cards.forEach(card => {
            card.style.display = (type === 'all' || card.classList.contains(type)) ? 'block' : 'none';
        });
    }



    function processVoucher(id, tail) {
        let conf = (tail === 2) ? "Sanction this voucher?" : "Delete this voucher?";
        let btn = (tail === 2) ? "#2E7D32" : "#B3261E";

        Swal.fire({
            title: conf,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: btn,
            confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("delcashbook.php", { sccode: '<?php echo $sccode; ?>', id: id, tail: tail }, function () {
                    location.reload();
                });
            }
        });
    }
</script>

<script>
    // ১. অ্যাকাউন্ট হেড অটো সেট করার লজিক
    document.getElementById('e_partid').addEventListener('change', function () {
        // সিলেক্টেড অপশন থেকে 'data-head' এট্রিবিউট নেওয়া
        let selectedOption = this.options[this.selectedIndex];
        let headCode = selectedOption.getAttribute('data-head');
        document.getElementById('head_code').value = headCode;
    });

    function addEntry() {
        document.getElementById('entryForm').reset();
        document.getElementById('entry_id').value = "";
        document.getElementById('modalTitle').innerText = "New Voucher";

        // আজকের তারিখ ডিফল্ট সেট করা
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('e_date').value = today;

        // ডিফল্ট টাইপ এক্সপেন্ডিচার সেট করা
        document.getElementById('e_type').value = 'Expenditure';

        entryModal.show();
    }

    function editEntry(data) {
        document.getElementById('modalTitle').innerText = "Edit Voucher";
        document.getElementById('entry_id').value = data.id;
        document.getElementById('e_date').value = data.date;
        document.getElementById('e_particulars').value = data.particulars;
        document.getElementById('e_amount').value = data.amount;
        document.getElementById('e_type').value = data.type;
        document.getElementById('e_memono').value = data.memono;
        document.getElementById('e_partid').value = data.partid; // সাব-হেড সেট

        // এডিটের সময় অ্যাকাউন্ট হেড (head_code) সেট করা নিশ্চিত করা
        document.getElementById('head_code').value = data.account_head;

        entryModal.show();
    }
</script>