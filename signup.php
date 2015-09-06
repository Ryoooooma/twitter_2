<?php

session_start();

require_once('config.php');
require_once('functions.php');

// ポストされているかどうかをまず確認 → されてたら挙動開始させる
if ($_SERVER['REQUEST_METHOD'] != "POST") {

	// CSRF対策 → functions.php参照（ポスト以外の処理だったらセッション変数tokenに乱数をセットする）
	setToken();	

} else {

	// ここまでがCSRF対策
	// ポストされた際の挙動記載（フォームでセットしたhiddenポストtokenが空、もしくはセッション変数tokenとhiddenポストtokenが等しくない時にエラーを出す）
	checkToken();

	// データを扱いやすくするために、フォームからポストされた内容を各変数に突っ込んでおく
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];

	// そのあとDBに接続する
	$dbh = connectDb();

	// 以下エラー処理を記載
	// 配列で初期化しておく
	$error = array();
	
	// 名前が空かどうかチェック
	if ($user_name == '') {
		$error['user_name'] = '名前を入力してください';
	}
	
	// パスワードが空かどうかチェック
	if ($password == '') {
		$error['password'] = 'パスワードを入力してください';
	}

	// 上記のエラーチェックをパスしたら登録処理を実行する
	if (empty($error)) {
		$sql = "insert into users
				(user_name, password, created, modified)
				values
				(:user_name, :password, now(), now())";
		$stmt = $dbh->prepare($sql);
		$params = array(
			":user_name" => $user_name,
			":password" => getSha1Password($password)
		);
		$stmt->execute($params);

		// 登録処理後、ログインページへリダイレクト処理をおこなう
		header('Location: '.SITE_URL.'login.php');
		exit;
	}
}

?>

<html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>ユーザー登録</title>
	</head>
	<body class="japan_blue white_font">
		<h1>一旦登録してみて</h1>
		<p> ---以下フォームにご入力ください--- </p>
		<form action="" method="POST">

			<p>
				お名前：
				<input type="text" name="user_name" value="<?php echo h($user_name); ?>"> 
				<span class="error">
					<?php echo $error['user_name']; ?>
				</span>
			</p>

			<p>
				パスワード：
				<input type="password" name="password" value=""> 
				<span class="error">
					<?php echo $error['password']; ?>
				</span>
			</p>
			<p>
				<input type="hidden" name="token" value="">
			</p>
			<p>
				<input type="submit" value="新規登録！"> 
				<a href="index.php">
					ログインに戻る
				</a>
			</p>
			<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
		</form>
	</body>
</html>