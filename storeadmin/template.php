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
    if ($showrow[0] == "templates")
        $show_templates = $showrow[1];
    if ($showrow[0] == "custom")
        $show_custom = $showrow[1];
}

if ($show_templates == "No" AND $show_custom == "No" AND $set_master_key == "no") {
    ?>
    <table border="0" cellpadding="7" cellspacing="0" align="center" class="generaltable">
        <tr>
            <td align="center" class="fieldname" colspan="2">Template Administration</td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                Sorry, but the template administration area is not currently available.
                Please ask your system administrator for assistance.
            </td>
        </tr>
    </table>
    <?php
}

$varquery = "SELECT URL FROM " . $DB_Prefix . "_vars WHERE ID=1";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select. Try again later.");
$varrow = mysqli_fetch_row($varresult);
$url = $varrow[0];

if ($mode == "save") {
    $custompage = "$Home_Path/template/index.htm";
// Replace variables
    $custom_contents = stripslashes($custom_contents);
    $custom_contents = str_replace("%CATEGORY_BAR%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_bar.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_DESCRIPTION%", '<script language="php">include("$Home_Path/$Inc_Dir/description.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_DROPDOWN%", '<script language="php">include("$Home_Path/$Inc_Dir/categories.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_HORIZONTAL%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_horizontal.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_NAVIGATION%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_list.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_TABLE%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_table.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_TABS%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_tabs.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_VERTICAL%", '<script language="php">include("$Home_Path/$Inc_Dir/navbar_vertical.php");</script>', $custom_contents);
    $custom_contents = str_replace("%CONTENT%", '<script language="php">include("$Home_Path/$Inc_Dir/content.php");</script>', $custom_contents);
    $custom_contents = str_replace("%COPYRIGHT%", '<script language="php">include("$Home_Path/$Inc_Dir/copyright.php");</script>', $custom_contents);
    $custom_contents = str_replace("%COUPONS%", '<script language="php">include("$Home_Path/$Inc_Dir/coupon.php");</script>', $custom_contents);
    $custom_contents = str_replace("%EMAIL_FRIEND%", '<script language="php">include("$Home_Path/$Inc_Dir/email.php");</script>', $custom_contents);
    $custom_contents = str_replace("%FEATURED_COLUMN%", '<script language="php">include("$Home_Path/$Inc_Dir/featured.php");</script>', $custom_contents);
    $custom_contents = str_replace("%ITEMNUM_DROPDOWN%", '<script language="php">include("$Home_Path/$Inc_Dir/catnum.php");</script>', $custom_contents);
    $custom_contents = str_replace("%LOGO%", '<script language="php">include("$Home_Path/$Inc_Dir/logo.php");</script>', $custom_contents);
    $custom_contents = str_replace("%META_TAGS%", '<script language="php">include("$Home_Path/$Inc_Dir/meta.php");</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL%", '<script language="php">include("$Home_Path/$Inc_Dir/pagenav_horizontal.php");</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL%", '<script language="php">include("$Home_Path/$Inc_Dir/pagenav_vertical.php");</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR%", '<script language="php">include("$Home_Path/$Inc_Dir/pagelist_bar.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL%", '<script language="php">include("$Home_Path/$Inc_Dir/pagelist_horizontal.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE%", '<script language="php">include("$Home_Path/$Inc_Dir/pagelist_table.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS%", '<script language="php">include("$Home_Path/$Inc_Dir/pagelist_tabs.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGE_TITLE%", '<script language="php">include("$Home_Path/$Inc_Dir/title.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL%", '<script language="php">include("$Home_Path/$Inc_Dir/pagelist_vertical.php");</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_1%", '<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_2%", '<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_3%", '<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_4%", '<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_5%", '<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', $custom_contents);
    $custom_contents = str_replace("%PRICE_DROPDOWN%", '<script language="php">include("$Home_Path/$Inc_Dir/price.php");</script>', $custom_contents);
    $custom_contents = str_replace("%SEARCH_CATALOG%", '<script language="php">include("$Home_Path/$Inc_Dir/search_catalog.php");</script>', $custom_contents);
    $custom_contents = str_replace("%SEARCH_SITE%", '<script language="php">include("$Home_Path/$Inc_Dir/search_site.php");</script>', $custom_contents);
    $custom_contents = str_replace("%SITE_MESSAGE%", '<script language="php">include("$Home_Path/$Inc_Dir/sitemessage.php");</script>', $custom_contents);
    $custom_contents = str_replace("%SITE_LOGO%", '<script language="php">include("$Home_Path/$Inc_Dir/sitelogo.php");</script>', $custom_contents);
    $custom_contents = str_replace("%SITE_NAME%", '<script language="php">include("$Home_Path/$Inc_Dir/sitename.php");</script>', $custom_contents);
    $custom_contents = str_replace("%STYLE_SHEET%", '<script language="php">include("$Home_Path/$Inc_Dir/style.php");</script>', $custom_contents);
    $custom_contents = str_replace("%TITLE_TAG%", '<script language="php">include("$Home_Path/$Inc_Dir/metatitle.php");</script>', $custom_contents);
    $custom_contents = str_replace("%VIEW_CART%", '<script language="php">include("$Home_Path/$Inc_Dir/viewcontents.php");</script>', $custom_contents);
    $custom_contents = str_replace("%VIEW_CONTENT%", '<script language="php">include("$Home_Path/$Inc_Dir/viewcart.php");</script>', $custom_contents);

    if (!empty($ftp_site)) {
        $cust_pg = str_replace($Home_Path, $ftp_path, $custompage);
        $conn_id = @ftp_connect($ftp_site);
        $login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);
        @ftp_site($conn_id, "CHMOD " . $chmod_update . " " . $cust_pg);
    }
    $handle = @fopen($custompage, "w+");
    if (!$handle)
        die ("Could not upload file");
    $writecontents = @fwrite($handle, $custom_contents);
    @fclose($handle);
    if (!empty($ftp_site)) {
        @ftp_site($conn_id, "CHMOD " . $chmod_file . " " . $cust_pg);
        @ftp_close($conn_id);
    }

    if ($writecontents) {
        $updquery = "UPDATE " . $DB_Prefix . "_vars SET ThemeName='index.htm' WHERE ID=1";
        $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
    } else
        echo "<p align=\"center\"><b>Please ask your administrator to check the custom template permissions.</b></p>";
}

// Update theme if needed
if ($Submit == "Apply" AND $template) {
    $updquery = "UPDATE " . $DB_Prefix . "_vars SET ThemeName='$template' WHERE ID=1";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
// Update variables if selected
    $phptemplate = str_replace(".htm", ".php", $template);
    if (file_exists("$Home_Path/template/$phptemplate")) {
        include("../template/$phptemplate");
        if ($updatecolors == "yes") {
            $updatecquery = "UPDATE " . $DB_Prefix . "_colors SET BodyBkgd='$BodyBkgd', BodyText='$BodyText', ";
            $updatecquery .= "AnchorLink='$AnchorLink', AnchorHover='$AnchorHover', HighlightText='$HighlightText', ";
            $updatecquery .= "HeaderColor='$HeaderColor', AccentColor='$AccentColor', SaleColor='$SaleColor', ";
            $updatecquery .= "LineColor='$LineColor', ProductLink='$ProductLink', ProductHover='$ProductHover', ";
            $updatecquery .= "PageLink='$PageLink', PageHover='$PageHover', FeatureLink='$FeatureLink', ";
            $updatecquery .= "FeatureHover='$FeatureHover', EmailLink='$EmailLink', EmailHover='$EmailHover', ";
            $updatecquery .= "RelatedLink='$RelatedLink', RelatedHover='$RelatedHover', PopupLink='$PopupLink', ";
            $updatecquery .= "PopupHover='$PopupHover', CartLink='$CartLink', CartHover='$CartHover', ";
            $updatecquery .= "DrillDownLink='$DrillDownLink', DrillDownHover='$DrillDownHover', ";
            $updatecquery .= "CategoryLink='$CategoryLink', CategoryHover='$CategoryHover', SubCatLink='$SubCatLink', ";
            $updatecquery .= "SubCatHover='$SubCatHover', EndCatLink='$EndCatLink', EndCatHover='$EndCatHover', ";
            $updatecquery .= "FormButtonText='$FormButtonText', FormButtonBkgd='$FormButtonBkgd', ";
            $updatecquery .= "FormButtonBorder='$FormButtonBorder', CatButtonText='$CatButtonText', ";
            $updatecquery .= "CatButtonBkgd='$CatButtonBkgd', CatButtonBorder='$CatButtonBorder', ";
            $updatecquery .= "CatActiveText='$CatActiveText', CatActiveBkgd='$CatActiveBkgd', ";
            $updatecquery .= "CatHoverText='$CatHoverText', CatHoverBkgd='$CatHoverBkgd' WHERE ID='1'";
            $updatecresult = mysqli_query($dblink, $updatecquery) or die("Unable to update colors. Please try again later.");
        }
        if ($updateimages == "yes") {
            if ($Logo != "" AND substr($Logo, 0, 7) != "http://")
                $Logo = $urldir . "/" . $Logo;
            if ($OrderButton != "" AND substr($OrderButton, 0, 7) != "http://")
                $OrderButton = $urldir . "/" . $OrderButton;
            if ($SearchButton != "" AND substr($SearchButton, 0, 7) != "http://")
                $SearchButton = $urldir . "/" . $SearchButton;
            if ($CartButton != "" AND substr($CartButton, 0, 7) != "http://")
                $CartButton = $urldir . "/" . $CartButton;
            if ($RegistryButton != "" AND substr($RegistryButton, 0, 7) != "http://")
                $RegistryButton = $urldir . "/" . $RegistryButton;
            if ($NewHeader != "" AND substr($NewHeader, 0, 7) != "http://")
                $NewHeader = $urldir . "/" . $NewHeader;
            if ($FeaturedHeader != "" AND substr($FeaturedHeader, 0, 7) != "http://")
                $FeaturedHeader = $urldir . "/" . $FeaturedHeader;
            if ($SaleHeader != "" AND substr($SaleHeader, 0, 7) != "http://")
                $SaleHeader = $urldir . "/" . $SaleHeader;
            if ($AllHeader != "" AND substr($AllHeader, 0, 7) != "http://")
                $AllHeader = $urldir . "/" . $AllHeader;
            if ($NewNavigation != "" AND substr($NewNavigation, 0, 7) != "http://")
                $NewNavigation = $urldir . "/" . $NewNavigation;
            if ($FeaturedNavigation != "" AND substr($FeaturedNavigation, 0, 7) != "http://")
                $FeaturedNavigation = $urldir . "/" . $FeaturedNavigation;
            if ($SaleNavigation != "" AND substr($SaleNavigation, 0, 7) != "http://")
                $SaleNavigation = $urldir . "/" . $SaleNavigation;
            if ($AllNavigation != "" AND substr($AllNavigation, 0, 7) != "http://")
                $AllNavigation = $urldir . "/" . $AllNavigation;
            $updatevquery = "UPDATE " . $DB_Prefix . "_vars SET LogoURL='$Logo', OrderButton='$OrderButton', ";
            $updatevquery .= "SearchButton='$SearchButton', ViewCartButton='$CartButton', RegistryButton='$RegistryButton', ";
            $updatevquery .= "NewHeader='$NewHeader', FeaturedHeader='$FeaturedHeader', SaleHeader='$SaleHeader', ";
            $updatevquery .= "AllHeader='$AllHeader', NewNavImg='$NewNavigation', FeaturedNavImg='$FeaturedNavigation', ";
            $updatevquery .= "SaleNavImg='$SaleNavigation', AllNavImg='$AllNavigation' WHERE ID='1'";
            $updatevresult = mysqli_query($dblink, $updatevquery) or die("Unable to update colors. Please try again later.");
        }
    }
}

$thmquery = "SELECT ThemeName FROM " . $DB_Prefix . "_vars WHERE ID='1'";
$thmresult = mysqli_query($dblink, $thmquery) or die ("Unable to select. Try again later.");
$thmrow = mysqli_fetch_row($thmresult);
$theme_name = $thmrow[0];

// Create theme options
if ($dir = @opendir("../template")) {
    while (($file = readdir($dir)) !== false) {
        $tempval = str_replace("_", " ", substr($file, 0, -4));
        if (substr($file, -4) == ".htm" AND $file != "index.htm") {
            $setselection = "<";
            $setselection .= "option value=\"$file\"";
            if ($file == $theme_name)
                $setselection .= "selected ";
            $setselection .= ">$tempval</";
            $setselection .= "option>";
            $setsellist[] = $setselection;
        }
    }
    closedir($dir);
}

//Show form if templates exist
if ($setselection != "" AND ($set_master_key == "yes" OR $show_templates == "Yes")) {
    ?>

    <form action="template.php" method="POST">
        <table border="0" cellpadding="3" cellspacing="0" align="center" class="generaltable">
            <tr>
                <td align="center" class="fieldname">Web Site Template</td>
            </tr>
            <tr>
                <td align="center">
                    Choose a template style below or add a custom theme to your site:
                </td>
            </tr>
            <tr>
                <td align="center">
                    <select name="template" size="1">
                        <?php
                        echo "<option ";
                        if (!$theme_name OR $theme_name == "index.htm")
                            echo "selected ";
                        echo "value=\"\">-- Select Template --</option>";
                        asort($setsellist);
                        $setselect = implode("", $setsellist);
                        echo "$setselect";
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <blockquote>
                        <input type="checkbox" name="updatecolors" checked value="yes"> Update Link Colors (overrides
                        current color settings)
                        <br><input type="checkbox" name="updateimages" value="yes"> Update Logo/Images (overrides
                        current image settings)
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <input type="submit" class="button" value="Apply" name="Submit">
                    <?php
                    if (file_exists("$Home_Path/template/index.htm") AND ($set_master_key == "yes" OR $show_custom == "Yes"))
                        echo "<input type=\"submit\" class=\"button\" value=\"Customize\" name=\"Submit\">";
                    ?>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

// Update theme if needed
if (file_exists("$Home_Path/template/index.htm") AND ($set_master_key == "yes" OR $show_custom == "Yes")) {

    if ($Submit == "Customize" AND $template) {
        $custompage = "$Home_Path/template/$template";
        $templateval = str_replace("_", " ", substr($template, 0, -4));
        echo "<p align=\"center\" class=\"highlighttext\">Please Note: You are editing the $templateval template. If you save these<br>
changes by clicking &quot;Update&quot; below, you will override any custom<br>template you have loaded in 
the past. This change is not reversable!</p>";
    } else
        $custompage = "$Home_Path/template/index.htm";
    if (version_compare(phpversion(), "4.3.0", ">=")) {
        $contents = file_get_contents($custompage);
    } else {
        if (!empty($ftp_site)) {
            $cust_pg = str_replace($Home_Path, $ftp_path, $custompage);
            $conn_id = @ftp_connect($ftp_site);
            $login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);
            @ftp_site($conn_id, "CHMOD " . $chmod_update . " " . $cust_pg);
        }
        $handle = @fopen($custompage, "a+");
        if (!$handle)
            die ("Could not upload file");
        $contents = @fread($handle, filesize($custompage));
        @fclose($handle);
        if (!empty($ftp_site)) {
            @ftp_site($conn_id, "CHMOD " . $chmod_file . " " . $cust_pg);
            @ftp_close($conn_id);
        }
        if (!$contents)
            echo "<p align=\"center\"><b>Please ask your administrator to check the template file permissions.</b></p>";
    }

// Replace variables
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/description.php");</script>', "%CATEGORY_DESCRIPTION%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/categories.php");</script>', "%CATEGORY_DROPDOWN%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_bar.php");</script>', "%CATEGORY_BAR%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_horizontal.php");</script>', "%CATEGORY_HORIZONTAL%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_list.php");</script>', "%CATEGORY_NAVIGATION%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_table.php");</script>', "%CATEGORY_TABLE%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_tabs.php");</script>', "%CATEGORY_TABS%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/navbar_vertical.php");</script>', "%CATEGORY_VERTICAL%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/content.php");</script>', "%CONTENT%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/copyright.php");</script>', "%COPYRIGHT%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/coupon.php");</script>', "%COUPONS%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/email.php");</script>', "%EMAIL_FRIEND%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/featured.php");</script>', "%FEATURED_COLUMN%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/catnum.php");</script>', "%ITEMNUM_DROPDOWN%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/meta.php");</script>', "%META_TAGS%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/logo.php");</script>', "%LOGO%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagenav_horizontal.php");</script>', "%NAVIGATION_HORIZONTAL%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', "%NAVIGATION_HORIZONTAL_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', "%NAVIGATION_HORIZONTAL_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', "%NAVIGATION_HORIZONTAL_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', "%NAVIGATION_HORIZONTAL_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp="";</script>', "%NAVIGATION_HORIZONTAL_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagenav_vertical.php");</script>', "%NAVIGATION_VERTICAL%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', "%NAVIGATION_VERTICAL_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', "%NAVIGATION_VERTICAL_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', "%NAVIGATION_VERTICAL_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', "%NAVIGATION_VERTICAL_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp="";</script>', "%NAVIGATION_VERTICAL_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagelist_bar.php");</script>', "%PAGES_BAR%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', "%PAGES_BAR_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', "%PAGES_BAR_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', "%PAGES_BAR_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', "%PAGES_BAR_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp="";</script>', "%PAGES_BAR_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagelist_horizontal.php");</script>', "%PAGES_HORIZONTAL%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', "%PAGES_HORIZONTAL_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', "%PAGES_HORIZONTAL_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', "%PAGES_HORIZONTAL_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', "%PAGES_HORIZONTAL_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp="";</script>', "%PAGES_HORIZONTAL_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagelist_table.php");</script>', "%PAGES_TABLE%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', "%PAGES_TABLE_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', "%PAGES_TABLE_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', "%PAGES_TABLE_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', "%PAGES_TABLE_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp="";</script>', "%PAGES_TABLE_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagelist_tabs.php");</script>', "%PAGES_TABS%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', "%PAGES_TABS_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', "%PAGES_TABS_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', "%PAGES_TABS_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', "%PAGES_TABS_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp="";</script>', "%PAGES_TABS_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/pagelist_vertical.php");</script>', "%PAGES_VERTICAL%", $contents);
    $contents = str_replace('<script language="php">$pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', "%PAGES_VERTICAL_1%", $contents);
    $contents = str_replace('<script language="php">$pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', "%PAGES_VERTICAL_2%", $contents);
    $contents = str_replace('<script language="php">$pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', "%PAGES_VERTICAL_3%", $contents);
    $contents = str_replace('<script language="php">$pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', "%PAGES_VERTICAL_4%", $contents);
    $contents = str_replace('<script language="php">$pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp="";</script>', "%PAGES_VERTICAL_5%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/title.php");</script>', "%PAGE_TITLE%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/price.php");</script>', "%PRICE_DROPDOWN%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/search_catalog.php");</script>', "%SEARCH_CATALOG%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/search_site.php");</script>', "%SEARCH_SITE%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/sitemessage.php");</script>', "%SITE_MESSAGE%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/sitelogo.php");</script>', "%SITE_LOGO%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/sitename.php");</script>', "%SITE_NAME%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/style.php");</script>', "%STYLE_SHEET%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/metatitle.php");</script>', "%TITLE_TAG%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/viewcontents.php");</script>', "%VIEW_CART%", $contents);
    $contents = str_replace('<script language="php">include("$Home_Path/$Inc_Dir/viewcart.php");</script>', "%VIEW_CONTENT%", $contents);
    ?>

    <a name="customize"></a>
    <form action="template.php" method="POST">
        <table border="0" cellpadding="7" cellspacing="0" align="center" class="generaltable">
            <tr>
                <td align="center" class="fieldname" colspan="2">Custom Template</td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    Paste your custom template's HTML code below, including all HTML tags<br>
                    and any <a href="includes/sitecodes.htm" target="_blank" onClick="PopUp=window.open('includes/sitecodes.htm',
'NewWin', 'resizable=yes,scrollbars=yes,width=400,height=400,left=0,top=0,screenX=0,screenY=0'); 
PopUp.focus(); return false;">site include codes</a> as needed. Use absolute references for all of your<br>
                    images and links, instead of relative ones. (ie. &lt;a
                    href=&quot;page.php&quot;&gt;Page&lt;/a&gt;<br>
                    would be written as &lt;a href=&quot;http://www.yoursite.com/page.php&quot;&gt;Page&lt;/a&gt;)
                </td>
            </tr>
            <td>
                <tr>
                    <td align="center" colspan="2">
<textarea rows="20" name="custom_contents" cols="55">
<?php
echo "$contents";
?>
</textarea>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <input type="hidden" value="save" name="mode">
                        <input type="submit" class="button" value="Update" name="Submit">
                    </td>
                </tr>
        </table>
    </form>
    <?php
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>