<!-- Guardian -->

<?php
$stid = $userid;
$sh = ($stid * 1 >= 1000 && $stid != '') ? 1 : 0;
$stnameeng = ($sh == 1) ? '<span style="color:var(--m3-primary);">&nbsp;' . $fullname . '</span>' : '<span style="color:gray;">&nbsp;I AM A GUARDIAN</span>';
$subline = ($sh == 1) ? "ID # $stid" : "Guardian Profile (Click to Setup)";
?>

<div class="setup-card shadow-sm" <?php echo ($sh == 0) ? 'data-bs-toggle="modal" data-bs-target="#setguardianbox"' : ''; ?>>
    <div class="m3-icon-box" style="background: #FFF7E0; color: #F57C00;"><i class="bi bi-people-fill"></i></div>
    <div class="flex-grow-1">
        <div class="fw-black" style="font-size: 0.9rem;"><?php echo $stnameeng; ?></div>
        <small class="text-muted fw-bold"><?php echo $subline; ?></small>
    </div>
    <?php if($sh == 0): ?>
        <i class="bi bi-plus-circle-fill text-warning fs-5"></i>
    <?php else: ?>
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <?php endif; ?>
</div>