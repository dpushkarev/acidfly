<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// START FORMATTING OF PAGE NUMBERS
if (!IsSet($pageformat))
{
$offset = ($page-1)*$LimitOfItems;
if ($totalsearchnum % $LimitOfItems == 0)
$page_count = ($totalsearchnum-($totalsearchnum%$LimitOfItems)) / $LimitOfItems;
else
$page_count = ($totalsearchnum-($totalsearchnum%$LimitOfItems)) / $LimitOfItems + 1;

if ($category)
$linkinfo .= "&category=$category";
if ($keyword)
{
$keyword = stripslashes($keyword);
$keyword = urlencode($keyword);
$linkinfo .= "&keyword=$keyword";
}
if ($cond)
$linkinfo .= "&cond=$cond";
if ($item)
$linkinfo .= "&item=$item";
if ($catalog)
$linkinfo .= "&catalog=$catalog";
if ($price)
$linkinfo .= "&price=$price";
if ($sale)
$linkinfo .= "&sale=yes";
if ($all)
$linkinfo .= "&all=yes";
if ($new)
$linkinfo .= "&new=yes";

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
$output_string .= "<a href=\"$Catalog_Page?page=$i$linkinfo\" title=\"Go to Page $i\">$i</a>";
else
$output_string .= "<b>$i</b>";
if ($i != $page_count AND $i != $n*2+$pagestart)
$output_string .= " | ";
}
$i++;
}
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
echo "<a href=\"$Catalog_Page?page=1$linkinfo\" class=\"pagelinkcolor\" title=\"Go to First Page\">&lt;&lt;</a> &nbsp;";
echo "<a href=\"$Catalog_Page?page=$previous$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Previous Page\">&lt;</a> &nbsp; ";
}
echo "$output_string";
if ($page < $page_count)
{
echo " &nbsp; <a href=\"$Catalog_Page?page=$next$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Next Page\">&gt;</a>";
echo "&nbsp; <a href=\"$Catalog_Page?page=$page_count$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Last Page\">&gt;&gt;</a>";
}
if (!IsSet($pageformat))
echo "<br>Page <b>$page</b> of <b>$page_count</b>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
if ($page > 1)
echo "<a href=\"$Catalog_Page?page=1$linkinfo\" class=\"pagelinkcolor\" title=\"Go to First Page\">&lt;&lt; First</a> | <a href=\"$Catalog_Page?page=$previous$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Previous Page\">&lt; Previous</a> | ";
echo "Page <b>$page</b> of <b>$page_count</b>";
if ($page < $page_count)
echo " | <a href=\"$Catalog_Page?page=$next$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Next Page\">Next &gt;</a> | <a href=\"$Catalog_Page?page=$page_count$linkinfo\" class=\"pagelinkcolor\" title=\"Go to Last Page\">Last &gt;&gt;</a>";
echo "</p>";
}
}
$pageformat = "yes";
// END DISPLAY OF PAGE NUMBERS
?>
