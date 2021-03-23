<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$ipaddress = $_SERVER['REMOTE_ADDR'];

if ($mode == "post")
{
echo "<form action=\"guestbook.$pageext\" method=\"post\">";
?>
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td align="right" valign="top">Name:</td>
<td align="left" valign="top"><input type="text" name="name" size="40"></td>
</tr>
<tr>
<td align="right" valign="top">Email:</td>
<td align="left" valign="top"><input type="text" name="email" size="40"></td>
</tr>
<tr>
<td align="right" valign="top">Location:</td>
<td align="left" valign="top"><input type="text" name="location" size="40"></td>
</tr>
<tr>
<td align="right" valign="top">Comments:</td>
<td align="left" valign="top"><textarea rows="5" name="comments" cols="34"></textarea></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
<td align="center">
<input type="submit" value="Preview" name="mode" class="formbutton"> 
<input type="submit" value="Submit" name="mode" class="formbutton">
</td>
</tr>
</table>
<?php
echo "</form>";
}
else if ($mode == "Preview")
{
if (!$name OR !$comments)
echo "<p>Sorry, but we did not receive either your name or comments. Please go back and re-enter them.</p>";
else
{
$stripname = stripslashes(stripbadstuff($_POST['name']));
$striplocation = stripslashes(stripbadstuff($_POST['location']));
$stripcomments = stripslashes(stripbadstuff($_POST['comments']));
$stripemail = stripslashes(stripbadstuff($_POST['email']));
echo "<form action=\"guestbook.$pageext\" method=\"post\">";
?>
<table border="0" cellpadding="3" cellspacing="0" align="center">
<?php
if ($name)
{
echo "<tr>";
echo "<td align=\"right\" valign=\"top\">Name:</td>";
echo "<td align=\"left\" valign=\"top\">$stripname</td>";
echo "</tr>";
}
if ($email)
{
echo "<tr>";
echo "<td align=\"right\" valign=\"top\">Email:</td>";
echo "<td align=\"left\" valign=\"top\">$stripemail</td>";
echo "</tr>";
}
if ($location)
{
echo "<tr>";
echo "<td align=\"right\" valign=\"top\">Location:</td>";
echo "<td align=\"left\" valign=\"top\">$striplocation</td>";
echo "</tr>";
}
if ($comments)
{
echo "<tr>";
echo "<td align=\"right\" valign=\"top\">Comments:</td>";
echo "<td align=\"left\" valign=\"top\">$stripcomments</td>";
echo "</tr>";
}
?>
<tr>
<td>&nbsp;</td>
<td align="center">
<?php
$repname = str_replace("\"", "&quot;", $stripname);
$replocation = str_replace("\"", "&quot;", $striplocation);
$repcomments = str_replace("\"", "&quot;", $stripcomments);
$repemail = str_replace("\"", "&quot;", $stripemail);
echo "<input type=\"hidden\" name=\"name\" value=\"$repname\">";
echo "<input type=\"hidden\" name=\"email\" value=\"$repemail\">";
echo "<input type=\"hidden\" name=\"location\" value=\"$replocation\">";
echo "<input type=\"hidden\" name=\"comments\" value=\"$repcomments\">";
?>
<input type="submit" value="Submit" name="mode" class="formbutton">
</td>
</tr>
</table>
<?php
echo "</form>";
}
}
else if ($mode == "Submit" OR !$mode)
{
if ($mode == "Submit")
{
if (!$name OR !$comments)
echo "<p>Sorry, but we did not receive either your name or comments. Please go back and re-enter them.</p>";
else
{
$addname = addslash_mq(stripbadstuff($_POST['name']));
$addlocation = addslash_mq(stripbadstuff($_POST['location']));
$addcomments = addslash_mq(stripbadstuff($_POST['comments']));
$addemail = addslash_mq(stripbadstuff($_POST['email']));
$stripname = stripslashes(stripbadstuff($_POST['name']));
$striplocation = stripslashes(stripbadstuff($_POST['location']));
$stripcomments = stripslashes(stripbadstuff($_POST['comments']));
$stripemail = stripslashes(stripbadstuff($_POST['email']));

$ckquery = "SELECT ID FROM " .$DB_Prefix ."_guestbook WHERE Name='$addname' AND Email='$addemail' AND Location='$addlocation' AND Comments='$addcomments'";
$ckresult = mysql_query($ckquery, $dblink) or die ("Unable to check. Try again later.");
if (mysql_num_rows($ckresult) == 0)
{
$insquery = "INSERT INTO " .$DB_Prefix ."_guestbook (Name, Email, Location, IPAddress, Comments, Date) ";
$insquery .= "VALUES ('$addname', '$addemail', '$addlocation', '$ipaddress', '$addcomments', '0000-00-00')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
echo "<p align=\"center\">";
echo "Thank you for your post. We will activate your message shortly.</p>";
@mail($Admin_Email, "Guestbook Entry", "The following guestbook entry was submitted, and must be approved through your administration area before it will appear on your site:
Name: $stripname
Email: $stripemail
Location: $striplocation
Comments: $stripcomments", "From: $Contact_Email\r\nReply-To: $Contact_Email");
}
}
}

if (!$mode OR ($name AND $comments))
{
echo "<p align=\"center\">";
echo "<a href=\"guestbook.$pageext?mode=post\">";
echo "Add a Message</a></p>";

// Set page numbers
if (!$page)
$offset = 0;
else
$offset = (($LimitOfItems * $page)-$LimitOfItems);

$gbquery = "SELECT * FROM " .$DB_Prefix ."_guestbook WHERE Date<>'0000-00-00' ORDER BY ID DESC";
$totalgbquery = $gbquery;
$gbquery .= " LIMIT $offset, $LimitOfItems";
$gbresult = mysql_query($gbquery, $dblink) or die ("Unable to select totals. Please try again later.");
$totalgbresult = mysql_query($totalgbquery, $dblink) or die ("Unable to access total records.");
$totalgbnum = mysql_num_rows($totalgbresult);

if ($totalgbnum > 0)
{
// START FORMATTING OF PAGE NUMBERS
$offset = ($page-1)*$LimitOfItems;
if ($totalgbnum % $LimitOfItems == 0)
$page_count = ($totalgbnum-($totalgbnum%$LimitOfItems)) / $LimitOfItems;
else
$page_count = ($totalgbnum-($totalgbnum%$LimitOfItems)) / $LimitOfItems + 1;

$previous = $page - 1;
$next = $page + 1;

if ($page_count > 1)
{
$i = 1;
$n = 3;
if ($page < $n+1)
$pagestart = 1;
else if ($page > ($page_count-$n))
$pagestart = $page_count-$n*2;
else
$pagestart = $page-$n;

while ($i <= $page_count)
{
if (($i >= $pagestart) AND ($i <= $n*2+$pagestart))
{
if ($i != $page)
$output_string .= "<a href=\"guestbook.$pageext?page=$i\">$i</a>";
else
$output_string .= "<b>$i</b>";
if ($i != $page_count AND $i != $n*2+$pagestart)
$output_string .= " | ";
}
$i++;
}
}
// END FORMATTING OF PAGE NUMBERS

// START DISPLAY OF PAGE NUMBERS
if ($page_count > 1)
{
if ($Page_Numbers == "Yes")
{
echo "<p align=\"center\">";
if ($page > 1)
{
echo "<a href=\"guestbook.$pageext?page=1\"><<</a> | ";
echo "<a href=\"guestbook.$pageext?page=$previous\"><</a> | ";
}
echo "$output_string";
if ($page < $page_count)
{
echo " | <a href=\"guestbook.$pageext?page=$next\">></a>";
echo " | <a href=\"guestbook.$pageext?page=$page_count\">>></a>";
}
echo "<br>Page <b>$page</b> of <b>$page_count</b>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
if ($page > 1)
echo "<a href=\"guestbook.$pageext?page=1\"><< First</a> | <a href=\"guestbook.$pageext?page=$previous\">< Previous</a> | ";
echo "Page <b>$page</b> of <b>$page_count</b>";
if ($page < $page_count)
echo " | <a href=\"guestbook.$pageext?page=$next\">Next ></a> | <a href=\"guestbook.$pageext?page=$page_count\">Last >></a>";
echo "</p>";
}
}
// END DISPLAY OF PAGE NUMBERS

echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" width=\"95%\">";
for ($gb = 1; $gbrow = mysql_fetch_row($gbresult); ++$gb)
{
$gbname = stripslashes(stripbadstuff($gbrow[1]));
$gblocation = stripslashes(stripbadstuff($gbrow[3]));
$gbcomments = stripslashes(stripbadstuff($gbrow[5]));
$gbemail = stripslashes(stripbadstuff($gbrow[2]));
$gbdate = $gbrow[6];
echo "<tr><td align=\"center\" colspan=\"2\">";
if ($Product_Line)
echo "<hr class=\"linecolor\">";
else
echo "&nbsp;";
echo "</td></tr>";
?>
<tr>
<td align="left" class="boldtext">
<?php
if ($gbrow[2])
echo "<a href=\"mailto:$gbemail\">$gbname</a>";
else
echo "$gbname";
?>
</td>
<td align="right">
<?php echo "$gbdate"; ?>
</td>
</tr>
<tr>
<td colspan="2">
<?php echo "$gbcomments"; ?>
</td>
</tr>

<?php
if ($gblocation)
{
?>
<tr>
<td  colspan="2">
<?php echo "From: $gblocation"; ?>
</td>
</tr>
<?php
}
}
echo "<tr><td align=\"center\" colspan=\"2\">";
if ($Product_Line)
echo "<hr class=\"linecolor\">";
else
echo "&nbsp;";
echo "</td></tr>";
echo "</table>";
}
}
}
?>
