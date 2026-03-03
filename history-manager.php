<?php
$page_title = "History Events";
include 'inc.php'; // DB connection & Session

$target_date = $_GET['date'] ?? date('Y-m-d');
$day = date('d', strtotime($target_date));
$month = date('m', strtotime($target_date));
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-error-container: #F9DEDC;
    }

    body {
        background-color: var(--m3-surface);
        font-family: 'Inter', sans-serif;
    }

    /* Tonal Hero Area */
    .history-hero {
        background-color: var(--m3-primary);
        padding: 40px 20px 60px;
        /* নিচের দিকে প্যাডিং বাড়িয়ে দেওয়া হয়েছে */
        border-radius: 0 0 40px 40px;
        text-align: center;
        position: relative;
        /* পজিশন নিশ্চিত করা */
        z-index: 1;
        color: wheat;
    }

    .date-pill {
        background: white;
        border: 1.5px solid var(--m3-primary);
        border-radius: 100px;
        padding: 10px 20px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Event Cards */


    .event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(103, 80, 164, 0.08);
    }



    /* Type-based Colors */
    .type-Birth {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .type-Death {
        background: #FBE9E7;
        color: #D84315;
    }

    .type-Invention {
        background: #E3F2FD;
        color: #1565C0;
    }

    .type-Events {
        background: #F3EDF7;
        color: #6750A4;
    }

    .zone-badge {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }
</style>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-primary-container: #EADDFF;
        --m3-secondary-container: #F3EDF7;
        --m3-outline: #79747E;
    }

    /* Modal Main Container */
    .m3-modal-main {
        background-color: var(--m3-surface);
        border-radius: 28px !important;
        /* M3 Large Corner */
    }

    /* Icon Box for Header */
    .m3-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        /* Squircle */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--m3-primary-container);
        color: var(--m3-primary);
    }

    /* Tonal Input Container */
    .m3-input-box {
        background-color: var(--m3-secondary-container);
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid transparent;
        transition: 0.3s cubic-bezier(0.2, 0, 0, 1);
    }

    /* Focus State: Input becomes white with primary border */
    .m3-input-box:focus-within {
        background-color: #FFFFFF;
        border-color: var(--m3-primary);
        box-shadow: 0 0 0 1px var(--m3-primary);
    }

    /* Labels inside the box */
    .m3-field-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--m3-primary);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    /* Clean Inputs */
    .m3-field-input {
        width: 100%;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1C1B1F;
        outline: none;
        padding: 0;
    }

    /* Special for Textarea */
    textarea.m3-field-input {
        resize: none;
        padding-top: 4px;
    }

    /* M3 Pill Button */
    .btn-primary.rounded-pill {
        background-color: var(--m3-primary);
        border: none;
        font-weight: 900;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }

    .btn-primary.rounded-pill:hover {
        background-color: #4F378B;
        box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
    }
</style>

