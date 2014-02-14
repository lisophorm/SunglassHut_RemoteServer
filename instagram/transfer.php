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

mysql_select_db($database_localhost, $localhost);
$query_instagrams = "SELECT * FROM instagram WHERE transferred = 0 and approved = 1 ORDER BY created ASC limit 5";
$instagrams = mysql_query($query_instagrams, $localhost) or die(mysql_error());
$row_instagrams = mysql_fetch_assoc($instagrams);
$totalRows_instagrams = mysql_num_rows($instagrams);

// no new images, attempt to download images with problems
if($totalRows_instagrams == 0) {
	mysql_select_db($database_localhost, $localhost);
	$query_instagrams = "SELECT * FROM instagram WHERE transferred = 9 and approved = 1 ORDER BY created ASC limit 5";
	$instagrams = mysql_query($query_instagrams, $localhost) or die(mysql_error());
	$row_instagrams = mysql_fetch_assoc($instagrams);
	$totalRows_instagrams = mysql_num_rows($instagrams);
}

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$json = array();
do { 
$json[] = $row_instagrams;
  } while ($row_instagrams = mysql_fetch_assoc($instagrams)); 

echo json_encode($json);

mysql_free_result($instagrams);
?>
