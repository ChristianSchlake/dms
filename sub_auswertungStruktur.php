<?php
//	session_start();
	include("sub_init_database.php");
	include("functions.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">
	
	<?php
		echo "<title>".abfrageEinstellung("tabellenNameLang")."</title>";
	?>

	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="icons/foundation-icons.css"/>

<style>      
	.size-12 { font-size: 12px; }
	.size-14 { font-size: 14px; }
	.size-16 { font-size: 16px; }
	.size-18 { font-size: 18px; }
	.size-21 { font-size: 21px; }
	.size-24 { font-size: 24px; }
	.size-36 { font-size: 36px; }
	.size-48 { font-size: 48px; }
	.size-60 { font-size: 60px; }
	.size-72 { font-size: 72px; }
	.size-X { font-size: 26px; }
</style>

</head>

<body>

<?php	
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	$updateStatus=0;
	$editStatus=0;
	$deleteStatus=0;
	$suchWert="%";
	$editWert="";
	$editNeuerWert="";
	$tabellenBeschreibung="";
	$datumVon="01.01.1000";
	$datumBis = date("d.m.Y");
//	print_r($_GET);
	foreach ($_GET as $key => $value) {
		if ($key=="tabelle") {
			$tabelle=$value;
			getSpaltenDMS();
			foreach ($spaltenName as $i => $value) {
				if ($spaltenName[$i]==$tabelle) {
					$tabellenBeschreibung=$spaltenBeschreibung[$i];
				}
			}

		}
		if ($key=="spaltenTyp") {
			$spaltenTypX=$value;
		}
		if ($key=="datum") {
			if ($value!="") {
				$datumX=explode("-",$value);
				if (strtotime($datumX[0])>strtotime("01.01.0000")) {
					$datumVon=$datumX[0];
				} else {
					$datumVon="01.01.1000";
				}
				if (strtotime($datumX[1])>strtotime("01.01.0000")) {
					$datumBis=$datumX[1];
				} else {
					$datumBis="31.12.9999";
				}
			}
		}
	}
?>

<?php
	$auswertungen=array();
	$auswertungenText=array();
	$auswertungenDatum=array();
	$abfrage="SELECT * FROM typenDefinition WHERE typ=\"auswertung\" AND suchwert=\"".$tabelle."\"";
	$ergebnis = mysql_query($abfrage);
	while($row = mysql_fetch_object($ergebnis))
	{
		$tmpX=split(",",$row->eingabewert);
		$auswertungen[]=$tmpX[0];
		$auswertungenDatum[]=$tmpX[1];
		$auswertungenText[]=$row->beschreibung;
	}
?>


<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<?php
				echo "<h1><a href=\"main_suche.php?aufruf=1\"><i class=\"fi-refresh \"></i> ".abfrageEinstellung("tabellenNameKurz")."</a></h1>";
			?>			
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">
	        <li class="divider hide-for-small"></li>
	        <li class="has-dropdown"><a href="#"><i class="fi-graph-bar "></i> Auswertungen</a>
	                <ul class="dropdown">
						<?php
							getSpaltenDMS();	
							foreach ($spaltenName as $i => $value) {
								switch ($spaltenTyp[$i]) {
									case 'auswahlStruktur':
											echo "<li><a href=\"sub_auswertungStruktur.php?spaltenTyp=".$spaltenTyp[$i]."&tabelle=".$spaltenName[$i]."\">",$spaltenBeschreibung[$i]."</a></li>";
										break;
									case 'auswahl':
											echo "<li><a href=\"sub_auswertungStruktur.php?spaltenTyp=".$spaltenTyp[$i]."&tabelle=".$spaltenName[$i]."\">",$spaltenBeschreibung[$i]."</a></li>";
										break;
								}
							}
						?>
	                </ul>
	        </li>
		</ul>
		<ul class="right">
			<a class="button secondary round" href="sub_auswertungStruktur.php" data-reveal-id="eingabeModal"><i class="fi-page-add"></i> neuer Eintrag</a>
		</ul>
		
	</section>
</nav>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Suchformular -->
<div class="row collapse">
	<fieldset>
		<?php
			echo "<legend>".$tabellenBeschreibung."</legend>";
		?>
		<form action="sub_auswertungStruktur.php" method="get">
			<?php
				echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
				echo "<input type=\"hidden\" name=\"spaltenTyp\" value=\"".$spaltenTypX."\"\>";
			?>
			<div class="small-8 columns">
				<?php
					echo "<input type=\"text\" placeholder=\"Datum\" name=\"datum\" value=\"".$datumVon."-".$datumBis."\">";
				?>
			</div>
			<div class="small-4 columns">
				<input class="button prefix secondary" value="suchen" type="Submit">			
			</div>
		</form>		
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->

<div class="row collapse">
	<div class="small-4 large-4 columns">
		<h2>Wert</h2>
	</div>
	<?php
		foreach ($auswertungen as $i => $value)
		{
			echo "<div class=\"small-4 large-4 columns\">";
				echo "<h2>",$auswertungenText[$i],"</h2>";
			echo "</div>";
		}
	?>
	<hr>
</div>

<?php
	$auswahlX=array();
	switch ($spaltenTypX) {
		case 'auswahlStruktur':
				generateListOrdnerAenderung(0,"",$tabelle,0,true);
			break;
		case 'auswahl':
				generateListOrdnerAenderung_auswahl($tabelle);
			break;
	}
//	print_r($auswahlX);
	foreach ($auswahlX as $i => $value)
	{
		$ordner=explode("|", $value);
		echo "<div class=\"row collapse\">";
			echo "<div class=\"small-4 large-4 columns\">";
				echo "<p>",$ordner[2],"</p>";
			echo "</div>";
			foreach ($auswertungen as $i => $value)
			{
				echo "<div class=\"small-4 large-4 columns\">";
					$abfrage="SELECT sum(".$auswertungen[$i].") AS summe FROM DMS WHERE ".$tabelle."=".$ordner[0]." AND ".$auswertungenDatum[$i]." >= STR_TO_DATE('".$datumVon."','%d.%m.%Y') AND ".$auswertungenDatum[$i]." <= STR_TO_DATE('".$datumBis."','%d.%m.%Y')";
//					echo $abfrage;
					$ergebnis=mysql_query($abfrage);
					$row = mysql_fetch_assoc($ergebnis);
//					echo $abfrage;
					$summe = $row['summe'];
/*					if ($summe=="") {
						$abfrage="SELECT sum(A.".$auswertungen[$i].") AS summe FROM DMS AS A INNER JOIN ".$tabelle."Structure AS B ON A.".$tabelle." = B.son WHERE B.father=".$ordner[0]." AND ".$auswertungenDatum[$i]." >= STR_TO_DATE('".$datumVon."','%d.%m.%Y') AND ".$auswertungenDatum[$i]." <= STR_TO_DATE('".$datumBis."','%d.%m.%Y')";
//						$abfrage="SELECT sum(".$auswertungen[$i].") AS summe FROM DMS WHERE ".$tabelle."=".$ordner[0]." AND ".$auswertungenDatum[$i]." >= STR_TO_DATE('".$datumVon."','%d.%m.%Y') AND ".$auswertungenDatum[$i]." <= STR_TO_DATE('".$datumBis."','%d.%m.%Y')";
//						echo $abfrage,"<br>";
						$ergebnis=mysql_query($abfrage);
						$row = mysql_fetch_assoc($ergebnis);
						$summe = $row['summe'];
					}
*/
					echo "<p>",$summe,"</p>";					
				echo "</div>";
			}
			echo "<hr>";
		echo "</div>";
	}
?>





<?php
	mysql_close($verbindung);
?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script src="js/foundation/foundation.reveal.js"></script>
  <script>$(document).foundation();</script> 

</body>
