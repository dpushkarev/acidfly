<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Determine page
$setpg = str_replace(dirname($_SERVER['PHP_SELF']) ."/","",$_SERVER['PHP_SELF']);
$setpg = str_replace("/","",$setpg);
$getpgnm = explode(".", $setpg);
$pgnm_noext = $getpgnm[0];

// Sets Cookies
if (isset($_POST[tot]))
{
setcookie ("qtyvals", "$_POST[qty]", "0", "/", "", 0);
setcookie ("totvals", "$_POST[tot]", "0", "/", "", 0);
}
else if (isset($_GET[tot]))
{
setcookie ("qtyvals", "$_GET[qty]", "0", "/", "", 0);
setcookie ("totvals", "$_GET[tot]", "0", "/", "", 0);
}
else
{
$qty = "$_COOKIE[qtyvals]";
$tot = "$_COOKIE[totvals]";
}

if (isset($_POST[wholeemail]))
{
setcookie ("wsemail", "$_POST[wholeemail]", "0", "/", "", 0);
$wsemail = $_POST[wholeemail];
setcookie ("membercode", "", "0", "/", "", 0);
$membercode = "";
}
else if (isset($_COOKIE[wsemail]))
$wsemail = $_COOKIE[wsemail];

if (isset($_POST[wholepass]))
{
setcookie ("wspass", "$_POST[wholepass]", "0", "/", "", 0);
$wspass = $_POST[wholepass];
}
else if (isset($_COOKIE[wspass]))
$wspass = $_COOKIE[wspass];

if ($_GET[wslg] == "y" OR isset($_GET[code]))
{
setcookie ("wsemail", "", "0", "/", "", 0);
setcookie ("wspass", "", "0", "/", "", 0);
$wsemail = "";
$wspass = "";
}

if (isset($_GET[code]))
{
setcookie ("membercode", "$_GET[code]", "0", "/", "", 0);
$membercode = $_GET[code];
}
else if (isset($_COOKIE[membercode]))
$membercode = $_COOKIE[membercode];

// Set directory
$thisdir = str_replace("/","",dirname($_SERVER[PHP_SELF]));
if (substr($thisdir, -5) == "pages")
$dirname = "pages";
else if (substr($thisdir, -2) == "go")
$dirname = "go";
else
$dirname = "";

$wsmsg = "";
$memmsg = "";

// Check Wholesale
if ($wspass AND $wsemail)
{
$wsquery = "SELECT Company, Discount FROM " .$DB_Prefix ."_wholesale WHERE Email='$wsemail' AND Password='$wspass' AND Active='Yes'";
$wsresult = mysql_query($wsquery, $dblink) or die ("Unable to select. Try again later.");
$wsnum = mysql_num_rows($wsresult);
if ($wsnum == 1)
{
$wsrow = mysql_fetch_row($wsresult);
if ($wsrow[1] > 0)
$wsoverride = $wsrow[1];
else
$wsoverride = 0;
$wsmsg = "<p align=\"center\" class=\"accent\">";
if ($mode == "ws")
$wsmsg .= "Welcome, " .stripslashes($wsrow[0]) .". ";
$wsmsg .= "Wholesale discounts will be applied in the cart to applicable products.</p>";
}
}

// Check member info
else if ($membercode)
{
$thisday = date("Y-m-d");
$memquery = "SELECT * FROM " .$DB_Prefix ."_coupons WHERE CodeNumber='$membercode' AND ExpireDate > '$thisday'";
$memresult = mysql_query($memquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($memresult) == 1)
{
$memrow = mysql_fetch_array($memresult);
$memdisc = (float)$memrow[Discount];
$membername = stripslashes($memrow[GroupName]);
$memmsg = "<p align=\"center\" class=\"accent\">";
$memmsg .= "A $memdisc% discount for $membername will be applied.";
$memmsg .= "</p>";
}
}

