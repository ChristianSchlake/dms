<?php
	session_start();
	include("sub_init_database.php");
?>


<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>DMS</title>

	<link rel="stylesheet" href="css/foundation.css">
</head>

<body>

<?php
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Übergabe auslesen und in die Session schreiben */
	if(isset($_GET['edit_herausgeber']))
	{
	$_SESSION['edit_herausgeber']=$_GET['edit_herausgeber'];
	}

	if(isset($_GET['edit_herausgeber_editStatus']))
	{
	$_SESSION['edit_herausgeber_editStatus']=$_GET['edit_herausgeber_editStatus'];
	}

	if (isset($_GET['edit_herausgeber_changeID']))	{
		$edit_herausgeber_changeID=$_GET['edit_herausgeber_changeID'];
	}

	if (isset($_GET['edit_herausgeber_changeHerausgeber']))	{
		$edit_herausgeber_changeHerausgeber=$_GET['edit_herausgeber_changeHerausgeber'];
	}

	if (isset($_GET['edit_herausgeber_delete']))	{
		$edit_herausgeber_changeHerausgeber=$_GET['edit_herausgeber_delete'];
	}

/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Werte aus session in Variablen schreiben */
	if (!isset($_SESSION['edit_herausgeber']))	{
		$edit_herausgeber="%";
	}	
	else {
		$edit_herausgeber=$_SESSION['edit_herausgeber'];
	}

	if (!isset($_SESSION['edit_herausgeber_editStatus']))	{
		$edit_herausgeber_editStatus=0;
	}
	else {
		$edit_herausgeber_editStatus=$_SESSION['edit_herausgeber_editStatus'];
	}
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="main_verwalte_Herausgeber.php?edit_herausgeber=%">Herausgeber verwalten</a></h1>
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
					<li><a href=main_verwalte_Typ.php>Typ verwalten</a></li>					
				</ul>
			</li>
		</ul>
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="sub_verwalte_Herausgeber_add.php">Neuer Herausgeber</a>
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
					<form action="main_verwalte_Herausgeber.php" method="get">
						<div class="row collapse">
							<div class="small-8 columns">
								<input type="text" placeholder="Suche nach dem Herausgeber" name="edit_herausgeber">
							</div>
								<div class="small-4 columns">
								<?php
									if($edit_herausgeber!="%") {
										echo "<input class=\"button prefix\" value=\"",$edit_herausgeber,"\" type=\"Submit\">";
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
	if (isset($edit_herausgeber_changeID) and !isset($_GET['edit_herausgeber_delete'])){
		$abfrage="UPDATE Herausgeber SET Herausgeber=\"".$edit_herausgeber_changeHerausgeber."\" WHERE HerausgeberID=".$edit_herausgeber_changeID;
		mysql_query($abfrage);
	}
	if (isset($_GET['edit_herausgeber_delete'])){
		$abfrage="DELETE FROM Herausgeber WHERE HerausgeberID=".$edit_herausgeber_changeID;
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
						if($edit_herausgeber_editStatus==0){
			  				echo "<dd class=\"active\"><a href=\"main_verwalte_Herausgeber.php?edit_herausgeber_editStatus=0\">Show</a></dd>";
		  					echo "<dd><a href=\"main_verwalte_Herausgeber.php?edit_herausgeber_editStatus=1\">Edit</a></dd>";
						}
						else {
			  				echo "<dd><a href=\"main_verwalte_Herausgeber.php?edit_herausgeber_editStatus=0\">Show</a></dd>";
		  					echo "<dd class=\"active\"><a href=\"main_verwalte_Herausgeber.php?edit_herausgeber_editStatus=1\">Edit</a></dd>";
						}
					?>
				</dl>
			</div>
			<div class="row">
				<table>
					<tr>
						<td>ID</td>
						<td>Herausgeber</td>
						<th></th>
					</tr>

					<?php
						$abfrage="
						SELECT DISTINCT
								HerausgeberID,
								Herausgeber
							FROM Herausgeber
							WHERE 
								Herausgeber LIKE '$edit_herausgeber'
							ORDER BY Herausgeber
						";
					
						$ergebnis = mysql_query($abfrage);

						if($edit_herausgeber_editStatus==0){
							while($row = mysql_fetch_object($ergebnis))
							{
								echo "<tr>";
									echo "<td>",$row->HerausgeberID,"</td>";
									echo "<td>",$row->Herausgeber,"</td>";
								echo "</tr>";
							}
						}
						else {
							while($row = mysql_fetch_object($ergebnis))							{
								echo "<form class=\"custom\" action=\"main_verwalte_Herausgeber.php\" method=\"get\">";
									echo "<tr>";
										// ID
										echo "<td><label>",$row->HerausgeberID,"</label></td>";
										echo "<input type=\"hidden\" name=\"edit_herausgeber_changeID\" value=\"".$row->HerausgeberID."\"\>";
										// Herausgeber
										echo "<td><input type=\"text\" value=\"",$row->Herausgeber,"\" size=\"300\" name=\"edit_herausgeber_changeHerausgeber\"></td>";
										// Button ändern
										echo "<td><input class=\"tiny button round\" type=\"submit\" value=\"",ändern,"\"></td>";
										// Button löschen
										echo "<td><input class=\"tiny button round\" type=\"submit\" name=\"edit_herausgeber_delete\" value=\"",löschen,"\"></td>";
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

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script>$(document).foundation();</script> 

</body>
