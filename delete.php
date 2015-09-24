<?php

require_once('config.php');
require_once('functions.php');

// DB接続した値を＄dbhにぶっこんでいる
$dbh = connectDb();

$id = (int)$_POST['id'];

// query（クエリー）メソッドでSQL文を実行している → 返り値はPDOStatementオブジェクトでこのオブジェクト内に結果が保持されている
$dbh->query("delete from posts where id = $id ");

echo $id;