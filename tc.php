<?php
session_start();
$pageTitle = "Terms & Conditions - EIMBox";
if(isset($_SESSION["user"])) {
   include 'inc.php';
} else {
    include 'header.all.php';
}

include 'tc-container.php';

  if(isset($_SESSION["user"])) {
   include 'footer.php';
} else {
    include 'footer.all.php';
}
?>
</body>

</html>