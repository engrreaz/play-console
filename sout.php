<?php
session_start();
$_SESSION["user"] = '';
$_SESSION["devicetoken"] = '';
// unset($_SESSION['token']);
// unset($_SESSION['userData']);
// $gClient->revokeToken();
session_unset();
session_destroy();
$_SESSION = NULL;
setcookie("user", time() - (3600 * 24 * 365));
// setcookie("user", "");
header("Location:index.php?logout");
?>
<script>
    // window.location.href = 'index.php';
</script>