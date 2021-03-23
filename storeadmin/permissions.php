<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

// Update Permissions
if ($setperm AND $pid)
{
$updquery = "UPDATE " .$DB_Prefix ."_permissions SET GivePermission = '$setperm' WHERE ID = '$pid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to update permissions. Try again later.");
// Update subpermissions if needed
if ($setperm == "No")
{
$tquery = "SELECT SetPg FROM " .$DB_Prefix ."_permissions WHERE ID = '$pid' AND SubGroup = ''";
$tresult = mysql_query($tquery, $dblink) or die ("Unable to select. Try again later.");
$trow = mysql_fetch_row($tresult);
if (mysql_num_rows($tresult) == 1)
{
$updtquery = "UPDATE " .$DB_Prefix ."_permissions SET GivePermission = '$setperm' WHERE SetPg = '$trow[0]'";
$updtresult = mysql_query($updtquery, $dblink) or die ("Unable to update permissions. Try again later.");
}
}
}

else if ($setperm AND $gid >= 0)
{
$updquery = "UPDATE " .$DB_Prefix ."_permissions SET GivePermission = '$setperm' WHERE SiteGroup = '$group[$gid]'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to update all permissions. Try again later.");
// Update subpermissions if needed
$tquery = "SELECT SetPg FROM " .$DB_Prefix ."_permissions WHERE SiteGroup = '$group[$gid]'";
$tresult = mysql_query($tquery, $dblink) or die ("Unable to select. Try again later.");
while ($trow = mysql_fetch_row($tresult))
{
$updtquery = "UPDATE " .$DB_Prefix ."_permissions SET GivePermission = '$setperm' WHERE SetPg = '$trow[0]'";
$updtresult = mysql_query($updtquery, $dblink) or die ("Unable to update permissions. Try again later.");
}
}

// Update group order
else if ($setgroup >= 0 AND $pid)
{
$updquery = "UPDATE " .$DB_Prefix ."_permissions SET SiteGroup = '$group[$setgroup]' WHERE ID = '$pid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to update all permissions. Try again later.");
}
?>

<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="5">Administration Page Permissions</td>
</tr>
<tr>
<td align="left" colspan="5">
Select the permissions you wish to set for the administrator. Note: This disables the feature 
pages from being accessed by the general administrator only. The master administrator can still 
access all administration areas, and front end site pages with these features can still be viewed.
</td>
</tr>
<tr>
<td class="accent" width="100%" align="center" valign="baseline">Feature</td>
<td class="accent" align="center" valign="baseline">Page</td>
<td class="accent" colspan="2" align="center" valign="baseline">Group</td>
<td class="accent" align="center" valign="baseline">Permission?</td>
</tr>
<?php
foreach ($group AS $gval => $groupname)
{
$sitegroup = stripslashes($groupname);
$groupid = urlencode($sitegroup);
echo "<tr><td colspan=\"5\"><a name=\"$gval\"></a><hr noshade size=\"1\" class=\"linecolor\"></td></tr>";
// Get entries for this group
$itemquery = "SELECT * FROM " .$DB_Prefix ."_permissions WHERE SiteGroup = '$groupname' ORDER BY ID";
$itemresult = mysql_query($itemquery, $dblink) or die ("Unable to select permission entries. Try again later.");
while ($itemrow = mysql_fetch_array($itemresult))
{
echo "<tr>";
echo "<td width=\"100%\" nowrap>" .stripslashes($itemrow[Feature]) ."</td>";
echo "<td>$itemrow[SetPg].php</td>";
echo "<td nowrap align=\"right\">$sitegroup</td>";
echo "<td nowrap align=\"left\">";
if ($gval == 0)
{
$downgval = $gval+1;
echo "<a href=\"permissions.php?pid=$itemrow[ID]&setgroup=$downgval#$downgval\">";
echo "<img alt=\"down\" border=\"0\" src=\"images/downarrow.gif\" width=\"9\" height=\"10\"></a> ";
echo "<img alt=\"-\" border=\"0\" src=\"images/spacer.gif\" width=\"9\" height=\"1\">";
}
else if ($gval == count($group)-1)
{
$upgval = $gval-1;
echo "<img alt=\"-\" border=\"0\" src=\"images/spacer.gif\" width=\"9\" height=\"1\"> ";
echo "<a href=\"permissions.php?pid=$itemrow[ID]&setgroup=$upgval#$upgval\">";
echo "<img alt=\"up\" border=\"0\" src=\"images/uparrow.gif\" width=\"9\" height=\"10\"></a>";
}
else
{
$downgval = $gval+1;
$upgval = $gval-1;
echo "<a href=\"permissions.php?pid=$itemrow[ID]&setgroup=$downgval#$downgval\">";
echo "<img alt=\"down\" border=\"0\" src=\"images/downarrow.gif\" width=\"9\" height=\"10\"></a> ";
echo "<a href=\"permissions.php?pid=$itemrow[ID]&setgroup=$upgval#$upgval\">";
echo "<img alt=\"up\" border=\"0\" src=\"images/uparrow.gif\" width=\"9\" height=\"10\"></a>";
}
echo "&nbsp;&nbsp;&nbsp;";
echo "</td>";
echo "<td nowrap align=\"center\">";
if ($itemrow[GivePermission] == "No")
echo "<a href=\"permissions.php?pid=$itemrow[ID]&setperm=Yes#$gval\" class=\"lightcolortext\">Yes</a> | <span class=\"highlighttext\">No</span>";
else
echo "<span class=\"highlighttext\">Yes</span> | <a href=\"permissions.php?pid=$itemrow[ID]&setperm=No#$gval\" class=\"lightcolortext\">No</a>";
echo "</td>";
echo "</tr>";
// Display subpermissions if they exist
$subquery = "SELECT * FROM " .$DB_Prefix ."_permissions WHERE SetPg = '$itemrow[SetPg]'";
$subquery .= " AND SiteGroup = '' ORDER BY ID";
$subresult = mysql_query($subquery, $dblink) or die ("Unable to select permission entries. Try again later.");
while ($subrow = mysql_fetch_array($subresult))
{
echo "<tr>";
echo "<td width=\"100%\" colspan=\"4\" nowrap>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;";
echo stripslashes($subrow[Feature]);
echo "</td>";
echo "<td nowrap align=\"center\">";
if ($subrow[GivePermission] == "No")
echo "<a href=\"permissions.php?pid=$subrow[ID]&setperm=Yes#$gval\" class=\"lightcolortext\">Yes</a> | <span class=\"highlighttext\">No</span>";
else
echo "<span class=\"highlighttext\">Yes</span> | <a href=\"permissions.php?pid=$subrow[ID]&setperm=No#$gval\" class=\"lightcolortext\">No</a>";
echo "</td>";
echo "</tr>";
}
}
echo "<tr>";
echo "<td width=\"100%\" colspan=\"4\" nowrap><br>Set all permissions for &quot;$sitegroup&quot;</td>";
echo "<td nowrap align=\"center\"><br>";
echo "<a href=\"permissions.php?gid=$gval&setperm=Yes#$gval\" class=\"lightcolortext\">Yes</a> | ";
echo "<a href=\"permissions.php?gid=$gval&setperm=No#$gval\" class=\"lightcolortext\">No</a>";
echo "</td>";
echo "</tr>";
}
?>

</table>
</center>
</div>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
