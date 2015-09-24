<?php

require_once('config.php');
require_once('functions.php');


	$search_word = $_POST['body'];

	$dbh = connectDb();


// 普通の一覧表示と検索後の一覧表示を条件分岐で分けないといけない、、、と思う！
	$sql = "select * from posts where body like '%$search_word%'";
	$stmt = $dbh->query($sql);
	$results = $stmt->fetchAll();

	var_dump($results);
	// 検証結果、、、search.phpではきちんとセレクトされて、results変数に入ってるので、search.phpからindex.phpにいく時にNULLになってる
	header('Location: '.SITE_URL);
	exit;

?>