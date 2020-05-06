$(function(){
    $("#submit").click(function(){
        var num = $("#num"), password = $("#password");
        if(!num.val() || num.val().length > 12) {
            num.next().show().find('div').text('请正确输入用户名');
            return;
        }
        if(!password.val() || password.val().length > 30) {
            password.next().show().find('div').text('请正确输入密码');
            return;
        }

        var params={
            url:'login',
            type:'post',
            data:{num:num.val(),password:password.val()},
            sCallback:function(data){
                let scope = data['scope'];
                if(scope > 16){
                    window.location.href = '../manager/index.html';
                }else if(scope > 8){
                    window.location.href = '../teacher/index.html';
                }else{
                    window.location.href = '../student/index.html';
                }
            },
            eCallback:function(e){
                if(e.status==400){
                    $('.error-tips').text('帐号或密码错误').show().delay(2000).hide(0);
                }
            }
        };
        document.base.getData(params);
    });
});