<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /kakao/php/wrong_page.php');
}
require_once 'db_select_kakao.php';
$user_id = $_POST['user_id'];

try {
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $sql = "SELECT * FROM users_info WHERE user_id =:user_id";
//    echo "DB연결 성공!";

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $prepare = $db->prepare($sql);
    $prepare->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);


    //친구추가시 검색할때, 친구이름반환하기위해서 사용됨.
    if (isset($_POST['user_name'])) {
        if (isset($result[0])) {
//            echo $result[0]['user_id'];
            $arrUser = [];
            $arrUser['user_id'] = $result[0]['user_id'];
            $arrUser['user_name'] = $result[0]['user_name'];
            echo json_encode($arrUser);
            return;
        } else {
            echo 'false';
//            echo false;
            return;
        }
    }
    if (isset($result[0])) {
        echo false;
    } else {
        echo true;
    }
} catch (PDOException $e) {
//    echo "db연결실패! 원인 : ".h($e.getMessage());
}
