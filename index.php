<?php

session_start();

require_once('config.php');
require_once('functions.php');


// ログインしてなかったら、ログインページにとばす
if (empty($_SESSION['me'])) {
	header('Location:' .SITE_URL.'login.php');
}

// ログイン情報を変数meに突っ込む
$me = $_SESSION['me'];

// DBに接続
$dbh = connectDb();

// 変数usersに配列型で初期化
$users = array();
$posts = array();

// ユーザー一覧を最新順で全件取得（fetchをforeachで取得している）
$sql = "select * from users order by created desc";
foreach ($dbh->query($sql) as $row) {
	array_push($users, $row);
}

// つぶやき一覧を最新順で取得
$sql = "select * from posts order by created desc";
foreach ($dbh->query($sql) as $row) {
	array_push($posts, $row);
}


if ($_SERVER['REQUEST_METHOD'] != "POST") {
	// 投稿前

	// CSRF対策
	// setToken();	
} else {
	// 投稿後
	// checkToken();

	// $name = $_POST['name'];
	// $email = $_POST['email'];
	$user_id = $me['id'];
	$body = $_POST['body'];


	$error = array();

	// エラー処理
	if ($body == '') {
		$error['body'] = '内容を入力してくださいな';
	}

	if (empty($error)) {
		
		// DBに格納
		$dbh = connectDb();
		
		$sql = "insert into posts
			   (user_id, body, created, modified)
			   values
			   (:user_id, :body, now(), now())";

		$stmt = $dbh->prepare($sql);

		$params = array(
			":body" => $body,
			":user_id" => $user_id,
		);

		$stmt->execute($params);

		// 同じページにとばす
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
		<title>ホーム画面</title>
	</head>
	<body class="japan_blue white_font">
			<p>「
				<strong>
					<?php echo $me['user_name']; ?>
				</strong>
				」さん、こんにちは！
			</p>

			<h2><strong>【つぶやく】</strong></h2>
			<form action="" method="POST">
				<p>
					ツイーーーーーーーーーーーート
				</p>
				<p class="error">
					<?php echo $error['body']; ?>
				</p>
				<p>
					<textarea name="body" cols="40" rows="5"></textarea>
				</p>
				<p>
					<input type="submit" value="送信">
					<input type="hidden" name="id" value="">
				</p>
			</form>
		
			<h2>【ユーザーの一覧】</h2>
			<p>
				現在のユーザー数は
				<strong>
					<?php echo count($users); ?>人
				</strong>
				なり
			</p>
			<ul>
				<?php foreach ($users as $user) : ?>
					<li>
						<a href="view.php?id=<?php echo h($user['id']); ?>">
							<?php echo h($user['user_name']); ?>さん
						</a>
					</li>
				<?php endforeach ; ?>
			</ul>
			<p><a href="logout.php">ログアウト</a></p>
		
			<h2>【つぶやき一覧】</h2>
			<p>
				つぶやき総数：
				<strong>
					<?php echo count($posts); ?>件
				</strong>
				なり
			</p>
			<ul>
				<?php foreach ($posts as $post) : ?>
					<li>
						「<?php echo h($post['body']); ?>」
						 by ユーザーID：
						 <strong>
						 	<?php echo h($post['user_id']); ?>  
						 </strong>
						 <a href="edit.php?id=<?php echo h($post['id']); ?>">[編集]</a> / 
						 <span class="delete_link" data-id="<?php echo h($post['id']); ?>">[削除]</span>
					</li>
				<?php endforeach ; ?>
			</ul>
			
			<script>
				$(function() {
					$('.delete_link').click(function() {
						if (confirm("削除してもよろしいですか？")) {
							var num = $('#num').text();
							num--;
							$.post('./delete.php', {
								id: $(this).data('id')
							}, function(rs) {
								$('#post_' + rs).fadeOut(800);
								// ここ使うと削除した時にバグが起きる→要検証！
								// $('#num').text(num);
							});
						}
					});
				});
			</script>
	</body>
</html>