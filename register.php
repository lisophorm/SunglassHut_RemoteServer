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




$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['urn']))) {
$insertSQL = sprintf("insert ignore into stats (stats.urn,stats.email_hash,stats.post_type) values (%s,MD5(%s),'email')",
                       GetSQLValueString($_POST['urn'], "text"),
					   GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode("error:".mysql_error()."query:".$insertSQL ));
	
	
  $insertSQL = sprintf("INSERT INTO users (urn, firstname, lastname, email,mobile,current_location,extraterms) VALUES (%s, %s, %s, %s, %s,%s,%s)",
                       GetSQLValueString($_POST['urn'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['mobile'], "text"),
					   GetSQLValueString($_POST['current_location'], "text"),
					   GetSQLValueString($_POST['extraterms'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die("result=ERROR&message=".urlencode(mysql_error()));
  die("result=OK");
} else {
	 die("result=ERROR&message=".urlencode("No urn set!"));
}
?>