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

if ($mode == "update")
{
$groupname = addslash_mq($groupname);
if ($expiredate != 0)
{
$splitdate = explode("/",$expiredate);
$expiredate = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
if (!$expiredate)
$expiredate = date("Y-m-d", mktime (0,0,0,date("m"),date("d")+14,date("Y")));
$code = str_replace ('"', "", $code);
$code = str_replace("'", "", $code);
$cquery = "SELECT ID FROM " .$DB_Prefix ."_coupons WHERE CodeNumber='$code'";
if ($mid)
$cquery .= " AND ID <> '$mid'";
$cresult = mysql_query($cquery, $dblink) or die ("Unable to select. Try again later.");
$cnum = mysql_num_rows($cresult);
if ($cnum > 0)
$error = "<p align=\"center\">Sorry, but this code number already exists. Please try again.</p>";
else if ($mid)
{
$updquery = "UPDATE " .$DB_Prefix ."_coupons SET GroupName='$groupname', Discount='$discount', ";
$updquery .= "CodeNumber='$code', ExpireDate='$expiredate' WHERE ID='$mid'";
$updresult = mysql_query($updquery, $dblink) or die ("Unable to select. Try again later.");
}
else
{
$insquery = "INSERT INTO " .$DB_Prefix ."_coupons (GroupName, Discount, CodeNumber, ";
$insquery .= "ExpireDate) VALUES ('$groupname', '$discount', '$code', '$expiredate')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

if ($mode == "Yes" AND $mid)
{
$delquery = "DELETE FROM " .$DB_Prefix ."_coupons WHERE ID='$mid'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
}

if ($mode == "Add" OR $mode == "Edit")
{
if ($mode == "Edit" AND $mid)
{
$memquery = "SELECT * FROM " .$DB_Prefix ."_coupons WHERE ID='$mid'";
$memresult = mysql_query($memquery, $dblink) or die ("Unable to select. Try again later.");
$memrow = mysql_fetch_array($memresult);
$groupname = str_replace('"', "&quot;", stripslashes($memrow[GroupName]));
$discount = (float)$memrow[Discount];
$code = $memrow[CodeNumber];
if ($memrow[ExpireDate] != 0)
{
$splitdate = explode("-",$memrow[ExpireDate]);
$expiredate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
}
if (!$expiredate)
$expiredate = date("n/j/y", mktime (0,0,0,date("m"),date("d")+14,date("Y")));
?>
<form method="POST" action="coupons.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="4"><?php echo "$mode Member"; ?></td>
</tr>
<tr>
<td align="right">Group Name:</td>
<td align="left" colspan="3">
<input type="text" name="groupname" size="48" maxlength="50" value="<?php echo "$groupname"; ?>">
</td>
</tr>
<tr>
<td align="right">Coupon/Code #:</td>
<td align="left">
<input type="text" name="code" size="12" value="<?php echo "$code"; ?>" maxlength="15">
</td>
<td align="right">Discount:</td>
<td align="left"><input type="text" name="discount" size="5" value="<?php echo "$discount"; ?>"> %</td>
</tr>
<tr>
<td align="right">Expire Date:</td>
<td align="left" colspan="3">
<input type="text" name="expiredate" size="12" value="<?php echo "$expiredate"; ?>"> 
(MM/DD/YY)
</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="4">
<?php
if ($mid AND $mode == "Edit")
echo "<input type=\"hidden\" value=\"$mid\" name=\"mid\">";
?>
<input type="hidden" value="update" name="mode">
<input type="submit" class="button" value="Update" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<p align="center"><a href="coupons.php">Back to Coupons</a></p>
<?php
}

else if ($mode == "Delete" AND $mid)
{
?>
<form method="POST" action="coupons.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname">Delete Member Discount</td>
</tr>
<tr>
<td>Are you sure you want to delete this member discount? The information will be deleted permanently.</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="hidden" value="<?php echo "$mid"; ?>" name="mid">
<input type="submit" class="button" value="Yes" name="mode"> 
<input type="submit" class="button" value="No" name="mode">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

else if ($mode == "Get Code" AND $mid)
{
$codequery = "SELECT CodeNumber, ExpireDate FROM " .$DB_Prefix ."_coupons WHERE ID='$mid'";
$coderesult = mysql_query($codequery, $dblink) or die ("Unable to select. Try again later.");
$coderow = mysql_fetch_row($coderesult);
$codenum = $coderow[0];
if ($coderow[1] != 0)
{
$splitdate = explode("-",$coderow[1]);
$expdate = mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]);
}
$thisdate = mktime (0,0,0,date("m"),date("d"),date("Y"));
?>
<form method="POST" action="coupons.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname">Member Code</td>
</tr>
<?php
if ($thisdate > $expdate)
echo "<tr><td align=\"center\">This code has expired.</td></tr>";
else
{
echo "<tr><td align=\"center\">Give your members the following coupon code:</td></tr>";
echo "<tr><td align=\"center\" class=\"highlighttext\">";
$setcodeurl = "$urldir?code=$codenum";
echo "<a href=\"$setcodeurl\" target=\"_blank\">$setcodeurl</a>";
echo "</td></tr>";
}
?>
</table>
</center>
</div>
</form>
<p align="center"><a href="coupons.php">Back to Coupons</a></p>
<?php
}

else
{
if ($error)
echo "$error";
?>

<form method="POST" action="coupons.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname">Discount Coupons</td>
</tr>
<tr>
<td valign="middle" align="center">
<?php
$memquery = "SELECT * FROM " .$DB_Prefix ."_coupons ORDER BY GroupName";
$memresult = mysql_query($memquery, $dblink) or die ("Unable to select. Try again later.");
$memnum = mysql_num_rows($memresult);
if ($memnum == 0)
echo "<i>No coupons entered.</i>";
else
{
echo "<select size=\"1\" name=\"mid\">";
for ($m=1; $memrow = mysql_fetch_array($memresult); ++$m)
{
echo "<option ";
if ($m == 1)
echo "selected ";
echo "value=\"$memrow[ID]\">";
echo stripslashes($memrow[GroupName]);
echo " - $memrow[Discount]%";
$todaysdate = date("Y-m-d");
if ($memrow[ExpireDate] < $todaysdate)
echo " (expired)";
echo "</option>";
}
echo "</select>";
}
?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Add" name="mode"> 
<?php
if ($memnum > 0)
{
echo " | <input type=\"submit\" class=\"button\" value=\"Edit\" name=\"mode\">";
echo " | <input type=\"submit\" class=\"button\" value=\"Delete\" name=\"mode\">";
echo " | <input type=\"submit\" class=\"button\" value=\"Get Code\" name=\"mode\">";
}
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
