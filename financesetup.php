<?php 
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে

// ১. ডাটা প্রিপারেশন (Prepared Statements)
$sy_param = $sy;
$stmt_cls = $conn->prepare("SELECT areaname FROM areas WHERE user = ? AND sessionyear = ? ORDER BY idno, id");
$stmt_cls->bind_param("ss", $rootuser, $sy_param);
$stmt_cls->execute();
$res_cls = $stmt_cls->get_result();

$active_classes = [];
while($row = $res_cls->fetch_assoc()) {
    $active_classes[] = $row['areaname'];
}
$stmt_cls->close();

// মোট ছাত্র সংখ্যা ও ক্লাসের পরিসংখ্যান
$class_count = count($active_classes);
$total_students = 0;
$stmt_st_cnt = $conn->prepare("SELECT COUNT(*) FROM sessioninfo WHERE sccode = ? AND sessionyear LIKE ? AND status = '1'");
$sy_like = "%$sy%";
$stmt_st_cnt->bind_param("ss", $sccode, $sy_like);
$stmt_st_cnt->execute();
$stmt_st_cnt->bind_result($total_students);
$stmt_st_cnt->fetch();
$stmt_st_cnt->close();
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface */

    /* Standard M3 Top App Bar */
    .m3-app-bar {
        background-color: #FFFFFF;
        height: 64px;
        display: flex; align-items: center;
        padding: 0 16px;
        position: sticky; top: 0; z-index: 1050;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 20px 20px;
    }
    .m3-app-bar .back-btn { color: #1C1B1F; font-size: 1.5rem; margin-right: 16px; text-decoration: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .m3-app-bar .page-title { font-size: 1.2rem; font-weight: 600; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Summary Stats Card */
    .hero-stats {
        background: #F3EDF7; border-radius: 28px;
        padding: 20px; margin: 15px;
        display: flex; justify-content: space-around; text-align: center;
    }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: #6750A4; display: block; }
    .stat-lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #49454F; }

    /* M3 Section Card */
    .m3-card {
        background: white; border-radius: 24px;
        padding: 16px; margin: 0 15px 15px; border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Table Styling */
    .table-container { overflow-x: auto; border-radius: 16px; background: white; }
    .m3-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .m3-table th { background: #EADDFF; color: #21005D; padding: 12px; text-align: center; font-weight: 700; }
    .m3-table td { padding: 12px; border-bottom: 1px solid #E7E0EC; vertical-align: middle; }
    .m3-table .particular-cell { min-width: 150px; text-align: left; }
    .m3-table .amt-cell { text-align: right; font-weight: 600; color: #6750A4; }

    .btn-pill { border-radius: 100px; padding: 10px 24px; font-weight: 700; border: none; transition: 0.2s; }
    .btn-pill:active { transform: scale(0.96); }

    /* Checkbox Styling */
    .m3-checkbox-list { max-height: 300px; overflow-y: auto; padding: 10px; }
    .checkbox-item { display: flex; align-items: center; padding: 12px; border-radius: 12px; margin-bottom: 5px; background: #F7F2FA; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="settings_admin.php" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <h1 class="page-title">Finance Setup</h1>
    <div class="ms-auto"><i class="bi bi-gear-fill text-muted"></i></div>
</header>

<main class="pb-5">
    <div class="hero-stats shadow-sm">
        <div>
            <span class="stat-val"><?php echo $class_count; ?></span>
            <span class="stat-lbl">Active Classes</span>
        </div>
        <div class="vr mx-3 opacity-25"></div>
        <div>
            <span class="stat-val"><?php echo $total_students; ?></span>
            <span class="stat-lbl">Students (<?php echo $sy; ?>)</span>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-primary mb-0">Fee Types Manager</h6>
            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="collapse" data-bs-target="#feeCollapse">
                <i class="bi bi-list-check me-1"></i> Configure
            </button>
        </div>

        <div id="feeCollapse" class="collapse">
            <div class="m3-checkbox-list">
                <?php
                // সব ফি আইটেম ফেচ করা
                $res_items = $conn->query("SELECT * FROM financeitem ORDER BY slno ASC");
                while($item = $res_items->fetch_assoc()):
                    $engs = $item["particulareng"];
                    $id = $item["id"];
                    
                    // চেক করা এই আইটেমটি অলরেডি সেটআপে আছে কি না
                    $stmt_chk = $conn->prepare("SELECT id FROM financesetup WHERE sessionyear = ? AND sccode = ? AND particulareng = ? LIMIT 1");
                    $stmt_chk->bind_param("sss", $sy_param, $sccode, $engs);
                    $stmt_chk->execute();
                    $chk = ($stmt_chk->get_result()->num_rows > 0) ? "checked" : "";
                    $stmt_chk->close();
                ?>
                <label class="checkbox-item" for="cc<?php echo $id; ?>">
                    <input type="checkbox" class="form-check-input me-3" id="cc<?php echo $id; ?>" 
                           onclick="ccsave(<?php echo $id; ?>);" <?php echo $chk; ?>>
                    <div>
                        <div class="fw-bold small"><?php echo $engs; ?></div>
                        <div class="text-muted small" style="font-size: 0.65rem;"><?php echo $item['particularben']; ?></div>
                    </div>
                </label>
                <?php endwhile; ?>
            </div>
            <button class="btn btn-success btn-pill w-100 mt-3" onclick="location.reload();">Done & Refresh</button>
        </div>
    </div>

    <div class="m3-card shadow-sm">
        <h6 class="fw-bold text-secondary mb-3 small uppercase tracking-wider">Class-wise Fee Structures</h6>
        
        <div class="table-container shadow-sm border">
            <table class="m3-table">
                <thead>
                    <tr>
                        <th class="particular-cell">Particular</th>
                        <th>Action</th>
                        <?php foreach($active_classes as $cls_header) echo "<th>".strtoupper($cls_header)."</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stmt_setup = $conn->prepare("SELECT * FROM financesetup WHERE sessionyear = ? AND sccode = ? ORDER BY slno ASC");
                    $stmt_setup->bind_param("ss", $sy_param, $sccode);
                    $stmt_setup->execute();
                    $res_setup = $stmt_setup->get_result();

                    while($row = $res_setup->fetch_assoc()):
                        $id = $row['id'];
                    ?>
                    <tr>
                        <td class="particular-cell">
                            <div class="fw-bold"><?php echo $row['particulareng']; ?></div>
                            <div class="text-muted small"><?php echo $row['particularben']; ?></div>
                        </td>
                        <td id="ss<?php echo $id; ?>">
                            <button class="btn btn-info btn-sm rounded-pill px-3" onclick="setvalfinsingle(<?php echo $id; ?>, 1);">Sync</button>
                        </td>
                        <?php 
                        foreach($active_classes as $cls_key) {
                            $col_name = strtolower($cls_key);
                            // যদি কলামটি ডাটাবেজে না থাকে (যেমন Nursery), তবে ০ দেখাবে
                            $val = $row[$col_name] ?? 0;
                            echo "<td class='amt-cell' id='td_{$id}_{$col_name}'>$val</td>";
                        }
                        ?>
                    </tr>
                    <?php endwhile; $stmt_setup->close(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-2 text-center">
            <button class="btn btn-primary btn-pill shadow w-100 py-3" onclick="setvalfin();">
                <i class="bi bi-person-check-fill me-2"></i> UPDATE ALL STUDENTS' FEES
            </button>
            <div id="setvalx" class="mt-2 small fw-bold text-success"></div>
            <div id="setval" class="mt-1 small opacity-50"></div>
        </div>
    </div>
</main>

<div style="height: 60px;"></div>



<script>
    // ১. সিঙ্গেল আইটেম সিঙ্ক (AJAX)
    function setvalfinsingle(id, push) {
        $.ajax({
            url: "setfinancevalsingle.php",
            type: "POST",
            data: { sccode: '<?php echo $sccode; ?>', id: id, pp: push },
            beforeSend: function () {
                $("#ss"+id).html('<div class="spinner-border spinner-border-sm text-primary"></div>');
            },
            success: function(html) {
                if(html.trim() === 'Done !') {
                    $("#ss"+id).html('<i class="bi bi-check-circle-fill text-success fs-5"></i>');
                } else {
                    // প্রগ্রেস বার হিসেবে k রিটার্ন করলে রিকার্সিভলি কল করা হচ্ছে
                    $("#td_"+id+"_status").html(html); 
                    setvalfinsingle(id, 0); 
                }
            }
        });
    }

    // ২. সব স্টুডেন্টের জন্য ফি সেট করা
    function setvalfin() {
        $.ajax({
            url: "setfinanceval.php",
            type: "POST",
            data: { sccode: '<?php echo $sccode; ?>' },
            beforeSend: function () {
                $('#setval').html('<div class="spinner-border spinner-border-sm me-2"></div> Applying changes to student records...');
            },
            success: function(html) {
                if(html.trim() === 'Done !') {
                    Swal.fire('Success!', 'Fees updated for all students.', 'success');
                    $('#setval').html('<span class="text-success fw-bold">✓ Process Completed</span>');
                } else {
                    $("#setvalx").html(html);
                    setvalfin(); // Recursive call until 'Done !'
                }
            }
        });
    }

    // ৩. ফি আইটেম একটিভ/ইন-এক্টিভ করা
    function ccsave(id) {
        // এখানে আপনার setfinanssceval.php অথবা সমগোত্রীয় লজিক কল হবে
        $.ajax({
            url: "setfinanssceval.php",
            type: "POST",
            data: { sccode: '<?php echo $sccode; ?>', itemid: id },
            success: function(res) {
                // UI feedback
            }
        });
    }
</script>

<?php include 'footer.php'; ?>