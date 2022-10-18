<?php
//include_once("setup.php");

include_once("includes/config.php");
include_once("includes/controller.php");
$Carsharing = new Carsharing(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD);

//Autos als JSON
echo $Carsharing->ajaxrequest();
//echo $x;
/*

*/