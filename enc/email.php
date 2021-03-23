<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Email to a friend link
if ($Email_Link != "" AND $setpg != $catpg)
{
$emailpg = "page=$setpg";
if ($dirname)
$emailpg .= "&dir=$dirname";
if (!empty($_SERVER['QUERY_STRING']))
$emailpg .= "%3F" .urlencode($_SERVER['QUERY_STRING']);
echo "<a href=\"$URL/go/email.php?$emailpg\" target=\"_blank\" onClick=\"PopUp=window.open('$URL/go/email.php?$emailpg', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\" class=\"emailcolor\">";
echo "<img border=\"0\" src=\"$URL/$Inc_Dir/envelope.gif\" alt=\"" .stripslashes($Email_Link) ."\" width=\"20\" height=\"12\"></a>";
echo "&nbsp;<a href=\"$URL/go/email.php?$emailpg\" target=\"_blank\" onClick=\"PopUp=window.open('$URL/go/email.php?$emailpg', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\">";
echo stripslashes($Email_Link) ."</a>";
}
?>