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

	function getSpaltenDMS() {
		global $spaltenName, $spaltenTyp, $spaltenBeschreibung, $spaltenSuchwert, $spaltenID, $spaltenEingabewert, $spaltenBreiteShow, $spaltenBreiteSuchFormular, $spaltenBreiteNeuesDokumentFormular;
		$spaltenName=array();
		$spaltenTyp=array();
		$spaltenBeschreibung=array();
		$spaltenSuchwert=array();	
		$spaltenID=array();
		$spaltenEingabewert=array();
		$spaltenBreiteShow=array();
		$spaltenBreiteSuchFormular=array();
		$spaltenBreiteNeuesDokumentFormular=array();
		$editStatus=abfrageEinstellung("editStatus");
		// Spalten ermitteln
		$result = mysql_query("SELECT * FROM typenDefinition ORDER BY reihenfolge");
		while ($row = mysql_fetch_object($result)) {
			$spaltenName[]=$row->name;
			$spaltenTyp[]=$row->typ;
			$spaltenBeschreibung[]=$row->beschreibung;
			$spaltenSuchwert[]=$row->suchwert;
			$spaltenID[]=$row->reihenfolge;
			$spaltenEingabewert[]=$row->eingabewert;
			$spaltenBreiteSuchFormular[]=$row->spaltenbreiteSuchFormular;
			$spaltenBreiteNeuesDokumentFormular[]=$row->spaltenbreiteNeuesDokumentFormular;
			if ($editStatus==0) {
				$spaltenBreiteShow[]=$row->spaltenbreiteShow;
			} else {
				$spaltenBreiteShow[]=$row->spaltenbreiteEdit;
			}
		}
		foreach ($spaltenBreiteShow as $i => $value) {
			if ($value!="") {
				$breite=explode(",", $spaltenBreiteShow[$i]);
				if ($breite[0]>0 and $breite[1]>0 and $breite[0]<13 and $breite[1]<13 and is_numeric($breite[0])==true and is_numeric($breite[1])==true) {
					$spaltenBreiteShow[$i]="<div class=\"small-".$breite[1]." large-".$breite[0]." columns\">";
				}
			}
		}
		foreach ($spaltenBreiteSuchFormular as $i => $value) {
			if ($value!="") {
				$breite=explode(",", $spaltenBreiteSuchFormular[$i]);
				if ($breite[0]>0 and $breite[1]>0 and $breite[0]<13 and $breite[1]<13 and is_numeric($breite[0])==true and is_numeric($breite[1])==true) {
					$spaltenBreiteSuchFormular[$i]="<div class=\"small-".$breite[1]." large-".$breite[0]." columns\">";
				}
			}
		}
		foreach ($spaltenBreiteNeuesDokumentFormular as $i => $value) {
			if ($value!="") {
				$breite=explode(",", $spaltenBreiteNeuesDokumentFormular[$i]);
				if ($breite[0]>0 and $breite[1]>0 and $breite[0]<13 and $breite[1]<13 and is_numeric($breite[0])==true and is_numeric($breite[1])==true) {
					$spaltenBreiteNeuesDokumentFormular[$i]="<div class=\"small-".$breite[1]." large-".$breite[0]." columns\">";
				}
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
				case 'previewPic':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'zahl':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'text':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'url':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'dokurl':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;
				case 'datum':
					$aufruf=$aufruf."&".$spaltenName[$i]."=%";
					break;					
			}
		}
		$aufruf=substr($aufruf, 1);
		$aufruf=$aufruf."&startPage=0";
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

	function getFilenameByID($id) {
		foreach (glob("upload/".$id.".*") as $filename) {
	    	return $filename;
		}
	}
	
?>
