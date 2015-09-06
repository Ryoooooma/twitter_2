<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$id = (int)$_POST['id'];

$dbh->query("delete from posts where id = $id ");

echo $id;