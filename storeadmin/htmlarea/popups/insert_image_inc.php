<!DOCTYPE HTML PUBLIC "-//W3C//DTD W3 HTML 3.2//EN">
<HTML id=dlgImage>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<TITLE>Insert Image</TITLE>
<style>
  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };
</style>
<SCRIPT defer>
function _CloseOnEsc() {
  if (event.keyCode == 27) { window.close(); return; }
}

function _getTextRange(elm) {
  var r = elm.parentTextEdit.createTextRange();
  r.moveToElementText(elm);
  return r;
}

window.onerror = HandleError

function HandleError(message, url, line) {
  var str = "An error has occurred in this dialog." + "\n\n"
  + "Error: " + line + "\n" + message;
  alert(str);
  window.close();
  return true;
}

function Init() {
  var elmSelectedImage;
  var htmlSelectionControl = "Control";
  var globalDoc = window.dialogArguments;
  var grngMaster = globalDoc.selection.createRange();
  
  // event handlers  
  document.body.onkeypress = _CloseOnEsc;
  btnOK.onclick = new Function("btnOKClick()");

  txtFileName.fImageLoaded = false;
  txtFileName.intImageWidth = 0;
  txtFileName.intImageHeight = 0;

  if (globalDoc.selection.type == htmlSelectionControl) {
    if (grngMaster.length == 1) {
      elmSelectedImage = grngMaster.item(0);
      if (elmSelectedImage.tagName == "IMG") {
        txtFileName.fImageLoaded = true;
        if (elmSelectedImage.src) {
          txtFileName.value          = elmSelectedImage.src.replace(/^[^*]*(\*\*\*)/, "$1");  // fix placeholder src values that editor converted to abs paths
          txtFileName.intImageHeight = elmSelectedImage.height;
          txtFileName.intImageWidth  = elmSelectedImage.width;
          txtVertical.value          = elmSelectedImage.vspace;
          txtHorizontal.value        = elmSelectedImage.hspace;
          txtBorder.value            = elmSelectedImage.border;
          txtAltText.value           = elmSelectedImage.alt;
          selAlignment.value         = elmSelectedImage.align;
        }
      }
    }
  }
  txtFileName.value = txtFileName.value || "http://";
}

function _isValidNumber(txtBox) {
  var val = parseInt(txtBox);
  if (isNaN(val) || val < 0 || val > 999) { return false; }
  return true;
}

function btnOKClick() {
  var elmImage;
  var intAlignment;
  var htmlSelectionControl = "Control";
  var globalDoc = window.dialogArguments;
  var grngMaster = globalDoc.selection.createRange();
  
  // error checking

  if (!txtFileName.value || txtFileName.value == "http://") { 
    alert("Image URL must be specified.");
    txtFileName.focus();
    return;
  }
  if (txtHorizontal.value && !_isValidNumber(txtHorizontal.value)) {
    alert("Horizontal spacing must be a number between 0 and 999.");
    txtHorizontal.focus();
    return;
  }
  if (txtBorder.value && !_isValidNumber(txtBorder.value)) {
    alert("Border thickness must be a number between 0 and 999.");
    txtBorder.focus();
    return;
  }
  if (txtVertical.value && !_isValidNumber(txtVertical.value)) {
    alert("Vertical spacing must be a number between 0 and 999.");
    txtVertical.focus();
    return;
  }

  // delete selected content and replace with image
  if (globalDoc.selection.type == htmlSelectionControl && !txtFileName.fImageLoaded) {
    grngMaster.execCommand('Delete');
    grngMaster = globalDoc.selection.createRange();
  }
    
  idstr = "\" id=\"556e697175657e537472696e67";     // new image creation ID
  if (!txtFileName.fImageLoaded) {
    grngMaster.execCommand("InsertImage", false, idstr);
    elmImage = globalDoc.all['556e697175657e537472696e67'];
    elmImage.removeAttribute("id");
    elmImage.removeAttribute("src");
    grngMaster.moveStart("character", -1);
  } else {
    elmImage = grngMaster.item(0);
    if (elmImage.src != txtFileName.value) {
      grngMaster.execCommand('Delete');
      grngMaster = globalDoc.selection.createRange();
      grngMaster.execCommand("InsertImage", false, idstr);
      elmImage = globalDoc.all['556e697175657e537472696e67'];
      elmImage.removeAttribute("id");
      elmImage.removeAttribute("src");
      grngMaster.moveStart("character", -1);
      txtFileName.fImageLoaded = false;
    }
    grngMaster = _getTextRange(elmImage);
  }

  if (txtFileName.fImageLoaded) {
    elmImage.style.width = txtFileName.intImageWidth;
    elmImage.style.height = txtFileName.intImageHeight;
  }

  if (txtFileName.value.length > 2040) {
    txtFileName.value = txtFileName.value.substring(0,2040);
  }
  
  elmImage.src = txtFileName.value;
  
  if (txtHorizontal.value != "") { elmImage.hspace = parseInt(txtHorizontal.value); }
  else                           { elmImage.hspace = 0; }

  if (txtVertical.value != "") { elmImage.vspace = parseInt(txtVertical.value); }
  else                         { elmImage.vspace = 0; }
  
  elmImage.alt = txtAltText.value;

  if (txtBorder.value != "") { elmImage.border = parseInt(txtBorder.value); }
  else                       { elmImage.border = 0; }

  elmImage.align = selAlignment.value;
  grngMaster.collapse(false);
  grngMaster.select();
  window.close();
}
</SCRIPT>
</HEAD>
<BODY id=bdy onload="Init()" style="background: threedface; color: windowtext;" scroll=no>

