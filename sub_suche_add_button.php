

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt der Typ als Auswahlliste-->
<div class="row collapse">
	<div class="small-12 large-4 columns">
		<?php
			if($auswahl_Typ!="%"){
				echo "<a href=\"#\" data-dropdown=\"drop1\" class=\"tiny button dropdown\">",$auswahl_Typ,"</a>";
			}
			else {
				echo "<a href=\"#\" data-dropdown=\"drop1\" class=\"tiny button dropdown secondary\">Typ</a>";
			}
		?>
		<ul id="drop1" class="f-dropdown">
			<?php
				if($auswahl_Typ!="%"){echo "<li><a href=\"main_suche.php?Typ=%\">&rarr; reset </a></li>";}
				$abfrage="
					SELECT DISTINCT
						U.TypName
					FROM DMS AS S
					INNER JOIN Herausgeber AS Z 
						ON S.Herausgeber = Z.HerausgeberID
					INNER JOIN dir AS Y 	
						ON Y.id = S.dir
					INNER JOIN typ AS U 	
						ON U.TypID = S.TypID						
						
					WHERE 
							Y.id LIKE '$auswahl_ordner'
						AND U.TypName LIKE '$auswahl_Typ'
						AND S.Speicherdatum Like '$auswahl_Datum'
						AND S.Herausgabedatum >= STR_TO_DATE('$auswahl_DatumAusgabeVon', '%d.%m.%Y')
						AND S.Herausgabedatum <= STR_TO_DATE('$auswahl_DatumAusgabeBis', '%d.%m.%Y')
						AND S.Beschreibung LIKE '$auswahl_Beschreibung'
						AND S.Herausgeber LIKE '$auswahl_Herausgeber'
						ORDER BY Z.herausgeber
				";
				$ergebnis = mysql_query($abfrage);
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<li><a href=\"main_suche.php?Typ=",utf8_encode($row->TypName),"\">",utf8_encode($row->TypName),"</a></li>";
				}				
			?>
		</ul>
	</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt die Ordner als Auswahlliste-->
	<div class="small-12 large-4 columns">
		<?php
			$abfrage="SELECT DISTINCT name FROM dir WHERE id=".$auswahl_ordner;
			$ergebnis = mysql_query($abfrage);
			while($row = mysql_fetch_object($ergebnis)) {
				$ordnerName=$row->name;
			}

			if($auswahl_ordner!="%") {
				echo "<a href=\"#\" data-dropdown=\"drop2\" class=\"tiny button dropdown\">",$ordnerName,"</a>";
			}
			else {
				echo "<a href=\"#\" data-dropdown=\"drop2\" class=\"tiny button dropdown secondary\">Ordner</a>";
			}
		?>
		<ul id="drop2" class="f-dropdown">
		<?php
			if($auswahl_ordner!="%"){echo "<li><a href=\"main_suche.php?ordner=%\">&rarr; reset </a></li>";}
			$abfrage="
				SELECT DISTINCT
					Y.name,
					Y.id
				FROM DMS AS S
				INNER JOIN Herausgeber AS Z 
					ON S.Herausgeber = Z.HerausgeberID
				INNER JOIN dir AS Y 	
					ON Y.id = S.dir
				INNER JOIN typ AS U 	
					ON U.TypID = S.TypID					
				WHERE 
						Y.id LIKE '$auswahl_ordner'
					AND U.TypName LIKE '$auswahl_Typ'
					AND S.Speicherdatum Like '$auswahl_Datum'
					AND S.Herausgabedatum >= STR_TO_DATE('$auswahl_DatumAusgabeVon', '%d.%m.%Y')
					AND S.Herausgabedatum <= STR_TO_DATE('$auswahl_DatumAusgabeBis', '%d.%m.%Y')
					AND S.Beschreibung LIKE '$auswahl_Beschreibung'
					AND S.Herausgeber LIKE '$auswahl_Herausgeber'
					ORDER BY Y.name
			";
			$ergebnis = mysql_query($abfrage);
			while($row = mysql_fetch_object($ergebnis)) {
				echo "<li><a href=\"main_suche.php?ordner=",$row->id,"\">",utf8_encode($row->name),"</a></li>";
			}
		?>
		</ul>
	</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt die Herausgeber als Auswahlliste-->
	<div class="small-12 large-4 columns">
		<?php
			$abfrage="SELECT DISTINCT Herausgeber FROM Herausgeber WHERE HerausgeberID=".$auswahl_Herausgeber;
			$ergebnis = mysql_query($abfrage);
			while($row = mysql_fetch_object($ergebnis)) {
				$herausgeberName=$row->Herausgeber;
			}

			if($auswahl_Herausgeber!="%") {
				echo "<a href=\"#\" data-dropdown=\"drop3\" class=\"tiny button dropdown\">",$herausgeberName,"</a>";
			}
			else {
				echo "<a href=\"#\" data-dropdown=\"drop3\" class=\"tiny button dropdown secondary\">Herausgeber</a>";
			}
		?>
		<ul id="drop3" class="f-dropdown" data-dropdown-content>
			<?php
				if($auswahl_Herausgeber!="%"){echo "<li><a href=\"main_suche.php?herausgeber=%\">&rarr; reset </a></li>";}
				$abfrage="
					SELECT DISTINCT
						Z.Herausgeber,
						Z.Herausgeberid
					FROM DMS AS S
					INNER JOIN Herausgeber AS Z 
						ON S.Herausgeber = Z.HerausgeberID
					INNER JOIN dir AS Y 	
						ON Y.id = S.dir
					INNER JOIN typ AS U 	
						ON U.TypID = S.TypID						
					WHERE 
							Y.id LIKE '$auswahl_ordner'
						AND U.TypName LIKE '$auswahl_Typ'
						AND S.Speicherdatum Like '$auswahl_Datum'
						AND S.Herausgabedatum >= STR_TO_DATE('$auswahl_DatumAusgabeVon', '%d.%m.%Y')
						AND S.Herausgabedatum <= STR_TO_DATE('$auswahl_DatumAusgabeBis', '%d.%m.%Y')
						AND S.Beschreibung LIKE '$auswahl_Beschreibung'
						AND S.Herausgeber LIKE '$auswahl_Herausgeber'
						ORDER BY Z.Herausgeber
				";
				$ergebnis = mysql_query($abfrage);
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<li><a href=\"main_suche.php?herausgeber=",$row->Herausgeberid,"\">",utf8_encode($row->Herausgeber),"</a></li>";
				}
			?>
		</ul>
	</div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt die Beschreibung als Formular-->
