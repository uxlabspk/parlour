<?php
// php_web/auth/logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../");
exit;
?>
