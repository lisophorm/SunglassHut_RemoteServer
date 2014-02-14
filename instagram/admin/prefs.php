<?php require_once('../Connections/localhost.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
$query_checkprefs = "SELECT * FROM prefs";
$checkprefs = mysql_query($query_checkprefs, $localhost) or die(mysql_error());
$row_checkprefs = mysql_fetch_assoc($checkprefs);
$totalRows_checkprefs = mysql_num_rows($checkprefs);

if ($totalRows_checkprefs == 0) {
	echo "generating preferences<br/>";
	  $insertSQL = sprintf("INSERT INTO prefs (id, lastretrieve, hash1, hash2, hash3, hash4, autoapprove) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "int"),
                       GetSQLValueString(70, "int"),
                       GetSQLValueString("hashtag1", "text"),
                       GetSQLValueString("hashtag2", "text"),
                       GetSQLValueString("hashtag3", "text"),
                       GetSQLValueString("hashtag4", "text"),
                       GetSQLValueString(0,"int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  
  	$query_checkprefs = "SELECT * FROM prefs";
	$checkprefs = mysql_query($query_checkprefs, $localhost) or die(mysql_error());
	$row_checkprefs = mysql_fetch_assoc($checkprefs);
	$totalRows_checkprefs = mysql_num_rows($checkprefs);
  
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE prefs SET lastretrieve=%s, hash1=%s, hash2=%s, hash3=%s, hash4=%s, autoapprove=%s",
                       GetSQLValueString($_POST['lastretrieve'], "int"),
                       GetSQLValueString($_POST['hash1'], "text"),
                       GetSQLValueString($_POST['hash2'], "text"),
                       GetSQLValueString($_POST['hash3'], "text"),
                       GetSQLValueString($_POST['hash4'], "text"),
                       GetSQLValueString(isset($_POST['autoapprove']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "automoderator.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sunglass Hut</title>
<style type="text/css">
body,td,th {
	font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
}
</style>
</head>

<body>
<div style="margin-left:auto;margin-right:auto;text-align:center;">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
<h2>Edit preferences</h2>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="600" border="1">
    <tr>
      <td>Last instagrams</td>
      <td><label for="lastretrieve"></label>
      <input name="lastretrieve" type="text" id="lastretrieve" value="<?php echo $row_checkprefs['lastretrieve']; ?>" size="50" /></td>
    </tr>
    <tr>
      <td>Hash #1</td>
      <td><label for="hash1"></label>
      <input name="hash1" type="text" id="hash1" value="<?php echo $row_checkprefs['hash1']; ?>" size="50" /></td>
    </tr>
    <tr>
      <td>Hash #2</td>
      <td><label for="hash2"></label>
      <input name="hash2" type="text" id="hash2" value="<?php echo $row_checkprefs['hash2']; ?>" size="50" /></td>
    </tr>
    <tr>
      <td>Hash #3</td>
      <td><label for="hash3"></label>
      <input name="hash3" type="text" id="hash3" value="<?php echo $row_checkprefs['hash3']; ?>" size="50" /></td>
    </tr>
    <tr>
      <td>Hash #4</td>
      <td><label for="hash4"></label>
      <input name="hash4" type="text" id="hash4" value="<?php echo $row_checkprefs['hash4']; ?>" size="50" /></td>
    </tr>
    <tr>
      <td>Autoapprove</td>
      <td><input <?php if (!(strcmp($row_checkprefs['autoapprove'],1))) {echo "checked=\"checked\"";} ?> name="autoapprove" type="checkbox" id="autoapprove" value="1" />
      <label for="autoapprove"></label></td>
    </tr>
    <tr>
      <td><a href="automoderator.php">cancel</a></td>
      <td><input type="submit" name="button" id="button" value="Submit" />
      <input name="theid" type="hidden" id="theid" value="1" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($checkprefs);
?>
