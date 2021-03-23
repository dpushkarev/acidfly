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

if ($_POST[Submit] == "Update Settings")
{
$updvarquery = "UPDATE " .$DB_Prefix ."_vars SET FontStyle='$fontstyle', FontSize='$fontsize', ";
$updvarquery .= "SmFontSize='$smfontsize', LgFontSize='$lgfontsize', HeaderSize='$headersize' WHERE ID=1";
$updvarresult = mysql_query($updvarquery, $dblink) or die("Unable to update. Please try again later.");

$colorquery = "UPDATE " .$DB_Prefix ."_colors SET BodyBkgd='$bodybkgd', BodyText='$bodytext', ";
$colorquery .= "AnchorLink='$anchorlink', AnchorHover='$anchorhover', HighlightText='$highlighttext', ";
$colorquery .= "HeaderColor='$headercolor', AccentColor='$accentcolor', SaleColor='$salecolor', ";
$colorquery .= "LineColor='$linecolor', ProductLink='$productlink', ProductHover='$producthover', ";
$colorquery .= "PageLink='$pagelink', PageHover='$pagehover', FeatureLink='$featurelink', ";
$colorquery .= "FeatureHover='$featurehover', EmailLink='$emaillink', EmailHover='$emailhover', ";
$colorquery .= "RelatedLink='$relatedlink', RelatedHover='$relatedhover', PopupLink='$popuplink', ";
$colorquery .= "PopupHover='$popuphover', DrillDownLink='$drilldownlink', DrillDownHover='$drilldownhover', ";
$colorquery .= "CartLink='$cartlink', CartHover='$carthover', CategoryLink='$categorylink', ";
$colorquery .= "CategoryHover='$categoryhover', SubCatLink='$subcatlink', SubCatHover='$subcathover', ";
$colorquery .= "EndCatLink='$endcatlink', EndCatHover='$endcathover', FormButtonText='$formbuttontext', ";
$colorquery .= "FormButtonBkgd='$formbuttonbkgd', FormButtonBorder='$formbuttonborder', ";
$colorquery .= "CatButtonText='$catbuttontext', CatButtonBkgd='$catbuttonbkgd', ";
$colorquery .= "CatButtonBorder='$catbuttonborder', CatActiveText='$catactivetext', ";
$colorquery .= "CatActiveBkgd='$catactivebkgd', CatHoverText='$cathovertext', ";
$colorquery .= "CatHoverBkgd='$cathoverbkgd' WHERE ID=1";
$colorresult = mysql_query($colorquery, $dblink) or die("Unable to update. Please try again later.");

echo "<p align=\"center\">Your system settings have been updated.</p>";
}

$varquery = "SELECT * FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die("Unable to select your variables. Please try again later.");
$varnum = mysql_num_rows($varresult);
if ($varnum == 1)
{
$varrow = mysql_fetch_array($varresult);
if ($varrow[FontStyle] == "")
$FontStyle="Arial";
else
$FontStyle=stripslashes($varrow[FontStyle]);
if ($varrow[SmFontSize])
$SmFontSize=$varrow[SmFontSize];
else
$SmFontSize=8;
if ($varrow[FontSize])
$FontSize=$varrow[FontSize];
else
$FontSize=10;
if ($varrow[LgFontSize])
$LgFontSize=$varrow[LgFontSize];
else
$LgFontSize=12;
if ($varrow[HeaderSize])
$HeaderSize=$varrow[HeaderSize];
else
$HeaderSize=24;
}