<div class="row collapse">
	<form action="main_suche.php" method="get">
		<div class="row collapse">
			<div class="small-8 columns">
				<input type="text" placeholder="Suche nach der Beschreibung\" name="Beschreibung">
			</div>
				<div class="small-4 columns">
				<?php
					if($auswahl_Beschreibung!="%") {
						echo "<input class=\"button prefix\" value=\"",$auswahl_Beschreibung,"\" type=\"Submit\">";
					}
					else {
						echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
					}
				?>

			</div>
		</div>
	</form>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Hier kommt das Herausgabedatum als Formular-->
<form action="main_suche.php" method="get">
	<div class="row collapse">
		<div class="small-12 large-4 columns">
				<input type="text" placeholder="Herausgabedatum von" name="herausgabeDatumVon">
		</div>
		<div class="small-12 large-4 columns">
			<input type="text" placeholder="Herausgabedatum bis" name="herausgabeDatumBis">
		</div>
		<div class="small-12 large-4 columns">
			<?php
				if($auswahl_DatumAusgabeVon!="01.01.1000" or $auswahl_DatumAusgabeBis!="01.01.9999") {
					echo "<input class=\"button prefix\" value=\"",$auswahl_DatumAusgabeVon," - ",$auswahl_DatumAusgabeBis,"\" type=\"Submit\">";
				}
				else {
					echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
				}
			?>
	</div>
</form>

<!-- Hier kommt das Speicherdatum als Formular-->
<form action="main_suche.php" method="get">
	<div class="row collapse">
		<div class="small-12 large-4 columns">
				<input type="text" placeholder="Speicherdatum von" name="speicherdatumVon">
		</div>
		<div class="small-12 large-4 columns">
			<input type="text" placeholder="Speicherdatum bis" name="speicherdatumBis">
		</div>
		<div class="small-12 large-4 columns">
			<?php
				if($auswahl_DatumAusgabeVon!="01.01.1000" or $auswahl_DatumAusgabeBis!="01.01.9999") {
					echo "<input class=\"button prefix\" value=\"",$auswahl_DatumSpeicherVon," - ",$auswahl_DatumSpeicherBis,"\" type=\"Submit\">";
				}
				else {
					echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
				}
			?>
	</div>
</form>


