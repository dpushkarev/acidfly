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

$showquery = "SELECT SubGroup, GivePermission FROM " . $DB_Prefix . "_permissions WHERE SetPg='$setpg' AND SubGroup<>''";
$showresult = mysqli_query($dblink, $showquery) or die ("Unable to select. Try again later.");
while ($showrow = mysqli_fetch_row($showresult)) {
    if ($showrow[0] == "general")
        $show_general = $showrow[1];
    if ($showrow[0] == "catalog")
        $show_catalog = $showrow[1];
}

if ($show_general == "No" AND $show_catalog == "No" AND $set_master_key == "no") {
    ?>
    <table border="0" cellpadding="7" cellspacing="0" align="center" class="generaltable">
        <tr>
            <td align="center" class="fieldname" colspan="2">Image Administration</td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                Sorry, but the image administration area is not currently available.
                Please ask your system administrator for assistance.
            </td>
        </tr>
    </table>
    <?php
}

$varquery = "SELECT ThumbnailDir, LgImageDir, CatImageDir, ImageDir FROM " . $DB_Prefix . "_vars WHERE ID=1";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($varresult) == 1) {
    $varrow = mysqli_fetch_row($varresult);
    $thumbdir = $varrow[0];
    $lgimgdir = $varrow[1];
    $catimgdir = $varrow[2];
    $imgdir = $varrow[3];
}

// UPDATE CATALOG IMAGES
if ($fcn == "update" AND $catfcn == "save") {
    $updquery = "UPDATE " . $DB_Prefix . "_vars SET OrderButton='$orderbutton', SearchButton='$searchbutton', ";
    $updquery .= "ViewCartButton='$viewcartbutton', RegistryButton='$registrybutton', ";
    $updquery .= "NewHeader='$newheader', FeaturedHeader='$featuredheader', ";
    $updquery .= "SaleHeader='$saleheader', AllHeader='$allheader', NewNavImg='$newnavimg', ";
    $updquery .= "FeaturedNavImg='$featurednavimg', SaleNavImg='$salenavimg', AllNavImg='$allnavimg', ";
    $updquery .= "LogoURL='$logourl' WHERE ID=1";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}

