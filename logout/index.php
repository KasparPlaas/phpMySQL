<?php
session_start();

// Tühjenda kõik sessiooni andmed
$_SESSION = [];

// Kui sessiooniküpsis on olemas, kustuta see
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Lõpeta sessioon
session_destroy();

// Suuna tagasi avalehele
header("Location: ../index.php");
exit;
