<?php
// Sessiooni haldamise fail
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kaasa andmebaasi konfiguratsioon
include_once '../andmebaas/config.php';

// Kasutaja sessiooni muutujad
$on_sisse_logitud = isset($_SESSION['on_sisse_logitud']) && $_SESSION['on_sisse_logitud'] === true;
$praegune_kasutaja = $on_sisse_logitud ? $_SESSION['kasutajanimi'] : null;
$kasutaja_roll = $on_sisse_logitud ? $_SESSION['roll'] : null;
$kasutaja_id = $on_sisse_logitud ? $_SESSION['kasutaja_id'] : null;

// Funktsioon kasutaja sisselogimiseks
function logi_sisse($kasutaja_id, $kasutajanimi, $roll) {
    $_SESSION['kasutaja_id'] = $kasutaja_id;
    $_SESSION['kasutajanimi'] = $kasutajanimi;
    $_SESSION['roll'] = $roll;
    $_SESSION['on_sisse_logitud'] = true;
    $_SESSION['sisselogimise_aeg'] = time();
    
    // Uuenda globaalseid muutujaid
    global $on_sisse_logitud, $praegune_kasutaja, $kasutaja_roll, $kasutaja_id;
    $on_sisse_logitud = true;
    $praegune_kasutaja = $kasutajanimi;
    $kasutaja_roll = $roll;
    $kasutaja_id = $kasutaja_id;
}

// Funktsioon kasutaja väljalogimiseks
function logi_valja() {
    session_unset();
    session_destroy();
    
    // Uuenda globaalseid muutujaid
    global $on_sisse_logitud, $praegune_kasutaja, $kasutaja_roll, $kasutaja_id;
    $on_sisse_logitud = false;
    $praegune_kasutaja = null;
    $kasutaja_roll = null;
    $kasutaja_id = null;
}

// Funktsioon kontrollimaks kas kasutaja on sisse logitud
function kontrolli_sisselogimist() {
    global $on_sisse_logitud;
    if (!$on_sisse_logitud) {
        header('Location: ../kasutaja/login.php');
        exit();
    }
}

// Funktsioon kontrollimaks kas kasutaja on admin
function kontrolli_admin_oiguseid() {
    global $kasutaja_roll;
    if ($kasutaja_roll !== 'admin') {
        header('Location: ../pealeht/index.php');
        exit();
    }
}

// Turvaline parooli hash'imine
function hash_parool($parool) {
    return password_hash($parool, PASSWORD_DEFAULT);
}

// Parooli kontroll
function kontrolli_parooli($parool, $hash) {
    return password_verify($parool, $hash);
}
?>