<style>
    /* আইকন এবং কার্ডের সৌন্দর্য বর্ধন */
    .cat-icon {
        width: 44px !important;
        height: 44px !important;
        min-width: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .event-card {
        background: #FFFFFF;
        border: 1px solid rgba(103, 80, 164, 0.1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        /* একটু বেশি শ্যাডো যাতে হিরো থেকে আলাদা দেখায় */
        border-radius: 16px;
        padding: 12px;
    }

    /* ড্রপডাউন অ্যারো লুকানো */
    .dropdown-toggle::after {
        display: none !important;
    }

    /* গ্রিপ আইকন হোভার ইফেক্ট */
    .drag-handle:hover {
        background-color: rgba(103, 80, 164, 0.05);
        border-radius: 8px;
    }
</style>

<style>
    .m3-drag-placeholder {
        background-color: var(--m3-secondary-container);
        border: 2px dashed var(--m3-primary);
        border-radius: 24px;
        height: 80px;
        margin-bottom: 12px;
        visibility: visible !important;
    }

    /* ড্র্যাগ করার সময় কার্সার চেঞ্জ */
    .drag-handle:active {
        cursor: grabbing !important;
    }

    #eventContainer {
        position: relative;
        z-index: 10;
        /* হিরোর চেয়ে বেশি জেনুইন ইনডেক্স */
        margin-top: -40px;
        /* হিরোর ভেতর যতটুকু ঢোকাতে চান */
        padding-top: 10px;
        /* প্রথম কার্ডটি যেন একদম লেগে না যায় */
    }
</style>

<main class="pb-5">
    <div class="history-hero shadow-sm">
        <h3 class="fw-bold m-0 ">This Day in History</h3>
        <p class="small text-white fw-normal mb-0">Discover important national & international events</p>

        <form id="dateFilterForm" class="date-pill">
            <i class="bi bi-calendar-check text-primary"></i>
            <input type="date" name="date" value="<?= $target_date ?>" class="border-0 fw-bold outline-none"
                onchange="this.form.submit()">
        </form>
    </div>

    <div class="container-fluid px-3" id="eventContainer" style="margin-top: -25px;">
        <?php
        $sql = "SELECT * FROM history WHERE day = $day AND month = $month  and (sccode=0 or sccode='$sccode') ORDER BY priority DESC";
        $res = $conn->query($sql);

        if ($res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
                $icon = match ($row['category']) {
                    'Scientist' => 'bi-claude',
                    'Poet', 'Writer' => 'bi-pen-fill',
                    'Politicial' => 'bi-bank',
                    'Sports' => 'bi-trophy-fill',
                    'Singer', 'Artist' => 'bi-music-note-beamed',
                    default => 'bi-star-fill'
                };

                $hidden = 'hidden';
                if ($row['sccode'] == 0 && $is_admin > 3 || $row['sccode'] == $sccode) {
                    $hidden = '';
                }
                if ($row['sccode'] != 0 && ($is_admin > 3 || $is_chief == 1)) {
                    // $hidden = '';
                }
                ?>
                <div class="event-card shadow-sm d-flex align-items-center gap-3 item mb-3" data-id="<?= $row['id'] ?>">

                    <div class="cat-icon type-<?= $row['type'] ?> shadow-xs">
                        <i class="bi <?= $icon ?>"></i>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">
                        <div class="zone-badge"><?= $row['zone'] ?> • <?= $row['type'] ?></div>
                        <div class="fw-black text-dark text-truncate" style="font-size: 0.95rem;"><?= $row['category'] ?></div>
                        <p class="small text-muted mb-0 text-truncate" style="font-size: 0.8rem;"><?= $row['details'] ?></p>
                    </div>

                    <div class="dropdown">
                        <div class="drag-handle dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false"
                            style="cursor: grab; color: #79747E;" <?= $hidden ?>>
                            <i class="bi bi-grip-vertical fs-3"></i>
                        </div>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-4" style="z-index:100;">
                            <li><a class="dropdown-item fw-bold" onclick="editEvent(<?= $row['id'] ?>)">
                                    <i class="bi bi-pencil me-2 text-primary"></i> Edit</a></li>
                            <li><a class="dropdown-item fw-bold text-danger" onclick="deleteEvent(<?= $row['id'] ?>)">
                                    <i class="bi bi-trash me-2"></i> Delete</a></li>
                        </ul>
                    </div>

                </div>
                <?php
            endwhile;
        else:
            echo '<div class="text-center py-5 opacity-50"><i class="bi bi-calendar-x display-1"></i><p class="fw-bold mt-2">No events found for this day.</p></div>';
        endif;
        ?>
    </div>

    <button class="m3-fab border-0 shadow-lg"
        style="position:fixed; bottom:80px; right:30px; width:56px; height:56px; border-radius:16px; background:var(--m3-primary); color:white; z-index:500;"
        onclick="openAddModal()">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</main>

<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 m3-modal-main shadow-lg">

            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="m3-icon-circle">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-black m-0 text-dark" id="modalTitle">History Entry</h5>
                        <p class="small text-muted fw-bold mb-0">National & International Records</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form id="eventForm" class="modal-body px-4 py-4">
                <input type="hidden" name="id" id="e_id" value="0">

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">EVENT DATE</label>
                    <input type="date" name="date" id="e_date" class="m3-field-input" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="m3-input-box">
                            <label class="m3-field-label">CATEGORY</label>
                            <select name="category" id="e_category" class="m3-field-input cursor-pointer">
                                <option value="Scientist">Scientist</option>
                                <option value="Poet">Poet</option>
                                <option value="Writer">Writer</option>
                                <option value="Politicial">Politician</option>
                                <option value="Sports">Sports</option>
                                <option value="Singer">Singer</option>
                                <option value="Artist">Artist</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="m3-input-box">
                            <label class="m3-field-label">TYPE</label>
                            <select name="type" id="e_type" class="m3-field-input cursor-pointer">
                                <option value="Birth">Birth</option>
                                <option value="Death">Death</option>
                                <option value="Invention">Invention</option>
                                <option value="Events">Events</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">ZONE / REACH</label>
                    <select name="zone" id="e_zone" class="m3-field-input cursor-pointer">
                        <option value="International">International</option>
                        <option value="National">National</option>
                        <option value="Local">Local</option>
                    </select>
                </div>

                <div class="m3-input-box mb-3">
                    <label class="m3-field-label">EVENT DETAILS</label>
                    <textarea name="details" id="e_details" class="m3-field-input" rows="3"
                        placeholder="Explain the significance..."></textarea>
                </div>

                <div class="m3-input-box mb-4">
                    <label class="m3-field-label">DISPLAY PRIORITY (0-99)</label>
                    <input type="number" name="priority" id="e_priority" class="m3-field-input" value="0">
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black shadow">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>SAVE HISTORY
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>


<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#eventContainer").sortable({
            handle: ".drag-handle", // শুধুমাত্র গ্রিপ আইকন দিয়ে ড্র্যাগ হবে
            placeholder: "m3-drag-placeholder", // ড্র্যাগ করার সময় খালি জায়গা দেখাবে
            update: function (event, ui) {
                saveHistoryOrder(); // ড্রপ করার সাথে সাথে সেভ হবে
            }
        });
    });

    function saveHistoryOrder() {
        // নতুন সিরিয়াল অনুযায়ী ID গুলোর অ্যারে তৈরি করা
        let order = [];
        $('.item').each(function (index) {
            // নতুন সিরিয়াল হবে (লিস্টের নিচের দিকে গেলে প্রায়োরিটি কমবে)
            // অথবা আপনি উল্টোটাও করতে পারেন (index + 1)
            order.push({
                id: $(this).data('id'),
                priority: 100 - index // ওপরের ইভেন্ট বেশি প্রায়োরিটি পাবে
            });
        });

        // AJAX এর মাধ্যমে ব্যাকএন্ডে পাঠানো
        $.ajax({
            url: 'backend/save-history-order.php',
            type: 'POST',
            data: { order: JSON.stringify(order) },
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Updated',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            dataType: 'json'
        });
    }