$colorquery = "SELECT * FROM " .$DB_Prefix ."_colors WHERE ID=1";
$colorresult = mysql_query($colorquery, $dblink) or die("Unable to select your coloriables. Please try again later.");
$colornum = mysql_num_rows($colorresult);
if ($colornum == 1)
{
$colorrow = mysql_fetch_array($colorresult);
$BodyBkgd = $colorrow[BodyBkgd];
$BodyText = $colorrow[BodyText];
$AnchorLink = $colorrow[AnchorLink];
$AnchorHover = $colorrow[AnchorHover];
$HighlightText = $colorrow[HighlightText];
$HeaderColor = $colorrow[HeaderColor];
$AccentColor = $colorrow[AccentColor];
$SaleColor = $colorrow[SaleColor];
$LineColor = $colorrow[LineColor];
$ProductLink = $colorrow[ProductLink];
$ProductHover = $colorrow[ProductHover];
$PageLink = $colorrow[PageLink];
$PageHover = $colorrow[PageHover];
$FeatureLink = $colorrow[FeatureLink];
$FeatureHover = $colorrow[FeatureHover];
$EmailLink = $colorrow[EmailLink];
$EmailHover = $colorrow[EmailHover];
$RelatedLink = $colorrow[RelatedLink];
$RelatedHover = $colorrow[RelatedHover];
$PopupLink = $colorrow[PopupLink];
$PopupHover = $colorrow[PopupHover];
$DrillDownLink = $colorrow[DrillDownLink];
$DrillDownHover = $colorrow[DrillDownHover];
$CartLink = $colorrow[CartLink];
$CartHover = $colorrow[CartHover];
$CategoryLink = $colorrow[CategoryLink];
$CategoryHover = $colorrow[CategoryHover];
$SubCatLink = $colorrow[SubCatLink];
$SubCatHover = $colorrow[SubCatHover];
$EndCatLink = $colorrow[EndCatLink];
$EndCatHover = $colorrow[EndCatHover];
$FormButtonText = $colorrow[FormButtonText];
$FormButtonBkgd = $colorrow[FormButtonBkgd];
$FormButtonBorder = $colorrow[FormButtonBorder];
$CatButtonText = $colorrow[CatButtonText];
$CatButtonBkgd = $colorrow[CatButtonBkgd];
$CatButtonBorder = $colorrow[CatButtonBorder];
$CatActiveText = $colorrow[CatActiveText];
$CatActiveBkgd = $colorrow[CatActiveBkgd];
$CatHoverText = $colorrow[CatHoverText];
$CatHoverBkgd = $colorrow[CatHoverBkgd];
}
?>

