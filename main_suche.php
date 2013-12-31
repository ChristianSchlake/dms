<?php
//	session_start();
	include("sub_init_database.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>DMS</title>

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

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- Functions -->
<?php
	include("functions.php");
?>

<?php	
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	foreach ($_GET as $key => $value) {
		if ($key=="aufruf") {
			reset_suche();
		}
//		echo $key,"-->",$value,"<br>";
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
		foreach ($spaltenEingabewert as $i => $spalte) {
			switch ($spaltenTyp[$i]) {
				case "zahl":
					if ($spaltenFormularAnzeige[$i]==1) {					
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "auswahlStruktur":
					if ($spaltenFormularAnzeige[$i]==1) {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}					
					}
					break;
				case "auswahl":
					if ($spaltenFormularAnzeige[$i]==1) {					
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}					
					}
					break;
				case "text":
					if ($spaltenFormularAnzeige[$i]==1) {
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "datum":
					if ($spaltenFormularAnzeige[$i]==1) {
						if (checkDateX($spalte)!=true) {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
			}
		}		
		if ($allesRichtig==true) {
			if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
				// neue ID ermitteln
				$MaxID=mysql_query("SELECT MAX(id) FROM DMS");
				$MaxID=mysql_fetch_array($MaxID, MYSQL_BOTH);
				$MaxID=$MaxID[0];
				$MaxID=$MaxID+1;
				// Dateinamen ersetllen
				$path_parts=pathinfo($_FILES['userfile']['name']);
				$extension=$path_parts['extension'];
				$datei="upload/".$MaxID.".".$extension;
				move_uploaded_file($_FILES['userfile']['tmp_name'], $datei);
				$spaltenClause="";
				$spaltenValueClause="";
				getSpaltenDMS();
				foreach ($spaltenName as $i => $spalte) {
					
					switch ($spaltenTyp[$i]) {
						case 'einstellung':
							break;
						case 'datum':
							if ($spaltenFormularAnzeige[$i]==1) {
								$spaltenClause=$spaltenClause.",".$spaltenName[$i];
								$spaltenValueClause=$spaltenValueClause.",STR_TO_DATE('".$spaltenEingabewert[$i]."', '%d.%m.%Y')";								
							}
							break;
						default:
							if ($spaltenFormularAnzeige[$i]==1) {
								$spaltenClause=$spaltenClause.",".$spaltenName[$i];
								$spaltenValueClause=$spaltenValueClause.",\"".$spaltenEingabewert[$i]."\"";
							}
							break;
					}
				}
				$spaltenClause=substr($spaltenClause,1);
				$spaltenValueClause=substr($spaltenValueClause,1);
				$aufruf="INSERT INTO DMS (".$spaltenClause.",Datei,id) VALUES (".$spaltenValueClause.",\"".$_FILES['userfile']['name']."\",".$MaxID.")";
//				echo $aufruf;
				$eintragen = mysql_query($aufruf);
			} else {
				echo "<span class=\"round alert label\">Fehler beim Dateiupload</span>";
			}
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
					if ($spaltenFormularAnzeige[$i]==1) {					
						if ($spalte=="") {
							echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
							$allesRichtig=false;
						}
					}
					break;
				case "auswahlStruktur":
					if ($spalte=="") {
						echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
						$allesRichtig=false;
					}					
					break;
				case "auswahl":
					if ($spalte=="") {
						echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
						$allesRichtig=false;
					}					
					break;
				case "text":
					if ($spalte=="") {
						echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
						$allesRichtig=false;
					}
					break;
				case "datum":
					if (checkDateX($spalte)!=true) {
						echo "<span class=\"round alert label\">Fehler ".$spaltenBeschreibung[$i]." nicht angegeben</span>";
						$allesRichtig=false;						
					}
					break;
			}
		}
		if ($allesRichtig==true) {
			$abfrage="UPDATE DMS SET dir=".$ordner.",TypID=".$Typ.",Beschreibung=\"".$Beschreibung."\",Herausgeber=".$herausgeber.",Herausgabedatum=STR_TO_DATE('".$herausgabedatum."', '%d.%m.%Y') WHERE id=".$id; 
			foreach ($spaltenName as $i => $spalte) {				
				switch ($spaltenTyp[$i]) {
					case 'einstellung':
						break;
					case 'datum':
						if ($spaltenFormularAnzeige[$i]==1) {
							$spaltenClause=$spaltenClause.",".$spaltenName[$i]."=STR_TO_DATE('".$spaltenEingabewert[$i]."', '%d.%m.%Y')";
						}
						break;
					default:
						if ($spaltenFormularAnzeige[$i]==1) {						
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
			<h1><a href="main_suche.php?aufruf=1"><i class="fi-refresh "></i>  DMS</a></h1>
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
								}
							}
						?>
	                </ul>
	        </li>
			<li class="divider hide-for-small"></li>			
			<a class="button secondary round" href="main_suche.php" data-reveal-id="suchModal"><i class="fi-page-search"></i> suchen</a>
		</ul>
		<ul class="right">
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
<div class="row">
	<fieldset>
		<legend>DMS Tabelle</legend>
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
		echo "<div class=\"row\">";
			foreach ($spaltenName as $i => $spalte) {
				// Aufteilung im Grid festelgen (Gilt auf für die Spaltendarstellung weiter unten)
				switch ($spaltenTyp[$i]) {
					case 'auswahl':
						$gridAuswahl="<div class=\"small-8 large-3 columns\">";
						// Sortierung
						$b=chr(64+$spaltenID[$i]);
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$b.".".$spaltenName[$i];
						}
						break;
					case 'auswahlStruktur':
						$gridAuswahlStruktur="<div class=\"small-8 large-3 columns\">";
						// Sortierung
						$b=chr(64+$spaltenID[$i]);
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$b.".".$spaltenName[$i];
						}
						break;
					case 'text':
						$gridText="<div class=\"small-12 large-8 columns\">";
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$spaltenName[$i];
						}
						break;
					case 'zahl':
						$gridZahl="<div class=\"small-3 large-1 columns\">";
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$spaltenName[$i];
						}
						break;
					case 'datum':
						$gridDatum="<div class=\"small-6 large-3 columns\">";
						// Sortierung						
						if ($sortierung==$spaltenName[$i]) {
							$sortClause=" ORDER BY ".$spaltenName[$i];
						}
						break;						
				}
				// Button einfügen			
				switch ($spaltenTyp[$i]) {
					case 'auswahl':
						echo $spaltenBreite[$i];
						if($sortierung==$spaltenName[$i]) {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small\">".$spaltenBeschreibung[$i]."</a></th>";								
						} else {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small secondary\">".$spaltenBeschreibung[$i]."</a></th>";
						}
						echo "</div>";
						break;
					case 'auswahlStruktur':
						echo $spaltenBreite[$i];
						if($sortierung==$spaltenName[$i]) {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small\">".$spaltenBeschreibung[$i]."</a></th>";								
						} else {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small secondary\">".$spaltenBeschreibung[$i]."</a></th>";
						}
						echo "</div>";
						break;
					case 'text':
						echo $spaltenBreite[$i];
						if($sortierung==$spaltenName[$i]) {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small\">".$spaltenBeschreibung[$i]."</a></th>";								
						} else {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small secondary\">".$spaltenBeschreibung[$i]."</a></th>";
						}
						echo "</div>";
						break;
					case 'zahl':						
						echo $spaltenBreite[$i];
						if($sortierung==$spaltenName[$i]) {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small\">".$spaltenBeschreibung[$i]."</a></th>";								
						} else {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small secondary\">".$spaltenBeschreibung[$i]."</a></th>";
						}
						echo "</div>";
						break;
					case 'datum':
						echo $spaltenBreite[$i];
						if($sortierung==$spaltenName[$i]) {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small\">".$spaltenBeschreibung[$i]."</a></th>";								
						} else {
							echo "<th><a href=\"main_suche.php?sortierung=".$spaltenName[$i]."&sortierfolge=".$sortierfolgeNEU."\" class=\"button small secondary\">".$spaltenBeschreibung[$i]."</a></th>";
						}
						echo "</div>";
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
			$innerJoin="SELECT ".$selectClause.",id, Datei FROM DMS AS Z".$innerJoin." WHERE ";
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
				while($row = mysql_fetch_object($ergebnis))
				{
					echo "<div class=\"row\">";
					foreach ($spaltenName as $i => $spalte)
					{
						$extension=explode(".",$row->Datei);
						switch ($spaltenTyp[$i])
						{
							case 'auswahl':
								$spalteX=$spaltenName[$i];
								echo $spaltenBreite[$i];
									echo "<p>",$row->$spalteX,"</p>";
								echo "</div>";
								break;
							case 'auswahlStruktur':
								$spalteX=$spaltenName[$i];
								echo $spaltenBreite[$i];
									echo "<p>",$row->$spalteX,"</p>";
								echo "</div>";
								break;
							case 'text':
								$spalteX=$spaltenName[$i];
								echo $spaltenBreite[$i];
									echo "<p>",$row->$spalteX,"</p>";
								echo "</div>";
								break;
							case 'zahl':
								$spalteX=$spaltenName[$i];
								echo $spaltenBreite[$i];
									echo "<p>",$row->$spalteX,"</p>";
								echo "</div>";
								break;
							case 'datum':
								$spalteX=$spaltenName[$i];
								echo $spaltenBreite[$i];
									echo "<p>",date($datumsFormat,strtotime($row->$spalteX)),"</p>";
								echo "</div>";
								break;
						}						
					}
					echo "<div class=\"small-12 large-12 columns\">";
						echo "<a class=\"button secondary step fi-download expand size-X\" href=\"upload/",$row->id,".",$extension[count($extension)-1],"\"></a>";
					echo "</div>";
				echo "</div>";
				echo "<hr>";
				}
			} else {
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<div class=\"row\">";
						echo "<form class=\"custom\" action=\"main_suche.php\" method=\"post\">";
							foreach ($spaltenName as $i => $spalte)
							{						
								switch ($spaltenTyp[$i]) 
								{
									case 'auswahl':
										$spalteX=$spaltenName[$i]."ID";
										$eingabeWert=$row->$spalteX;
										if ($spaltenFormularAnzeige[$i]==1) {											
											$abfrageX="SELECT DISTINCT * FROM ".$spaltenName[$i]." ORDER BY ".$spaltenName[$i];
											$ergebnisX = mysql_query($abfrageX);
											echo $spaltenBreite[$i];
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
										if ($spaltenFormularAnzeige[$i]==1) {										
											echo $spaltenBreite[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<select class=\"medium\" name=\"".$spaltenName[$i]."EDI\">";
												generateListOrdnerFormular(0,$selectedItem,$spaltenName[$i]);
												echo "</select>";
											echo "</div>";
										}
										break;
									case 'text':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenFormularAnzeige[$i]==1) {										
											echo $spaltenBreite[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
									case 'zahl':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenFormularAnzeige[$i]==1) {										
											echo $spaltenBreite[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
									case 'datum':
										$spalteX=$spaltenName[$i];
										$eingabeWert=$row->$spalteX;
										if ($spaltenFormularAnzeige[$i]==1) {										
											echo $spaltenBreite[$i];
												echo "<label>".$spaltenBeschreibung[$i]."</label>";
												echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".date($datumsFormat,strtotime($eingabeWert))."\" name=\"".$spaltenName[$i]."EDI\">";
											echo "</div>";
										}
										break;
								}						
							}
							echo "<input type=\"hidden\" value=\"2\" name=\"fileuploadADD\">";
							echo "<input type=\"hidden\" value=\"".$row->id."\" name=\"idEDI\">";
							$extension=explode(".",$row->Datei);
							echo "<a class=\"button step fi-download expand size-X\" href=\"upload/",$row->id,".",$extension[count($extension)-1],"\"></a>";														
							echo "<button class=\"step fi-page-edit expand size-X\" type=\"Submit\"></button>";
						echo "</form>";
					echo "</div>";
					echo "<hr>";
				}
			}
		?>
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
	<legend>Suche</legend>
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
  
<!--script src="js/vendor/jquery.js"></script>
<script src="http://foundation.zurb.com/docs/assets/js/all.js"></script>
<script>$(document).foundation();</script-->  


	<!--script src="/js/vendor/jquery.js"></script>
	<script src="/js/foundation/foundation.min.js"></script-->
	
</body>
