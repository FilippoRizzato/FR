<?php
session_start();
session_unset();
session_destroy();

setcookie('user_id', '', time() - 3600, "/");
setcookie('user_email', '', time() - 3600, "/");

header("Location: home_page.php");
exit;
?>