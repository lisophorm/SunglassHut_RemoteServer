<?php require_once('../Connections/localhost.php'); ?>
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
$query_oldpics = "select * from instagram where instagram.created < DATE_SUB(NOW(),INTERVAL 5 HOUR)";
$oldpics = mysql_query($query_oldpics, $localhost) or die(mysql_error());
$row_oldpics = mysql_fetch_assoc($oldpics);
$totalRows_oldpics = mysql_num_rows($oldpics);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table border="1">
  <tr>
    <td>id</td>
    <td>ignore</td>
    <td>tag</td>
    <td>instagram_id</td>
    <td>likes</td>
    <td>src</td>
    <td>thumb</td>
    <td>url</td>
    <td>date</td>
    <td>username</td>
    <td>userpic</td>
    <td>userfullname</td>
    <td>created</td>
    <td>caption</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_oldpics['id']; ?></td>
      <td><?php echo $row_oldpics['ignore']; ?></td>
      <td><?php echo $row_oldpics['tag']; ?></td>
      <td><?php echo $row_oldpics['instagram_id']; ?></td>
      <td><?php echo $row_oldpics['likes']; ?></td>
      <td><?php @unlink("images/".$row_oldpics['src']); ?></td>
      <td><?php @unlink("images/".$row_oldpics['thumb']); ?></td>
      <td><?php echo $row_oldpics['url']; ?></td>
      <td><?php echo $row_oldpics['date']; ?></td>
      <td><?php echo $row_oldpics['username']; ?></td>
      <td><?php @unlink("images/".$row_oldpics['userpic']); ?></td>
      <td><?php echo $row_oldpics['userfullname']; ?></td>
      <td><?php echo $row_oldpics['created']; ?></td>
      <td><?php echo $row_oldpics['caption']; ?></td>
    </tr>
    <?php } while ($row_oldpics = mysql_fetch_assoc($oldpics)); ?>
</table>
</body>
</html>
<?php

mysql_free_result($oldpics);

?>
