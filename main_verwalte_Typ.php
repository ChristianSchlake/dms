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

<?php
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Übergabe auslesen und in die Session schreiben */
	if(isset($_GET['edit_typ']))
	{
	$_SESSION['edit_typ']=$_GET['edit_typ'];
	}

	if(isset($_GET['edit_typ_editStatus']))
	{
	$_SESSION['edit_typ_editStatus']=$_GET['edit_typ_editStatus'];
	}

	if (isset($_GET['edit_typ_changeID']))	{
		$edit_typ_changeID=$_GET['edit_typ_changeID'];
	}

	if (isset($_GET['edit_typ_changetyp']))	{
		$edit_typ_changetyp=$_GET['edit_typ_changetyp'];
	}

	if (isset($_GET['edit_typ_delete']))	{
		$edit_typ_changetyp=$_GET['edit_typ_delete'];
	}

/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Werte aus session in Variablen schreiben */
	if (!isset($_SESSION['edit_typ']))	{
		$edit_typ="%";
	}	
	else {
		$edit_typ=$_SESSION['edit_typ'];
	}

	if (!isset($_SESSION['edit_typ_editStatus']))	{
		$edit_typ_editStatus=0;
	}
	else {
		$edit_typ_editStatus=$_SESSION['edit_typ_editStatus'];
	}
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
<nav class="top-bar" data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="main_verwalte_Typ.php?edit_typ=%">Typ verwalten</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">								
			<!-- Navigation -->
			<li class="divider hide-for-small"></li>
			<li class="has-dropdown"><a href="#">Navigation</a>
				<ul class="dropdown">
					<li><a href=main_suche.php>DMS</a></li>
					<li><a href=main_verwalte_Herausgeber.php>Herausgeber verwalten</a></li>					
				</ul>
			</li>
		</ul>
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="sub_verwalte_Typ_add.php">Neuer Typ</a>
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
				<div class="row collapse">
					<form action="main_verwalte_Typ.php" method="get">
						<div class="row collapse">
							<div class="small-8 columns">
								<input type="text" placeholder="Suche nach dem typ" name="edit_typ">
							</div>
								<div class="small-4 columns">
								<?php
									if($edit_typ!="%") {
										echo "<input class=\"button prefix\" value=\"",$edit_typ,"\" type=\"Submit\">";
									}
									else {
										echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
									}
								?>

							</div>
						</div>
					</form>
				</div>
		</fieldset>
	</div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Daten aktualisieren -->
<?php
	if (isset($edit_typ_changeID) and !isset($_GET['edit_typ_delete'])){
		$abfrage="UPDATE typ SET TypName=\"".$edit_typ_changetyp."\" WHERE TypID=".$edit_typ_changeID;
		mysql_query($abfrage);
	}
	if (isset($_GET['edit_typ_delete'])){
		$abfrage="DELETE FROM typ WHERE TypID=".$edit_typ_changeID;
		mysql_query($abfrage);
	}
?>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->
<div class="row">
	<div class="small-12 large-12 columns">
		<fieldset>
			<legend>DMS Tabelle</legend>
			<div class="row">
				<dl class="sub-nav">
					<?php
						if($edit_typ_editStatus==0){
			  				echo "<dd class=\"active\"><a href=\"main_verwalte_Typ.php?edit_typ_editStatus=0\">Show</a></dd>";
		  					echo "<dd><a href=\"main_verwalte_Typ.php?edit_typ_editStatus=1\">Edit</a></dd>";
						}
						else {
			  				echo "<dd><a href=\"main_verwalte_Typ.php?edit_typ_editStatus=0\">Show</a></dd>";
		  					echo "<dd class=\"active\"><a href=\"main_verwalte_Typ.php?edit_typ_editStatus=1\">Edit</a></dd>";
						}
					?>
				</dl>
			</div>
			<div class="row">
				<table>
					<tr>
						<td>ID</td>
						<td>typ</td>
						<th></th>
					</tr>

					<?php
						$abfrage="
						SELECT DISTINCT
								TypID,
								TypName
							FROM typ
							WHERE 
								TypName LIKE '$edit_typ'
							ORDER BY TypName
						";
					
						$ergebnis = mysql_query($abfrage);

						if($edit_typ_editStatus==0){
							while($row = mysql_fetch_object($ergebnis))
							{
								echo "<tr>";
									echo "<td>",$row->TypID,"</td>";
									echo "<td>",$row->TypName,"</td>";
								echo "</tr>";
							}
						}
						else {
							while($row = mysql_fetch_object($ergebnis))							{
								echo "<form class=\"custom\" action=\"main_verwalte_Typ.php\" method=\"get\">";
									echo "<tr>";
										// ID
										echo "<td><label>",$row->TypID,"</label></td>";
										echo "<input type=\"hidden\" name=\"edit_typ_changeID\" value=\"".$row->TypID."\"\>";
										// typ
										echo "<td><input type=\"text\" value=\"",$row->TypName,"\" size=\"300\" name=\"edit_typ_changetyp\"></td>";
										// Button ändern
										echo "<td><input class=\"tiny button round\" type=\"submit\" value=\"",ändern,"\"></td>";
										// Button löschen
										echo "<td><input class=\"tiny button round\" type=\"submit\" name=\"edit_typ_delete\" value=\"",löschen,"\"></td>";
									echo "</tr>";
								echo "</form>";
							}
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
