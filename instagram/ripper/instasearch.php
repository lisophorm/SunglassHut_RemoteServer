<?php require_once('../Connections/localhost.php'); ?>
<?php 
error_reporting(E_ALL);

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
$old_error_handler = set_error_handler("myErrorHandler");
?><?php
mysql_select_db($database_localhost, $localhost);
$query_prefs = "SELECT * FROM prefs";
$prefs = mysql_query($query_prefs, $localhost) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);

$tags = array($row_prefs['hash1'], $row_prefs['hash2'], $row_prefs['hash3'], $row_prefs['hash4']);
$ids =array("7f08a654b0ed4a8fa8f9102f46905cb3","7f08a654b0ed4a8fa8f9102f46905cb3","7f08a654b0ed4a8fa8f9102f46905cb3","7f08a654b0ed4a8fa8f9102f46905cb3");

if(!isset($_COOKIE['tagcount'])) {
	echo "first time<br/>";
	setcookie("tagcount",0,time()+3600);
	$currenttag=$tags[0];
	$currentid=$ids[0];
} else {
	$count=$_COOKIE['tagcount'];
	$count++;
	$count = $count % 4;
	setcookie("tagcount",$count,time()+3600);
	$currenttag=$tags[$count];
	$currentid=$ids[$count];
	echo "current tag:".$count.$currenttag."<br/>";
}



?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript">
//var timeout = setTimeout("location.reload(true);",30000);
</script>
</head>

<body><?php

$client = "7f08a654b0ed4a8fa8f9102f46905cb3";
$clnum = mt_rand(1,3);

$api = "https://api.instagram.com/v1/tags/".$currenttag."/media/recent?client_id=".$currentid;


function get_curl($url) {
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_PROXY, "http://us.proxymesh.com:31280"); 
		curl_setopt($ch, CURLOPT_PROXYPORT, 31280); 
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "wasserman:k0st0golov");
        $output = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
		if($output===false || $http_status!=200) {
			$ignore=1;
			save_error("curl pic",0,curl_error($ch));
			return false;
		}
        return $output;
    } else{
        return file_get_contents($url);
    }
}

function get_curl_bin($url) {
	global $lastid,$proxy,$proxyauth,$ignore;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_PROXY, "http://us.proxymesh.com:31280"); 
		curl_setopt($ch, CURLOPT_PROXYPORT, 31280); 
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "wasserman:k0st0golov");
        $output = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($output===false || $http_status!=200) {
			$ignore=1;
			save_error("curl pic",0,curl_error($ch));
			return false;
		}
		
        
        
        return $output;
}

$response = get_curl($api);
if ($response===false) {
	
}
$images = array();

$encoded=json_decode($response);
if(isset($encoded->meta->error_message)) {
 
	save_error($encoded->meta->error_type,$encoded->meta->code,$encoded->meta->error_message);
	die("got error:".$encoded->meta->error_message);
}
//print_r($encoded);
//die();
echo "data length".count($encoded->data)."<br/>";
if($response){
	foreach($encoded->data as $item){	
		$date  =$item->created_time;
		$likes = $item->likes->count;
        $src = $item->images->standard_resolution->url;
        $thumb = $item->images->thumbnail->url;
		$url = $item->link;
		$username = $item->user->username;
		$userpic = $item->user->profile_picture;
		$userfullname = $item->user->full_name;
		$caption = $item->caption->text;
		$id = $item->id;
		
		mysql_select_db($database_localhost, $localhost);
$query_tweets = "SELECT instagram_id FROM instagram where instagram_id = '$id'";
		$tweets = mysql_query($query_tweets, $localhost) or die("$query_limit_tweets".mysql_error());
		$totalRows_tweets = mysql_num_rows($tweets);
		$row_tweets = mysql_fetch_assoc($tweets);
		
		echo "rows ".$totalRows_tweets."<br/>";
		
		if($totalRows_tweets==0) {
			echo "inserting ".$id."<br/>";
			
		$userphoto=get_curl_bin($userpic);
		
		$thumbphoto=get_curl_bin($thumb);

		$srcphoto=get_curl_bin($src);
		
		if($userphoto!=false && $thumbphoto != false && $srcphoto !=false) {
			$transferred=0;
			file_put_contents('images/'.basename($src), $srcphoto);
			file_put_contents('images/'.basename($userpic), $userphoto);
			file_put_contents('images/'.basename($thumb), $thumbphoto);
		} else {
			$transferred=5;
		}
		
		

		$insertSQL = sprintf("INSERT INTO instagram (instagram_id,likes,src,thumb,url,date,username,userpic,userfullname,tag,created,caption,transferred,approved) VALUES (%s,%s,%s,%s,%s,FROM_UNIXTIME(%s),%s,%s,%s,'".$currenttag."',NOW(),%s,%s,%s)",
			   GetSQLValueString($id, "text"),
			   GetSQLValueString($likes, "int"),
			   GetSQLValueString(basename($src), "text"),
			   GetSQLValueString(basename($thumb), "text"),
			   GetSQLValueString($url, "text"),
			   GetSQLValueString($date, "text"),
			   GetSQLValueString($username, "text"),
			   GetSQLValueString(basename($userpic), "text"),
			   GetSQLValueString($userfullname, "text"),
			   GetSQLValueString($caption, "text"),
			   GetSQLValueString($transferred, "text"),
			   $row_prefs['autoapprove']);
    
      			mysql_select_db($database_localhost, $localhost);
      			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error()." ".$insertSQL);
			
		

		} else {
			echo "instafield present" .$id."<br/>";
		}
		
		

    }
} else {
	echo "no response<br/>";
}

function save_error($error_type,$code,$error_message) {
	global $database_localhost, $localhost;
	$insertSQL = sprintf("INSERT into instagram_messages (error_type,`code`,error_message,`when`) VALUES (%s,%s,%s,NOW())",
                           GetSQLValueString($error_type, "text"),
						   GetSQLValueString($code, "text"),
						   GetSQLValueString($currenttag." ".$error_message, "text"));
    
      					mysql_select_db($database_localhost, $localhost);
      					$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error()." ".$insertSQL);
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	global $currenttag,$response;
	global $database_localhost, $localhost;
	
	if($errline==101) {
		$extra=" ".$response;
	} else {
		$extra="";
	}
	
     save_error("php",$errno,$currenttag." line".$errline." ".$errstr.$extra);
  

    /* Don't execute PHP internal error handler */
    return true;
}


die();
?></body>
</html>
<?php
mysql_free_result($prefs);
?>
