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
			echo "<div class=\"row\">";
				echo "<div class=\"small-6 large-1 columns\">";
					// ID				
					echo "<label>",$row->id,"</label>";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row->id."\"\>";
				echo "</div>";
				echo "<div class=\"small-6 large-1 columns\">";
					// Typ
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
				echo "</div>";
				echo "<div class=\"small-12 large-4 columns\">";
					// Beschreibung
					echo "<input type=\"text\" value=\"",$row->Beschreibung,"\" size=\"300\" name=\"Beschreibung\">";
				echo "</div>";
				echo "<div class=\"small-6 large-3 columns\">";
					// Ordner
					echo "<select class=\"medium\" name=\"ordner\">";
						generateListOrdnerFormular(0, $row->name);
					echo "</select>";
					echo "<br>";	
					// Herausgeber
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
					echo "</select>";
				echo "</div>";
				echo "<div class=\"small-6 large-2 columns\">";
					// Herausgabedatum
					echo "<input type=\"text\" value=\"",date("d.m.Y",strtotime($row->Herausgabedatum)),"\" name=\"herausgabedatum\">";
					// Speicherdatum
					echo "<br>";
					echo "<label>",date("d.m.Y",strtotime($row->Speicherdatum)),"</label>";
				echo "</div>";
				echo "<div class=\"small-6 large-1 columns\">";
					// Button anzeigen
					$extension=explode(".",$row->Datei);
					echo "<a class=\"tiny button round\" href=\"upload/",$row->id,".",$extension[count($extension)-1],"\">file</a>";
					echo "<br>";
					// Button ändern
					echo "<input class=\"tiny button round\" type=\"submit\" value=\"ändern\">";
				echo "</div>";
				echo "<hr />";
			echo "</div>";
		echo "</form>";
	}
?>