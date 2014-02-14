<?php require 'php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '606919926007758',
  'secret' => 'a7211abe6bbb1a107305d4f24a758a95',
  'cookie' => true
));

$fb_key = 'fbs_'.sfConfig::get('app_facebook_application_id');
  set_cookie($fb_key, '', '', '', '/', '');
  $facebook->setSession(NULL);

?>
