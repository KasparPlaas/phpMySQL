<?php
// andmebaasi ühendus

$db_server = 'localhost';
$db_andmebaas = 'majutus';
$db_kasutaja = 'kaspar';
$db_salasona = 'Passw0rd';

// Create connection using object-oriented style
$yhendus = new mysqli($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);

// Check connection
if ($yhendus->connect_error) {
    die('Probleem andmebaasiga: ' . $yhendus->connect_error);
}
?>
