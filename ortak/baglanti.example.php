<?php
$host = "localhost";
$dbname = "hastane_otomasyon";
$user = "KULLANICI_ADINIZI_GIRIN";
$pass = "SIFRENIZI_GIRIN";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>