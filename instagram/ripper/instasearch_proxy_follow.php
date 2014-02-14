<?php require_once('../Connections/localhost.php'); 

// flag to ignore certain photos
$ignore=0;

$proxyauth="ottocid:k0st0golov";
$proxy="us.proxymesh.com:31280";

$tags = array("styleitout", "styleitout", "styleitout", "styleitout");
$ids =array("e13e851525c1418d8e2ae4e3df5420af","f61a2746f7df4402b567dc159a3daa9b","91dcb12e16704f539e730a82afe0f6b6","c7571e246e584f0fb042ecd3c6e2b3bf");

if(isset($_GET['tagcount'])) {
	setcookie("tagcount",$_GET['tagcount'],time()+3600);
} else {
	die("please specify tagcount");
}

	$count=$_GET['tagcount'];
	$currenttag=$_GET['tag'];
	$currentid=$ids[$count];
	echo "current tag:".$count.$currenttag."<br/>";


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


?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Instagram scraping like a MoFo</title>
<script type="text/javascript">
  var timeout = setTimeout("location.reload(true);",60000);
</script>
</head>

<body><?php

$client = "c7571e246e584f0fb042ecd3c6e2b3bf";
$clnum = mt_rand(1,3);

if(isset($_COOKIE['nextpage'])) {
	$api=$_COOKIE['nextpage'];
} else {
	$api = "https://api.instagram.com/v1/tags/".$currenttag."/media/recent?client_id=".$currentid;
}



echo "api call:".$api."<br/>";


function get_curl($url,$header=1) {
	global $lastid,$proxy,$proxyauth;
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_PROXY, "http://us.proxymesh.com:31280"); 
		curl_setopt($ch, CURLOPT_PROXYPORT, 31280); 
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "wassproxy:k0st0golov");
        $output = curl_exec($ch);
		if($output===false) {
			
			save_error("curl api",0,curl_error($ch));
			curl_close($ch);
			die("curl death:");
		}
		curl_close($ch);
        
        
        return $output;
    } else{
        return file_get_contents($url);
    }
}

function get_curl_bin($url) {
	global $lastid,$proxy,$proxyauth,$ignore;
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
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "wassproxy:k0st0golov");
        $output = curl_exec($ch);
		if($output===false) {
			$ignore=1;
			save_error("curl pic",0,curl_error($ch));
			//curl_close($ch);
		}
		curl_close($ch);
        
        
        return $output;
    } else{
        return file_get_contents($url);
    }
}


$response = get_curl($api);
$start_json=strpos($response,"{");
$end_json=strrpos($response,"}");

$header=substr($response,0,$start_json);

$response_parsed=substr($response,$start_json);




    if(preg_match('!X-Ratelimit-Remaining: ([0-9]+)!i', $header, $matches)) {
        $remaining = $matches[1];
    }

$lastid=0;

// Do something based on the number of remaining attempts
echo "Remaining attempts: $remaining";


		$insertSQL = sprintf("INSERT INTO instagram_limits (tag,apikey,instagram_limits.`limit`,instagram_limits.`when`) VALUES (%s,%s,%s,NOW())",
			   GetSQLValueString($currenttag, "text"),
			   GetSQLValueString($currentid, "text"),
			   GetSQLValueString($remaining, "int"));
    
      			mysql_select_db($database_localhost, $localhost);
      			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error()." ".$insertSQL);



$encoded=json_decode($response_parsed);
print_r($encoded->pagination->next_url);
setcookie("nextpage",$encoded->pagination->next_url,time()+3600);
if(isset($encoded->meta->error_message)) {
 
	save_error($encoded->meta->error_type,$encoded->meta->code,$encoded->meta->error_message);
	die("got error:".$encoded->meta->error_message);
}

if(isset($encoded->data)) {
echo "lenght or results:".count($encoded->data)."<br/>";
}

if(isset($encoded->data)){
	$conto=pull_data($encoded);
	echo "inserted ".$conto." images <br/>";
	} else {
	echo "no response<br/>";
}

function pull_data($encoded) {
	global $currenttag,$response;
	global $database_localhost, $localhost,$ignore;
	
	$insertcount=0;
	
	foreach($encoded->data as $item){	
		//echo "caption ".$item->caption->text."<br/>";
	
		$date  =$item->created_time;
		$likes = $item->likes->count;
        $src = $item->images->standard_resolution->url;
        $thumb = $item->images->thumbnail->url;
		$url = $item->link;
		$username = $item->user->username;
		$userpic = $item->user->profile_picture;
		$userfullname = $item->user->full_name;
		if(isset($item->caption->text)) {
			$caption = $item->caption->text;
		} else {
			$caption = "";
		}
		
		$id = $item->id;
		
		mysql_select_db($database_localhost, $localhost);
$query_tweets = "SELECT instagram_id FROM instagram where instagram_id = '$id'";
		$tweets = mysql_query($query_tweets, $localhost) or die("$query_limit_tweets".mysql_error());
		$totalRows_tweets = mysql_num_rows($tweets);
		$row_tweets = mysql_fetch_assoc($tweets);
		

		if(!isset($item->link)) {
			$ignore=1;
		}
		
		if($totalRows_tweets==0) {
			
			
		$photo=get_curl_bin($userpic);
		file_put_contents('images/'.basename($userpic), $photo);
		$photo=get_curl_bin($thumb);
		file_put_contents('images/'.basename($thumb), $photo);
		$photo=get_curl_bin($src);
		file_put_contents('images/'.basename($src), $photo);
		

		$insertSQL = sprintf("INSERT IGNORE INTO instagram (instagram_id,likes,src,thumb,url,date,username,userpic,userfullname,tag,created,caption,instagram.`ignore`) VALUES (%s,%s,%s,%s,%s,FROM_UNIXTIME(%s),%s,%s,%s,'".$currenttag."',NOW(),%s,%s)",
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
			   GetSQLValueString($ignore, "int"));
    
      			mysql_select_db($database_localhost, $localhost);
      			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error()." ".$insertSQL);
			
				$insertcount++;

		} 
		
		

    }
	
	return $insertcount;

}

function save_error($error_type,$code,$error_message) {
	global $database_localhost, $localhost,$currenttag;
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
	
	if($errline==91) {
		$extra=" ".$response;
	} else {
		$extra="";
	}
	
     save_error("php",$errno,$currenttag." line".$errline." ".$errstr.$extra);
  

    /* Don't execute PHP internal error handler */
    return true;
}

?></body>
</html>