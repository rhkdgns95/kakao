<?php
function register($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}
function regist($password, $hash)
{
    if(password_verify($password, $hash))
        return true;
    else
        return false;
}
