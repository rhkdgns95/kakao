<?php
session_start();
if(!isset($_SESSION['user_name'])){
    header('Location: /kakao/php/wrong_page.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/kakao/css/iframe_friends.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>
<body>
   <div class="bg">


   </div>
    <div class="iframe-con">
        <div class="top-bar">
            <div class="title">친구 추가</div>
            <div class="close-btn"><a href="#">x</a></div>
        </div>
        <div class="menu-bar">
            <ul>
            <li class="list-opt add-tel active">
                <a href="#">연락처로 친구 추가</a>
                <ul>
                   <form action="">
                        <li><input type="text" placeholder="이름을 입력해주세요"></li>
                        <li>
                        <select name="" id="">
                            <option value="" selected>+82</option>
                            <option value="" >+83</option>
                            <option value="" >+84</option>
                        </select>
                        <input type="text" placeholder="전화번호를 입력해주세요"></li>

                    <div class="contents-bar">
                        <span>연락처로 친구를 추가할 수 있습니다.</span>
                        <span>추가하고싶은 친구의 이름과<br>휴대전화번호를 입력해 주세요</span>
                        <input type="submit" value="친구 추가">
                    </div>
                    </form>
                </ul>

            </li>
            <li class ="list-opt add-id">
                <a href="#">ID 검색</a>
                <ul>
                    <li><i class="fa fa-search" aria-hidden="true"></i><input type="text" placeholder="아이디를 입력해주세요"></li>
                    <div>
                        <span>아이디로 친구를 추가하세요</span>
                        <span>상대가 카카오 아이디를 등록하고,<br>검색허용을 한 경우 찾기가 가능합니다.</span>

                        <ul class="user-box">
                            <li class="user-bg">user-사진</li>
                            <li class="user-name"></li>
                            <input type="button" value="친구추가">
                        </ul>

                    </div>

                </ul>
            </li>
            </ul>
        </div>
    </div>
    <script>
        var $idSearch = $('.iframe-con .add-id input[type="text"]');
        var check_id = null;
        $('.iframe-con .list-opt').click(function(){
            var $clickedBtn = $(this);
            if(!$clickedBtn.hasClass('active')){
                $clickedBtn.siblings('.active').removeClass('active');
                $clickedBtn.addClass('active');
            }
        })

        $('.iframe-con .add-id input[type="text"]').keydown(function(e){
            if(e.keyCode == 13)
                {
                    addUser = $idSearch.val();
                    var addUserName;
//                    check_id = null;
                    $.ajax({
                        type:'post',
                        url:'/kakao/db/db_is_exist_user.php',
                        dataType:'json',
                        data:{
                         user_id:addUser,
                         user_name:1
                        },
                        success:function(data){
                            if(data) {
                                check_id = data['user_id'];
                                addUserName = data['user_name'];
                                $('.iframe-con .menu-bar .add-id .user-box').show();
                                $('.iframe-con .menu-bar .add-id .user-box li:nth-last-of-type(1)').text(addUserName);
                                console.log(data['user_id']);
                                console.log(data['user_name']);

                                $('.iframe-con .add-id ul div span:nth-of-type(1)').hide();
                                $('.iframe-con .add-id ul div span:nth-of-type(2)').hide();

                            }
                            else{
                                $('.iframe-con .menu-bar .add-id .user-box').hide();

                                $('.iframe-con .add-id ul div span:nth-of-type(1)').text(addUser+'를 찾을 수 없습니다.');
                                $('.iframe-con .add-id ul div span:nth-last-of-type(1)').text('입력하신 아이디로 등록한 회원이 없거나 검색이 허용되지 않은 회원입니다.');
                                $('.iframe-con .add-id ul div span:nth-of-type(1)').show();
                                $('.iframe-con .add-id ul div span:nth-of-type(2)').show();
                            }

                        },


                    })

                }
        })
        // 친구추가 누를시 데이터를 db에 넣기 그다음.... 창이닫혀야하는데 그게안됨.
        // 그래서 부모의함수 실행을실행시켜서 iframe을 숨김으로 바꿈.



        // 닫기버튼누를시, index.php에 정의되어있는 함수를 실행시킴!
        $('.iframe-con .top-bar .close-btn').click(function(){
            parent.hide_iframe();
        })

        $('.iframe-con .add-id .user-box input[type="button"]').click(function(){
            var loginUser = '<?=$_SESSION['user_id']?>';
            if(check_id !== null)
            {
//                window.close(); // 일반적인 현재 창 닫기
//                window.open('about:blank','_self').self.close();  // IE에서 묻지 않고 창 닫기
                $.ajax({
                    type:'post',
                    url:'/kakao/db/db_add_friends.php',
                    data:{
                        loginUser:loginUser,
                        addUser:check_id
                    },
                    success:function(data){
                        //data가 입력되면 사용자 친구목록에 표시되도록해야함... new

                    }
                })
                parent.hide_iframe();
            }
        })

    </script>
</body>
</html>


