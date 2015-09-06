<?php

session_start();

require_once('config.php');
require_once('functions.php');

// DBに接続
$dbh = connectDb();

if (preg_match('/^[1-9][0-9]*$/', $_GET['id'])) {
	$id = (int)$_GET['id'];
} else {
	echo "不正なIDです。";
	exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	// 投稿前

	// CSRF対策
	setToken();	

	$stmt = $dbh->prepare("select * from posts where id = :id limit 1");
	$stmt->execute(array(":id" => $id));
	$post = $stmt->fetch() or die("no one found!");
	$body = $post['body'];

} else {
	// 投稿後
	checkToken();

	$body = $_POST['body'];

	$error = array();

	// エラー処理
	if ($body == '') {
		$error['body'] = '内容を入力してください';
	}

	if (empty($error)) {

		$sql = "update posts set
				body = :body,
				modified = now()
				where id = :id";
		$stmt = $dbh->prepare($sql);
		$params = array(
			":body" => $body,
			":id" => $id
		);
		$stmt->execute($params);

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
		<title>データの編集</title>
	</head>
	<body class="japan_blue white_font">
		<h1>チャチャッと書き換えちゃおう</h1>
		<form action="" method="POST">
			<p>
				内容*：
			</p>
			<p>
				<?php echo $error['body']; ?>
			</p>
			<p>
				<textarea name="body" cols="40" rows="5">
					<?php echo h($post['body']); ?>
				</textarea>
			</p>
			<p>
				<input type="submit" value="更新">
			</p>
			<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
		</form>
		<p><a href="index.php">戻る</a></p>
	</body>
</html>