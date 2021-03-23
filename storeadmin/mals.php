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
?>
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Mals-E Shopping Cart</td>
</tr>
<?php
if ($mode == "gc")
{
?>
<tr>
<td valign="middle" align="left">
<?php
$chkquery = "SELECT * FROM " .$DB_Prefix ."_certificates";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
$chknum = mysql_num_rows($chkresult);
if ($chknum == 1)
{
$chkrow = mysql_fetch_array($chkresult);
$amt[1] = $chkrow[Amount1];
$amt[2] = $chkrow[Amount2];
$amt[3] = $chkrow[Amount3];
$amt[4] = $chkrow[Amount4];
?>
To finish the gift certificate setup, please follow the instructions below:
<ol>
<li>Log in to your Mals-E administration area below.</li>
<li>Select "Cart Set-Up".</li>
<li>Select "Vouchers".</li>
<li>Under "Voucher Mask", enter <b>CMMYYNNNN</b></li>
<?php
for ($g=1; $g <= 4; ++$g)
{
if ($amt[$g] > 0)
{
echo "<li>Under Code $g:</li>";
echo "<ul>";
echo "<li>In the 'Type' field, select the type <b>A fixed value of</b></li>";
echo "<li>In the 'Amount' field, enter <b>$amt[$g]</b></li>";
echo "<li>In the 'Issue Min' field, enter <b>$g" ."000</b></li>";
echo "<li>In the 'Issue Max' field, enter <b>$g" ."999</b></li>";
echo "</ul>";
}
}
?>
<li>Select 'Update Record' to save.</li>
<li>Your gift certificates are now set up and ready for customer purchase.</li>
</ol>
<?php
}
else
{
echo "To begin your gift certificate setup, please enter your details through the ";
echo "<a href=\"certificates.php\">gift certificates administration section</a>, ";
echo "then come here to update your Mals-E cart.";
}
?>
</td>
</tr>
<?php
}
?>
<tr>
<td>
<?php
$malsquery = "SELECT MalsCart FROM " .$DB_Prefix ."_vars";
$malsresult = mysql_query($malsquery, $dblink) or die ("Unable to select your cart. Try again later.");
$malsrow = mysql_fetch_row($malsresult);
$malselink = "https://secure.aitsafe.com/admin/users/index.htm?username=$malsrow[0]";
echo "<iframe src=\"$malselink\" width=\"500\" height=\"500\" frameborder=\"1\" scroll=\"auto\">";
echo "<p align=\"center\">Go to your <a href=\"$malselink\" target=\"_blank\">Mals-E Cart</a>.</p>";
echo "</iframe>";
?>
</td>
</tr>
</table>
</center>
</div>
<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>
</html>
