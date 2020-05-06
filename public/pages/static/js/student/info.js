$(function(){
    init();

    function init() {
        var params={
            url:'student/info',
            sCallback:function(data) {
                //console.log(data);
                let detail = `
                    <table class="table table-bordered">
						<tr><th>编号</th><td>${data['num']}</td></tr>
						<tr><th>姓名</th><td>${data['name']}</td></tr>
						<tr><th>身份证</th><td>${data['identity']}</td></tr>
						<tr><th>性别</th><td>${data['gender']}</td></tr>
						<tr><th>入学年份</th><td>${data['year']}</td></tr>
						<tr><th>院系</th><td>${data['department']['name']}</td></tr>
						<tr><th>班级</th><td>${data['clazz'] != null ? data['clazz']['name'] : '还未分配'}</td></tr>
						<tr><th>宿舍</th><td>${data['room'] != null ? data['room']['name'] : '在外居住'}</td></tr>
						<tr><th>邮箱</th><td>${data['email']}</td></tr>
						<tr><th>籍贯</th><td>${data['from']}</td></tr>
					</table>
                `;
                course = data['course'];
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