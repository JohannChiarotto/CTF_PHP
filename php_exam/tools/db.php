<?php
$mysqli = new mysqli("localhost", "root", "123", "php_exam_db");
if ($mysqli->connect_error) die("Erreur : " . $mysqli->connect_error);
$mysqli->set_charset("utf8mb4");
?>