<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<script language="php">include("includes/htmlarea.php");</script>
</head>

<body onload="editor_generate('description');">
<script language="php">$java = "description"; include("includes/htmlareabody.php");</script>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

if ($Submit == "Add Category")
{
$category = addslash_mq($category);
$description = addslash_mq($description);
$keywords = addslash_mq($keywords);
$metatitle = addslash_mq($metatitle);
if ($category)
{
// Get last category number in this subcat
$newquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$parent'";
$newresult = mysql_query($newquery, $dblink) or die ("Unable to select. Try again later.");
$newnum = mysql_num_rows($newresult) + 1;
$inscatquery = "INSERT INTO " .$DB_Prefix ."_categories (Category, Parent, Image, Description, ";
$inscatquery .= "Keywords, Active, HeaderImage, ListImage, CatColumns, CatRows, ";
$inscatquery .= "CatOrder, MetaTitle) VALUES ('$category', '$parent', '$image', '$description', ";
$inscatquery .= "'$keywords', '$active', '$headerimage', '$listimage', '$catcolumns', ";
$inscatquery .= "'$catrows', '$newnum', '$metatitle')";
$inscatresult = mysql_query($inscatquery, $dblink) or die("Unable to add your category. Please try again later.");
$inscatid = mysql_insert_id();
}
}

if ($Submit == "Edit Category")
{
// Check to see if items exist under the parent
$parquery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE Category1='$parent' OR Category2='$parent' ";
$parquery .= "OR Category3='$parent' OR Category4='$parent' OR Category5='$parent'";
$parresult = mysql_query($parquery, $dblink) or die ("Unable to select items. Try again later.");
$parnum = mysql_num_rows($parresult);
$category = addslash_mq($category);
$description = addslash_mq($description);
$keywords = addslash_mq($keywords);
$metatitle = addslash_mq($metatitle);
if ($category)
{
$catquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE ID='$catid'";
$catresult = mysql_query($catquery, $dblink) or die ("Unable to edit your category. Please try again later.");
$catrow = mysql_fetch_row($catresult);
$oldcatid = $catrow[0];
$oldcat = $catrow[1];
$subcatquery = "UPDATE " .$DB_Prefix ."_categories SET Parent='$catid' WHERE Parent='$oldcatid'";
$subcatresult = mysql_query($subcatquery, $dblink) or die("Unable to edit your category. Please try again later.");
$updcatquery = "UPDATE " .$DB_Prefix ."_categories SET Category='$category', Image='$image', ";
$updcatquery .= "Description='$description', Parent='$parent', Keywords='$keywords', ";
$updcatquery .= "Active='$active', HeaderImage='$headerimage', ListImage='$listimage', ";
$updcatquery .= "CatColumns='$catcolumns', CatRows='$catrows', MetaTitle='$metatitle' WHERE ID='$catid'";
$updcatresult = mysql_query($updcatquery, $dblink) or die("Unable to edit your category. Please try again later.");
// Update category order if needed
if ($parent != $oldparent)
{
// Change all existing category orders in that subcategory
$oldquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$oldparent' AND CatOrder > '$currentorder' ORDER BY CatOrder";
$oldresult = mysql_query($oldquery, $dblink) or die ("Unable to select. Try again later.");
$oldnum = mysql_num_rows($oldresult);
$neworder = $currentorder;
for ($i=1; $oldrow=mysql_fetch_row($oldresult); ++$i)
{
$updiquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$neworder' WHERE ID='$oldrow[0]'";
$updiresult = mysql_query($updiquery, $dblink) or die("Unable to update old. Please try again later.");
++ $neworder;
}
// Change this category order
$newquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$parent'";
$newresult = mysql_query($newquery, $dblink) or die ("Unable to select. Try again later.");
$newnum = mysql_num_rows($newresult);
$updnquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$newnum' WHERE ID='$catid'";
$updnresult = mysql_query($updnquery, $dblink) or die("Unable to update new. Please try again later.");
}
}
}

