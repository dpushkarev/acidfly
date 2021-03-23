<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Set page numbers
if (!$page)
{
$page = 1;
$offset = 0;
}
else
$offset = (($LimitOfItems * $page)-$LimitOfItems);

$getlinkquery = "SELECT * FROM " .$DB_Prefix ."_links WHERE Active='Yes' ORDER BY LinkOrder, LinkName";
$totallinkquery = $getlinkquery;
$getlinkquery .= " LIMIT $offset, $LimitOfItems";
$getlinkresult = mysql_query($getlinkquery, $dblink) or die ("Could not show links. Try again later.");
$totallinkresult = mysql_query($totallinkquery, $dblink) or die ("Unable to access total records.");
$totallinknum = mysql_num_rows($totallinkresult);

if (mysql_num_rows($getlinkresult) > 0)
{
// START FORMATTING OF PAGE NUMBERS
$offset = ($page-1)*$LimitOfItems;
if ($totallinknum % $LimitOfItems == 0)
$page_count = ($totallinknum-($totallinknum%$LimitOfItems)) / $LimitOfItems;
else
$page_count = ($totallinknum-($totallinknum%$LimitOfItems)) / $LimitOfItems + 1;

$previous = $page - 1;
$next = $page + 1;

if ($page_count > 1)
{
$i = 1;
$n = 3;
if ($page < $n+1)
$pagestart = 1;
else if ($page > ($page_count-$n))
$pagestart = $page_count-$n*2;
else
$pagestart = $page-$n;

while ($i <= $page_count)
{
if (($i >= $pagestart) AND ($i <= $n*2+$pagestart))
{
if ($i != $page)
$output_string .= "<a href=\"weblinks.$pageext?page=$i\" class=\"pagelinkcolor\">$i</a>";
else
$output_string .= "<b>$i</b>";
if ($i != $page_count AND $i != $n*2+$pagestart)
$output_string .= " | ";
}
$i++;
}
}
// END FORMATTING OF PAGE NUMBERS

// START DISPLAY OF PAGE NUMBERS
if ($page_count > 1)
{
if ($Page_Numbers == "Yes")
{
echo "<p align=\"center\">";
if ($page > 1)
{
echo "<a href=\"weblinks.$pageext?page=1\" class=\"pagelinkcolor\">&lt;&lt;</a> | ";
echo "<a href=\"weblinks.$pageext?page=$previous\" class=\"pagelinkcolor\">&lt;</a> | ";
}
echo "$output_string";
if ($page < $page_count)
{
echo " | <a href=\"weblinks.$pageext?page=$next\" class=\"pagelinkcolor\">&gt;</a>";
echo " | <a href=\"weblinks.$pageext?page=$page_count\" class=\"pagelinkcolor\">&gt;&gt;</a>";
}
echo "<br>Page <b>$page</b> of <b>$page_count</b>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
if ($page > 1)
echo "<a href=\"weblinks.$pageext?page=1\" class=\"pagelinkcolor\">&lt;&lt; First</a> | <a href=\"weblinks.$pageext?page=$previous\" class=\"pagelinkcolor\">&lt; Previous</a> | ";
echo "Page <b>$page</b> of <b>$page_count</b>";
if ($page < $page_count)
echo " | <a href=\"weblinks.$pageext?page=$next\" class=\"pagelinkcolor\">Next &gt;</a> | <a href=\"weblinks.$pageext?page=$page_count\" class=\"pagelinkcolor\">Last &gt;&gt;</a>";
echo "</p>";
}
}
// END DISPLAY OF PAGE NUMBERS

while ($getlinkrow = mysql_fetch_array($getlinkresult))
{
if ($Product_Line)
echo "<hr class=\"linecolor\">";
// If code exists, display it
if ($getlinkrow[LinkCode])
echo "<p>" .stripslashes($getlinkrow[LinkCode]) ."</p>";

// If no link code, display manual link
else
{
echo "<p>";
if ($getlinkrow[LinkName])
$linkname = stripslashes($getlinkrow[LinkName]);
else
$linkname = $getlinkrow[SiteURL];
if ($getlinkrow[Image])
echo "<a href=\"$getlinkrow[SiteURL]\" target=\"_blank\"><img src=\"$getlinkrow[Image]\" alt=\"$linkname\" border=\"0\"></a><br>";
echo "<a href=\"$getlinkrow[SiteURL]\" target=\"_blank\" class=\"boldtext\">$linkname</a>";
if ($getlinkrow[Description])
{
$stripdescrip = stripslashes($getlinkrow[Description]);
if (strstr($stripdescrip, '<') == FALSE AND strstr($stripdescrip, '>') == FALSE)
{
$stripdescrip = str_replace ("\r", "<br>", $stripdescrip);
echo "<br>$stripdescrip";
}
else
echo "$stripdescrip";
}
echo "</p>";
}
}
if ($Product_Line)
echo "<hr class=\"linecolor\">";
}
?>