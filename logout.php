<?php
ob_start();
require_once 'config/database.php';

// Menghapus data sesi log masuk
$_SESSION = [];
session_destroy();

header("Location: login.php");
exit;
?>