<?php 

session_start();

require_once('config.php');
require_once('functions.php');

// DB接続した値を＄dbhにぶっこんでいる
$dbh = connectDb();

// 正規表現でIDチェックをしている。そのうえでクリック（get）されたidを変数idに突っ込んでいる
if (preg_match('/^[1-9][0-9]*$/', $_GET['id'])) {
	$id = (int)$_GET['id'];
} else {
	echo "不正なIDです。";
	exit;
}

// クリックされたユーザー情報を1件取得
$stmt = $dbh->prepare("select * from users where id = :id limit 1");
$stmt->execute(array(":id" => $id));
// 返り値のPDOオブジェクトに対してfetchメソッドを実行し、結果セットを配列で取得している
$clicked_user = $stmt->fetch() or die("ユーザーを取得できません");
// var_dump($clicked_user);


// クリックされたユーザーのつぶやきを全件取得（現状とれていない）
$stmt = $dbh->prepare("select * from posts where user_id = :id");
$stmt->execute(array(":id" => $id));
// 返り値のPDOオブジェクトに対してfetchメソッドを実行し、結果セットを配列で取得している
$clicked_users_posts = $stmt->fetch();
// $dbh = connectDb();
// $clicked_user_posts = array();
// $sql = "select * from posts where user_id = :id";
// foreach ($dbh->query($sql) as $row) {
// 	array_push($clicked_user_posts, $row);
// }
// var_dump($clicked_users_posts);
// →→→ つぶやきがゼロの場合にエラーを吐き出す仕様になっている。
// →→→ カウントさせた際にゼロの場合は「つぶやきゼロです」の文字列を吐き出して、それ以外はフェッチさせる仕様に変更する。






// 以下（フォロー関係を作るために）、relationshipsテーブルにデータをinsertするためのSQL
// DB接続した値を＄dbhにぶっこんでいる
$dbh = connectDb();

$sql = "insert into relationships
	   (follower_id, followed_id, created, modified)
	   values
	   (:follower_id, :followed_id, now(), now())";

$stmt = $dbh->prepare($sql);

$params = array(
	":follower_id" => $_SESSION['me']['id'],
	":followed_id" => $clicked_user['id']
);

$stmt->execute($params);

?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title><?php echo $clicked_user['user_name']; ?>さんのページ</title>
	</head>

	<body class="japan_blue white_font">

		<p>
			<strong>
				<?php echo $clicked_user['user_name']; ?>さんのページ
			</strong>
		</p>
		<p>
			<input name="follow" type="button" value="フォロー">
			<input name="release" type="button" value="フォロー解除">
		</p>

		<!-- <h2>【ユーザーの一覧】</h2>
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
		</ul> -->
	

		<h2>【つぶやき】</h2>
		<p>
			つぶやき総数：
			<strong>
				<?php echo count($clicked_users_posts['body']); ?>件
			</strong>
			なり
		</p>


<!-- 		<ul>
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
-->

	</body>
</html>
