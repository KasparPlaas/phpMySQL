<?php
	try {
		// sinu andmed
		$db_server = 'localhost';
		$db_andmebaas = 'sport';
		$db_kasutaja = 'mario';
		$db_salasona = 'mario';

		// ühendus
		$yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
	} catch (mysqli_sql_exception $e) {
		die('Probleem andmebaasiga: ' . $e->getMessage());
	}
?>