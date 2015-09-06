<?php

session_start();

require_once('config.php');
require_once('functions.php');

$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-86400, '/twitter_2/');
}

session_destroy();

header('Location: '.SITE_URL.'login.php');

?>