// Sets product line color
$colorquery = "SELECT LineColor FROM " .$DB_Prefix ."_colors WHERE ID='1'";
$colorresult = mysql_query($colorquery) or die ("Unable to select your system variables. Try again later.");
$colorrow = mysql_fetch_array($colorresult);
if ($colorrow[LineColor])
$Product_Line = $colorrow[LineColor];

// Sets System Variables
$varquery = "SELECT * FROM " .$DB_Prefix ."_vars";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select your system variables. Try again later.");
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_array($varresult);
$Open_Set=$varrow[OpenSet];
$Site_Name=stripslashes($varrow[SiteName]);
$URL=str_replace("~", "&#126;", $urldir);
$catpg=$varrow[CatalogPage];
$Catalog_Page=$URL ."/" .$catpg;
$Admin_Email=$varrow[AdminEmail];
$Search_URL=$URL ."/advsearch.$pageext";
$themename = $varrow[ThemeName];
$Meta_Title=stripslashes($varrow[MetaTitle]);
$Meta_Keywords=stripslashes($varrow[Keywords]);
$Meta_Description=stripslashes($varrow[Description]);
$Mals_Cart_ID=$varrow[MalsCart];
$Mals_Server=$varrow[MalsServer];
$Currency=$varrow[Currency];
if ($Mals_Cart_ID == "")
$Set_Ord_Button="No";
else
$Set_Ord_Button=stripslashes($varrow[SetOrdButton]);
$Site_Message=stripslashes($varrow[SiteMessage]);
$Image_Dir = $varrow[ImageDir];

if (substr($varrow[OrderButton], 0, 7) == "http://")
$Order_Button="$varrow[OrderButton]";
else if ($varrow[OrderButton])
$Order_Button="$URL/$varrow[OrderButton]";

if (substr($varrow[SearchButton], 0, 7) == "http://")
$Search_Button="$varrow[SearchButton]";
else if ($varrow[SearchButton])
$Search_Button="$URL/$varrow[SearchButton]";

$All_Product_Link=$varrow[AllProductLink];
$Item_Rows=$varrow[ItemRows];
$Item_Columns=$varrow[ItemColumns];
$NavBarType=$varrow[NavBar];
if ($varrow[OptionNumber] > 0)
$Option_Number=$varrow[OptionNumber];
else
$Option_Number=1;
$Order_Of_Product=$varrow[OrderOfProduct];
$Show_OOS=$varrow[ShowOOS];
if (substr($varrow[ViewCartButton], 0, 7) == "http://")
$Cart_Button="$varrow[ViewCartButton]";
else if ($varrow[ViewCartButton])
$Cart_Button="$URL/$varrow[ViewCartButton]";
$Page_Numbers=$varrow[PageNumbers];
$Adv_Search=$varrow[AdvSearch];
$Email_Link=$varrow[EmailLink];
$Limited_Qty=stripslashes($varrow[LimitedQty]);
$Sales_Product_Link=$varrow[SaleProductLink];
$New_Product_Link=$varrow[NewProductLink];
$Featured_Product_Link=$varrow[FeaturedLink];
$Registry=$varrow[WishList];
if (substr($varrow[RegistryButton], 0, 7) == "http://")
$Registry_Button="$varrow[RegistryButton]";
else if ($varrow[RegistryButton])
$Registry_Button="$URL/$varrow[RegistryButton]";
if ($varrow[ImageHeight])
$imgheight=$varrow[ImageHeight];
else
$imgheight="";
if ($varrow[ImageWidth])
$imgwidth=$varrow[ImageWidth];
else
$imgwidth="";
$inventorylimit=$varrow[InventoryLimit];
$inventorycheck=$varrow[InventoryCheck];
$relatedmsg = stripslashes($varrow[RelatedMsg]);
if (substr($varrow[NewHeader], 0, 7) == "http://")
$newheader = "$varrow[NewHeader]";
else if ($varrow[NewHeader])
$newheader = "$URL/$varrow[NewHeader]";

