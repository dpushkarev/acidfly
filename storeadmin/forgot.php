<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
require_once("../stconfig.php");
include("includes/header.htm");

// Process link
if ($em AND $ph)
{
?>
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="left" colspan="2">
<?php
// Check for validity
$varquery = "SELECT AdminEmail, URL FROM " .$DB_Prefix ."_vars WHERE ID=1 AND AdminEmail='$em' AND PassPhrase='$ph'";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
// If password info is correct
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_row($varresult);
$admemail = $varrow[0];
$webpage="http://" .$varrow[1] ."/$Adm_Dir/";
// Create random password
mt_srand ((double) microtime() * 10000000);
$adminpass = chr(mt_rand(97,122));
$adminpass .= chr(mt_rand(97,122));
$adminpass .= mt_rand(1,9);
$adminpass .= mt_rand(1,9);
$adminpass .= chr(mt_rand(97,122));
$adminpass .= mt_rand(1,9);
$adminpass .= mt_rand(1,9);
$adminpass .= chr(mt_rand(97,122));
// Create password hash
$passcode = md5($adminpass);
// Update password information
$updquery = "UPDATE " .$DB_Prefix ."_vars SET PassPhrase='', AdminPass='$passcode' WHERE ID=1";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
$mailmsg = "A request for your web store administration password was made today. ";
$mailmsg .= "You can log into your catalog administration area at $webpage, using the ";
$mailmsg .= "following information:\r\n\r\nUser Name: $Admin_User\r\n";
$mailmsg .= "Password: $adminpass\r\n\r\n";
$mailmsg .= "After logging in, you can select the Password link to change your password ";
$mailmsg .= "to something easier to remember. If you have any problems or need additional ";
$mailmsg .= "assistance, please contact support.";
@mail("$admemail", "Forgotten Password", "$mailmsg", "From: $admemail\r\nReply-To: $admemail");
echo "Your new password has been emailed to $admemail.<br>";
echo "You can use this password to enter your ";
echo "<a href=\"$webpage\">administration area</a>.";
}
else
{
echo "Sorry, but your link could not be validated. ";
echo "Please <a href=\"forgot.php\">try again</a> or contact us for assistance.";
}
?>
</td>
</tr>
</table>
</center>
</div>
<?php
}

else if ($_POST[mode] == "reset" AND !empty($_POST[emailaddress]))
{
?>
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="left" colspan="2">
<?php
$varquery = "SELECT AdminEmail, URL FROM " .$DB_Prefix ."_vars WHERE ID=1 AND AdminEmail='$emailaddress'";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_row($varresult);
// Create random pass phrase
$passcode = chr(mt_rand(97,122));
$passcode .= chr(mt_rand(97,122));
$passcode .= mt_rand(1,9);
$passcode .= mt_rand(1,9);
$passcode .= chr(mt_rand(97,122));
$passcode .= mt_rand(1,9);
$passcode .= mt_rand(1,9);
$passcode .= chr(mt_rand(97,122));
$passcode .= chr(mt_rand(97,122));
$passcode .= mt_rand(1,9);
$passcode .= mt_rand(1,9);
$passcode .= chr(mt_rand(97,122));
$admemail = $varrow[0];
$weblink="http://" .$varrow[1] ."/$Adm_Dir/forgot.php?em=$admemail&ph=$passcode";
// Update vars table
$updquery = "UPDATE " .$DB_Prefix ."_vars SET PassPhrase='$passcode' WHERE ID=1";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
$weblink="http://" .$varrow[1] ."/$Adm_Dir/forgot.php?em=$admemail&ph=$passcode";
$mailmsg = "A request for your web store administration password was made today. ";
$mailmsg .= "Before we can process the request, you must activate the request by clicking ";
$mailmsg .= "the link below. This will verify that the request was actually made by you:\r\n\r\n";
$mailmsg .= "$weblink\r\n\r\n";
$mailmsg .= "Clicking this link will create a new password which you can then use to log in to your ";
$mailmsg .= "administration area. If you have any problems, please contact support.";
@mail("$admemail", "Forgotten Password", "$mailmsg", "From: $admemail\r\nReply-To: $admemail");
echo "Thank you. We have sent a message to your email address with additional information ";
echo "explaining how to activate your password request and log in to your administration area. ";
echo "Please check your email for instructions. If you have any problems or do not receive your ";
echo "information shortly, please contact us for assistance.";
}
else
{
echo "Sorry, but your email address did not match that which is in the system. ";
echo "Please <a href=\"forgot.php\">try again</a> or contact us for assistance.";
}
?>
</td>
</tr>
</table>
</center>
</div>
<?php
}

else
{
?>
<form method="POST" action="forgot.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="left" colspan="2">
If you have forgotten your administrative password, please enter your email address, 
then click the link below for your password information to be sent to you. If you 
no longer have the email address in your files or have any problems retrieving your 
password, please contact us for assistance.</td>
</tr>
<tr>
<td align="right" class="fieldname" nowrap>Email Address:</td>
<td align="left"><input type="text" name="emailaddress" size="35"></td>
</tr>
<tr>
<td align="center" colspan="2">
<input type="hidden" value="reset" name="mode">
<input type="submit" class="button" value="Reset Password" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

include("includes/footer.htm");
?>
</body>

</html>