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

<script type="text/javascript">
	function FensterOeffnen (Adresse) {
		MeinFenster = window.open(Adresse, "Zweitfenster", "width=300,height=400,left=100,top=200");
		MeinFenster.focus();
	}
</script>

<?php	
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	foreach ($_GET as $key => $value) {
		if ($key=="aufruf") {
			reset_suche();
		}
//		echo $key,"-->",$value,"<br>";
		if ($value=="") {
			$value="%";
		}
		$abfrage="UPDATE typenDefinition SET suchwert='".$value."' WHERE name='".$key."'";
		mysql_query($abfrage);
	}

// File Upload ausführen
	getSpaltenDMS(); 	
	// reset option Fileupload
	$abfrage="UPDATE typenDefinition SET eingabewert=0 WHERE name=\"fileupload\"";
	mysql_query($abfrage);
	// Post Daten abfragen und in die Datenbank eintragen	
	foreach ($_POST as $key => $value) {
		if ($key=="idEDI") {
			$idEdit=$value;
		}		
		$key=substr($key,0,-3); // das ADD am ende entfernen
		$abfrage="UPDATE typenDefinition SET eingabewert='".$value."' WHERE name='".$key."'";
		mysql_query($abfrage);
	}
	getSpaltenDMS();
	$fileUpLoad=abfrageEinstellungADDFile("fileupload");
	if ($fileUpLoad==1) {
		$allesRichtig=true;
		// neue ID ermitteln
		$MaxID=mysql_query("SELECT MAX(id) FROM DMS");
		$MaxID=mysql_fetch_array($MaxID, MYSQL_BOTH);
		$MaxID=$MaxID[0];
		$MaxID=$MaxID+1;
		// Dateinamen ersetllen
		$path_parts=pathinfo($_FILES['userfile']['name']);
		$extension=$path_parts['extension'];
		$datei="upload/".$MaxID.".".$extension;
		foreach ($spaltenEingabewert as $i => $spalte) {
			switch ($spaltenTyp[$i]) {
				case "zahl":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "auswahlStruktur":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}					
					}
					break;
				case "previewPic":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						} else {
							$path_parts=pathinfo($_FILES[$spalte]['name']);
							$extensionPreview=$path_parts['extension'];
							$dateiPreview="preview/".$spaltenName[$i]."_".$MaxID.".jpg";
							$url=$spalte;
							echo $url,"<br>";							
							echo $dateiPreview;
							file_put_contents($dateiPreview, fopen($url, 'r'));
						}
					}
					break;
				case "auswahl":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}					
					}
					break;
				case "text":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "url":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;					
				case "dokurl":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						} else {
							if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
								move_uploaded_file($_FILES['userfile']['tmp_name'], $datei);
							} else {
								echo "<span class=\"round alert label\">Fehler beim Dateiupload</span>";
								$allesRichtig=false;
							}
						}
					}
					break;
				case "datum":
					if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
						if (checkDateX($spalte)!=true) {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
			}
		}
		if ($allesRichtig==true) {				
			$spaltenClause="";
			$spaltenValueClause="";
			getSpaltenDMS();
			foreach ($spaltenName as $i => $spalte) {
				switch ($spaltenTyp[$i]) {
					case 'einstellung':
						break;
					case 'datum':
						if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
							$spaltenClause=$spaltenClause.",".$spaltenName[$i];
							$spaltenValueClause=$spaltenValueClause.",STR_TO_DATE('".$spaltenEingabewert[$i]."', '%d.%m.%Y')";								
						}
						break;
					case 'dokurl':
						if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
							$spaltenClause=$spaltenClause.",".$spaltenName[$i];
							$spaltenValueClause=$spaltenValueClause.",\"".$spaltenEingabewert[$i]."\"";
						}
						break;
					case 'previewPic':
						break;
					default:
						if ($spaltenBreiteNeuesDokumentFormular[$i]!="") {
							$spaltenClause=$spaltenClause.",".$spaltenName[$i];
							$spaltenValueClause=$spaltenValueClause.",\"".$spaltenEingabewert[$i]."\"";
						}
						break;
				}
			}
			$spaltenClause=substr($spaltenClause,1);
			$spaltenValueClause=substr($spaltenValueClause,1);
			$aufruf="INSERT INTO DMS (".$spaltenClause.",id) VALUES (".$spaltenValueClause.",".$MaxID.")";
			echo $aufruf;
			$eintragen = mysql_query($aufruf);
		} else {
			echo "<br>";
			echo "<span class=\"round alert label\">Das Dokument wurde nicht eingecheckt!</span>";
		}
	}