if (substr($varrow[FeaturedHeader], 0, 7) == "http://")
$featuredheader = "$varrow[FeaturedHeader]";
else if ($varrow[FeaturedHeader])
$featuredheader = "$URL/$varrow[FeaturedHeader]";

if (substr($varrow[SaleHeader], 0, 7) == "http://")
$saleheader = "$varrow[SaleHeader]";
else if ($varrow[SaleHeader])
$saleheader = "$URL/$varrow[SaleHeader]";

if (substr($varrow[AllHeader], 0, 7) == "http://")
$allheader = "$varrow[AllHeader]";
else if ($varrow[AllHeader])
$allheader = "$URL/$varrow[AllHeader]";

if (substr($varrow[NewNavImg], 0, 7) == "http://")
$newnavimg = "$varrow[NewNavImg]";
else if ($varrow[NewNavImg])
$newnavimg = "$URL/$varrow[NewNavImg]";

if (substr($varrow[FeaturedNavImg], 0, 7) == "http://")
$featurednavimg = "$varrow[FeaturedNavImg]";
else if ($varrow[FeaturedNavImg])
$featurednavimg = "$URL/$varrow[FeaturedNavImg]";

if (substr($varrow[SaleNavImg], 0, 7) == "http://")
$salenavimg = "$varrow[SaleNavImg]";
else if ($varrow[SaleNavImg])
$salenavimg = "$URL/$varrow[SaleNavImg]";

if (substr($varrow[AllNavImg], 0, 7) == "http://")
$allnavimg = "$varrow[AllNavImg]";
else if ($varrow[AllNavImg])
$allnavimg = "$URL/$varrow[AllNavImg]";

$indexproducts=$varrow[IndexProducts];
$catalogproducts=$varrow[CatalogProducts];

if (substr($varrow[LogoURL], 0, 7) == "http://")
$logourl=$varrow[LogoURL];
else if ($varrow[LogoURL])
$logourl="$URL/$varrow[LogoURL]";
if ($logourl)
{
$logosize=@getimagesize($logourl);
$logowidth=$logosize[0];
$logoheight=$logosize[1];
}
$relatedimages=$varrow[RelatedImages];
$showmaincat=$varrow[MainCategories];
$OrderProcess = $varrow[OrderProcess];

$Open_Key=strtolower($varrow[URL]);
$Open_Key=str_replace("www.","",$Open_Key);
$Open_Key=str_replace(".","",$Open_Key);
$Open_Key=str_replace("/","",$Open_Key);
$Open_Key="oc_sb$Open_Key";
$Open_Key=md5($Open_Key);
}

// Set review link
$View_Page = "http://" .$Mals_Server .".aitsafe.com/cf/review.cfm";

if ($Open_Key != $Open_Set)
die("Domain info is not correct for $URL. Please re-enter variables.");

