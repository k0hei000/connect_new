<?php

//データベース関連
define('DSN', 'mysql:dbname=LAA0628410-connect;host=mysql024.phy.lolipop.lan');
define('DB_USER', 'LAA0628410');
define('DB_PASSWORD', 'connect2015');

//facebook関連
define('APP_ID', '436865333162259');
define('APP_SECRET', 'e5734ceef09b1e70dbaea90660ede073');
define('FACEBOOK_SDK_V4_SRC_DIR', dirname($_SERVER['SCRIPT_FILENAME'])."/facebook-php-sdk-v4/src/Facebook/");


//画像関連
define('IMAGES_DIR', dirname($_SERVER['SCRIPT_FILENAME'])."/images");
define('THUMBNAILS_DIR', dirname($_SERVER['SCRIPT_FILENAME'])."/thumbnails");
define('THUMBNAILS_WIDTH', 72);
define('MAX_SIZE', 307200);

//GD
if(!function_exists('imagecreatetruecolor')){
	echo "GDがインストールされていません!";
	exit;
}

//その他
define('SITE_URL', 'http://k0hei.science/connect/');
define('PASSWORD_KEY', 'sfoasnvosa');

error_reporting(E_ALL & ~E_NOTICE);
ini_set( 'display_errors', 1 );

session_set_cookie_params(0, '/k0hei.science/connect/');