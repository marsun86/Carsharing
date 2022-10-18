<?php
include_once("includes/config.php");
include_once("includes/controller.php");
//Instanz der Klasse TileManager und dann TileManager::init aufruft     
$Carsharing  = new Carsharing(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD);
$Carsharing->datenbankerstellen();
$Carsharing->init();

echo "Datenbank initialisiert.";
echo '<a href="index.php">Link zur index.php</a>';
$weg = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if ($weg != 'localhost/Carsharing/includes/api.php') {
    echo "Datenbank initialisiert.";
}
