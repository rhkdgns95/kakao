<?php
session_start();
if(!isset($_SESSION['user_id']))
{
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
$sender = $_GET['user_id'];
$receiver = $_GET['friends_id'];

?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/kakao/css/chatting-room.css">

    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>
<body>
<!--    chat-group은 날짜별로 구별하기위한 요소   -->

    <div class="chatting-room">
        <div class="message-box">
            <div>
<!--                <ul class="chat-message other">-->
<!--                    <section></section>-->
<!--                    <span></span>-->
<!--                    <li class="message"></li>-->
<!--                </ul>-->
<!--                <ul class="chat-message mine">-->
<!--                    <section>사진</section>-->
<!--                    <span>나</span>-->
<!--                    <li class="message">내용</li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="chat-group" data-date-str='2018년 8월 14일 화요일'>-->
<!--                <ul class="chat-message other">-->
<!--                    <section></section>-->
<!--                    <span>상대방</span>-->
<!--                    <li class="message">내용~~~</li>-->
<!--                </ul>-->
<!--                <ul class="chat-message mine">-->
<!--                    <section>사진</section>-->
<!--                    <span>나</span>-->
<!--                    <li class="message">내용</li>-->
<!--                </ul>-->
            </div>
        </div>
        <div class="input-box">
            <input type="text" name="input-chat" autofocus>
            <div class="plus-btn"><i class="fa fa-plus" aria-hidden="true"></i></div>
            <div class="search-btn">검색</div>
            <div class="emo-btn"><i class="fa fa-smile-o" aria-hidden="true"></i></div>
        </div>
    </div>
<script>
    $(function(){
        $('.chatting-room .search-btn').click(function() {
            var contents = $('input[name="input-chat"]').val();
            if (contents === '') {
                return false;
            }
            // var message = `
            //     <ul class="chat-message mine">
            //         <section></section>
            //         <span>나</span>
            //         <li class="message">${txt}</li>
            //     </ul>
            // `;

            // ajax를 사용해서 사용자가 입력한값 서버로 전송하기 (동기화로 사용하는게 나을듯, scrolltop때문에)

            $.ajax({
                type:"post",
                url:'/kakao/php/send_message.php',
                async:false,
                data:{
                    sender:'<?=$sender?>',
                    receiver:'<?=$receiver?>',
                    contents:contents
                },
                success:function(data){
                    console.log(data);
                }
                // error:function(){
                //   console.log('error!');
                // }
            })


            // $('.chat-group:nth-last-of-type(1)').append(message);
            $('.chatting-room .input-box input[type="text"]').val('');
            $('.message-box').scrollTop(999999);
        })

        $('input[type="text"]').keydown(function(e){
            if(e.keyCode==13)
                $('.chatting-room .search-btn').click();
        })

        // 5초마다 채팅화면 갱신됨.
        var loop = setInterval(chatting, 3000);
        // 클릭시마다 대화목록 최신화 - 갱싱 수정시 사용됨.
        // $(window).click(function(){
        //     chatting();
        // })
    })

    function chatting(){
        $.ajax({
            type:'post',
            url:'/kakao/php/below_chat.php',
            data:{
                sender:'<?=$sender?>',
                receiver:'<?=$receiver?>',
            },
            success:function(data){
                console.log(data);
                $('.chatting-room .message-box .chat-group').remove();
                //받은 데이터가없는경우.
                if(data === 'none'){
                    console.log('받은데이터없음');
                }
                else
                    $('.chatting-room .message-box').append(data);
            },
            error:function(){// 에러발생시 창닫기.


            }
        })


    }

</script>
</body>
</html>