// Start search query for required pages
if ($setpg == $catpg OR $pgnm_noext == "index")
{
$searchquery = "SELECT * FROM " .$DB_Prefix ."_items LEFT JOIN " .$DB_Prefix ."_options";
$searchquery .= " ON " .$DB_Prefix ."_items.ID=ItemID WHERE " .$DB_Prefix ."_items.Active = 'Yes'";
// If not wholesale, limit to non ws items
if (empty($wspass) AND empty($wsemail) AND $wsnum != 1)
$searchquery .= " AND WSOnly = 'No'";

if ($Show_OOS == "No")
$searchquery .= " AND (Inventory IS NULL OR Inventory > 0)";

// Search by keyword
if ($keyword)
{
if (substr($cond, 0, 3) == "adv")
{
$cond = strtoupper(str_replace("adv","",$cond));
$advsearch_set = "yes";
}
if (!$cond)
$cond = "AND";
if ($condition == "PHRASE")
$searchquery .= " AND (Item LIKE '%$keyword%' OR Keywords LIKE '%$keyword%' OR Attributes LIKE '%$keyword%')";
else
{
$searchterm = explode(" ", trim($keyword));
$countsearch = count($searchterm);
$nameterms = "";
$searchquery .= " AND (";
for ($scounter = 0; $scounter < $countsearch; ++$scounter)
{
$addlterm = addslash_mq($searchterm[$scounter]);
if ($scounter != 0)
{
$searchquery .= " $cond ";
$nameterms .= " $cond ";
}
$searchquery .= "(Item LIKE '%$addlterm%' OR Keywords LIKE '%$addlterm%' OR Attributes LIKE '%$addlterm%')";
$nameterms .= "$addlterm";
}
$searchquery .= ")";
}
}

// If customer selects category, find records with that category
if ($category)
{
$parentquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$category'";
$parentresult = mysql_query($parentquery, $dblink) or die ("Unable to select category. Try again later.");
$searchquery .= "AND (Category1='$category' OR Category2='$category' OR Category3='$category' OR Category4='$category' OR Category5='$category'";
if (mysql_num_rows($parentresult) > 0)
{
$maincat_set = "yes";
for ($p = 1; $parentrow = mysql_fetch_row($parentresult); ++$p)
{
$searchquery .= " OR Category1='$parentrow[0]' OR Category2='$parentrow[0]' OR Category3='$parentrow[0]' OR Category4='$parentrow[0]' OR Category5='$parentrow[0]'";
// START MULTI CATEGORY
$endquery = "SELECT ID FROM " .$DB_Prefix ."_categories WHERE Parent='$parentrow[0]'";
$endresult = mysql_query($endquery, $dblink) or die ("Unable to select category. Try again later.");
if (mysql_num_rows($endresult) > 0)
{
for ($s = 1; $endrow = mysql_fetch_row($endresult); ++$s)
{
$searchquery .= " OR Category1='$endrow[0]' OR Category2='$endrow[0]' OR Category3='$endrow[0]' OR Category4='$endrow[0]' OR Category5='$endrow[0]'";
}
}
// END MULTI CATEGORY
}
}
$searchquery .= ")";
$metaquery = "SELECT Category, Keywords, Description, HeaderImage, CatColumns, CatRows, MetaTitle FROM " .$DB_Prefix ."_categories WHERE ID='$category'";
$metaresult = mysql_query($metaquery, $dblink) or die ("Unable to select category. Try again later.");
if (mysql_num_rows($metaresult) == 1)
{
$metarow = mysql_fetch_array($metaresult);
if ($metarow[Keywords])
$Meta_Keywords=stripslashes($metarow[Keywords]);
if ($metarow[Description])
$Meta_Description=substr(strip_tags(stripslashes($metarow[Description])), 0, 100);
if ($metarow[MetaTitle])
$Meta_Title=stripslashes($metarow[MetaTitle]);
else
$Meta_Title=stripslashes($metarow[Category]);
$Catalog_Description=stripslashes($metarow[Description]);
if (substr($metarow[HeaderImage], 0, 7) == "http://")
$Header_Image=$metarow[HeaderImage];
else if ($metarow[HeaderImage])
$Header_Image="$URL/$metarow[HeaderImage]";
if ($metarow[CatColumns] != "")
$Item_Columns=$metarow[CatColumns];
if ($metarow[CatRows] > 0)
$Item_Rows=$metarow[CatRows];
$stripcategory = stripslashes($metarow[Category]);
$repcategory = str_replace("&","&amp;",$stripcategory);
$pagetitle = "$repcategory";
}
}

// If customer chooses an item, find that item (for customizes)
if ($item)
{
$searchquery .= " AND " .$DB_Prefix ."_items.ID = '$item'";
$metaquery = "SELECT Keywords, Description, MetaTitle, Item FROM " .$DB_Prefix ."_items WHERE ID='$item'";
$metaresult = mysql_query($metaquery, $dblink) or die ("Unable to select item. Try again later.");
if (mysql_num_rows($metaresult) == 1)
{
$metarow = mysql_fetch_array($metaresult);
if ($metarow[Keywords])
$Meta_Keywords=stripslashes($metarow[Keywords]);
if ($metarow[Description])
$Meta_Description=substr(strip_tags(stripslashes($metarow[Description])), 0, 100);
if ($metarow[MetaTitle])
$Meta_Title=stripslashes($metarow[MetaTitle]);
else
$Meta_Title=stripslashes($metarow[Item]);
}
}

// If customer chooses a catalog number, find that number (for customizes)
if ($catalog)
{
$searchquery .= " AND Catalog = '$catalog'";
$metaquery = "SELECT Keywords, Description, MetaTitle, Item FROM " .$DB_Prefix ."_items WHERE Catalog='$catalog'";
$metaresult = mysql_query($metaquery, $dblink) or die ("Unable to select item. Try again later.");
if (mysql_num_rows($metaresult) == 1)
{
$metarow = mysql_fetch_array($metaresult);
if ($metarow[Keywords])
$Meta_Keywords=stripslashes($metarow[Keywords]);
if ($metarow[Description])
$Meta_Description=substr(strip_tags(stripslashes($metarow[Description])), 0, 100);
if ($metarow[MetaTitle])
$Meta_Title=stripslashes($metarow[MetaTitle]);
else
$Meta_Title=stripslashes($metarow[Item]);
}
}

// If customer chooses sale items, find all items on sale
if ($sale)
$searchquery .= " AND SalePrice <> '0'";

// If customer chooses featured items, find all features
if ($featured)
$searchquery .= " AND Featured = 'Yes'";

// If customer selects price, find records in that price range
if ($price)
{
$pricearray = explode("-", trim($price));
$startprice = $pricearray[0];
$endprice = $pricearray[1];

if ($startprice == 0 AND $endprice == 0)
$searchquery .= " AND " .$DB_Prefix ."_items.ID='0'";
if ($endprice == 0)
$searchquery .= " AND ((SalePrice = 0 AND RegPrice >= '$startprice') OR (SalePrice > 0 AND SalePrice >= '$startprice'))";
else if ($startprice == 0)
$searchquery .= " AND ((SalePrice = 0 AND RegPrice <= '$endprice') OR (SalePrice > 0 AND SalePrice <= '$endprice'))";
else
$searchquery .= " AND ((SalePrice = 0 AND RegPrice >= '$startprice' AND RegPrice <= '$endprice') OR (SalePrice > 0 AND SalePrice >= '$startprice' AND SalePrice <= '$endprice'))";
}
}

