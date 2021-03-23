<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="120" valign="top" class="padded">
<?php
foreach ($group as $linkval => $linkset)
{
if ($linkval < $linkend)
{
$lkquery = "SELECT * FROM " .$DB_Prefix ."_permissions WHERE ";
if ($set_master_key == "no")
$lkquery .= "GivePermission = 'Yes' AND ";
$lkquery .= "SiteGroup = '$linkset' ORDER BY ID";
$lkresult = mysql_query($lkquery, $dblink) or die ("Unable to select links.");
if (mysql_num_rows($lkresult) > 0)
{
echo "<p><b>$linkset</b>";
for ($lk=1; $lkrow = mysql_fetch_array($lkresult); ++$lk)
{
$featurename = str_replace(" ", "&nbsp;", stripslashes($lkrow[Feature]));
if ($setpg == $lkrow[SetPg])
echo "<br><a href=\"$lkrow[SetPg].php\" class=\"linkset\">$featurename</a>";
else
echo "<br><a href=\"$lkrow[SetPg].php\" class=\"links\">$featurename</a>";
}
echo "<br><img src=\"images/spacer.gif\" width=\"120\" height=\"1\">";
echo "</p>";
}
}
}
?>
</td>
<td width="1" valign="top" class="verticalline"><img src="images/spacer.gif" width="1" height="1"></td>
<td width="100%" valign="top" class="padded">