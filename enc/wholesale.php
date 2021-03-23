<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($Submit == "Send")
{
$wsquery = "SELECT Email, Password FROM " .$DB_Prefix ."_wholesale WHERE Email = '$wsemail'";
$wsresult = mysql_query($wsquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($wsresult) == 1)
{
$wsrow = mysql_fetch_row($wsresult);
$email = $wsrow[0];
$wspass = $wsrow[1];
$webpage = $URL ."/wholesale.$pageext";
@mail("$email", "Forgotten Password", "You can log into our wholesale area at $webpage, using the following password: $wspass. If you have any questions, please contact us at $Admin_Email

$Site_Name
$URL", "From: $Admin_Email\r\nReply-To: $Admin_Email");
echo "<p align=\"center\">";
echo "Your password has been emailed to $email.<br>";
echo "You can use this password to enter our ";
echo "<a href=\"wholesale.$pageext?wslg=y\">wholesale area</a>.</p>";
}
else
{
echo "<p align=\"center\">";
if (!$wsemail)
echo "We did not receive an email address. ";
else
echo "Sorry, but your email address could not be found. ";
echo "Please <a href=\"wholesale.$pageext?get=pass&wslg=y\">try again</a>.</p>";
}
}

else if ($_POST['Submit'] == "Apply")
{
if (!$_POST['compname'] OR !$_POST['emailaddy'])
{
echo "<p>Sorry, but it seems as though your company name or email address ";
echo "were not entered. Please go back and try again.</p>";
}
else if (!$_POST['ws_pass'] OR $_POST['ws_pass'] != $_POST['ws_repass'])
{
echo "<p>Sorry, but your password did not enter the value you re-entered. ";
echo "Please go back and try again.</p>";
}
else
{
$wsquery = "SELECT ID FROM " .$DB_Prefix ."_wholesale WHERE Email='" .$_POST[emailaddy] ."'";
$wsresult = mysql_query($wsquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($wsresult) > 0)
{
echo "<p>Sorry, but your email address is already in our system. Go to our ";
echo "<a href=\"wholesale.$pageext?wslg=y\">login area</a> to sign in. ";
echo "If you have forgotten your password, click the 'Forgot Password' link ";
echo "and it will be sent to you.</p>";
}
else
{
$varquery = "SELECT URL, AdminEmail FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
$varrow = mysql_fetch_row($varresult);
$URL = $varrow[0];
$admin_email = $varrow[1];
$addcompany = addslash_mq(stripbadstuff($_POST['compname']));
$stripcompany = stripslashes(stripbadstuff($_POST['compname']));
$addemail = stripbadstuff($_POST['emailaddy']);
$stripemail = stripbadstuff($_POST['emailaddy']);
$addaddress = addslash_mq(stripbadstuff($_POST['addressinfo']));
$stripaddress = stripslashes(stripbadstuff($_POST['addressinfo']));
$addwspass = stripbadstuff($_POST['ws_pass']);
$stripwspass = stripbadstuff($_POST['ws_pass']);
$addwebsiteurl = stripbadstuff($_POST['websiteurl']);
$stripwebsiteurl = stripbadstuff($_POST['websiteurl']);
$insquery = "INSERT INTO " .$DB_Prefix ."_wholesale (Company, WebSite, Email, Contact, Password, Discount, Active) ";
$insquery .= "VALUES ('$addcompany', '$addwebsiteurl', '$addemail', '$addaddress', '$addwspass', '0', 'No')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
mail($admin_email, "Wholesale Application", "The following company has requested to be a wholesale vendor:

Company: $stripcompany
Address: $stripaddress
Web Site: $stripwebsiteurl
Email: $stripemail

This company is not yet an active wholesale vendor. You must first activate the company and notify them 
with more information. To view and activate this compay, please log in to your administration area at:
http://$URL/$Adm_Dir/
and select the Wholesale link.", "From: $admin_email\r\nReply-To: $admin_email");

echo "<p>Thank you for your application. If you are approved, we will ";
echo "contact you shortly with additional information. If you have ";
echo "any questions, please contact us for assistance.</p>";
}
}
}

else if ($get == "pass")
{
?>
<form action="<?php echo "wholesale.$pageext"; ?>" method="post">
<p align="center">
Please enter your email address below, and your password will be sent to you.</p>
<p align="center">Email Address: 
<input type="text" name="wsemail" size="40"> 
<input type="submit" value="Send" name="Submit" class="formbutton"></p>
</form>
<?php
}

else if ($signup == "yes")
{
?>

<p>Please fill in the fields below and submit to sign up as a wholesale vendor.</p>

<form action="<?php echo "wholesale.$pageext"; ?>" method="post">
<table border="0" align="center" cellspacing="0" cellpadding="3">
<tr>
<td vAlign="top" align="right">Company:</td>
<td vAlign="top" align="left" colSpan="3"><input type="text" size="40" name="compname"> 
</td>
</tr>
<tr>
<td vAlign="top" align="right">Web Site:</td>
<td vAlign="top" align="left" colSpan="3"><input type="text" size="40" name="websiteurl"> 
</td>
</tr>
<tr>
<td vAlign="top" align="right">Email Address:</td>
<td vAlign="top" align="left" colSpan="3"><input type="text" size="40" name="emailaddy"> 
</td>
</tr>
<tr>
<td vAlign="top" align="right">Address:</td>
<td vAlign="top" align="left" colSpan="3">
<textarea name="addressinfo" rows="3" cols="33"></textarea>
</td>
</tr>
<tr>
<td vAlign="top" align="right">Password:</td>
<td vAlign="top" align="left"><input size="10" name="ws_pass" type="password" maxLength="10">
</td>
<td vAlign="top" align="right">Re-enter:</td>
<td vAlign="top" align="left"><input size="10" name="ws_repass" type="password" maxLength="10">
</td></tr>
<tr>
<td vAlign=top align=center colSpan="4">
<input type=submit value="Apply" name="Submit" class="formbutton">
</td>
</tr>
</table>
</form>

<?php
}
else
{
$wholesale_set = "n";
if ($wspass AND $wsemail)
{
$wsquery = "SELECT Company, Discount FROM " .$DB_Prefix ."_wholesale WHERE Email='$wsemail' AND Password='$wspass' AND Active='Yes'";
$wsresult = mysql_query($wsquery, $dblink) or die ("Unable to select. Try again later.");
$wsnum = mysql_num_rows($wsresult);
if ($wsnum == 1)
{
echo "<p align=\"center\">";
echo "You are currently logged in as a wholesaler. Please ";
echo "<a href=\"wholesale.$pageext?wslg=y\">click here</a> ";
echo "to log out of wholesale mode.</p>";
$wholesale_set = "y";
}
}

if ($wholesale_set == "n")
{
?>
<form action="<?php echo "$Catalog_Page"; ?>" method="post">
<p>Our wholesale area is only available to registered wholesale vendors. 
Would you like to wholesale our products? 
<?php echo "<a href=\"wholesale.$pageext?signup=yes\">Apply</a> "; ?>
to become a vendor and we will send you information shortly if you are approved.</p>

<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td align="right">Your Email Address:</td>
<td><input type="text" name="wholeemail" size="20"></td>
</tr>
<tr>
<td align="right">Wholesale Password:</td>
<td><input type="password" name="wholepass" size="20"></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="mode" value="ws">
<input type="submit" value="Log In" name="Submit" class="formbutton">
</td>
</tr>
</table>
<?php
echo "<p align=\"center\" class=\"smfont\">";
echo "<a href=\"wholesale.$pageext?get=pass&wslg=y\">Forgot your password?</a></p>";
?>
</form>

<?php
}
}
?>
