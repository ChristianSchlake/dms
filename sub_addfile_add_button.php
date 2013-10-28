<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Variablen einlesen-->
<?php
	$auswahl_typ=$_POST['typ'];

	$auswahl_ordner=$_POST['ordner'];
	$auswahl_ordnerID=explode("|",$auswahl_ordner);
	$auswahl_ordnerID=$auswahl_ordnerID[1];

	$auswahl_herausgeber=$_POST['herausgeber'];
	$auswahl_herausgeberID=explode("|",$auswahl_herausgeber);
	$auswahl_herausgeberID=$auswahl_herausgeberID[1];

	$auswahl_beschreibung=$_POST['beschreibung'];
	$auswahl_herausgabedatum=$_POST['herausgabedatum'];
	$datei=$_FILES['userfile']['name'];
?>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- Functions -->
<?php
	function generateListOrdnerFormular($root_id = 0) {
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
				generateListOrdnerFormular($row->son);
			   }
			else {
				$name=$row->name;
				if($name==$auswahl_ordner) {
					echo "<option selected>",$row->name,"|",$row->son,"</option>";
				}
				else {
					echo "<option>",$row->name,"|",$row->son,"</option>";
				}
			}
		}
	}
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- prüfen ob alle Daten eingetragen sind-->
<?php
	$eintrag=0;
	$dateCheck=false;
	$datum=explode(".",$auswahl_herausgabedatum);
	if(checkdate($datum[1],$datum[0],$datum[2])==true){
		$dateCheck=true;
		echo "<h1>True</h1>";
	}

	if(	isset($auswahl_typ) and
		isset($auswahl_ordner) and
		isset($auswahl_herausgeber) and
		isset($auswahl_herausgabedatum) and 
		isset($auswahl_beschreibung) and
		isset($datei))
	{
		if(	$auswahl_typ!="" and
			$auswahl_ordner!="" and
			$auswahl_herausgeber!="" and
			$auswahl_herausgabedatum!="" and
			$auswahl_beschreibung!="" and
			$datei!="")
		{
			$eintrag=1;
		}
	}
	if($eintrag==1) {
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
			$eintragen = mysql_query("INSERT INTO DMS (id,Typ,dir,Herausgeber,Herausgabedatum,Beschreibung,Kategorie,Datei) VALUES (".$MaxID.",\"".$auswahl_typ."\",".$auswahl_ordnerID.",".$auswahl_herausgeberID.",STR_TO_DATE('".$auswahl_herausgabedatum."', '%d.%m.%Y'),\"".$auswahl_beschreibung."\",".$auswahl_ordnerID.",\"".$_FILES['userfile']['name']."\")");
			// prüfen ob alles eingetragen wurde
			$MaxID2=mysql_query("SELECT MAX(id) FROM DMS");
			$MaxID2=mysql_fetch_array($MaxID2, MYSQL_BOTH);
			$MaxID2=$MaxID2[0];
			if($MaxID2==$MaxID){
				header("Location: main_suche.php?id=".$MaxID);	
			}
			else{
				echo "<div class=\"row collapse\">";
				echo "<span class=\"round alert label\">Sie haben das Dokument \"".$_FILES['userfile']['name']."\" mit der ID \"".$MaxID."\" übertragen</span>";
				echo "</div>";
			}
			
		}
		
	}
?>


<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt der Typ als Auswahlliste-->
<form action="main_addfile.php" method="POST" class="custom" enctype="multipart/form-data">
	<div class="row collapse">
		<div class="small-12 large-4 columns">
			<select class="medium" name="typ">
				<?php
					$abfrage="
						SELECT DISTINCT
							S.Typ
						FROM DMS AS S
						INNER JOIN Herausgeber AS Z 
							ON S.Herausgeber = Z.HerausgeberID
						INNER JOIN dir AS Y 	
							ON Y.id = S.dir
					";
					$ergebnis = mysql_query($abfrage);
					while($row = mysql_fetch_object($ergebnis)) {
						if($row->Typ==$auswahl_typ){						
							echo "<option selected>",$row->Typ,"</option>";
						}
						else {
							echo "<option>",$row->Typ,"</option>";
						}
					}				
				?>
			</select>
		</div>
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<!-- Hier kommt die Ordner als Auswahlliste-->
		<div class="small-12 large-4 columns">
			<select class="medium" name="ordner">
				<?php
					generateListOrdnerFormular(0);
				?>
			</select>
		</div>
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<!-- Hier kommt der Herausgeber als Auswahlliste-->
		<div class="small-12 large-4 columns">
			<select class="medium" name="herausgeber">
				<?php
					$abfrage="SELECT DISTINCT Herausgeber,HerausgeberID  FROM Herausgeber";
					$ergebnis = mysql_query($abfrage);
					while($row = mysql_fetch_object($ergebnis)) {
						if($row->Herausgeber==$auswahl_herausgeber){						
							echo "<option selected>",$row->Herausgeber,"|",$row->HerausgeberID,"</option>";
						}
						else {
							echo "<option>",$row->Herausgeber,"|",$row->HerausgeberID,"</option>";
						}
					}
				?>
			</select>
		</div>
	</div>
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<!-- Hier kommt die Beschreibung als Formular-->
	<div class="row collapse">
		<?php
			if($eintrag=="0" and $auswahl_beschreibung=="" and isset($auswahl_beschreibung)) {
				echo "<input type=\"text\" class=\"error\" placeholder=\"Beschreibung\" name=\"beschreibung\" />";
				echo "<small class=\"error\">fehlende Eingabe</small>";
			}
			else {
				echo "<input type=\"text\" placeholder=\"Beschreibung\" value=\"",$auswahl_beschreibung,"\" name=\"beschreibung\">";
			}
		?>
	</div>
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<!-- Hier kommt das Herausgabedatum als Formular-->
	<div class="row collapse">
		<?php
			if($eintrag=="0" and $auswahl_herausgabedatum=="" and isset($auswahl_herausgabedatum)) {
				echo "<input type=\"text\" class=\"error\" placeholder=\"Herausgabedatum [TT.MM.JJJJ]\" name=\"herausgabedatum\">";
				echo "<small class=\"error\">fehlende Eingabe</small>";
			}
			else {
				if($dateCheck==false and $auswahl_herausgabedatum!=""){
					echo "<input type=\"text\" class=\"error\" placeholder=\"Herausgabedatum [TT.MM.JJJJ]\" name=\"herausgabedatum\">";
					echo "<small class=\"error\">bitte geben Sie das Datum im Format TT.MM.JJJJ an</small>";
				}
				else{
					echo "<input type=\"text\" placeholder=\"Herausgabedatum [TT.MM.JJJJ]\" value=\"",$auswahl_herausgabedatum,"\" name=\"herausgabedatum\">";
				}
			}
		?>
	</div>
	<div class="row collapse">
		<fieldset>
			<legend>File Upload</legend>
				<input type="file" name="userfile"/>
				<?php
				if($datei=="" and isset($auswahl_ordner)){
					echo "<small class=\"error\">fehlende Eingabe</small>";
				}
				?>
		</fieldset>
	</div>
	<div class="row collapse">
		<button class="button expand" type="Submit">Dokument einchecken</button>
	</div>
</form>


