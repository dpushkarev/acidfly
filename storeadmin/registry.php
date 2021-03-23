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

$setpg_lower = "registry";
$setpg_upper = "Gift Registry";

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
$updvquery = "UPDATE " .$DB_Prefix ."_vars SET WishList='gift' WHERE WishList = 'none' AND ID='1'";
$updvresult = mysql_query($updvquery, $dblink) or die("Unable to update. Please try again later.");
}

if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
$updvquery = "UPDATE " .$DB_Prefix ."_vars SET WishList='none' WHERE WishList <> 'none' AND ID='1'";
$updvresult = mysql_query($updvquery, $dblink) or die("Unable to update. Please try again later.");
}

$varquery = "SELECT WishList FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
$varrow = mysql_fetch_row($varresult);
$Registry = $varrow[0];

// Finish delete record
if ($regid AND $submit == "Yes")
{
$delquery = "DELETE FROM " .$DB_Prefix ."_registry WHERE ID='$regid'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
$delrquery = "DELETE FROM " .$DB_Prefix ."_reglist WHERE RegistryID='$regid'";
$delrresult = mysql_query($delrquery, $dblink) or die("Unable to delete. Please try again later.");
}

// Update record
if ($submit == "Update")
{
$addregname1 = addslash_mq($regname1);
$addregname2 = addslash_mq($regname2);
$addshipname = addslash_mq($shiptoname);
$addshipaddress = addslash_mq($shiptoaddress);
$addshipcity = addslash_mq($shiptocity);
$addshipstate = addslash_mq($shiptostate);
$addshipcountry = addslash_mq($shiptocountry);
if ($evtdate != 0)
{
$splitdate = explode("/",$evtdate);
$dateofevent = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
// CHECK USER
if ($reguser AND (!$regid OR ($regid AND $olduser != $reguser)))
{
$chknum = 1;
$chklimit = 1;
while ($chknum > 0)
{
// Allow to go through loop 50 times then fail
if ($chklimit == 50)
{
$getuser = "failed";
break;
}
$chkquery = "SELECT RegUser FROM " .$DB_Prefix ."_registry WHERE RegUser='$reguser'";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
$chknum = mysql_num_rows($chkresult);
if ($chknum > 0)
{
$tempreguser = substr($reguser, 0, 6);
mt_srand ((double) microtime() * 10000000);
$tempreguser .= chr(mt_rand(97,122));
$tempreguser .= chr(mt_rand(97,122));
$reguser = $tempreguser;
}
++$chklimit;
}
}

// UPDATE
if ($getuser == "failed")
echo "<p align=\"center\">Sorry, but this user name was already in the system. Please try again.</p>";
else if (!$addregname1 OR !$reguser OR !$regpass)
echo "<p align=\"center\">Registrant could not be entered.</p>";
else if ($regid)
{
$updquery = "UPDATE " .$DB_Prefix ."_registry SET Email='$regemail', RegName1='$addregname1', ";
$updquery .= "RegName2='$addregname2', ShipToName='$addshipname', ";
$updquery .= "ShipToAddress='$addshipaddress', ShipToCity='$addshipcity', ";
$updquery .= "ShipToState='$addshipstate', ShipToZip='$shiptozip', Type='$regtype', ";
$updquery .= "ShipToCountry='$addshipcountry', EventDate='$dateofevent', ";
$updquery .= "RegUser='$reguser', RegPass='$regpass' WHERE ID='$regid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
// ADD
else
{
$createdate = date("Y-m-d");
$insquery = "INSERT INTO " .$DB_Prefix ."_registry (Email, RegName1, RegName2, ShipToName, ShipToAddress, ";
$insquery .= "ShipToCity, ShipToState, ShipToZip, Type, ShipToCountry, EventDate, RegUser, RegPass, ";
$insquery .= "CreateDate) VALUES ('$regemail', '$addregname1', '$addregname2', '$addshipname', ";
$insquery .= "'$addshipaddress', '$addshipcity', '$addshipstate', '$shiptozip', '$regtype', '$addshipcountry', ";
$insquery .= "'$dateofevent', '$reguser', '$regpass', '$createdate')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

// Delete record
if ($regid AND $md == "del")
{
?>

<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Delete Registrant</td>
</tr>
<tr>
<td valign="middle" align="center">Are you sure you want to delete this registrant? 
You will remove the registrant permanently, along with their registry items.</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="hidden" value="<?php echo "$regid"; ?>" name="regid">
<input type="hidden" value="<?php echo "$keyword"; ?>" name="keyword">
<input type="hidden" value="<?php echo "$eventdate"; ?>" name="eventdate">
<input type="hidden" value="<?php echo "$page"; ?>" name="page">
<input type="hidden" value="find" name="md">
<input type="submit" value="Yes" name="submit" class="button"> 
<input type="submit" value="No" name="submit" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}

// Edit products
else if ($regid AND $md == "itm" AND $action == "del")
{
?>
<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Delete Item</td>
</tr>
<tr>
<td valign="middle" align="center">Are you sure you want to delete this item? 
It will be removed permanently.</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="hidden" value="<?php echo "$regid"; ?>" name="regid">
<input type="hidden" value="<?php echo "$itemid"; ?>" name="itemid">
<input type="hidden" value="itm" name="md">
<input type="submit" value="Yes" name="action" class="button"> 
<input type="submit" value="No" name="action" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

else if ($regid AND $md == "itm")
{
// Delete registry entry
if ($action == "Yes")
{
$delquery = "DELETE FROM " .$DB_Prefix ."_reglist WHERE ID = '$itemid'";
$delresult = mysql_query($delquery, $dblink) or die ("Unable to delete. Try again later.");
}
// Modify items wanted
else if ($action == "dnwnt")
{
$updquery = "UPDATE " .$DB_Prefix ."_reglist SET QtyWanted = QtyWanted-1 WHERE ID = '$itemid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to decrease wanted. Try again later.");
}
else if ($action == "upwnt")
{
$updquery = "UPDATE " .$DB_Prefix ."_reglist SET QtyWanted = QtyWanted+1 WHERE ID = '$itemid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to increase wanted. Try again later.");
}
// Modify items received
else if ($action == "dnrcd")
{
$updquery = "UPDATE " .$DB_Prefix ."_reglist SET QtyReceived = QtyReceived-1 WHERE ID = '$itemid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to decrease received. Try again later.");
}
else if ($action == "uprcd")
{
$updquery = "UPDATE " .$DB_Prefix ."_reglist SET QtyReceived = QtyReceived+1 WHERE ID = '$itemid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to increase received. Try again later.");
}
$regquery = "SELECT " .$DB_Prefix ."_reglist.*, " .$DB_Prefix ."_items.Item ";
$regquery .= "FROM " .$DB_Prefix ."_reglist, " .$DB_Prefix ."_items ";
$regquery .= "WHERE " .$DB_Prefix ."_reglist.ProductID=" .$DB_Prefix ."_items.ID ";
$regquery .= "AND " .$DB_Prefix ."_reglist.RegistryID='$regid'";
$regresult = mysql_query($regquery, $dblink) or die ("Unable to select registry items. Try again later.");
$regnum = mysql_num_rows($regresult);
?>

<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="4">Edit Items</td>
</tr>
<?php
if ($regnum == 0)
{
?>
<tr>
<td colspan="4">Sorry, there are no items in the registry to edit.</td>
</tr>
<?php
}
else
{
?>
<tr>
<td width="100%"><u>Item</u></td>
<td align="center"><u>Wants</u></td>
<td align="center"><u>Rec'd</u></td>
<td align="center">&nbsp;</td>
</tr>
<?php
while ($regrow = mysql_fetch_array($regresult))
{
echo "<tr>";
echo "<td width=\"100%\">" .stripslashes($regrow[Item]) ." " .stripslashes($regrow[Options]) ."</td>";
echo "<td align=\"center\">" .(float)$regrow[QtyWanted];
if ($regrow[QtyWanted] > 0)
echo "<a href=\"registry.php?regid=$regid&itemid=$regrow[ID]&md=itm&action=dnwnt\"><img border=\"0\" alt=\"down\" src=\"images/downarrow.gif\" height=\"10\" width=\"9\"></a>";
echo "<a href=\"registry.php?regid=$regid&itemid=$regrow[ID]&md=itm&action=upwnt\"><img border=\"0\" alt=\"up\" src=\"images/uparrow.gif\" height=\"10\" width=\"9\"></a>";
echo "</td>";
echo "<td align=\"center\">" .(float)$regrow[QtyReceived];
if ($regrow[QtyReceived] > 0)
echo "<a href=\"registry.php?regid=$regid&itemid=$regrow[ID]&md=itm&action=dnrcd\"><img border=\"0\" alt=\"down\" src=\"images/downarrow.gif\" height=\"10\" width=\"9\"></a>";
echo "<a href=\"registry.php?regid=$regid&itemid=$regrow[ID]&md=itm&action=uprcd\"><img border=\"0\" alt=\"up\" src=\"images/uparrow.gif\" height=\"10\" width=\"9\"></a>";
echo "</td>";
echo "<td align=\"center\">";
echo "<a href=\"registry.php?regid=$regid&itemid=$regrow[ID]&md=itm&action=del\">Delete</a>";
echo "</td>";
echo "</tr>";
}
}
?>
<tr>
<td valign="middle" align="center" colspan="4">
<input type="hidden" value="<?php echo "$regid"; ?>" name="regid">
<input type="hidden" value="<?php echo "$keyword"; ?>" name="keyword">
<input type="hidden" value="<?php echo "$eventdate"; ?>" name="eventdate">
<input type="hidden" value="<?php echo "$page"; ?>" name="page">
<input type="hidden" value="find" name="md">
<input type="submit" value="Edit" name="submit" class="button"> 
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}

// Display one record
else if ($md == "dp")
{
?>
<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="4">Registrant Information</td>
</tr>
<?php
if ($regid)
{
$getquery = "SELECT * FROM " .$DB_Prefix ."_registry WHERE ID='$regid'";
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
$reguser = $getrow[RegUser];
$regpass = $getrow[RegPass];
if ($getrow[EventDate]!= 0)
{
$splitdate = explode("-",$getrow[EventDate]);
$evtdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
}
}
?>

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
<td valign="top" align="left" colspan="3"><input type="text" name="regname1" value="<?php echo "$regname1"; ?>" size="45"></td>
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
<td valign="top" align="left" colspan="3"><input type="text" name="regname2" value="<?php echo "$regname2"; ?>" size="45"></td>
</tr>
<?php
}
?>
<tr>
<td valign="top" align="right">Email Address:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="regemail" value="<?php echo "$regemail"; ?>" size="45"></td>
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
<td valign="top" align="left" colspan="3"><input type="text" name="evtdate" value="<?php echo "$evtdate"; ?>" size="12"> (MM/DD/YY)</td>
</tr>
<tr>
<td valign="top" align="right">Ship To:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptoname" value="<?php echo "$shiptoname"; ?>" size="45"></td>
</tr>
<tr>
<td valign="top" align="right">Street Address:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptoaddress" value="<?php echo "$shiptoaddress"; ?>" size="45"></td>
</tr>
<tr>
<td valign="top" align="right">City:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptocity" value="<?php echo "$shiptocity"; ?>" size="45"></td>
</tr>
<tr>
<td valign="top" align="right">State/Region:</td>
<td valign="top" align="left"><input type="text" name="shiptostate" value="<?php echo "$shiptostate"; ?>" size="6"></td>
<td valign="top" align="right">Zip Code:</td>
<td valign="top" align="left"><input type="text" name="shiptozip" value="<?php echo "$shiptozip"; ?>" size="10"></td>
</tr>
<tr>
<td valign="top" align="right">Country:</td>
<td valign="top" align="left" colspan="3"><input type="text" name="shiptocountry" value="<?php echo "$shiptocountry"; ?>" size="45"></td>
</tr>
<tr>
<td valign="top" align="right">User Name:</td>
<td valign="top" align="left"><input type="text" name="reguser" value="<?php echo "$reguser"; ?>" size="10" maxlength="8"></td>
<td valign="top" align="right">Password:</td>
<td valign="top" align="left"><input type="text" name="regpass" value="<?php echo "$regpass"; ?>" size="10" maxlength="8"></td>
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
<td valign="top" align="center" colspan="4">
<input type="hidden" value="find" name="md">
<input type="hidden" value="<?php echo "$regid"; ?>" name="regid">
<input type="hidden" value="<?php echo "$keyword"; ?>" name="keyword">
<input type="hidden" value="<?php echo "$eventdate"; ?>" name="eventdate">
<input type="hidden" value="<?php echo "$page"; ?>" name="page">
<input type="hidden" value="<?php echo "$reguser"; ?>" name="olduser">
<input type="submit" value="Update" name="submit" class="button"></td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\">";
echo "<a href=\"registry.php?md=find&keyword=$keyword&eventdate=$eventdate&page=$page\">";
echo "Back to List</a></p>";
}

