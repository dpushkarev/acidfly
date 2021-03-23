<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($action == "signup")
{
if (empty($per[0]) OR empty($per[1]) OR empty($per[2]) OR $per[2] == "http://" OR empty($per[3]) OR empty($per[4]) OR empty($per[5]) OR empty($per[6]) OR empty($per[7]) OR empty($per[9]) OR empty($per[11]))
{
echo "<p class=\"salecolor\">Some information was missing. Please fill in all fields in bold.</p>";
$action = "error";
}
else
{
$per[0] = str_replace('"', "", stripslashes($per[0]));
$per[1] = str_replace('"', "", stripslashes($per[1]));
$per[2] = str_replace('"', "", stripslashes($per[2]));
$per[3] = str_replace('"', "", stripslashes($per[3]));
$per[4] = str_replace('"', "", stripslashes($per[4]));
$per[5] = str_replace('"', "", stripslashes($per[5]));
$per[6] = str_replace('"', "", stripslashes($per[6]));
$per[7] = str_replace('"', "", stripslashes($per[7]));
$per[8] = str_replace('"', "", stripslashes($per[8]));
$per[9] = str_replace('"', "", stripslashes($per[9]));
$per[10] = str_replace('"', "", stripslashes($per[10]));
$per[11] = str_replace('"', "", stripslashes($per[11]));
?>

<p align="center">Please confirm your information:</p>
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="left" class="accent">Name:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[0]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Site Name:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[1]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Website URL:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[2]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Address:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[3]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">City:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[4]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">State/Province:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[5]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Zip/Postal Code:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[6]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Country:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[7]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Tax ID Number:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[8]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Phone:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[9]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Fax:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[10]"); ?></td>
</tr>
<tr>
<td valign="top" align="left" class="accent">Email:</td>
<td valign="top" align="left"><?php echo stripslashes("$per[11]"); ?></td>
</tr>
<tr>
<td valign="top" align="center" colspan="2">
<form action="<?php echo "http://$Mals_Server.aitsafe.com/mtracker/new.htm"; ?>" method="post">
<input type="hidden" value="<?php echo "$Mals_Cart_ID"; ?>" name="userid">
<input type="hidden" value=" JOIN NOW " name="action">
<?php
for ($i = 0; $i <= 11; ++$i)
{
$perval = str_replace('"', "", stripslashes($per[$i]));
echo "<input type=\"hidden\" value=\"$perval\" name=\"per[]\">";
}
?>
<input type="submit" value="Join Now" name="submit" class="formbutton">
</form>
</td>
</tr>
</table>

<?php
}
}

if ($action != "signup")
{
?>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td valign="top" align="center">

<form action="<?php echo "affiliates.$pageext"; ?>" method="post">
<table border="0" cellpadding="3" cellspacing="0">
<tr>
<td valign="top" align="left" class="boldtext">Name</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[0]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Site Name</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[1]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Website URL</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[2]"; ?>" size="30" value="http://"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Address</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[3]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">City</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[4]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">State/Province</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[5]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Zip/Postal Code</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[6]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Country</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[7]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left">Tax ID Number</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[8]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Phone</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[9]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left">Fax</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[10]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="left" class="boldtext">Email</td>
<td valign="top" align="left"><input type="text" name="per[]" value="<?php echo "$per[11]"; ?>" size="30"></td>
</tr>
<tr>
<td valign="top" align="center" colspan="2">
<input type="hidden" name="action" value="signup">
<input type="submit" value="Join Now" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>

</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td valign="top" align="center">
<?php
if ($Product_Line)
$affcolor = $Product_Line;
else
$affcolor = $Product_Color;
?>
<form action="<?php echo "http://$Mals_Server.aitsafe.com/mtracker/index.htm"; ?>" method="post">
<table border="0" cellpadding="3" cellspacing="0" style="<?php echo "background-color: $Highlight_Color; border: 1 dotted $affcolor"; ?>">
<tr>
<td width="100%" colspan="2" align="center">Already in our system?<br>Log in below:</td>
</tr>
<tr>
<td>User ID:</td>
<td><input type="text" name="username" size="10"></td>
</tr>
<tr>
<td>Password:</td>
<td><input type="text" name="pass" size="10"></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" value="Log In" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>

</td>
</tr>
</table>

<?php
}
?>
