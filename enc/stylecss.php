<script language="php">
if (isset($_REQUEST[changetheme]) AND !ctype_alnum(str_replace(array("_", "-", ".htm"), "", $_REQUEST[changetheme])))
die("Invalid theme");
header("Content-Type: text/css");
</script>
<!-- 
<script language="php">
require_once("../stconfig.php");
$varquery = "SELECT * FROM " .$DB_Prefix ."_vars WHERE ID='1'\r\n";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select your system variables. Try again later.");
$varrow = mysql_fetch_array($varresult);
$Font_Style=stripslashes($varrow[FontStyle]);
if ($varrow[SmFontSize])
$smfontsize=$varrow[SmFontSize];
else
$smfontsize=8;
if ($varrow[FontSize])
$fontsize=$varrow[FontSize];
else
$fontsize=10;
if ($varrow[LgFontSize])
$lgfontsize=$varrow[LgFontSize];
else
$lgfontsize=12;
if ($varrow[HeaderSize])
$headersize=$varrow[HeaderSize];
else
$headersize=24;

// Sets Colors from template sheet
if ($changetheme)
{
$newtheme = str_replace("htm", "php", $changetheme);
include ("../template/$newtheme");
}

// Sets colors from database
else
{
$colorquery = "SELECT * FROM " .$DB_Prefix ."_colors WHERE ID='1'\r\n";
$colorresult = mysql_query($colorquery ) or die ("Unable to select your system variables. Try again later.");
$colorrow = mysql_fetch_array($colorresult );
if ($colorrow[BodyBkgd]) $BodyBkgd = $colorrow[BodyBkgd]; else $BodyBkgd = "#FFFFFF";
if ($colorrow[BodyText]) $BodyText = $colorrow[BodyText]; else $BodyText = "#000000";
if ($colorrow[AnchorLink]) $AnchorLink = $colorrow[AnchorLink]; else $AnchorLink = "#000000";
if ($colorrow[AnchorHover]) $AnchorHover = $colorrow[AnchorHover]; else $AnchorHover = $AnchorLink;
if ($colorrow[HighlightText]) $HighlightText = $colorrow[HighlightText]; else $HighlightText = "#000000";
if ($colorrow[HeaderColor]) $HeaderColor = $colorrow[HeaderColor]; else $HeaderColor = $BodyText;
if ($colorrow[AccentColor]) $AccentColor = $colorrow[AccentColor]; else $AccentColor = $HighlightColor;
if ($colorrow[SaleColor]) $SaleColor = $colorrow[SaleColor]; else $SaleColor = $HighlightColor;
$LineColor = $colorrow[LineColor];
if ($colorrow[ProductLink]) $ProductLink = $colorrow[ProductLink]; else $ProductLink = "#000000";
if ($colorrow[ProductHover]) $ProductHover = $colorrow[ProductHover]; else $ProductHover = $ProductLink;
if ($colorrow[PageLink]) $PageLink = $colorrow[PageLink]; else $PageLink = $BodyText;
if ($colorrow[PageHover]) $PageHover = $colorrow[PageHover]; else $PageHover = $PageLink;
if ($colorrow[FeatureLink]) $FeatureLink = $colorrow[FeatureLink]; else $FeatureLink = $ProductLink;
if ($colorrow[FeatureHover]) $FeatureHover = $colorrow[FeatureHover]; else $FeatureHover = $FeatureLink;
if ($colorrow[EmailLink]) $EmailLink = $colorrow[EmailLink]; else $EmailLink = $ProductLink;
if ($colorrow[EmailHover]) $EmailHover = $colorrow[EmailHover]; else $EmailHover = $EmailLink;
if ($colorrow[RelatedLink]) $RelatedLink = $colorrow[RelatedLink]; else $RelatedLink = $ProductLink;
if ($colorrow[RelatedHover]) $RelatedHover = $colorrow[RelatedHover]; else $RelatedHover = $RelatedLink;
if ($colorrow[PopupLink]) $PopupLink = $colorrow[PopupLink]; else $PopupLink = $ProductLink;
if ($colorrow[PopupHover]) $PopupHover = $colorrow[PopupHover]; else $PopupHover = $PopupLink;
if ($colorrow[CategoryLink]) $CategoryLink = $colorrow[CategoryLink]; else $CategoryLink = "#000000";
if ($colorrow[CategoryHover]) $CategoryHover = $colorrow[CategoryHover]; else $CategoryHover = $CategoryLink;
if ($colorrow[SubCatLink]) $SubCatLink = $colorrow[SubCatLink]; else $SubCatLink = $CategoryLink;
if ($colorrow[SubCatHover]) $SubCatHover = $colorrow[SubCatHover]; else $SubCatHover = $SubCatLink;
if ($colorrow[EndCatLink]) $EndCatLink = $colorrow[EndCatLink]; else $EndCatLink = $CategoryLink;
if ($colorrow[EndCatHover]) $EndCatHover = $colorrow[EndCatHover]; else $EndCatHover = $EndCatLink;
if ($colorrow[DrillDownLink]) $DrillDownLink = $colorrow[DrillDownLink]; else $DrillDownLink = $CategoryLink;
if ($colorrow[DrillDownHover]) $DrillDownHover = $colorrow[DrillDownHover]; else $DrillDownHover = $DrillDownLink;
if ($colorrow[CartLink]) $CartLink = $colorrow[CartLink]; else $CartLink = "#000000";
if ($colorrow[CartHover]) $CartHover = $colorrow[CartHover]; else $CartHover = $CartLink;
if ($colorrow[CatButtonText]) $CatButtonText = $colorrow[CatButtonText]; else $CatButtonText = "#000000";
if ($colorrow[CatButtonBkgd]) $CatButtonBkgd = $colorrow[CatButtonBkgd]; else $CatButtonBkgd = "#CCCCCC";
if ($colorrow[CatButtonBorder]) $CatButtonBorder = $colorrow[CatButtonBorder]; else $CatButtonBorder = "#000000";
if ($colorrow[CatActiveText]) $CatActiveText = $colorrow[CatActiveText]; else $CatActiveText = "#000000";
if ($colorrow[CatActiveBkgd]) $CatActiveBkgd = $colorrow[CatActiveBkgd]; else $CatActiveBkgd = "#CCCCCC";
if ($colorrow[CatHoverText]) $CatHoverText = $colorrow[CatHoverText]; else $CatHoverText = "#000000";
if ($colorrow[CatHoverBkgd]) $CatHoverBkgd = $colorrow[CatHoverBkgd]; else $CatHoverBkgd = "#CCCCCC";
$FormButtonText = $colorrow[FormButtonText];
$FormButtonBkgd = $colorrow[FormButtonBkgd];
$FormButtonBorder = $colorrow[FormButtonBorder];
}

