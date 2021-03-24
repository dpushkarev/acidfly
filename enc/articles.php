<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($article)
{
$showquery = "SELECT * FROM " .$DB_Prefix ."_articles WHERE ID='$article'";
$showresult = mysqli_query($dblink, $showquery) or die ("Unable to select. Try again later.");
$showrow = mysqli_fetch_array($showresult);
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
$artresult = mysqli_query($dblink, $artquery) or die ("Unable to select. Try again later.");
echo "<ul>";
while ($artrow = mysqli_fetch_row($artresult))
{
echo "<li><a href=\"articles.$pageext?article=$artrow[0]\">" .stripslashes($artrow[1]) ."</a></li>";
}
echo "</ul>";
}

?>
