<?php

session_start();

require_once('config.php');
require_once('functions.php');

if (!empty($_SESSION['me'])) {
	header('Location:'.SITE_URL);
}

function getUser($user_name, $password, $dbh) {
	$sql = "select * from users where user_name = :user_name and password = :password limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":user_name"=>$user_name, ":password"=>getSha1Password($password)));
	// 返り値のPDOオブジェクトに対してfetchメソッドを実行し、結果セットを配列で取得している
	$user = $stmt->fetch();
	return $user ? $user : false;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	// 投稿前

	// CSRF対策
	setToken();	
} else {
	// 投稿後
	checkToken();

	$user_name = $_POST['user_name'];
	$password = $_POST['password'];


	// DB接続した値を＄dbhにぶっこんでいる
	$dbh = connectDb();

	$error = array();

	// エラー処理
	// メールアドレスメールアドレスとパスワードが正しくない
	// この書き方でもOK → if (!$me = getUser($user_name, $password, $dbh)) {
	$me = getUser($user_name, $password, $dbh);

	// 名前が空
	if ($password == '') {
		$error['user_name'] = 'お名前を入力してください';
	}

	if (!$me) {
		$error['password'] = 'お名前とパスワードが正しくありません。';
	}

	// パスワードが空
	if ($password == '') {
		$error['password'] = 'パスワードを入力してください';
	}

	if (empty($error)) {
		// セッションハイジャック対策...これやると一旦動かないから置いておく。
		// session_regenerate_id(true);
		$_SESSION['me'] = $me;
			header('Location: '.SITE_URL);
		exit;
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>ログイン画面</title>
	</head>
	<body class="japan_blue white_font">
		<h1>ありさんたちが巣穴にログインしています</h1>
		<p> ---以下フォームにご入力ください--- </p>
		<form action="" method="POST">
			<p>
				お名前：
				<input type="text" name="user_name" value="<?php echo h($user_name); ?>"> 
				<span class="error">
					<?php echo h($error['user_name']); ?>
				</span>
			</p>
			<p>
				パスワード：
				<input type="password" name="password" value=""> 
				<span class="error">
					<?php echo h($error['password']); ?>
				</span>
			</p>
			<p><input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>"></p>
			<p><input type="submit" value="ログイン！"> <a href="signup.php">新規登録してみる</a></p>
		</form>
	</body>
</html>