<?php
require_once("../../../stconfig.php");
$dirquery = "SELECT ImageDir FROM " .$DB_Prefix ."_vars WHERE ID=1";
$dirresult = mysql_query($dirquery, $dblink) or die ("Unable to select. Try again later.");
$dirrow = mysql_fetch_row($dirresult);
$imgdir = $dirrow[0];
?>

<?php
if (!$uplImage)
{
?>
<DIV id=divFileName style="left: 0.98em; top: 3.5em; width: 7em; height: 1.2em; ">
<form action="insert_image.php" enctype="multipart/form-data" method="POST" target="_self" style="margin: 0">
OR Upload:
<INPUT name=uplImage type=file style="left: 7.54em; top: 0em; width: 21.5em;height: 2em; " size="20">
<input type=hidden name=img_dir value=<?php echo "$imgdir"; ?>>
<input type="submit" style="left: 30.36em; top: 0em; width: 7em; height: 2em; " value="Upload" name="submitmode">
</form>
</DIV>
<?php
$h1 = "1em;";
$h2 = "6em;";
$h3 = "8em;";
$h4 = "10em;";
$h5 = "12.3em;";
}
else
{
$h1 = "0em;";
$h2 = "2.5em;";
$h3 = "5em;";
$h4 = "6.7em;";
$h5 = "9em;";
}
?>

<DIV id=divFileName style="left: 0.98em; top: <?php echo "$h1"; ?> width: 7em; height: 1.2em; ">Select Image:</DIV>
<BUTTON ID=btnOK style="left: 31.36em; top: <?php echo "$h1"; ?> width: 7em; height: 2em; " type=submit tabIndex=40>OK</BUTTON>
<?php
if ($dir = @opendir("../../../$imgdir")) 
{
$filelist = array();
$f = 1;
while (($file = readdir($dir)) !== false) 
{
if(substr($file, -3) == "gif" OR substr($file, -3) == "jpg" OR substr($file, -4) == "jpeg" OR substr($file, -3) == "png")
{
$setimgval = "<option ";
if ($uplImage == $file OR (!$uplImage AND $f == 1))
$setimgval .= "selected ";
$setimgval .= "value=\"$urldir/$imgdir/$file\">$file</option>";
$filelist[$f] = $setimgval;
++$f;
}
} 
closedir($dir);
}
$optdisplay = implode($filelist, "");
echo "<select ID=txtFileName tabIndex=10 ";
echo "style=\"left: 8.54em; top: $h1 width: 21.5em; height: 2em;\">";
echo "$optdisplay";
echo "</select>";
?>

<DIV id=divAltText style="left: 0.98em; top: <?php echo "$h2"; ?> width: 6.58em; height: 1.2em; ">Alternate Text:</DIV>
<INPUT type=text ID=txtAltText tabIndex=15 style="left: 8.34em; top: <?php echo "$h2"; ?> width: 21.5em; height: 2em; " onfocus="select()">
<BUTTON ID=btnCancel style="left: 31.36em; top: <?php echo "$h2"; ?> width: 7em; height: 2em; " type=reset tabIndex=45 onClick="window.close();">Cancel</BUTTON>

<FIELDSET id=fldLayout style="left: .9em; top: <?php echo "$h3"; ?> width: 17.08em; height: 7em;">
<LEGEND id=lgdLayout>Layout</LEGEND>
</FIELDSET>

<FIELDSET id=fldSpacing style="left: 18.9em; top: <?php echo "$h3"; ?> width: 11em; height: 7em;">
<LEGEND id=lgdSpacing>Spacing</LEGEND>
</FIELDSET>

<DIV id=divAlign style="left: 1.82em; top: <?php echo "$h4"; ?> width: 4.76em; height: 1.2em; ">Alignment:</DIV>
<SELECT size=1 ID=selAlignment tabIndex=20 style="left: 10.36em; top: <?php echo "$h4"; ?> width: 6.72em; height: 2em; ">
<OPTION id=optNotSet value=""> Not set </OPTION>
<OPTION id=optLeft value=left> Left </OPTION>
<OPTION id=optRight value=right> Right </OPTION>
<OPTION id=optTexttop value=textTop> Texttop </OPTION>
<OPTION id=optAbsMiddle value=absMiddle> Absmiddle </OPTION>
<OPTION id=optBaseline value=baseline SELECTED> Baseline </OPTION>
<OPTION id=optAbsBottom value=absBottom> Absbottom </OPTION>
<OPTION id=optBottom value=bottom> Bottom </OPTION>
<OPTION id=optMiddle value=middle> Middle </OPTION>
<OPTION id=optTop value=top> Top </OPTION>
</SELECT>

<DIV id=divHoriz style="left: 19.88em; top: <?php echo "$h4"; ?> width: 4.76em; height: 1.2em; ">Horizontal:</DIV>
<INPUT ID=txtHorizontal style="left: 24.92em; top: <?php echo "$h4"; ?> width: 4.2em; height: 2em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=25 onfocus="select()">

<DIV id=divBorder style="left: 1.82em; top: <?php echo "$h5"; ?> width: 8.12em; height: 1.2em; ">Border Thickness:</DIV>
<INPUT ID=txtBorder style="left: 10.36em; top: <?php echo "$h5"; ?> width: 6.72em; height: 2em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=21 onfocus="select()">

<DIV id=divVert style="left: 19.88em; top: <?php echo "$h5"; ?> width: 3.64em; height: 1.2em; ">Vertical:</DIV>
<INPUT ID=txtVertical style="left: 24.92em; top: <?php echo "$h5"; ?> width: 4.2em; height: 2em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=30 onfocus="select()">

</BODY>
</HTML>
