<?php
// ONLY ADJUST THE INFORMATION WITHIN QUOTES BELOW:
$Host_Name = getenv('DB_HOST'); // The name of your web server database host
$DB_Name = "acidfly_database2"; // The name of your database
$DB_User_Name = "root"; // The name of your database user
$DB_Password = "root"; // The name of your database password
$DB_Prefix = "opc"; // The prefix of your database tables (ie. opc_)
$Home_Path = "/Users/denis.pushkarev/freelance/projects/php/acidfly"; // Your sites home path with no trailing slash
$Admin_User = "admin"; // Your store administration user name
$Master_Key = ""; // Your global administrator password if permissions will be set
$Inc_Dir = "enc"; // The directory in which your PHP site include files reside
$Adm_Dir = "storeadmin"; // The directory in which your PHP store administration area resides
$ftp_site = ""; // FTP server URL (ie. yoursite.com)
$ftp_path = ""; // FTP path, no trailing slash (ie. public_html)
$ftp_user = ""; // FTP user name
$ftp_pass = ""; // FTP password
$chmod_update = "0777"; // Permission for upload - change this to 0755 for phpSuExec
$chmod_folder = "0755"; // Permission value of a folder after update
$chmod_file = "0644"; // Permission value of a file after update
// DO NOT MODIFY PAST THIS LINE!

$die_if_words = array("<?", "?>", "<script", "</script>", "javascript:", "document.location", "%0a", "%0d", "%Oa", "%oa", "%OD", "%od", "Content-Type:", "Reply-To:");
$remove_if_words = array("to:", "cc:", "|", ")", "(", ";");
$curdr = strtolower(str_replace("/", "", dirname($_SERVER[PHP_SELF])));
if (empty($curdr) OR substr($curdr, -5) == "pages" OR substr($curdr, -2) == "go") {
    while (list($fieldname, $fieldvalue) = each($_REQUEST)) {
        for ($v = 0; $v < count($die_if_words); ++$v) {
            if (substr_count($fieldvalue, $die_if_words[$v]) > 0)
                die("Invalid Input - Script Characters Not Allowable Inputs");
        }
    }
}

function stripbadstuff($frmvalue)
{
    $frmvalue = strip_tags($frmvalue);
    global $remove_if_words;
    for ($b = 0; $b < count($remove_if_words); ++$b) {
        $frmvalue = str_replace(trim($remove_if_words[$b]), "", $frmvalue);
    }
    return $frmvalue;
}

if (isset($_REQUEST['Home_Path']))
    die("Home Path Not Found");
if (isset($_REQUEST['Inc_Dir']))
    die("Inc Dir Not Found");
if (isset($_REQUEST['Adm_Dir']))
    die("Admin Dir Not Found ");
error_reporting(E_ALL ^ E_NOTICE);

$dblink = mysqli_connect($Host_Name, $DB_User_Name, $DB_Password) OR DIE("Unable to connect to database");
mysqli_select_db($dblink, $DB_Name) or die("Unable to select database");

// Get variables
if (!isset($show_setup_config)) {
    $urlquery = "SELECT URL, PageExt FROM " . $DB_Prefix . "_vars";
    $urlresult = mysqli_query($dblink, $urlquery) or die ("Unable to select vars. Try again later.");
    $urlrow = mysqli_fetch_row($urlresult);
    $urlbase = $urlrow[0];
    $urldir = "http://" . $urlbase;
    if ($urlrow[1])
        $pageext = $urlrow[1];
    else
        $pageext = "php";
}

extract($_POST);
extract($_GET);

if (isset($_REQUEST['Home_Path']))
    die("Home Path Not Found");
if (isset($_REQUEST['Inc_Dir']))
    die("Inc Dir Not Found");
if (isset($_REQUEST['Adm_Dir']))
    die("Admin Dir Not Found ");

if (!function_exists('addslash_mq')) {
    function addslash_mq($value)
    {
        if (!get_magic_quotes_gpc())
            return addslashes($value);
        else
            return $value;
    }
}
?>