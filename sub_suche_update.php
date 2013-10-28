<?php
	session_start();
?>

<?php
$serverhost="192.168.2.111";
$db_user="xataface_DMS";
$db_pass="xataface_DMS";
$db_database="xataface_DMS";
$verbindung = mysql_connect ($serverhost,$db_user,$db_pass) or die ("keine Verbindung möglich. Benutzername oder Passwort sind falsch");

mysql_select_db($db_database) or die ("Die Datenbank existiert nicht.");
	mysql_query("SET CHARACTER SET utf8");
	mysql_query("SET NAMES utf8");
	header("Content-type:text/html; charset=utf8");
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

<?php
if(isset($_POST['typ']))
	{
	$Typ=$_POST['typ'];
	}

if(isset($_POST['Beschreibung']))
	{
	$Beschreibung=$_POST['Beschreibung'];
	}

if(isset($_POST['ordner']))
	{
	$ordner=$_POST['ordner'];
	$idArray=explode("|",$ordner);
	$ordner=$idArray[1];
	}

if(isset($_POST['herausgeber']))
	{
	$herausgeber=$_POST['herausgeber'];
	$idArray=explode("|",$herausgeber);
	$herausgeber=$idArray[1];
	}

if(isset($_POST['herausgabedatum']))
	{
	$herausgabedatum=$_POST['herausgabedatum'];
	}

if(isset($Typ))
	{
	$idArray=explode("|",$Typ);
	$id=$idArray[1];
	$Typ=$idArray[0];
	}
?>







<body>
	<?php
		echo "<h1>",$Typ,"</h1><br>";
		echo "<h1>",$Beschreibung,"</h1><br>";
		echo "<h1>",$ordner,"</h1><br>";
		echo "<h1>",$herausgeber,"</h1><br>";
		echo "<h1>",$herausgabedatum,"</h1>";
		echo "<h1>",$id,"</h1><br>";

		$abfrage="UPDATE DMS SET dir=".$ordner.",Typ=\"".$Typ."\",Beschreibung=\"".$Beschreibung."\",Herausgeber=".$herausgeber.",Herausgabedatum=STR_TO_DATE('".$herausgabedatum."', '%d.%m.%Y') WHERE id=".$id;
		echo "<h1>",$abfrage,"</h1>";
//		mysql_query($abfrage);
		echo "<p>",$abfrage,"</p>";
	?>

</body>

<?php
//	header("Location: main_suche.php");
?>;
