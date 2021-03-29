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
    $custom_contents = str_replace("%CATEGORY_BAR%", '<?php include("$Home_Path/$Inc_Dir/navbar_bar.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_DESCRIPTION%", '<?php include("$Home_Path/$Inc_Dir/description.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_DROPDOWN%", '<?php include("$Home_Path/$Inc_Dir/categories.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_HORIZONTAL%", '<?php include("$Home_Path/$Inc_Dir/navbar_horizontal.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_NAVIGATION%", '<?php include("$Home_Path/$Inc_Dir/navbar_list.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_TABLE%", '<?php include("$Home_Path/$Inc_Dir/navbar_table.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_TABS%", '<?php include("$Home_Path/$Inc_Dir/navbar_tabs.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CATEGORY_VERTICAL%", '<?php include("$Home_Path/$Inc_Dir/navbar_vertical.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%CONTENT%", '<?php include("$Home_Path/$Inc_Dir/content.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%COPYRIGHT%", '<?php include("$Home_Path/$Inc_Dir/copyright.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%COUPONS%", '<?php include("$Home_Path/$Inc_Dir/coupon.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%EMAIL_FRIEND%", '<?php include("$Home_Path/$Inc_Dir/email.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%FEATURED_COLUMN%", '<?php include("$Home_Path/$Inc_Dir/featured.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%ITEMNUM_DROPDOWN%", '<?php include("$Home_Path/$Inc_Dir/catnum.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%LOGO%", '<?php include("$Home_Path/$Inc_Dir/logo.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%META_TAGS%", '<?php include("$Home_Path/$Inc_Dir/meta.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL%", '<?php include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_HORIZONTAL_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL%", '<?php include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%NAVIGATION_VERTICAL_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR%", '<?php include("$Home_Path/$Inc_Dir/pagelist_bar.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_BAR_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL%", '<?php include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_HORIZONTAL_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE%", '<?php include("$Home_Path/$Inc_Dir/pagelist_table.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABLE_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS%", '<?php include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_TABS_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGE_TITLE%", '<?php include("$Home_Path/$Inc_Dir/title.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL%", '<?php include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_1%", '<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_2%", '<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_3%", '<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_4%", '<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PAGES_VERTICAL_5%", '<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', $custom_contents);
    $custom_contents = str_replace("%PRICE_DROPDOWN%", '<?php include("$Home_Path/$Inc_Dir/price.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%SEARCH_CATALOG%", '<?php include("$Home_Path/$Inc_Dir/search_catalog.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%SEARCH_SITE%", '<?php include("$Home_Path/$Inc_Dir/search_site.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%SITE_MESSAGE%", '<?php include("$Home_Path/$Inc_Dir/sitemessage.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%SITE_LOGO%", '<?php include("$Home_Path/$Inc_Dir/sitelogo.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%SITE_NAME%", '<?php include("$Home_Path/$Inc_Dir/sitename.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%STYLE_SHEET%", '<?php include("$Home_Path/$Inc_Dir/style.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%TITLE_TAG%", '<?php include("$Home_Path/$Inc_Dir/metatitle.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%VIEW_CART%", '<?php include("$Home_Path/$Inc_Dir/viewcontents.php"); ?>', $custom_contents);
    $custom_contents = str_replace("%VIEW_CONTENT%", '<?php include("$Home_Path/$Inc_Dir/viewcart.php"); ?>', $custom_contents);

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
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/description.php"); ?>', "%CATEGORY_DESCRIPTION%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/categories.php"); ?>', "%CATEGORY_DROPDOWN%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_bar.php"); ?>', "%CATEGORY_BAR%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_horizontal.php"); ?>', "%CATEGORY_HORIZONTAL%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_list.php"); ?>', "%CATEGORY_NAVIGATION%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_table.php"); ?>', "%CATEGORY_TABLE%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_tabs.php"); ?>', "%CATEGORY_TABS%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/navbar_vertical.php"); ?>', "%CATEGORY_VERTICAL%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/content.php"); ?>', "%CONTENT%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/copyright.php"); ?>', "%COPYRIGHT%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/coupon.php"); ?>', "%COUPONS%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/email.php"); ?>', "%EMAIL_FRIEND%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/featured.php"); ?>', "%FEATURED_COLUMN%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/catnum.php"); ?>', "%ITEMNUM_DROPDOWN%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/meta.php"); ?>', "%META_TAGS%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/logo.php"); ?>', "%LOGO%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); ?>', "%NAVIGATION_HORIZONTAL%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', "%NAVIGATION_HORIZONTAL_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', "%NAVIGATION_HORIZONTAL_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', "%NAVIGATION_HORIZONTAL_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', "%NAVIGATION_HORIZONTAL_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_horizontal.php"); $pggrp=""; ?>', "%NAVIGATION_HORIZONTAL_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); ?>', "%NAVIGATION_VERTICAL%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', "%NAVIGATION_VERTICAL_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', "%NAVIGATION_VERTICAL_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', "%NAVIGATION_VERTICAL_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', "%NAVIGATION_VERTICAL_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagenav_vertical.php"); $pggrp=""; ?>', "%NAVIGATION_VERTICAL_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagelist_bar.php"); ?>', "%PAGES_BAR%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', "%PAGES_BAR_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', "%PAGES_BAR_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', "%PAGES_BAR_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', "%PAGES_BAR_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_bar.php"); $pggrp=""; ?>', "%PAGES_BAR_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); ?>', "%PAGES_HORIZONTAL%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', "%PAGES_HORIZONTAL_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', "%PAGES_HORIZONTAL_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', "%PAGES_HORIZONTAL_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', "%PAGES_HORIZONTAL_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_horizontal.php"); $pggrp=""; ?>', "%PAGES_HORIZONTAL_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagelist_table.php"); ?>', "%PAGES_TABLE%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', "%PAGES_TABLE_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', "%PAGES_TABLE_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', "%PAGES_TABLE_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', "%PAGES_TABLE_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_table.php"); $pggrp=""; ?>', "%PAGES_TABLE_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); ?>', "%PAGES_TABS%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', "%PAGES_TABS_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', "%PAGES_TABS_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', "%PAGES_TABS_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', "%PAGES_TABS_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_tabs.php"); $pggrp=""; ?>', "%PAGES_TABS_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); ?>', "%PAGES_VERTICAL%", $contents);
    $contents = str_replace('<?php $pggrp=1; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', "%PAGES_VERTICAL_1%", $contents);
    $contents = str_replace('<?php $pggrp=2; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', "%PAGES_VERTICAL_2%", $contents);
    $contents = str_replace('<?php $pggrp=3; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', "%PAGES_VERTICAL_3%", $contents);
    $contents = str_replace('<?php $pggrp=4; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', "%PAGES_VERTICAL_4%", $contents);
    $contents = str_replace('<?php $pggrp=5; include("$Home_Path/$Inc_Dir/pagelist_vertical.php"); $pggrp=""; ?>', "%PAGES_VERTICAL_5%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/title.php"); ?>', "%PAGE_TITLE%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/price.php"); ?>', "%PRICE_DROPDOWN%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/search_catalog.php"); ?>', "%SEARCH_CATALOG%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/search_site.php"); ?>', "%SEARCH_SITE%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/sitemessage.php"); ?>', "%SITE_MESSAGE%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/sitelogo.php"); ?>', "%SITE_LOGO%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/sitename.php"); ?>', "%SITE_NAME%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/style.php"); ?>', "%STYLE_SHEET%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/metatitle.php"); ?>', "%TITLE_TAG%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/viewcontents.php"); ?>', "%VIEW_CART%", $contents);
    $contents = str_replace('<?php include("$Home_Path/$Inc_Dir/viewcart.php"); ?>', "%VIEW_CONTENT%", $contents);
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