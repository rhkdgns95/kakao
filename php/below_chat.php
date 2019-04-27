<?php

if($_SERVER["REQUEST_METHOD"] !== "POST")
{
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
require_once '../db/db_select_kakao.php';

$sender = $_POST['sender'];
$receiver = $_POST['receiver'];
try{
   // 상대방과 주고받은 메시지 내가 받기도하고 보내기도한 메시지들을 순서대로 보여주기 위한방법.
    $db = new PDO($dsn, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //아이디로 친구이름 찾는 방법.
    $sql = "SELECT * FROM messages_info WHERE (sender = :sender AND receiver = :receiver) OR (sender = :receiver2 AND receiver = :sender2)";
    $prepare = $db->prepare($sql);
    $prepare->bindValue(':sender', $sender, PDO::PARAM_STR);
    $prepare->bindValue(':sender2', $sender, PDO::PARAM_STR);
    $prepare->bindValue(':receiver', $receiver, PDO::PARAM_STR);
    $prepare->bindValue(':receiver2', $receiver, PDO::PARAM_STR);
    $prepare->execute();

    $msg_tag = '';
    $result = h($prepare->fetchAll(PDO::FETCH_ASSOC));

    $check_date = 0;
    $len = count($result) - 1;
    if(!isset($result[0]['contents']))
    {
        echo "none";
        exit;
    }
    foreach($result as $key => $value)
    {
        //생각해주어야할 것 3가지가있음.
        //1 전에 보낸사람이 동일한경우를 체크,
        //2. 전에 보내던 메시지의 날짜와 동일한경우를 체크
        //3. 보낸사람이 먼저나오냐 받는사람이 먼저나오냐에 따라서 다르게하기
        // 2번조건
        if($key !== 0 && $key <= $len ) // 0부터 N-1 까지의 갯수
        {
            $send_at1 = h(substr($result[$key-1]['send_at'], 0, 10));
            $send_at2 = h(substr($result[$key]['send_at'], 0,10));

            if($send_at1 !== $send_at2)  // 다음메시지가 이전날짜와 다른경우.
            {
                if($check_date == 1 ) //이전메시지에서 태그를 닫아주어야함.
                    $msg_tag .= "</div>";

                $msg_tag .= "<div class=\"chat-group\" data-date-str=\"{$send_at2}\">";
                $check_date = 1;
            }
            else
            {
                $check_date = 0;
            }
        }
        else // 0일때
        {
            if($check_date == 0)
            {
                $current_date = h(substr($result[$key]['send_at'], 0, 10));
                $msg_tag .= "<div class=\"chat-group\" data-date-str=\"{$current_date}\">";
                $check_date = 1;
            }
        }
            if($result[$key]['sender'] === $sender){ // 내가 보낸경우
                $ul_tag = 'mine';
                $user = $sender;
            }
            else{ // 상대방이 보낸 메시지가 나온경우
                $ul_tag = 'other';
                $user = $receiver;
            }


            $msg_tag .="
                    <ul class=\"chat-message {$ul_tag}\">
                        <section></section>
                        <span>{$user}</span>
                        <li class=\"message\">{$value['contents']}</li>
                    </ul>";

        if($len == $key) // 마지막 값인경우!
            $msg_tag .= "</div>";
    }
    echo $msg_tag;

} catch(PDOException $e){
    echo "DB접속 오류. 이유 : ".h($e->getMessage());
}

// 두가지선택권이있다.

// 1. 채팅메시지를 불러와서 부모태그의 자식에 넣는방법....
// 2. 채팅메시지를 불러와서 전에 값과 비교해서 추가된것만 insert해주는 방법


