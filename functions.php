<?php

// PDOオブジェクト取得時（DB接続時）に失敗した場合、PDOExceptionというエラーを出すため、try　catchで例外処理をしている
function connectDb() {
	try {
		return new PDO(DSN, DB_USER, DB_PASSWORD);
	} catch (PDOException $e) {
		echo $e ->getMessage();
		exit;
	}
}



function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}



function setToken() {
	if (!isset($_SESSION['token'])) {
		$_SESSION['token'] = sha1(uniqid(mt_rand(), true));
	}
}



function checkToken() {
	if (empty($_POST['token']) || $_POST['token'] !== h($_SESSION['token'])) {
		echo "不正な処理です！";
		// var_dump($_SESSION['token']);
		// var_dump($_POST['token']);
		exit;
	}
}



function getSha1Password($input_password) {
	return (sha1($input_password));
}


function emailExists($email, $dbh) {
	$sql = "select * from users where email = :email limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":email" => $email));
	$user = $stmt->fetch();
	return $user ? true : false;
}