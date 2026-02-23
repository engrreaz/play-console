<!-- Student -->
<?php
$stid = $userid;
$sh = 0;
$stnameeng = '~~~~~~~~~~~';
$subline = $stid;

if ($stid * 1 >= 1000) {
    $sql0 = "SELECT stnameeng FROM students WHERE stid='$stid' LIMIT 1";
    $result0 = $conn->query($sql0);
    if ($row0 = $result0->fetch_assoc()) {
        $stnameeng = $row0["stnameeng"];
        $sh = 1;
    }
}
?>

<div class="setup-card shadow-sm" <?php echo ($sh == 0) ? 'data-bs-toggle="modal" data-bs-target="#setstudentbox"' : ''; ?>>
    <div class="m3-icon-box" style="background: #E1F5FE; color: #0288D1;"><i class="bi bi-mortarboard"></i></div>
    <div class="flex-grow-1">
        <div class="fw-black" style="font-size: 0.9rem;"><?php echo ($sh == 0) ? "Setup Student Profile" : $stnameeng; ?></div>
        <small class="text-muted">Student ID # <?php echo $subline; ?></small>
    </div>
    <?php if($sh == 0): ?><i class="bi bi-plus-circle-fill text-primary"></i><?php endif; ?>
</div>