$styleinfo = "/* BODY FONT AND SIZE */\r\n";
$styleinfo .= "body { \r\n";
$styleinfo .= "font-family: $Font_Style; \r\n";
$styleinfo .= "font-size: $fontsize" ."pt; \r\n";
$styleinfo .= "color: $BodyText; \r\n";
$styleinfo .= "background-color: $BodyBkgd; \r\n";
$styleinfo .= "margin: 0px; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* ANCHOR STYLES */\r\n";
$styleinfo .= "a { \r\n";
$styleinfo .= "color: $AnchorLink; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "a:hover { \r\n";
$styleinfo .= "color: $AnchorHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* TABLE DATA CELL FONT AND SIZE */\r\n";
$styleinfo .= "td { \r\n";
$styleinfo .= "font-family: $Font_Style; \r\n";
$styleinfo .= "font-size: $fontsize" ."pt; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* SMALL FONT SIZE */\r\n";
$styleinfo .= ".smfont { \r\n";
$styleinfo .= "font-size: $smfontsize" ."pt; \r\n";
$styleinfo .= "text-decoration: none; \r\n"; 
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* LARGE FONT SIZE */\r\n";
$styleinfo .= ".lgfont { \r\n";
$styleinfo .= "font-size: $lgfontsize" ."pt; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* BOLD FONT */\r\n";
$styleinfo .= ".boldtext { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* ACCENTED FONT */\r\n";
$styleinfo .= ".accent { \r\n";
$styleinfo .= "font-style: italic; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* HIGHLIGHT TEXT */\r\n";
$styleinfo .= ".highlighttext { \r\n";
$styleinfo .= "color: $HighlightText; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* ACCENT TEXT */\r\n";
$styleinfo .= ".accenttext { \r\n";
$styleinfo .= "color: $AccentColor; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* HEADER FONT COLOR */\r\n";
$styleinfo .= ".header { \r\n";
$styleinfo .= "color: $HeaderColor; \r\n";
$styleinfo .= "font-weight : bold; \r\n";
$styleinfo .= "text-align : center; \r\n";
$styleinfo .= "font-size : $headersize" ."px; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* CELL COLOR FOR ACCENTS */\r\n";
$styleinfo .= ".accentcell { \r\n";
$styleinfo .= "background-color: $AccentColor; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* TABLE BORDER FOR ACCENTS */\r\n";
$styleinfo .= ".accenttable { \r\n";
$styleinfo .= "border: 1px solid $AccentColor; \r\n";
$styleinfo .= "border-collapse: collapse; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* FORMATTING FOR ORDER/SEARCH BUTTONS AND LINKS */\r\n";
if ($FormButtonText AND $FormButtonBkgd)
{
if ($FormButtonBorder == "")
$FormButtonBorder = $FormButtonText;
$styleinfo .= ".formbutton { \r\n";
$styleinfo .= "color: $FormButtonText; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "background-color: $FormButtonBkgd; \r\n";
$styleinfo .= "border-color: $FormButtonBorder; \r\n";
$styleinfo .= "border: 1px solid; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= ".orderlink { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $FormButtonText; \r\n";
$styleinfo .= "background-color: $FormButtonBkgd; \r\n";
$styleinfo .= "border-color: $FormButtonBorder; \r\n";
$styleinfo .= "border: 1px solid; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "padding: 3; \r\n";
$styleinfo .= "}\r\n\r\n";
}

$styleinfo .= "/* SALE AND ERROR COLOR */\r\n";
$styleinfo .= ".salecolor { \r\n";
$styleinfo .= "color: $SaleColor; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* HORIZONTAL LINE LINK */\r\n";
$styleinfo .= ".linecolor { \r\n";
$styleinfo .= "color: $LineColor; \r\n";
$styleinfo .= "height: 1px; \r\n";
$styleinfo .= "width: 100%; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* LINE TABLE */\r\n";
$styleinfo .= ".linetable { \r\n";
$styleinfo .= "border: 1 solid $LineColor; \r\n";
$styleinfo .= "border-collapse: collapse; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* LINE TABLE CELL */\r\n";
$styleinfo .= ".linecell { \r\n";
$styleinfo .= "border: 1 solid $LineColor; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* PRODUCT LINK */\r\n";
$styleinfo .= ".itemcolor { \r\n";
$styleinfo .= "color: $ProductLink; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.itemcolor:hover { \r\n";
$styleinfo .= "color: $ProductHover; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* PAGE LINK */\r\n";
$styleinfo .= ".pagelinkcolor { \r\n";
$styleinfo .= "color: $PageLink; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.pagelinkcolor:hover { \r\n";
$styleinfo .= "color: $PageHover; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* FEATURED ITEMS */\r\n";
$styleinfo .= ".featurecolor { \r\n";
$styleinfo .= "color: $FeatureLink; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.featurecolor:hover { \r\n";
$styleinfo .= "color: $FeatureHover; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* EMAIL FRIEND LINK */\r\n";
$styleinfo .= ".emailcolor { \r\n";
$styleinfo .= "color: $EmailLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.emailcolor:hover { \r\n";
$styleinfo .= "color: $EmailHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* RELATED ITEM LINK */\r\n";
$styleinfo .= ".relatedcolor { \r\n";
$styleinfo .= "color: $RelatedLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.relatedcolor:hover { \r\n";
$styleinfo .= "color: $RelatedHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* POP UP LINK */\r\n";
$styleinfo .= ".popupcolor { \r\n";
$styleinfo .= "color: $PopupLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.popupcolor:hover { \r\n";
$styleinfo .= "color: $PopupHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* DRILL DOWN LINK */\r\n";
$styleinfo .= ".drilldown { \r\n";
$styleinfo .= "color: $DrillDownLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.drilldown:hover { \r\n";
$styleinfo .= "color: $DrillDownHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* VIEW CART LINK */\r\n";
$styleinfo .= ".cartcolor { \r\n";
$styleinfo .= "color: $CartLink; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.cartcolor:hover { \r\n";
$styleinfo .= "color: $CartHover; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* MAIN CATEGORY LINK */\r\n";
$styleinfo .= ".catcolor { \r\n";
$styleinfo .= "color: $CategoryLink; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.catcolor:hover { \r\n";
$styleinfo .= "color: $CategoryHover; \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* SUBCATEGORY LINK */\r\n";
$styleinfo .= ".subcatcolor { \r\n";
$styleinfo .= "color: $SubCatLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.subcatcolor:hover { \r\n";
$styleinfo .= "color: $SubCatHover; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* END CATEGORY LINK */\r\n";
$styleinfo .= ".endcatcolor { \r\n";
$styleinfo .= "color: $EndCatLink; \r\n";
$styleinfo .= "}\r\n\r\n";
$styleinfo .= "a.endcatcolor:hover { \r\n";
$styleinfo .= "color: $EndCatHover; \r\n";
$styleinfo .= "}\r\n\r\n";
  
$styleinfo .= "/* PARAGRAPH LAYOUT FOR CATALOG PRODUCTS */\r\n";
$styleinfo .= ".p_layout { \r\n";
$styleinfo .= "margin-top: 10; margin-bottom: 10; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* BUTTON SETUP FOR USE WITH %PAGES_TABLE% OR %NAVBAR_TABLE% */\r\n";
$styleinfo .= "td.buttoncell { \r\n";
$styleinfo .= "border: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.buttoncell a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.buttoncell a:hover, td.buttonactive a:hover { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatHoverText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatHoverBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.buttonactive { \r\n";
$styleinfo .= "border: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.buttonactive a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatActiveText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* TAB CELL STYLE FOR USE WITH %PAGES_TABS% OR %NAVBAR_TABS% */\r\n";
$styleinfo .= "td.tabcell { \r\n";
$styleinfo .= "border-top: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "border-left: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "border-right: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.tabcell a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.tabcell a:hover, td.tabactive a:hover { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatHoverText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatHoverBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.tabactive { \r\n";
$styleinfo .= "border-top: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "border-left: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "border-right: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "color: $CatActiveText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.tabactive a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatActiveText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "/* BAR CELL STYLE FOR USE WITH %PAGES_BAR% OR %NAVBAR_BAR% */\r\n";
$styleinfo .= "table.bar { \r\n";
$styleinfo .= "border-top: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "border-bottom: 1 solid $CatButtonBorder; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.barcell { \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.barcell a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatButtonText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatButtonBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.barcell a:hover, td.baractive a:hover { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatHoverText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatHoverBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.baractive { \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "color: $CatActiveText; \r\n";
$styleinfo .= "}\r\n\r\n";

$styleinfo .= "td.baractive a { \r\n";
$styleinfo .= "font-weight: bold; \r\n";
$styleinfo .= "color: $CatActiveText; \r\n";
$styleinfo .= "text-decoration: none; \r\n";
$styleinfo .= "background-color: $CatActiveBkgd; \r\n";
$styleinfo .= "display: block; \r\n";
$styleinfo .= "}\r\n\r\n";

if ($showstyle == "yes")
{
$styleinfo = str_replace("\r\n", "<br>", $styleinfo);
echo "&lt;style type=&quot;text/css&quot;&gt;<br>\r\n";
echo "$styleinfo\r\n";
echo "&lt;/style&gt;\r\n";
}
else
echo "$styleinfo\r\n";
</script>
// -->