</script>

<script>
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

    function openAddModal() {
        $('#eventForm')[0].reset();
        $('#e_id').val(0);
        $('#e_date').val('<?= $target_date ?>');
        $('#modalTitle').text('New History Entry');
        eventModal.show();
    }

    function editEvent(id) {
        console.log("Fetching ID:", id); // ডিবাগিং এর জন্য

        $.post('backend/get-history.php', { id }, function (d) {
            // এখানে সরাসরি 'd' ব্যবহার করা হয়েছে, JSON.parse করার প্রয়োজন নেই 
            // কারণ আমরা শেষে 'json' টাইপ বলে দিয়েছি

            if (d) {
                $('#e_id').val(d.id);
                $('#e_date').val(d.date);
                $('#e_category').val(d.category);
                $('#e_type').val(d.type);
                $('#e_zone').val(d.zone);
                $('#e_details').val(d.details);
                $('#e_priority').val(d.priority);

                $('#modalTitle').text('Edit History Entry');

                // মডাল ওপেন করা
                eventModal.show();
            } else {
                console.error("No data found for ID:", id);
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Failed to fetch data. Please check console.");
        });
    }

    $('#eventForm').on('submit', function (e) {
        e.preventDefault();
        $.post('backend/save-history.php', $(this).serialize(), function (res) {
            if (res.status === 'success') location.reload();
        }, 'json');
    });



    function deleteEvent(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This historical event will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                // এখানে JSON ফরম্যাটে ডাটা পাঠানো হচ্ছে
                $.post('backend/save-history.php', { delete_id: id }, function (res) {
                    if (res.status === 'success') {
                        location.reload();
                    }
                }, 'json');
            }
        });
    }

    $(function () {
        $("#eventContainer").sortable({
            handle: ".drag-handle", // এটি নিশ্চিত করুন
            placeholder: "m3-drag-placeholder",
            update: function (event, ui) {
                saveHistoryOrder();
            }
        });
    });
</script>