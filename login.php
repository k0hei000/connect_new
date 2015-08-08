<?php
function Login(){

session_start();
require_once __DIR__ . '/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';


$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.3',
  ]);


$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/connect2015/signup.php', $permissions);


echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}
?>