<?php

$check_id = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        div{
            padding-left:127px;
        }
        table{
            margin: 0 auto;
        }
        .check-box{
            font-size:13px;
        }
    </style>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="/kakao/js/signup.js"></script>

</head>
<body>
<div><h1>회원가입</h1></div>
    <form action="/kakao/db/db_insert_signup.php" method="post" autocomplete="off">
       <table autocomplete='off'>
           <tr>
                <td><input type="text" required="" name="user_name" autofocus placeholder="이름입력"></td>
           </tr>
           <tr>
                <td><input type="text" required="" name="user_id" placeholder="아이디입력"  autocomplete="off"></td>
                <td><input type="button" value="중복확인"></td>
           </tr>
           <tr>
               <td class="check-box">사용가능여부 : <span class="user_check">확인안됨</span></td>
               <td><input type="hidden" name="id_check" value="not"></td>
           </tr>
           <tr>
                <td><input type="password" required="" name="user_password" placeholder="비밀번호입력"><p class="password_check" style="padding:0px;margin:0px;font-size:12px"></p></td>
           </tr>
           <tr>
               <td><input type="password" required name="user_password2" placeholder="비밀번호확인"><p class="password_check2" style="padding:0px;margin:0px;font-size:12px"></p></td>
           </tr>
           <tr>
                <td><input type="submit" value="회원가입"></td>
                <td><input type="hidden" value="not" name="password_check"></td>
                <td><input type="hidden" value="not" name="password_check2"></td>
           </tr>

       </table>


    </form>
    <script>


    </script>
</body>
</html>
