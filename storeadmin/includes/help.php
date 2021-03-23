<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="style.css">
</script>
</head>

<body>
<?php
include("open.php");
$buttons = "<a href=\"help.php\">";
$buttons .= "<img border=\"0\" src=\"../images/search.gif\" alt=\"Search Help\" align=\"left\" width=\"16\" height=\"16\"></a>";
$buttons .= "<a href=\"javascript:history.back();\">";
$buttons .= "<img border=\"0\" src=\"../images/back.gif\" alt=\"Go Back\" align=\"left\" width=\"16\" height=\"16\"></a>";
$buttons .= "<a href=\"javascript:window.close();\">";
$buttons .= "<img border=\"0\" src=\"../images/close.gif\" alt=\"Close Window\" align=\"right\" width=\"16\" height=\"16\"></a>";
$buttons .= "<a href=\"javascript:history.forward();\">";
$buttons .= "<img border=\"0\" src=\"../images/forward.gif\" alt=\"Go Forward \" align=\"right\" width=\"16\" height=\"16\"></a>";

echo "<div class=\"regtext\">";
$helpquery = "SELECT " .$DB_Prefix ."_help.* FROM " .$DB_Prefix ."_help";
$helpquery .= " LEFT JOIN " .$DB_Prefix ."_permissions";
$helpquery .= " ON " .$DB_Prefix ."_help.Mode = " .$DB_Prefix ."_permissions.SetPg";
if ($set_master_key == "yes")
$helpquery .= " WHERE " .$DB_Prefix ."_help.ID > 0";
else
{
$helpquery .= " WHERE ((" .$DB_Prefix ."_permissions.SiteGroup <> ''";
$helpquery .= " AND " .$DB_Prefix ."_permissions.GivePermission = 'Yes')";
$helpquery .= " OR " .$DB_Prefix ."_help.SetPerm = 'No')";
}
// KEYWORD
if ($keyword)
{
$searchterm = explode(" ", trim($keyword));
$countterms = count($searchterm);
$helpquery .= " AND (";
for ($c = 0; $c < $countterms; ++$c)
{
$addlterm = addslash_mq($searchterm[$c]);
if ($c > 0)
$helpquery .= " AND ";
if ($countterms > 1)
$helpquery .= "(";
$helpquery .= "Title LIKE '%$addlterm%' OR HelpFile LIKE '%$addlterm%'";
if ($set_master_key == "yes")
$helpquery .= " OR MasterFile LIKE '%$addlterm%'";
if ($countterms > 1)
$helpquery .= ")";
}
$helpquery .= ")";
}
// MODE
else if ($mode)
{
$helpquery .= " AND " .$DB_Prefix ."_help.Mode='$mode'";
if (isset($submode))
$helpquery .= " AND " .$DB_Prefix ."_help.SubMode='$submode'";
}
// ID
else if ($helpid)
$helpquery .= " AND " .$DB_Prefix ."_help.ID='$helpid'";
$helpquery .= " GROUP BY " .$DB_Prefix ."_help.ID ORDER BY " .$DB_Prefix ."_help.ID";

$helpresult = mysql_query($helpquery, $dblink) or die ("Unable to select. Try again later.");
$helpnum = mysql_num_rows($helpresult);

