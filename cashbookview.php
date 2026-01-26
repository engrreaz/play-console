<?php
$page_title = "Cashbook Manager";
include 'inc.php';

/** ---------------- ১. ডেটা প্রসেসিং (CRUD) ---------------- **/
if (isset($_COOKIE['form-submitted']) && $_COOKIE['form-submitted'] == "true") {
    $form_submitted = true;
} else {
    $form_submitted = false;
}


// ১.১ এন্ট্রি সেভ/আপডেট লজিক
if (isset($_POST['save_entry']) && $form_submitted) {
    $id = $_POST['entry_id'];
    $date = $_POST['date'];
    $partid = $_POST['partid']; // sub_head_id
    $head_id = $_POST['head_code'];
    $particulars = mysqli_real_escape_string($conn, $_POST['particulars']);
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $memono = $_POST['memono'] ?? '';
    $month = $_POST['month'] ?? date('n');
    $year = $_POST['year'] ?? date('Y');
    $entryby = $usr;
    $new_sccode = $sccode * 10; // সরাসরি স্যানকশনড হিসেবে সেভ হবে

    if (!empty($id)) {
        // Update
        $sql = "UPDATE cashbook SET date='$date', account_head='$head_id', partid='$partid', particulars='$particulars', amount='$amount', type='$type', memono='$memono', month='$month', year='$year' WHERE id='$id'";
        $conn->query($sql);
    } else {
        // Insert (সরাসরি স্যানকশনড হিসেবে সেভ হবে)
        $sql = "INSERT INTO cashbook (sccode, date, account_head, partid, particulars, amount, type, memono, month, year, entryby, entrytime, sessionyear) 
                      VALUES ('$new_sccode', '$date', '$head_id', '$partid', '$particulars', '$amount', '$type', '$memono', '$month', '$year', '$usr', '$cur', '$sessionyear')";
        $conn->query($sql);
    }
    // echo $sql;
    // header("Location: accounts-cashbook-advanced.php"); exit();
}

/** ---------------- ২. ডেটা লোড ---------------- **/

