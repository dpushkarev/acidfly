<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<?php include("includes/htmlarea.php"); ?>
</head>

<body<?php $java = "article"; include("includes/htmlareabody.php"); ?>>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

if ($mode == "Save")
{
$title = addslash_mq($title);
$article = addslash_mq($article);
// Edit Record
if ($artid)
{
$updquery = "UPDATE " .$DB_Prefix ."_articles SET Title='$title', Article='$article' WHERE ID='$artid'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
// Add New Record
else
{
$cntquery = "SELECT ListOrder FROM " .$DB_Prefix ."_articles ORDER BY ListOrder DESC LIMIT 1";
$cntresult = mysqli_query($dblink, $cntquery) or die ("Unable to select. Try again later.");
$cntrow = mysqli_fetch_row($cntresult);
if (mysqli_num_rows($cntresult) == 0)
$listorder = 1;
else
$listorder = $cntrow[0]+1;
$insquery = "INSERT INTO " .$DB_Prefix ."_articles (Title, Article, ListOrder) VALUES ('$title', '$article', '$listorder')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
}
}

// Delete Record
if ($mode == "Yes")
{
$cntquery = "SELECT ListOrder FROM " .$DB_Prefix ."_articles ORDER BY ListOrder DESC LIMIT 1";
$cntresult = mysqli_query($dblink, $cntquery) or die ("Unable to select. Try again later.");
$cntrow = mysqli_fetch_row($cntresult);
$cntnum = mysqli_num_rows($cntresult);
if ($cntnum > 0)
{
if ($cntrow[0] != $ord)
{
$upd1query = "UPDATE " .$DB_Prefix ."_articles SET ListOrder='$ord' WHERE ListOrder='$cntrow[0]'";
$upd1result = mysqli_query($dblink, $upd1query) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_articles SET ListOrder='$cntrow[0]' WHERE ID='$artid'";
$upd2result = mysqli_query($dblink, $upd2query) or die("Unable to update. Please try again later.");
}
}
$delquery = "DELETE FROM " .$DB_Prefix ."_articles WHERE ID='$artid'";
$delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
}

// Update List Order
if ($artid AND $curord AND $neword)
{
$upd1query = "UPDATE " .$DB_Prefix ."_articles SET ListOrder='$curord' WHERE ListOrder='$neword'";
$upd1result = mysqli_query($dblink, $upd1query) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_articles SET ListOrder='$neword' WHERE ID='$artid'";
$upd2result = mysqli_query($dblink, $upd2query) or die("Unable to update. Please try again later.");
}

// Show Add/Edit Form
if ($mode == "Add" OR $mode == "Edit")
{
if ($artid)
{
$artquery = "SELECT * FROM " .$DB_Prefix ."_articles WHERE ID = '$artid'";
$artresult = mysqli_query($dblink, $artquery) or die ("Unable to select. Try again later.");
$artrow = mysqli_fetch_array($artresult);
$title = str_replace('"', "&quot;", stripslashes($artrow[Title]));
$article = stripslashes($artrow[Article]);
}
?>

<form method="POST" action="articles.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="right">Title:</td>
<td valign="top" align="left">
<?php echo "<input type=\"text\" name=\"title\" value=\"$title\" size=\"58\">"; ?>
</td>
</tr>
<tr>
<td valign="top" align="right">Article:</td>
<td valign="top" align="left">
<textarea rows="9" name="article" id="article" cols="50"><?php echo "$article"; ?></textarea>
</td>
</tr>
<td valign="top" align="center" colspan="2">
<?php echo "<input type=\"hidden\" value=\"$artid\" name=\"artid\">"; ?>
<input type="submit" value="Save" name="mode" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"articles.php\">Back to Articles</a></p>";
}

// Show Delete Box
else if ($mode == "Delete")
{
?>

<form method="POST" action="articles.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="center">Do you want to delete this article?</td>
</tr>
<tr>
<td valign="top" align="center">
<?php
echo "<input type=\"hidden\" value=\"$artid\" name=\"artid\">";
echo "<input type=\"hidden\" value=\"$ord\" name=\"ord\">";
?>
<input type="submit" value="Yes" name="mode" class="button">
<input type="submit" value="No" name="mode" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"articles.php\">Back to Articles</a></p>";
}

else
{
$setpg_lower = "articles";
$setpg_upper = ucfirst($setpg_lower);

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}

$getquery = "SELECT ID, Active FROM " .$DB_Prefix ."_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($getresult) == 1)
{
$getrow = mysqli_fetch_row($getresult);
$pgid = $getrow[0];
$setactive = $getrow[1];
}
if (!$setactive)
$setactive = "No";

if ($setactive == "Yes")
{

$artquery = "SELECT ID, Title, ListOrder FROM " .$DB_Prefix ."_articles ORDER BY ListOrder";
$artresult = mysqli_query($dblink, $artquery) or die ("Unable to select. Try again later.");
$artnum = mysqli_num_rows($artresult);
?>

<form method="POST" action="articles.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="3">Articles</td>
</tr>
<?php
if ($artnum == 0)
{
?>
<tr>
<td valign="middle" align="center" colspan="3">You do not currently have any articles. Add one by clicking the button below.</td>
</tr>
<?php
}
else
{
?>
<tr>
<td valign="middle" align="center" colspan="3">Select an article from the list below, or add a new article.</td>
</tr>
<tr>
<td class="accent">Title</td>
<td class="accent">Order</td>
<td>&nbsp;</td>
</tr>
<?php
for ($i = 1; $artrow = mysqli_fetch_array($artresult); ++$i)
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\" width=\"100%\">";
echo stripslashes($artrow[Title]);
echo "</td>";
echo "<td valign=\"top\" align=\"left\" nowrap>";
$dnord = $artrow[ListOrder]+1;
$upord = $artrow[ListOrder]-1;
if ($i == $artnum)
echo "<img border=\"0\" src=\"images/spacer.gif\" alt=\"-\" height=\"1\" width=\"9\"> ";
if ($i < $artnum)
echo " <a href=\"articles.php?artid=$artrow[ID]&neword=$dnord&curord=$artrow[ListOrder]\"><img alt=\"-\" border=\"0\" src=\"images/downarrow.gif\" width=\"9\" height=\"10\"></a>";
if ($i > 1)
echo " <a href=\"articles.php?artid=$artrow[ID]&neword=$upord&curord=$artrow[ListOrder]\"><img alt=\"-\" border=\"0\" src=\"images/uparrow.gif\" width=\"9\" height=\"10\"></a>";
echo "</td>";
echo "<td valign=\"top\" align=\"left\"><a href=\"articles.php?artid=$artrow[ID]&mode=Edit\">Edit</a></td>";
echo "<td valign=\"top\" align=\"left\"><a href=\"articles.php?artid=$artrow[ID]&mode=Delete&ord=$artrow[ListOrder]\">Delete</a></td>";
echo "</tr>";
}
}
?>
<tr>
<td valign="middle" align="center" class="fieldname" colspan="3">
<input class="button" type="submit" value="Add" name="mode"></td>
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
The articles page is currently inactive. If you would like to<br>activate it, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Articles Page" name="submit">
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
echo "<a href=\"articles.php?submit=Deactivate+Articles+Page\">Deactivate</a></p>";
}

}
include("includes/links2.php");
include("includes/footer.htm");
?>

</html>