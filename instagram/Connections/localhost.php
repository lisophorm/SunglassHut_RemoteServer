<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "localhost";
$database_localhost = "admin_instagram_sunglasshut";
$username_localhost = "sunglasshut";
$password_localhost = "bug00000002";
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR); 
?><?php
define("AUTOAPPROVED","0",true);
?>