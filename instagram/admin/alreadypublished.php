<?php require_once('../Connections/localhost.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_instagrams = 40;
$pageNum_instagrams = 0;
if (isset($_GET['pageNum_instagrams'])) {
  $pageNum_instagrams = $_GET['pageNum_instagrams'];
}
$startRow_instagrams = $pageNum_instagrams * $maxRows_instagrams;

mysql_select_db($database_localhost, $localhost);
$query_instagrams = "SELECT id, approved, tag, instagram_id, thumb, `date`, userpic, userfullname, created, caption, transferred FROM instagram where transferred=1 ORDER BY created DESC";
$query_limit_instagrams = sprintf("%s LIMIT %d, %d", $query_instagrams, $startRow_instagrams, $maxRows_instagrams);
$instagrams = mysql_query($query_limit_instagrams, $localhost) or die(mysql_error());
$row_instagrams = mysql_fetch_assoc($instagrams);

if (isset($_GET['totalRows_instagrams'])) {
  $totalRows_instagrams = $_GET['totalRows_instagrams'];
} else {
  $all_instagrams = mysql_query($query_instagrams);
  $totalRows_instagrams = mysql_num_rows($all_instagrams);
}
$totalPages_instagrams = ceil($totalRows_instagrams/$maxRows_instagrams)-1;

$queryString_instagrams = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_instagrams") == false && 
        stristr($param, "totalRows_instagrams") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_instagrams = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_instagrams = sprintf("&totalRows_instagrams=%d%s", $totalRows_instagrams, $queryString_instagrams);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NBA</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
}
</style>
<script type="text/javascript"  src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    setInterval(function() {
		$.get("../ripper/instasearch.php",function(data) {
			console.log(data);
			location.reload();
		});
}, 30000);
});

</script>
</head>

<body>
<p>
<table border="1" style="margin-left:auto;margin-right:auto;">
  <tr  style="text-align: center; color: #FFF;">
    <td colspan="11" align="left" ><a href="prefs.php">change preferences </a>- <a href="automoderator.php">back to moderation</a></td>
  </tr>
  <tr bgcolor="#000000" style="text-align: center; color: #FFF;">
    <td><strong>ID</strong></td>
    <td><strong>APPROVED</strong></td>
    <td>&nbsp;</td>
    <td><strong>TAG</strong></td>
    <td align="center"><strong>thumb</strong></td>
    <td><strong>date</strong></td>
    <td align="center"><strong>userpic</strong></td>
    <td><strong>userfullname</strong></td>
    <td><strong>created</strong></td>
    <td width="300"><strong>CAPTION</strong></td>
    <td><strong>transferred</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_instagrams['id']; ?></td>
      <td><div id="cell<?php echo $row_instagrams['id']; ?>"><a href="censor.php?approved=1&amp;id=<?php echo $row_instagrams['id']; ?>" class="allow">allow</a> / <a href="censor.php?approved=0&amp;id=<?php echo $row_instagrams['id']; ?>" class="block">block</a></div></td>
      <td><div class="status"><?php echo $row_instagrams['approved']==1?"APPROVED":"BLOCKED";  ?></div></td>
      <td><?php echo $row_instagrams['tag']; ?></td>
      <td align="center"><img name="" src="../ripper/images/<?php echo $row_instagrams['thumb']; ?>" alt="" /></td>
      <td><?php echo $row_instagrams['date']; ?></td>
      <td align="center"><img src="../ripper/images/<?php echo $row_instagrams['userpic']; ?>" alt="" width="50" height="50" /></td>
      <td><?php echo $row_instagrams['userfullname']; ?></td>
      <td><?php echo $row_instagrams['created']; ?></td>
      <td width="300"><div style="width:300px;overflow:hidden;"><?php echo wordwrap($row_instagrams['caption'],100); ?></div></td>
      <td><?php echo $row_instagrams['transferred']; ?></td>
    </tr>
    <?php } while ($row_instagrams = mysql_fetch_assoc($instagrams)); ?>
        <tr>
      <td colspan="11" align="center"><a href="<?php echo $logoutAction ?>"></a>
        <table border="0">
        <tr>
          <td><?php if ($pageNum_instagrams > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_instagrams=%d%s", $currentPage, 0, $queryString_instagrams); ?>">First</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_instagrams > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_instagrams=%d%s", $currentPage, max(0, $pageNum_instagrams - 1), $queryString_instagrams); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_instagrams < $totalPages_instagrams) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_instagrams=%d%s", $currentPage, min($totalPages_instagrams, $pageNum_instagrams + 1), $queryString_instagrams); ?>">Next</a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_instagrams < $totalPages_instagrams) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_instagrams=%d%s", $currentPage, $totalPages_instagrams, $queryString_instagrams); ?>">Last</a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table>
      <br />
      <a href="<?php echo $logoutAction ?>">Log out</a></td>
    </tr>

</table>
<p>
<p><script type="text/javascript">
$(".allow").click(function(e) {
	var status=$(this).parent().parent().parent().find(".status");
	var dest=$(this).attr("href");
    $.get(dest);
	status.html("APPROVED");
	return false;
});
$(".block").click(function(e) {
	var status=$(this).parent().parent().parent().find(".status");
	var dest=$(this).attr("href");
    $.get(dest);
	status.html("BLOCKED");
	return false;
});
$(document).ready(function(e) {
   $("tr:odd").addClass("odd");
});
</script>
</body>
</html>
<?php
mysql_free_result($instagrams);

?>