// DELETE IMAGE
if ($imagedir AND $imgname AND $fcn == "Yes") {
    if (!empty($ftp_site)) {
        $ftp_con = ftp_connect($ftp_site);
        $ftp_login = ftp_login($ftp_con, $ftp_user, $ftp_pass);
        $ftp_del_file = ftp_delete($ftp_con, "$ftp_path/$imgname");
        if (!$ftp_con OR !$ftp_login OR !$ftp_del_file)
            die("<p align=\"center\">Cannot delete image. Please <a href=\"images.php?imagedir=$imagedir\">try again</a>.</p>");
        @ftp_close($ftp_con);
    } else {
        $fullimg = "$Home_Path/$imgname";
        @unlink("$fullimg") or die("<p align=\"center\">Cannot delete image. Please <a href=\"images.php?imagedir=$imagedir\">try again</a>.</p>");
    }

    if ($imagedir == "catimgdir") {
// Look through category navigation images
        $upd1query = "UPDATE " . $DB_Prefix . "_categories SET Image='' WHERE Image='$imgname'";
        $upd1result = mysqli_query($dblink, $upd1query) or die("Unable to update. Please try again later.");
// Look through category header images
        $upd2query = "UPDATE " . $DB_Prefix . "_categories SET HeaderImage='' WHERE HeaderImage='$imgname'";
        $upd2result = mysqli_query($dblink, $upd2query) or die("Unable to update. Please try again later.");
// Look through category list images
        $upd3query = "UPDATE " . $DB_Prefix . "_categories SET ListImage='' WHERE ListImage='$imgname'";
        $upd3result = mysqli_query($dblink, $upd3query) or die("Unable to update. Please try again later.");
    } else if ($imagedir == "thumbdir") {
// Look through items for thumbnail images
        $updquery = "UPDATE " . $DB_Prefix . "_items SET SmImage='' WHERE SmImage='$imgname'";
        $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
    } else if ($imagedir == "lgimgdir") {
// Look through items for large images
        $updquery = "UPDATE " . $DB_Prefix . "_items SET LgImage='' WHERE LgImage='$imgname'";
        $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
    } else {
// Look through variables for images
        $upd1query = "UPDATE " . $DB_Prefix . "_vars SET OrderButton='' WHERE OrderButton='$imgname'";
        $upd1result = mysqli_query($dblink, $upd1query) or die("Unable to update order button. Please try again later.");
// Look through variables for images
        $upd2query = "UPDATE " . $DB_Prefix . "_vars SET SearchButton='' WHERE SearchButton='$imgname'";
        $upd2result = mysqli_query($dblink, $upd2query) or die("Unable to update search button. Please try again later.");
// Look through variables for images
        $upd3query = "UPDATE " . $DB_Prefix . "_vars SET ViewCartButton='' WHERE ViewCartButton='$imgname'";
        $upd3result = mysqli_query($dblink, $upd3query) or die("Unable to update cart button. Please try again later.");
// Look through variables for images
        $upd4query = "UPDATE " . $DB_Prefix . "_vars SET RegistryButton='' WHERE RegistryButton='$imgname'";
        $upd4result = mysqli_query($dblink, $upd4query) or die("Unable to update cart button. Please try again later.");
// Look through variables for images
        $upd5query = "UPDATE " . $DB_Prefix . "_vars SET NewHeader='' WHERE NewHeader='$imgname'";
        $upd5result = mysqli_query($dblink, $upd5query) or die("Unable to update new header. Please try again later.");
// Look through variables for images
        $upd6query = "UPDATE " . $DB_Prefix . "_vars SET FeaturedHeader='' WHERE FeaturedHeader='$imgname'";
        $upd6result = mysqli_query($dblink, $upd6query) or die("Unable to update feature header. Please try again later.");
// Look through variables for images
        $upd7query = "UPDATE " . $DB_Prefix . "_vars SET AllHeader='' WHERE AllHeader='$imgname'";
        $upd7result = mysqli_query($dblink, $upd7query) or die("Unable to update all header. Please try again later.");
// Look through variables for images
        $upd8query = "UPDATE " . $DB_Prefix . "_vars SET SaleHeader='' WHERE SaleHeader='$imgname'";
        $upd8result = mysqli_query($dblink, $upd8query) or die("Unable to update sale header. Please try again later.");
// Look through variables for images
        $upd9query = "UPDATE " . $DB_Prefix . "_vars SET NewNavImg='' WHERE NewNavImg='$imgname'";
        $upd9result = mysqli_query($dblink, $upd9query) or die("Unable to update new nav image. Please try again later.");
// Look through variables for images
        $upd10query = "UPDATE " . $DB_Prefix . "_vars SET FeaturedNavImg='' WHERE FeaturedNavImg='$imgname'";
        $upd10result = mysqli_query($dblink, $upd10query) or die("Unable to update feature nav image. Please try again later.");
// Look through variables for images
        $upd11query = "UPDATE " . $DB_Prefix . "_vars SET AllNavImg='' WHERE AllNavImg='$imgname'";
        $upd11result = mysqli_query($dblink, $upd11query) or die("Unable to update all nav image. Please try again later.");
// Look through variables for images
        $upd12query = "UPDATE " . $DB_Prefix . "_vars SET SaleNavImg='' WHERE SaleNavImg='$imgname'";
        $upd12result = mysqli_query($dblink, $upd12query) or die("Unable to update sale nav image. Please try again later.");
// Look through page headers for images
        $upd13query = "UPDATE " . $DB_Prefix . "_pages SET HeaderImage='' WHERE HeaderImage='$imgname'";
        $upd13result = mysqli_query($dblink, $upd13query) or die("Unable to update page headers. Please try again later.");
// Look through page navigation for images
        $upd14query = "UPDATE " . $DB_Prefix . "_pages SET NavImage='' WHERE NavImage='$imgname'";
        $upd14result = mysqli_query($dblink, $upd14query) or die("Unable to update page nav images. Please try again later.");
// Look through links for images
        $upd15query = "UPDATE " . $DB_Prefix . "_links SET Image='' WHERE Image='$imgname'";
        $upd15result = mysqli_query($dblink, $upd15query) or die("Unable to update link images. Please try again later.");
// Look through variables for images
        $upd16query = "UPDATE " . $DB_Prefix . "_vars SET LogoURL='' WHERE LogoURL='$imgname'";
        $upd16result = mysqli_query($dblink, $upd16query) or die("Unable to update sale nav image. Please try again later.");
    }
}

