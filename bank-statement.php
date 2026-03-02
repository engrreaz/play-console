<?php
// bank-statement.php
$page_title = "Bank Statement";
include 'inc.php';

$accid = $_GET['id'];
$search = $_GET['search'] ?? '';

// ১. অ্যাকাউন্ট সামারি
$stmt = $conn->prepare("SELECT * FROM bankinfo WHERE id = ?");
$stmt->bind_param("i", $accid);
$stmt->execute();
$acc_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ২. ট্রানজেকশন ডাটা
$query = "SELECT * FROM banktrans WHERE accid = ? AND (particulareng LIKE ? OR transtype LIKE ?) ORDER BY date DESC, id DESC";
$search_term = "%$search%";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $accid, $search_term, $search_term);
$stmt->execute();
$transactions = $stmt->get_result();
?>

<main>
    <div class="p-3 bg-white shadow-sm d-flex align-items-center">
        <button onclick="history.back()" class="btn btn-link text-dark"><i class="bi bi-arrow-left"></i></button>
        <div class="ms-2">
            <div class="fw-bold"><?php echo $acc_info['bankname']; ?></div>
            <div class="small text-muted"><?php echo $acc_info['accno']; ?></div>
        </div>
    </div>

    <div class="pb-5">
        <?php while ($tr = $transactions->fetch_assoc()): 
            $t_type = strtolower($tr['transtype']);
            $class = ($t_type == 'deposit' || $t_type == 'interest') ? 'type-deposit' : 'type-withdraw';
        ?>
            <div class="trans-card <?php echo $class; ?> shadow-sm">
                <div class="d-flex justify-content-between">
                    <div class="small text-muted"><?php echo date('d M, Y', strtotime($tr['date'])); ?></div>
                    <div class="small fw-bold text-uppercase"><?php echo $tr['transtype']; ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <div>
                        <div class="fw-bold"><?php echo htmlspecialchars($tr['particulareng']); ?></div>
                        <div style="font-size:10px" class="text-muted">CHQ: <?php echo $tr['chqno']; ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">৳<?php echo number_format($tr['amount'], 2); ?></div>
                        <div style="font-size:10px" class="text-muted">Bal: ৳<?php echo number_format($tr['balance'], 2); ?></div>
                    </div>
                </div>
                <div class="text-end mt-2">
                    <button class="btn btn-sm btn-light py-0 text-danger" onclick="deleteTrans(<?php echo $tr['id']; ?>)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="fab shadow-lg" onclick="addTransaction()">
        <i class="bi bi-plus-lg fs-4"></i>
    </div>
</main>

<?php include 'footer.php'; ?>

<script>
    // ১. নতুন ট্রানজেকশন ইনপুট মোডাল
    function addTransaction() {
        Swal.fire({
            title: 'New Transaction',
            confirmButtonColor: '#6750A4',
            html: `
                <div class="text-start">
                    <label class="small fw-bold mb-1">Date</label>
                    <input type="date" id="t_date" class="form-control mb-3" value="<?php echo date('Y-m-d'); ?>">
                    
                    <label class="small fw-bold mb-1">Type</label>
                    <select id="t_type" class="form-select mb-3">
                        <option value="Deposit">Deposit (জমা)</option>
                        <option value="Withdraw">Withdraw (উত্তোলন)</option>
                        <option value="Interest">Interest (মুনাফা)</option>
                        <option value="Deduction">Deduction (চার্জ)</option>
                    </select>
                    
                    <label class="small fw-bold mb-1">Amount</label>
                    <input type="number" id="t_amount" class="form-control mb-3" placeholder="0.00">
                    
                    <label class="small fw-bold mb-1">Particulars</label>
                    <input type="text" id="t_desc" class="form-control mb-3" placeholder="Details of transaction">

                    <label class="small fw-bold mb-1">Cheque No (Optional)</label>
                    <input type="text" id="t_chq" class="form-control" placeholder="CHQ/Ref Number">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Confirm & Save',
            preConfirm: () => {
                const data = {
                    accid: '<?php echo $accid; ?>',
                    date: document.getElementById('t_date').value,
                    transtype: document.getElementById('t_type').value,
                    amount: document.getElementById('t_amount').value,
                    particulars: document.getElementById('t_desc').value,
                    chqno: document.getElementById('t_chq').value
                };
                if (!data.amount || data.amount <= 0) {
                    Swal.showValidationMessage('Please enter a valid amount');
                }
                return data;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveToBackend(result.value);
            }
        });
    }

    // ২. ডাটা সেভ করা
    function saveToBackend(data) {
        $.ajax({
            url: 'bank/save-bank-trans.php',
            type: 'POST',
            data: data,
            beforeSend: function() { Swal.showLoading(); },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }
        });
    }

    // ৩. ট্রানজেকশন ডিলিট করা
    function deleteTrans(id) {
        Swal.fire({
            title: 'Delete Transaction?',
            text: "Balance will be recalculated automatically.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('bank/delete-bank-trans.php', { id: id, accid: '<?php echo $accid; ?>' }, function(res) {
                    const data = JSON.parse(res);
                    if(data.status === 'success') location.reload();
                });
            }
        });
    }
</script>