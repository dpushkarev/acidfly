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

if ($Submit == "Update Variables")
{
if (!$currency)
$currency="$";
$addlimitedqty = addslash_mq($limitedqty);
$addrelatedmsg = addslash_mq($relatedmsg);
$updvarquery = "UPDATE " .$DB_Prefix ."_vars SET MalsCart='$malscart', MalsServer='$malsserver', ";
$updvarquery .= "Currency='$currency', AllProductLink='$allproductlink', ItemColumns='$itemcolumns', ";
$updvarquery .= "ItemRows='$itemrows', ShowOOS='$showoos', RelatedMsg='$addrelatedmsg', ";
$updvarquery .= "OrderOfProduct='$orderofproduct', LimitedQty='$addlimitedqty', NavBar='$navbar', ";
$updvarquery .= "PageNumbers='$pagenumbers', SaleProductLink='$saleproductlink', ";
$updvarquery .= "NewProductLink='$newproductlink', FeaturedLink='$featuredlink', ";
if ($wishlist)
$updvarquery .= "WishList='$wishlist', ";
$updvarquery .= "ImageWidth='$imgwidth', ImageHeight='$imgheight', OptionNumber='$optionnumber', ";
$updvarquery .= "InventoryLimit='$inventorylimit', InventoryCheck='$inventorycheck', ";
$updvarquery .= "ShowDiscountPr='$showdiscountpr', SetOrdButton='$setordbutton', ";
$updvarquery .= "ItemImages='$itemimages', ShowCosts='$showcosts', RelatedImages='$relatedimages', ";
$updvarquery .= "MainCategories='$maincategories', OrderProcess='$orderprocess', ";
$updvarquery .= "ShowItemLimit='$showitemlimit' WHERE ID=1";
$updvarresult = mysql_query($updvarquery, $dblink) or die("Unable to select your variables. Please try again later.");
// Update wish list page if needed
if ($wishlist == "none")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='registry' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
else if (isset($wishlist))
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='registry' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
echo "<p align=\"center\">Your catalog variables have been updated.</p>";
}

$varquery = "SELECT * FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die("Unable to select your variables. Please try again later.");
$varnum = mysql_num_rows($varresult);
if ($varnum == 1)
{
$varrow = mysql_fetch_array($varresult);
$MalsCart=$varrow[MalsCart];
if ($varrow[Currency] == "")
$Currency="$";
else
$Currency=$varrow[Currency];
$AllProductLink=$varrow[AllProductLink];
if ($varrow[ItemRows] > 0)
$ItemRows=$varrow[ItemRows];
else
$ItemRows=3;
$ItemColumns=$varrow[ItemColumns];
$OptionNumber=$varrow[OptionNumber];
$OrderOfProduct=$varrow[OrderOfProduct];
$MalsServer=$varrow[MalsServer];
$ShowOOS=$varrow[ShowOOS];
$PageNumbers=$varrow[PageNumbers];
$LimitedQty=stripslashes($varrow[LimitedQty]);
$SaleProductLink=$varrow[SaleProductLink];
$NewProductLink=$varrow[NewProductLink];
$FeaturedLink=$varrow[FeaturedLink];
$ImgHeight=$varrow[ImageHeight];
$ImgWidth=$varrow[ImageWidth];
$InventoryLimit=$varrow[InventoryLimit];
$InventoryCheck=$varrow[InventoryCheck];
$ShowDiscountPr=$varrow[ShowDiscountPr];
$ShowItemLimit=$varrow[ShowItemLimit];
$RelatedMsg=stripslashes($varrow[RelatedMsg]);
$ItemImages=$varrow[ItemImages];
$ShowCosts=$varrow[ShowCosts];
$RelatedImages=$varrow[RelatedImages];
$SetOrdButton=$varrow[SetOrdButton];
$MainCategories=$varrow[MainCategories];
$OrderProcess=$varrow[OrderProcess];
$NavBar=$varrow[NavBar];
$WishList=$varrow[WishList];
}
?>

