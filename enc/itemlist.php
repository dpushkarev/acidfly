<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

echo "<br>";
$listquery = "SELECT ID, Catalog, Item FROM " . $DB_Prefix . "_items WHERE Active='Yes'";
$listresult = mysqli_query($dblink, $listquery) or die ("Unable to select. Try again later.");
for ($l = 1; $listrow = mysqli_fetch_row($listresult); ++$l) {
    echo "<br>";
    $gotoitem = "$Catalog_Page?item=$listrow[0]";
    echo "<a href=\"$gotoitem\">";
    if ($listrow[1])
        echo "#" . stripslashes($listrow[1]) . ". ";
    echo stripslashes($listrow[2]);
    echo "</a>";
}
?>