// Search through registrants
else if ($md == "find")
{
?>

<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="5">Registrants</td>
</tr>
<?php
$regquery = "SELECT * FROM " .$DB_Prefix ."_registry";
if ($keyword OR $eventdate)
$regquery .= " WHERE ";
if ($keyword)
{
$keyword = str_replace ('"', "", stripslashes($keyword));
$keyword = str_replace ("'", "", stripslashes($keyword));
$regquery .= "(";
$registrynames = explode(" ", $keyword);
for ($i=0; $i < count($registrynames); ++$i)
{
if ($i > 0)
$regquery .= " OR ";
$regquery .= "RegName1 LIKE '%$registrynames[$i]%' OR RegName2 LIKE '%$registrynames[$i]%'";
}
$regquery .= ")";
}
if ($eventdate)
{
if ($keyword)
$regquery .= " AND";
$expevent = explode("-", $eventdate);
$noofdays = date("t", mktime (0,0,0,$expevent[0],"1",$expevent[1]));
$startevent = $expevent[1] ."-" .$expevent[0] ."-01";
$endevent = $expevent[1] ."-" .$expevent[0] ."-" .$noofdays;
$regquery .= " EventDate >= '$startevent' AND EventDate <= '$endevent'";
}
$regresult = mysql_query($regquery, $dblink) or die ("Unable to select. Try again later.");
$regnum = mysql_num_rows($regresult);
if ($regnum == 0)
{
echo "<tr><td align=\"center\" class=\"salecolor\" colspan=\"5\">";
echo "There are no registrants that match this criteria. ";
echo "Please try again.</td></tr>";
}
else
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\" class=\"accent\">";
if ($Registry == "bridal")
echo "Bride and Groom";
else if ($Registry == "baby")
echo "Parents";
else
echo "Registrant";
echo "</td>";
echo "<td valign=\"top\" align=\"center\" class=\"accent\">Location</td>";
echo "<td valign=\"top\" align=\"center\" class=\"accent\">";
if ($Registry == "bridal")
echo "Wedding Date";
else if ($Registry == "baby")
echo "Due Date";
else
echo "Event Date";
echo "</td>";
echo "<td valign=\"top\" align=\"center\" class=\"accent\">Type</td>";
echo "<td valign=\"top\" align=\"center\" class=\"accent\">Action</td>";
echo "</tr>";
while ($regrow = mysql_fetch_array($regresult))
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\">";
echo stripslashes($regrow[RegName1]);
if ($regrow[RegName2] AND $Registry == "bridal")
echo " &amp; " .stripslashes($regrow[RegName2]);
else if ($regrow[RegName2] AND $Registry == "baby")
echo " (" .stripslashes($regrow[RegName2]) .")";
echo "</td>";
echo "<td valign=\"top\" align=\"center\">";
if ($regrow[ShipToState] != "")
$showlocation = stripslashes($regrow[ShipToState]);
if ($regrow[ShipToCountry] != "United States")
$showlocation .= " " .stripslashes($regrow[ShipToCountry]);
if (!$showlocation)
$showlocation = "Unknown";
echo "$showlocation";
echo "</td>";
echo "<td valign=\"top\" align=\"center\">";
if ($regrow[EventDate] != 0)
{
$splitdate = explode("-",$regrow[EventDate]);
$evntdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
echo "$evntdate";
echo "</td>";
echo "<td valign=\"top\" align=\"center\">$regrow[Type]</td>";
echo "<td valign=\"top\" align=\"center\">";
$enckeyword = urlencode($keyword);
echo "<a href=\"registry.php?regid=$regrow[ID]&md=dp&keyword=$enckeyword&eventdate=$eventdate&page=$page\">Edit</a> | ";
echo "<a href=\"registry.php?regid=$regrow[ID]&md=del&keyword=$enckeyword&eventdate=$eventdate&page=$page\">Delete</a> | ";
echo "<a href=\"registry.php?regid=$regrow[ID]&md=itm&keyword=$enckeyword&eventdate=$eventdate&page=$page\">Items</a>";
echo "</td>";
echo "</tr>";
}
}
?>
</table>
</center>
</div>
</form>
<?php
echo "<p align=\"center\">";
echo "<a href=\"registry.php?md=dp&keyword=$enckeyword&eventdate=$eventdate&page=$page\">";
echo "Add New Registrant</a> | ";
echo "<a href=\"registry.php\">Search Again</a></p>";

}

