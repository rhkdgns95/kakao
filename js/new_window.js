$(function(){
  $('.btn-box span a').click(function(e){
      e.preventDefault();
     mywin=window.open('/kakao/php/signup_page.php','window','location=no,directories=no,resizable=no,status=no,toolbar=no,menubar=no,width=500,height=400,left=0,top=0, scrollbars=yes');
  })
})

//$('.friends-list > li').dblclick(function(){
//    var num = $(this).find('.friends-no').text();
//    var url = "/kakao/php/chatting-room.php?user_id=<?//=$_SESSION['user_id']?>//&friends_no="+num+".php";
//
//    openNewWindow(url, 'window');
//})
//    function openNewWindow(url, name){
//    var $main = $('.con1 .main');
//    var width=$main.width(),
//        height=$main.height();
//    var left = (screen.availWidth - width) / 2;
//    var top = (screen.availHeight - height) / 2;
//    specs = `width=${width},height=${height},left=${left},top=${top}`;
//    specs += ",location=no,directories=no,resizable=no,status=no,toolbar=no,menubar=no";
//
//    window.open(url, name, specs);
//    }

