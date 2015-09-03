<?php

session_start();

require_once('config.php');
require_once('functions.php');


?>

<html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>ユーザー登録</title>
	</head>
	<body>
		<h1>ユーザー登録</h1>
		<form action="" method="POST">
			<p>
				お名前：
				<input type="text" name="name" value="">
			</p>
			<p>
				パスワード：
				<input type="password" name="password" value="">
			</p>
			<p>
				<input type="hidden" name="token" value="">
			</p>
			<p>
				<input type="submit" value="新規登録！"> 
				<a href="index.php">
					戻る
				</a>
			</p>
		</form>
	</body>
</html>