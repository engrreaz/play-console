<?php
// inc.light.php এ ডাটাবেজ কানেকশন এবং $sccode থাকে
include '../inc.light.php'; 

// জাভাস্ক্রিপ্ট থেকে পাঠানো ডাটা
$fid = intval($_POST['fid']);
$itemcode = $_POST['itemcode'];
$spl = $_POST['spl'];

// স্লট এবং সেশন কুকি বা পোস্ট থেকে পুনরুদ্ধার (নিশ্চিত হওয়ার জন্য)
$slot = $_POST['slot'] ?? $_COOKIE['slot'] ?? '';
$session = $sessionyear;

// ১. আইটেমের নাম ফেচ করা (টাইটেলের জন্য)
$itemData = $conn->query("SELECT particulareng, particularben FROM financesetup WHERE itemcode='$itemcode'")->fetch_assoc();
$itemText = ($itemData['particulareng'] ?? '') . ' | ' . ($itemData['particularben'] ?? '');

// ২. ক্লাস লিস্ট ফেচ করা (ফিক্সড কুয়েরি)
$classSql = "SELECT areaname FROM areas 
             WHERE sccode='$sccode' AND sessionyear='$session' 
             GROUP BY areaname 
             ORDER BY MIN(idno) ASC";
$classRs = $conn->query($classSql);

if (!$classRs || $classRs->num_rows === 0) {
    echo "<div class='p-3 text-center text-muted'>No classes found. Check Session: $session</div>";
    exit;
}

while ($c = $classRs->fetch_assoc()) {
    $class = $c['areaname'];
    
    // ক্লাসের অ্যামাউন্ট
    $amtRs = $conn->query("SELECT amount FROM financesetupvalue WHERE classname='$class' AND sccode='$sccode' AND sessionyear='$session' AND slot='$slot' AND itemcode='$itemcode'");
    $totalClassAmount = ($amtRs->num_rows) ? $amtRs->fetch_assoc()['amount'] : 0;
    ?>
    
    <div class="m3-class-accordion mb-2 shadow-sm border overflow-hidden" style="border-radius: 16px; background: white;">
        <div class="p-3 d-flex justify-content-between align-items-center pointer" 
             onclick="$(this).next('.sec-list').slideToggle(200); $(this).find('.chevron').toggleClass('bi-chevron-right bi-chevron-down');">
            
            <div class="d-flex align-items-center">
                <i class="bi bi-chevron-right chevron me-3 text-primary"></i>
                <div>
                    <div class="fw-black text-dark fs-6"><?= $class ?></div>
                    <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.55rem;">Set Base Amount</div>
                </div>
            </div>

            <button class="btn btn-tonal-primary btn-sm rounded-pill px-3 fw-black" 
                    onclick="event.stopPropagation(); openAmountModal(<?= $fid ?>, '<?= $itemcode ?>', '<?= $spl ?>', '<?= $itemText ?>', '<?= $class ?>', '')">
                ৳ <?= number_format($totalClassAmount, 2) ?>
            </button>
        </div>

        <div class="sec-list p-2 bg-light border-top" style="display:none;">
            <?php
            // গুরুত্বপূর্ণ: সেকশন কুয়েরি থেকে 'slot' ফিল্টারটি সরিয়ে দেখুন যদি areas টেবিলে slot না থাকে
            $secSql = "SELECT DISTINCT subarea FROM areas 
                       WHERE sccode='$sccode' AND sessionyear='$session' AND areaname='$class' 
                       ORDER BY subarea ASC";
            $secRs = $conn->query($secSql);

            if ($secRs && $secRs->num_rows > 0):
                while ($s = $secRs->fetch_assoc()):
                    $section = $s['subarea'];
                    // সেকশন ভিত্তিক অ্যামাউন্ট
                    $sAmtRs = $conn->query("SELECT amount FROM financesetupvalue WHERE sccode='$sccode' AND sessionyear='$session' AND slot='$slot' AND classname='$class' AND sectionname='$section' AND itemcode='$itemcode'");
                    $secAmount = ($sAmtRs->num_rows) ? $sAmtRs->fetch_assoc()['amount'] : 0;
                ?>
                    <div class="d-flex justify-content-between align-items-center p-2 px-3 mb-1 bg-white border rounded-4">
                        <div class="fw-bold small text-secondary">
                            <i class="bi bi-arrow-return-right me-2 opacity-50"></i>Sec: <?= $section ?>
                        </div>
                        <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" style="font-size: 0.7rem;"
                                onclick="openAmountModal(<?= $fid ?>, '<?= $itemcode ?>', '<?= $spl ?>', '<?= $itemText ?>', '<?= $class ?>', '<?= $section ?>')">
                            ৳ <?= number_format($secAmount, 2) ?>
                        </button>
                    </div>
                <?php 
                endwhile;
            else:
                echo "<div class='small text-muted p-2 ps-4'>No sections found.</div>";
            endif; 
            ?>
        </div>
    </div>
<?php } ?>