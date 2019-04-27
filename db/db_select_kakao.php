<?php
$dbServer = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'kakao';
$dsn = "mysql:host={$dbServer}; dbname={$dbName}; charset=utf8";

function h($data)
{
    if(is_array($data))
        return array_map('h', $data);
    else
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
