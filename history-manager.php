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
        background-color: var(--m3-secondary-container);
        padding: 30px 20px 60px;
        border-radius: 0 0 32px 32px;
        text-align: center;
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
    .event-card {
        background: white;
        border-radius: 24px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #E7E0EC;
        transition: 0.3s;
        position: relative;
    }

    .event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(103, 80, 164, 0.08);
    }

    .cat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
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

<main class="pb-5">
    <div class="history-hero shadow-sm">
        <h3 class="fw-black m-0 text-dark">This Day in History</h3>
        <p class="small text-muted fw-bold mb-0">Discover important national & international events</p>

        <form id="dateFilterForm" class="date-pill">
            <i class="bi bi-calendar-check text-primary"></i>
            <input type="date" name="date" value="<?= $target_date ?>" class="border-0 fw-bold outline-none"
                onchange="this.form.submit()">
        </form>
    </div>

    <div class="container-fluid px-3" style="margin-top: -25px;">
        <?php
        $sql = "SELECT * FROM history WHERE day = $day AND month = $month ORDER BY priority DESC";
        $res = $conn->query($sql);

        if ($res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
                $icon = match ($row['category']) {
                    'Scientist' => 'bi-atom',
                    'Poet', 'Writer' => 'bi-pen-fill',
                    'Politicial' => 'bi-bank',
                    'Sports' => 'bi-trophy-fill',
                    'Singer', 'Artist' => 'bi-music-note-beamed',
                    default => 'bi-star-fill'
                };
                ?>
                <div class="event-card shadow-sm d-flex align-items-center gap-3">
                    <div class="cat-icon type-<?= $row['type'] ?>">
                        <i class="bi <?= $icon ?>"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="zone-badge"><?= $row['zone'] ?> • <?= $row['type'] ?></span>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical text-muted pointer" data-bs-toggle="dropdown"></i>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                                    <li><a class="dropdown-item fw-bold" onclick="editEvent(<?= $row['id'] ?>)"><i
                                                class="bi bi-pencil me-2"></i> Edit</a></li>
                                    <li><a class="dropdown-item fw-bold text-danger" onclick="deleteEvent(<?= $row['id'] ?>)"><i
                                                class="bi bi-trash me-2"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="fw-black text-dark text-truncate"><?= $row['category'] ?></div>
                        <p class="small text-muted mb-0"><?= $row['details'] ?></p>
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
        style="position:fixed; bottom:80px; right:30px; width:56px; height:56px; border-radius:16px; background:var(--m3-primary); color:white;"
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
        $.post('backend/get-history.php', { id }, function (res) {
            const d = JSON.parse(res);
            $('#e_id').val(d.id);
            $('#e_date').val(d.date);
            $('#e_category').val(d.category);
            $('#e_type').val(d.type);
            $('#e_zone').val(d.zone);
            $('#e_details').val(d.details);
            $('#e_priority').val(d.priority);
            $('#modalTitle').text('Edit Entry');
            eventModal.show();
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
</script>