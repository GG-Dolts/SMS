$(function(){
    init();

    function init() {
        var params={
            url:'employee/info',
            sCallback:function(data) {
                //console.log(data);
                let detail = `
                    <table class="table table-bordered">
                    <tr><th>编号</th><td>${data['num']}</td></tr>
                    <tr><th>姓名</th><td>${data['name']}</td></tr>
                    <tr><th>身份证</th><td>${data['identity']}</td></tr>
                    <tr><th>性别</th><td>${data['gender']}</td></tr>
                    <tr><th>学历</th><td>${data['education']}</td></tr>
                    <tr><th>头衔</th><td>${data['title']}</td></tr>
                    <tr><th>部门</th><td>${document.base.getDepartment(data['num'])}</td></tr>
                    <tr><th>邮箱</th><td>${data['email']}</td></tr>
                    <tr><th>住址</th><td>${data['address']}</td></tr>
                    </table>
                `;
                $('#detail').append(detail);
            },
            eCallback:function (data) {
                responseJSON = data.responseJSON;
                alert(responseJSON.message);
                window.parent.location = '../index/login.html';
            }
        };
        document.base.getData(params);
    }
});