// Update der Daten durchführen
	getSpaltenDMS();
	if ($fileUpLoad==2) {
		$allesRichtig=true;
		foreach ($spaltenEingabewert as $i => $spalte) {
//			echo $i,"-->",$spalte,"<br>";
			switch ($spaltenTyp[$i]) {
				case "zahl":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "auswahlStruktur":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}					
					break;
				case "previewPic":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}					
					break;
				case "auswahl":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "text":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "url":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "dokurl":
					if ($spaltenBreiteShow[$i]!="") {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "datum":
					if ($spaltenBreiteShow[$i]!="") {
						if (checkDateX($spalte)!=true) {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;						
						}
					}
					break;
			}
		}
		if ($allesRichtig==true) {
//			$abfrage="UPDATE DMS SET dir=".$ordner.",TypID=".$Typ.",Beschreibung=\"".$Beschreibung."\",Herausgeber=".$herausgeber.",Herausgabedatum=STR_TO_DATE('".$herausgabedatum."', '%d.%m.%Y') WHERE id=".$id; 
			foreach ($spaltenName as $i => $spalte) {				
				switch ($spaltenTyp[$i]) {
					case 'einstellung':
						break;
					case 'datum':
						if ($spaltenBreiteShow[$i]!="") {
							$spaltenClause=$spaltenClause.",".$spaltenName[$i]."=STR_TO_DATE('".$spaltenEingabewert[$i]."', '%d.%m.%Y')";
						}
						break;
					default:
						if ($spaltenBreiteShow[$i]!="") {						
							$spaltenClause=$spaltenClause.",".$spaltenName[$i]."=\"".$spaltenEingabewert[$i]."\"";
						}
						break;
				}
			}
			$spaltenClause=substr($spaltenClause,1);
			$aufruf="UPDATE DMS SET ".$spaltenClause." WHERE id=".$idEdit;
//			echo $aufruf;
			$eintragen = mysql_query($aufruf);
		}		
	}
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
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
	        <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Listen verwalten</a>
	                <ul class="dropdown">
						<?php
							getSpaltenDMS();	
							foreach ($spaltenName as $i => $value) {
								switch ($spaltenTyp[$i]) {
									case 'auswahl':
											echo "<li><a href=\"sub_verwalte_auswahl.php?editStatus=0&tabelle=".$spaltenName[$i]."\">",$spaltenBeschreibung[$i]."</a></li>";
										break;
									case 'auswahlStruktur':
											echo "<li><a href=\"sub_verwalte_auswahlStruktur.php?editStatus=0&tabelle=".$spaltenName[$i]."\">",$spaltenBeschreibung[$i]."</a></li>";
										break;
								}
							}
						?>
	                </ul>
	        </li>
			<li class="divider"></li>
		    <li><a href="sub_einstellungen.php"><i class="fi-wrench "></i> Einstellungen</a></li>
			<li class="divider"></li>
			<a class="button secondary round" href="main_suche.php" data-reveal-id="suchModal"><i class="fi-page-search"></i> suchen</a>
		</ul>
		<ul class="right">
			<li class="divider"></li>
			<a class="button secondary round" href="main_suche.php" data-reveal-id="newFileModal"><i class="fi-page-add"></i> neues Dokument</a>
		</ul>
		
	</section>
</nav>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->
<?php
	$editStatus=abfrageEinstellung("editStatus");
	getSpaltenDMS();
