<?php
	include("functions.php");

	$id="";
	foreach ($_GET as $key => $value) {
		if ($key=="id") {
			$id=$value;
		}
	}
	$fileName=getFilenameByID($id);
	header("Location: $fileName");
	print "<script>self.close();</script>";
?>
</body>
