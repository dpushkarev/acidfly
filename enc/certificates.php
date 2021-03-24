<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$curquery = "SELECT Currency FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$curresult = mysqli_query($dblink, $curquery) or die ("Unable to select. Try again later.");
$currow = mysqli_fetch_row($curresult);
$currency = $currow[0];

$gcquery = "SELECT * FROM " .$DB_Prefix ."_certificates";
$gcresult = mysqli_query($dblink, $gcquery) or die ("Unable to select. Try again later.");
$gcrow = mysqli_fetch_array($gcresult);
$amount1 = $gcrow[Amount1];
$amount2 = $gcrow[Amount2];
$amount3 = $gcrow[Amount3];
$amount4 = $gcrow[Amount4];
$amount5 = $gcrow[Amount5];
$gcname = stripslashes($gcrow[Name]);
$sendinfo = stripslashes($gcrow[SendInfo]);
$expmo = $gcrow[ExpireMonths];

// If error message
if ($err == "y")
{
echo "<p align=\"center\" class=\"salecolor\">";
echo "Please enter a value for all fields below:</p>";
}

// Set cart variables
echo "<form method=\"POST\" action=\"$URL/go/order.php\">";
echo "<input type=\"hidden\" name=\"pgnm_noext\" value=\"$pgnm_noext\">";
echo "<input type=\"hidden\" name=\"pageext\" value=\"php\">";
echo "<input type=\"hidden\" name=\"product\" value=\"$gcname\">";
echo "<input type=\"hidden\" name=\"giftcert\" value=\"GC\">";
echo "<input type=\"hidden\" name=\"expmo\" value=\"$expmo\">";
echo "<input type=\"hidden\" name=\"return\" value=\"$Return_URL\">";
echo "<div align=\"center\">";
echo "<center>";
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">Issue To:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo "<input type=\"text\" name=\"issued\" size=\"20\" maxlength=\"100\">";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">$sendinfo:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo "<input type=\"text\" name=\"sendto\" size=\"20\" maxlength=\"100\">";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">From:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo "<input type=\"text\" name=\"from\" size=\"20\" maxlength=\"100\">";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td valign=\"top\" align=\"right\">Amount:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo "<select name=\"price\" size=\"1\">";
for ($i=1; $i <= 5; ++$i)
{
$amt = ${"amount$i"};
if ($amt > 0)
{
echo "<option ";
if ($i == 1)
echo "selected ";
echo "value=\"$amt~$i\">$currency$amt</option>";
}
}
echo "</select>";
echo "</td>";
echo "</tr>";


echo "<td valign=\"top\" align=\"center\" colspan=\"2\">";
if ($Order_Button)
echo "<input type=\"image\" alt=\"Order\" src=\"$Order_Button\" name=\"Order\" border=\"0\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Order\" name=\"Order\" class=\"formbutton\">";
echo "</td></tr>";

echo "</table>";
echo "</center>";
echo "</div>";
echo "</form>";
?>
