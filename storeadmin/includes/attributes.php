<html>

<head>
<title>Upload Image</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<p align="center" class="fieldname">Attribute Wizard</p>
<?php
$maxattributes = 10;
include("open.php");

$varquery = "SELECT Currency FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
$varrow = mysql_fetch_row($varresult);
$currency = $varrow[0];

// After posting
if ($_POST[mode] == "submit")
{
echo "<script language=\"javascript\">\r\n";
echo "<!--\r\n";
echo "function setAttribute(AttValue, OptName)\r\n";
echo "{\r\n";
echo "window.opener.document.Options.attributes$opt.value = AttValue;\r\n";
if ($typeval == "drop")
echo "window.opener.document.Options.type$opt.value = 'Drop Down';\r\n";
else
echo "window.opener.document.Options.type$opt.value = 'Radio Button';\r\n";
if ($optname)
echo "window.opener.document.Options.name$opt.value = OptName;\r\n";
echo "window.close();\r\n";
echo "}\r\n";
echo "// -->\r\n";
echo "</script>\r\n";
$attvalue = "";

echo "<p align=\"center\"><b>$optname</b></p>";
echo "<table align=\"center\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";
for ($i = 0; $i <= $maxattributes; ++$i)
{
$optname = str_replace('"', "”", $optname);
$optname = str_replace("'", "’", $optname);
$optname = str_replace(":", "", $optname);
$optname = str_replace("~", "", $optname);
$optname = str_replace("#", "", $optname);
$optname = str_replace("{b}", "", $optname);
$optname = str_replace("{/b}", "", $optname);
$optname = str_replace("{br}", "", $optname);
$optname = str_replace(" - ", "-", $optname);
$attnameval = "${"attname$i"}";
$attnameval = str_replace('"', "”", $attnameval);
$attnameval = str_replace("'", "’", $attnameval);
$attnameval = str_replace(":", "", $attnameval);
$attnameval = str_replace("~", "", $attnameval);
$attnameval = str_replace("#", "", $attnameval);
$attnameval = str_replace("{b}", "", $attnameval);
$attnameval = str_replace("{/b}", "", $attnameval);
$attnameval = str_replace("{br}", "", $attnameval);
$attnameval = str_replace(" - ", "-", $attnameval);
$priceval = "${"price$i"}";
$priceval = number_format($priceval, 2, '.', '');
$unitsval = "${"units$i"}";
if ($attnameval)
{
if ($i > 0)
$attvalue .= "~";
$attvalue .= "$attnameval";
if ($priceval > 0)
$attvalue .= ":$priceval";
if ($unitsval > 0 AND $priceval == 0)
$attvalue .= "::$unitsval";
else if ($unitsval > 0)
$attvalue .= ":$unitsval";
echo "<tr>";
echo "<td>$attnameval</td>";
echo "<td>";
if ($priceval > 0)
echo "$currency$priceval";
echo "</td>";
echo "<td>";
if ($unitsval > 0)
echo "$unitsval";
echo "</td>";
echo "</tr>";
}
}
echo "</table>";
echo "<p align=\"center\">";
echo "<a href=\"javascript:setAttribute('$attvalue', '$optname');\"><b>Update Attributes</b></a>";
echo "</p>";
}

else
{
if ($id)
{
$optquery = "SELECT Attributes, Name FROM " .$DB_Prefix ."_options WHERE ID='$id'";
$optresult = mysql_query($optquery, $dblink) or die ("Unable to select. Try again later.");
$optrow = mysql_fetch_row($optresult);
$attributes = $optrow[0];
$optname = $optrow[1];
}
else
{
$attributes = "";
$optname = "";
}
$splitatt = explode("~", $attributes);

echo "<form method=\"POST\" action=\"attributes.php\">";
echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" align=\"center\">";
$countatt = count($splitatt);
if ($opt == 1)
$colspan = 3;
else
$colspan = 2;
for ($a = 0; $a < $countatt, $a <= $maxattributes; ++$a)
{
if ($a == 0)
{
echo "<tr>";
echo "<td colspan=\"$colspan\" align=\"center\">";
echo "Field Name: <input type=\"text\" name=\"optname\" size=\"25\" value=\"$optname\">";
echo "<br><hr noshade size=\"1\" color=\"#C0C0C0\">";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><i>Attribute Name</i></td>";
echo "<td><i>Add'l Charge</i></td>";
if ($opt == 1)
echo "<td><i>Units</i></td>";
echo "</tr>";
}
$splitopt = explode(":", $splitatt[$a]);
echo "<tr>";
echo "<td><input type=\"text\" name=\"attname$a\" size=\"20\" value=\"$splitopt[0]\"></td>";
echo "<td>$currency<input type=\"text\" name=\"price$a\" size=\"6\" value=\"$splitopt[1]\"></td>";
if ($opt == 1)
echo "<td><input type=\"text\" name=\"units$a\" size=\"3\" value=\"$splitopt[2]\"></td>";
echo "</tr>";
}
echo "<tr>";
echo "<td colspan=\"$colspan\" align=\"center\">";
echo "<input type=\"radio\" name=\"typeval\" value=\"drop\" checked> Drop Down ";
echo "<input type=\"radio\" name=\"typeval\" value=\"radio\"> Radio Button<br>";
echo "<input type=\"hidden\" name=\"opt\" value=\"$opt\">";
echo "<input type=\"hidden\" name=\"mode\" value=\"submit\">";
echo "<input type=\"submit\" class=\"button\" value=\"Submit\" name=\"Submit\">";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
}
?>
</body>
</html>
