<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$lessoptct = $optcount - 1;

// If option is a memo field, display it as such
if ($optrow[4] == "Memo Field")
$display_options .= "<textarea rows=\"3\" name=\"optval$optcount\" cols=\"30\">$attributes</textarea>";

// If option is a check box, display it and show value or price
else if ($optrow[4] == "Check Box")
{
$display_options .= "<input type=\"checkbox\" ";
if ("$optionname - Yes" == $expopts[$lessoptct])
$display_options .= "checked ";
$display_options .= "name=\"optval$optcount\" value=\"$attributes\">";
if ($attributes > 0)
$display_options .= " (add $Currency$attributes)";
else if ($attributes != "Yes" AND $attributes != "yes" AND $attributes != "YES")
$display_options .= " $attributes";
}

// If option is a drop down box or radio button, create options
else if ($optrow[4] == "Drop Down" OR $optrow[4] == "Radio Button")
{
$set_dropdown = "";
$set_radiobutton = "";
$optionarray = explode("~", $attributes);
$chkatts = strpos($attributes, ":");
$totaloptions = count($optionarray);
for ($count = 0; $count < $totaloptions; ++$count)
{
$attribute = explode(":", $optionarray[$count]);
$attributename = stripslashes($attribute[0]);
$attributeprice = number_format($attribute[1], 2);

// Set Prices
if ($optcount == 1 AND $chkatts)
{
$newattprice = number_format($unitprice + $attribute[1], 2);
$attributedisplay = $attributename ." $Currency$newattprice";
$priceset = "no";
}
else if ($attribute[1] > 0)
$attributedisplay = $attributename ." add $Currency$attributeprice";
else
$attributedisplay = $attributename;

// Set Units
if ($attribute[2] AND $attribute[1])
$attributeinfo = "$attributename:$attributeprice:$attribute[2]";
else if ($attribute[1])
$attributeinfo = "$attributename:$attributeprice:$attribute[2]";
else if ($attribute[2])
$attributeinfo = "$attributename::$attribute[2]";
else
$attributeinfo = $attributename;

// Check inventory quantities
if ($optcount == 1)
{
$attquery = "SELECT Quantity FROM " .$DB_Prefix ."_inventory WHERE ProductID = '$product_id' AND Attribute = '$attribute[0]'";
$attresult = mysqli_query($dblink, $attquery) or die ("Unable to access database.");
$attnum = mysqli_num_rows($attresult);
if ($attnum == 1)
{
$show_item_inv = "no";
$attrow = mysqli_fetch_array($attresult);
if ($attrow[0] <= $inventorylimit)
{
if ($attrow[0] > 0)
$attributedisplay .= " ($attrow[0] in stock)";
else if ($attrow[0] <= 0 AND $inventorycheck == "No")
$attributedisplay .= " (out of stock - on backorder)";
else if ($attrow[0] <= 0 AND $inventorycheck == "Yes")
$attributedisplay = "";
}
}
}

// Create attributes
if ($attributedisplay AND "$optionname - $attributename" == $expopts[$lessoptct])
{
$set_list = " <input type=\"hidden\" name=\"optval$optcount\" value=\"$attributeinfo\">";
$set_list .= "$attributedisplay";
}
else if ($attributedisplay AND $optrow[4] == "Drop Down")
$set_dropdown .= "<" ."option value=\"$attributeinfo\"" .">$attributedisplay<" ."/option" .">";
else if ($attributedisplay)
$set_radiobutton .= "<br><input type=\"radio\" name=\"optval$optcount\" value=\"$attributeinfo\">$attributedisplay";
}

// After loop, create display options
if ($set_list)
{
$display_options .= "$set_list";
$set_list = "";
}
else if ($optrow[4] == "Drop Down" AND $set_dropdown)
{
$display_options .= "<select size=\"1\" name=\"optval$optcount\">";
$display_options .= "$set_dropdown";
$display_options .= "</select>";
}
else if ($optrow[4] == "Radio Button" AND $set_radiobutton)
{
$chkradio = substr($set_radiobutton, 11, strlen($set_radiobutton));
$set_radiobutton = "<input checked " .$chkradio;
$display_options .= "$set_radiobutton";
}

}

// If option is other, display as a text field
else
$display_options .= "<input type=\"text\" name=\"optval$optcount\" value=\"$attributes\" size=\"20\">";
?>