// ADD IMAGE
if ($imagedir AND $_FILES['addimg'] AND $fcn == "add") {
    if ($imagedir == "thumbdir")
        $absdir = $thumbdir;
    if ($imagedir == "lgimgdir")
        $absdir = $lgimgdir;
    if ($imagedir == "catimgdir")
        $absdir = $catimgdir;
    if ($imagedir == "imgdir")
        $absdir = $imgdir;

    if (($_FILES['addimg']['type'] != "image/gif" AND $_FILES['addimg']['type'] != "image/jpg" AND $_FILES['addimg']['type'] != "image/jpeg" AND $_FILES['addimg']['type'] != "image/pjpeg" AND $_FILES['addimg']['type'] != "image/png" AND $_FILES['addimg']['type'] != "image/x-png") OR $_FILES['addimg']['size'] > "100000")
        $uploadmsg = "The file $abs_img could not be uploaded. Please make sure that the file is an image and that it is under 100 KB in size.";
    else {
        $abs_img = $_FILES['addimg']['name'];
        $abs_img = ereg_replace("[^[:alnum:].-_]", "", $abs_img);
        $abs_img = $absdir . "/" . $abs_img;
        $rel_img = "../" . $abs_img;

        if (!empty($ftp_site)) {
            $ftp_con = ftp_connect($ftp_site);
            $ftp_login = ftp_login($ftp_con, $ftp_user, $ftp_pass);
            $ftp_load_file = ftp_put($ftp_con, "$ftp_path/$abs_img", $_FILES['addimg']['tmp_name'], FTP_BINARY);
            if ($ftp_con AND $ftp_login AND $ftp_load_file) {
                if (file_exists($rel_img))
                    $uploadmsg = "The file $abs_img has been uploaded, and the new file has replaced the existing file.";
                else
                    $uploadmsg = "The file $abs_img has been added.";
            } else
                $uploadmsg = "The file $abs_img could not be uploaded.";
            @ftp_close($ftp_con);
        } else {
            if (move_uploaded_file($_FILES['addimg']['tmp_name'], $rel_img)) {
                if (file_exists($rel_img))
                    $uploadmsg = "The file $abs_img has been uploaded, and the new file has replaced the existing file.";
                else
                    $uploadmsg = "The file $abs_img has been added.";
            } else
                $uploadmsg = "The file $abs_img could not be uploaded.";
        }
    }
}

