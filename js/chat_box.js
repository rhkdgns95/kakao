$(function(){
    $('.chat-box .chatting-list').click(function(){

        if(!$(this).hasClass('active'))
        {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

        }
    })
})