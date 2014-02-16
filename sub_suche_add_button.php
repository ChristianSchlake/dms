<form action="main_suche.php" method="GET" class="custom">
	<div class="row collapse">
		<?php

			$pos1=strpos($abfrage, "ORDER BY");
			$abfrageTMP=substr($abfrage,0, $pos1);
			$pos1=strpos($abfrageTMP, "FROM DMS");
			$abfrageTMP=" ".substr($abfrageTMP, $pos1);


			getSpaltenDMS();
			foreach ($spaltenBeschreibung as $i => $spalt) {
				switch ($spaltenTyp[$i]) {
					case "zahl":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							echo $spaltenBreiteSuchFormular[$i];					
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								if ($spaltenSuchwert[$i]!="%") {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
								} else {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";										
								}
							echo "</div>";
						}
						break;
					case "auswahlStruktur":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							$eingabeWert=abfrageEinstellung($spaltenName[$i]);
							echo $spaltenBreiteSuchFormular[$i];
								$b=chr(64+$spaltenID[$i]);
								$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY upper(".$b.".".$spaltenName[$i].")";
								$ergebnis = mysql_query($abfrage);
								$spalteX=$spaltenName[$i]."ID";
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								echo "<select class=\"medium\" name=\"".$spaltenName[$i]."\">";
									echo "<option value=".$row->$spalteX.">%</option>";
									while($row = mysql_fetch_object($ergebnis)) {
										if ($row->$spalteX==$eingabeWert) {
											echo "<option selected value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";									
										} else {						
											echo "<option value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";
										}
									}
								echo "</select>";
							echo "</div>";
						}
						break;
					case "previewPic":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							$eingabeWert=abfrageEinstellung($spaltenName[$i]);
							echo $spaltenBreiteSuchFormular[$i];
								$b=chr(64+$spaltenID[$i]);
								$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY upper(".$b.".".$spaltenName[$i].")";
								$ergebnis = mysql_query($abfrage);
								$spalteX=$spaltenName[$i]."ID";
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								echo "<select class=\"medium\" name=\"".$spaltenName[$i]."\">";
									echo "<option value=".$row->$spalteX.">%</option>";
									while($row = mysql_fetch_object($ergebnis)) {
										if ($row->$spalteX==$eingabeWert) {
											echo "<option selected value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";									
										} else {						
											echo "<option value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";
										}
									}
								echo "</select>";
							echo "</div>";
						}
						break;
					case "auswahl":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							$eingabeWert=abfrageEinstellung($spaltenName[$i]);
							echo $spaltenBreiteSuchFormular[$i];
								$b=chr(64+$spaltenID[$i]);
								$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY upper(".$b.".".$spaltenName[$i].")";
								$ergebnis = mysql_query($abfrage);
								$spalteX=$spaltenName[$i]."ID";
								echo "<label>".$spaltenBeschreibung[$i]."</label>";				
								echo "<select class=\"medium\" name=\"".$spaltenName[$i]."\">";
									echo "<option value=".$row->$spalteX.">%</option>";
									while($row = mysql_fetch_object($ergebnis)) {
										if ($row->$spalteX==$eingabeWert) {
											echo "<option selected value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";									
										} else {						
											echo "<option value=".$row->$spalteX.">",$row->$spaltenName[$i],"</option>";
										}
									}
								echo "</select>";
							echo "</div>";
						}
						break;
					case "text":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							echo $spaltenBreiteSuchFormular[$i];
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								if ($spaltenSuchwert[$i]!="%") {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
								} else {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
								}								
							echo "</div>";
						}
						break;
					case "url":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							echo $spaltenBreiteSuchFormular[$i];
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								if ($spaltenSuchwert[$i]!="%") {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
								} else {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
								}								
							echo "</div>";
						}
						break;
					case "dokurl":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							echo $spaltenBreiteSuchFormular[$i];
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								if ($spaltenSuchwert[$i]!="%") {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
								} else {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";
								}								
							echo "</div>";
						}
						break;
					case "datum":
						if ($spaltenBreiteSuchFormular[$i]!="") {
							echo $spaltenBreiteSuchFormular[$i];
								echo "<label>".$spaltenBeschreibung[$i]."</label>";
								if ($spaltenSuchwert[$i]!="%") {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\" value=\"",$spaltenSuchwert[$i],"\">";
								} else {
									echo "<input type=\"text\" placeholder=\"",$spaltenBeschreibung[$i],"\" name=\"",$spaltenName[$i],"\">";										
								}
							echo "</div>";
						}
						break;
				}
			}
			echo "<div class=\"row collapse\">";
				echo "<div class=\"small-12 large-12 columns\">";
					echo "<button class=\"button expand\" type=\"Submit\">suchen</button>";
				echo "</div>";
			echo "</div>";
		echo "</div>";	
	echo "</form>";
?>
