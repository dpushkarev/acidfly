<?php
// Show editor?
$show_htm_edit = "no";
if (!$setpg)
{
$thisdir = dirname($_SERVER[PHP_SELF]);
$setpg = str_replace($thisdir,"",$_SERVER[PHP_SELF]);
$setpg = str_replace("/","",$setpg);
$setpg = str_replace(".php", "", $setpg);
}
if ($setpg == "articles" AND ($mode == "Edit" OR $mode == "Add"))
$show_htm_edit = "yes";
if ($setpg == "categories" AND ($Submit == "Edit" OR $Submit == "Add"))
$show_htm_edit = "yes";
if ($setpg == "events" AND ($mode == "Edit" OR $mode == "Add"))
$show_htm_edit = "yes";
if ($setpg == "faqs" AND ($mode == "Edit" OR $mode == "Add"))
$show_htm_edit = "yes";
if ($setpg == "items" AND ($Submit == "Edit" OR $Submit == "Add"))
$show_htm_edit = "yes";
if ($setpg == "pages" AND ($edit == "yes" OR $add == "yes"))
$show_htm_edit = "yes";
if ($setpg == "popups" AND ($popup == "Edit" OR $popup == "Add"))
$show_htm_edit = "yes";
if ($setpg == "variables")
$show_htm_edit = "yes";

if ($show_htm_edit == "yes")
{
// Version 2 - Works with IE 5.5+ only
if (file_exists("htmlarea/editor.js"))
echo " onload=\"editor_generate('$java');\"";
// Version 3 - works with IE 5.5+ and Mozilla 1.3+
else if (file_exists("htmlarea/htmlarea.js"))
echo " onload=\"HTMLArea.replace('$java', config);\"";
}
?>
