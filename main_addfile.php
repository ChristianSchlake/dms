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


</head>

<body>


<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="sub_suche_reset_session.php">DMS</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<!-- zurück zur suche -->
		<ul class="right">
			<li class="has-form">
				<a class="button round" href="main_suche.php">Suche nach Dokumenten</a>
			</li>
		</ul>
	</section>
</nav>

<Eingabeformular>
	<div class="row">
		<div class="small-12 large-12 columns">
			<fieldset>
				<legend>Eingabeformular</legend>
				<?php
					include("sub_addfile_add_button.php");
				?>
			</fieldset>
		</div>
	</div>
</Eingabeformular>


<?php
	mysql_close($verbindung);
?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script>$(document).foundation();</script> 
	
</body>
