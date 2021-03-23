<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if (!$regname OR strlen($regname) <= 2)
{
echo "<p align=\"center\" class=\"salecolor\">";
if ($Registry == "bridal")
echo "Please enter at least two characters of the bride name or groom's name.";
else if ($Registry == "baby")
echo "Please enter at least two characters of the baby's name or the parents'/guardians' names.";
else
echo "Please enter at least two characters of the registrant's name.";
echo "</p>";
$show = "search";
}
else
{
$regquery = "SELECT * FROM " .$DB_Prefix ."_registry WHERE Type = 'Public' AND (";
$registrynames = explode(" ", $regname);
for ($i=0; $i < count($registrynames); ++$i)
{
if ($i > 0)
$regquery .= " OR ";
$regquery .= "RegName1 LIKE '%$registrynames[$i]%' OR RegName2 LIKE '%$registrynames[$i]%'";
}
$regquery .= ")";
if ($eventdate)
{
$expevent = explode("-", $eventdate);
$noofdays = date("t", mktime (0,0,0,$expevent[0],"1",$expevent[1]));
$startevent = $expevent[1] ."-" .$expevent[0] ."-01";
$endevent = $expevent[1] ."-" .$expevent[0] ."-" .$noofdays;
$regquery .= " AND EventDate >= '$startevent' AND EventDate <= '$endevent'";
}
$regresult = mysql_query($regquery, $dblink) or die ("Unable to select. Try again later.");
$regnum = mysql_num_rows($regresult);
if ($regnum == 0)
{
echo "<p align=\"center\" class=\"salecolor\">";
echo "There are no registrants that match this criteria. ";
echo "Please try again.</p>";
$show = "search";
}
else
{
?>
<p align="center">The following registrants were found:</p>
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="left" class="accent">
<?php
if ($Registry == "bridal")
echo "Bride and Groom";
else if ($Registry == "baby")
echo "Parents";
else
echo "Registrant";
?>
</td>
<td valign="top" align="center" class="accent">Location</td>
<td valign="top" align="center" class="accent">
<?php
if ($Registry == "bridal")
echo "Wedding Date";
else if ($Registry == "baby")
echo "Due Date";
else
echo "Event Date";
?>
</td>
</tr>
<?php
while ($regrow = mysql_fetch_array($regresult))
{
?>
<tr>
<td valign="top" align="left">
<?php
echo "<a href=\"registry.$pageext?rgid=$regrow[ID]&rguser=$regrow[RegUser]\">";
echo stripslashes($regrow[RegName1]);
if ($regrow[RegName2] AND $Registry == "bridal")
echo " &amp; " .stripslashes($regrow[RegName2]);
else if ($regrow[RegName2] AND $Registry == "baby")
echo " (" .stripslashes($regrow[RegName2]) .")";
echo "</a>";
?>
</td>
<td valign="top" align="center">
<?php
if ($regrow[ShipToState] OR $regrow[ShipToCountry])
{
echo stripslashes($regrow[ShipToState]);
if ($regrow[ShipToCountry] != "" AND strtolower($regrow[ShipToCountry]) != "usa"  AND strtolower($regrow[ShipToCountry]) != "united states" AND strtolower($regrow[ShipToCountry]) != "us" AND strtolower($regrow[ShipToCountry]) != "united states" AND strtolower($regrow[ShipToCountry]) != "united states of america")
{
if ($regrow[ShipToState])
echo ", ";
echo stripslashes($regrow[ShipToCountry]);
}
}
else
echo "N/A";
?>
</td>
<td valign="top" align="center">
<?php
if ($regrow[EventDate] != 0)
{
$splitdate = explode("-",$regrow[EventDate]);
$eventdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
else
$eventdate = "N/A";
echo "$eventdate";
?>
</td>
</tr>
<?php
}
?>
</table>
<?php
echo "<p align=\"center\">";
echo "<a href=\"registry.$pageext?show=search\">Search again</a> | ";
echo "<a href=\"registry.$pageext?show=register$addlink\">Set Up Your Own Registry</a> | ";
echo "<a href=\"registry.$pageext?show=login$addlink\">Existing Users Log In</a></p>";
}
}
?>