?>
<div class="row collapse">
	<fieldset>
		<?php
			echo "<legend>".abfrageEinstellung("tabellenNameLang")."</legend>";
		?>
		<div class="row">
			<div class="small-12 large-12 columns"\>
				<dl class="sub-nav">
					<?php
						if($editStatus==0){
			  				echo "<dd class=\"active\"><a href=\"main_suche.php?editStatus=0\">Show</a></dd>";
		  					echo "<dd><a href=\"main_suche.php?editStatus=1\">Edit</a></dd>";
						}
						else {
			  				echo "<dd><a href=\"main_suche.php?editStatus=0\">Show</a></dd>";
		  					echo "<dd class=\"active\"><a href=\"main_suche.php?editStatus=1\">Edit</a></dd>";
						}
					?>
				</dl>
				<hr>		
			</div>
			
		</div>		
		<!-- Spaltenüberschriften -->
		<?php
			$sortierung=abfrageEinstellung("sortierung");
			$sortierfolge=abfrageEinstellung("sortierfolge");
			if ($sortierfolge==1) {				
				$sortierfolgeNEU=0;
			} else {
				$sortierfolgeNEU=1;
			}
			$sortClause="";
//--------------------------------------------------------------------------------------------------------------------
// Tabellenüberschrift einfügen
//--------------------------------------------------------------------------------------------------------------------
		echo "<div class=\"row collapse\">";
			foreach ($spaltenName as $i => $spalte) {
				// Aufteilung im Grid festelgen (Gilt auf für die Spaltendarstellung weiter unten)
				switch ($spaltenTyp[$i]) {
					case 'auswahl':
						// Sortierung
						$b=chr(64+$spaltenID[$i]);
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY upper(".$b.".".$spaltenName[$i].")";
						}
						break;
					case 'auswahlStruktur':
						// Sortierung
						$b=chr(64+$spaltenID[$i]);
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY upper(".$b.".".$spaltenName[$i].")";
						}
						break;
					case 'previewPic':
						break;
					case 'text':
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY upper(".$spaltenName[$i].")";
						}
						break;
					case 'url':
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY upper(".$spaltenName[$i].")";
						}
						break;
					case 'dokurl':
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY upper(".$spaltenName[$i].")";
						}
						break;						
					case 'zahl':
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$spaltenName[$i];
						}
						break;
					case 'datum':
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$spaltenName[$i];
						}
						break;
				}
				// Button einfügen			
				switch ($spaltenTyp[$i]) {
					case 'auswahl':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'auswahlStruktur':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'previewPic':
						break;
					case 'text':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'url':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'dokurl':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'zahl':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;
					case 'datum':
						if ($spaltenBreiteShow[$i]!="") {
							echo $spaltenBreiteShow[$i];
							if($sortierung==$spaltenName[$i]) {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand\">".$spaltenBeschreibung[$i]."</a>";
							} else {
								echo "<a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button tiny expand secondary\">".$spaltenBeschreibung[$i]."</a>";
							}
							echo "</div>";
						}
						break;						
				}
			}
			if ($sortierfolge==0) {
				$sortClause=$sortClause." DESC";
			}		
		echo "</div>";
		echo "<hr>";
