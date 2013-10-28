<?php
	session_start();
	include("sub_init_database.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>DMS</title>

	<link rel="stylesheet" href="css/foundation.css">

	<script src="/js/vendor/custom.modernizr.js"></script>


	<script>
		document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
	</script>

</head>

<body>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- Functions -->
<?php
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

/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen einlesen */
	include("sub_suche_load_variablen.php");
?>


<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
<nav class="top-bar" data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="sub_suche_reset_session.php">DMS</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">								
			<!-- Navigation -->
			<li class="divider hide-for-small"></li>
			<li class="has-dropdown"><a href="#">Navigation</a>
				<ul class="dropdown">
					<li><a href=main_verwalte_Herausgeber.php>Herausgeber verwalten</a></li>
				</ul>
			</li>
			<!-- Typ -->
			<li class="divider hide-for-small"></li>
			<li class="has-dropdown"><a href="main_suche.php">Typ</a>
				<ul class="dropdown">
					<?php		
						$abfrage="SELECT DISTINCT typ FROM DMS ORDER BY typ";
						$ergebnis = mysql_query($abfrage);
						while($row = mysql_fetch_object($ergebnis)) {
							echo "<li><a href=\"main_suche.php?Typ=",$row->typ,"\">",$row->typ,"</a></li>";
						}

					?>
				</ul>
			</li>

			<!-- Ordner -->
			<li class="divider hide-for-small"></li>
			<li class="has-dropdown"><a href="main_suche.php">Ordner</a>
				<ul class="dropdown">
					<?php		
						generateListOrdner(0);
					?>
				</ul>
			</li>

			<!-- Beschreibung -->
			<li class="divider hide-for-small"></li>
			<li class="has-form">
				<form action="main_suche.php" method="get">
					<div class="row collapse">
						<div class="small-9 columns">
							<input type="text" placeholder="Suchen der Beschreibung" name="Beschreibung">
						</div>
						<div class="small-3 columns">
							<input class="button" value="suchen" type="Submit">"
						</div>
					</div>
				</form>
			</li>
		</ul>
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="main_addfile.php">Neues Dokument</a>
			</li>
		</ul>
	</section>
</nav>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Suchformular -->
<div class="row">
	<div class="small-12 large-12 columns">
		<fieldset>
			<legend>Suche</legend>
			<?php
				include("sub_suche_add_button.php");
			?>
		</fieldset>
	</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->
	<div class="small-12 large-12 columns">
		<fieldset>
			<legend>DMS Tabelle</legend>
			<div class="row">
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
			</div>
			<div class="row">
				<table>
					<tr>
						<?php
							if($auswahl_sortierung=="S.id" or $auswahl_sortierung=="S.id DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.id\" class=\"small button\">id</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.id\" class=\"small button secondary\">id</a></th>";}

							if($auswahl_sortierung=="S.typ" or $auswahl_sortierung=="S.typ DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.typ\" class=\"small button\">Typ</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.typ\" class=\"small button secondary\">Typ</a></th>";}

							if($auswahl_sortierung=="S.Beschreibung" or $auswahl_sortierung=="S.Beschreibung DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Beschreibung\" class=\"small button\">Beschreibung</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Beschreibung\" class=\"small button secondary\">Beschreibung</a></th>";}

							if($auswahl_sortierung=="X.name" or $auswahl_sortierung=="X.name DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=X.name\" class=\"small button\">Ordner</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=X.name\" class=\"small button secondary\">Ordner</a></th>";}

							if($auswahl_sortierung=="Z.Herausgeber" or $auswahl_sortierung=="Z.Herausgeber DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=Z.Herausgeber\" class=\"small button\">Herausgeber</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=Z.Herausgeber\" class=\"small button secondary\">Herausgeber</a></th>";}

							if($auswahl_sortierung=="S.Herausgabedatum" or $auswahl_sortierung=="S.Herausgabedatum DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Herausgabedatum\" class=\"small button\">Herausgabedatum</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Herausgabedatum\" class=\"small button secondary\">Herausgabedatum</a></th>";}

							if($auswahl_sortierung=="S.Speicherdatum" or $auswahl_sortierung=="S.Speicherdatum DESC") {
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Speicherdatum\" class=\"small button\">Speicherdatum</a></th>";}
							else{
								echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Speicherdatum\" class=\"small button secondary\">Speicherdatum</a></th>";}
							?>
							<th></th>
					</tr>

					<?php
						$abfrage="
						SELECT DISTINCT
								S.id,
								S.Typ,
								S.Beschreibung,
								S.Datei,
								S.Speicherdatum,
								S.Herausgabedatum,
								Z.Herausgeber,
								Z.HerausgeberID,
								X.name,
								S.Speicherdatum
							FROM DMS AS S
							INNER JOIN Herausgeber AS Z 
								ON S.Herausgeber = Z.HerausgeberID
							INNER JOIN dir AS X 	
								ON X.id = S.dir
							WHERE 
									X.name LIKE '$auswahl_Kategorie'
								AND S.Typ LIKE '$auswahl_Typ'
								AND S.Speicherdatum >= STR_TO_DATE('$auswahl_DatumAusgabeVon', '%d.%m.%Y')
								AND S.Speicherdatum <= STR_TO_DATE('$auswahl_DatumAusgabeBis', '%d.%m.%Y')
								AND S.Herausgabedatum >= STR_TO_DATE('$auswahl_DatumAusgabeVon', '%d.%m.%Y')
								AND S.Herausgabedatum <= STR_TO_DATE('$auswahl_DatumAusgabeBis', '%d.%m.%Y')
								AND S.Beschreibung LIKE '$auswahl_Beschreibung'
								AND S.Herausgeber LIKE '$auswahl_Herausgeber'
								AND S.dir LIKE '$auswahl_ordner'
								AND S.id LIKE '$auswahl_id'
								ORDER BY $auswahl_sortierung
						";
					
						$ergebnis = mysql_query($abfrage);

						if($editStatus==0){
							while($row = mysql_fetch_object($ergebnis))
							{
								$extension=explode(".",$row->Datei);
								echo "<tr>";
									echo "<td>",$row->id,"</td>";
									echo "<td>",$row->Typ,"</td>";
									echo "<td>",$row->Beschreibung,"</td>";
									echo "<td>",$row->name,"</td>";
									echo "<td>",$row->Herausgeber,"</td>";
									echo "<td>",date("d.m.Y",strtotime($row->Herausgabedatum)),"</td>";
									echo "<td>",date("d.m.Y",strtotime($row->Speicherdatum)),"</td>";
									echo "<td><a href=\"upload/",$row->id,".",$extension[count($extension)-1],"\">file</a></td>";
								echo "</tr>";
							}
						}
						else {
							include("sub_suche_update_addbutton.php");

						}
					?>
				</table>
			</div>
		</fieldset>
	</div>
</div>

<?php
	mysql_close($verbindung);
?>

	<script src="js/foundation.min.js"></script>

	<!--script src="js/foundation/foundation.dropdown.js"></script>  


	<script src="js/foundation/foundation.forms.js"></script>
	<script src="js/foundation/foundation.topbar.js"></script>

	<!--  
	<script src="js/foundation/foundation.alerts.js"></script>

	<script src="js/foundation/foundation.clearing.js"></script>

	<script src="js/foundation/foundation.cookie.js"></script>

	<script src="js/foundation/foundation.js"></script>  

	<script src="js/foundation/foundation.joyride.js"></script>

	<script src="js/foundation/foundation.magellan.js"></script>

	<script src="js/foundation/foundation.orbit.js"></script>

	<script src="js/foundation/foundation.reveal.js"></script>

	<script src="js/foundation/foundation.section.js"></script>

	<script src="js/foundation/foundation.tooltips.js"></script>


	<script src="js/foundation/foundation.interchange.js"></script>

	<script src="js/foundation/foundation.placeholder.js"></script>

	<script src="js/foundation/foundation.abide.js"></script>
	-->

	<script>
		$(document).foundation();
	</script>
</body>
