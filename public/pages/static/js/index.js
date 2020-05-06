$(function(){
    $("#logout").click(function () {
        var keys = document.cookie.match(/[^ =;]+(?==)/g);
        if (keys) {
            for (var i = keys.length; i--;) {
                document.cookie = keys[i] + '=0;path=/;expires=' + new Date(0).toUTCString();
                document.cookie = keys[i] + '=0;path=/;domain=' + document.domain + ';expires=' + new Date(0).toUTCString();
                document.cookie = keys[i] + '=0;path=/;domain=ratingdog.cn;expires=' + new Date(0).toUTCString();
            }
        }
    });

    function getImg(){
        var params = {
            url:'employee/info',
            sCallback:function(data) {
                $('#avatar img').attr('src', '../'+data['image']);
            }
        };
        document.base.getData(params);
    }
    getImg();
});