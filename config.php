<?php

define('DSN', 'mysql:host=localhost;dbname=twitter_2');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

// 相対バス、絶対パスどちらで指定しているかを認識していくこと。
// [ アドバンス ]階層が変わることを考える（拡張性）と相対パスで指定
define('SITE_URL', 'http://192.168.33.10/php/twitter_2/');
define('PASSEWORD_KEY', 'root');

error_reporting(E_ALL & ~E_NOTICE);

session_set_cookie_params(0, '/twitter_2/');
