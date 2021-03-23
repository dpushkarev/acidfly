<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($regname)
$regname = str_replace('"', "&quot;", stripslashes($regname));
?>
<p>Use the form below to find the guest you would like to purchase a gift for. 
Enter the first and/or last name of the registrant, as well as the event month 
and date if known.</p>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="right">Registrant's Name:</td>
<td valign="top" align="left"><input type="text" name="regname" value="<?php echo "$regname"; ?>" size="20"></td>
</tr>
<tr>
<td valign="top" align="right">Event Date:</td>
<td valign="top" align="left">
<select size="1" name="eventdate">
<?php
echo "<option ";
if (!$eventdate)
echo "selected ";
echo "value=\"\">Not Known</option>";
$thismonth = date("m");
$thisyear = date("y");
for ($mo = $thismonth; $mo < $thismonth+36; ++$mo)
{
$modmo = $mo % 12;
$divmo = (int) ($mo / 12);
// In December, set values
if ($modmo == 0)
{
$curmo = str_pad(12, 2, "0", STR_PAD_LEFT);
$curyr = 2000 + ($thisyear + $divmo - 1);
}
else
{
$curmo = str_pad($modmo, 2, "0", STR_PAD_LEFT);
$curyr = 2000 + ($thisyear + $divmo);
}
$display_date = date("F Y", mktime (0,0,0,$curmo,"1",$curyr));
echo "<option ";
if (isset($eventdate) AND $eventdate == "$curmo-$curyr")
echo "selected ";
echo "value=\"$curmo-$curyr\">$display_date</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td colspan="2" valign="top" align="center">
<input type="hidden" name="mode" value="search">
<input type="submit" value="Find Registries" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>
<?php
echo "<p align=\"center\">";
echo "<a href=\"registry.$pageext?show=register$addlink\">Set Up Your Own Registry</a> | ";
echo "<a href=\"registry.$pageext?show=login$addlink\">Existing Users Log In</a></p>";
?>