// Page info
$pgquery = "SELECT * FROM " .$DB_Prefix ."_pages WHERE PageName = '$pgnm_noext'";
$pgresult = mysql_query($pgquery, $dblink) or die ("Unable to select. Try again later.");
$pgnum = mysql_num_rows($pgresult);
if ($pgnum == 1)
{
$pgrow = mysql_fetch_array($pgresult);
if ($pgrow[Active] == "No")
{
header ("location: $URL/error.$pageext");
die("Sorry, this page could not be found.");
}
else
{
if ($pgrow[ThemeName])
{
$changetheme = $pgrow[ThemeName];
$themename = $changetheme;
}
$pg_type = $pgrow[PageType];
$pg_content = stripslashes($pgrow[Content]);
if ($pgrow[Keywords])
$Meta_Keywords=stripslashes($pgrow[Keywords]);
if ($pgrow[Description])
$Meta_Description=substr(strip_tags(stripslashes($pgrow[Description])), 0, 100);
if ($pgrow[MetaTitle])
$Meta_Title=stripslashes($pgrow[MetaTitle]);
if (strstr($pgrow[Content], '<') == FALSE AND strstr($pgrow[Content], '>') == FALSE)
{
$pg_content = str_replace ("\r", "<br>", $pg_content);
$pg_content = "<p>$pg_content</p>";
}
if ($setpg == $catpg)
$navlisttitle = stripslashes($pgrow[PageTitle]);
if ($pgrow[ShowTitle] == "Yes" AND ($setpg != $catpg OR ($setpg == $catpg AND !$category AND !$keyword AND !$cond AND !$item AND !$catalog AND !$price AND !$sale AND !$featured AND !$all AND !$new AND !$mode)))
{
$pagetitle = stripslashes($pgrow[PageTitle]);
if (substr($pgrow[HeaderImage], 0, 7) == "http://")
$Header_Image = "$pgrow[HeaderImage]";
else if ($pgrow[HeaderImage])
$Header_Image = "$URL/$pgrow[HeaderImage]";
}
else if ($setpg == $catpg)
{
if ($advsearch_set == "yes")
$pagetitle = "Advanced Search";
else if ($keyword)
$pagetitle = "Search for Keyword(s): $nameterms";
else if ($price AND $startprice == 0 AND $endprice == 0)
$pagetitle = "Price Not Selected";
else if ($price AND $endprice == 0)
$pagetitle = "Over $Currency$startprice";
else if ($price AND $startprice == 0)
$pagetitle = "Under $Currency$endprice";
else if ($price)
$pagetitle = "From $Currency$startprice to $Currency$endprice";
else if ($new == "yes" AND $newheader)
$Header_Image = $newheader;
else if ($new == "yes")
$pagetitle = "New Items";
else if ($sale == "yes" AND $saleheader)
$Header_Image = $saleheader;
else if ($sale == "yes")
$pagetitle = "Sale Items";
else if ($featured == "yes" AND $featuredheader)
$Header_Image = $featuredheader;
else if ($featured == "yes")
$pagetitle = "Featured Items";
else if ($all == "yes" AND $allheader)
$Header_Image = $allheader;
else if ($all == "yes")
$pagetitle = "All Items";
}
}
}
// Set shopping cart title
else if ($setpg == "order.$pageext" AND $dirname == "go")
$pagetitle = "Shopping Cart";
// Set page list title
else if ($setpg == "item_list.php" AND $dirname == "store")
$pagetitle = "Product List";

