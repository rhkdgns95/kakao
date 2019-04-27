$(function(){
//    id 중복확인 버튼 눌렀을경우
            $('input[type="button"]').click(function(){
            var user_id = $('input[name="user_id"]').val();
//                if($('input[name="user_id"]').prop('disabled') === true)
//                    alert('check_success');

            if(check_id(user_id))
            {
                $.ajax({
                    type:'post',
                    data:{
                        user_id:user_id
                    },
                    url:'/kakao/db/db_is_exist_user.php',
                    success:function(data){
                        if(data)
                        {
                            $('.user_check').text('사용가능').css('color','blue');
                            $('input[name="id_check"]').val(user_id);
                            $('input[name="user_id"]').prop("disabled", true);
                        }
                        else
                        {
                            $('.user_check').text('이미사용중').css('color','red');
                            $('input[name="id_check"]').val("not");
                        }
                    }
                })
            }
            else
            {
                $('.user_check').text('확인안됨').css('color','black');
                $('input[name="id_check"]').val("not");
            }

        })

//    회원가입 눌렀을경우
    document.querySelector('input[type="submit"]').addEventListener('click', function(e){
        var check = $('input[name="id_check"]').val();
        // disabled를 F12눌러서 변경가능하므로...
        if($('input[name="user_name"]').val().length === 0 )
        {
            alert("이름입력해주세요!");
            e.preventDefault();
            return false;
        }
        if(check === "not" || $('input[name="user_id"]').prop('disabled') ===false){
            alert('아이디 다시 확인해주세요!');
            e.preventDefault();
            return false;
        }
        if($('input[name="user_password"]').val() !== $('input[name="user_password2"]').val() || $('input[name="password_check"]').val() ==='not' || $('input[name="password_check2"]').val() === 'not')
        {
            alert('비밀번호 다시 확인해주세요!');
            e.preventDefault();
            return false;
        }
        $('input[name="user_id"]').prop("disabled", false);
    })

//    1차패스워드 키 다운시
    $('input[name="user_password"]').keydown(function(){
        setTimeout(function(){
            var con = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/;
            var user_password = $('input[name="user_password"]').val();
            check = con.test(user_password);
            if(check){
                $('.password_check').text('사용가능').css('color','blue');
                $('input[name="password_check"]').val(user_password);
            }
            else{
                $('.password_check').text('사용불가').css('color','red');
                $('input[name="password_check"]').val("not");
            }
            if($('input[name="user_password2"]').val().length !== 0)
            {

                console.log('이거실행전!');
                if(user_password === $('input[name="user_password2"]').val() && $('input[name="password_check"]').val() !== 'not')
                {
                    console.log('이거실행!');
                    $('.password_check2').text('일치함').css('color','blue');
                    $('input[name="password_check2"]').val(user_password);
                }
                else
                {
                    $('input[name="password_check2"]').val('not');
                    $('.password_check2').text('비밀번호 재확인').css('color','red');
                }
            }
            if(user_password.length === 0 )
            {
                 $('.password_check').text('');
                 $('input[name="password_check"]').val('not');
            }

        }, 100)
    })
    //2차 패스워드 키 다운시
    $('input[name="user_password2"]').keydown(function(){
//        var first_password = $('input[name="password_check"]').val();

        setTimeout(function(){
            var first_password = $('input[name="password_check"]').val();
            var second_password = $('input[name="user_password2"]').val();
            if(first_password === second_password)
            {
                $('.password_check2').text('일치함').css('color','blue');
                $('input[name="password_check2"]').val(second_password);
            }
            else
            {
                $('.password_check2').text('비밀번호 재확인').css('color','red');
                $('input[name="password_check2"]').val('not');
            }
            if(second_password.length === 0){
                $('.password_check2').text('');
                $('input[name="password_check2"]').val('not');
            }
        }, 100);
    })
})

var check_id = function(id)
{
     var id_len = id.length;
     if(id_len >=6 && id_len <= 14)
     {

        for(var i=0; i<id_len; i++)
        {
            var tmp = id.substr(i, 1);
            correct_id = tmp >= 'a' && tmp<= 'z';
            if(tmp >= 'a' && tmp <= 'z')
            {
                continue;
            }
            else if(tmp >= 'A' && tmp <= 'Z')
            {
                continue;
            }
            else if(tmp >=0 && tmp <= 9)
            {
                continue;
            }
            else{
                alert('아이디는 영어 대/소문자 및 숫자만 가능합니다!');
                return false;
            }
        }
        return true;
    }
    else
    {
        alert('아이디 길이는 6자이상 14자 이하만 가능합니다');
        return false;
    }
}

