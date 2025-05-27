<?php
include '../kasutaja/session.php';

// Logi kasutaja välja
logi_valja();

// Suuna sisselogimise lehele
header('Location: ../kasutaja/login.php');
exit();
?>