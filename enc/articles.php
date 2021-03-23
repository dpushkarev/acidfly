<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($article)
{
$showquery = "SELECT * FROM " .$DB_Prefix ."_articles WHERE ID='$article'";
$showresult = mysql_query($showquery, $dblink) or die ("Unable to select. Try again later.");
$showrow = mysql_fetch_array($showresult);
$article_content = stripslashes($showrow[Article]);
if (strstr($showrow[Article], '<') == FALSE AND strstr($showrow[Article], '>') == FALSE)
{
$article_content = str_replace ("\r", "<br>", $article_content);
$article_content = "<p>$article_content</p>";
}
echo "<p class=\"boldtext\">" .stripslashes($showrow[Title]) ."</p>";
echo "$article_content";

echo "<p align=\"center\"><a href=\"articles.$pageext\">Back to Articles</a></p>";
}

else
{
$artquery = "SELECT ID, Title FROM " .$DB_Prefix ."_articles ORDER BY ListOrder";
$artresult = mysql_query($artquery, $dblink) or die ("Unable to select. Try again later.");
echo "<ul>";
while ($artrow = mysql_fetch_row($artresult))
{
echo "<li><a href=\"articles.$pageext?article=$artrow[0]\">" .stripslashes($artrow[1]) ."</a></li>";
}
echo "</ul>";
}

?>
