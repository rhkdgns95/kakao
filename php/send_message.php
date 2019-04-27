<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
require_once "../db/db_select_kakao.php";


$receiver = $_POST['receiver'];
$sender = $_POST['sender'];
$contents = $_POST['contents'];

try {

    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO messages_info(receiver, sender, contents) VALUES(:receiver, :sender, :contents)";

    $prepare = $db->prepare($sql);
    $prepare->bindValue(':receiver', $receiver, PDO::PARAM_STR);
    $prepare->bindValue(':sender', $sender, PDO::PARAM_STR);
    $prepare->bindValue(':contents', $contents, PDO::PARAM_STR);
    $prepare->execute();

    echo "success";

} catch (PDOException $e) {
    echo "DB연결실패. 이유 : " . h($e->getMessage());
}


