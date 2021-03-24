<!DOCTYPE HTML PUBLIC "-//W3C//DTD W3 HTML 3.2//EN">
<HTML  id=dlgImage STYLE="width: 430px; height: 210px; ">
<HEAD>
<TITLE>Insert Image</TITLE>
</HEAD>
<BODY style="background: threedface; color: windowtext;" scroll=no>
<script language="php">
require_once("../../../stconfig.php");
$pswdquery = "SELECT AdminPass, OpenSet, URL FROM " .$DB_Prefix ."_vars WHERE ID=1";
$pswdresult = mysqli_query($dblink, $pswdquery) or die ("Unable to select your system variables. Try again later.");
$pswdnum = mysqli_num_rows($pswdresult);
if ($pswdnum == 1)
{
// Check for correct password
$pswdrow = mysqli_fetch_row($pswdresult);
if ($_COOKIE['adminpswd'] != $pswdrow[0] OR $_COOKIE['adminusr'] != $Admin_User)
die("File Not Found");
}

if ($_POST[submitmode] == "Upload")
{
// Check to see if the file exists and is right type and size
if ($_FILES['uplImage'] AND $_FILES['uplImage']['tmp_name'] != "none")
{
if ($_FILES['uplImage']['type'] != "image/gif" AND $_FILES['uplImage']['type'] != "image/jpg" AND $_FILES['uplImage']['type'] != "image/jpeg" AND $_FILES['uplImage']['type'] != "image/pjpeg" AND $_FILES['uplImage']['type'] != "image/png")
echo $_FILES['uplImage']['type'] ." is Wrong type";
else
{
if ($_FILES['uplImage']['size'] > "999999")
echo $_FILES['uplImage']['sizee'] ." to large, resize";
else
{
$abs_img = $_FILES['uplImage']['name'];
$abs_img = ereg_replace("[^[:alnum:].]", "", $abs_img);
$rel_img = "../../../$img_dir/" .$abs_img;
if (file_exists($rel_img))
$uploadmsg1 = "has been uploaded.";
else
$uploadmsg1 = "has been added.";

if (!empty($ftp_site))
{
$ftp_con = ftp_connect($ftp_site);
$ftp_login = ftp_login($ftp_con, $ftp_user, $ftp_pass);
$ftp_load_file = ftp_put($ftp_con, "$ftp_path/$img_dir/$abs_img", $_FILES['uplImage']['tmp_name'], FTP_BINARY);
if ($ftp_con AND $ftp_login AND $ftp_load_file)
$upl_Image = $abs_img;
else
echo "Could not upload";
@ftp_close($ftp_con);
}
else
{
if (move_uploaded_file($_FILES['uplImage']['tmp_name'], $rel_img))
{
$upl_Image = $abs_img;
}
else
echo "Could not upload";
}
}
}
}
}
echo "<iframe src=\"insert_image_inc.php?uplImage=$upl_Image\" width=\"500\" height=\"500\" frameborder=\"0\" scroll=\"auto\">";
echo "</iframe>";
</script>

</BODY>
</HTML>
