<form action="main_suche.php" method="POST" class="custom" enctype="multipart/form-data">
	<input type="hidden" value="1" name="fileuploadADD">
	<div class="row">
	<?php
		$pos1=strpos($abfrage, "ORDER BY");
		$abfrageTMP=substr($abfrage,0, $pos1);
		$pos1=strpos($abfrageTMP, "FROM DMS");
		$abfrageTMP=" ".substr($abfrageTMP, $pos1);
		$pos1=strpos($abfrageTMP, "WHERE");
		$abfrageTMP=" ".substr($abfrageTMP, 0,$pos1);
		getSpaltenDMS();
		foreach ($spaltenBeschreibung as $i => $spalt) {
			switch ($spaltenTyp[$i]) {
				case "zahl":
					$eingabeWert=abfrageEinstellungADDFile($spaltenName[$i]);					
					if ($spaltenFormularAnzeige[$i]==1) {
						echo $spaltenBreite[$i];
							echo "<label>".$spaltenBeschreibung[$i]."</label>";
							echo "<input type=\"zahl\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."ADD\">";
						echo "</div>";
					}
					break;
				case "auswahlStruktur":
					if ($spaltenFormularAnzeige[$i]==1) {
						$eingabeWert=abfrageEinstellungADDFile($spaltenName[$i]);
						echo $spaltenBreite[$i];
							echo "<label>".$spaltenBeschreibung[$i]."</label>";												
							echo "<select class=\"medium\" name=\"".$spaltenName[$i]."ADD\">";
							generateListOrdnerFormular(0,$eingabeWert,$spaltenName[$i]);
							echo "</select>";						
						echo "</div>";
					}
					break;
				case "auswahl":
					if ($spaltenFormularAnzeige[$i]==1) {						
						$eingabeWert=abfrageEinstellungADDFile($spaltenName[$i]);
						echo $spaltenBreite[$i];
							$b=chr(64+$spaltenID[$i]);
							$abfrage="SELECT DISTINCT ".$b.".".$spaltenName[$i].",".$b.".".$spaltenName[$i]."ID".$abfrageTMP." ORDER BY ".$b.".".$spaltenName[$i];
							$ergebnis = mysql_query($abfrage);
							$spalteX=$spaltenName[$i]."ID";
							echo "<label>".$spaltenBeschreibung[$i]."</label>";				
							echo "<select class=\"medium\" name=\"".$spaltenName[$i]."ADD\">";
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
					if ($spaltenFormularAnzeige[$i]==1) {
						$eingabeWert=abfrageEinstellungADDFile($spaltenName[$i]);
						echo $spaltenBreite[$i];
							echo "<label>".$spaltenBeschreibung[$i]."</label>";
							echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."ADD\">";
						echo "</div>";
					}
					break;
				case "datum":
					if ($spaltenFormularAnzeige[$i]==1) {
						$eingabeWert=abfrageEinstellungADDFile($spaltenName[$i]);
						
						echo $spaltenBreite[$i];
							echo "<label>".$spaltenBeschreibung[$i]."</label>";
							echo "<input type=\"text\" placeholder=\"".$spaltenBeschreibung[$i]."\" value=\"".$eingabeWert."\" name=\"".$spaltenName[$i]."ADD\">";
						echo "</div>";
					}
					break;
			}				
		}
	?>
	</div>

	<div class="row">
		<!div class="small-12 large-12 columns">		
			<fieldset>
				<legend>File Upload</legend>
					<input type="file" name="userfile"/>
			</fieldset>
		</div>
	</div>
	
	<div class="row">	
		<div class="small-12 large-12 columns">
			<button class="button expand" type="Submit">Dokument einchecken</button>
		</div>
	</div>
</form>