<?php
session_start();
?>

<?php
	$_SESSION['auswahl_id']="%";
	$_SESSION['auswahl_Typ']="%";
	$_SESSION['auswahl_Beschreibung']="%";
	$_SESSION['auswahl_ordner']="%";
	$_SESSION['auswahl_Herausgeber']="%";
	$_SESSION['auswahl_Datum']="%";
	$_SESSION['auswahl_DatumAusgabeVon']="01.01.1000";
	$_SESSION['auswahl_DatumAusgabeBis']="01.01.9999";
	$_SESSION['auswahl_DatumSpeicherVon']="01.01.1000";
	$_SESSION['auswahl_DatumSpeicherBis']="01.01.9999";
	$_SESSION['auswahl_Datum']="%";
	$_SESSION['sortierung']="S.id DESC";
	$_SESSION['editStatus']=0;
	$_SESSION['startPage']=0;	
	header("Location: main_suche.php");
?>