// Create dynamic return url
$Return_URL = "$urlbase/";
if ($dirname)
$Return_URL .= "$dirname/";
$Return_URL .= "$setpg";
$querylist = array();
if ($_POST['category'] OR $_GET['category'])
$querylist[] = "category=$category";
if ($_POST['keyword'] OR $_GET['keyword'])
$querylist[] = "keyword=$keyword";
if ($_POST['cond'] OR $_GET['cond'])
$querylist[] = "cond=$cond";
if ($_POST['item'] OR $_GET['item'])
$querylist[] = "item=$item";
if ($_POST['catalog'] OR $_GET['catalog'])
$querylist[] = "catalog=$catalog";
if ($_POST['price'] OR $_GET['price'])
$querylist[] = "price=$price";
if ($_POST['sale'] OR $_GET['sale'])
$querylist[] = "sale=$sale";
if ($_POST['featured'] OR $_GET['featured'])
$querylist[] = "featured=$featured";
if ($_POST['all'] OR $_GET['all'])
$querylist[] = "all=$all";
if ($_POST['new'] OR $_GET['new'])
$querylist[] = "new=$new";
if ($_POST['page'] OR $_GET['page'])
$querylist[] = "page=$page";
if ($_POST['regid'] OR $_GET['regid'])
$querylist[] = "regid=$regid";
if ($_POST['rguser'] OR $_GET['rguser'])
$querylist[] = "rguser=$rguser";
if (empty($querylist))
$Return_URL .= "?go=y";
else
$Return_URL .= "?" .implode("&", $querylist);
$ReturnURL = urlencode($Return_URL);
if ($Item_Columns == "D")
$LimitOfItems=$Item_Rows * 2;
else if ($Item_Columns == "T")
$LimitOfItems=$Item_Rows * 3;
else if ($Item_Columns == "G")
$LimitOfItems=$Item_Rows;
else
$LimitOfItems=$Item_Columns*$Item_Rows;
flush();
// Display site header if it exists
if ($Site_Header)
echo "<p>$Site_Header</p>";
?>