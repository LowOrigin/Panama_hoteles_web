<?php
session_start();
session_destroy();
header("Location: ../formularios/login_form.php");
exit();
?>