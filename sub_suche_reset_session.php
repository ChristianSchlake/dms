<?php
session_start();
?>

<?php

	foreach ($spaltenName as $i => $spalte) {
		switch ($spaltenTyp[$i]) {
			case 'auswahl':
				$b=chr(64+$spaltenID[$i]);
				$selectClause=$selectClause.",".$b.".".$spaltenName[$i];						
				$innerJoin=$innerJoin." INNER JOIN ".$spaltenName[$i]." AS ".$b." ON Z.".$spaltenName[$i]." = ".$b.".".$spaltenName[$i]."ID";
				$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";						
				break;
			case 'auswahlStruktur':
				$b=chr(64+$spaltenID[$i]);
				$selectClause=$selectClause.",".$b.".".$spaltenName[$i];						
				$innerJoin=$innerJoin." INNER JOIN ".$spaltenName[$i]." AS ".$b." ON Z.".$spaltenName[$i]." = ".$b.".".$spaltenName[$i]."ID";
				$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";						
				break;						
			case 'zahl':
				$b=chr(64+$spaltenID[$i]);
				$selectClause=$selectClause.",".$spaltenName[$i];
				$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";						
				break;
			case 'text':
				$b=chr(64+$spaltenID[$i]);
				$selectClause=$selectClause.",".$spaltenName[$i];
				$whereClause=$whereClause." AND Z.".$spaltenName[$i]." LIKE '".$spaltenSuchwert[$i]."'";						
				break;
		}									
	}


	header("Location: main_suche.php?id=%?");
?>
