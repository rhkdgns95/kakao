<?php
session_start();
if(!isset($_SESSION['user_name']))
{
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
else{
    session_unset(); //세션값 초기화
    session_destroy(); //세션 삭제
    header('Location: /kakao/main/index.php');
}