// START SHOW ONLY IF KEYWORDS, MODE OR ID IS PRESENT
if ($keyword OR $mode OR $helpid)
{

// SHOW LIST
if ($helpnum > 1)
{
echo "<p align=\"center\" class=\"highlighttext\">";
echo "$buttons";
echo "Click Topic to View Details</p>";
echo "<ul>";
while ($helprow = mysql_fetch_array($helpresult))
{
$title = stripslashes($helprow[Title]);
echo "<li><a href=\"help.php?helpid=$helprow[ID]\">$title</a></li>";
}
echo "</ul>";
}

// SHOW SINGLE
else if ($helpnum == 1)
{
$helprow = mysql_fetch_array($helpresult);
$title = stripslashes($helprow[Title]);
$helpfile = stripslashes($helprow[HelpFile]);
$showhelp = "";
// PULL OUT PERMISSION DELETES
$helpfile_exp = explode("<%>", $helpfile);
for ($h=0; $h <= count($helpfile_exp); ++$h)
{
// PULL OUT CODE
$exp_code = explode("{/code}", trim($helpfile_exp[$h]));
if ($exp_code[1])
{
$code_info = explode("{code}", $exp_code[0]);
// CHECK VARIABLES
$grparr = explode("-", trim($code_info[1]));
// Check variables?
if (substr($grparr[0], 0, 4) == "VAR_")
{
$varfield = substr($grparr[0], 4);
if (substr($grparr[1], 0, 2) == "GT")
{
$varop = ">=";
$varval = substr($grparr[1], 2);
}
else if (substr($grparr[1], 0, 2) == "LT")
{
$varop = "<=";
$varval = substr($grparr[1], 2);
}
else
{
$varop = "=";
$varval = "'$grparr[1]'";
}
if (substr($grparr[0], 0, 4) == "VAR_")
$showquery = "SELECT ID FROM " .$DB_Prefix ."_vars WHERE " .$varfield .$varop .$varval;
$showresult = mysql_query($showquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($showresult) == 1)
$showhelp .= "$exp_code[1]";
}
else
{
$showquery = "SELECT ID FROM " .$DB_Prefix ."_permissions WHERE SetPg='$grparr[0]' AND SubGroup='$grparr[1]' AND GivePermission='Yes'";
$showresult = mysql_query($showquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($showresult) == 1)
$showhelp .= "$exp_code[1]";
}
}
else
$showhelp .= "$helpfile_exp[$h]";
}
echo "<p align=\"center\">";
echo "$buttons";
echo "<span class=\"highlighttext\">$title</span>";
echo "<br><a href=\"javascript:window.opener.location='../$helprow[Mode].$pageext';window.close();\">Go To Page</a>";
echo "</p>";
// Show help?
$pgquery = "SELECT ID FROM " .$DB_Prefix ."_pages WHERE PageName='$helprow[Mode]' AND Active='No'";
$pgresult = mysql_query($pgquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($pgresult) == 1)
echo "Please <a href=\"javascript:window.opener.location='../$helprow[Mode].$pageext';window.close();\">activate the page</a> before viewing the help screens.";
else
echo "$showhelp";
if (isset($_COOKIE['adminmstr']) AND $_COOKIE['adminmstr'] == $Master_Key)
{
if (!$submode)
$masterfile = stripslashes($helprow[MasterFile]);
else
{
$masterquery = "SELECT MasterFile FROM " .$DB_Prefix ."_help";
$masterquery .= " WHERE Mode='$helprow[Mode]' AND SubMode=''";
$masterresult = mysql_query($masterquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($masterresult) == 1)
{
$masterrow = mysql_fetch_row($masterresult);
$masterfile = stripslashes($masterrow[0]);
}
}
if ($masterfile)
{
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"largetable\">";
echo "<tr>";
echo "<td width=\"100%\">$masterfile</td>";
echo "</tr>";
echo "</table>";
}
}
}

}
// END SHOW ONLY IF KEYWORDS, MODE OR ID IS PRESENT

echo "</div>";
echo "<div class=\"regtext\">";
echo "<form action=\"help.php\" method=\"POST\">";
echo "<p align=\"center\" class=\"highlighttext\">";
if (!$keyword AND !$mode AND !$helpid)
echo "$buttons";
echo "Search Help Topics</p>";
echo "<p align=\"center\">";
echo "<input type=\"text\" name=\"keyword\" size=\"20\"><br>";
echo "<input type=\"submit\" value=\"Search\" name=\"submit\"></p>";
echo "</form>";
echo "</div>";
?>
</body>

</html>