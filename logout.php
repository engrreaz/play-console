<?php
session_start();
session_unset();
session_destroy();

// কুকিও ডিলিট করে দিতে পারেন যেহেতু আপনি কুকি ব্যবহার করেছেন
setcookie("user", "", time() - 3600, "/"); 

header("Location: login.php");
exit();
?>