<html>

<head>
<title>Email</title>
<meta name="robots" content="noindex,nofollow">
<script language="php">
require_once("../stconfig.php");
// Sets System Variables
$varquery = "SELECT * FROM " .$DB_Prefix ."_vars";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($varresult) == 1)
{
$varrow = mysqli_fetch_array($varresult);
$Site_Name=stripslashes($varrow[SiteName]);
$Catalog_Page=$varrow[CatalogPage];
$Admin_Email=$varrow[AdminEmail];
if ($varrow[Theme] == "custom.htm")
$themename = $varrow[Theme];
else
$themename = $varrow[Theme] ."/template.htm";
}
include("../$Inc_Dir/style.php");
$starttime = date("YmdHis", mktime (date("H"),date("i")-10,date("s"),date("m"),date("d"),date("Y")));
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<?php
if ($_POST[Submit] == "Send Email")
{
if (!$_POST[fromemail] OR !$_POST[toemail] OR (!$_POST[item] AND !$_POST[page]))
die ("<p>Sorry, but we did not receive all of your information. Please go back and try again.</p>");
else
{
// Check for domain emails
$fromemailcheck = explode("@", $_POST[fromemail]);
$toemailcheck = explode("@", $_POST[toemail]);
if (substr_count($varrow[2], $fromemailcheck[1]) > 0 OR substr_count($varrow[2], $toemailcheck[1]) > 0)
die ("<p>Sorry, but messages cannot be sent from this domain. Please try again.</p>");
else
{
// Compare to input var
$chquery = "SELECT * FROM " .$DB_Prefix ."_check WHERE CheckVar='$_POST[checkvar]' AND DateTime>'$starttime'";
$chresult = mysqli_query($dblink, $chquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($chresult) == 0)
die ("<p>Sorry, but the form has timed out or has already been processed.</p>");
else
{
if ($_POST[item])
{
$itemquery = "SELECT Item FROM " .$DB_Prefix ."_items WHERE ID='$_POST[item]'";
$itemresult = mysqli_query($dblink, $itemquery) or die ("Unable to select your item. Try again later.");
$itemrow = mysqli_fetch_row($itemresult);
$urlname = stripslashes($itemrow[0]);
$urladdress = $urldir ."/" .$Catalog_Page ."?item=" .$item;
}
else if ($_POST[page])
{
$emailpage = str_replace(".$pageext", "", $_POST[page]);
$pagequery = "SELECT PageTitle FROM " .$DB_Prefix ."_pages WHERE PageName='$emailpage'";
if ($dir)
$pagequery .= " AND PageType='additional'";
$pageresult = mysqli_query($dblink, $pagequery) or die ("Unable to select your page. Try again later.");
$pagerow = mysqli_fetch_row($pageresult);
$urlname = stripslashes($pagerow[0]);
if ($dir)
$urladdress = $urldir ."/" .$dir ."/" .$page;
else
$urladdress = $urldir ."/" .$page;
}

$message = stripslashes($message);
$message = str_replace("@", " at ", $message);
if ($message)
$addl = "\r\n\r\n$fromname also included this message:\r\n$message";
else
$addl = "";
if ($_POST[fromname])
$strfromname = stripslashes($_POST[fromname]);
else
$strfromname = "A friend at " .$_POST[fromemail];
if ($_POST[toname])
{
$strtoname = stripslashes($_POST[toname]);
$entry = ", $strtoname";
$to_name = "$strtoname at ";
}

mail("$toemail", "Check This Out", "Hello$entry.

$strfromname was viewing $sitename and thought you would be interested in this page:

$urlname
$urladdress$addl", "From: $fromemail\r\nReply-To: $fromemail");

if ($copy == "Yes")
{
mail("$fromemail", "Check This Out (copy)", "The following message was sent to $to_name$toemail:

-------------------------------------------

$strfromname was viewing $sitename and thought you would be interested in this item:

$urlname
$urladdress$addl", "From: $fromemail\r\nReply-To: $fromemail");
}

$delquery = "DELETE FROM " .$DB_Prefix ."_check WHERE CheckVar='$_POST[checkvar]' AND DateTime>'$starttime'";
$delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");

echo "<p align=\"center\">You just sent the following page information to $to_name$toemail:</p>";
echo "<p align=\"center\"><span class=\"boldtext\">";
echo "$urlname<br>$urladdress</p>";
echo "<p align=\"center\">";
echo "<a href=\"javascript:window.close();\">Close Window</a></p>";
}
}
}
}
else
{
$ipaddress = $_SERVER[REMOTE_ADDR];
// Create random characters
mt_srand ((double) microtime() * 10000000);
$rc = chr(mt_rand(97,122));
$rc .= chr(mt_rand(97,122));
$rc .= mt_rand(1,9);
$rc .= mt_rand(1,9);
$rc .= chr(mt_rand(97,122));
$rc .= mt_rand(1,9);
$rc .= mt_rand(1,9);
$rc .= chr(mt_rand(97,122));
$checkvar = md5($rc);
$insquery = "INSERT INTO " .$DB_Prefix ."_check (IPAddress, CheckVar) VALUES ('$ipaddress', '$checkvar')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
?>
<form method="POST" name="emailform" action="email.php" onSubmit="return validateComplete(document.emailform);">
<div align="center">
<center>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
<td valign="top" align="right">Your Name:</td>
<td valign="top" align="left"><input type="text" name="fromname" size="30"></td>
</tr>
<tr>
<td valign="top" align="right">Your Email*:</td>
<td valign="top" align="left"><input type="text" name="fromemail" size="30"></td>
</tr>
<tr>
<td valign="top" align="right">Send To Name:</td>
<td valign="top" align="left"><input type="text" name="toname" size="30"></td>
</tr>
<tr>
<td valign="top" align="right">Send To Email*:</td>
<td valign="top" align="left"><input type="text" name="toemail" size="30"></td>
</tr>
<tr>
<td valign="top" align="center" colspan="2">
Click here to receive a copy of this email: 
<input type="checkbox" name="copy" value="Yes"></td>
</tr>
<tr>
<td valign="top" align="right">Your Message:</td>
<td valign="top" align="left">
<textarea rows="2" name="message" cols="25"></textarea></td>
</tr>
<tr>
<td valign="middle" align="right" colspan="2">* Required fields</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="2">
<?php
if ($item)
echo "<input type=\"hidden\" name=\"item\" value=\"$item\">";
if ($page)
echo "<input type=\"hidden\" name=\"page\" value=\"$page\">";
if ($dir)
echo "<input type=\"hidden\" name=\"dir\" value=\"$dir\">";
echo "<input type=\"hidden\" name=\"checkvar\" value=\"$checkvar\">";
?>
<input type="submit" value="Send Email" name="Submit" class="formbutton">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}
?>
</body>

</html>
