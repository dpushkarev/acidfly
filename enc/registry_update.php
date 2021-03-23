<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// If user is logged in
if ($regnum == 1)
{

// IF REGISTERED, UPDATE INFO
if ($_POST[mode] == "update")
{
if (!$_POST[regname1] AND $Registry == "bridal")
$regerror = "Please enter the bride's name.";
else if (!$_POST[regname2] AND $Registry == "bridal")
$regerror = "Please enter the groom's name.";
else if (!$_POST[regname1] AND $Registry == "gift")
$regerror = "Please enter the receiver's name.";
else if (!$_POST[regemail])
$regerror = "Please enter the registrant's email address.";
else if (!$_POST[eventdate] AND $Registry == "bridal")
$regerror = "Please enter the wedding date.";
else if (!$_POST[eventdate] AND $Registry == "baby")
$regerror = "Please enter the baby's due date.";
// IF NO ERROR MESSAGE EXISTS, UPDATE INFO
if ($regerror)
echo "<p align=\"center\" class=\"salecolor\">$regerror</p>";
else
{
$addregname1 = addslash_mq(stripbadstuff($regname1));
$addregname2 = addslash_mq(stripbadstuff($regname2));
$addshipname = addslash_mq(stripbadstuff($shiptoname));
$addshipaddress = addslash_mq(stripbadstuff($shiptoaddress));
$addshipcity = addslash_mq(stripbadstuff($shiptocity));
$addshipstate = addslash_mq(stripbadstuff($shiptostate));
$addshipcountry = addslash_mq(stripbadstuff($shiptocountry));
if ($eventdate != 0)
{
$splitdate = explode("/",$eventdate);
$dateofevent = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
$updquery = "UPDATE " .$DB_Prefix ."_registry SET Email='$regemail', RegName1='$addregname1', ";
$updquery .= "RegName2='$addregname2', ShipToName='$addshipname', ";
$updquery .= "ShipToAddress='$addshipaddress', ShipToCity='$addshipcity', ";
$updquery .= "ShipToState='$addshipstate', ShipToZip='$shiptozip', ";
$updquery .= "ShipToCountry='$addshipcountry', EventDate='$dateofevent', ";
$updquery .= "Type='$regtype' WHERE ID='$registry_id'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
}

$getquery = "SELECT * FROM " .$DB_Prefix ."_registry WHERE ID='$regrow[0]'";
$getresult = mysql_query($getquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($getresult) == 1)
{
$getrow = mysql_fetch_array($getresult);
$regemail = $getrow[Email];
$regname1 = str_replace('"', "&quot;", stripslashes($getrow[RegName1]));
$regname2 = str_replace('"', "&quot;", stripslashes($getrow[RegName2]));
$shiptoname = str_replace('"', "&quot;", stripslashes($getrow[ShipToName]));
$shiptoaddress = str_replace('"', "&quot;", stripslashes($getrow[ShipToAddress]));
$shiptocity = str_replace('"', "&quot;", stripslashes($getrow[ShipToCity]));
$shiptostate = str_replace('"', "&quot;", stripslashes($getrow[ShipToState]));
$shiptozip = $getrow[ShipToZip];
$shiptocountry = str_replace('"', "&quot;", stripslashes($getrow[ShipToCountry]));
$regtype = $getrow[Type];
if ($getrow[EventDate]!= 0)
{
$splitdate = explode("-",$getrow[EventDate]);
$eventdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
?>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="right">
<?php
if ($Registry == "bridal")
echo "Bride's Name:";
else if ($Registry == "baby")
echo "Parents' Names:";
else
echo "Registered To:";
?>
<td valign="top" align="left" colspan="3"><input type="text" name="regname1" value="<?php echo "$regname1"; ?>" size="33"></td>
</tr>
<?php
if ($Registry == "bridal" OR $Registry == "baby")
{
?>
<tr>
<td valign="top" align="right">
<?php
if ($Registry == "bridal")
echo "Groom's Name:";
else if ($Registry == "baby")
echo "Baby's Name:";
?>
</td>
<td valign="top" align="left" colspan="3"><input type="text" name="regname2" value="<?php echo "$regname2"; ?>" size="33"></td>
</tr>
<?php
}
?>
<tr>
<td valign="top" align="right">Email Address:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="regemail" value="<?php echo "$regemail"; ?>" size="33"></td>
</tr>
<tr>
<td valign="top" align="right">
<?php
if ($Registry == "bridal")
echo "Wedding Date:";
else if ($Registry == "baby")
echo "Due Date:";
else
echo "Event Date:";
?>
</td>
<td valign="top" align="left" colspan="3"><input type="text" name="eventdate" value="<?php echo "$eventdate"; ?>" size="12"> (MM/DD/YY)</td>
</tr>
<tr>
<td valign="top" align="right">Ship To:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptoname" value="<?php echo "$shiptoname"; ?>" size="33"></td>
</tr>
<tr>
<td valign="top" align="right">Street Address:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptoaddress" value="<?php echo "$shiptoaddress"; ?>" size="33"></td>
</tr>
<tr>
<td valign="top" align="right">City:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptocity" value="<?php echo "$shiptocity"; ?>" size="33"></td>
</tr>
<tr>
<td valign="top" align="right">State/Region:</td>
<td valign="top" align="left"><input type="text" name="shiptostate" value="<?php echo "$shiptostate"; ?>" size="6"></td>
<td valign="top" align="right">Zip Code:</td>
<td valign="top" align="left"><input type="text" name="shiptozip" value="<?php echo "$shiptozip"; ?>" size="10"></td>
</tr>
<tr>
<td valign="top" align="right">Country:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptocountry" value="<?php echo "$shiptocountry"; ?>" size="33"></td>
</tr>
<tr>
<td valign="top" align="right">Registry Type:</td>
<td valign="top" align="left" colspan="3">
<select size="1" name="regtype">
<?php
echo "<option ";
if ($regtype == "Private")
echo "selected ";
echo "value=\"Private\">Private - Not in Searches</option>";
echo "<option ";
if ($regtype == "Public")
echo "selected ";
echo "value=\"Public\">Public - Seen By Everyone</option>";
?>
</select>
</td>
</tr>
<tr>
<td colspan="4" valign="top" align="center">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="registry_id" value="<?php echo "$regrow[0]"; ?>">
<input type="submit" value="Update" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>
<?php
}
}
echo "<p align=\"center\">";
echo "<a href=\"registry.$pageext?mode=registry\">View Registry</a> | ";
echo "<a href=\"registry.$pageext?logout=yes\">Log Out</a></p>";
?>
