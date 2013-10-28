<?php
	session_start();
	include("sub_init_database.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">
	<title>DMS - Datei einchecken</title>
	<link rel="stylesheet" href="css/foundation.css">

	<script src="/js/vendor/custom.modernizr.js"></script>
	<script>
		document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
	</script>


</head>

<body>


<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
<nav class="top-bar" data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="main_verwalte_Herausgeber.php?edit_herausgeber=%">Herausgeber verwalten</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<!-- zurück zur suche -->
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="main_verwalte_Herausgeber.php">Herausgeber verwalten</a>
			</li>
		</ul>
	</section>
</nav>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Variablen einlesen-->
<?php
	$editHerausgeber_Herausgeber=$_POST['editHerausgeber_Herausgeber'];
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- prüfen ob alle Daten eingetragen sind-->
<?php
	$eintrag=0;
	$dateCheck=false;

	if(	isset($editHerausgeber_Herausgeber))
	{
		if(	$editHerausgeber_Herausgeber!="")
		{
			$eintrag=1;
		}
	}
	if($eintrag==1) {
			// neue ID ermitteln
			$eintragen = mysql_query("INSERT INTO Herausgeber (Herausgeber) VALUES (\"".$editHerausgeber_Herausgeber."\")");		
			$_SESSION['edit_herausgeber']=$editHerausgeber_Herausgeber;
			$_SESSION['edit_herausgeber_editStatus']=0;
			header("Location: main_verwalte_Herausgeber.php");
	}
?>


<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Eingabeformular -->
<div class="row">
	<div class="small-12 large-12 columns">
		<fieldset>
			<legend>Eingabeformular</legend>
			<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
			<!-- Hier kommt der Herausgeber als Formular-->
			<form action="sub_verwalte_Herausgeber_add.php" method="POST" class="custom">
				<div class="row collapse">
					<?php
						if($eintrag=="0" and $editHerausgeber_Herausgeber=="" and isset($editHerausgeber_Herausgeber)) {
							echo "<input type=\"text\" class=\"error\" placeholder=\"Beschreibung\" name=\"editHerausgeber_Herausgeber\" />";
							echo "<small class=\"error\">fehlende Eingabe</small>";
						}
						else {
							echo "<input type=\"text\" placeholder=\"Beschreibung\" value=\"",$editHerausgeber_Herausgeber,"\" name=\"editHerausgeber_Herausgeber\">";
						}
					?>
				</div>
			<div class="row collapse">
				<button class="button expand" type="Submit">Herausgeber eintragen</button>
			</form>
		</fieldset>
	</div>
</div>


<?php
	mysql_close($verbindung);
?>
	<script src="js/foundation.min.js"></script>
	<!--script src="js/foundation/foundation.dropdown.js"></script>  


	<script src="js/foundation/foundation.forms.js"></script>
	<!--script src="js/foundation/foundation.topbar.js"></script-->

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
