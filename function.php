<?php
// use Facebook\FacebookSession;
// use Facebook\FacebookRequest;
// use Facebook\GraphUser;
// use Facebook\FacebookRequestException;
// use Facebook\FacebookJavaScriptLoginHelper;

function connectDb(){
	try {
		return new PDO(DSN, DB_USER, DB_PASSWORD);
	} catch (PDOException $e){
		echo $e->getMessage();
		exit;
	}
}

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

function setToken() {
	$token = sha1(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
}

function checkToken() {
	if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])) {
		echo "不正な処理が行われました。";
		exit;
	}
}

function emailExist($email, $dbh){
	$sql = "select * from users where email = :email limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":email" => $email));
	$user = $stmt->fetch();
	return $user ? true : false;
}

function userExist($facebook_id, $dbh){
	$sql = "select * from users where facebook_id = :facebook_id limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":facebook_id" => $facebook_id));
	$user = $stmt->fetch();
	return $user ? true : false;
}



function getSha1Password($s){
	return (sha1(PASSWORD_KEY.$s));
}



function getuser($a) {
  $dbh = connectDb();
  $sql = "select * from users where id = ".$a;
  $stmt = $dbh->query($sql);
  $stmt->execute;
  $user = $stmt->fetch();
  return $user;
}

function getcategory($a){
  $dbh = connectDb();
  $sql = "select * from categories where id = ".$a;
  $stmt = $dbh->query($sql);
  $stmt->execute;
  $category = $stmt->fetch();
  return $category;
}

function getuniversity($a){
  $dbh = connectDb();
  $sql = "select * from universities where id = ".$a;
  $stmt = $dbh->query($sql);
  $stmt->execute;
  $university = $stmt->fetch();
  return $university;
}

function getimage($a){
  $dbh = connectDb();
  $sql = "select * from images where id = ".$a;
  $stmt = $dbh->query($sql);
  $stmt->execute;
  $image = $stmt->fetch();
  return $image;
}

function Login(){
session_start();
require __DIR__ . '/facebook-php-sdk-v4-5.0/src/Facebook/autoload.php';
//require_once $_SERVER['SCRIPT_FILENAME'].'/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.3',
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl(__DIR__. '/signup.php', $permissions);
//$loginUrl = $helper->getLoginUrl(__DIR__. '/signup.php');

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}

