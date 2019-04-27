<?php

$Is_SessionStarted =  session_status();

if(!isset($_SESSION['user_name']) || $Is_SessionStarted !== 2)
{

    header('Location: /kakao/php/wrong_page.php');
    exit;
}

require_once "../db/search_friends_list.php";
//친구가 한명이라도 있는경우.
if(isset($friends_info))
{
    $friends_list = "";
    $len =  count($friends_info);
    foreach($friends_info as $value)
    {
        $friends_list .= "
        <li>
            <ul>
                <li></li>
                <li>{$value['user_name']}</li>
                <li>
                    <span>유저-{$value['user_no']}:프로필내용</span>
                </li>
                <p class='friends-no' style=\"display:none;\">{$value['user_id']}</p>
            </ul>
        </li>";
    }
}
else
{
    $len = 0;
    $friends_list = "<div>등록된 친구가 없습니다.</div>";
}


$friends_box = "
    <ul class=\"line friends-list\">
        <p>친구 {$len}명</p>
        {$friends_list}
    </ul>
";
////////////////////////////////////////////////////////////////


