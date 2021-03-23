<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<script language="php">include("includes/htmlarea.php");</script>
</head>

<body<script language="php">$java = "answer"; include("includes/htmlareabody.php");</script>>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

if ($mode == "Save")
{
$question = addslash_mq($question);
$answer = addslash_mq($answer);
// Edit Record
if ($faqid)
{
$updquery = "UPDATE " .$DB_Prefix ."_faq SET Question='$question', Answer='$answer' WHERE ID='$faqid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
// Add New Record
else
{
$cntquery = "SELECT ListOrder FROM " .$DB_Prefix ."_faq ORDER BY ListOrder DESC LIMIT 1";
$cntresult = mysql_query($cntquery, $dblink) or die ("Unable to select. Try again later.");
$cntrow = mysql_fetch_row($cntresult);
if (mysql_num_rows($cntresult) == 0)
$listorder = 1;
else
$listorder = $cntrow[0]+1;
$insquery = "INSERT INTO " .$DB_Prefix ."_faq (Question, Answer, ListOrder) VALUES ('$question', '$answer', '$listorder')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

// Delete Record
if ($mode == "Yes")
{
$cntquery = "SELECT ListOrder FROM " .$DB_Prefix ."_faq ORDER BY ListOrder DESC LIMIT 1";
$cntresult = mysql_query($cntquery, $dblink) or die ("Unable to select. Try again later.");
$cntrow = mysql_fetch_row($cntresult);
$cntnum = mysql_num_rows($cntresult);
if ($cntnum > 0)
{
if ($cntrow[0] != $ord)
{
$upd1query = "UPDATE " .$DB_Prefix ."_faq SET ListOrder='$ord' WHERE ListOrder='$cntrow[0]'";
$upd1result = mysql_query($upd1query, $dblink) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_faq SET ListOrder='$cntrow[0]' WHERE ID='$faqid'";
$upd2result = mysql_query($upd2query, $dblink) or die("Unable to update. Please try again later.");
}
}
$delquery = "DELETE FROM " .$DB_Prefix ."_faq WHERE ID='$faqid'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
}

// Update List Order
if ($faqid AND $curord AND $neword)
{
$upd1query = "UPDATE " .$DB_Prefix ."_faq SET ListOrder='$curord' WHERE ListOrder='$neword'";
$upd1result = mysql_query($upd1query, $dblink) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_faq SET ListOrder='$neword' WHERE ID='$faqid'";
$upd2result = mysql_query($upd2query, $dblink) or die("Unable to update. Please try again later.");
}

// Show Add/Edit Form
if ($mode == "Add" OR $mode == "Edit")
{
if ($faqid)
{
$faqquery = "SELECT * FROM " .$DB_Prefix ."_faq WHERE ID = '$faqid'";
$faqresult = mysql_query($faqquery, $dblink) or die ("Unable to select. Try again later.");
$faqrow = mysql_fetch_array($faqresult);
$question = str_replace('"', "&quot;", stripslashes($faqrow[Question]));
$answer = stripslashes($faqrow[Answer]);
}
?>

<form method="POST" action="faqs.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="right">Question:</td>
<td valign="top" align="left">
<?php echo "<input type=\"text\" name=\"question\" value=\"$question\" size=\"58\">"; ?>
</td>
</tr>
<tr>
<td valign="top" align="right">Answer:</td>
<td valign="top" align="left">
<textarea rows="9" name="answer" id="answer" cols="50"><?php echo "$answer"; ?></textarea>
</td>
</tr>
<td valign="top" align="center" colspan="2">
<?php echo "<input type=\"hidden\" value=\"$faqid\" name=\"faqid\">"; ?>
<input type="submit" value="Save" name="mode" class="button">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"faqs.php\">Back to FAQs</a></p>";
}

// Show Delete Box
else if ($mode == "Delete")
{
?>

<form method="POST" action="faqs.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="center">Do you want to delete this question?</td>
</tr>
<tr>
<td valign="top" align="center">
<?php
echo "<input type=\"hidden\" value=\"$faqid\" name=\"faqid\">";
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
echo "<p align=\"center\"><a href=\"faqs.php\">Back to FAQs</a></p>";
}

else
{
$setpg_lower = "faqs";
$setpg_upper = "FAQs";

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}

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

$faqquery = "SELECT ID, Question, ListOrder FROM " .$DB_Prefix ."_faq ORDER BY ListOrder";
$faqresult = mysql_query($faqquery, $dblink) or die ("Unable to select. Try again later.");
$faqnum = mysql_num_rows($faqresult);
?>

<form method="POST" action="faqs.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="3">Frequently Asked Questions</td>
</tr>
<?php
if ($faqnum == 0)
{
?>
<tr>
<td valign="middle" align="center" colspan="3">You do not currently have any FAQs. Add one by clicking the button below.</td>
</tr>
<?php
}
else
{
?>
<tr>
<td valign="middle" align="center" colspan="3">Select a question from the list below, or add a new question.</td>
</tr>
<tr>
<td class="accent">Question</td>
<td class="accent">Order</td>
<td>&nbsp;</td>
</tr>
<?php
for ($i = 1; $faqrow = mysql_fetch_array($faqresult); ++$i)
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\" width=\"100%\">";
echo stripslashes($faqrow[Question]);
echo "</td>";
echo "<td valign=\"top\" align=\"left\" nowrap>";
$dnord = $faqrow[ListOrder]+1;
$upord = $faqrow[ListOrder]-1;
if ($i == $faqnum)
echo "<img alt=\"-\" border=\"0\" src=\"images/spacer.gif\" height=\"1\" width=\"9\"> ";
if ($i < $faqnum)
echo " <a href=\"faqs.php?faqid=$faqrow[ID]&neword=$dnord&curord=$faqrow[ListOrder]\"><img alt=\"down\" border=\"0\" alt=\"down\" src=\"images/downarrow.gif\" width=\"9\" height=\"10\"></a>";
if ($i > 1)
echo " <a href=\"faqs.php?faqid=$faqrow[ID]&neword=$upord&curord=$faqrow[ListOrder]\"><img alt=\"up\" border=\"0\" alt=\"up\" src=\"images/uparrow.gif\" width=\"9\" height=\"10\"></a>";
echo "</td>";
echo "<td valign=\"top\" align=\"left\"><a href=\"faqs.php?faqid=$faqrow[ID]&mode=Edit\">Edit</a></td>";
echo "<td valign=\"top\" align=\"left\"><a href=\"faqs.php?faqid=$faqrow[ID]&mode=Delete&ord=$faqrow[ListOrder]\">Delete</a></td>";
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
The FAQ page is currently inactive. If you would like to<br>activate it, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate FAQs Page" name="submit">
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
echo "<a href=\"faqs.php?submit=Deactivate+FAQs+Page\">Deactivate</a></p>";
}

}
include("includes/links2.php");
include("includes/footer.htm");
?>

</html>