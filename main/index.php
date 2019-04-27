<?php
session_start();
if(isset($_SESSION['user_name']))
{
    require_once '../php/main_tag.php';
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

    <?php if(!isset($_SESSION['user_id'])):?>
    <!--Login전 CSS-->
    <link rel="stylesheet" href="/kakao/css/main.css">
    <script src="/kakao/js/new_window.js"></script>
    <script src="/kakao/js/login.js"></script>


    <?php else:?>
    <script src="/kakao/js/main_search_bar.js"></script>
    <script src="/kakao/js/chat_box.js"></script>
    <!--Login후 CSS-->
    <link rel="stylesheet" href="/kakao/css/after_main.css">
    <!--Login후 친구목록 CSS-->
    <link rel="stylesheet" href="/kakao/css/friends_box_style.css">
    <link rel="stylesheet" href="/kakao/css/iframe_add_friends.css">
    <link rel="stylesheet" href="/kakao/css/chat_box.css"/>
    <?php endif;?>
    <title>kakaotalk</title>

</head>
<body>
    <?php if(!isset($_SESSION['user_name'])):?>
    <div class="con">
        <div class="login">
            <div class="top-logo"><i class="fa fa-facebook-official" aria-hidden="true"></i></div>
            <div class="login-box">
                <form action="" method="post">
                    <table>
                        <tr>
                            <td><input type="text" name="user_id" required autofocus placeholder='카카오 계정 혹은 전화번호'></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="user_password" required placeholder="비밀번호 (4~32자)"></td>
                            </tr>
                        <tr>
                            <td><input type="submit" value="로그인"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"><span>잠금모드로 자동로그인</span></td>
                        </tr>
                        <tr>
                            <td><p class="err_msg">카카오계정 또는 비밀번호를 다시 확인해 주세요.</p></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="bottom-box">
                <div class="btn-box">
                    <span><a href="/kakao/php/signup_page.php">카카오계정 찾기</a></span>
                    <span><a href="#">비밀번호 재설정</a></span>
                </div>
            </div>
        </div>
    </div>
    <?php else:?>
    <div class="con1">
        <div class="main">
            <div class="top-box">
                <div class="logo">
                kakao<span>talk</span>
                </div>
                <div class="btn-box">
                   <li><a href="#">X
</a></li>
                   <li><a href="#">ㅁ</a></li>
                   <li><a href="#">_</a></li>
                </div>
            </div>
            <ul class="list-box">
                    <li class="list friends-box active">
                         <ul class="contents contents-box">
                            <li class="list-logo"><a href="#"><i class="fa fa-user" aria-hidden="true"></i></a>
</li>

                            <nav class="selector">
                            <p class="search-box"><i class="fa fa-search" aria-hidden="true"></i><input type="text" placeholder="이름검색" name="user_search" autocomplete="off"><a href="#" class="remove-text"><i class="fa fa-times" aria-hidden="true"></i>
</a></p>
                                <div class="scroll-contents">
                                <ul class="line my-profile">
                                    <p>내 프로필</p>
                                    <li>
                                       <ul>
                                            <li></li>
                                            <li class="user_name"><?=$_SESSION['user_name']?></li>
                                            <li><span>프로필내용</span></li>
                                       </ul>
                                    </li>
                                </ul>
                                <ul class="line group">
                                    <p>그룹</p>
                                    <li>
                                       <ul>
                                            <li></li>
                                            <li>플러스친구</li>
                                            <li><span>프로필내용</span></li>
                                       </ul>
                                    </li>
                                </ul>
                                <?= $friends_box?>
                                </div>
                            </nav>
                        </ul>
                    </li>
                    <li class="list chat-box">
                        <ul class="contents contents-box">
                            <li class="list-logo"><a href="#"><i class="fa fa-comment" aria-hidden="true"></i></a>
</li>
                            <nav class="selector">
                            <p class="search-box"><i class="fa fa-search" aria-hidden="true"></i><input type="text" placeholder="채팅방 이름, 참여자 검색" name="user_search" autocomplete="off"><a href="#" class="remove-text"><i class="fa fa-times" aria-hidden="true"></i>
</a></p>
                                <div class="scroll-contents">
                                <ul class="chatting-list-box">
                                    <li class="chatting-list" style="display:none;">
                                        <ul>
                                            <span class="user-picture"></span>
                                            <span class="user-name">이름</span>
                                            <span class="user-send-at">오전 12:02</span>
                                            <span class="check-at">1</span>
                                            <li>내용들....</li>
                                        </ul>
                                    </li>
<!--                                    <li class="chatting-list">-->
<!--                                        <ul>-->
<!--                                            <span class="user-picture"></span>-->
<!--                                            <span class="user-name">이름</span>-->
<!--                                            <span class="user-send-at">오전 12:02</span>-->
<!--                                            <span class="check-at">1</span>-->
<!--                                            <li>내용들....</li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li class="chatting-list">-->
<!--                                        <ul>-->
<!--                                            <span class="user-picture"></span>-->
<!--                                            <span class="user-name">이름</span>-->
<!--                                            <span class="user-send-at">오전 12:02</span>-->
<!--                                            <span class="check-at">1</span>-->
<!--                                            <li>내용들....</li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
                                </ul>
                                </div>
                            </nav>
                        </ul>
                    </li>

                    <li class="list added-box">
                        <ul class="contents contents-box">
                            <li class="list-logo"><a href="#"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
</li>
                            <nav class="selector">
                                <ul class="my-profile">
                                    <li></li>
                                    <li></li>
                                </ul>
                                <ul class="group">
                                    <li></li>
                                    <li></li>
                                </ul>
                                <ul class="friends-list">
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </nav>
                        </ul>
                    </li>
                    <div class="setting-box">
                        <div class="setting-menu"><a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a><ul>
                            <li><a href="#">새로운채팅</a></li>
                            <li><a href="#">오픈채팅</a></li>
                            <li class="add-friends"><a href="#">친구 추가</a></li>
                            <li><a href="#">그룹 만들기</a></li>
                            <li><a href="#">카카오톡 정보</a></li>
                            <li><a href="#">설정</a></li>
                            <li><a href="#">잠금모드 설정</a></li>
                            <li class="set-logout"><a href="/kakao/php/logout.php">로그아웃</a></li>
                            <li><a href="#">종료</a></li>
                        </ul></div>
                        <div><a href="#"><i class="fa fa-microphone-slash" aria-hidden="true"></i></a></div>
                    </div>
             </ul>


<!--            <div class="side-bg" style="display:none;"></div>-->

             <div class="iframe-box"><iframe class="iframe-friends" frameborder=0 src="/kakao/php/iframe-friends.php" frameborder="1" style="display:none;">현재 브라우저는 iframe을 지원하지 않는다.</iframe>
             </div>
        </div>

    </div>

    <h1>hello! <?=$_SESSION['user_name']?></h1>

    <script>


        // @메인 3가지 메뉴중 채팅list클릭시 대화목록들이 3초마다 초기화되도록 - 시작
        function chatting_list()
        {
            console.log('실행중');
            var user_id = '<?=$_SESSION['user_id']?>';
            $.ajax({
                type:'post',
                url:'/kakao/db/search_chat_list.php',
                data:{
                    user_id:user_id
                },
                success:function(data){
                    $('.chatting-list-box > li').remove();
                    $('.chatting-list-box').append(data);
                }
            })
        }
        // @메인 3가지 메뉴중 채팅list클릭시 대화목록들이 3초마다 초기화되도록 - 종료

        // 채팅목록에서 -> 채팅창 더블클릭시 채팅방 생성 시작
        // on('이벤트', '선택 요소', '실행되는함수') -> 윈도우 실행시가아닌, 동적으로 생성된 요소의 이벤트 발생시킬때!
        $(document).on('dblclick', '.chatting-list-box .chatting-list', function(){
            var user_id = '<?= $_SESSION['user_id']?>';
            var friends_id = $(this).find('.friends-no').text();
            chatting_open(user_id, friends_id);
            console.log(friends_id);
        })


        // 친구목록에서 -> 친구 더블클릭시 채팅방 생성 시작
        $('.friends-list > li').on('dblclick', function() {
            var user_id = '<?php echo $_SESSION['user_id']?>';
            var friends_id = $(this).find('.friends-no').text();
            chatting_open(user_id, friends_id);
            //var url = `/kakao/php/chatting-room.php?user_id=${user_id}&friends_id=${friends_id}`;
            //openNewWindow(url, 'window');
        });
        function chatting_open(user_id, friends_id){
            var url = `/kakao/php/chatting-room.php?user_id=${user_id}&friends_id=${friends_id}`;
            openNewWindow(url, 'window');
        }
        // 친구 더블클릭시 채팅방 생성 종료


        // function chatting_list_recent(chat_tag)
        // {
        //     $('.chatting-list-box > li').remove();
        //     $('.chatting-list-box').append(chat_tag);
        // }

    </script>
    <!--   로그인전에는 스크립트 내용이 실행될 이유가 없음!     -->
    <?php endif;?>


</body>
</html>






