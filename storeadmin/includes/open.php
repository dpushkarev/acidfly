<?php
// Set Page
$thisdir = dirname($_SERVER[PHP_SELF]);
$setpg = str_replace($thisdir,"",$_SERVER[PHP_SELF]);
$setpg = str_replace("/","",$setpg);
$setpg = str_replace(".php", "", $setpg);
$setmode = "$setpg";
$stripthisdir = str_replace("/", "", substr($thisdir, -8));
if ($stripthisdir == "includes")
$inc_dir_nm = "";
else
$inc_dir_nm = "includes/";
// Create submodes
include($inc_dir_nm ."submodes.php");
// Set permission groups
$group = array("Web Site", "E-Store", "Advanced", "Site Extras", "Administration");

// On what group number should the first set of links end?
$linkend = 3;

if ($stripthisdir == "includes")
require_once("../../stconfig.php");
else
require_once("../stconfig.php");

if (file_exists("../setup.php"))
{
die ("The setup file still exists. Please delete this file before continuing!");
}

if ($_POST[adminmaster] != "")
$mastaddl = "?master=y";
$err_msg = "<p align=\"center\">Sorry, but your user name or password was not correct. Please <a href=\"index.php$mastaddl\">go back</a> and try again.</p>";
$code_msg = "<p align=\"center\">Please <a href=\"variables.php\">update</a> your domain and key code.</p>";
$mstr_msg = "<p align=\"center\">Sorry, but this page cannot be found. Please try again or contact the administrator.</p>";
$pswdquery = "SELECT AdminPass, OpenSet, URL FROM " .$DB_Prefix ."_vars WHERE ID=1";
$pswdresult = mysql_query($pswdquery, $dblink) or die ("Unable to select your system variables. Try again later.");
if (mysql_num_rows($pswdresult) == 1)
{
// Check for correct password
$pswdrow = mysql_fetch_row($pswdresult);
if ($_COOKIE['adminpswd'] != $pswdrow[0] OR $_COOKIE['adminusr'] != $Admin_User)
die($err_msg);
}
else
die($err_msg);

// Check for master key
if ($Master_Key == "" OR (isset($_COOKIE['adminmstr']) AND $_COOKIE['adminmstr'] == $Master_Key))
$set_master_key = "yes";
else
$set_master_key = "no";

// Kill page if master is not set and permissions set to no
if ($set_master_key == "no")
{
// Check parents permissions
$prquery = "SELECT COUNT(ID) FROM " .$DB_Prefix ."_permissions WHERE SetPg='$setpg' AND SiteGroup<>'' AND GivePermission='No'";
$prresult = mysql_query($prquery, $dblink) or die ("Unable to select. Try again later.");
$prrow = mysql_fetch_row($prresult);
if ($prrow[0] > 0)
die($mstr_msg);
}

// Check for unlock code
$openset=$pswdrow[1];
$key=strtolower($pswdrow[2]);
$key=str_replace("www.","",$key);
$key=str_replace(".","",$key);
$key=str_replace("/","",$key);
$key="oc_sb$key";
$key=md5($key);
if ($key != $openset AND $setpg != "variables")
die($code_msg);

if ($setmode != "index" AND $setmode != "forgot" AND $stripthisdir != "includes" AND $key == $openset)
{
echo "<a href=\"admin.php\">";
echo "<img border=\"0\" src=\"images/home.gif\" alt=\"Administration Home\" align=\"left\" width=\"16\" height=\"16\" hspace=\"10\" vspace=\"10\"></a>";
echo "<a href=\"includes/help.php?mode=$setmode&submode=$submode\" target=\"_blank\" onClick=\"PopUp=window.open('includes/help.php?mode=$setmode&submode=$submode', 'NewWin', 'resizable=yes,scrollbars=yes,width=400,height=400,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">";
echo "<img border=\"0\" src=\"images/help.gif\" alt=\"Help - $setmode\" align=\"right\" width=\"16\" height=\"16\" hspace=\"10\" vspace=\"10\"></a>";

if ($_COOKIE['adminpswd'] == "827ccb0eea8a706c4c34a16891f84e7b")
echo "<div align=\"center\" class=\"error\"><b>YOU ARE USING THE DEFAULT PASSWORD, WHICH IS NOT SECURE.<br>IT IS HIGHLY RECOMMENDED THAT YOU <a href=\"password.php\">CHANGE</a> YOUR PASSWORD!</b></div>";
}

// Delete backups if they exist
if ($setpg != "backup")
{
if ($dir = @opendir("files")) 
{
while (($file = readdir($dir)) !== false) 
{
if(substr($file, 0, 9) == "db_backup")
{
if (!empty($ftp_site))
{
$ftp_con = @ftp_connect($ftp_site);
$ftp_login = @ftp_login($ftp_con, $ftp_user, $ftp_pass);
$ftppagename = "$ftp_path/files/$file";
$ftp_del_file = @ftp_delete($ftp_con, $ftppagename);
}
else
@unlink ("files/$file");
}
}
closedir($dir);
}
}
?>
