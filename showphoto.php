<?php require_once('Connections/localhost.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$updateSQL = sprintf("update stats set stats.facebook_hit_email=stats.facebook_hit_email+1 where stats.urn=%s",
                       GetSQLValueString($_GET['urn'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die("result=ERROR&message=".$updateSQL.(mysql_error()));

$colname_photo = "-1";
if (isset($_GET['urn'])) {
  $colname_photo = $_GET['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_photo = sprintf("SELECT * FROM userphoto WHERE urn = %s", GetSQLValueString($colname_photo, "text"));
$photo = mysql_query($query_photo, $localhost) or die(mysql_error());
$row_photo = mysql_fetch_assoc($photo);
$totalRows_photo = mysql_num_rows($photo);

$colname_user = "-1";
if (isset($_GET['urn'])) {
  $colname_user = $_GET['urn'];
}
mysql_select_db($database_localhost, $localhost);
$query_user = sprintf("SELECT * FROM users WHERE urn = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $localhost) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:title" content="Sunglass Hut" />
<meta property="fb:admins" content="595373701" />
<meta property="fb:app_id" content="606919926007758" />
<meta property="og:image" content="http://sunglasshut.wassermanexperience.com/thumbs/<?php echo $row_photo['filename']; ?>" />
<meta property="og:description" content="Which Sunglass Hut look do you prefer? #styleitout" />
<meta property="og:url" content="http://sunglasshut.wassermanexperience.com/showphoto.php?urn=<?php echo $row_user['urn']; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sunglass Hut</title>
<style type="text/css">
body, td, th {
	color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
}
body {
	background-color: #000000;
}
</style>
</head>

<body>
<div style="width:100%;margin-left:auto;margin-right:auto;"><div style="text-align:center;width:100%;padding-bottom:30px;margin-bottom:30px;border-bottom:4px solid #F383C3;"><img src="/emailer/logo_page.gif" width="242" height="245" alt="Sunglass Hut" /></div>
  <div style="width:100%;clear:both;text-align:center;"><a href="https://www.facebook.com/SunglassHut"><img src="http://sunglasshut.wassermanexperience.com/rendered/<?php echo $row_photo['filename']; ?>" width="238" height="800" /></a></div>
  <div style="text-align:center;padding-top:30px;margin-top:30px;border-top:4px solid #F383C3;"><a href="http://sunglasshut.wassermanexperience.com/sharephoto.php?urn=<?php echo $_GET['urn']; ?>"><img src="/emailer/shareonfb.gif" style="border:none;" width="289" height="59" alt="Share on Facebook" /></a><span style="text-align: center"></span></div>
  <div style="text-align:center;margin-top:30px;"><a href="http://sunglasshut.wassermanexperience.com/download.php?urn=<?php echo $_GET['urn']; ?>&filename=<?php echo $row_photo['filename']; ?>"><img style="border:none;" src="/emailer/downloadphoto.gif" width="289" height="56" alt="download photo" /></a></div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41838708-1', 'wassermanexperience.com');
  ga('send', 'pageview');

</script>
</body>
</html>
<?php
mysql_free_result($photo);

mysql_free_result($user);
?>
