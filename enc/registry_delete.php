<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// If user is logged in
if ($regnum == 1)
{
?>
<p class="salecolor" align="center">WARNING!</p>
<p>Are you sure you want to delete your gift registry? Your contact information, 
gifts wanted and gifts received will all be deleted permanently. You cannot undo 
this action, and we cannot retrieve your information again after deleting.</p>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
<p align="center">
<input type="hidden" name="registry_id" value="<?php echo "$regrow[0]"; ?>">
<input type="submit" value="No" name="deleteregistry" class="formbutton"> 
<input type="submit" value="Yes" name="deleteregistry" class="formbutton">
</p>
</form>
<?php
echo "<p align=\"center\">";
echo "<a href=\"registry.$pageext?mode=registry\">View Registry</a> | ";
echo "<a href=\"registry.$pageext?logout=yes\">Log Out</a></p>";
}
else
echo "<p align=\"center\">Cannot delete registry</p>";
?>
