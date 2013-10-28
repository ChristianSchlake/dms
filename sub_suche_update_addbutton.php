<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- Functions -->
<?php
	function generateListOrdnerFormular($root_id = 0, $sel) {
		global $auswahl_ordner;
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
				echo "<option DISABLED>",$row->name,"</option>";
				generateListOrdnerFormular($row->son, $sel);
			   }
			else {
				$name=$row->name;
				if($name==$sel) {
					echo "<option selected>",$row->name,"|",$row->son,"</option>";
				}
				else {
					echo "<option>",$row->name,"|",$row->son,"</option>";
				}
			}
		}
	}
?>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

<?php
// Abfrage Typ
$abfrageTMP="
	SELECT DISTINCT
		TypName,
		TypID
	FROM typ";
	$ergebnisTMP = mysql_query($abfrageTMP);
	$i=0;
	while($rowTMP = mysql_fetch_object($ergebnisTMP)) {
		$listeTyp[$i]=$rowTMP->TypName."|".$rowTMP->TypID;
		$i++;
	}
// Herausgeber
	$abfrageTMP="SELECT DISTINCT Herausgeber,HerausgeberID FROM Herausgeber";
	$ergebnisTMP = mysql_query($abfrageTMP);
	$i=0;
	while($rowTMP = mysql_fetch_object($ergebnisTMP)) {
		$listeHerausgeber[$i]=$rowTMP->Herausgeber."|".$rowTMP->HerausgeberID;
		$i++;
	}


	while($row = mysql_fetch_object($ergebnis))							{
		echo "<form class=\"custom\" action=\"sub_suche_update.php\" method=\"post\">";
		echo "<tr>";
			// ID
			echo "<td><label>",$row->id,"</label></td>";
			echo "<input type=\"hidden\" name=\"id\" value=\"".$row->id."\"\>";
			// Typ
			echo "<td>";
				echo "<select class=\"medium\" name=\"typ\">";				
				foreach($listeTyp AS $typ)
				{
				$typName=explode("|",$typ);
					if($typName[0]==$row->TypName){
						echo "<option selected>",$typ,"</option>";
					}
					else {
						echo "<option>",$typ,"</option>";
					}
				}
				echo "</select>";
			echo "</td>";
			// Beschreibung
			echo "<td><input type=\"text\" value=\"",$row->Beschreibung,"\" size=\"300\" name=\"Beschreibung\"></td>";
			// Ordner
			echo "<td>";
				echo "<select class=\"medium\" name=\"ordner\">";
				generateListOrdnerFormular(0, $row->name);
				echo "<\select>";
			echo "</td>";
			// Herausgeber
			echo "<td>";
				echo "<select class=\"medium\" name=\"herausgeber\">";

				foreach($listeHerausgeber AS $Herausgeber)
				{
					if($Herausgeber==$row->Herausgeber."|".$row->HerausgeberID){
						echo "<option selected>",$Herausgeber,"</option>";
					}
					else {
						echo "<option>",$Herausgeber,"</option>";
					}
				}
				echo "<\select>";
			echo "</td>";
			// Herausgabedatum
			echo "<td><input type=\"text\" value=\"",date("d.m.Y",strtotime($row->Herausgabedatum)),"\" name=\"herausgabedatum\"></td>";
			// Speicherdatum
			echo "<td><label>",date("d.m.Y",strtotime($row->Speicherdatum)),"</label></td>";
			// Button anzeigen
			$extension=explode(".",$row->Datei);
			echo "<td><a class=\"tiny button round\" href=\"upload/",$row->id,".",$extension[count($extension)-1],"\">file</a></td>";
			// Button ändern
			echo "<td><input class=\"tiny button round\" type=\"submit\" value=\"",ändern,"\"></td>";
		echo "</tr>";
		echo "</form>";
	}
?>
