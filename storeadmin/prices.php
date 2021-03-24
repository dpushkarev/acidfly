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

if ($Submit == "Update Prices")
{
$pricequery = "SELECT * FROM " .$DB_Prefix ."_prices";
$priceresult = mysqli_query($dblink, $pricequery) or die ("Unable to select. Try again later.");
// Add prices
if (mysqli_num_rows($priceresult) == 0)
{
for ($i = 1; $i <= 10; ++$i)
{
$insquery = "INSERT INTO " .$DB_Prefix ."_prices (StartPrice, EndPrice) VALUES ('$start[$i]', '$end[$i]')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
}
}
// Edit prices
else
{
for ($i = 1; $i <= 10; ++$i)
{
$updquery = "UPDATE " .$DB_Prefix ."_prices SET StartPrice='$start[$i]', EndPrice='$end[$i]' WHERE ID='$id[$i]'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
}
}

// Get currency
$varquery = "SELECT Currency FROM " .$DB_Prefix ."_vars";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($varresult) == 1)
{
$varrow = mysqli_fetch_row($varresult);
$Currency="$varrow[0]";
}
else
$Currency="$";
?>

<form method="POST" action="prices.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td colspan="2" align="center" class="fieldname">Update Price Search</td>
</tr>
<tr>
<td valign="middle" align="right" class="accent">Start Price &nbsp; </td>
<td valign="middle" align="left" class="accent"> &nbsp; End Price</td>
</tr>
<?php
$pricequery = "SELECT * FROM " .$DB_Prefix ."_prices";
$priceresult = mysqli_query($dblink, $pricequery) or die ("Unable to select. Try again later.");
for ($i = 1; $pricerow = mysqli_fetch_row($priceresult),$i <= 10; ++$i)
{
if (($pricerow[1] == 0) AND ($pricerow[2] == 0))
{
$startprice = "";
$endprice = "";
}
else
{
$startprice = $pricerow[1];
$endprice = $pricerow[2];
}
echo "<tr>";
echo "<td valign=\"middle\" align=\"right\">$Currency<input type=\"text\" name=\"start[$i]\" value=\"$startprice\" size=\"10\"></td>";
echo "<td valign=\"middle\" align=\"left\">$Currency<input type=\"text\" name=\"end[$i]\" value=\"$endprice\" size=\"10\">";
echo "<input type=\"hidden\" name=\"id[$i]\" value=\"$pricerow[0]\"></td>";
echo "</tr>";
}
?>
<tr>
<td valign="middle" align="center" colspan="2">
<input type="submit" class="button" value="Update Prices" name="Submit">
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
