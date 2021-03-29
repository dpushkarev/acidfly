<html>

<head>
    <title>Upload Image</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php
include("open.php");

if ($Submit == "Load Image") {
// Create Javascript
    if ($fieldsname) {
        echo "<script language=javascript>
function setImg()
{
window.opener.document.$formsname.$fieldsname.value = document.ImgForm.nameofimg.value;
window.close();
}
</script>";
    }

// Sets Load Directories
    $varquery = "SELECT ThumbnailDir, LgImageDir, CatImageDir, ImageDir FROM " . $DB_Prefix . "_vars";
    $varresult = mysqli_query($dblink, $varquery) or die ("Unable to get image directories.");
    $varrow = mysqli_fetch_array($varresult);

    if (substr($fieldsname, 0, 7) == "smimage")
        $imagedir = $varrow[ThumbnailDir];
    else if (substr($fieldsname, 0, 7) == "lgimage")
        $imagedir = $varrow[LgImageDir];
    else if ($formsname == "EditCat")
        $imagedir = $varrow[CatImageDir];
    else
        $imagedir = $varrow[ImageDir];
    if ($imagedir == "" OR !$imagedir)
        $imagedir = "images";

// IMAGE ONE
    if ($_FILES['fileone']['tmp_name'] AND $_FILES['fileone']['tmp_name'] != "none") {
        if ($_FILES['fileone']['type'] != "image/gif" AND $_FILES['fileone']['type'] != "image/jpg" AND $_FILES['fileone']['type'] != "image/jpeg" AND $_FILES['fileone']['type'] != "image/pjpeg" AND $_FILES['fileone']['type'] != "image/png" AND $_FILES['fileone']['type'] != "image/x-png") {
            echo "<table border=0 cellpadding=0 cellspacing=0 class=\"smalltable\">";
            echo "<tr>";
            echo "<td align=\"center\">";
            echo "Sorry, but you can only upload images here.<br>";
            echo "Types include GIFs, JPGs and PNGs.";
            echo "Please <a href=\"imgload.php\">try again</a>.";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            die();
        }
        if ($_FILES['fileone']['size'] > "100000") {
            echo "<table border=0 cellpadding=0 cellspacing=0 class=\"smalltable\">";
            echo "<tr>";
            echo "<td align=\"center\">";
            echo "Sorry, but the file you tried to upload is too large.<br>";
            echo "Files must be under 100 Kilobytes in size.";
            echo "Please <a href=\"imgload.php\">try again</a>.";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            die();
        }
        $abs_img_one = $_FILES['fileone']['name'];
        $abs_img_one = preg_match("/[^[:alnum:].-_]/", "", $abs_img_one);
        $abs_img_one = $imagedir . "/" . $abs_img_one;
        $rel_img_one = "../../" . $abs_img_one;
// FTP IMAGE
        if (!empty($ftp_site)) {
            $ftp_con = ftp_connect($ftp_site);
            $ftp_login = ftp_login($ftp_con, $ftp_user, $ftp_pass);
            $ftp_load_file = ftp_put($ftp_con, "$ftp_path/$abs_img_one", $_FILES['fileone']['tmp_name'], FTP_BINARY);
            if ($ftp_con AND $ftp_login AND $ftp_load_file)
                $loadone = "y";
            else
                $imgone_err = "Could not upload first image";
            @ftp_close($ftp_con);
        } // LOAD VIA PHP
        else {
            if (move_uploaded_file($_FILES['fileone']['tmp_name'], $rel_img_one))
                $loadone = "y";
            else
                $imgone_err = "Could not upload first image";
        }
    }

    if ($_FILES['filetwo']['tmp_name'] AND $_FILES['filetwo']['tmp_name'] != "none") {
        if ($_FILES['filetwo']['type'] != "image/gif" AND $_FILES['filetwo']['type'] != "image/jpg" AND $_FILES['filetwo']['type'] != "image/jpeg" AND $_FILES['filetwo']['type'] != "image/pjpeg" AND $_FILES['filetwo']['type'] != "image/png" AND $_FILES['filetwo']['type'] != "image/x-png") {
            echo "<table border=0 cellpadding=0 cellspacing=0 class=\"smalltable\">";
            echo "<tr>";
            echo "<td align=\"center\">";
            echo "Sorry, but you can only upload images here.<br>";
            echo "Types include GIFs, JPGs and PNGs.";
            echo "Please <a href=\"imgload.php\">try again</a>.";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            die();
        }
        if ($_FILES['filetwo']['size'] > "100000") {
            echo "<table border=0 cellpadding=0 cellspacing=0 class=\"smalltable\">";
            echo "<tr>";
            echo "<td align=\"center\">";
            echo "Sorry, but the file you tried to upload is too large.<br>";
            echo "Files must be under 100 Kilobytes in size.";
            echo "Please <a href=\"imgload.php\">try again</a>.";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            die();
        }
        $abs_img_two = $_FILES['filetwo']['name'];
        $abs_img_two = preg_replace("/[^[:alnum:].-_]/", "", $abs_img_two);
        $abs_img_two = $imagedir . "/" . $abs_img_two;
        $rel_img_two = "../../" . $abs_img_two;
        if (!empty($ftp_site)) {
            $ftp_con = ftp_connect($ftp_site);
            $ftp_login = ftp_login($ftp_con, $ftp_user, $ftp_pass);
            $ftp_load_file = ftp_put($ftp_con, "$ftp_path/$abs_img_two", $_FILES['filetwo']['tmp_name'], FTP_BINARY);
            if ($ftp_con AND $ftp_login AND $ftp_load_file)
                $loadtwo = "y";
            else
                $imgtwo_err = "Could not upload second image";
            @ftp_close($ftp_con);
        } else {
            if (move_uploaded_file($_FILES['filetwo']['tmp_name'], $rel_img_two))
                $loadtwo = "y";
            else
                $imgtwo_err = "Could not upload second image";
        }
    }

    echo "<form name=\"ImgForm\">";
    echo "<table border=0 cellpadding=0 cellspacing=0 class=\"smalltable\">";
    echo "<tr>";
    echo "<td align=\"center\">";
    if ($imgone_err)
        echo $imgone_err;
    else if ($imgtwo_err)
        echo $imgtwo_err;
    if (file_exists($rel_img_one) AND $loadone == "y" AND file_exists($rel_img_two) AND $loadtwo == "y")
        echo "<p>The file $abs_img_one and its mouseover $abs_img_two have been uploaded.</p>";
    else if (file_exists($rel_img_one) AND $loadone == "y")
        echo "<p>The file $abs_img_one has been uploaded.</p>";
    else if (file_exists($rel_img_two) AND $loadtwo == "y")
        echo "<p>The file $abs_img_two has been uploaded.</p>";
    if ($fieldsname AND ($loadone == "y" OR $loadtwo == "y")) {
        if ($abs_img_one AND $abs_img_two)
            $image_name = "$abs_img_one~$abs_img_two";
        else if ($abs_img_one)
            $image_name = $abs_img_one;
        else
            $image_name = $abs_img_two;
        echo "<input type=\"hidden\" name=\"nameofimg\" value=\"$image_name\">";
        echo "<input type=\"button\" class=\"button\" value=\"Add To Form\" onClick=\"setImg();\">";
    }

    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</form>";
} else {
    ?>
    <form enctype="multipart/form-data" action="imgload.php" method="post">
        <div align="center">
            <center>
                <table border=0 cellpadding=0 cellspacing=0 class="smalltable">
                    <tr>
                        <td width="100%" align="center">Click <b>Browse</b> to find an image on your computer.</td>
                    </tr>
                    <tr>
                        <td width="100%" align="center"><input name="fileone" type="file"
                                                               accept="image/gif, image/jpg, image/jpeg, image/png">
                        </td>
                    </tr>
                    <?php
                    if ($mo == "y") {
                        ?>
                        <tr>
                            <td width="100%" align="center" class="smalltext">
                                Optional - add a mouseover image:<br>
                                <input name="filetwo" type="file" accept="image/gif, image/jpg, image/jpeg, image/png">
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td width="100%" align="center">Then click <b>Load Image</b> to upload the image.<br>
                            Your system may take a few minutes to upload.<br>
                            If the image already exists, it will be overwritten!
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" align="center">
                            <input type="hidden" name="formsname" value="<?php echo "$formsname"; ?>">
                            <input type="hidden" name="fieldsname" value="<?php echo "$fieldsname"; ?>">
                            <input type="hidden" name="mode" value="<?php echo "$mode"; ?>">
                            <input type="submit" class="button" name="Submit" value="Load Image"></td>
                    </tr>
                </table>
            </center>
        </div>
    </form>
    <?php
}

echo "<p align=\"center\"><a href=\"javascript:window.close();\">Close This Window</a></p>";
?>
</body>

</html>
