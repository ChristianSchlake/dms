<?php
	session_start();
?>

<?php
	include("sub_suche_load_variablen.php");

	if(isset($_GET['sortierung']))
	{
		$sortierung=$_GET['sortierung'];
	}

	if($sortierung==$auswahl_sortierung){
		$auswahl_sortierung=$sortierung." DESC";
	}
	else {
		$auswahl_sortierung=$sortierung;
	}
	
	$_SESSION['sortierung']=$auswahl_sortierung;
?>




<?php
	header("Location: main_suche.php");
?>
