<?php
require_once("setup.php");
?>





<!DOCTYPE html>
<html lang="de">
  <head>


  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Carsharing</title>
    <link rel="stylesheet" href="./styles/Style.css" type="text/css" />
  </head>
  <body>

  
<a href="unterseite.php">Ausleihe, Rückgabe und Suche eines Fahrzeugs </a>


  <h1> Anwendungsfall 3: Datenanzeige<h1>



<h2>Stationen<h2>
<?php
$Carsharing->anzeigeSTATIONS();
?>
<h2>Autos<h2>

<?php
$Carsharing->anzeigeCARS();
?>



<span class="rechts">
 
  


<h1>Alle aktuell ausgeliehenen
Fahrzeuge</h1>

<?php
$Carsharing->anzeigeausgelieheneFahrzeuge();
?>



</form>
  </body>
</html>