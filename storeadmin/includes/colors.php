<html>

<head>
    <title>Colors</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<?php
include_once("../stconfig.php");
$setpg = "colors";
$fieldname = $_GET[field];
echo "<script language=\"javascript\">
<!--
function setColor(ColorValue)
{
window.opener.document.Vars.$fieldname.value = ColorValue;
window.close();
}
// -->
</script>";
?>
<table align="center" cellpadding="0" cellspacing="0" class="colortable">
    <tr>
        <td bgcolor="#000000" class="colorcell"><a class="noline" onMouseover="window.status='#000000';return true"
                                                   onMouseout="window.status='#000000';return true"
                                                   href="javascript:setColor('#000000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#000033" class="colorcell"><a class="noline" onMouseover="window.status='#000033';return true"
                                                   onMouseout="window.status='#000033';return true"
                                                   href="javascript:setColor('#000033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#000066" class="colorcell"><a class="noline" onMouseover="window.status='#000066';return true"
                                                   onMouseout="window.status='#000066';return true"
                                                   href="javascript:setColor('#000066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#000099" class="colorcell"><a class="noline" onMouseover="window.status='#000099';return true"
                                                   onMouseout="window.status='#000099';return true"
                                                   href="javascript:setColor('#000099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0000CC" class="colorcell"><a class="noline" onMouseover="window.status='#0000CC';return true"
                                                   onMouseout="window.status='#0000CC';return true"
                                                   href="javascript:setColor('#0000CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0000FF" class="colorcell"><a class="noline" onMouseover="window.status='#0000FF';return true"
                                                   onMouseout="window.status='#0000FF';return true"
                                                   href="javascript:setColor('#0000FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#003300" class="colorcell"><a class="noline" onMouseover="window.status='#003300';return true"
                                                   onMouseout="window.status='#003300';return true"
                                                   href="javascript:setColor('#003300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#003333" class="colorcell"><a class="noline" onMouseover="window.status='#003333';return true"
                                                   onMouseout="window.status='#003333';return true"
                                                   href="javascript:setColor('#003333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#003366" class="colorcell"><a class="noline" onMouseover="window.status='#003366';return true"
                                                   onMouseout="window.status='#003366';return true"
                                                   href="javascript:setColor('#003366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#003399" class="colorcell"><a class="noline" onMouseover="window.status='#003399';return true"
                                                   onMouseout="window.status='#003399';return true"
                                                   href="javascript:setColor('#003399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0033CC" class="colorcell"><a class="noline" onMouseover="window.status='#0033CC';return true"
                                                   onMouseout="window.status='#0033CC';return true"
                                                   href="javascript:setColor('#0033CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0033FF" class="colorcell"><a class="noline" onMouseover="window.status='#0033FF';return true"
                                                   onMouseout="window.status='#0033FF';return true"
                                                   href="javascript:setColor('#0033FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#006600" class="colorcell"><a class="noline" onMouseover="window.status='#006600';return true"
                                                   onMouseout="window.status='#006600';return true"
                                                   href="javascript:setColor('#006600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#006633" class="colorcell"><a class="noline" onMouseover="window.status='#006633';return true"
                                                   onMouseout="window.status='#006633';return true"
                                                   href="javascript:setColor('#006633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#006666" class="colorcell"><a class="noline" onMouseover="window.status='#006666';return true"
                                                   onMouseout="window.status='#006666';return true"
                                                   href="javascript:setColor('#006666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#006699" class="colorcell"><a class="noline" onMouseover="window.status='#006699';return true"
                                                   onMouseout="window.status='#006699';return true"
                                                   href="javascript:setColor('#006699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0066CC" class="colorcell"><a class="noline" onMouseover="window.status='#0066CC';return true"
                                                   onMouseout="window.status='#0066CC';return true"
                                                   href="javascript:setColor('#0066CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0066FF" class="colorcell"><a class="noline" onMouseover="window.status='#0066FF';return true"
                                                   onMouseout="window.status='#0066FF';return true"
                                                   href="javascript:setColor('#0066FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#009900" class="colorcell"><a class="noline" onMouseover="window.status='#009900';return true"
                                                   onMouseout="window.status='#009900';return true"
                                                   href="javascript:setColor('#009900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#009933" class="colorcell"><a class="noline" onMouseover="window.status='#009933';return true"
                                                   onMouseout="window.status='#009933';return true"
                                                   href="javascript:setColor('#009933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#009966" class="colorcell"><a class="noline" onMouseover="window.status='#009966';return true"
                                                   onMouseout="window.status='#009966';return true"
                                                   href="javascript:setColor('#009966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#009999" class="colorcell"><a class="noline" onMouseover="window.status='#009999';return true"
                                                   onMouseout="window.status='#009999';return true"
                                                   href="javascript:setColor('#009999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0099CC" class="colorcell"><a class="noline" onMouseover="window.status='#0099CC';return true"
                                                   onMouseout="window.status='#0099CC';return true"
                                                   href="javascript:setColor('#0099CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#0099FF" class="colorcell"><a class="noline" onMouseover="window.status='#0099FF';return true"
                                                   onMouseout="window.status='#0099FF';return true"
                                                   href="javascript:setColor('#0099FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CC00" class="colorcell"><a class="noline" onMouseover="window.status='#00CC00';return true"
                                                   onMouseout="window.status='#00CC00';return true"
                                                   href="javascript:setColor('#00CC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CC33" class="colorcell"><a class="noline" onMouseover="window.status='#00CC33';return true"
                                                   onMouseout="window.status='#00CC33';return true"
                                                   href="javascript:setColor('#00CC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CC66" class="colorcell"><a class="noline" onMouseover="window.status='#00CC66';return true"
                                                   onMouseout="window.status='#00CC66';return true"
                                                   href="javascript:setColor('#00CC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CC99" class="colorcell"><a class="noline" onMouseover="window.status='#00CC99';return true"
                                                   onMouseout="window.status='#00CC99';return true"
                                                   href="javascript:setColor('#00CC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CCCC" class="colorcell"><a class="noline" onMouseover="window.status='#00CCCC';return true"
                                                   onMouseout="window.status='#00CCCC';return true"
                                                   href="javascript:setColor('#00CCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00CCFF" class="colorcell"><a class="noline" onMouseover="window.status='#00CCFF';return true"
                                                   onMouseout="window.status='#00CCFF';return true"
                                                   href="javascript:setColor('#00CCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FF00" class="colorcell"><a class="noline" onMouseover="window.status='#00FF00';return true"
                                                   onMouseout="window.status='#00FF00';return true"
                                                   href="javascript:setColor('#00FF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FF33" class="colorcell"><a class="noline" onMouseover="window.status='#00FF33';return true"
                                                   onMouseout="window.status='#00FF33';return true"
                                                   href="javascript:setColor('#00FF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FF66" class="colorcell"><a class="noline" onMouseover="window.status='#00FF66';return true"
                                                   onMouseout="window.status='#00FF66';return true"
                                                   href="javascript:setColor('#00FF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FF99" class="colorcell"><a class="noline" onMouseover="window.status='#00FF99';return true"
                                                   onMouseout="window.status='#00FF99';return true"
                                                   href="javascript:setColor('#00FF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FFCC" class="colorcell"><a class="noline" onMouseover="window.status='#00FFCC';return true"
                                                   onMouseout="window.status='#00FFCC';return true"
                                                   href="javascript:setColor('#00FFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#00FFFF" class="colorcell"><a class="noline" onMouseover="window.status='#00FFFF';return true"
                                                   onMouseout="window.status='#00FFFF';return true"
                                                   href="javascript:setColor('#00FFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#330000" class="colorcell"><a class="noline" onMouseover="window.status='#330000';return true"
                                                   onMouseout="window.status='#330000';return true"
                                                   href="javascript:setColor('#330000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#330033" class="colorcell"><a class="noline" onMouseover="window.status='#330033';return true"
                                                   onMouseout="window.status='#330033';return true"
                                                   href="javascript:setColor('#330033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#330066" class="colorcell"><a class="noline" onMouseover="window.status='#330066';return true"
                                                   onMouseout="window.status='#330066';return true"
                                                   href="javascript:setColor('#330066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#330099" class="colorcell"><a class="noline" onMouseover="window.status='#330099';return true"
                                                   onMouseout="window.status='#330099';return true"
                                                   href="javascript:setColor('#330099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3300CC" class="colorcell"><a class="noline" onMouseover="window.status='#3300CC';return true"
                                                   onMouseout="window.status='#3300CC';return true"
                                                   href="javascript:setColor('#3300CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3300FF" class="colorcell"><a class="noline" onMouseover="window.status='#3300FF';return true"
                                                   onMouseout="window.status='#3300FF';return true"
                                                   href="javascript:setColor('#3300FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#333300" class="colorcell"><a class="noline" onMouseover="window.status='#333300';return true"
                                                   onMouseout="window.status='#333300';return true"
                                                   href="javascript:setColor('#333300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#333333" class="colorcell"><a class="noline" onMouseover="window.status='#333333';return true"
                                                   onMouseout="window.status='#333333';return true"
                                                   href="javascript:setColor('#333333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#333366" class="colorcell"><a class="noline" onMouseover="window.status='#333366';return true"
                                                   onMouseout="window.status='#333366';return true"
                                                   href="javascript:setColor('#333366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#333399" class="colorcell"><a class="noline" onMouseover="window.status='#333399';return true"
                                                   onMouseout="window.status='#333399';return true"
                                                   href="javascript:setColor('#333399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3333CC" class="colorcell"><a class="noline" onMouseover="window.status='#3333CC';return true"
                                                   onMouseout="window.status='#3333CC';return true"
                                                   href="javascript:setColor('#3333CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3333FF" class="colorcell"><a class="noline" onMouseover="window.status='#3333FF';return true"
                                                   onMouseout="window.status='#3333FF';return true"
                                                   href="javascript:setColor('#3333FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#336600" class="colorcell"><a class="noline" onMouseover="window.status='#336600';return true"
                                                   onMouseout="window.status='#336600';return true"
                                                   href="javascript:setColor('#336600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#336633" class="colorcell"><a class="noline" onMouseover="window.status='#336633';return true"
                                                   onMouseout="window.status='#336633';return true"
                                                   href="javascript:setColor('#336633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#336666" class="colorcell"><a class="noline" onMouseover="window.status='#336666';return true"
                                                   onMouseout="window.status='#336666';return true"
                                                   href="javascript:setColor('#336666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#336699" class="colorcell"><a class="noline" onMouseover="window.status='#336699';return true"
                                                   onMouseout="window.status='#336699';return true"
                                                   href="javascript:setColor('#336699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3366CC" class="colorcell"><a class="noline" onMouseover="window.status='#3366CC';return true"
                                                   onMouseout="window.status='#3366CC';return true"
                                                   href="javascript:setColor('#3366CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3366FF" class="colorcell"><a class="noline" onMouseover="window.status='#3366FF';return true"
                                                   onMouseout="window.status='#3366FF';return true"
                                                   href="javascript:setColor('#3366FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#339900" class="colorcell"><a class="noline" onMouseover="window.status='#339900';return true"
                                                   onMouseout="window.status='#339900';return true"
                                                   href="javascript:setColor('#339900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#339933" class="colorcell"><a class="noline" onMouseover="window.status='#339933';return true"
                                                   onMouseout="window.status='#339933';return true"
                                                   href="javascript:setColor('#339933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#339966" class="colorcell"><a class="noline" onMouseover="window.status='#339966';return true"
                                                   onMouseout="window.status='#339966';return true"
                                                   href="javascript:setColor('#339966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#339999" class="colorcell"><a class="noline" onMouseover="window.status='#339999';return true"
                                                   onMouseout="window.status='#339999';return true"
                                                   href="javascript:setColor('#339999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3399CC" class="colorcell"><a class="noline" onMouseover="window.status='#3399CC';return true"
                                                   onMouseout="window.status='#3399CC';return true"
                                                   href="javascript:setColor('#3399CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#3399FF" class="colorcell"><a class="noline" onMouseover="window.status='#3399FF';return true"
                                                   onMouseout="window.status='#3399FF';return true"
                                                   href="javascript:setColor('#3399FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CC00" class="colorcell"><a class="noline" onMouseover="window.status='#33CC00';return true"
                                                   onMouseout="window.status='#33CC00';return true"
                                                   href="javascript:setColor('#33CC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CC33" class="colorcell"><a class="noline" onMouseover="window.status='#33CC33';return true"
                                                   onMouseout="window.status='#33CC33';return true"
                                                   href="javascript:setColor('#33CC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CC66" class="colorcell"><a class="noline" onMouseover="window.status='#33CC66';return true"
                                                   onMouseout="window.status='#33CC66';return true"
                                                   href="javascript:setColor('#33CC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CC99" class="colorcell"><a class="noline" onMouseover="window.status='#33CC99';return true"
                                                   onMouseout="window.status='#33CC99';return true"
                                                   href="javascript:setColor('#33CC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CCCC" class="colorcell"><a class="noline" onMouseover="window.status='#33CCCC';return true"
                                                   onMouseout="window.status='#33CCCC';return true"
                                                   href="javascript:setColor('#33CCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33CCFF" class="colorcell"><a class="noline" onMouseover="window.status='#33CCFF';return true"
                                                   onMouseout="window.status='#33CCFF';return true"
                                                   href="javascript:setColor('#33CCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FF00" class="colorcell"><a class="noline" onMouseover="window.status='#33FF00';return true"
                                                   onMouseout="window.status='#33FF00';return true"
                                                   href="javascript:setColor('#33FF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FF33" class="colorcell"><a class="noline" onMouseover="window.status='#33FF33';return true"
                                                   onMouseout="window.status='#33FF33';return true"
                                                   href="javascript:setColor('#33FF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FF66" class="colorcell"><a class="noline" onMouseover="window.status='#33FF66';return true"
                                                   onMouseout="window.status='#33FF66';return true"
                                                   href="javascript:setColor('#33FF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FF99" class="colorcell"><a class="noline" onMouseover="window.status='#33FF99';return true"
                                                   onMouseout="window.status='#33FF99';return true"
                                                   href="javascript:setColor('#33FF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FFCC" class="colorcell"><a class="noline" onMouseover="window.status='#33FFCC';return true"
                                                   onMouseout="window.status='#33FFCC';return true"
                                                   href="javascript:setColor('#33FFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#33FFFF" class="colorcell"><a class="noline" onMouseover="window.status='#33FFFF';return true"
                                                   onMouseout="window.status='#33FFFF';return true"
                                                   href="javascript:setColor('#33FFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#660000" class="colorcell"><a class="noline" onMouseover="window.status='#660000';return true"
                                                   onMouseout="window.status='#660000';return true"
                                                   href="javascript:setColor('#660000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#660033" class="colorcell"><a class="noline" onMouseover="window.status='#660033';return true"
                                                   onMouseout="window.status='#660033';return true"
                                                   href="javascript:setColor('#660033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#660066" class="colorcell"><a class="noline" onMouseover="window.status='#660066';return true"
                                                   onMouseout="window.status='#660066';return true"
                                                   href="javascript:setColor('#660066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#660099" class="colorcell"><a class="noline" onMouseover="window.status='#660099';return true"
                                                   onMouseout="window.status='#660099';return true"
                                                   href="javascript:setColor('#660099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6600CC" class="colorcell"><a class="noline" onMouseover="window.status='#6600CC';return true"
                                                   onMouseout="window.status='#6600CC';return true"
                                                   href="javascript:setColor('#6600CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6600FF" class="colorcell"><a class="noline" onMouseover="window.status='#6600FF';return true"
                                                   onMouseout="window.status='#6600FF';return true"
                                                   href="javascript:setColor('#6600FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#663300" class="colorcell"><a class="noline" onMouseover="window.status='#663300';return true"
                                                   onMouseout="window.status='#663300';return true"
                                                   href="javascript:setColor('#663300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#663333" class="colorcell"><a class="noline" onMouseover="window.status='#663333';return true"
                                                   onMouseout="window.status='#663333';return true"
                                                   href="javascript:setColor('#663333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#663366" class="colorcell"><a class="noline" onMouseover="window.status='#663366';return true"
                                                   onMouseout="window.status='#663366';return true"
                                                   href="javascript:setColor('#663366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#663399" class="colorcell"><a class="noline" onMouseover="window.status='#663399';return true"
                                                   onMouseout="window.status='#663399';return true"
                                                   href="javascript:setColor('#663399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6633CC" class="colorcell"><a class="noline" onMouseover="window.status='#6633CC';return true"
                                                   onMouseout="window.status='#6633CC';return true"
                                                   href="javascript:setColor('#6633CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6633FF" class="colorcell"><a class="noline" onMouseover="window.status='#6633FF';return true"
                                                   onMouseout="window.status='#6633FF';return true"
                                                   href="javascript:setColor('#6633FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#666600" class="colorcell"><a class="noline" onMouseover="window.status='#666600';return true"
                                                   onMouseout="window.status='#666600';return true"
                                                   href="javascript:setColor('#666600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#666633" class="colorcell"><a class="noline" onMouseover="window.status='#666633';return true"
                                                   onMouseout="window.status='#666633';return true"
                                                   href="javascript:setColor('#666633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#666666" class="colorcell"><a class="noline" onMouseover="window.status='#666666';return true"
                                                   onMouseout="window.status='#666666';return true"
                                                   href="javascript:setColor('#666666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#666699" class="colorcell"><a class="noline" onMouseover="window.status='#666699';return true"
                                                   onMouseout="window.status='#666699';return true"
                                                   href="javascript:setColor('#666699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6666CC" class="colorcell"><a class="noline" onMouseover="window.status='#6666CC';return true"
                                                   onMouseout="window.status='#6666CC';return true"
                                                   href="javascript:setColor('#6666CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6666FF" class="colorcell"><a class="noline" onMouseover="window.status='#6666FF';return true"
                                                   onMouseout="window.status='#6666FF';return true"
                                                   href="javascript:setColor('#6666FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#669900" class="colorcell"><a class="noline" onMouseover="window.status='#669900';return true"
                                                   onMouseout="window.status='#669900';return true"
                                                   href="javascript:setColor('#669900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#669933" class="colorcell"><a class="noline" onMouseover="window.status='#669933';return true"
                                                   onMouseout="window.status='#669933';return true"
                                                   href="javascript:setColor('#669933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#669966" class="colorcell"><a class="noline" onMouseover="window.status='#669966';return true"
                                                   onMouseout="window.status='#669966';return true"
                                                   href="javascript:setColor('#669966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#669999" class="colorcell"><a class="noline" onMouseover="window.status='#669999';return true"
                                                   onMouseout="window.status='#669999';return true"
                                                   href="javascript:setColor('#669999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6699CC" class="colorcell"><a class="noline" onMouseover="window.status='#6699CC';return true"
                                                   onMouseout="window.status='#6699CC';return true"
                                                   href="javascript:setColor('#6699CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#6699FF" class="colorcell"><a class="noline" onMouseover="window.status='#6699FF';return true"
                                                   onMouseout="window.status='#6699FF';return true"
                                                   href="javascript:setColor('#6699FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CC00" class="colorcell"><a class="noline" onMouseover="window.status='#66CC00';return true"
                                                   onMouseout="window.status='#66CC00';return true"
                                                   href="javascript:setColor('#66CC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CC33" class="colorcell"><a class="noline" onMouseover="window.status='#66CC33';return true"
                                                   onMouseout="window.status='#66CC33';return true"
                                                   href="javascript:setColor('#66CC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CC66" class="colorcell"><a class="noline" onMouseover="window.status='#66CC66';return true"
                                                   onMouseout="window.status='#66CC66';return true"
                                                   href="javascript:setColor('#66CC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CC99" class="colorcell"><a class="noline" onMouseover="window.status='#66CC99';return true"
                                                   onMouseout="window.status='#66CC99';return true"
                                                   href="javascript:setColor('#66CC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CCCC" class="colorcell"><a class="noline" onMouseover="window.status='#66CCCC';return true"
                                                   onMouseout="window.status='#66CCCC';return true"
                                                   href="javascript:setColor('#66CCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66CCFF" class="colorcell"><a class="noline" onMouseover="window.status='#66CCFF';return true"
                                                   onMouseout="window.status='#66CCFF';return true"
                                                   href="javascript:setColor('#66CCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FF00" class="colorcell"><a class="noline" onMouseover="window.status='#66FF00';return true"
                                                   onMouseout="window.status='#66FF00';return true"
                                                   href="javascript:setColor('#66FF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FF33" class="colorcell"><a class="noline" onMouseover="window.status='#66FF33';return true"
                                                   onMouseout="window.status='#66FF33';return true"
                                                   href="javascript:setColor('#66FF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FF66" class="colorcell"><a class="noline" onMouseover="window.status='#66FF66';return true"
                                                   onMouseout="window.status='#66FF66';return true"
                                                   href="javascript:setColor('#66FF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FF99" class="colorcell"><a class="noline" onMouseover="window.status='#66FF99';return true"
                                                   onMouseout="window.status='#66FF99';return true"
                                                   href="javascript:setColor('#66FF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FFCC" class="colorcell"><a class="noline" onMouseover="window.status='#66FFCC';return true"
                                                   onMouseout="window.status='#66FFCC';return true"
                                                   href="javascript:setColor('#66FFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#66FFFF" class="colorcell"><a class="noline" onMouseover="window.status='#66FFFF';return true"
                                                   onMouseout="window.status='#66FFFF';return true"
                                                   href="javascript:setColor('#66FFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#990000" class="colorcell"><a class="noline" onMouseover="window.status='#990000';return true"
                                                   onMouseout="window.status='#990000';return true"
                                                   href="javascript:setColor('#990000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#990033" class="colorcell"><a class="noline" onMouseover="window.status='#990033';return true"
                                                   onMouseout="window.status='#990033';return true"
                                                   href="javascript:setColor('#990033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#990066" class="colorcell"><a class="noline" onMouseover="window.status='#990066';return true"
                                                   onMouseout="window.status='#990066';return true"
                                                   href="javascript:setColor('#990066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#990099" class="colorcell"><a class="noline" onMouseover="window.status='#990099';return true"
                                                   onMouseout="window.status='#990099';return true"
                                                   href="javascript:setColor('#990099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9900CC" class="colorcell"><a class="noline" onMouseover="window.status='#9900CC';return true"
                                                   onMouseout="window.status='#9900CC';return true"
                                                   href="javascript:setColor('#9900CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9900FF" class="colorcell"><a class="noline" onMouseover="window.status='#9900FF';return true"
                                                   onMouseout="window.status='#9900FF';return true"
                                                   href="javascript:setColor('#9900FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#993300" class="colorcell"><a class="noline" onMouseover="window.status='#993300';return true"
                                                   onMouseout="window.status='#993300';return true"
                                                   href="javascript:setColor('#993300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#993333" class="colorcell"><a class="noline" onMouseover="window.status='#993333';return true"
                                                   onMouseout="window.status='#993333';return true"
                                                   href="javascript:setColor('#993333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#993366" class="colorcell"><a class="noline" onMouseover="window.status='#993366';return true"
                                                   onMouseout="window.status='#993366';return true"
                                                   href="javascript:setColor('#993366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#993399" class="colorcell"><a class="noline" onMouseover="window.status='#993399';return true"
                                                   onMouseout="window.status='#993399';return true"
                                                   href="javascript:setColor('#993399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9933CC" class="colorcell"><a class="noline" onMouseover="window.status='#9933CC';return true"
                                                   onMouseout="window.status='#9933CC';return true"
                                                   href="javascript:setColor('#9933CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9933FF" class="colorcell"><a class="noline" onMouseover="window.status='#9933FF';return true"
                                                   onMouseout="window.status='#9933FF';return true"
                                                   href="javascript:setColor('#9933FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#996600" class="colorcell"><a class="noline" onMouseover="window.status='#996600';return true"
                                                   onMouseout="window.status='#996600';return true"
                                                   href="javascript:setColor('#996600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#996633" class="colorcell"><a class="noline" onMouseover="window.status='#996633';return true"
                                                   onMouseout="window.status='#996633';return true"
                                                   href="javascript:setColor('#996633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#996666" class="colorcell"><a class="noline" onMouseover="window.status='#996666';return true"
                                                   onMouseout="window.status='#996666';return true"
                                                   href="javascript:setColor('#996666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#996699" class="colorcell"><a class="noline" onMouseover="window.status='#996699';return true"
                                                   onMouseout="window.status='#996699';return true"
                                                   href="javascript:setColor('#996699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9966CC" class="colorcell"><a class="noline" onMouseover="window.status='#9966CC';return true"
                                                   onMouseout="window.status='#9966CC';return true"
                                                   href="javascript:setColor('#9966CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9966FF" class="colorcell"><a class="noline" onMouseover="window.status='#9966FF';return true"
                                                   onMouseout="window.status='#9966FF';return true"
                                                   href="javascript:setColor('#9966FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#999900" class="colorcell"><a class="noline" onMouseover="window.status='#999900';return true"
                                                   onMouseout="window.status='#999900';return true"
                                                   href="javascript:setColor('#999900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#999933" class="colorcell"><a class="noline" onMouseover="window.status='#999933';return true"
                                                   onMouseout="window.status='#999933';return true"
                                                   href="javascript:setColor('#999933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#999966" class="colorcell"><a class="noline" onMouseover="window.status='#999966';return true"
                                                   onMouseout="window.status='#999966';return true"
                                                   href="javascript:setColor('#999966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#999999" class="colorcell"><a class="noline" onMouseover="window.status='#999999';return true"
                                                   onMouseout="window.status='#999999';return true"
                                                   href="javascript:setColor('#999999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9999CC" class="colorcell"><a class="noline" onMouseover="window.status='#9999CC';return true"
                                                   onMouseout="window.status='#9999CC';return true"
                                                   href="javascript:setColor('#9999CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#9999FF" class="colorcell"><a class="noline" onMouseover="window.status='#9999FF';return true"
                                                   onMouseout="window.status='#9999FF';return true"
                                                   href="javascript:setColor('#9999FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CC00" class="colorcell"><a class="noline" onMouseover="window.status='#99CC00';return true"
                                                   onMouseout="window.status='#99CC00';return true"
                                                   href="javascript:setColor('#99CC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CC33" class="colorcell"><a class="noline" onMouseover="window.status='#99CC33';return true"
                                                   onMouseout="window.status='#99CC33';return true"
                                                   href="javascript:setColor('#99CC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CC66" class="colorcell"><a class="noline" onMouseover="window.status='#99CC66';return true"
                                                   onMouseout="window.status='#99CC66';return true"
                                                   href="javascript:setColor('#99CC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CC99" class="colorcell"><a class="noline" onMouseover="window.status='#99CC99';return true"
                                                   onMouseout="window.status='#99CC99';return true"
                                                   href="javascript:setColor('#99CC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CCCC" class="colorcell"><a class="noline" onMouseover="window.status='#99CCCC';return true"
                                                   onMouseout="window.status='#99CCCC';return true"
                                                   href="javascript:setColor('#99CCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99CCFF" class="colorcell"><a class="noline" onMouseover="window.status='#99CCFF';return true"
                                                   onMouseout="window.status='#99CCFF';return true"
                                                   href="javascript:setColor('#99CCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FF00" class="colorcell"><a class="noline" onMouseover="window.status='#99FF00';return true"
                                                   onMouseout="window.status='#99FF00';return true"
                                                   href="javascript:setColor('#99FF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FF33" class="colorcell"><a class="noline" onMouseover="window.status='#99FF33';return true"
                                                   onMouseout="window.status='#99FF33';return true"
                                                   href="javascript:setColor('#99FF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FF66" class="colorcell"><a class="noline" onMouseover="window.status='#99FF66';return true"
                                                   onMouseout="window.status='#99FF66';return true"
                                                   href="javascript:setColor('#99FF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FF99" class="colorcell"><a class="noline" onMouseover="window.status='#99FF99';return true"
                                                   onMouseout="window.status='#99FF99';return true"
                                                   href="javascript:setColor('#99FF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FFCC" class="colorcell"><a class="noline" onMouseover="window.status='#99FFCC';return true"
                                                   onMouseout="window.status='#99FFCC';return true"
                                                   href="javascript:setColor('#99FFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#99FFFF" class="colorcell"><a class="noline" onMouseover="window.status='#99FFFF';return true"
                                                   onMouseout="window.status='#99FFFF';return true"
                                                   href="javascript:setColor('#99FFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#CC0000" class="colorcell"><a class="noline" onMouseover="window.status='#CC0000';return true"
                                                   onMouseout="window.status='#CC0000';return true"
                                                   href="javascript:setColor('#CC0000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC0033" class="colorcell"><a class="noline" onMouseover="window.status='#CC0033';return true"
                                                   onMouseout="window.status='#CC0033';return true"
                                                   href="javascript:setColor('#CC0033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC0066" class="colorcell"><a class="noline" onMouseover="window.status='#CC0066';return true"
                                                   onMouseout="window.status='#CC0066';return true"
                                                   href="javascript:setColor('#CC0066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC0099" class="colorcell"><a class="noline" onMouseover="window.status='#CC0099';return true"
                                                   onMouseout="window.status='#CC0099';return true"
                                                   href="javascript:setColor('#CC0099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC00CC" class="colorcell"><a class="noline" onMouseover="window.status='#CC00CC';return true"
                                                   onMouseout="window.status='#CC00CC';return true"
                                                   href="javascript:setColor('#CC00CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC00FF" class="colorcell"><a class="noline" onMouseover="window.status='#CC00FF';return true"
                                                   onMouseout="window.status='#CC00FF';return true"
                                                   href="javascript:setColor('#CC00FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC3300" class="colorcell"><a class="noline" onMouseover="window.status='#CC3300';return true"
                                                   onMouseout="window.status='#CC3300';return true"
                                                   href="javascript:setColor('#CC3300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC3333" class="colorcell"><a class="noline" onMouseover="window.status='#CC3333';return true"
                                                   onMouseout="window.status='#CC3333';return true"
                                                   href="javascript:setColor('#CC3333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC3366" class="colorcell"><a class="noline" onMouseover="window.status='#CC3366';return true"
                                                   onMouseout="window.status='#CC3366';return true"
                                                   href="javascript:setColor('#CC3366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC3399" class="colorcell"><a class="noline" onMouseover="window.status='#CC3399';return true"
                                                   onMouseout="window.status='#CC3399';return true"
                                                   href="javascript:setColor('#CC3399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC33CC" class="colorcell"><a class="noline" onMouseover="window.status='#CC33CC';return true"
                                                   onMouseout="window.status='#CC33CC';return true"
                                                   href="javascript:setColor('#CC33CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC33FF" class="colorcell"><a class="noline" onMouseover="window.status='#CC33FF';return true"
                                                   onMouseout="window.status='#CC33FF';return true"
                                                   href="javascript:setColor('#CC33FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC6600" class="colorcell"><a class="noline" onMouseover="window.status='#CC6600';return true"
                                                   onMouseout="window.status='#CC6600';return true"
                                                   href="javascript:setColor('#CC6600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC6633" class="colorcell"><a class="noline" onMouseover="window.status='#CC6633';return true"
                                                   onMouseout="window.status='#CC6633';return true"
                                                   href="javascript:setColor('#CC6633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC6666" class="colorcell"><a class="noline" onMouseover="window.status='#CC6666';return true"
                                                   onMouseout="window.status='#CC6666';return true"
                                                   href="javascript:setColor('#CC6666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC6699" class="colorcell"><a class="noline" onMouseover="window.status='#CC6699';return true"
                                                   onMouseout="window.status='#CC6699';return true"
                                                   href="javascript:setColor('#CC6699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC66CC" class="colorcell"><a class="noline" onMouseover="window.status='#CC66CC';return true"
                                                   onMouseout="window.status='#CC66CC';return true"
                                                   href="javascript:setColor('#CC66CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC66FF" class="colorcell"><a class="noline" onMouseover="window.status='#CC66FF';return true"
                                                   onMouseout="window.status='#CC66FF';return true"
                                                   href="javascript:setColor('#CC66FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#CC9900" class="colorcell"><a class="noline" onMouseover="window.status='#CC9900';return true"
                                                   onMouseout="window.status='#CC9900';return true"
                                                   href="javascript:setColor('#CC9900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC9933" class="colorcell"><a class="noline" onMouseover="window.status='#CC9933';return true"
                                                   onMouseout="window.status='#CC9933';return true"
                                                   href="javascript:setColor('#CC9933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC9966" class="colorcell"><a class="noline" onMouseover="window.status='#CC9966';return true"
                                                   onMouseout="window.status='#CC9966';return true"
                                                   href="javascript:setColor('#CC9966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC9999" class="colorcell"><a class="noline" onMouseover="window.status='#CC9999';return true"
                                                   onMouseout="window.status='#CC9999';return true"
                                                   href="javascript:setColor('#CC9999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC99CC" class="colorcell"><a class="noline" onMouseover="window.status='#CC99CC';return true"
                                                   onMouseout="window.status='#CC99CC';return true"
                                                   href="javascript:setColor('#CC99CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CC99FF" class="colorcell"><a class="noline" onMouseover="window.status='#CC99FF';return true"
                                                   onMouseout="window.status='#CC99FF';return true"
                                                   href="javascript:setColor('#CC99FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCC00" class="colorcell"><a class="noline" onMouseover="window.status='#CCCC00';return true"
                                                   onMouseout="window.status='#CCCC00';return true"
                                                   href="javascript:setColor('#CCCC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCC33" class="colorcell"><a class="noline" onMouseover="window.status='#CCCC33';return true"
                                                   onMouseout="window.status='#CCCC33';return true"
                                                   href="javascript:setColor('#CCCC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCC66" class="colorcell"><a class="noline" onMouseover="window.status='#CCCC66';return true"
                                                   onMouseout="window.status='#CCCC66';return true"
                                                   href="javascript:setColor('#CCCC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCC99" class="colorcell"><a class="noline" onMouseover="window.status='#CCCC99';return true"
                                                   onMouseout="window.status='#CCCC99';return true"
                                                   href="javascript:setColor('#CCCC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCCCC" class="colorcell"><a class="noline" onMouseover="window.status='#CCCCCC';return true"
                                                   onMouseout="window.status='#CCCCCC';return true"
                                                   href="javascript:setColor('#CCCCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCCCFF" class="colorcell"><a class="noline" onMouseover="window.status='#CCCCFF';return true"
                                                   onMouseout="window.status='#CCCCFF';return true"
                                                   href="javascript:setColor('#CCCCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFF00" class="colorcell"><a class="noline" onMouseover="window.status='#CCFF00';return true"
                                                   onMouseout="window.status='#CCFF00';return true"
                                                   href="javascript:setColor('#CCFF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFF33" class="colorcell"><a class="noline" onMouseover="window.status='#CCFF33';return true"
                                                   onMouseout="window.status='#CCFF33';return true"
                                                   href="javascript:setColor('#CCFF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFF66" class="colorcell"><a class="noline" onMouseover="window.status='#CCFF66';return true"
                                                   onMouseout="window.status='#CCFF66';return true"
                                                   href="javascript:setColor('#CCFF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFF99" class="colorcell"><a class="noline" onMouseover="window.status='#CCFF99';return true"
                                                   onMouseout="window.status='#CCFF99';return true"
                                                   href="javascript:setColor('#CCFF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFFCC" class="colorcell"><a class="noline" onMouseover="window.status='#CCFFCC';return true"
                                                   onMouseout="window.status='#CCFFCC';return true"
                                                   href="javascript:setColor('#CCFFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#CCFFFF" class="colorcell"><a class="noline" onMouseover="window.status='#CCFFFF';return true"
                                                   onMouseout="window.status='#CCFFFF';return true"
                                                   href="javascript:setColor('#CCFFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#FF0000" class="colorcell"><a class="noline" onMouseover="window.status='#FF0000';return true"
                                                   onMouseout="window.status='#FF0000';return true"
                                                   href="javascript:setColor('#FF0000');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF0033" class="colorcell"><a class="noline" onMouseover="window.status='#FF0033';return true"
                                                   onMouseout="window.status='#FF0033';return true"
                                                   href="javascript:setColor('#FF0033');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF0066" class="colorcell"><a class="noline" onMouseover="window.status='#FF0066';return true"
                                                   onMouseout="window.status='#FF0066';return true"
                                                   href="javascript:setColor('#FF0066');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF0099" class="colorcell"><a class="noline" onMouseover="window.status='#FF0099';return true"
                                                   onMouseout="window.status='#FF0099';return true"
                                                   href="javascript:setColor('#FF0099');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF00CC" class="colorcell"><a class="noline" onMouseover="window.status='#FF00CC';return true"
                                                   onMouseout="window.status='#FF00CC';return true"
                                                   href="javascript:setColor('#FF00CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF00FF" class="colorcell"><a class="noline" onMouseover="window.status='#FF00FF';return true"
                                                   onMouseout="window.status='#FF00FF';return true"
                                                   href="javascript:setColor('#FF00FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF3300" class="colorcell"><a class="noline" onMouseover="window.status='#FF3300';return true"
                                                   onMouseout="window.status='#FF3300';return true"
                                                   href="javascript:setColor('#FF3300');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF3333" class="colorcell"><a class="noline" onMouseover="window.status='#FF3333';return true"
                                                   onMouseout="window.status='#FF3333';return true"
                                                   href="javascript:setColor('#FF3333');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF3366" class="colorcell"><a class="noline" onMouseover="window.status='#FF3366';return true"
                                                   onMouseout="window.status='#FF3366';return true"
                                                   href="javascript:setColor('#FF3366');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF3399" class="colorcell"><a class="noline" onMouseover="window.status='#FF3399';return true"
                                                   onMouseout="window.status='#FF3399';return true"
                                                   href="javascript:setColor('#FF3399');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF33CC" class="colorcell"><a class="noline" onMouseover="window.status='#FF33CC';return true"
                                                   onMouseout="window.status='#FF33CC';return true"
                                                   href="javascript:setColor('#FF33CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF33FF" class="colorcell"><a class="noline" onMouseover="window.status='#FF33FF';return true"
                                                   onMouseout="window.status='#FF33FF';return true"
                                                   href="javascript:setColor('#FF33FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF6600" class="colorcell"><a class="noline" onMouseover="window.status='#FF6600';return true"
                                                   onMouseout="window.status='#FF6600';return true"
                                                   href="javascript:setColor('#FF6600');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF6633" class="colorcell"><a class="noline" onMouseover="window.status='#FF6633';return true"
                                                   onMouseout="window.status='#FF6633';return true"
                                                   href="javascript:setColor('#FF6633');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF6666" class="colorcell"><a class="noline" onMouseover="window.status='#FF6666';return true"
                                                   onMouseout="window.status='#FF6666';return true"
                                                   href="javascript:setColor('#FF6666');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF6699" class="colorcell"><a class="noline" onMouseover="window.status='#FF6699';return true"
                                                   onMouseout="window.status='#FF6699';return true"
                                                   href="javascript:setColor('#FF6699');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF66CC" class="colorcell"><a class="noline" onMouseover="window.status='#FF66CC';return true"
                                                   onMouseout="window.status='#FF66CC';return true"
                                                   href="javascript:setColor('#FF66CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF66FF" class="colorcell"><a class="noline" onMouseover="window.status='#FF66FF';return true"
                                                   onMouseout="window.status='#FF66FF';return true"
                                                   href="javascript:setColor('#FF66FF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    <tr>
        <td bgcolor="#FF9900" class="colorcell"><a class="noline" onMouseover="window.status='#FF9900';return true"
                                                   onMouseout="window.status='#FF9900';return true"
                                                   href="javascript:setColor('#FF9900');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF9933" class="colorcell"><a class="noline" onMouseover="window.status='#FF9933';return true"
                                                   onMouseout="window.status='#FF9933';return true"
                                                   href="javascript:setColor('#FF9933');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF9966" class="colorcell"><a class="noline" onMouseover="window.status='#FF9966';return true"
                                                   onMouseout="window.status='#FF9966';return true"
                                                   href="javascript:setColor('#FF9966');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF9999" class="colorcell"><a class="noline" onMouseover="window.status='#FF9999';return true"
                                                   onMouseout="window.status='#FF9999';return true"
                                                   href="javascript:setColor('#FF9999');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF99CC" class="colorcell"><a class="noline" onMouseover="window.status='#FF99CC';return true"
                                                   onMouseout="window.status='#FF99CC';return true"
                                                   href="javascript:setColor('#FF99CC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FF99FF" class="colorcell"><a class="noline" onMouseover="window.status='#FF99FF';return true"
                                                   onMouseout="window.status='#FF99FF';return true"
                                                   href="javascript:setColor('#FF99FF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCC00" class="colorcell"><a class="noline" onMouseover="window.status='#FFCC00';return true"
                                                   onMouseout="window.status='#FFCC00';return true"
                                                   href="javascript:setColor('#FFCC00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCC33" class="colorcell"><a class="noline" onMouseover="window.status='#FFCC33';return true"
                                                   onMouseout="window.status='#FFCC33';return true"
                                                   href="javascript:setColor('#FFCC33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCC66" class="colorcell"><a class="noline" onMouseover="window.status='#FFCC66';return true"
                                                   onMouseout="window.status='#FFCC66';return true"
                                                   href="javascript:setColor('#FFCC66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCC99" class="colorcell"><a class="noline" onMouseover="window.status='#FFCC99';return true"
                                                   onMouseout="window.status='#FFCC99';return true"
                                                   href="javascript:setColor('#FFCC99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCCCC" class="colorcell"><a class="noline" onMouseover="window.status='#FFCCCC';return true"
                                                   onMouseout="window.status='#FFCCCC';return true"
                                                   href="javascript:setColor('#FFCCCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFCCFF" class="colorcell"><a class="noline" onMouseover="window.status='#FFCCFF';return true"
                                                   onMouseout="window.status='#FFCCFF';return true"
                                                   href="javascript:setColor('#FFCCFF');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFF00" class="colorcell"><a class="noline" onMouseover="window.status='#FFFF00';return true"
                                                   onMouseout="window.status='#FFFF00';return true"
                                                   href="javascript:setColor('#FFFF00');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFF33" class="colorcell"><a class="noline" onMouseover="window.status='#FFFF33';return true"
                                                   onMouseout="window.status='#FFFF33';return true"
                                                   href="javascript:setColor('#FFFF33');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFF66" class="colorcell"><a class="noline" onMouseover="window.status='#FFFF66';return true"
                                                   onMouseout="window.status='#FFFF66';return true"
                                                   href="javascript:setColor('#FFFF66');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFF99" class="colorcell"><a class="noline" onMouseover="window.status='#FFFF99';return true"
                                                   onMouseout="window.status='#FFFF99';return true"
                                                   href="javascript:setColor('#FFFF99');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFFCC" class="colorcell"><a class="noline" onMouseover="window.status='#FFFFCC';return true"
                                                   onMouseout="window.status='#FFFFCC';return true"
                                                   href="javascript:setColor('#FFFFCC');">&nbsp;&nbsp;&nbsp;</a></td>
        <td bgcolor="#FFFFFF" class="colorcell"><a class="noline" onMouseover="window.status='#FFFFFF';return true"
                                                   onMouseout="window.status='#FFFFFF';return true"
                                                   href="javascript:setColor('#FFFFFF');">&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
</table>

<p align="center"><font size="2">(<a class="noline" href="javascript:window.close();">Close Window</a>)</font></p>

</body>

</html>