<form method="POST" name="Vars" action="settings.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td vAlign="top" align="right">
<a title="This font applies to all elements on the page" class="fieldname">Font Style:</a></td>
<td vAlign="top" align="left">
<input type="text" name="fontstyle" value="<?php echo "$FontStyle"; ?>"  size="12">
</td>
<td vAlign="top" align="right">
<a title="The main font size on the page" class="fieldname">Font Size:</a></td>
<td vAlign="top" align="left">
<select style="width: 70px" name="fontsize" size="1">
<?php
$fs = 6;
while ($fs <= 24)
{
echo "<option ";
if ($FontSize == $fs)
echo "selected ";
echo "value=\"$fs\">$fs pt</option>";
if ($fs < 12)
$fs = $fs+1;
else
$fs = $fs+2;
}
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The size of the smallest accent font" class="fieldname">Small Font:</a></td>
<td vAlign="top" align="left">
<select style="width: 70px" name="smfontsize" size="1">
<?php
$sfs = 6;
while ($sfs <= 24)
{
echo "<option ";
if ($SmFontSize == $sfs)
echo "selected ";
echo "value=\"$sfs\">$sfs pt</option>";
if ($sfs < 12)
$sfs = $sfs+1;
else
$sfs = $sfs+2;
}
?>
</select>
</td>
<td vAlign="top" align="right">
<a title="The size of the larger page name font" class="fieldname">Large Font:</a></td>
<td vAlign="top" align="left">
<select style="width: 70px" name="lgfontsize" size="1">
<?php
$lfs = 8;
while ($lfs <= 24)
{
echo "<option ";
if ($LgFontSize == $lfs)
echo "selected ";
echo "value=\"$lfs\">$lfs pt</option>";
if ($lfs < 12)
$lfs = $lfs+1;
else
$lfs = $lfs+2;
}
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The size of the company name header font" class="fieldname">Header Font:</a></td>
<td vAlign="top" align="left">
<select style="width: 70px" name="headersize" size="1">
<?php
$hs = 12;
while ($hs <= 40)
{
echo "<option ";
if ($HeaderSize == $hs)
echo "selected ";
echo "value=\"$hs\">$hs pt</option>";
$hs = $hs+2;
}
?>
</select>
</td>
<td vAlign="top" align="right">
<a title="The color of the company name header font" class="fieldname">Header Color:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"headercolor\" value=\"$HeaderColor\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=headercolor\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=headercolor', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>";
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the page background" class="fieldname">Body Bkgd:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"bodybkgd\" value=\"$BodyBkgd\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=bodybkgd\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=bodybkgd', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of the main text on the page" class="fieldname">Body Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"bodytext\" value=\"$BodyText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=bodytext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=bodytext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the main links on the page" class="fieldname">Anchor Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"anchorlink\" value=\"$AnchorLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=anchorlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=anchorlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the main links on the page (leave blank for no hover)" class="fieldname">Anchor Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"anchorhover\" value=\"$AnchorHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=anchorhover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=anchorhover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the horizontal lines between products (leave blank for no lines)" class="fieldname">Line Color:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"linecolor\" value=\"$LineColor\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=linecolor\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=linecolor', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of sale prices and error messages" class="fieldname">Sale Color:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"salecolor\" value=\"$SaleColor\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=salecolor\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=salecolor', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of tables with accents, such as the events calendar" class="fieldname">Accent Color:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"accentcolor\" value=\"$AccentColor\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=accentcolor\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=accentcolor', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of highlighted text" class="fieldname">Highlight Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"highlighttext\" value=\"$HighlightText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=highlighttext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=highlighttext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of page navigation links" class="fieldname">Page Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"pagelink\" value=\"$PageLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=pagelink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=pagelink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of page navigation links (leave blank for no hover)" class="fieldname">Page Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"pagehover\" value=\"$PageHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=pagehover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=pagehover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of links within product layouts" class="fieldname">Product Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"productlink\" value=\"$ProductLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=productlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=productlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of links within product layouts (leave blank for no hover)" class="fieldname">Product Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"producthover\" value=\"$ProductHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=producthover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=producthover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of featured product links" class="fieldname">Feature Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"featurelink\" value=\"$FeatureLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=featurelink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=featurelink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of featured product links (leave blank for no hover)" class="fieldname">Feature Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"featurehover\" value=\"$FeatureHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=featurehover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=featurehover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of email a friend links" class="fieldname">Email Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"emaillink\" value=\"$EmailLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=emaillink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=emaillink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of email a friend links (leave blank for no hover)" class="fieldname">Email Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"emailhover\" value=\"$EmailHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=emailhover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=emailhover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of related product links" class="fieldname">Related Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"relatedlink\" value=\"$RelatedLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=relatedlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=relatedlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of related product links (leave blank for no hover)" class="fieldname">Related Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"relatedhover\" value=\"$RelatedHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=relatedhover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=relatedhover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of pop up page links" class="fieldname">Pop Up Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"popuplink\" value=\"$PopupLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=popuplink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=popuplink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of pop up page links (leave blank for no hover)" class="fieldname">Pop Up Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"popuphover\" value=\"$PopupHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=popuphover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=popuphover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the drill down category navigation links" class="fieldname">Drill Down Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"drilldownlink\" value=\"$DrillDownLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=drilldownlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=drilldownlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the drill down category navigation links (leave blank for no hover)" class="fieldname">Drill Down Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"drilldownhover\" value=\"$DrillDownHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=drilldownhover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=drilldownhover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the view cart link" class="fieldname">Cart Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"cartlink\" value=\"$CartLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=cartlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=cartlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the view cart link (leave blank for no hover)" class="fieldname">Cart Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"carthover\" value=\"$CartHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=carthover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=carthover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?></td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the main category links for vertical or horizontal text links" class="fieldname">Category Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"categorylink\" value=\"$CategoryLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=categorylink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=categorylink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the main category links for vertical or horizontal text links (leave blank for no hover)" class="fieldname">Category Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"categoryhover\" value=\"$CategoryHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=categoryhover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=categoryhover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the sub category links for vertical or horizontal text links" class="fieldname">Subcat Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"subcatlink\" value=\"$SubCatLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=subcatlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=subcatlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the sub category links for vertical or horizontal text links (leave blank for no hover)" class="fieldname">Subcat Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"subcathover\" value=\"$SubCatHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=subcathover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=subcathover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the last end category links for vertical or horizontal text links" class="fieldname">End Cat Link:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"endcatlink\" value=\"$EndCatLink\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=endcatlink\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=endcatlink', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the last end category links for vertical or horizontal text links (leave blank for no hover)" class="fieldname">End Cat Hover:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"endcathover\" value=\"$EndCatHover\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=endcathover\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=endcathover', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the text if using tab, button or bar navigation styles" class="fieldname">Nav Button Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"catbuttontext\" value=\"$CatButtonText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=catbuttontext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=catbuttontext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of the background if using tab, button or bar navigation styles" class="fieldname">Nav Button Bkgd:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"catbuttonbkgd\" value=\"$CatButtonBkgd\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=catbuttonbkgd\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=catbuttonbkgd', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the text of the active tab, button or bar" class="fieldname">Active Nav Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"catactivetext\" value=\"$CatActiveText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=catactivetext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=catactivetext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of the background of the active tab, button or bar" class="fieldname">Active Nav Bkgd:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"catactivebkgd\" value=\"$CatActiveBkgd\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=catactivebkgd\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=catactivebkgd', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The hover color of the text if using tab, button or bar navigation styles (leave blank for no hover)" class="fieldname">Hover Nav Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"cathovertext\" value=\"$CatHoverText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=cathovertext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=cathovertext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The hover color of the background if using tab, button or bar navigation styles (leave blank for no hover)" class="fieldname">Hover Nav Bkgd:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"cathoverbkgd\" value=\"$CatHoverBkgd\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=cathoverbkgd\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=cathoverbkgd', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the border if using tab, button or bar navigation styles" class="fieldname">Nav
Border:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"catbuttonborder\" value=\"$CatButtonBorder\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=catbuttonborder\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=catbuttonborder', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of the border for form buttons like order or search buttons (leave blank to use standard form buttons)" class="fieldname">Form Button
Border:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"formbuttonborder\" value=\"$FormButtonBorder\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=formbuttonborder\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=formbuttonborder', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<a title="The color of the text for form buttons like order or search buttons (leave blank to use standard form buttons)" class="fieldname">Form Button Text:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"formbuttontext\" value=\"$FormButtonText\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=formbuttontext\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=formbuttontext', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
<td vAlign="top" align="right">
<a title="The color of the background for form buttons like order or search buttons (leave blank to use standard form buttons)" class="fieldname">Form Button Bkgd:</a></td>
<td vAlign="top" align="left">
<?php 
echo "<input type=\"text\" name=\"formbuttonbkgd\" value=\"$FormButtonBkgd\" size=\"8\"> ";
echo "<a href=\"includes/colors.php?field=formbuttonbkgd\" target=\"_blank\" ";
echo "onClick=\"PopUp=window.open('includes/colors.php?field=formbuttonbkgd', 'NewWin', ";
echo "'resizable=yes,scrollbars=no,status=yes,width=300,height=325,left=0,top=0,screenX=0,screenY=0'); ";
echo "PopUp.focus(); return false;\">Select</a>"; 
?>
</td>
</tr>
<tr>
<td vAlign="center" align="middle" colSpan="4">
<input type="submit" class="button" value="Update Settings" name="Submit">
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