// DO YOU WANT TO DELETE IMAGE?
if ($imagedir AND $imgname AND $fcn == "del") {
    ?>

    <form method="POST" action="images.php">
        <div align="center">
            <center>
                <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                    <tr>
                        <td valign="middle" align="center" class="fieldname">
                            Delete Image
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center">
                            You are about to permanently delete the following image:
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center" class="highlighttext">
                            <?php
                            echo "$imgname";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle">
                            Deleting this image will also remove that image from any fields in the database. It may not,
                            however,
                            remove the references from any HTML editing, and so image breaks may appear on your site.
                            Do you want to continue?
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center">
                            <?php
                            echo "<input type=\"hidden\" name=\"imagedir\" value=\"$imagedir\">";
                            echo "<input type=\"hidden\" name=\"imgname\" value=\"$imgname\">";
                            ?>
                            <input type="submit" class="button" value="Yes" name="fcn">
                            <input type="submit" class="button" value="No" name="fcn">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
} // VIEW ALL IMAGES IN THIS DIRECTORY
else if ($imagedir) {
    ?>

    <div align="center">
        <center>
            <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                <tr>
                    <td valign="middle" align="center" class="fieldname" colspan="3">
                        <?php
                        if ($imagedir == "thumbdir") {
                            echo "Thumbnail Image Directory";
                            $dirname = $thumbdir;
                        }
                        if ($imagedir == "lgimgdir") {
                            echo "Large Image Directory";
                            $dirname = $lgimgdir;
                        }
                        if ($imagedir == "catimgdir") {
                            echo "Category Image Directory";
                            $dirname = $catimgdir;
                        }
                        if ($imagedir == "imgdir") {
                            echo "General Image Directory";
                            $dirname = $imgdir;
                        }
                        $directoryname = "../" . $dirname;
                        ?>
                    </td>
                </tr>
                <?php
                // If uploaded message exists, display it here.
                if ($uploadmsg)
                    echo "<tr><td align=\"center\" colspan=\"3\">$uploadmsg</td></tr>";

                if ($dir = @opendir("$directoryname")) {
                    while (($file = readdir($dir)) !== false) {
// if($file != ".." && $file != "." && $file != "_vti_cnf" && substr($file, -3) == )

                        $filecheck = strtolower(substr($file, -4));
                        if ($filecheck == ".gif" OR $filecheck == ".jpg" OR $filecheck == "jpeg" OR $filecheck == ".png") {
                            $filelist[] = $file;
                        }
                    }
                    closedir($dir);
                }

                if (!$filelist)
                    echo "<tr><td align=\"center\" colspan=\"3\"><i>No Images Found</i></td></tr>";
                else {
                    asort($filelist);
// while (list ($key, $val) = each ($filelist)) 
                    for ($fl = 1; list ($key, $val) = each($filelist); ++$fl) {
                        if (($fl + 1) % 2 == 0)
                            echo "<tr>";
                        echo "<td align=\"left\">";
                        echo "<a href=\"$directoryname/$val\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">$val</a> &nbsp; <a href=\"images.php?imagedir=$imagedir&imgname=$dirname/$val&fcn=del\">DEL</a>";
                        echo "</td>";
                        if ($fl % 2 == 0)
                            echo "</tr>";
                    }
                }
                ?>
                <tr>
                    <td align="center" colspan="3">
                        <form enctype="multipart/form-data" action="images.php" method="post">
                            <?php
                            echo "<input type=\"hidden\" name=\"imagedir\" value=\"$imagedir\">";
                            ?>
                            <input type="hidden" name="fcn" value="add">
                            Add New Image: <input name="addimg" type="file"
                                                  accept="image/gif, image/jpg, image/jpeg, image/png" size="20"> <input
                                    type="submit" class="button" value="Add" name="submit">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="3">
                        <a href="images.php">Back to Images</a>
                    </td>
                </tr>
            </table>
        </center>
    </div>

    <?php
} // SHOW CATALOG IMAGES
else if ($fcn == "update") {
    $varquery = "SELECT OrderButton, SearchButton, ViewCartButton, RegistryButton, URL, ";
    $varquery .= "NewHeader, FeaturedHeader, SaleHeader, AllHeader, NewNavImg, LogoURL, ";
    $varquery .= "FeaturedNavImg, SaleNavImg, AllNavImg FROM " . $DB_Prefix . "_vars WHERE ID=1";
    $varresult = mysqli_query($dblink, $varquery) or die ("Unable to select. Try again later.");
    if (mysqli_num_rows($varresult) == 1) {
        $varrow = mysqli_fetch_array($varresult);
        $OrderButton = $varrow[OrderButton];
        if (substr($OrderButton, 0, 7) == "http://")
            $OrderButtonPopup = $OrderButton;
        else
            $OrderButtonPopup = "http://$varrow[URL]/" . $OrderButton;
        $SearchButton = $varrow[SearchButton];
        if (substr($SearchButton, 0, 7) == "http://")
            $SearchButtonPopup = $SearchButton;
        else
            $SearchButtonPopup = "http://$varrow[URL]/" . $SearchButton;
        $ViewCartButton = $varrow[ViewCartButton];
        if (substr($ViewCartButton, 0, 7) == "http://")
            $ViewCartButtonPopup = $ViewCartButton;
        else
            $ViewCartButtonPopup = "http://$varrow[URL]/" . $ViewCartButton;
        $RegistryButton = $varrow[RegistryButton];
        if (substr($RegistryButton, 0, 7) == "http://")
            $RegistryButtonPopup = $RegistryButton;
        else
            $RegistryButtonPopup = "http://$varrow[URL]/" . $RegistryButton;
        $NewHeader = $varrow[NewHeader];
        if (substr($NewHeader, 0, 7) == "http://")
            $NewHeaderPopup = $NewHeader;
        else
            $NewHeaderPopup = "http://$varrow[URL]/" . $NewHeader;
        $FeaturedHeader = $varrow[FeaturedHeader];
        if (substr($FeaturedHeader, 0, 7) == "http://")
            $FeaturedHeaderPopup = $FeaturedHeader;
        else
            $FeaturedHeaderPopup = "http://$varrow[URL]/" . $FeaturedHeader;
        $SaleHeader = $varrow[SaleHeader];
        if (substr($SaleHeader, 0, 7) == "http://")
            $SaleHeaderPopup = $SaleHeader;
        else
            $SaleHeaderPopup = "http://$varrow[URL]/" . $SaleHeader;
        $AllHeader = $varrow[AllHeader];
        if (substr($AllHeader, 0, 7) == "http://")
            $AllHeaderPopup = $AllHeader;
        else
            $AllHeaderPopup = "http://$varrow[URL]/" . $AllHeader;
        $NewNavImg = $varrow[NewNavImg];
        if (substr($NewNavImg, 0, 7) == "http://")
            $NewNavImgPopup = $NewNavImg;
        else
            $NewNavImgPopup = "http://$varrow[URL]/" . $NewNavImg;
        $FeaturedNavImg = $varrow[FeaturedNavImg];
        if (substr($FeaturedNavImg, 0, 7) == "http://")
            $FeaturedNavImgPopup = $FeaturedNavImg;
        else
            $FeaturedNavImgPopup = "http://$varrow[URL]/" . $FeaturedNavImg;
        $SaleNavImg = $varrow[SaleNavImg];
        if (substr($SaleNavImg, 0, 7) == "http://")
            $SaleNavImgPopup = $SaleNavImg;
        else
            $SaleNavImgPopup = "http://$varrow[URL]/" . $SaleNavImg;
        $AllNavImg = $varrow[AllNavImg];
        if (substr($AllNavImg, 0, 7) == "http://")
            $AllNavImgPopup = $AllNavImg;
        else
            $AllNavImgPopup = "http://$varrow[URL]/" . $AllNavImg;
        $LogoURL = $varrow[LogoURL];
        if (substr($LogoURL, 0, 7) == "http://")
            $LogoURLPopup = $LogoURL;
        else
            $LogoURLPopup = "http://$varrow[URL]/" . $LogoURL;
    }
    ?>

    <form action="images.php" method="post" name="CatImg">
        <div align="center">
            <center>
                <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                    <tr>
                        <td valign="middle" align="center" class="fieldname" colspan="2">Catalog Images</td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$LogoURLPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Logo</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="logourl" value="<?php echo "$LogoURL"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=logourl" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=logourl', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$OrderButtonPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Order Button</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="orderbutton" value="<?php echo "$OrderButton"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=orderbutton" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=orderbutton', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$SearchButtonPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Search Button</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="searchbutton" value="<?php echo "$SearchButton"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=searchbutton" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=searchbutton', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$ViewCartButtonPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">View Cart Button</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="viewcartbutton" value="<?php echo "$ViewCartButton"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=viewcartbutton" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=viewcartbutton', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$RegistryButtonPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Registry Button</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="registrybutton" value="<?php echo "$RegistryButton"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=registrybutton" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=registrybutton', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$NewHeaderPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">New Header Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="newheader" value="<?php echo "$NewHeader"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=newheader" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=newheader', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$FeaturedHeaderPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Featured Header Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="featuredheader" value="<?php echo "$FeaturedHeader"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=featuredheader" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=featuredheader', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$SaleHeaderPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Sale Header Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="saleheader" value="<?php echo "$SaleHeader"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=saleheader" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=saleheader', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$AllHeaderPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">All Header Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="allheader" value="<?php echo "$AllHeader"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=allheader" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=allheader', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$NewNavImgPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">New Navigation Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="newnavimg" value="<?php echo "$NewNavImg"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=newnavimg&mo=y" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=newnavimg&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$FeaturedNavImgPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Featured Navigation Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="featurednavimg" value="<?php echo "$FeaturedNavImg"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=featurednavimg&mo=y"
                               target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=featurednavimg&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$SaleNavImgPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">Sale Navigation Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="salenavimg" value="<?php echo "$SaleNavImg"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=salenavimg&mo=y" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=salenavimg&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right">
                            <?php echo "<a href=\"$AllNavImgPopup\" style=\"text-decoration: none; color: #000000\" target=\"_blank\">All Navigation Image</a>:"; ?>
                        </td>
                        <td valign="middle" align="left">
                            <input type="text" name="allnavimg" value="<?php echo "$AllNavImg"; ?>" size="30">
                            <a href="includes/imgload.php?formsname=CatImg&fieldsname=allnavimg&mo=y" target="_blank"
                               onClick="PopUp=window.open('includes/imgload.php?formsname=CatImg&fieldsname=allnavimg&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center" colspan="2">
                            <input type="hidden" value="update" name="fcn">
                            <input type="hidden" value="save" name="catfcn">
                            <input type="submit" class="button" value="Update" name="Submit">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <a href="images.php">Back to Images</a>
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
} // DISPLAY IMAGE DIRECTORIES
else {

    if ($set_master_key == "yes" OR $show_general == "Yes") {
        ?>
        <form method="POST" action="images.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" class="fieldname">
                                Image List
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                Select a category from the list below to view all images in that directory.
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <?php
                                if ($imgdir == $thumbdir AND $thumbdir == $lgimgdir AND $lgimgdir == $catimgdir)
                                    echo "<input type=\"hidden\" name=\"imagedir\" value=\"imgdir\">";
                                else {
                                    echo "<select size=\"1\" name=\"imagedir\">";
                                    echo "<option value=\"imgdir\" selected>General Images Directory</option>";
                                    if ($thumbdir != $imgdir)
                                        echo "<option value=\"thumbdir\">Thumbnail Image Directory</option>";
                                    if ($lgimgdir != $imgdir AND $lgimgdir != $thumbdir)
                                        echo "<option value=\"lgimgdir\">Large Image Directory</option>";
                                    if ($catimgdir != $imgdir AND $catimgdir != $thumbdir AND $catimgdir != $lgimgdir)
                                        echo "<option value=\"catimgdir\">Category Image Directory</option>";
                                    echo "</select>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="submit" class="button" value="Show Images" name="Submit">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

    if ($set_master_key == "yes" OR $show_catalog == "Yes") {
        ?>
        <form method="POST" action="images.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" class="fieldname">Catalog Images</td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">Add or delete images to be used by your
                                shopping catalog, such as order buttons, search buttons, or navigation buttons.
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="hidden" value="update" name="fcn">
                                <input type="submit" class="button" value="Update" name="Submit">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>