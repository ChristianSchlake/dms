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
	<!--script>
		document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
	</script-->


</head>
	<script src="/js/vendor/jquery.js"></script>

<body>


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
		<!-- zurück zur suche -->
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="main_verwalte_Typ.php">Typ verwalten</a>
			</li>
		</ul>
	</section>
</nav>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Variablen einlesen-->
<?php
	$editTyp_Name=$_POST['editTyp_Name'];
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- prüfen ob alle Daten eingetragen sind-->
<?php
	$eintrag=0;
	$dateCheck=false;

	if(	isset($editTyp_Name))
	{
		if(	$editTyp_Name!="")
		{
			$eintrag=1;
		}
	}
	if($eintrag==1) {
			// neue ID ermitteln
			$eintragen = mysql_query("INSERT INTO typ (TypName) VALUES (\"".$editTyp_Name."\")");		
			$_SESSION['edit_typ']=$editTyp_Name;
			$_SESSION['edit_typ_editStatus']=0;
			header("Location: main_verwalte_Typ.php");
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
			<form action="sub_verwalte_Typ_add.php" method="POST" class="custom">
				<div class="row collapse">
					<?php
						if($eintrag=="0" and $editTyp_Name=="" and isset($editTyp_Name)) {
							echo "<input type=\"text\" class=\"error\" placeholder=\"Typ\" name=\"editTyp_Name\" />";
							echo "<small class=\"error\">fehlende Eingabe</small>";
						}
						else {
							echo "<input type=\"text\" placeholder=\"Typ\" value=\"",$editTyp_Name,"\" name=\"editTyp_Name\">";
						}
					?>
				</div>
			<div class="row collapse">
				<button class="button expand" type="Submit">Typ eintragen</button>
			</form>
		</fieldset>
	</div>
</div>


<?php
	mysql_close($verbindung);
?>

	<script src="/js/vendor/jquery.js"></script>
	<script src="/js/foundation/foundation.min.js"></script>


	<!--script src="js/foundation.min.js"></script-->
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
