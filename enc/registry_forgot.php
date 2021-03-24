<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($fmode == "submitted")
{
$emquery = "SELECT RegUser, RegPass FROM " .$DB_Prefix ."_registry WHERE Email='$regemail'";
$emresult = mysqli_query($dblink, $emquery) or die ("Unable to select. Try again later.");
$emnum = mysqli_num_rows($emresult);
if (isset($regemail) AND $emnum == 1)
{
$emrow = mysqli_fetch_row($emresult);
$webpage = $URL ."/" .$setpg;
$regmsg = "You can log into our gift registry at $webpage, using the following information:\r\n\r\n";
$regmsg .= "User Name: $emrow[0]\r\n";
$regmsg .= "Password: $emrow[1]\r\n\r\n";
$regmsg .= "If you have any questions, please contact us at $Admin_Email.\r\n\r\n";
$regmsg .= "$Site_Name\r\n$URL";
@mail("$regemail", "Forgotten Password", "$regmsg", "From: $Admin_Email\r\nReply-To: $Admin_Email");
echo "<p>Thank you for your inquiry. We have sent an email to you with instructions on logging ";
echo "in to your $Registry registry. Please check your email shortly for further information.</p>";
}
else
$fmode = "error";
}

if (!$fmode OR $fmode == "error")
{
if ($fmode == "error")
echo "<p>Sorry, but we could not find your information in our system. Please try again.</p>";
else
echo "<p>If you are a registrant, enter your email address below and your information will be sent to you shortly:</p>";
?>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="right">Email Address:</td>
<td valign="top" align="left"><input type="text" name="regemail" size="30"></td>
</tr>
<tr>
<td colspan="2" valign="top" align="center">
<input type="hidden" name="show" value="forgot">
<?php echo "$addhidden"; ?>
<input type="hidden" name="fmode" value="submitted">
<input type="submit" value="Get Info" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>
<?php
}
?>
