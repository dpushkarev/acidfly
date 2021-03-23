<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($gotodate)
{
$goto = explode("~", $gotodate);
$curmonth = $goto[0];
$curyear = $goto[1];
}

if (!$curmonth)
$curmonth = date("m", mktime (0,0,0,date("m"),1,date("Y")));
if (!$curyear)
$curyear = date("Y", mktime (0,0,0,date("m"),1,date("Y")));
$curdate = date("F Y", mktime (0,0,0,$curmonth,1,$curyear));
$pastmonth = date("m", mktime (0,0,0,$curmonth-1,1,$curyear));
$pastyear = date("Y", mktime (0,0,0,$curmonth-1,1,$curyear));
$pastdate = date("M Y", mktime (0,0,0,$pastmonth,1,$pastyear));
$nextmonth = date("m", mktime (0,0,0,$curmonth+1,1,$curyear));
$nextyear = date("Y", mktime (0,0,0,$curmonth+1,1,$curyear));
$nextdate = date("M Y", mktime (0,0,0,$nextmonth,1,$nextyear));
$enddate = date("t", mktime (0,0,0,$curmonth,1,$curyear));

$startoffset = date("w", mktime (0,0,0,$curmonth,1,$curyear));
$endoffset = date("w", mktime (0,0,0,$curmonth,$enddate,$curyear));
$endoffset = 6 - $endoffset;
?>

<table border="0" cellpadding="5" cellspacing="0" width ="350" align="center">
<tr>
<td align="left" class="smfont">
<?php
echo "<a href=\"events.$pageext?curmonth=$pastmonth&curyear=$pastyear\">&lt;- $pastdate</a>";
?>
</td>
<td align="center" class="boldtext">
<?php echo "$curdate"; ?>
</td>
<td align="right" class="smfont">
<?php
echo "<a href=\"events.$pageext?curmonth=$nextmonth&curyear=$nextyear\">$nextdate -&gt;</a>";
?>
</td>
</tr>
</table>
<table cellpadding="3" cellspacing="0" align="center" width="350" class="accenttable">
<?php
echo "<tr class=\"accentcell\">";
echo "<td width=\"50\">Sun</td>";
echo "<td width=\"50\">Mon</td>";
echo "<td width=\"50\">Tue</td>";
echo "<td width=\"50\">Wed</td>";
echo "<td width=\"50\">Thu</td>";
echo "<td width=\"50\">Fri</td>";
echo "<td width=\"50\">Sat</td>";
echo "</tr>";

if ($startoffset > 0)
{
echo "<tr>";
for ($s = 1; $s <= $startoffset; ++$s)
{
echo "<td width=\"50\" height=\"40\" valign=\"top\" align=\"left\">&nbsp;</td>";
}
}

$begdate = date("Y-m-d", mktime (0,0,0,$curmonth,1,$curyear));
$finaldate = date("Y-m-d", mktime (0,0,0,$curmonth,$enddate,$curyear));

for ($c = 1; $c <= $enddate; ++$c)
{
$meddate = date("Y-m-d", mktime (0,0,0,$curmonth,$c,$curyear));
$evquery = "SELECT ID FROM " .$DB_Prefix ."_events WHERE Date = '$meddate' AND Expire > '$today' GROUP BY Date";
$evresult = mysql_query($evquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($evresult) > 0)
$eventid = "Yes";
else
$eventid = "No";
if (($c == 1 AND $startoffset == 0) OR ($c + $startoffset) % 7 - 1 == 0)
echo "<tr>";
echo "<td width=\"50\" height=\"40\" valign=\"top\" align=\"left\"";
if ($eventid == "Yes")
echo " bgcolor=\"#FFFFFF\"";
echo " class=\"itemcolor\">";
if ($eventid == "Yes")
echo "<a href=\"events.$pageext?curmonth=$curmonth&curyear=$curyear&event=$meddate#event\"><b>$c</b></a>";
else
echo "$c";
if ("$curyear-$curmonth-$c" == $today)
echo " *";
echo "</td>";
if (($c == $enddate AND $endoffset == 0) OR ($c + $startoffset) % 7 == 0)
echo "</tr>";
}

if ($endoffset > 0)
{
for ($e = 1; $e <= $endoffset; ++$e)
{
echo "<td width=\"50\" height=\"40\" valign=\"top\" align=\"left\">&nbsp;</td>";
}
}

?>
</table>

<?php
if ($event)
{
echo "<a name=\"event\"></a>";
$desquery = "SELECT Name, Description FROM " .$DB_Prefix ."_events WHERE Date='$event'";
$desresult = mysql_query($desquery, $dblink) or die ("Unable to select. Try again later.");
while ($desrow = mysql_fetch_row($desresult))
{
$eventname = stripslashes($desrow[0]);
$description = stripslashes($desrow[1]);
if (strstr($description, '<') == FALSE AND strstr($description, '>') == FALSE)
$description = str_replace ("\r", "<br>", $description);
$splitdate = explode("-",$event);
$eventdate = date("l, F j, Y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
echo "<p align=\"center\">";
echo "<b>$eventname</b>";
echo "<br><i>$eventdate</i></p>";
echo "<p>$description</p>";
}
echo "<p align=\"center\">";
echo "<a href=\"events.$pageext?curmonth=$curmonth&curyear=$curyear\">All $curdate Events</a>";
echo "</p>";
}
else
{
$getdateinfo = "$curyear-$curmonth";
$desquery = "SELECT ID, Name, Date, Description FROM " .$DB_Prefix ."_events WHERE Date LIKE '$getdateinfo-%' AND Expire > '$today'";
$desresult = mysql_query($desquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($desresult) > 0)
{
echo "&nbsp;<table align=\"center\" border=\"0\">";
echo "<tr>";
echo "<td class=\"boldtext\">Events This Month:</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<ul>";
while ($desrow = mysql_fetch_row($desresult))
{
$eventname = stripslashes($desrow[1]);
if ($desrow[2] != 0)
{
$splitdate = explode("-",$desrow[2]);
$eventdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
else
$eventdate = "";
echo "<li>";
if ($desrow[3])
echo "<a href=\"events.$pageext?curmonth=$curmonth&curyear=$curyear&event=$desrow[2]#event\">$eventname ($eventdate)</a>";
else
echo "$eventname ($eventdate)";
echo "</li>";
}
echo "</ul>";
echo "</td>";
echo "</tr>";
echo "</table>";
}
?>

<form action="<?php echo "events.$pageext"; ?>" method="post">
<p align="center">Go to: 
<select name="gotodate" size="1">
<?php
$curgoto = date("m~Y", mktime (0,0,0,date("m"),1,date("Y")));
for ($i = 1; $i <= 16; ++$i)
{
$goto1 = date("m~Y", mktime (0,0,0,date("m")-4+$i,1,date("Y")));
$goto2 = date("F Y", mktime (0,0,0,date("m")-4+$i,1,date("Y")));
echo "<option ";
if (IsSet($gotodate) AND $gotodate == $goto1)
echo "selected ";
else if ($curgoto == $goto1)
echo "selected ";
echo "value=\"$goto1\">$goto2</option>";
}
?>
</select> 
<input type="submit" value="Go" class="formbutton">
</p>
</form>

<?php
}
?>
