<?php
$page_title = "Payment Setup";
include 'inc.php'; // আপনার মূল হেডার ফাইল

$slot = $_COOKIE['chain-slot'] ?? '';
$sessionyear_param = '%' . $_COOKIE['chain-session'] . '%';

?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-secondary-container: #F3EDF7;
        --m3-tertiary-container: #FFDDB3;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Inter', sans-serif;
    }

    /* M3 Tonal Hero Section */
    .setup-hero {
        background-color: var(--m3-secondary-container);
        padding: 12px;
        border-radius: 0 0 32px 32px;
        margin-bottom: -30px;
    }

    /* Tonal Dropdowns */
    .m3-select-box {
        background: white;
        border: 1px solid #E7E0EC;
        border-radius: 12px;
        padding: 8px 12px;
    }

    .m3-label-tiny {
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    /* Fee Item Card */
    .m3-item-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E7E0EC;
        margin-bottom: 12px;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    .m3-item-card.dragging {
        opacity: 0.4;
        transform: scale(0.95);
    }

    .m3-item-card:hover {
        border-color: var(--m3-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-tonal-header {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .drag-handle {
        color: #79747E;
        cursor: grab;
        font-size: 1.4rem;
        margin-right: 15px;
    }

    .amount-badge {
        background: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        padding: 6px 16px;
        border-radius: 100px;
        font-weight: 900;
        font-size: 0.9rem;
    }

    /* Drag Placeholder */
    .m3-drag-placeholder {
        height: 80px;
        background: var(--m3-secondary-container);
        border: 2px dashed var(--m3-primary);
        border-radius: 24px;
        margin-bottom: 12px;
    }

    /* FAB for Add New */
    .m3-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--m3-tertiary-container);
        color: #291800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        transition: 0.2s;
    }

    .m3-fab:hover {
        transform: scale(1.1);
        background: #FFCC91;
    }
</style>

<style>
    /* M3 Variables */
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-success-container: #C7EBD1;
    }

    .m3-modal-main {
        background-color: var(--m3-surface);
        border-radius: 28px !important;
    }

    /* Icon Box Squircle */
    .m3-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tonal Input Boxes */
    .m3-input-box {
        background-color: var(--m3-secondary-container);
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .m3-input-box:focus-within {
        background: #fff;
        border-color: var(--m3-primary);
        box-shadow: 0 0 0 1px var(--m3-primary);
    }

    .m3-field-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
        letter-spacing: 0.5px;
    }

    .m3-field-input {
        width: 100%;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
    }

    /* Switch Card */
    .m3-switch-card {
        background: #fff;
        border: 1px solid #E7E0EC;
        padding: 12px 16px;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Prominent Amount Box */
    .m3-amount-input-container {
        background: #fff;
        border: 2.5px solid var(--m3-primary);
        border-radius: 20px;
        padding: 15px;
        transition: 0.3s;
    }

    .m3-amount-field {
        width: 100%;
        border: none;
        text-align: center;
        font-size: 2.2rem;
        font-weight: 900;
        color: #1C1B1F;
        outline: none;
    }

    /* Buttons */
    .m3-btn-primary {
        background: var(--m3-primary);
        color: #fff;
        border-radius: 100px;
        font-weight: 800;
        border: none;
    }

    .m3-btn-tonal {
        background: var(--m3-secondary-container);
        color: var(--m3-primary);
        border-radius: 100px;
        font-weight: 800;
        border: none;
    }

    .m3-btn-primary:hover {
        opacity: 0.9;
    }
</style>

<main>
    <section class="setup-hero shadow-sm">


        <?php
        $chain_param = '-c 4 -t Choose Options -u -r -b View List -h class';
        include 'component/tree-ui.php';
        ?>

        <div class="text-center py-1" style="top:-60px;">
            <button class="btn btn-m3-tonal px-5 py-1 fw-bold" onclick="saveOrder()">
                <i class="bi bi-sort-numeric-down me-2"></i> UPDATE ORDER
            </button>
        </div>

    </section>

    <div class="container-fluid mt-5 px-3" id="itemlist">
        <?php
        // আপনার লজিক অনুযায়ী অ্যামাউন্ট এবং আইটেম লিস্ট ফেচ করা
        $sqlAmt = "SELECT itemcode, amount FROM financesetupvalue WHERE sccode='$sccode' AND sessionyear LIKE '%$sessionyear_param%' AND slot='$slot' AND classname='' AND sectionname=''";
        // echo $sqlAmt;
        $result = $conn->query($sqlAmt);
        $amounts = [];
        while ($row = $result->fetch_assoc()) {
            $amounts[$row['itemcode']] = $row['amount'];
        }

        $sql = "SELECT * FROM financesetup WHERE sccode='$sccode' AND sessionyear LIKE '$sessionyear_param' AND slot='$slot' ORDER BY slno";
        // echo $sql;
        $rs = $conn->query($sql);

        while ($r = $rs->fetch_assoc()):
            $itemcode = $r['itemcode'];
            $valAmount = $amounts[$itemcode] ?? 0;
            $id = $r['id'];
            ?>
            <div class="m3-item-card item" data-id="<?= $id ?>" draggable="true">
                <div class="card-tonal-header">
                    <div class="d-flex align-items-center flex-grow-1"
                        onclick="toggleItem(<?= $id ?>, '<?= $itemcode ?>', <?= $r['splitable'] ?? 0 ?>)">
                        <i class="bi bi-grip-vertical drag-handle" draggable="true"></i>
                        <div>
                            <div class="fw-black text-dark fs-6"><?= $r['particulareng'] ?></div>
                            <div class="small text-muted fw-bold"><?= $r['particularben'] ?></div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <div class="amount-badge shadow-sm pointer" data-bs-toggle="dropdown">
                            ৳ <?= number_format($valAmount, 2) ?>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                            <li><a class="dropdown-item fw-bold" onclick="openEdit(<?= $id ?>)"><i
                                        class="bi bi-pencil me-2 text-primary"></i> Edit Details</a></li>
                            <li><a class="dropdown-item fw-bold"
                                    onclick="openAmountModal(<?= $id ?>, '<?= $itemcode ?>', <?= $r['splitable'] ?? 0 ?>, '<?= $r['particulareng'] ?>')"><i
                                        class="bi bi-currency-dollar me-2 text-success"></i> Change Default Amount</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item fw-bold text-danger" onclick="delItem(<?= $id ?>)"><i
                                        class="bi bi-trash me-2"></i> Delete Item</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body border-top" id="itemBody<?= $id ?>"
                    style="display:none; background: #fafafa; border-radius: 0 0 24px 24px;"></div>
            </div>
        <?php endwhile; ?>
    </div>

    <button class="m3-fab border-0 mb-5" onclick="openAdd()">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</main>




<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-main shadow-lg border-0">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-circle bg-primary-container text-primary">
                        <i class="bi bi-plus-square-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-black m-0 text-dark" id="modal-title-item">Payment Item</h5>
                        <p class="small text-muted fw-bold mb-0">Set frequency and billing rules</p>
                    </div>
                </div>
                <button class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <input type="hidden" id="fid" value="0">

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">PARTICULAR (ENGLISH)</label>
                    <input type="text" id="peng" class="m3-field-input" placeholder="Monthly Tuition Fee">
                </div>

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">PARTICULAR (BANGLA)</label>
                    <input type="text" id="pben" class="m3-field-input" placeholder="মাসিক বেতন">
                </div>

                <div class="m3-input-box mb-4">
                    <label class="m3-field-label">BILLING FREQUENCY</label>
                    <select id="mon" class="m3-field-input cursor-pointer">
                        <option value="0">Every Month</option>
                        <optgroup label="Specific Months">
                            <option value="1">January</option>
                            <option value="7">July</option>
                        </optgroup>
                        <optgroup label="Installments">
                            <option value="33">Quarterly (3 Months)</option>
                            <option value="66">Half-Yearly</option>
                        </optgroup>
                    </select>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <div class="m3-switch-card">
                            <label class="small fw-bold m-0">New Only</label>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="new_only">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-switch-card">
                            <label class="small fw-bold m-0">Splitable</label>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="splitable">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="itemMsg" class="text-center small mt-2"></div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn m3-btn-tonal flex-fill py-3" data-bs-dismiss="modal">CANCEL</button>
                <button type="button" onclick="saveItem()" class="btn m3-btn-primary flex-fill py-3 shadow">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>SAVE ITEM
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="amountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m3-modal-main shadow-lg border-0" style="max-width: 420px; margin: auto;">
            <div class="modal-body p-4 text-center">
                <div class="m3-icon-circle bg-success-container text-success mx-auto mb-3"
                    style="width:72px; height:72px; border-radius:24px;">
                    <i class="bi bi-cash-coin fs-1"></i>
                </div>

                <h5 class="fw-black text-dark mb-1" id="set-amount-title">Set Fee Amount</h5>
                <p class="small text-muted fw-bold mb-4" id="ainfo">Loading class info...</p>

                <input type="hidden" id="afid">
                <input type="hidden" id="aitemcode">
                <input type="hidden" id="aclass">
                <input type="hidden" id="asection">
                <input type="hidden" id="splyn">

                <div class="m3-amount-input-container shadow-sm mb-3">
                    <label class="m3-field-label text-primary">PAYABLE AMOUNT (৳)</label>
                    <input type="number" id="aamount" class="m3-amount-field" step="0.01" placeholder="0.00"
                        onfocus="this.select()">
                </div>

                <div id="amountMsg" class="small fw-bold mb-3"></div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn m3-btn-tonal flex-fill py-3"
                        data-bs-dismiss="modal">DISCARD</button>
                    <button type="button" onclick="saveAmount()" class="btn m3-btn-primary flex-fill py-3 shadow">
                        CONFIRM ৳
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>


<script>
    // মডাল ইন্সট্যান্সগুলো ইনিশিয়েলাইজ করা
    const itemModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('itemModal'));
    const amountModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('amountModal'));



    // ২. আইটেম অ্যাড/এডিট ফাংশন
    function openAdd() {
        $('#fid').val(0);
        $('#modal-title-item').text('New Payment Item');
        $('#peng, #pben').val('');
        $('#mon').val(0);
        $('#new_only, #splitable').prop('checked', false);
        itemModal.show();
    }

    function openEdit(id) {
        $.post('payments/get-finance-item.php', { id }, function (res) {
            const d = JSON.parse(res);
            $('#fid').val(d.id);
            $('#modal-title-item').text('Edit: ' + d.particulareng);
            $('#peng').val(d.particulareng);
            $('#pben').val(d.particularben);
            $('#mon').val(d.month);
            $('#new_only').prop('checked', d.new_only == 1);
            $('#splitable').prop('checked', d.splitable == 1);
            itemModal.show();
        });
    }

    function saveItem() {
        const data = {
            id: $('#fid').val(),
            eng: $('#peng').val(),
            ben: $('#pben').val(),
            mon: $('#mon').val(),
            new_only: $('#new_only').is(':checked') ? 1 : 0,
            splitable: $('#splitable').is(':checked') ? 1 : 0
        };

        $.post('payments/save-finance-item.php', data, function (res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1200, showConfirmButton: false });
                setTimeout(() => location.reload(), 1300);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    }

    // ৩. ডাইনামিক ক্লাস/সেকশন লোড করা (Accordion)
    function toggleItem(id, itemcode, spl) {
        let box = $('#itemBody' + id);
        let session = $('#session-main').val(); // বর্তমান সেশন নিন
        let slot = $('#slot-main').val();       // বর্তমান স্লট নিন

        if (box.is(':visible')) { box.slideUp(200); return; }

        if (box.data('loaded') !== 1) {
            box.html('Loading...').slideDown(200);
            $.post('payments/load-item-classes.php', {
                fid: id,
                itemcode: itemcode,
                spl: spl,
                session: session, // সেশন পাঠানো হচ্ছে
                slot: slot        // স্লট পাঠানো হচ্ছে
            }, function (res) {
                box.html(res);
                box.data('loaded', 1);
            });
        } else {
            box.slideDown(200);
        }
    }

    // ৪. অ্যামাউন্ট সেটআপ মডাল
    function openAmountModal(fid, itemcode, splitable, itemText, cls = '', sec = '') {
        $('#afid').val(fid);
        $('#aitemcode').val(itemcode);
        $('#aclass').val(cls);
        $('#asection').val(sec);
        $('#splyn').val(splitable);
        $('#set-amount-title').text(itemText);

        let contextText = cls ? `Class: ${cls}` : 'Default Setting';
        if (sec) contextText += ` • Section: ${sec}`;
        $('#ainfo').text(contextText);

        $.post('payments/get-amount.php', { itemcode, class: cls, section: sec }, function (res) {
            $('#aamount').val(res.amount);
            amountModal.show();
            // ইনপুট বক্সটি অটো-ফোকাস এবং সিলেক্ট করা
            setTimeout(() => { $('#aamount').focus().select(); }, 400);
        }, 'json');
    }

    function saveAmount() {
        const data = {
            fitemcode: $('#aitemcode').val(),
            class: $('#aclass').val(),
            section: $('#asection').val(),
            amount: $('#aamount').val(),
            spl: $('#splyn').val()
        };

        $.post('payments/save-amount.php', data, function (res) {
            if (res.status === 'success') {
                amountModal.hide();
                Swal.fire({ icon: 'success', title: 'Amount Synced', timer: 800, showConfirmButton: false });
                // পেজ রিলোড না করে ডাইনামিক্যালি UI আপডেট করা যেতে পারে, তবে নির্ভুলতার জন্য রিলোড উত্তম
                setTimeout(() => location.reload(), 900);
            }
        }, 'json');
    }

    // ৫. ড্র্যাগ অ্যান্ড ড্রপ অর্ডার সেভ করা
    function saveOrder() {
        const order = [...document.querySelectorAll('.item')].map((el, i) => `${el.dataset.id}=${i + 1}`).join(',');
        $.post('payments/save-item-order.php', { order }, function (res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Order Updated', timer: 1000, showConfirmButton: false });
            }
        }, 'json');
    }

    function delItem(id) {
        Swal.fire({
            title: 'Delete Item?',
            text: "This will remove this fee category and its associated amounts!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('payments/delete-finance-item.php', { id }, () => location.reload());
            }
        });
    }
</script>


<script>
    // ===============================
    // Drag & Drop Sort System
    // ===============================

    const itemList = document.getElementById('itemlist');
    let draggedItem = null;

    // Drag Start
    itemList.addEventListener('dragstart', function (e) {
        if (!e.target.classList.contains('drag-handle')) return;
        draggedItem = e.target.closest('.item');
        draggedItem.classList.add('dragging');
    });

    // Drag End
    itemList.addEventListener('dragend', function (e) {
        const item = e.target.closest('.item');
        if (!item) return;

        item.classList.remove('dragging');
        draggedItem = null;
    });

    // Drag Over
    itemList.addEventListener('dragover', function (e) {
        e.preventDefault();

        const afterElement = getDragAfterElement(itemList, e.clientY);
        const currentItem = document.querySelector('.dragging');

        if (!currentItem) return;

        if (afterElement == null) {
            itemList.appendChild(currentItem);
        } else {
            itemList.insertBefore(currentItem, afterElement);
        }
    });

    // Helper: Position Detection
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
</script>