if ($Submit == "Yes, Delete Category")
{
$catquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE ID='$catid'";
$catresult = mysql_query($catquery, $dblink) or die ("Unable to update your category. Try again later.");
$catrow = mysql_fetch_row($catresult);
$oldcatid = $catrow[0];
$oldcat = $catrow[1];

// Change all existing category orders in that subcategory
$oldquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$oldparent' AND CatOrder > '$currentorder' ORDER BY CatOrder";
$oldresult = mysql_query($oldquery, $dblink) or die ("Unable to select. Try again later.");
$oldnum = mysql_num_rows($oldresult);
$neworder = $currentorder;
for ($i=1; $oldrow=mysql_fetch_row($oldresult); ++$i)
{
$updiquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$neworder' WHERE ID='$oldrow[0]'";
$updiresult = mysql_query($updiquery, $dblink) or die("Unable to update old. Please try again later.");
++ $neworder;
}
$subquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$oldcatid' ORDER BY CatOrder";
$subresult = mysql_query($subquery, $dblink) or die ("Unable to select. Try again later.");
for ($n=1; $subrow = mysql_fetch_row($subresult); ++$n)
{
$updnquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$neworder' WHERE ID='$subrow[0]'";
$updnresult = mysql_query($updnquery, $dblink) or die("Unable to update old. Please try again later.");
++ $neworder;
}
$item1query = "UPDATE " .$DB_Prefix ."_items SET Category1='' WHERE Category1='$oldcatid'";
$item1result = mysql_query($item1query, $dblink) or die("Unable to edit your category. Please try again later.");

$item2query = "UPDATE " .$DB_Prefix ."_items SET Category2='' WHERE Category2='$oldcatid'";
$item2result = mysql_query($item2query, $dblink) or die("Unable to edit your category. Please try again later.");

$item3query = "UPDATE " .$DB_Prefix ."_items SET Category3='' WHERE Category3='$oldcatid'";
$item3result = mysql_query($item3query, $dblink) or die("Unable to edit your category. Please try again later.");

$item4query = "UPDATE " .$DB_Prefix ."_items SET Category4='' WHERE Category4='$oldcatid'";
$item4result = mysql_query($item4query, $dblink) or die("Unable to edit your category. Please try again later.");

$item5query = "UPDATE " .$DB_Prefix ."_items SET Category5='' WHERE Category5='$oldcat'";
$item5result = mysql_query($item5query, $dblink) or die("Unable to edit your category. Please try again later.");

$subcatquery = "UPDATE " .$DB_Prefix ."_categories SET Parent='0' WHERE Parent='$oldcatid'";
$subcatresult = mysql_query($subcatquery, $dblink) or die("Unable to edit your category. Please try again later.");

$delcatquery = "DELETE FROM " .$DB_Prefix ."_categories WHERE ID = '$catid'";
$delcatresult = mysql_query($delcatquery, $dblink) or die("Unable to delete this category. Please try again later.");
}