//--------------------------------------------------------------------------------------------------------------------
// Tabelleninhalt einfügen
//--------------------------------------------------------------------------------------------------------------------
			// Select abfrage aufbauen
			$innerJoin="";
			$whereClause="";
			$selectClause="";
			foreach ($spaltenName as $i => $spalte) {
				switch ($spaltenTyp[$i]) {
					case 'auswahl':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID";
						$innerJoin=$innerJoin." INNER JOIN ".$spaltenName[$i]." AS ".$b." ON Z.".$spaltenName[$i]." = ".$b.".".$spaltenName[$i]."ID";
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;
					case 'auswahlStruktur':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID";
						$innerJoin=$innerJoin." INNER JOIN ".$spaltenName[$i]." AS ".$b." ON Z.".$spaltenName[$i]." = ".$b.".".$spaltenName[$i]."ID";
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;
					case 'previewPic':
						break;
					case 'zahl':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$spaltenName[$i];
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;
					case 'text':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$spaltenName[$i];
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;
					case 'url':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$spaltenName[$i];
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;
					case 'dokurl':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$spaltenName[$i];
						$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";
						break;						
					case 'datum':
						$b=chr(64+$spaltenID[$i]);
						$selectClause=$selectClause.",".$spaltenName[$i];
						$datumX=explode("-",$spaltenSuchwert[$i]);
						if (strtotime($datumX[0])>strtotime("01.01.0000")) {
							$whereClause=$whereClause." AND Z.".$spaltenName[$i]." >= STR_TO_DATE('".$datumX[0]."','%d.%m.%Y')";
						}
						if (strtotime($datumX[1])>strtotime("01.01.0000")) {
							$whereClause=$whereClause." AND Z.".$spaltenName[$i]." <= STR_TO_DATE('".$datumX[1]."','%d.%m.%Y')";
						} else {
							// wenn nur ein Wert, dann nach dem genauen Datum suchen!
							$whereClause=str_replace(" AND Z.".$spaltenName[$i]." >= STR_TO_DATE("," AND STR_TO_DATE(Z.".$spaltenName[$i].",'%Y-%m-%d') = STR_TO_DATE(", $whereClause); 
						}						
						break;
				}									
			}
			$selectClause=ltrim($selectClause, ",");
			$innerJoin="SELECT ".$selectClause." FROM DMS AS Z".$innerJoin." WHERE ";
			$whereClause=ltrim($whereClause, " AND ");
			$abfrage=$innerJoin.$whereClause.$sortClause;	
					
			// Suchergebnis in einer Tabelle darstellen
			$startPage=abfrageEinstellung("startPage");
			$maxEintraegeProSite=abfrageEinstellung("maxEintraege");
			$ergebnis = mysql_query($abfrage);
			$menge = mysql_num_rows($ergebnis);
			if ($menge<$startPage) {
				$startPage=0;				
			}			
			$abfrage=$abfrage." LIMIT $startPage, $maxEintraegeProSite";
			$ergebnis = mysql_query($abfrage);
			$datumsFormat=abfrageEinstellung("datumFormat");
