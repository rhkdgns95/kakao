<?php
if(isset($_SESSION['user_name'])){  // 세션값이 존재함 == 로그인이 된경우
    $user_id = $_SESSION['user_id'];
}
//else if($_SERVER['REQUEST_METHOD'] === 'POST') //ajax를 사용해서 접근한경우
//{
//    $user_id = $_POST['user_id'];
//}
else{ //로그인하지 않고 접근한경우
//    echo "에러발생!";
    header('Location: /kakao/php/wrong_page.php');
}


// 경로가 조금 이상한이유? 상대적인 탐색임 /main/index.php가 기준이되므로.
require_once '../db/db_select_kakao.php';



try {
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "DB연결성공!";


    // Login한 유저 친구목록검색 (1)
    $sql = "SELECT * FROM users_info WHERE user_id=:user_id";
    $prepare = $db->prepare($sql);
    $prepare->bindValue(':user_id', $user_id, PDO::PARAM_STR);

    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
    //현재 로그인한 유저의 user_no로 friend를 검색하기위해서 user_no를 검색함.
    $user_no = $result[0]['user_no'];

    $sql2 = "SELECT * FROM friends_list WHERE user_no = :user_no";
    $prepare2 = $db->prepare($sql2);

    $prepare2->bindValue(':user_no', $user_no, PDO::PARAM_INT);
    $prepare2->execute();
    $result2 = $prepare2->fetchAll(PDO::FETCH_ASSOC);

    //친구가 한명이라도 있는경우,
    if(isset($result2))
    {
        $friends_info = [];
        $sql = "SELECT * FROM users_info WHERE user_no = :user_no";
        foreach($result2 as $key => $value)
        {
            $prepare = $db->prepare($sql);
            $prepare->bindValue(':user_no', $value['friend_no'], PDO::PARAM_INT);
            $prepare->execute();
            $result3 = $prepare->fetchAll(PDO::FETCH_ASSOC);
            $friends_info[] = $result3[0]; //0인 이유는 검색하면 계속 0에저장이됨.
        }
    }

//if($_SERVER['REQUEST_METHOD'] === 'POST')
//{
//    echo "입력받은계정 : ".$user_id;
//}
    //친구가 한명도 없는경우, 친구가없으므로, 검색후 종료

} catch(PDOException $e) {
    echo 'DB연결실패!'.h($e->getMessage());
}

