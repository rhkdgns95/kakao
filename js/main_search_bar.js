$(function(){
    // 채팅리스트 최신화시키기 위한 loop변수 생성.
    var chatListLoop= null;
    // 친구 인원이 많을경우 친구목록에 스크롤을 하기위해 크기를 지정함.
    var main_height = $('.con1 .main').outerHeight(true);
    console.log(main_height);
    var top_height = $('.con1 .top-box').outerHeight(true) + $('.con1 .list-box').outerHeight(true) +$('.con1 .selector').outerHeight(true)
    var contents_height = main_height - top_height;
    $('.con1 .scroll-contents').css('height', contents_height + 'px');

    // 친구목록, 채팅방 , 메뉴창 클릭시 그에맞는 화면보여주기 및
    // 현재창 한번더클릭시 스크롤바 최상단 위치시키기.
    $('.con1 .main .list-box .list a').click(function(){

        var $clickedBtn = $(this).parents('.list');
        if($clickedBtn.hasClass('active')){
            var $scrollBtn = $clickedBtn.closest('.list-box').find('.scroll-contents');
            $scrollBtn.scrollTop(0);
            return false;
        }
        else{
            // 채팅목록에서 실행되는 반복요청
            if($clickedBtn.index() == 1){
                console.log('채팅리스트 최신화 시작');
                chatListLoop = setInterval(chatting_list, 3000);
            }
            else{
                console.log('채팅리스트 최신화 종료');
                clearInterval(chatListLoop);
            }
                $clickedBtn.addClass('active');
                $clickedBtn.siblings('.active').removeClass('active');
            }
    })

    // 나중에 해주어 할 것 : 검색바가 차례대로 입력시 그에맞는 검색을 찾아주기.
    // 검색바에 텍스트 입력시 지우는 X표시 설정하기.
    $('.con1 input[name="user_search"]').keydown(function(){
        //setTimeout을 설정한 이유??
        /* : keydown()이 눌렸을경우 이 함수가 실행되는데, 이상하게도 한박자 느리게 값이 표시된다. 사실 이상한 것이 아니다. 왜냐? 눌렀을 당시의 데이터상태를 기준으로 실행된다. 즉, 값이 하나도없으면 하나도없는 값의 길이 0이 되는것이다. 그래서 setTimeout으로 값이 입력이 된다음 실행시키는것이다.
        */


        var $remove_text = $(this).siblings('a');
        setTimeout(function(){
            var data = $remove_text.siblings('input[name="user_search"]').val();
            /* 왜 굳이 ($this).data를 안한이유?
            : 못한것이다 setTImeout()안에 있으므로 $(this)가
            가리키는게 input[name]이 아니므로..

            그리고 또 왜 $remove_text라는 변수를 사용한이유?
            : user_search는 2개가 존재한다. 하나는 친구목록 또하나는 대화목록 즉, 두개를 한번에 인식되므로 그것을 방지하고자 DOM의 계층적 특징으로 접근한것이다.
            */
            var len = data.length;
            if(len === 0)
            {
                $remove_text.hide();
// 계층적인 문제로 사용 X : $('.con1 .list-box .selector .search-box .remove-text').hide();
            }
            else{
// 계층적인 문제로 사용 X : $('.con1 .list-box .selector .search-box .remove-text').show();
                $remove_text.show();
            }
        },100)
    })

    // 검색바에서 X표시 클릭시 작성한 데이터 지우기 위한 방법.
    // 나중에 이건 검색결과를 실시간으로 보여주어야한다.
    $('.con1 .main .list-box .selector .search-box .remove-text').click(function(){
        $(this).siblings('input[name="user_search"]').val('');
        $(this).hide();
    })



        //메뉴창을 누르고 아무곳이나 클릭하면 메뉴창이 닫히도록함
        $(window).click(function(){
            var $setting_opt = $('.con1 .main .list-box .setting-box .setting-menu');

            if($setting_opt.hasClass('active')){
                $setting_opt.removeClass('active');
            }
        })
        $('.con1 .main .list-box .setting-box .setting-menu').click(function(e){
            if($(this).hasClass('active'))
            {
                $(this).removeClass('active');
                //return false; -> 로그아웃 실행시 setting-menu먼저 클릭이벤트가 발생하므로,
                //return false를 실행하면 그것의 하단메뉴가 실행되지않으므로... 주석처리함!
            }
            else
            {
                $('.con1 .main .list-box .setting-box .setting-menu').addClass('active');
            }


            // 메뉴창클릭후 active가 생기면서 window까지 클릭이 되므로, 0.1초 뒤에 실행되게끔했는데, stopPropagation으로 방지함.

//            setTimeout(function(){
//                $('.con1 .main .list-box .setting-box .setting-menu').addClass('active');
//            },100);
            e.stopPropagation();
        })

    // 로그아웃 기능.
        $('.set-logout').click(function(e){
            var isLogout = confirm('로그아웃 하시겠습니까?');
            console.log(isLogout);
            if(!isLogout){
                e.preventDefault();
            }
        })
    // 카톡 friends_list하나 클릭시
    $('.con1 .main .list-box .selector .line > li').click(function(){
        if(!$(this).hasClass('active'))
        {
            var $scroll_contents = $(this).closest('.scroll-contents')
            $scroll_contents.find(' .line > li.active').removeClass('active');
             $(this).addClass('active');
        }
    })


    // 친구추가버튼 클릭시, display:block으로 바뀜
    $('.con1 .setting-box .setting-menu .add-friends').click(function(){
        show_iframe();
    })
})




// 친구 더블클릭시 채팅방 생성 함수
function openNewWindow(url, name){
    var $main = $('.con1 .main');
    var width= $main.width(),
        height= $main.height();

    var left = (screen.availWidth - width) / 2;
    var top = (screen.availHeight - height) / 2;
    specs = `width=${width},height=${height},left=${left},top=${top}`;
    specs += ",location=no,directories=no,resizable=no,status=no,toolbar=no,menubar=no";

    window.open(url, name, specs);
}


// iframe 친구추가할때 사용되는 함수들
function hide_iframe(){
    $('.con1 .iframe-friends').hide();
}
function show_iframe(){
    $('.con1 .iframe-friends').show();
}
