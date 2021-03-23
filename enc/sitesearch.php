<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if (isset($keyword))
{
if (empty($cond))
$condition = "AND";
else
$condition = strtoupper($cond);
$searchinfo = str_replace("&", " ", $keyword);
$searchterm = explode(" ", trim($searchinfo));
for ($kt=0; $kt < count($searchterm); ++$kt)
{
if (strlen($searchterm[$kt]) < 3)
$ktcheck = "yes";
}
if ($ktcheck == "yes")
echo "<p align=\"center\">All search terms must be at least three characters long. Please try again.</p>";
else
{

// SEARCH PAGES
$kwquery = "SELECT PageName, PageTitle, PageType FROM " .$DB_Prefix ."_pages WHERE Active='Yes' AND (";
// START KEYWORD PAGE SEARCH
if ($condition == "PHRASE")
$kwquery .= "Content LIKE '%$keyword%'";
else
{
for ($scounter = 0; $scounter < count($searchterm); ++$scounter)
{
$addlterm = addslash_mq(stripbadstuff($searchterm[$scounter]));
if ($scounter > 0)
$kwquery .= " $condition ";
$kwquery .= "Content LIKE '%$addlterm%'";
}
}
// END KEYWORD PAGE SEARCH
$kwquery .= ")";
$kwresult = mysql_query($kwquery, $dblink) or die ("Unable to select kw. Try again later.");
$kwnum = mysql_num_rows($kwresult);

// SEARCH CATEGORIES
$catquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Active='Yes' AND (";
// START KEYWORD CATEGORY SEARCH
if ($condition == "PHRASE")
$catquery .= "Category LIKE '%$keyword%' OR Keywords LIKE '%$keyword%'";
else
{
for ($counter = 0; $counter < count($searchterm); ++$counter)
{
$addlterm = addslash_mq(stripbadstuff($searchterm[$counter]));
if ($counter > 0)
$catquery .= " $condition ";
$catquery .= "(Category LIKE '%$addlterm%' OR Keywords LIKE '%$addlterm%')";
}
}
// END KEYWORD CATEGORY SEARCH
$catquery .= ")";
$catresult = mysql_query($catquery, $dblink) or die ("Unable to select key. Try again later.");
$catnum = mysql_num_rows($catresult);

// SEARCH ITEMS
$keyquery = "SELECT ID, Catalog, Item FROM " .$DB_Prefix ."_items WHERE Active='Yes' AND (";
// START KEYWORD ITEM SEARCH
if ($condition == "PHRASE")
$keyquery .= "Item LIKE '%$keyword%' OR Keywords LIKE '%$keyword%'";
else
{
for ($counter = 0; $counter < count($searchterm); ++$counter)
{
$addlterm = addslash_mq(stripbadstuff($searchterm[$counter]));
if ($counter > 0)
$keyquery .= " $condition ";
$keyquery .= "(Item LIKE '%$addlterm%' OR Keywords LIKE '%$addlterm%')";
}
}
// END KEYWORD ITEM SEARCH
$keyquery .= ")";
$keyresult = mysql_query($keyquery, $dblink) or die ("Unable to select key. Try again later.");
$keynum = mysql_num_rows($keyresult);

// SEARCH ARTICLES
$artchkquery = "SELECT ID FROM " .$DB_Prefix ."_pages WHERE PageName='articles' AND PageType='optional' AND Active='Yes'";
$artchkresult = mysql_query($artchkquery, $dblink) or die ("Unable to select artch. Try again later.");
if (mysql_num_rows($artchkresult) == 1)
{
$artquery = "SELECT ID, Title FROM " .$DB_Prefix ."_articles WHERE ";
// START ARTICLE SEARCH
if ($condition == "PHRASE")
$artquery .= "Title LIKE '%$keyword%' OR Article LIKE '%$keyword%'";
else
{
for ($scounter = 0; $scounter < count($searchterm); ++$scounter)
{
$addlterm = addslash_mq(stripbadstuff($searchterm[$scounter]));
if ($scounter > 0)
$artquery .= " $condition ";
$artquery .= "(Title LIKE '%$addlterm%' OR Article LIKE '%$addlterm%')";
}
}
// END ARTICLE SEARCH
$artquery .= " ORDER BY ListOrder, Title";
$artresult = mysql_query($artquery, $dblink) or die ("Unable to select art. Try again later.");
$artnum = mysql_num_rows($artresult);
}

// SEARCH FAQ
$faqchkquery = "SELECT ID FROM " .$DB_Prefix ."_pages WHERE PageName='faqs' AND PageType='optional' AND Active='Yes'";
$faqchkresult = mysql_query($faqchkquery, $dblink) or die ("Unable to select faqch. Try again later.");
if (mysql_num_rows($faqchkresult) == 1)
{
$faqquery = "SELECT ID, Question FROM " .$DB_Prefix ."_faq WHERE ";
// START FAQ SEARCH
if ($condition == "PHRASE")
$faqquery .= "Question LIKE '%$keyword%' OR Answer LIKE '%$keyword%'";
else
{
for ($scounter = 0; $scounter < count($searchterm); ++$scounter)
{
$addlterm = addslash_mq(stripbadstuff($searchterm[$scounter]));
if ($scounter > 0)
$faqquery .= " $condition ";
$faqquery .= "(Question LIKE '%$addlterm%' OR Answer LIKE '%$addlterm%')";
}
}
// END FAQ SEARCH
$faqquery .= " ORDER BY ListOrder, Question";
$faqresult = mysql_query($faqquery, $dblink) or die ("Unable to select faq. Try again later.");
$faqnum = mysql_num_rows($faqresult);
}

if ($kwnum == 0 AND $catnum == 0 AND $keynum == 0 AND $artnum == 0 AND $faqnum == 0)
echo "<p>No information found. Please try again.</p>";
else
{
$totallists = 0;
echo "<p>The following information was found:</p>";
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">";
echo "<tr>";

// Show site pages
if ($kwnum > 0)
{
$sitepages = "<td valign=\"top\">";
$sitepages .= "<span class=\"boldtext\">Site Pages</span>";
while ($kwrow = mysql_fetch_row($kwresult))
{
if ($kwrow[2] == "additional")
$page_dir="pages/";
else
$page_dir="";
$sitepages .= "<br><a href=\"$URL/$page_dir$kwrow[0].$pageext\">$kwrow[1]</a>";
}
$sitepages .= "</td>";
++$totallists;
}

// Show category pages
if ($catnum > 0)
{
$catlist = "<td valign=\"top\">";
$catlist .= "<span class=\"boldtext\">Categories</span>";
while ($catrow = mysql_fetch_row($catresult))
{
$clist = "$Catalog_Page?category=$catrow[0]";
$catlist .= "<br><a href=\"$Catalog_Page?category=$catrow[0]\">$catrow[1]</a>";
}
$catlist .= "</td>";
++$totallists;
}

// Show catalog pages
if ($keynum > 0)
{
$itemlist = "<td valign=\"top\">";
$itemlist .= "<span class=\"boldtext\">Catalog Items</span>";
while ($keyrow = mysql_fetch_row($keyresult))
{
$catlink = "$Catalog_Page?item=$keyrow[0]";
$itemlist .= "<br><a href=\"$Catalog_Page?item=$keyrow[0]\">";
if ($keyrow[1])
$itemlist .= "#$keyrow[1]. ";
$itemlist .= "$keyrow[2]</a>";
}
$itemlist .= "</td>";
++$totallists;
}

// Show articles
if ($artnum > 0)
{
$articlelist = "<td valign=\"top\">";
$articlelist .= "<span class=\"boldtext\">Articles</span>";
while ($artrow = mysql_fetch_row($artresult))
{
$titlename = stripslashes($artrow[1]);
$articlelist .= "<br><a href=\"$URL/articles.$pageext?article=$artrow[0]\">$titlename</a>";
}
$articlelist .= "</td>";
++$totallists;
}

// Show faqs
if ($faqnum > 0)
{
$faqlist = "<td valign=\"top\">";
$faqlist .= "<span class=\"boldtext\">Questions</span>";
while ($faqrow = mysql_fetch_row($faqresult))
{
$question = stripslashes($faqrow[1]);
$faqlist .= "<br><a href=\"$URL/faqs.$pageext#Q$faqrow[0]\">$question</a>";
}
$faqlist .= "</td>";
++$totallists;
}

if ($totallists == 5)
echo "$sitepages$catlist$itemlist</tr><tr>$articlelist$faqlist<td>&nbsp;</td>";
else if (!$sitepages AND $totallists == 4)
echo "$catlist$itemlist</tr><tr>$articlelist$faqlist";
else if (!$catlist AND $totallists == 4)
echo "$sitepages$itemlist</tr><tr>$articlelist$faqlist";
else if (!$itemlist AND $totallists == 4)
echo "$sitepages$catlist</tr><tr>$articlelist$faqlist";
else if (!$articlelist AND $totallists == 4)
echo "$sitepages$catlist</tr><tr>$itemlist$faqlist";
else if (!$faqlist AND $totallists == 4)
echo "$sitepages$catlist</tr><tr>$itemlist$articlelist";
else
echo "$sitepages$catlist$itemlist$articlelist$faqlist";
echo "</tr>";
echo "</table>";
}
}
}
?>

<form method="POST" action="<?php echo "sitesearch.$pageext"; ?>">
<div align="center">
<center>
<table border="0" cellpadding="3" cellspacing="0">
<tr>
<td valign="top" align="right">Keyword(s):</td>
<td valign="top" align="left">
<?php
if (!empty($keyword))
$setkw = str_replace('"', "&quot;", $keyword);
else
$setkw = "";
echo "<input type=\"text\" name=\"keyword\" size=\"32\" value=\"$setkw\"><br>";
echo "<span class=\"smfont\">";
echo "<input type=\"radio\" name=\"cond\" value=\"and\"";
if (empty($cond) OR $cond == "and")
echo " checked";
echo ">All Words ";
echo "<input type=\"radio\" name=\"cond\" value=\"or\"";
if ($cond == "or")
echo " checked";
echo ">Any Word ";
echo "<input type=\"radio\" name=\"cond\" value=\"phrase\"";
if ($cond == "phrase")
echo " checked";
echo ">Exact Phrase";
echo "</span>";
?>
</td>
</tr>
<tr>
<td valign="top" align="center" colspan="2">
<input type="submit" value="Search" class="formbutton">
</td>
</tr>
</table>
</center>
</div>
</form>
