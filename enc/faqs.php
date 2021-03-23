<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

echo "<a name=\"top\"></a>";

$faqquery = "SELECT ID, Question FROM " .$DB_Prefix ."_faq ORDER BY ListOrder";
$faqresult = mysql_query($faqquery, $dblink) or die ("Unable to select. Try again later.");
echo "<ul>";
while ($faqrow = mysql_fetch_row($faqresult))
{
echo "<li><a href=\"faqs.$pageext#Q$faqrow[0]\">" .stripslashes($faqrow[1]) ."</a></li>";
}
echo "</ul>";

$ansquery = "SELECT * FROM " .$DB_Prefix ."_faq ORDER BY ListOrder";
$ansresult = mysql_query($ansquery, $dblink) or die ("Unable to select. Try again later.");
while ($ansrow = mysql_fetch_array($ansresult))
{
echo "<p><a name=\"Q$ansrow[ID]\"></a>";
echo "<span class=\"boldtext\">" .stripslashes($ansrow[Question]) ."</span> ";
echo " <a href=\"faqs.$pageext#top\" class=\"smfont\">TOP</a><br>";
echo stripslashes($ansrow[Answer]);
echo "</p>";
}
?>
