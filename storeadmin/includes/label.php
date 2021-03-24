<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body style="margin:20">
<?php
include("open.php");
if ($ordid)
{
$ordquery = "SELECT * FROM " .$DB_Prefix ."_orders WHERE OrderNumber='$ordid'";
$ordresult = mysqli_query($dblink, $ordquery) or die ("Unable to select. Try again later.");
$ordnum = mysqli_num_rows($ordresult);
}

if ($ordnum == 1)
{
$ordrow = mysqli_fetch_array($ordresult);
$invname = stripslashes($ordrow[InvName]);
$invcompany = stripslashes($ordrow[InvCompany]);
$invaddress = stripslashes($ordrow[InvAddress]);
$invcity = stripslashes($ordrow[InvCity]);
$invstate = stripslashes($ordrow[InvState]);
$invzip = $ordrow[InvZip];
$invcountry = stripslashes($ordrow[InvCountry]);
if ($ordrow[ShipName])
$shipname = stripslashes($ordrow[ShipName]);
else
$shipname = $invname;
if ($ordrow[ShipAddress])
$shipaddress = stripslashes($ordrow[ShipAddress]);
else
$shipaddress = $invaddress;
if ($ordrow[ShipCity])
$shipcity = stripslashes($ordrow[ShipCity]);
else
$shipcity = $invcity;
if ($ordrow[ShipState])
$shipstate = stripslashes($ordrow[ShipState]);
else
$shipstate = $invstate;
if ($ordrow[ShipZip])
$shipzip = $ordrow[ShipZip];
else
$shipzip = $invzip;
if ($ordrow[ShipCountry])
$shipcountry = stripslashes($ordrow[ShipCountry]);
else
$shipcountry = $invcountry;
?>
<table border="0" cellpadding="50" cellspacing="0" width="288" height="180" style="border: 1 solid #C4BEAC">
<tr>
<td valign="middle" align="left" class="largerfont">
<?php
echo "$shipname";
echo "<br>$shipaddress";
echo "<br>$shipcity, $shipstate $shipzip";
echo "<br>$shipcountry";
?>
</td>
</tr>
</table>

<?php
}
else
echo "<p align=\"center\">No Records Found</a>";
?>
</body>

</html>
