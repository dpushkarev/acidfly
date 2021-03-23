<?php
// Version 2 - Works with IE 5.5+ only
if (file_exists("htmlarea/editor.js"))
{
?>
<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
  document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>
<?php
}

// Version 3 - works with IE 5.5+ and Mozilla 1.3+
else if (file_exists("htmlarea/htmlarea.js"))
{
?>
<script type="text/javascript">
_editor_url = "htmlarea/";
_editor_lang = "en";
</script>
<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript">
var config = new HTMLArea.Config();
config.toolbar = [
["fontname", "space",
"fontsize", "space",
"bold", "italic", "underline", "separator",
"copy", "cut", "paste", "space", "undo", "redo" ],
[ "justifyleft", "justifycenter", "justifyright", "separator",
"insertorderedlist", "insertunorderedlist", "outdent", "indent", "separator",
"forecolor", "hilitecolor", "separator",
"inserthorizontalrule", "createlink", "insertimage", "inserttable", "separator", 
"htmlmode", "popupeditor", "about"]
];
</script>
<?php
}
?>
