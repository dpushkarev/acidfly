<script language="php">
if (isset($_REQUEST[changetheme]) AND !ctype_alnum(str_replace(array("_", "-", ".htm"), "", $_REQUEST[changetheme])))
die("Invalid theme");
header("Content-Type: text/css");
</script>