//			echo $abfrage;
			if($editStatus==0)
			{
				// ANZEIGE DER TABELLE
				while($row = mysql_fetch_object($ergebnis))
				{
					echo "<div class=\"row\">";
					foreach ($spaltenName as $i => $spalte)
					{
						switch ($spaltenTyp[$i])
						{
							case 'auswahl':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<p>",$row->$spalteX,"</p>";
									echo "</div>";
								}
								break;
							case 'auswahlStruktur':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<p>",$row->$spalteX,"</p>";
									echo "</div>";
								}
								break;
							case 'previewPic':
								if ($spaltenBreiteShow[$i]!="") {
									echo $spaltenBreiteShow[$i];
										echo "<a class=\"th\" href=\"preview/".$spaltenName[$i]."_".$row->id.".jpg\">";
											echo "<img src=\"preview/".$spaltenName[$i]."_".$row->id.".jpg\">";
										echo "</a>";
									echo "</div>";
								}
								break;
							case 'text':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<p>",$row->$spalteX,"</p>";
									echo "</div>";
								}
								break;
							case 'url':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<a class=\"fi-link\" href=\"",$row->$spalteX,"\"></a>";									
									echo "</div>";
								}
								break;
							case 'dokurl':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<a class=\"tiny secondary expand fi-download\" href=\"sub_openFile.php?id=".$row->id."\" onclick=\"FensterOeffnen(this.href); return false\"> ".$row->$spalteX."</a>";												
									echo "</div>";
								}
								break;
							case 'zahl':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<p>",$row->$spalteX,"</p>";
									echo "</div>";
								}
								break;
							case 'datum':
								if ($spaltenBreiteShow[$i]!="") {
									$spalteX=$spaltenName[$i];
									echo $spaltenBreiteShow[$i];
										echo "<p>",date($datumsFormat,strtotime($row->$spalteX)),"</p>";
									echo "</div>";
								}
								break;
						}						
					}
				echo "</div>";
				echo "<hr>";
				}
			} else {
				// EDITIEREN DER TABELLE
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<form action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
						echo "<div class=\"row collapse\">";
							foreach ($spaltenName as $i => $spalte)
							{						
								switch ($spaltenTyp[$i]) 
								{
									case 'auswahl':
										$spalteX=$spaltenName[$i]."ID";
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											$abfrageX="SELECT DISTINCT * FROM ".$spaltenName[$i]." ORDER BY upper(".$spaltenName[$i].")";
											$ergebnisX = mysql_query($abfrageX);
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";											
												echo "<select class=\"medium\" name=\"".$spaltenName[$i]."EDI\">";
													while($rowX = mysql_fetch_object($ergebnisX)) {
														if ($rowX->$spalteX==$eingabeWert) {
															echo "<option selected value=".$rowX->$spalteX.">",$rowX->$spaltenName[$i],"</option>";
														} else {						
															echo "<option value=".$rowX->$spalteX.">",$rowX->$spaltenName[$i],"</option>";
														}
													}
												echo "</select>";
											echo "</div>";
										}
										break;
									case 'auswahlStruktur':
										$spalteX=$spaltenName[$i]."ID";
										$selectedItem=$row->$spalteX;
											if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<select class=\"medium\" name=\"".$spaltenName[$i]."EDI\">";
												generateListOrdnerFormular(0,$selectedItem,$spaltenName[$i],0);
												echo "</select>";
											echo "</div>";
										}
										break;
									case 'previewPic':
										break;
									case 'text':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
									case 'dokurl':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<div class=\"row collapse\">";
													echo "<div class=\"small-10 columns\">";
														echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
													echo "</div>";
													echo "<div class=\"small-2 columns\">";
														echo "<a class=\"button postfix\" href=\"sub_openFile.php?id=".$row->id."\" onclick=\"FensterOeffnen(this.href); return false\"><i class=\"fi-download size-X\"></i></a>";												
													echo "</div>";
												echo "</div>";
											echo "</div>";
										}
										break;
									case 'url':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
									case 'zahl':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
									case 'datum':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenBreiteShow[$i]!="") {
											echo $spaltenBreiteShow[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".date($datumsFormat,strtotime($eingabeWert))."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
								}						
							}
							echo "<input type=\"hidden\" value=\"2\" name=\"fileuploadADD\">"; //fileupload 2 --> daten ändern; fileupload 1 --> neue Daten anlegen
							echo "<input type=\"hidden\" value=\"".$row->id."\" name=\"idEDI\">";							
							echo "<button class=\"step fi-page-edit expand size-X\" type=\"Submit\"></button>";
						echo "</div>";
					echo "</form>";
					echo "<hr>";
				}
			}
		?>
		<div class="row">
			<div class="pagination-centered">
				<ul class="pagination">		
					<?php
						echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage-$maxEintraegeProSite,"\">&laquo;</a></li>";
						for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
							if($i>=$startPage and $i <$startPage+$maxEintraegeProSite){
								echo "<li class=\"current\"><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
							}
							else{
								echo "<li><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
							}
						}
						echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage+$maxEintraegeProSite,"\">&raquo;</a></li>";							
					?>
				</ul>
			</div>
		</div>
	</fieldset>
</div>

<!-- Suchformular -->
<div id="suchModal" class="reveal-modal" data-reveal>
	<fieldset>
		<legend>Suche</legend>
		<?php
			include("sub_suche_add_button.php");
		?>		
	</fieldset>
</div>

<!-- Formular neue Datei -->
<div id="newFileModal" class="reveal-modal" data-reveal>
	<fieldset>
	<legend>neues Dokument</legend>
		<?php
			include("sub_addfile_add_button.php");
		?>		
	</fieldset>
</div>


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
