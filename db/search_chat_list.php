<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST') // ajax요청받을시외에는 접근불가.
{
    header('Location: /kakao/php/wrong_page.php');
}

$user_id = $_POST['user_id'];
require_once '../db/db_select_kakao.php';

try{
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT MAX(no) as no, sender, receiver, contents, send_at FROM messages_info WHERE sender = :sender OR receiver = :receiver GROUP BY receiver, sender ORDER BY no DESC";

    $prepare = $db->prepare($sql);
    $prepare->bindValue(':sender', $user_id, PDO::PARAM_STR);
    $prepare->bindValue(':receiver', $user_id, PDO::PARAM_STR);
    $prepare->execute();
    $results = h($prepare->fetchAll(PDO::FETCH_ASSOC));

    $len = count($results);
    $msg_numbers = [];

    foreach($results as $key => $value)
    {
        $isInsert = 0;
        for($i = 0; $i < $len; $i++)
        {
            if($key == $i)
                continue;
            if($results[$i]['sender'] == $results[$key]['receiver'] && $results[$i]['receiver'] == $results[$key]['sender'])
            {
                $isInsert = 1;
                // receiver와 sender가 동일한경우, 해당 Message의 number를 구한다.
                // 중복되는 값들 중 큰 값을 구해야함. (최신데이터 이므로)

                if($results[$key]['no'] > $results[$i]['no'])
                    $num = $results[$key]['no'];
                else
                    continue;

                if(!in_array($num, $msg_numbers)){  // $num이 Message번호를 모아놓은곳에 있는지 확인한다. (있다면 true반환 / 없으면 false를 반환)
                        $msg_numbers[] = $num;
                }

            }
        }
        if($isInsert == 0 ) { // 중복된 값이 없을때,
            $msg_numbers[] = $value['no'];
        }
    }
    $unique_msgs = array_unique($msg_numbers);
    $list_result = [];

    $sql2 = "SELECT * FROM messages_info WHERE no = :no";
    $prepare2 = $db->prepare($sql2);
    foreach($unique_msgs as $unique_msg)
    {
        $prepare2->bindValue(':no', $unique_msg, PDO::PARAM_INT);
        $prepare2->execute();
        $list_data = h($prepare2->fetchAll(PDO::FETCH_ASSOC));
        $list_result = array_merge($list_result, $list_data);
    }

    // 여기만 다시살리면됨.
    if($list_result == null)
        $chat_list = null;

    else {
        $chat_list = '';
        foreach($list_result as $key => $value)
        {
            // 로그인한 유저와 주고받은 사람의 이름이 와야함 그래서 다음과 같이 설정함.
            if($value['sender'] == $user_id) // 보낸사람이 현재 자신이라면, receiver가 상대방이름이므로,
                $chat_user_name = $value['receiver'];
            else   // 보낸사람이 상대방이라면, sender가 상대방 이름이다.
                $chat_user_name = $value['sender'];
            $chat_send_at = $value['send_at'];
            $chat_contents = $value['contents'];
            $chat_list .= "
                <li class=\"chatting-list\">
                    <ul>
                        <span class=\"user-picture\"></span>
                        <span class=\"user-name\">{$chat_user_name}</span>
                        <span class=\"user-send-at\">{$chat_send_at}</span>
                        <span class=\"check-at\">1</span>
                        <li>{$chat_contents}</li>
                    </ul>
                    <p class='friends-no' style=\"display:none;\">{$chat_user_name}</p>
                </li>";
        }
        $chat_count = count($list_result);
    }
    echo $chat_list;

} catch(PDOException $e){

//    echo "DB접속 오류. 이유 : ".h($e->getMessage());
}