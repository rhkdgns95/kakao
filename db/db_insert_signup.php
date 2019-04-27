<?php

if($_SERVER['REQUEST_METHOD'] !== "POST"){
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
require_once '../php/encrypt_pwd.php'; // 패스워드 암호화
require_once 'db_select_kakao.php'; // kakao.php에서 DB사용하는 이름 변경하기.

echo $dsn;
$user_name = $_POST['user_name'];
$user_id = $_POST['id_check'];
//user_id는 크롬 개발자도구를 이용해서 변조가 가능하다.
//id_check를 받는이유는 hidden시켜서 보이지않으므로 변경이불가능하다.
//하지만 이또한 완벽한 보안이아니므로 나중에 다시생각해보아햐한다.
$user_password = register($_POST['user_password']); // 패스워드 암호화
// db연결 시작.
$sql = "INSERT INTO users_info(user_name, user_id, user_password) VALUES(:user_name, :user_id, :user_password)";
//해야될거 비밀번호 해쉬화 한다음 *user_password에 저장
// db값 넣기

try{

    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $prepare = $db->prepare($sql);
    $prepare->bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $prepare->bindValue(':user_id', $user_id,PDO::PARAM_STR);
    $prepare->bindValue(':user_password', $user_password, PDO::PARAM_STR);
    $prepare->execute();
    echo "<script>alert('회원가입 완료!')</script>";
    echo "<script>window.close();</script>";

//    echo "DB연결 성공!";
} catch (PDOException $e) {
    echo "<script>alert('회원가입 실패!');</script>";
    echo "<script>history.back();</script>";
//    echo "DB연결 실패! 이유 : ".h($e->getMessage());
}
