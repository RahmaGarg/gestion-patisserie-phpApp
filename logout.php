
<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: /patisserie/login-form-18/login-form-18/index.php");
exit();
?>
