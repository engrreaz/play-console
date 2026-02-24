<?php
include_once '../inc.light.php';

$sql = "SELECT * FROM slots WHERE sccode = '$sccode' ORDER BY id DESC";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $id = $row['id'];

        // মেরিট সিস্টেম লজিক
        $is_gpa = ($row['merit'] == 1);
        $merit_lbl = $is_gpa ? 'GPA System' : 'Total Marks';
        $merit_icon = $is_gpa ? 'bi-patch-check-fill' : 'bi-calculator-fill';
        $merit_clr = $is_gpa ? '#6750A4' : '#006A6A';

        // সময় ক্যালকুলেশন
        $time_range = date('h:i A', strtotime($row['reqin'])) . ' — ' . date('h:i A', strtotime($row['reqout']));

        echo '
        <div class="m3-elevated-card shadow-sm mb-3">
            <div class="card-body-wrapper">
                
                <div class="leading-visual">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>

                <div class="content-main">
                    <div class="slot-header">
                    <div class="flex-grow-1">
                    <span class="slot-name">' . htmlspecialchars($row['slotname']) . '</span>
                        <span class="id-tag">ID: ' . $id . '</span>
                    
                    </div>


                    <div class="action-bar">
                    <button class="m3-icon-btn edit" onclick="editSlot(' . $id . ')" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="m3-icon-btn delete" onclick="deleteSlot(' . $id . ')" title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                        
                    </div>
                    
                    <div class="meta-row">
                        <div class="m3-tonal-badge" style="--badge-clr: ' . $merit_clr . '">
                            <i class="bi ' . $merit_icon . '"></i>
                            <span>' . $merit_lbl . '</span>
                        </div>
                        <div class="m3-tonal-badge" style="--badge-clr: #49454F">
                            <i class="bi bi-person-bounding-box"></i>
                            <span>' . $row['parents'] . '</span>
                        </div>
                        <div class="m3-tonal-badge highlight">
                            <i class="bi bi-clock-fill"></i>
                            <span>' . $time_range . '</span>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>';
    }
} else {
    // Empty State আগের মতোই থাকবে
}
?>

<style>
    /* ১. কার্ডের মূল কন্টেইনার */
    .m3-elevated-card {
        background: #FFFFFF;
        border-radius: 20px;
        /* Squircle Shape */
        border: 1px solid #E7E0EC;
        transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        overflow: hidden;
    }

    .m3-elevated-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(103, 80, 164, 0.1) !important;
        border-color: #6750A4;
    }

    .card-body-wrapper {
        display: flex;
        align-items: center;
        padding: 16px;
        gap: 16px;
    }

    /* ২. লিডিং আইকন (Squircle) */
    .leading-visual {
        width: 52px;
        height: 52px;
        background: #F3EDF7;
        color: #6750A4;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* ৩. মেইন কন্টেন্ট এরিয়া */
    .content-main {
        flex-grow: 1;
        min-width: 0;
        /* Text truncate এর জন্য প্রয়োজনীয় */
    }

    .slot-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .slot-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1C1B1F;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .id-tag {
        font-size: 0.65rem;
        font-weight: 700;
        color: #79747E;
        background: #F4F4F4;
        padding: 1px 6px;
        border-radius: 4px;
    }

    /* ৪. মেটা চিপস / ব্যাজ */
    .meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .m3-tonal-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        background: #F7F2FA;
        color: var(--badge-clr);
    }

    .m3-tonal-badge.highlight {
        background: #E8F5E9;
        color: #2E7D32;
    }

    /* ৫. অ্যাকশন বাটন */
    .action-bar {
        display: flex;
        gap: 8px;
    }

    .m3-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: 0.2s;
        background: transparent;
    }

    .m3-icon-btn.edit {
        color: #6750A4;
    }

    .m3-icon-btn.delete {
        color: #B3261E;
    }

    .m3-icon-btn:hover {
        background: rgba(103, 80, 164, 0.08);
        transform: scale(1.1);
    }

    .m3-icon-btn.delete:hover {
        background: #FFF0F0;
    }
</style>