<form method="POST" name="Vars" action="catvars.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Enter your Mals-E cart account number (not password) here" class="fieldname">Mals-E Cart ID:</a></td>
<td vAlign="top" align="left">
<input type="text" name="malscart" value="<?php echo "$MalsCart"; ?>" size="7">
</td>
<td vAlign="top" align="right" nowrap>
<a title="Enter your currency symbol" class="fieldname">Currency:</a>
</td>
<td vAlign="top" align="left">
<input type="text" name="currency" value="<?php echo "$Currency"; ?>" size="7">
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Enter the server prefix (ie. www, ww4, ww7, etc.) of your Mals-E cart" class="fieldname">Mals-E Server:</a></td>
<td vAlign="top" align="left">
<input type="text" name="malsserver" value="<?php echo "$MalsServer"; ?>" size="7">
</td>
<td vAlign="top" align="right" nowrap>
<a title="Inventory messages will display only if the inventory quantity is less than this value" class="fieldname">Inv Limit:</a>
</td>
<td vAlign="top" align="left">
<input type="text" name="inventorylimit" value="<?php echo "$InventoryLimit"; ?>" size="7">
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
</td>
<td vAlign="top" align="left">
</td>
<td vAlign="top" align="right" nowrap>
</td>
<td vAlign="top" align="left">
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Enter the pixel height at you wish to display your thumbnail images, or leave blank to use the actual height of the thumbnail" class="fieldname">Image Height:</a>
</td>
<td vAlign="top" align="left">
<input type="text" name="imgheight" value="<?php echo "$ImgHeight"; ?>" size="7">
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want the system to stop someone from trying to order more than is in stock? Set Yes to limit them, No to allow backorders" class="fieldname">Inv Check:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="inventorycheck">
<?php
echo "<option ";
if ($InventoryCheck == "Yes")
echo "selected ";
echo "value=\"Yes\">Yes</option>";
echo "<option ";
if ($InventoryCheck == "No")
echo "selected ";
echo "value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Enter the pixel width at you wish to display your thumbnail images, or leave blank to use the actual width of the thumbnail" class="fieldname">Image Width:</a>
</td>
<td vAlign="top" align="left">
<input type="text" name="imgwidth" value="<?php echo "$ImgWidth"; ?>" size="7">
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do not display the item drop down box in the administration area (a good solution for larger stores)" class="fieldname">Item Select:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="showitemlimit">
<?php
if ($ShowItemLimit == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="How many thumbnail images would you like to display for each item?" class="fieldname">Item Images:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="itemimages">
<?php
echo "<option ";
if ($ItemImages == 1 OR !$ItemImages)
echo "selected ";
echo "value=\"1\">1</option>";
echo "<option ";
if ($ItemImages == 2)
echo "selected ";
echo "value=\"2\">2</option>";
echo "<option ";
if ($ItemImages == 3)
echo "selected ";
echo "value=\"3\">3</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display images next to each related product?" class="fieldname">Related
Images:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="relatedimages">
<?php
if ($RelatedImages == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="How many options (ie. colors, sizes, styles, etc.) do you want to add per options?" class="fieldname">Item Options:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="optionnumber">
<?php
for ($on = 0; $on <= 20; ++$on)
{
if ($on < 10)
$onval = "$on";
else
$onval = "$on";
echo "<option ";
if ($OptionNumber == "$on")
echo "selected ";
echo "value=\"$on\">$onval</option>";
}
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Select Yes to display page numbering (ie. 1 | 2 | 3 ...) or No to just display Previous and Next links" class="fieldname">Page Numbers:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="pagenumbers">
<?php if ($PageNumbers == "No") echo "<option selected value=\"No\">No</option><option value=\"Yes\">Yes</option>";
else echo "<option value=\"No\">No</option><option selected value=\"Yes\">Yes</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="How many rows of items do you want to display on one page?" class="fieldname">Item Rows:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="itemrows">
<?php
for ($ir = 1; $ir <= 30; ++$ir)
{
if ($ir < 10)
$irval = "$ir";
else
$irval = "$ir";
echo "<option ";
if ($ItemRows == "$ir")
echo "selected ";
echo "value=\"$ir\">$irval</option>";
}
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to set price discounts (ie. give lower prices for multiple item purchases)?" class="fieldname">Price Discount:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="showdiscountpr">
<?php
if ($ShowDiscountPr == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Show your products in a single column, double column, triple column, multiple column links, or a no-image grid listing" class="fieldname">Layout Style:</a></td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="itemcolumns">
<?php
echo "<option ";
if ($ItemColumns == "1")
echo "selected ";
echo "value=\"1\">Single Product Layout</option>";
echo "<option ";
if ($ItemColumns == "D")
echo "selected ";
echo "value=\"D\">Double Product Layout</option>";
echo "<option ";
if ($ItemColumns == "T")
echo "selected ";
echo "value=\"T\">Triple Product Layout</option>";
echo "<option ";
if ($ItemColumns == "2")
echo "selected ";
echo "value=\"2\">2 Column Layout</option>";
echo "<option ";
if ($ItemColumns == "3")
echo "selected ";
echo "value=\"3\">3 Column Layout</option>";
echo "<option ";
if ($ItemColumns == "4")
echo "selected ";
echo "value=\"4\">4 Column Layout</option>";
echo "<option ";
if ($ItemColumns == "5")
echo "selected ";
echo "value=\"5\">5 Column Layout</option>";
echo "<option ";
if ($ItemColumns == "6")
echo "selected ";
echo "value=\"6\">6 Column Layout</option>";
echo "<option ";
if ($ItemColumns == "G")
echo "selected ";
echo "value=\"G\">Grid Listing (No Images)</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Set this to Yes if you want to log your product cost and add a tax amount for items" class="fieldname">Tax/Costs:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="showcosts">
<?php
if ($ShowCosts == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="If you use a vertical link navigation, you can choose to display only the main categories (Vertical Display), main and sub categories (Vertical Subcategories), or only the main categories until a category is clicked - at which time the subcategories will appear (Expanded Categories). This field has no bearing on other navigation types." class="fieldname">Text Nav Bar:</a></td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="navbar">
<?php
echo "<option ";
if ($NavBar == "vertical")
echo "selected ";
echo "value=\"vertical\">Vertical Display</option>";
echo "<option ";
if ($NavBar == "subcategories")
echo "selected ";
echo "value=\"subcategories\">Subcategories</option>";
echo "<option ";
if ($NavBar == "expanded")
echo "selected ";
echo "value=\"expanded\">Expanded</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Select Yes to display out of stock items with an &quot;unavailable&quot; message. Select No if you don't want to list out of stock items" class="fieldname">Out of Stock:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="showoos">
<?php
if ($ShowOOS == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Would you like to list the cart in a standard web page, or embedded into an iframe on your site? If you choose an iframe, it will pull out of that iframe when customers finish their purchase" class="fieldname">Order Process:</a></td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="orderprocess">
<?php
echo "<option ";
if ($OrderProcess == "iframe" OR !$OrderProcess)
echo "selected ";
echo "value=\"iframe\">Inline Frame</option>";
echo "<option ";
if ($OrderProcess == "standard")
echo "selected ";
echo "value=\"standard\">Normal Window</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display a Featured Item link on your navigation section if featured items exist?" class="fieldname">Featured Link:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="featuredlink">
<?php
if ($FeaturedLink == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display the order button? You can choose to display it always, never, or only with items that have a base price over zero." class="fieldname">Order Button:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="setordbutton">
<?php
echo "<option ";
if ($SetOrdButton == "Yes")
echo "selected ";
echo "value=\"Yes\">Always Display</option>";
echo "<option ";
if ($SetOrdButton == "No")
echo "selected ";
echo "value=\"No\">Never Display</option>";
echo "<option ";
if ($SetOrdButton == "Prices")
echo "selected ";
echo "value=\"Prices\">Only Items w/ Prices</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display a New Item link on your navigation section?" class="fieldname">New Item Link:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="newproductlink">
<?php
if ($NewProductLink == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="When a category has subcategories, do you want to display those subcategories with links, display just the products for that category and all its subcats, or both the links and the products?" class="fieldname">Main Categories:</a></td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="maincategories">
<?php
echo "<option ";
if ($MainCategories == "Categories")
echo "selected ";
echo "value=\"Categories\">Categories</option>";
echo "<option ";
if ($MainCategories == "Products")
echo "selected ";
echo "value=\"Products\">Products</option>";
echo "<option ";
if ($MainCategories == "Both")
echo "selected ";
echo "value=\"Both\">Both</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display an All Item link on your navigation section?" class="fieldname">All Item Link:</a></td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="allproductlink">
<?php
if ($AllProductLink == "Yes")
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
else
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="How would you like to order your products?" class="fieldname">Product Order:</a></td>
<td vAlign="top" align="left">
<select style="width: 175px" size="1" name="orderofproduct">
<?php
echo "<option ";
if ($OrderOfProduct == "")
echo "selected ";
echo "value=\"\">Not Specified</option>";
echo "<option ";
if ($OrderOfProduct == "Item")
echo "selected ";
echo "value=\"Item\">Item A-Z</option>";
echo "<option ";
if ($OrderOfProduct == "Item DESC")
echo "selected ";
echo "value=\"Item DESC\">Item Z-A</option>";
echo "<option ";
if ($OrderOfProduct == "Catalog")
echo "selected ";
echo "value=\"Catalog\">Catalog #</option>";
echo "<option ";
if ($OrderOfProduct == "Units")
echo "selected ";
echo "value=\"Units\">Units</option>";
echo "<option ";
if ($OrderOfProduct == "Keywords, Item")
echo "selected ";
echo "value=\"Keywords, Item\">Keywords</option>";
echo "<option ";
if ($OrderOfProduct == "DateEdited")
echo "selected ";
echo "value=\"DateEdited\">Newest to Oldest</option>";
echo "<option ";
if ($OrderOfProduct == "DateEdited DESC")
echo "selected ";
echo "value=\"DateEdited DESC\">Oldest to Newest</option>";
echo "<option ";
if ($OrderOfProduct == "rand()")
echo "selected ";
echo "value=\"rand()\">Random</option>";
?>
</select>
</td>
<td vAlign="top" align="right" nowrap>
<a title="Do you want to display a Sale Item link on your navigation section if sale products exist?" class="fieldname">Sale Link:</a>
</td>
<td vAlign="top" align="left">
<select style="width: 60px" size="1" name="saleproductlink">
<?php
if ($SaleProductLink == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
</td>
<td vAlign="top" align="left">
</td>
<td vAlign="top" align="right" nowrap>
</td>
<td vAlign="top" align="left">
</td>
</tr>
<td>
<?php
$wlquery = "SELECT COUNT(ID) FROM " .$DB_Prefix ."_permissions WHERE SetPg='registry' AND GivePermission = 'No'";
$wlresult = mysql_query($wlquery, $dblink) or die ("Unable to select. Try again later.");
$wlrow = mysql_fetch_row($wlresult);
if ($wlrow[0] == 0 OR $set_master_key == "yes")
{
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="If you want to include a gift registry on your site, make a selection here. 
Choose a bridal or baby registry for a specific registry setup, or a gift registry for 
a general one that can be used for many occasions" class="fieldname">Registry Type:</a></td>
<td vAlign="top" align="left" colspan="3">
<select style="width: 175px" size="1" name="wishlist">
<?php
if ($WishList == "gift")
echo "<option selected value=\"gift\">Gift Registry</option>";
else
echo "<option value=\"gift\">Gift Registry</option>";
if ($WishList == "bridal")
echo "<option selected value=\"bridal\">Bridal Registry</option>";
else
echo "<option value=\"bridal\">Bridal Registry</option>";
if ($WishList == "baby")
echo "<option selected value=\"baby\">Baby Registry</option>";
else
echo "<option value=\"baby\">Baby Registry</option>";
if ($WishList == "none")
echo "<option selected value=\"none\">No Registry</option>";
else
echo "<option value=\"none\">No Registry</option>";
?>
</select>
</td>
</tr>
  <td>
<?php
}
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="If you want to display a message that will appear when there is a limited number of items in stock, enter it here" class="fieldname">Limited Qty:</a></td>
<td vAlign="top" align="left" colspan="3">
<textarea rows="2" name="limitedqty" cols="43"><?php echo "$LimitedQty"; ?></textarea>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="If you want to display a message that will appear when there are related products for an item, enter it here" class="fieldname">Related Msg:</a></td>
<td vAlign="top" align="left" colspan="3">
<textarea rows="2" name="relatedmsg" cols="43"><?php echo "$RelatedMsg"; ?></textarea>
</td>
</tr>
<tr>
<td vAlign="center" align="middle" colSpan="4">
<input type="submit" class="button" value="Update Variables" name="Submit">
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
