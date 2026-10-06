<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_giris"]) || $_SESSION["admin_giris"] !== true) {
    header("Location: /admin.html");
    exit;
}
?>