// সাব-হেড লিস্ট (ড্রপডাউনের জন্য) - হেড নামসহ জয়েন করা হয়েছে
$sub_heads = $conn->query("SELECT s.id, s.sub_head, h.account_head ,  s.account_head_id
                           FROM account_sub_head s 
                           LEFT JOIN account_head h ON s.account_head_id = h.id 
                           WHERE s.sccode='$sccode' ORDER BY h.account_head, s.sub_head");

// ক্যাশবুক ডেটা লোড
$datefrom = date('2025-01-01');
$dateto = date('Y-m-t');
$sql_main = "SELECT c.*, s.sub_head as head_name FROM cashbook c 
             LEFT JOIN account_sub_head s ON c.partid = s.id 
             WHERE (c.sccode='$sccode' OR c.sccode='" . ($sccode * 10) . "') 
             AND c.date BETWEEN '$datefrom' AND '$dateto' 
             ORDER BY c.date DESC, c.id DESC";
$res_main = $conn->query($sql_main);

$approved = [];
$pending = [];
$total_in = 0;
$total_ex = 0;

while ($row = $res_main->fetch_assoc()) {
    if ($row['sccode'] == $sccode) {
        $approved[] = $row;
        if ($row['type'] == 'Income')
            $total_in += $row['amount'];
        else
            $total_ex += $row['amount'];
    } else {
        $pending[] = $row;
    }
}
?>

<style>
    /* M3 Custom Styles */
    .m3-tab-bar {
        background: #fff;
        padding: 10px;
        border-radius: 0 0 20px 20px;
        box-shadow: var(--m3-shadow-sm);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .filter-chip-group {
        display: flex;
        gap: 8px;
        padding: 15px 12px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .chip {
        background: #fff;
        border: 1px solid var(--m3-outline);
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #444;
        cursor: pointer;
        white-space: nowrap;
    }

    .chip.active {
        background: var(--m3-tonal-container);
        color: var(--m3-primary);
        border-color: var(--m3-primary);
    }

    .v-card {
        padding: 15px;
        margin: 0 12px 12px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.04);
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
        background: var(--m3-primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: var(--m3-shadow-lg);
        border: none;
        z-index: 1000;
    }
</style>

<main>
    <div class="hero-container" style="padding-bottom: 40px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div style="font-size: 1.6rem; font-weight: 950;">
                    ৳<?php echo number_format($total_in - $total_ex, 2); ?></div>
                <div style="font-size: 0.7rem; opacity: 0.8; font-weight: 800; letter-spacing: 1px;">CASH IN HAND</div>
            </div>
            <div style="text-align: right;">
                <div class="small fw-bold text-success">In: +<?php echo number_format($total_in); ?></div>
                <div class="small fw-bold text-danger">Out: -<?php echo number_format($total_ex); ?></div>
            </div>
        </div>
    </div>

    <div class="m3-tab-bar">
        <ul class="nav nav-pills nav-justified" id="cb-tabs">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                    data-bs-target="#approved-list"><i class="bi bi-shield-check me-1"></i> SANCTIONED</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending-list"><i
                        class="bi bi-clock-history me-1"></i> PENDING (<?php echo count($pending); ?>)</button></li>
        </ul>
    </div>

    <div class="tab-content pb-5">
        <div class="tab-pane fade show active" id="approved-list">
            <div class="filter-chip-group">
                <div class="chip active" onclick="filterData('all', 'approved-list')">All Items</div>
                <div class="chip" onclick="filterData('Income', 'approved-list')">Incomes</div>
                <div class="chip" onclick="filterData('Expenditure', 'approved-list')">Expenditures</div>
            </div>

            <div class="list-container">
                <?php foreach ($approved as $v): ?>
                    <div class="m3-list-item v-card shadow-sm <?php echo $v['type']; ?>">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex gap-3">
                                <div class="icon-box <?php echo ($v['type'] == 'Income' ? 'c-inst' : 'c-exit'); ?>"
                                    style="width: 44px; height: 44px;">
                                    <i
                                        class="bi <?php echo ($v['type'] == 'Income' ? 'bi-arrow-down-left' : 'bi-arrow-up-right'); ?>"></i>
                                </div>
                                <div>
                                    <div class="st-title" style="font-size: 0.95rem; font-weight: 850;">
                                        <?php echo $v['particulars']; ?>
                                    </div>
                                    <div class="st-desc"
                                        style="font-size: 0.75rem; font-weight: 600; color:var(--m3-primary);">
                                        <?php echo $v['head_name']; ?>
                                    </div>
                                    <div class="small text-muted mt-1"><i
                                            class="bi bi-calendar3 me-1"></i><?php echo date('d M, y', strtotime($v['date'])); ?>
                                        | Memo: <?php echo $v['memono'] ?: 'N/A'; ?></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div style="font-weight:900;"
                                    class="<?php echo $v['type'] == 'Income' ? 'income-text' : 'expense-text'; ?>">
                                    ৳<?php echo number_format($v['amount']); ?></div>
                                <div class="d-flex gap-2 mt-2 justify-content-end">
                                    <i class="bi bi-pencil-square text-primary fs-5"
                                        onclick='editEntry(<?php echo json_encode($v); ?>)'></i>
                                    <i class="bi bi-trash3 text-danger fs-5"
                                        onclick="deleteEntry(<?php echo $v['id']; ?>)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="pending-list">
            <div class="filter-chip-group">
                <div class="chip active" onclick="filterData('all', 'pending-list')">All Pending</div>
                <div class="chip" onclick="filterData('Income', 'pending-list')">Incomes</div>
                <div class="chip" onclick="filterData('Expenditure', 'pending-list')">Expenditures</div>
            </div>

            <?php foreach ($pending as $v): ?>
                <div class="m3-list-item v-card shadow-sm <?php echo $v['type']; ?>" style="background: #FFF9C4;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <div class="fw-bold"><?php echo $v['particulars']; ?></div>
                            | by : <?php echo $v['entryby']; ?>
                        </div>
                    </div>

                    <div>
                        <div class="fs-3 text-muted text-right fw-bold">৳<?php echo $v['amount']; ?></div>

                        <div class="d-flex gap-3">
                            <i class="bi bi-pencil-square text-primary fs-5"
                                onclick='editEntry(<?php echo json_encode($v); ?>)'></i>
                            <i class="bi bi-check-circle-fill text-success fs-5"
                                onclick="processVoucher(<?php echo $v['id']; ?>, 2)"></i>
                            <i class="bi bi-x-circle text-danger fs-5"
                                onclick="processVoucher(<?php echo $v['id']; ?>, 1)"></i>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>

    <button class="m3-fab shadow-lg" onclick="addEntry()"><i class="bi bi-plus-lg"></i></button>
</main>



<div class="modal fade" id="entryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-content border-0 shadow-lg" style="width:90vw; margin:auto; padding:20px;">
            <h5 class="fw-bold mb-4" id="modalTitle" style="color: var(--m3-primary);">Add Transaction</h5>
            <form method="post" id="entryForm">
                <input type="hidden" name="entry_id" id="entry_id">
                <input type="hidden" name="head_code" id="head_code">

                <div class="row g-2">
                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-calendar-event m3-field-icon"></i>
                            <input type="date" name="date" id="e_date" class="m3-input-floating"
                                value="<?php echo date('Y-m-d'); ?>" required>
                            <label class="m3-floating-label">DATE</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-arrow-left-right m3-field-icon"></i>
                            <select name="type" id="e_type" class="m3-select-floating" required>
                                <option value="Income">Income</option>
                                <option value="Expenditure">Expenditure</option>
                            </select>
                            <label class="m3-floating-label">TYPE</label>
                        </div>
                    </div>
                </div>

                <div class="m3-floating-group">
                    <i class="bi bi-tags m3-field-icon"></i>
                    <select name="partid" id="e_partid" class="m3-select-floating" required>
                        <option value=""></option>

                        <?php
                        $sub_heads->data_seek(0);

                        $current_head = '';

                        while ($sh = $sub_heads->fetch_assoc()):

                            // নতুন group শুরু
                            if ($current_head != $sh['account_head']) {

                                // আগের group close
                                if ($current_head != '') {
                                    echo '</optgroup>';
                                }

                                echo '<optgroup label="' . $sh['account_head'] . '">';

                                $current_head = $sh['account_head'];
                            }
                            ?>

                            <option value="<?php echo $sh['id']; ?>" data-head="<?php echo $sh['account_head_id']; ?>">
                                <?php echo $sh['sub_head']; ?>
                            </option>

                        <?php endwhile;

                        // শেষ group close
                        if ($current_head != '') {
                            echo '</optgroup>';
                        }
                        ?>
                    </select>

                    <label class="m3-floating-label">ACCOUNT SECTOR (SUB-HEAD)</label>
                </div>

                <div class="m3-floating-group">
                    <i class="bi bi-pencil m3-field-icon"></i>
                    <input type="text" name="particulars" id="e_particulars" class="m3-input-floating" placeholder=" "
                        required>
                    <label class="m3-floating-label">DESCRIPTION</label>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-cash-stack m3-field-icon"></i>
                            <input type="number" name="amount" id="e_amount" class="m3-input-floating" placeholder=" "
                                required>
                            <label class="m3-floating-label">AMOUNT</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-floating-group">
                            <i class="bi bi-hash m3-field-icon"></i>
                            <input type="text" name="memono" id="e_memono" class="m3-input-floating" placeholder=" ">
                            <label class="m3-floating-label">MEMO NO.</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-light flex-fill py-2"
                        style="border-radius:12px; font-weight:700;" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" name="save_entry" class="btn btn-primary flex-fill py-2"
                        style="border-radius:12px; font-weight:700;">SAVE RECORD</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const entryModal = new bootstrap.Modal('#entryModal');

    // ১. ফিল্টারিং লজিক
    function filterData(type, tabId) {
        const tab = document.getElementById(tabId);
        const chips = tab.querySelectorAll('.chip');
        const cards = tab.querySelectorAll('.v-card');

        chips.forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');

        cards.forEach(card => {
            if (type === 'all' || card.classList.contains(type)) card.style.display = 'block';
            else card.style.display = 'none';
        });
    }

    // ২. অ্যাড/এডিট ফাংশন
    function addEntry() {
        document.getElementById('modalTitle').innerText = "New Voucher";
        document.getElementById('entry_id').value = "";
        document.getElementById('entryForm').reset();
        document.getElementById('e_type').value = 'Expenditure';

        $('#e_type').trigger('change');

        entryModal.show();

    }

    function editEntry(data) {
        document.getElementById('modalTitle').innerText = "Edit Voucher";
        document.getElementById('entry_id').value = data.id;
        document.getElementById('e_date').value = data.date;
        document.getElementById('e_partid').value = data.partid;
        document.getElementById('e_particulars').value = data.particulars;
        document.getElementById('e_amount').value = data.amount;
        document.getElementById('e_type').value = data.type;
        document.getElementById('e_memono').value = data.memono;
        $('#e_type').trigger('change');
        entryModal.show();
    }

    // ৩. ডিলিট এবং অ্যাপ্রুভ
    function deleteEntry(id) {
        Swal.fire({ title: 'Delete Item?', text: "This will remove the transaction forever!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E', confirmButtonText: 'Yes, Delete' })
            .then((result) => { if (result.isConfirmed) { window.location.href = "accounts-cashbook-advanced.php?del_id=" + id; } });
    }

    function processVoucher(id, tail) {
        $.post("delcashbook.php", { sccode: '<?php echo $sccode; ?>', id: id, tail: tail }, function () { setCookie("form-submitted", "true", 1); window.location.href = "cashbookview.php"; });
    }
</script>

<script>
    document.getElementById('e_type').addEventListener('change', function () {

        let type = this.value;

        $.ajax({
            url: 'ajax/ajax-get-subhead.php',
            type: 'POST',
            data: { type: type },
            success: function (data) {
                $('#e_partid').html(data);
            }
        });

    });


    $('#e_partid').on('change', function () {

        let opt = this.options[this.selectedIndex];

        $('#head_code').val(opt.getAttribute('data-head'));

    });

</script>