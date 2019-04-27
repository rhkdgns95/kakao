<?php
//if($_SERVER['REQUEST_METHOD'] !== "POST")
//{
//    header('Location: /kakao/php/wrong_page.php');
//    exit;
//}
require_once '../db/db_select_kakao.php';
require_once '../php/encrypt_pwd.php';
$user_id = $_POST['user_id'];
$user_password = $_POST['user_password'];

try{
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "DB연결성공!";

    $sql = "SELECT * FROM users_info WHERE user_id = :user_id";
    $prepare = $db->prepare($sql);

    $prepare->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);



    if(!isset($result[0]['user_name']))
    {
//        echo "존재 X";
        echo false;
    }
    else
    {
        $isCorrect = regist($user_password, $result[0]['user_password']);
        if($isCorrect){
            session_start();
            $_SESSION['user_id'] = $result[0]['user_id'];
            $_SESSION['user_name'] = $result[0]['user_name'];
            echo 1;
        }
        else{
//            echo "비밀번호실패";
            echo false;
        }
    }
} catch(PDOException $e) {
//    echo "DB 연결 실패! 이유 : ".h($e->getMessage());
}
