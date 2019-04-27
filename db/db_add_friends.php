<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: /kakao/php/wrong_page.php');
}

require_once '../db/db_select_kakao.php';
//user_id를 요청받은뒤, 테이블 users_info 에서 로그인 유저의 user_id와 검색한 친구의 user_id로 user_no를 가져온뒤, friends_list에 등록하면된다.

$loginUser = $_POST['loginUser']; // 로그인 유저
$addUser = $_POST['addUser'];  // 친구추가할 유저

try{
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "DB접속 성공";
    $sql = "SELECT * FROM users_info WHERE user_id = :user_id";
    // 로그인 유저의 정보 저장
    $prepare = $db->prepare($sql);
    $prepare->bindValue(':user_id', $loginUser, PDO::PARAM_STR);
    $prepare->execute();

    $result_loginUser = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // 회원가입한 유저의 정보 저장
    $prepare->bindValue(':user_id', $addUser, PDO::PARAM_STR);
    $prepare->execute();
    $result_addUser = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // 친구목록에 넣기.
    $insert_sql = "INSERT INTO friends_list(user_no, friend_no) VALUES(:loginUser, :addUser)";
    $prepare2 = $db->prepare($insert_sql);
    $prepare2->bindValue(':loginUser',$result_loginUser[0]['user_no'], PDO::PARAM_INT);
    $prepare2->bindValue(':addUser', $result_addUser[0]['user_no'], PDO::PARAM_INT);
    $prepare2->execute();

} catch(PDOException $e){
    //  echo "DB접속 실패. 이유 : ".h($e->getMessage());
}
