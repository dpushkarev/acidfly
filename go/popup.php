<script language="php">
require_once("../stconfig.php");
</script>
<html>

<head>
<title>Popup Page</title>
<meta name="robots" content="noindex,nofollow">
<script language="php">
$varquery = "SELECT FontStyle, FontSize FROM " .$DB_Prefix ."_vars";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
$varrow = mysqli_fetch_row($varresult);
$fontstyle = $varrow[0];
$fontsize = $varrow[1];
echo "<style type=\"text/css\">\r\n";
echo "body { ";
echo "font-family: $fontstyle; ";
echo "font-size: $fontsize" ."pt; ";
echo "}\r\n";
echo "td { ";
echo "font-family: $fontstyle; ";
echo "font-size: $fontsize" ."pt; ";
echo "}\r\n";
echo ".boldtext { ";
echo "font-weight: bold; ";
echo "}\r\n";
echo "</style>";
</script>
</head>

<body>
<?php
// Pull popup page info
$popquery = "SELECT PageTitle, Content FROM " .$DB_Prefix ."_popups WHERE ID='$pgid'";
$popresult = mysqli_query($dblink, $popquery) or die ("Unable to select your system popiables. Try again later.");
if (mysqli_num_rows($popresult) == 1)
{
$poprow = mysqli_fetch_row($popresult);
$pagetitle = stripslashes($poprow[0]);
$content = stripslashes($poprow[1]);
echo "<p align=\"center\" class=\"boldtext\">$pagetitle</p>";
if (strstr($content, '<') == FALSE AND strstr($content, '>') == FALSE)
{
$content = str_replace ("\r", "<br>", $content);
echo "<p>$content</p>";
}
else
echo "$content";
}
?>

<p align="center"><a href="javascript:window.close();">Close</a></p>
</body>

</html>
