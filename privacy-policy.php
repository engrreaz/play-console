<?php
session_start();
$pageTitle = "Privacy Policy - EIMBox";
if(isset($_SESSION["user"])) {
   include 'inc.php';

} else {
    include 'header.all.php';
  
}


include 'privacy-policy-container.php';

  if(isset($_SESSION["user"])) {
   include 'footer.php';
} else {
    include 'footer.all.php';
}
?>
</body>

</html>