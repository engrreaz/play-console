<?php include 'inc.php'; ?>

<style>



    .page-header-m3 {
        background: var(--dark);
        color: white;
        padding: 20px;
        border-radius: 0 0 var(--m3-radius) var(--m3-radius);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Floating Label Logic */
    .m3-field {
        position: relative;
        margin-bottom: 20px;
        background: #f1f3f4;
        border-radius: 8px 8px 0 0;
        border-bottom: 2px solid var(--dark);
    }

    .m3-field input,
    .m3-field select {
        width: 100%;
        border: none;
        background: transparent;
        padding: 25px 15px 10px 15px;
        outline: none;
        font-size: 1rem;
    }

    .m3-field label {
        position: absolute;
        top: 15px;
        left: 15px;
        color: #5f6368;
        transition: all 0.2s ease;
        pointer-events: none;
    }

    .m3-field input:focus~label,
    .m3-field input:not(:placeholder-shown)~label,
    .m3-field select:focus~label,
    .m3-field select:not([value=""]):valid~label {
        top: 5px;
        font-size: 0.75rem;
        color: var(--dark);
    }

    .btn-m3 {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-m3-info {
        background: #00bcd4;
        color: white;
        border: none;
    }

    .btn-m3-info:hover {
        background: #0097a7;
        box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);
    }
</style>


<main class="container">
    <div class="page-header-m3 mb-4">
        <div class="menu-icon fs-3"><i class="bi bi-people-fill"></i></div>
        <div>
            <h4 class="m-0">Student's ID Generator</h4>
            <small style="opacity: 0.8;">Generate professional ID cards easily</small>
        </div>
    </div>

    <?php if (in_array($userlevel, ['Administrator', 'Head Teacher', 'Principal'])) { ?>

        <div id="block">
            <?php
            $sql00xgr = "SELECT * FROM areas WHERE user='$rootuser' AND sessionyear LIKE '%$sy%' ORDER BY idno, id";
            $result00xgr = $conn->query($sql00xgr);

            if ($result00xgr->num_rows > 0) {
                while ($row00xgr = $result00xgr->fetch_assoc()) {
                    $id = $row00xgr["id"];
                    $cls2 = $row00xgr["areaname"];
                    $sec2 = $row00xgr["subarea"];
                    $from2 = $row00xgr["rollfrom"];
                    $to2 = $row00xgr["rollto"];
                    ?>

                    <div class="m3-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-dark text-white rounded-circle p-2 d-flex align-items-center justify-content-center"
                                    style="width:45px; height:45px;">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div>
                                    <h5 class="m-0 fw-bold text-dark" id="cls<?php echo $id; ?>"><?php echo $cls2; ?></h5>
                                    <small class="text-muted">Class Name</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="m-0 fw-bold text-dark" id="sec<?php echo $id; ?>"><?php echo $sec2; ?></h6>
                                <small class="text-muted">Section / Group</small>
                            </div>
                        </div>

                        <hr class="my-3" style="opacity: 0.1;">

                        <p class="text-muted mb-3"><i class="bi bi-input-cursor-text me-2"></i>Enter Student Roll/ID Range:</p>

                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="m3-field">
                                    <input type="number" id="from<?php echo $id; ?>" placeholder=" " value="<?php echo $from2; ?>">
                                    <label>Start From</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="m3-field">
                                    <input type="number" id="to<?php echo $id; ?>" placeholder=" " value="<?php echo $to2; ?>">
                                    <label>End To</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <button class="btn btn-m3 btn-m3-info w-100 h-100 mb-3" onclick="genid(<?php echo $id; ?>);">
                                    <i class="bi bi-qr-code-scan me-2"></i> Generate IDs
                                </button>
                            </div>
                        </div>

                        <div id="gen<?php echo $id; ?>" class="mt-2"></div>
                    </div>

                <?php }
            } else {
                echo '<div class="alert alert-warning rounded-4">No records found for this session.</div>';
            } ?>
        </div>

    <?php } else {
        echo '<div class="alert alert-danger rounded-4">Access Denied. Please login again.</div>';
    } ?>

    <div style="height:80px;"></div>
</main>

<?php include 'footer.php'; ?>

<script>
    function genid(id) {
        let a = document.getElementById("from" + id).value;
        let b = document.getElementById("to" + id).value;

        if (a > 0 && b > 0) {
            var infor = "rootuser=<?php echo $rootuser; ?>&id=" + id + "&sccode=<?php echo $sccode; ?>&from=" + a + "&to=" + b;

            $.ajax({
                type: "POST",
                url: "backend/generate-stid.php",
                data: infor,
                cache: false,
                beforeSend: function () {
                    $('#gen' + id).html('<div class="d-flex align-items-center text-primary"><div class="spinner-border spinner-border-sm me-2"></div> Working on it...</div>');
                },
                success: function (html) {
                    $("#gen" + id).html(html);
                }
            });
        } else {
            alert('Please Enter Valid Roll Range');
        }
    }
</script>