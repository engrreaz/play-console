<?php
session_start();
$pageTitle = "Account Deletion Policy - EIMBox";
if (isset($_SESSION["user"])) {
  include 'inc.php';
} else {
  include 'header.all.php';
}

include 'adp-container.php';

if (isset($_SESSION["user"])) {
  include 'footer.php';
} else {
  include 'footer.all.php';
}
?>
</body>

</html>