else
{

$getquery = "SELECT ID, Active FROM " .$DB_Prefix ."_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysql_query($getquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($getresult) == 1)
{
$getrow = mysql_fetch_row($getresult);
$pgid = $getrow[0];
$setactive = $getrow[1];
}
if (!$setactive)
$setactive = "No";

if ($setactive == "Yes")
{
?>

<form method="POST" action="registry.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="2">Find Registrants</td>
</tr>
<tr>
<td valign="middle" align="right">Registrant Name(s):</td>
<td valign="middle" align="left"><input type="text" name="keyword" size="20"></td>
</tr>
<tr>
<td valign="middle" align="right">Event Date:</td>
<td valign="middle" align="left">
<select size="1" name="eventdate">
<?php
echo "<option selected value=\"\"></option>";
$thisyear = date("y");
$startyear = $thisyear-1;
$stopmo = 12*($thisyear+2);
for ($mo = 1; $mo < $stopmo; ++$mo)
{
$modmo = $mo % 12;
$divmo = (int) ($mo / 12);
// In December, set values
if ($modmo == 0)
{
$curmo = str_pad(12, 2, "0", STR_PAD_LEFT);
$curyr = 2000 + ($startyear + $divmo - 1);
}
else
{
$curmo = str_pad($modmo, 2, "0", STR_PAD_LEFT);
$curyr = 2000 + ($startyear + $divmo);
}
$display_date = date("F Y", mktime (0,0,0,$curmo,"1",$curyr));
echo "<option value=\"$curmo-$curyr\">$display_date</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="right"></td>
<td valign="middle" align="left"></td>
</tr>
<tr>
<td valign="middle" align="center" colspan="2">
<input type="hidden" value="find" name="md">
<input type="submit" value="Find Registrants" name="submit" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
if ($pgid)
$setpg_lower = "<a href=\"pages.php?edit=yes&pgid=$pgid\">$setpg_lower</a>";
}

if ($setactive == "No")
{
?>

<form method="POST" action="<?php echo "$setpg.php"; ?>">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
The gift registry page is currently inactive. If you would like to<br>activate it, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Gift Registry Page" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}
else
{
echo "<p align=\"center\" class=\"smalltext\">";
echo "<a href=\"pages.php?edit=yes&pgid=$pgid\">Contents</a> | ";
echo "<a href=\"registry.php?submit=Deactivate+Gift+Registry+Page\">Deactivate</a></p>";
}

}
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>