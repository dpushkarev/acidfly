<script language="php">
require_once("../stconfig.php");
</script>
<html>

<head>
<title>Detailed Image View</title>
<meta name="robots" content="noindex,nofollow">
</head>

<body>
<?php
// Sets System Variables
$varquery = "SELECT URL FROM " .$DB_Prefix ."_vars";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select your system variables. Try again later.");
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_row($varresult);
$URL="http://" .$varrow[0];
}

if (substr($image, 0, 7) != "http://")
$image = $URL ."/" .$image;
?>

<p align="center"><img src="<?php echo "$image"; ?>" alt="Larger Image" border="0"></p>
<p align="center"><a href="javascript:window.close();">Close</a></p>
</body>

</html>