<?php
	session_start();
	include("sub_init_database.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>DMS</title>

	<link rel="stylesheet" href="css/foundation.css">

	<!--script src="/js/vendor/custom.modernizr.js"></script-->


	<!--script>
		document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
	</script-->
	
	<!--script src="/js/vendor/custom.modernizr.js"></script-->

</head>

<body>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<!-- Einstellungen -->
<?php
	$maxEintraegeProSite=10;
?>

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
<nav class="top-bar" data-topbar data-options="is_hover:true">
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
					<li><a href=main_verwalte_Typ.php>Typ verwalten</a></li>								
				</ul>
			</li>
			<!-- Typ -->
			<li class="divider hide-for-small"></li>
			<li class="has-dropdown"><a href="main_suche.php">Typ</a>
				<ul class="dropdown">
					<?php		
						$abfrage="SELECT DISTINCT typName FROM typ ORDER BY typName";
						$ergebnis = mysql_query($abfrage);
						while($row = mysql_fetch_object($ergebnis)) {
							echo "<li><a href=\"main_suche.php?Typ=",$row->typName,"\">",$row->typName,"</a></li>";
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
	<fieldset>
	<legend>Suche</legend>
		<div class="small-12 large-12 columns">			
			<?php
				include("sub_suche_add_button.php");
			?>		
		</div>
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->
<div class="row">
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
			<div class="small-6 large-1 columns">
				<?php
				// ID
					if($auswahl_sortierung=="S.id" or $auswahl_sortierung=="S.id DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.id\" class=\"small button\">id</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.id\" class=\"small button secondary\">id</a></th>";}
				?>						
			</div>
			<div class="small-6 large-2 columns">
				<?php
				// Typ
					if($auswahl_sortierung=="U.TypName" or $auswahl_sortierung=="U.TypName DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=U.TypName\" class=\"small button\">Typ</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=U.TypName\" class=\"small button secondary\">Typ</a></th>";}
				?>
			</div>							
			<div class="small-12 large-4 columns">
				<?php
				// Beschreibung
					if($auswahl_sortierung=="S.Beschreibung" or $auswahl_sortierung=="S.Beschreibung DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Beschreibung\" class=\"small button\">Beschreibung</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Beschreibung\" class=\"small button secondary\">Beschreibung</a></th>";}
				?>
			</div>
			<div class="small-6 large-3 columns">
				<?php
				// Ordner
					if($auswahl_sortierung=="X.name" or $auswahl_sortierung=="X.name DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=X.name\" class=\"small button\">Ordner</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=X.name\" class=\"small button secondary\">Ordner</a></th>";}
				?>
				<br>
					<?php
				// Herausgeber
					if($auswahl_sortierung=="Z.Herausgeber" or $auswahl_sortierung=="Z.Herausgeber DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=Z.Herausgeber\" class=\"small button\">Herausgeber</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=Z.Herausgeber\" class=\"small button secondary\">Herausgeber</a></th>";}
					?>
			</div>
			<div class="small-6 large-2 columns">
				<?php
				// Herausgabedatum
					if($auswahl_sortierung=="S.Herausgabedatum" or $auswahl_sortierung=="S.Herausgabedatum DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Herausgabedatum\" class=\"small button\">Herausgabedatum</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Herausgabedatum\" class=\"small button secondary\">Herausgabedatum</a></th>";}
				?>
				<br>
				<?php
				// Speicherdatum
					if($auswahl_sortierung=="S.Speicherdatum" or $auswahl_sortierung=="S.Speicherdatum DESC") {
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Speicherdatum\" class=\"small button\">Speicherdatum</a></th>";}
					else{
						echo "<th><a href=\"sub_suche_add_sortierung.php?sortierung=S.Speicherdatum\" class=\"small button secondary\">Speicherdatum</a></th>";}
				?>													
			</div>
			<hr />
		</div>
		<?php
			$abfrage="
			SELECT DISTINCT
					S.id,
					U.TypName,
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
				INNER JOIN typ AS U 
					ON U.TypID = S.TypID																
				INNER JOIN dir AS X 	
					ON X.id = S.dir
				WHERE 
						X.name LIKE '$auswahl_Kategorie'
					AND U.TypName LIKE '$auswahl_Typ'
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
			$menge = mysql_num_rows($ergebnis);						
			$abfrage=$abfrage."LIMIT $startPage, $maxEintraegeProSite";					
			$ergebnis = mysql_query($abfrage);
			if($editStatus==0){
				while($row = mysql_fetch_object($ergebnis))
				{
					$extension=explode(".",$row->Datei);
					echo "<div class=\"row\">";
						echo "<div class=\"small-6 large-1 columns\">";
							echo "<p>",$row->id,"</p>";
						echo "</div>";
						echo "<div class=\"small-6 large-2 columns\">";
							echo "<p>",$row->TypName,"</p>";
						echo "</div>";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<a href=\"upload/",$row->id,".",$extension[count($extension)-1],"\">",$row->Beschreibung,"</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-3 columns\">";
								echo "<p>",$row->name,"</p>";
								echo "<br>";
								echo "<p>",$row->Herausgeber,"</p>";
						echo "</div>";
						echo "<div class=\"small-6 large-2 columns\">";
								echo "<p>",date("d.m.Y",strtotime($row->Herausgabedatum)),"</p>";
								echo "<br>";
								echo "<p>",date("d.m.Y",strtotime($row->Speicherdatum)),"</p>";
						echo "</div>";
						echo "<hr />";
					echo "</div>";
				}
			}
			else {
				include("sub_suche_update_addbutton.php");
		
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

<?php
	mysql_close($verbindung);
?>


  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script>$(document).foundation();</script>  
  
<!--script src="js/vendor/jquery.js"></script>
<script src="http://foundation.zurb.com/docs/assets/js/all.js"></script>
<script>$(document).foundation();</script-->  


	<!--script src="/js/vendor/jquery.js"></script>
	<script src="/js/foundation/foundation.min.js"></script-->
	
</body>
