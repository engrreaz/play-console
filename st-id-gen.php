<?php 
$page_title = "Student ID Generator";
include 'inc.php'; 
?>

<style>
    /* ১. হিরো সেকশন কাস্টমাইজেশন */
    .hero-id-gen {
        padding-bottom: 35px;
        margin-bottom: 0;
        border-radius: 0 0 24px 24px;
    }

    /* ২. ইনপুট ফিল্ডের জন্য অতিরিক্ত প্যাডিং (আইকন থাকলে) */
    .m3-input-floating {
        padding-left: 46px !important;
    }

    /* ৩. কার্ড এবং সেকশন টাইটেল */
    .m3-card-id {
        background: #fff;
        border-radius: 8px !important;
        border: 1px solid #f0f0f0;
        margin: -25px 12px 16px;
        padding: 20px 16px;
        box-shadow: 0 8px 24px rgba(103, 80, 164, 0.05);
        position: relative;
        z-index: 10;
    }

    .class-icon-box {
        width: 48px; height: 48px;
        background: var(--m3-tonal-container);
        color: var(--m3-on-tonal-container);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
</style>

<main>
    <div class="hero-container hero-id-gen">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 900; line-height: 1.1;">ID Card Generator</div>
                    <div style="font-size: 0.8rem; opacity: 0.85; font-weight: 600;">Bulk Student Identity System</div>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                    SESSION <?php echo $sy; ?>
                </span>
            </div>
        </div>
    </div>

    <?php if (in_array($userlevel, ['Administrator', 'Head Teacher', 'Principal'])) { ?>
        
        <div id="block" style="margin-top: 15px; padding-bottom: 50px;">
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

                <div class="m3-card-id">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="class-icon-box shadow-sm">
                                <i class="bi bi-person-video"></i>
                            </div>
                            <div>
                                <div style="font-size: 1rem; font-weight: 900; color: #1C1B1F;" id="cls<?php echo $id; ?>"><?php echo strtoupper($cls2); ?></div>
                                <div style="font-size: 0.75rem; font-weight: 700; color: var(--m3-primary);"><?php echo $sec2; ?> Section</div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                             <div style="font-size: 0.65rem; font-weight: 800; color: #777; text-transform: uppercase;">Identity Format</div>
                             <div style="font-size: 0.8rem; font-weight: 900; color: #1C1B1F;">Standard QR</div>
                        </div>
                    </div>

                    <div style="border-top: 1px dashed #eee; margin: 12px 0 20px; padding-top: 15px;">
                        <div style="font-size: 0.75rem; font-weight: 800; color: #555; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-input-cursor-text"></i> Define Roll/ID Range
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="m3-floating-group">
                                    <i class="bi bi-hash m3-field-icon"></i>
                                    <input type="number" id="from<?php echo $id; ?>" class="m3-input-floating" placeholder=" " value="<?php echo $from2; ?>">
                                    <label class="m3-floating-label">START FROM</label>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="m3-floating-group">
                                    <i class="bi bi-hash m3-field-icon"></i>
                                    <input type="number" id="to<?php echo $id; ?>" class="m3-input-floating" placeholder=" " value="<?php echo $to2; ?>">
                                    <label class="m3-floating-label">END TO</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn-m3-submit" style="width: 100%; margin: 0; height: 52px; background: var(--m3-primary-gradient);" onclick="genid(<?php echo $id; ?>);">
                                    <i class="bi bi-qr-code"></i> GENERATE IDENTITY CARDS
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="gen<?php echo $id; ?>" style="margin-top: 10px;"></div>
                </div>

            <?php }
            } else { ?>
                <div style="text-align: center; padding: 40px; opacity: 0.5;">
                    <i class="bi bi-folder-x" style="font-size: 3rem;"></i>
                    <p style="font-weight: 800;">No Academic Areas Found</p>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div class="icon-box c-exit" style="width: 64px; height: 64px; margin: 0 auto 15px;">
                <i class="bi bi-shield-lock-fill" style="font-size: 2rem;"></i>
            </div>
            <div style="font-weight: 900; color: #B3261E;">ACCESS RESTRICTED</div>
            <div style="font-size: 0.8rem; color: #777;">Please contact system admin for permission.</div>
        </div>
    <?php } ?>

    <div style="height:80px;"></div>
</main>



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
                    $('#gen' + id).html('<div style="background: var(--m3-tonal-surface); padding: 12px; border-radius: 8px; color: var(--m3-primary); font-weight: 800; font-size: 0.8rem; text-align: center;"><div class="spinner-border spinner-border-sm me-2"></div> SYNCING DATA...</div>');
                },
                success: function (html) {
                    $("#gen" + id).html(html);
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Range',
                text: 'Please enter valid Start and End numbers.',
                confirmButtonColor: '#6750A4'
            });
        }
    }
</script>

<?php include 'footer.php'; ?>