if ($Submit == "Add" OR $Submit == "Edit")
{
if ($Submit == "Edit" AND $catid)
{
$getcatquery = "SELECT * FROM " .$DB_Prefix ."_categories WHERE ID = '$catid'";
$getcatresult = mysql_query($getcatquery, $dblink) or die ("Could not show initial categories. Try again later.");
$getcatrow = mysql_fetch_array($getcatresult);
$stripcategory = str_replace('"', '&quot;', stripslashes($getcatrow[Category]));
$parent = $getcatrow[Parent];
$stripmetatitle = str_replace('"', '&quot;', stripslashes($getcatrow[MetaTitle]));
$stripdescription = stripslashes($getcatrow[Description]);
$stripkeywords = stripslashes($getcatrow[Keywords]);
}
?>
<form method="POST" name="EditCat" action="categories.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="right">Category:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="category" value="<?php echo "$stripcategory";?>" size="50">
</td>
</tr>
<?php
// Are there subcats or end cats? Check
if ($Submit == "Edit")
{
$chksubquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$getcatrow[ID]'";
$chksubresult = mysql_query($chksubquery, $dblink) or die ("Unable to select. Try again later.");
$chksubnum = mysql_num_rows($chksubresult);
$checkendnum = 0;
if ($chksubnum > 0)
{
while ($chksubrow = mysql_fetch_row($chksubresult))
{
$chkendquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$chksubrow[0]'";
$chkendresult = mysql_query($chkendquery, $dblink) or die ("Unable to select. Try again later.");
$chkendnum = mysql_num_rows($chkendresult);
$checkendnum = $checkendnum + $chkendnum;
}
}
}

if ($checkendnum == 0 OR $Submit == "Add")
{
?>
<tr>
<td valign="top" align="right">Parent Category:</td>
<td valign="top" align="left" colspan="3">
<?php
echo "<input type=\"hidden\" name=\"oldparent\" value=\"$parent\">";
echo "<input type=\"hidden\" name=\"currentorder\" value=\"$getcatrow[CatOrder]\">";
$getscquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent='0' AND Category<>'$addgetcat'";
$getscresult = mysql_query($getscquery, $dblink) or die("Could not select categories");
if (mysql_num_rows($getscresult) > 0)
{
echo "<select size=\"1\" name=\"parent\">";
echo "<option value=\"0\"></option>";
for ($getsccount = 1; $getscrow = mysql_fetch_array($getscresult); ++$getsccount)
{
$stripcategory = stripslashes($getscrow[Category]);
echo "<option ";
if ($getscrow[ID] == $getcatrow[Parent])
echo "selected ";
echo "value=\"$getscrow[ID]\">$stripcategory</option>";
if ($chksubnum == 0 OR $Submit == "Add")
{
$getsubquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent='$getscrow[ID]' AND Category<>'$addgetcat'";
$getsubresult = mysql_query($getsubquery, $dblink) or die("Could not select categories");
for ($getsubcount = 1; $getsubrow = mysql_fetch_array($getsubresult); ++$getsubcount)
{
$stripsubcategory = stripslashes($getsubrow[Category]);
echo "<option ";
if ($getsubrow[ID] == $getcatrow[Parent])
echo "selected ";
echo "value=\"$getsubrow[ID]\">&nbsp;-&nbsp;$stripsubcategory</option>";
}
}
}
echo "</select>";
}
else
echo "N/A";
?>
</td>
</tr>
<?php
}
?>
<tr>
<td valign="top" align="center" colspan="4">
<i>If you want a button link for this category, enter the image URL below:</i>
</td>
</tr>
<tr>
<td valign="top" align="right">Nav Image:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="image" value="<?php echo "$getcatrow[Image]";?>"size="40"> 
<?php
echo "<a href=\"includes/imgload.php?formsname=EditCat&fieldsname=image&mo=y\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditCat&fieldsname=image&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
?>
</td>
</tr>
<tr>
<td valign="top" align="right">Header Image:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="headerimage" value="<?php echo "$getcatrow[HeaderImage]";?>"size="40">
<?php
echo "<a href=\"includes/imgload.php?formsname=EditCat&fieldsname=headerimage\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditCat&fieldsname=headerimage', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
?>
</td>
</tr>
<tr>
<td valign="top" align="right">List Image:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="listimage" value="<?php echo "$getcatrow[ListImage]";?>"size="40">
<?php
echo "<a href=\"includes/imgload.php?formsname=EditCat&fieldsname=listimage&mo=y\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditCat&fieldsname=listimage&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
?>
</td>
</tr>
<tr>
<td valign="top" align="right">Description:</td>
<td valign="top" align="left" colspan="3">
<textarea rows="5" name="description" id="description" cols="43"><?php echo "$stripdescription";?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right">Keywords:
<br><span class="smalltext">100 chars max</span></td>
<td valign="top" align="left" colspan="3">
<textarea rows="3" name="keywords" cols="43"><?php echo "$stripkeywords";?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right">Meta Title:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="metatitle" value="<?php echo "$stripmetatitle";?>" size="50">
</td>
</tr>
<tr>
<td valign="top" align="right">Layout:</td>
<td valign="top" align="left">
<select size="1" name="catcolumns">
<?php
echo "<option ";
if ($getcatrow[CatColumns] == "")
echo "selected ";
echo "value=\"\">N/A</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "1")
echo "selected ";
echo "value=\"1\">Single Product Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "D")
echo "selected ";
echo "value=\"D\">Double Product Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "T")
echo "selected ";
echo "value=\"T\">Triple Product Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "2")
echo "selected ";
echo "value=\"2\">2 Column Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "3")
echo "selected ";
echo "value=\"3\">3 Column Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "4")
echo "selected ";
echo "value=\"4\">4 Column Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "5")
echo "selected ";
echo "value=\"5\">5 Column Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "6")
echo "selected ";
echo "value=\"6\">6 Column Layout</option>";
echo "<option ";
if ($getcatrow[CatColumns] == "G")
echo "selected ";
echo "value=\"G\">Grid Listing (No Images)</option>";
?>
</select>
</td>
<td valign="top" align="right">Rows:</td>
<td valign="top" align="left">
<select size="1" name="catrows">
 <?php
echo "<option ";
if (!$getcatrow[CatRows])
echo "selected ";
echo "value=\"\">N/A</option>";
for ($ir = 1; $ir <= 30; ++$ir)
{
echo "<option ";
if ($getcatrow[CatRows]== "$ir")
echo "selected ";
echo "value=\"$ir\">$ir</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td valign="top" align="right">Active:</td>
<td valign="top" align="left" colspan="3">
<select size="1" name="active">
<?php
if ($getcatrow[Active] == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="4">
<?php
if ($Submit == "Edit")
{
echo "<input type=\"hidden\" value=\"$catid\" name=\"catid\">";
echo "<input type=\"submit\" class=\"button\" value=\"Edit Category\" name=\"Submit\">";
}
else
echo "<input type=\"submit\" class=\"button\" value=\"Add Category\" name=\"Submit\">";
?>
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}
else if ($Submit == "Delete")
{
$getcatquery = "SELECT * FROM " .$DB_Prefix ."_categories WHERE ID = '$catid'";
$getcatresult = mysql_query($getcatquery, $dblink) or die ("Could not show categories. Try again later.");
$getcatrow = mysql_fetch_array($getcatresult);
$stripcategory = stripslashes($getcatrow[Category]);
?>
<form method="POST" action="categories.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="center">
You are about to delete the following category:
<p align="center" class="fieldname">
<?php
echo "$stripcategory";
?>
</p>
<p>Do you want to continue?</p>
<?php
echo "<input type=\"hidden\" value=\"$catid\" name=\"catid\">";
echo "<input type=\"hidden\" name=\"oldparent\" value=\"$getcatrow[Parent]\">";
echo "<input type=\"hidden\" name=\"currentorder\" value=\"$getcatrow[CatOrder]\">";
?>
<input type="submit" class="button" value="Yes, Delete Category" name="Submit">&nbsp; <input type="submit" class="button" value="No, Don't Delete" name="Submit">
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
// Update order if needed
if ($curord AND $neword AND $catid)
{
$parval = "";
if ($parent)
{
$parquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE ID='$parent'";
$parresult = mysql_query($parquery, $dblink) or die ("Unable to select. Try again later.");
$parrow = mysql_fetch_row($parresult);
$parval = $parrow[0];
}
$updquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$curord' WHERE CatOrder='$neword' AND Parent='$parval'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update 1. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$neword' WHERE ID='$catid'";
$upd2result = mysql_query($upd2query, $dblink) or die("Unable to update 2. Please try again later.");
}
else
{
// Reset main categories
$mcquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='0' ORDER BY CatOrder, Category";
$mcresult = mysql_query($mcquery, $dblink) or die ("Unable to select main cats. Try again later.");
for ($m=1; $mcrow = mysql_fetch_row($mcresult); ++$m)
{
$mupdquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$m' WHERE ID='$mcrow[0]'";
$mupdresult = mysql_query($mupdquery, $dblink) or die("Unable to update main order. Please try again later.");
// Reset sub categories
$scquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$mcrow[0]' ORDER BY CatOrder, Category";
$scresult = mysql_query($scquery, $dblink) or die ("Unable to select sub cats. Try again later.");
for ($s=1; $scrow = mysql_fetch_row($scresult); ++$s)
{
$supdquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$s' WHERE ID='$scrow[0]'";
$supdresult = mysql_query($supdquery, $dblink) or die("Unable to update sub order. Please try again later.");
// Reset end categories
$ecquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$ecrow[0]' ORDER BY CatOrder, Category";
$ecresult = mysql_query($ecquery, $dblink) or die ("Unable to select end cats. Try again later.");
for ($e=1; $ecrow = mysql_fetch_row($ecresult); ++$e)
{
$eupdquery = "UPDATE " .$DB_Prefix ."_categories SET CatOrder='$e' WHERE ID='$ecrow[0]'";
$eupdresult = mysql_query($eupdquery, $dblink) or die("Unable to update end order. Please try again later.");
}
}
}
}
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<?php
$getcatquery = "SELECT ID, Category, CatOrder FROM " .$DB_Prefix ."_categories WHERE Parent = '0' ORDER BY CatOrder, Category";
$getcatresult = mysql_query($getcatquery, $dblink) or die ("Could not show initial categories. Try again later.");
$getcatnum = mysql_num_rows($getcatresult);

if ($getcatnum == 0)
echo "<tr><td>No Categories Listed.</td></tr>";
else
{
echo "<tr>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "<td class=\"fieldname\">Category</td>";
echo "<td class=\"fieldname\">Order</td>";
echo "<td align=\"left\" class=\"fieldname\">Action</td>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "</tr>";

for ($getcatcount = 1; $getcatrow = mysql_fetch_row($getcatresult); ++$getcatcount)
{
$subcatquery = "SELECT ID, Category, CatOrder FROM " .$DB_Prefix ."_categories WHERE Parent = '$getcatrow[0]' ORDER BY CatOrder, Category";
$subcatresult = mysql_query($subcatquery, $dblink) or die ("Could not show subcategories. Try again later.");
$subcatnum = mysql_num_rows($subcatresult);
echo "<tr>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "<td nowrap>" .stripslashes($getcatrow[1]) ."</td>";
echo "<td align=\"center\" nowrap>";
if ($getcatnum > 1)
{
if ($getcatcount < $getcatnum)
{
$newordg2 = $getcatcount+1;
echo "<a href=\"categories.php?catid=$getcatrow[0]&curord=$getcatrow[2]&neword=$newordg2\">";
echo "<img border=\"0\" alt=\"down\" src=\"images/downarrow.gif\" height=\"10\" width=\"9\"></a> ";
if ($getcatcount == 1)
echo "<img border=\"0\" alt=\"up\" src=\"images/spacer.gif\" height=\"1\" width=\"9\">";
}
if ($getcatcount > 1)
{
$newordg1 = $getcatcount-1;
if ($getcatcount == $getcatnum)
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"9\"> ";
echo "<a href=\"categories.php?catid=$getcatrow[0]&curord=$getcatrow[2]&neword=$newordg1\">";
echo "<img border=\"0\" alt=\"up\" src=\"images/uparrow.gif\" height=\"10\" width=\"9\"></a>";
}
}
else
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"20\">";
echo "</td>";
echo "<td nowrap>";
echo "<a href=\"categories.php?catid=$getcatrow[0]&Submit=Edit\">Edit</a> | ";
echo "<a href=\"categories.php?catid=$getcatrow[0]&Submit=Delete\">Delete</a> | ";
echo "<a href=\"itemlist.php?catsearch=$getcatrow[0]\">View Items</a>";
echo "</td>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "</tr>";
for ($subcatcount = 1; $subcatrow = mysql_fetch_row($subcatresult); ++$subcatcount)
{
echo "<tr>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "<td nowrap>&nbsp;-&nbsp;" .stripslashes($subcatrow[1]) ."</td>";
echo "<td align=\"center\" nowrap>";
if ($subcatnum > 1)
{
if ($subcatcount < $subcatnum)
{
$newords2 = $subcatcount+1;
echo "<a href=\"categories.php?catid=$subcatrow[0]&parent=$getcatrow[0]&curord=$subcatrow[2]&neword=$newords2\">";
echo "<img border=\"0\" alt=\"down\" src=\"images/downarrow2.gif\" height=\"10\" width=\"9\"></a> ";
if ($subcatcount == 1)
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"9\">";
}
if ($subcatcount > 1)
{
$newords1 = $subcatcount-1;
if ($subcatcount == $subcatnum)
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"9\"> ";
echo "<a href=\"categories.php?catid=$subcatrow[0]&parent=$getcatrow[0]&curord=$subcatrow[2]&neword=$newords1\">";
echo "<img border=\"0\" alt=\"up\" src=\"images/uparrow2.gif\" height=\"10\" width=\"9\"></a>";
}
}
else
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"20\">";
echo "</td>";
echo "<td nowrap>";
echo "<a href=\"categories.php?catid=$subcatrow[0]&Submit=Edit\">Edit</a> | ";
echo "<a href=\"categories.php?catid=$subcatrow[0]&Submit=Delete\">Delete</a> | ";
echo "<a href=\"itemlist.php?catsearch=$subcatrow[0]\">View Items</a>";
echo "</td>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "</tr>";
$endcatquery = "SELECT ID, Category, CatOrder FROM " .$DB_Prefix ."_categories WHERE Parent = '$subcatrow[0]' ORDER BY CatOrder, Category";
$endcatresult = mysql_query($endcatquery, $dblink) or die ("Could not show endcategories. Try again later.");
$endcatnum = mysql_num_rows($endcatresult);
for ($endcatcount = 1; $endcatrow = mysql_fetch_row($endcatresult); ++$endcatcount)
{
echo "<tr>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;" .stripslashes($endcatrow[1]) ."</td>";
echo "<td align=\"center\" nowrap>";
if ($endcatnum > 1)
{
if ($endcatcount < $endcatnum)
{
$neworde2 = $endcatcount+1;
echo "<a href=\"categories.php?catid=$endcatrow[0]&parent=$subcatrow[0]&curord=$endcatrow[2]&neword=$neworde2\">";
echo "<img border=\"0\" alt=\"down\" src=\"images/downarrow3.gif\" height=\"10\" width=\"9\"></a> ";
if ($endcatcount == 1)
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"9\">";
}
if ($endcatcount > 1)
{
$neworde1 = $endcatcount-1;
if ($endcatcount == $endcatnum)
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"9\"> ";
echo "<a href=\"categories.php?catid=$endcatrow[0]&parent=$subcatrow[0]&curord=$endcatrow[2]&neword=$neworde1\">";
echo "<img border=\"0\" alt=\"up\" src=\"images/uparrow3.gif\" height=\"10\" width=\"9\"></a>";
}
}
else
echo "<img border=\"0\" alt=\"-\" src=\"images/spacer.gif\" height=\"1\" width=\"20\">";
echo "</td>";
echo "<td nowrap>";
echo "<a href=\"categories.php?catid=$endcatrow[0]&Submit=Edit\">Edit</a> | ";
echo "<a href=\"categories.php?catid=$endcatrow[0]&Submit=Delete\">Delete</a> | ";
echo "<a href=\"itemlist.php?catsearch=$endcatrow[0]\">View Items</a>";
echo "</td>";
echo "<td width=\"50%\">&nbsp;</td>";
echo "</tr>";
}
}
}
}
?>
<tr>
<td valign="middle" align="center" colspan="7" width="100%">
<form action="categories.php" method="POST">
<input type="submit" class="button" value="Add" name="Submit">
</form>
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