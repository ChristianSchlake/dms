<?php
	echo "<div class=\"row collapse\">";
	$pos1=strpos($abfrage, "ORDER BY");
	$abfrageTMP=substr($abfrage,0, $pos1);
	$pos1=strpos($abfrageTMP, "FROM DMS");
	$abfrageTMP=" ".substr($abfrageTMP, $pos1);
	getSpaltenDMS();
	foreach ($spaltenBeschreibung as $i => $spalt) {
		switch ($spaltenTyp[$i]) {
			case "zahl":
				echo $spaltenBreite[$i];
					echo "<form action=\"main_suche.php\" method=\"get\">";
						if ($spaltenSuchwert[$i]!="%") {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
						} else {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";										
						}
						if($spaltenSuchwert[$i]!="%") {
							echo "<div class=\"row collapse\">";
								echo "<div class=\"small-9 large-9 columns\">";
									echo "<input class=\"button prefix\" value=\"suchen\" type=\"Submit\">";
								echo "</div>";
								echo "<div class=\"small-3 large-3 columns\">";
									echo "<a href=\"main_suche.php?".$spaltenName[$i]."=%\" class=\"button prefix\">reset</i></a>";
								echo "</div>";
							echo "</div>";
							
						}
						else {
							echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
						}
					echo "</form>";
				echo "</div>";
				break;
			case "auswahlStruktur":				
				echo $spaltenBreite[$i];
					$b=chr(64+$spaltenID[$i]);
					$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY ".$b.".".$spaltenName[$i];
					$ergebnis = mysql_query($abfrage);
					while($row = mysql_fetch_object($ergebnis)) {
						$nameX=$row->$spaltenName[$i];
					}
					if($spaltenSuchwert[$i]!="%") {
						echo "<a href=\"#\" data-dropdown=\"",$spaltenName[$i],"\" class=\"tiny button dropdown\">",$nameX,"</a>";
					}
					else {
						echo "<a href=\"#\" data-dropdown=\"",$spaltenName[$i],"\" class=\"tiny button dropdown secondary\">".$spaltenBeschreibung[$i]."</a>";
					}
					echo "<ul id=",$spaltenName[$i]," class=\"f-dropdown\" data-dropdown-content>";
						if($spaltenSuchwert[$i]!="%"){
							echo "<li><a href=\"main_suche.php?",$spaltenName[$i],"=%&startPage=0\">&rarr; reset </a></li>";
						}						
						$ergebnis = mysql_query($abfrage);
						$spalteX=$spaltenName[$i]."ID";						
						while($row = mysql_fetch_object($ergebnis)) {			
							echo "<li><a href=\"main_suche.php?",$spaltenName[$i],"=",$row->$spalteX,"&startPage=0","\">",$row->$spaltenName[$i],"</a></li>";
						}
					echo "</ul>";
				echo "</div>";
				break;
			case "auswahl":
				echo $spaltenBreite[$i];	
					$b=chr(64+$spaltenID[$i]);
					$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY ".$b.".".$spaltenName[$i];
					$ergebnis = mysql_query($abfrage);
//					echo $abfrage;
					while($row = mysql_fetch_object($ergebnis)) {
						$nameX=$row->$spaltenName[$i];
					}
					if($spaltenSuchwert[$i]!="%") {
						echo "<a href=\"#\" data-dropdown=\"",$spaltenName[$i],"\" class=\"tiny button dropdown\">",$nameX,"</a>";
					}
					else {
						echo "<a href=\"#\" data-dropdown=\"",$spaltenName[$i],"\" class=\"tiny button dropdown secondary\">".$spaltenBeschreibung[$i]."</a>";
					}
					echo "<ul id=",$spaltenName[$i]," class=\"f-dropdown\" data-dropdown-content>";
						if($spaltenSuchwert[$i]!="%"){
							echo "<li><a href=\"main_suche.php?",$spaltenName[$i],"=%&startPage=0\">&rarr; reset </a></li>";
						}
						$ergebnis = mysql_query($abfrage);
						$spalteX=$spaltenName[$i]."ID";
						while($row = mysql_fetch_object($ergebnis)) {			
							echo "<li><a href=\"main_suche.php?",$spaltenName[$i],"=",$row->$spalteX,"&startPage=0","\">",$row->$spaltenName[$i],"</a></li>";
						}
					echo "</ul>";
				echo "</div>";
				break;
			case "text":
				echo $spaltenBreite[$i];
					echo "<form action=\"main_suche.php\" method=\"get\">";
						if ($spaltenSuchwert[$i]!="%") {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
						} else {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
						}								
						if($spaltenSuchwert[$i]!="%") {
							echo "<input class=\"button prefix\" value=\"suchen\" type=\"Submit\">";
						}
						else {
							echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
						}
					echo "</form>";
				echo "</div>";
				break;
			case "url":
				echo $spaltenBreite[$i];
					echo "<form action=\"main_suche.php\" method=\"get\">";
						if ($spaltenSuchwert[$i]!="%") {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
						} else {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
						}								
						if($spaltenSuchwert[$i]!="%") {
							echo "<input class=\"button prefix\" value=\"suchen\" type=\"Submit\">";
						}
						else {
							echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
						}
					echo "</form>";
				echo "</div>";
				break;
			case "dokurl":
				echo $spaltenBreite[$i];
					echo "<form action=\"main_suche.php\" method=\"get\">";
						if ($spaltenSuchwert[$i]!="%") {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
						} else {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
						}								
						if($spaltenSuchwert[$i]!="%") {
							echo "<input class=\"button prefix\" value=\"suchen\" type=\"Submit\">";
						}
						else {
							echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
						}
					echo "</form>";
				echo "</div>";
				break;
			case "datum":
				echo $spaltenBreite[$i];
					echo "<form action=\"main_suche.php\" method=\"get\">";
						if ($spaltenSuchwert[$i]!="%") {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
						} else {
							echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";										
						}
						if($spaltenSuchwert[$i]!="%") {
							echo "<input class=\"button prefix\" value=\"suchen\" type=\"Submit\">";
						}
						else {
							echo "<input class=\"button prefix secondary\" value=\"suchen\" type=\"Submit\">";
						}
					echo "</form>";
				echo "</div>";
				break;
		}
	}	
	echo "</div>";	
?>
