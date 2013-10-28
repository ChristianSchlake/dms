<?php
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Übergabe auslesen und in die Session schreiben */
	if(isset($_GET['Typ']))
	{
	$_SESSION['auswahl_Typ']=$_GET['Typ'];
	}

	if(isset($_GET['id']))
	{
	$_SESSION['auswahl_id']=$_GET['id'];
	}

	$auswahl_Beschreibung=$_SESSION['auswahl_Beschreibung'];

	if(isset($_GET['Beschreibung']))
	{
	$_SESSION['auswahl_Beschreibung']=$_GET['Beschreibung'];
	}

	if(isset($_GET['ordner']))
	{
	$_SESSION['auswahl_ordner']=$_GET['ordner'];
	}

	if(isset($_GET['herausgeber']))
	{
	$_SESSION['auswahl_Herausgeber']=$_GET['herausgeber'];
	}

	if(isset($_GET['speicherdatum']))
	{
	$_SESSION['auswahl_Datum']=$_GET['speicherdatum'];
	}

	if(isset($_GET['herausgabeDatumVon']))
	{
	$_SESSION['auswahl_DatumAusgabeVon']=$_GET['herausgabeDatumVon'];
	}

	if(isset($_GET['herausgabeDatumBis']))
	{
	$_SESSION['auswahl_DatumAusgabeBis']=$_GET['herausgabeDatumBis'];
	}

	if(isset($_GET['speicherdatumBis']))
	{
	$_SESSION['auswahl_DatumAusgabeBis']=$_GET['speicherdatumBis'];
	}

	if(isset($_GET['speicherdatumVon']))
	{
	$_SESSION['auswahl_DatumAusgabeVon']=$_GET['speicherdatumVon'];
	}

	if(isset($_GET['editStatus']))
	{
	$_SESSION['editStatus']=$_GET['editStatus'];
	}

/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Werte aus session in Variablen schreiben */
	if (!isset($_SESSION['auswahl_ordner']))	{
		$auswahl_ordner="%";
	}	
	else {
		$auswahl_ordner=$_SESSION['auswahl_ordner'];
	}

	if (!isset($_SESSION['auswahl_id']))	{
		$auswahl_id="%";
	}	
	else {
		$auswahl_id=$_SESSION['auswahl_id'];
	}

	if (!isset($_SESSION['auswahl_Typ']))	{
		$auswahl_Typ="%";
	}
	else {
		$auswahl_Typ=$_SESSION['auswahl_Typ'];
	}

	if (!isset($_SESSION['auswahl_Beschreibung']))	{
		$auswahl_Beschreibung="%";
	}
	else {
		$auswahl_Beschreibung=$_SESSION['auswahl_Beschreibung'];
	}

	if (!isset($_SESSION['auswahl_Kategorie']))	{
		$auswahl_Kategorie="%";
	}
	else {
		$auswahl_Kategorie=$_SESSION['auswahl_Kategorie'];
	}

	if (!isset($_SESSION['auswahl_Herausgeber']))	{
		$auswahl_Herausgeber="%";
	}
	else {
		$auswahl_Herausgeber=$_SESSION['auswahl_Herausgeber'];
	}

	if (!isset($_SESSION['auswahl_Datum']))	{
		$auswahl_Datum="%";
	}
	else {
		$auswahl_Datum=$_SESSION['auswahl_Datum'];
	}

	if (!isset($_SESSION['auswahl_DatumAusgabeVon']))	{
		$auswahl_DatumAusgabeVon="01.01.1000";
	}
	else {
		$auswahl_DatumAusgabeVon=$_SESSION['auswahl_DatumAusgabeVon'];
	}

	if (!isset($_SESSION['auswahl_DatumAusgabeBis']))	{
		$auswahl_DatumAusgabeBis="01.01.9999";
	}
	else {
		$auswahl_DatumAusgabeBis=$_SESSION['auswahl_DatumAusgabeBis'];
	}

	if (!isset($_SESSION['auswahl_DatumAusgabeVon']))	{
		$auswahl_DatumAusgabeVon="01.01.1000";
	}
	else {
		$auswahl_DatumAusgabeVon=$_SESSION['auswahl_DatumAusgabeVon'];
	}

	if (!isset($_SESSION['auswahl_DatumAusgabeBis']))	{
		$auswahl_DatumAusgabeBis="01.01.9999";
	}
	else {
		$auswahl_DatumAusgabeBis=$_SESSION['auswahl_DatumAusgabeBis'];
	}

	if (!isset($_SESSION['sortierung']))	{
		$auswahl_sortierung="S.id DESC";
	}
	else {
		$auswahl_sortierung=$_SESSION['sortierung'];
	}

	if (!isset($_SESSION['editStatus']))	{
		$editStatus=0;
	}
	else {
		$editStatus=$_SESSION['editStatus'];
	}
?>
