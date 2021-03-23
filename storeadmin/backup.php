<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

$thisdate = date("mdy", mktime (0,0,0,date("m"),date("d"),date("Y")));

if ($submit == "Create Database Backup")
{
$showfinishedtables = "";
$backupall = "yes";
$tablequery = "SHOW TABLES FROM " .$DB_Name;
$tableresult = mysql_query($tablequery, $dblink) or die ("Unable to select. Try again later.");
$dateformat = date("l, F j, Y g:i a", mktime (0,0,0,date("m"),date("d"),date("Y")));
$snquery = "SELECT SiteName FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$snresult = mysql_query($snquery, $dblink) or die ("Unable to select. Try again later.");
$snrow = mysql_fetch_row($snresult);
$sitename = $snrow[0];
$sql_data = "# $sitename Database Backup\r\n";
$sql_data .= "# Database: $DB_Name\r\n";
$sql_data .= "# $dateformat\r\n";
$sql_data .= "\r\n\r\n";
while ($tablerow = mysql_fetch_row($tableresult))
{
// Display table in the backup?
if ($tablelist AND !in_array($tablerow[0], $tablelist))
{
$showfinishedtables .= "<span class=\"lightcolortext\">$tablerow[0] Not Backed Up</span><br>";
$backupall = "no";
}
else
{
$showfinishedtables .= "<span class=\"linecolor\">$tablerow[0] Backed Up</span><br>";
$sql_data .= "# --------------------------------\r\n\r\n";
$sql_data .= "# TABLE - " .strtoupper($tablerow[0]) ."\r\n\r\n";
$fields = mysql_list_fields("$DB_Name", "$tablerow[0]", $dblink);
$columns = mysql_num_fields($fields);
$showquery = "SHOW COLUMNS FROM $tablerow[0]";
$showresult = mysql_query($showquery, $dblink) or die ("Unable to select. Try again later.");
$shownum = mysql_num_rows($showresult);
// Show create tables?
if (!$dataonly OR $dataonly != "yes")
{
$sql_data .= "CREATE TABLE $tablerow[0] (\r\n";
while ($showrow = mysql_fetch_array($showresult))
{
// Show column name and type
$sql_data .= "$showrow[0] $showrow[1]";
// Is Null?
if ($showrow[2] == "YES")
$sql_data .= " NULL";
else
$sql_data .= " NOT NULL";
// Default?
if (!empty($showrow[4]))
$sql_data .= " default '$showrow[4]'";
// Auto increment?
if (!empty($showrow[5]))
$sql_data .= " $showrow[5]";
$sql_data .= ", \r\n";
}
$sql_data .= "PRIMARY KEY  (ID), \r\n";
$sql_data .= "UNIQUE KEY ID (ID), \r\n";
$sql_data .= "KEY ID_2 (ID)\r\n";
$sql_data .= ");\r\n\r\n";
}

// Go through records and create insert tables
$recquery = "SELECT * FROM $tablerow[0]";
$recresult = mysql_query($recquery, $dblink) or die ("Unable to select. Try again later.");
while ($recrow = mysql_fetch_row($recresult))
{
$sql_data .= "INSERT INTO $tablerow[0] VALUES (";
for ($rec=0; $rec < $shownum; ++$rec)
{
$record = stripslashes($recrow[$rec]);
$record = str_replace("\r\n", '\r\n', $record);
$record = str_replace("'", "''", $record);
if ($rec == 0)
$sql_data .= $record;
else
$sql_data .= ", '" .$record ."'";
}
$sql_data .= ");\r\n";
}
$sql_data .= "\r\n";
}
}

$sql_path = "$Home_Path/$Adm_Dir/files";
$sql_file = "$sql_path/db_backup_$thisdate.sql";
if (!empty($ftp_site))
{
$fsql_path = "$ftp_path/$Adm_Dir/files";
$conn_id = @ftp_connect($ftp_site);
$login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);
@ftp_site($conn_id, "CHMOD " .$chmod_update ." " .$fsql_path);
}
$handle = @fopen($sql_file, "w+");
if (!$handle)
die ("Could not create backup file");
@fwrite($handle, "$sql_data");
@fclose($handle);
if (!empty($ftp_site))
{
@ftp_site($conn_id, "CHMOD " .$chmod_folder ." " .$fsql_path);
@ftp_close($conn_id);
}

$backup_message = " - COMPLETED";
$this_date_backup = "db_backup_$thisdate.sql";

$show_new_backup = "<p align=\"center\">";
$show_new_backup .= "<a href=\"files/$this_date_backup\" target=\"_blank\">";
$show_new_backup .= "$this_date_backup</a></p>";
}
?>

<form action="backup.php" method="post">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">
<?php echo "Instant Database Backup$backup_message"; ?>
</td>
</tr>
<tr>
<td valign="middle" align="left">
<?php
if ($backup_message == " - COMPLETED")
{
echo "<p>Your database has been backed up. Please click the link below to save the file ";
echo "on your computer. On some Windows systems, you may have to right mouse click and select ";
echo "\"Save Target As...\" to save the file. Please note that this backup will be deleted as soon as ";
echo "you are finished with the backup, so please be sure to save the backup to your computer, ";
echo "or you will not be protected in case of a server error.</p>";
echo "$show_new_backup";
if ($backupall == "no")
echo "<p align=\"center\">Backup Table Log:<br>$showfinishedtables</p>";
}
else
{
echo "<p>Click the backup button below to back up your shop database information and page content. ";
echo "Note that this will back up only the database, and will not back up any other files, including ";
echo "additional pages, systems you've installed, web page templates, emails, or other server data.</p>";
echo "<p>It is recommended that you back up your database before and after making many changes to your ";
echo "pages or catalog, and keep a current copy both on your computer's hard drive and on a backup disk.</p>";
if ($set_master_key == "yes")
{
echo "<p align=\"center\">Select the table(s) you wish to back up:<br>";
echo "<select name=\"tablelist[]\" multiple size=\"5\">";
$tablequery = "SHOW TABLES FROM " .$DB_Name;
$tableresult = mysql_query($tablequery, $dblink) or die ("Unable to select. Try again later.");
while ($tablerow = mysql_fetch_row($tableresult))
{
echo "<option value=\"$tablerow[0]\" selected>$tablerow[0]</option>";
}
echo "</select><br>";
echo "<input type=\"checkbox\" name=\"dataonly\" value=\"yes\"> Show Data Only (No Table Structure)";
echo "</p>";
}
echo "<p align=\"center\">";
echo "<input type=\"submit\" name=\"submit\" value=\"Create Database Backup\" class=\"button\"></p>";
}
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
