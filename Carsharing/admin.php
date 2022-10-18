<?php

require_once("setup.php");
// Dateiupload

if (isset($_POST["submit"])) {
	if (ladeDatei()) {
		echo "Dateiübertragung erfolgreich";
		$Carsharing->upload();
		echo "hat geklappt";
	} else
		echo "Dateiübertragung abrechen";
}

function ladeDatei()
{
	/* verhindert undefined Array Key*/
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		/* Dateiverzeichnis festlegen und ausgeben*/
		$verzeichnis = "upload/";
		$dateiName = basename($_FILES["upload"]["name"]);
		$pfadDateiName = $verzeichnis . $dateiName;


		echo "<br>" . $dateiName . "<br>";
		echo "<br>" . $pfadDateiName . "<br>";

		/* Datei auf Server bringen*/

		if (move_uploaded_file($_FILES["upload"]["tmp_name"], $pfadDateiName)) {
			echo $dateiName;
			echo "<br>Datei erfolgreich verschoben <br>";
			return true;
		} else {
			echo "Fehler beim Upload";
			return false;
		}

		/* check Dateiendung*/
		$dateiEndung = strtolower(pathinfo($pfadDateiName, PATHINFO_EXTENSION));
		echo $dateiEndung . "<br>";
		if ($dateiEndung === '') {
			echo "Dateiendung ist nicht definiert";
			return false;
		}

		if ($dateiEndung != "txt") {
			echo "keine Textdatei";
			return false;
		}

		/* falls existiert schon*/

		if (file_exists($pfadDateiName)) {
			echo "Datei ist schon vorhanden";
			return false;
		}
	}
}
?>










<!DOCTYPE html>
<html lang="de">

<head>

	<link rel="stylesheet" href="./styles/Style.css" type="text/css" />

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Verwaltungsoberfläche Carsharing</title>
</head>

<body>


	<h1>Anwendungsfall 2: Verwalten von Stationen und Fahrzeugen</h1>

	<form method="POST" enctype='multipart/form-data' action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<style>
			.error {
				color: #FF0000;
			}
		</style>

		<h1>Dateiupload</h1>
		<input type="file" name="upload" id="file">
		<input type="submit" value="upload" name="submit">
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST['upload'])) {
				$Carsharing->upload();
			}
		}
		?>


		<h3> Stationen </h3>
		<p>
			<span class="error">* erforderliche Eingaben</span>
			<br>
			<span class="error">*</span>

			<input type="text" name="nameid">Name<br>

			<input type="text" name="adresseid">Adresse<br>

			<input type="number" step="0.1" name="breiteid">Geographische_Breite<br>

			<input type="number" step="0.1" name="laengeid">Geographiche_Länge<br>

			<input type="number" name="stellplatzid">Stellplätze<br>

			<input type="submit" name="datenSTATION" value="Station erstellen" /><br>

			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['datenSTATION'])) {
					$Carsharing->einzeleinleseSTATIONS();
				}
			}
			?>

		<h3>Fahrzeuge</h3>

		<span class="error">*</span>

		<input type="text" name="bezeichnungid">Bezeichnung<br>

		<select name="typid">
			<option value="0">Kleinwagen</option>
			<option value="1">Kompaktwagen</option>
			<option value="2">Mittelklasse</option>
			<option value="3">Van</option>
			<option value="4">Transporter</option>
		</select>
		Typ<br>

		<input type="text" name="kennzeichenid">Kennzeichen<br>

		<input type="number" name="tuerid">Anzahl Türen<br>

		<input type="number" name="sitzeid">Anzahl Sitze<br>

		<input type="text" name="ortid">Standort<br>

		<input type="submit" name="datenCARS" value="Auto erstellen" />

		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST['datenCARS'])) {
				$Carsharing->einzeleinleseCAR();
			}
		}
		?>

		<h3>
			alle Einzelformulareingaben hochladen
		</h3>
		<input type="submit" name="load" value="Station und Fahrzeug erstellen" /><br>
		<!--    Dateiupload                    -->

		<?php
		if (isset($_POST['load'])) {
			$Carsharing->inDB();
		}

		?>
	</form>
</body>

</body>

</html>