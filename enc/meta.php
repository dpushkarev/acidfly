<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

if ($Meta_Description != "")
    echo "<meta name=\"description\" content=\"" . str_replace('"', '', $Meta_Description) . "\">\r\n";
if ($Meta_Keywords != "")
    echo "<meta name=\"keywords\" content=\"" . str_replace('"', '', $Meta_Keywords) . "\">\r\n";
?>
