$(function(){
    var $u_id = $('input[name="user_id"]');
    var $u_pwd = $('input[name="user_password"]');
    $u_id.val(" ");
    $u_pwd.val("");


    $('.con .err_msg').hide();
    document.querySelector('input[type="submit"]').addEventListener('click', function(e){
        var user_id = $u_id.val();
        var user_password = $u_pwd.val();
        if($('input[name="user_password"]').val().length < 8)
        {
            e.preventDefault();
        }
        else
        {
//            e.preventDefault();
            $.ajax({
                type:'post',
                url:'/kakao/php/login_check.php',
                async:false, // ajax를 동기화 방식으로 사용해야함.
                data:
                {
                    user_id:user_id,
                    user_password:user_password
                },
                success:function(data)
                {
                    console.log(data);
                    if(data === '1')
                    {
                        $('.con .err_msg').hide();
                    }
                    else
                    {

                        e.preventDefault();
                        $('.con .err_msg').show();
                        return false;
                    }
                }
            })
        }
});
    $('input[name="user_password"]').keydown(function(e){
    setTimeout(function(){

        var len = $('input[name="user_password"]').val().length;
        if(e.keyCode ===  13)
        {
            if(len < 8)
            {
                return false;
            }
            else
            {
                $('input[type="submit"]').click();
//                alert($('input[name="user_id"]').val());
                return false;
            }
        }
        console.log(len);
        if(len >= 8 ){
            var bg_color = '#3a210fed';
            var color = "white";
            $('.con .err_msg').hide();
        }
        else{
            var bg_color = 'white';
            var color = "gray";
        }
        $('input[type="submit"]').css({
            'background-color':bg_color,
            'color':color
        });
    }, 100)
})

});

