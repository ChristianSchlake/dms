<?php
	function checkDateX($datumString) {
        $datum=explode(".",$datumString);
        if (checkdate($datum[1],$datum[0],$datum[2])==true) {
        	return true;
        } else {
        	return false;
        }
	}

	function generateListOrdnerFormular($root_id = 0, $selectedItem, $tabelle) {
		global $auswahl_ordner;
		$abfrage="
			SELECT DISTINCT
				a.son,
				b.".$tabelle."
			FROM ".$tabelle."Structure AS a
			INNER JOIN ".$tabelle." AS b
				ON b.".$tabelle."ID = a.son
			where a.father = $root_id
		";
		$ergebnis = mysql_query($abfrage);
		while($row = mysql_fetch_object($ergebnis)) {
			$abfrage_sohn="
				SELECT DISTINCT
					a.son,
					b.".$tabelle."ID
				FROM ".$tabelle."Structure AS a
				INNER JOIN ".$tabelle." AS b
					ON b.".$tabelle."ID = a.son
				where a.father = $row->son
			";
			$ergebnis_sohn=mysql_query($abfrage_sohn);
			$zahl=mysql_num_rows($ergebnis_sohn);
			if($zahl>0) {
				echo "<option DISABLED>",$row->$tabelle,"</option>";
				generateListOrdnerFormular($row->son, $selectedItem, $tabelle);
			   }
			else {
				//echo $abfrage_sohn;
				$name=$row->son;
				if($name==$selectedItem) {					
					echo "<option selected value=".$row->son.">",$row->$tabelle,"</option>";
				}
				else {
					echo "<option value=".$row->son.">",$row->$tabelle,"</option>";
				}
			}
		}
	}
			
/*	function checkAutoIncrementStatus($spalte) {
		// prüft ob eine Spalte angezeigt werden soll
		$abfrage="SHOW FIELDS FROM typenDefinition where Extra like \"auto_increment\" AND Field like \"".$spalte."\"";
		$ergebnis=mysql_query($abfrage);
		$zahl=mysql_num_rows($ergebnis);
		return $zahl;
	}*/
	
	function getSpaltenDMS() {
		global $spaltenName, $spaltenTyp, $spaltenBeschreibung, $spaltenSuchwert, $spaltenID, $spaltenEingabewert, $spaltenFormularAnzeige, $spaltenBreite;
		$spaltenName=array();
		$spaltenTyp=array();
		$spaltenBeschreibung=array();
		$spaltenSuchwert=array();	
		$spaltenID=array();
		$spaltenEingabewert=array();
		$spaltenFormularAnzeige=array();
		$spaltenBreite=array();
		// Spalten ermitteln
		$result = mysql_query("SELECT * FROM typenDefinition ORDER BY reihenfolge");
		while ($row = mysql_fetch_object($result)) {
			$spaltenName[]=$row->name;
			$spaltenTyp[]=$row->typ;
			$spaltenBeschreibung[]=$row->beschreibung;
			$spaltenSuchwert[]=$row->suchwert;
			$spaltenID[]=$row->id;
			$spaltenEingabewert[]=$row->eingabewert;
			$spaltenFormularAnzeige[]=$row->eingabeFormular;
			$spaltenBreite[]=$row->spaltenbreite;
		}
		foreach ($spaltenBreite as $i => $value) {
			$breite=explode(",", $spaltenBreite[$i]);
			if ($breite[0]>0 and $breite[1]>0 and $breite[0]<13 and $breite[1]<13 and is_numeric($breite[0])==true and is_numeric($breite[1])==true) {
				$spaltenBreite[$i]="<div class=\"small-".$breite[1]." large-".$breite[0]." columns\">";
//				$spaltenBreite[$i]="<div class=\"small-8 large-3 columns\">";	
			}
		}
	}
	
	function reset_suche() {
		global $spaltenTyp, $spaltenName;
		getSpaltenDMS();
		$aufruf="";
		foreach ($spaltenName as $i => $spalte) {
			switch ($spaltenTyp[$i]) {
				case 'auswahl':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'auswahlStruktur':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'zahl':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'text':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'datum':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
			}
		}
		$aufruf=substr($aufruf, 1);
		header("Location: main_suche.php?".$aufruf);
	}
	
	
	function generateListOrdner($root_id = 0) {
		$abfrage="
			SELECT DISTINCT
				a.son,
				b.name
			FROM dirStructure AS a
			INNER JOIN dir AS b
				ON b.id = a.son
			where a.father = $root_id
		";
		$ergebnis = mysql_query($abfrage);
		while($row = mysql_fetch_object($ergebnis)) {
			/*echo "<li><label>",$row->name,"</label></li>";*/
			$abrage_sohn="
				SELECT DISTINCT
					a.son,
					b.name
				FROM dirStructure AS a
				INNER JOIN dir AS b
					ON b.id = a.son
				where a.father = $row->son
			";
			$ergebnis_sohn=mysql_query($abrage_sohn);
			$zahl=mysql_num_rows($ergebnis_sohn);
			if($zahl>0) {
				echo "<li class=\"has-dropdown\"><a href=\"?ordner=",$row->son,"\">",$row->name,"</a>";
					echo "<ul class=\"dropdown\">";
						generateListOrdner($row->son);
					echo "</ul>";
				echo "</li>";
			   }
			else {
				echo "<li><a href=\"?ordner=",$row->son,"\">",$row->name,"</a></li>";
			   }

		}
	}
	function abfrageEinstellung($einstellung) {
		$abfrage="SELECT DISTINCT * FROM typenDefinition WHERE name='".$einstellung."' ORDER BY reihenfolge";
		$ergebnis = mysql_query($abfrage);
		while($row = mysql_fetch_object($ergebnis)) {
			$result=$row->suchwert;
		}
		return $result;
	}
	function abfrageEinstellungADDFile($einstellung) {
		$abfrage="SELECT DISTINCT * FROM typenDefinition WHERE name='".$einstellung."' ORDER BY reihenfolge";
		$ergebnis = mysql_query($abfrage);
		while($row = mysql_fetch_object($ergebnis)) {
			$result=$row->eingabewert;
		}
		return $result;
	}
	
?>