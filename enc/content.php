<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

// Display content only upon first initial page startup
if (($dirname == "pages" OR ($pg_type == "required" AND $pgnm_noext == "index") OR ($dirname == "" AND $_SERVER['REQUEST_METHOD'] == "GET" AND !$_SERVER['QUERY_STRING'])) AND $pg_content) {
    echo "$pg_content";
}

// Show order page if order button
if ($dirname == "go" AND $pgnm_noext != "itemlist") {
    include("$Home_Path/go/iframe.php");
}

// Display include only for pages in main directory
if ($dirname == "" AND $setpg == $catpg) {
    include("$Home_Path/$Inc_Dir/catalog.php");
} else if ($dirname == "" OR ($dirname == "go" AND $pgnm_noext == "itemlist")) {
    include("$Home_Path/$Inc_Dir/$pgnm_noext.php");
}
flush();
?>
