$(function(){

    var isSearch = false;
    document.base.add();
    back();
    search();
    init(1);

    function init(pageIndex){
        isSearch = false;
        var params={
            url:'student/by_employee',
            data:{page:pageIndex,size:10},
            sCallback:function(data) {
                $("#list tbody").empty();
                $("#page").empty();
                $("#detail").empty();
                getList(data);
                changeClass();
                changeRoom();
                getCourse();
                AddCourse();
                getOne();
                document.base.getPage(data);
                document.base.edit(5);
                document.base.enterPage(init);
                document.base.gotoPage(init);
                document.base.deleteOne("student/delete/", init);
            },
            eCallback:function (data) {
                responseJSON = data.responseJSON;
                alert(responseJSON.message);
                window.parent.location = '../index/login.html';
            }
        };
        document.base.getData(params);
    }

    function search(pageIndex){
        var pageIndex = pageIndex || 1;
        var params={
            url:'student/search',
            sCallback:function(data) {
                $("#list tbody").empty();
                $("#page").empty();
                getList(data);
                changeClass();
                changeRoom();
                getCourse();
                AddCourse();
                getOne();
                document.base.getPage(data);
                document.base.edit(5);
                document.base.gotoPage(search);
                document.base.enterPage(search);
                document.base.deleteOne("student/delete/", init);
            }
        };
        $("#search button").click(function(){
            var keyword = $("#search input").val().trim();
            params.data = {q:keyword,page:pageIndex,size:10};
            isSearch = true;
            document.base.getData(params);

        });
        if(isSearch){
            var keyword = $("#search input").val().trim();
            params.data = {q:keyword,page:pageIndex,size:5};
            document.base.getData(params);
        }
    }

    function getList(data){
        let rows = data["data"];
        var result = "";
        for(let i=0; i<rows.length; i++){
            let row = rows[i];
            //console.log(row);
            let rowHtml = `
			<tr>
                <td>#</td>
                <td>${row["num"]}</td>
                <td>${row["name"]}</td>
                <td>${row["email"]}</td>
                <td>${row["from"]}</td>
                <td>${row["department"]["name"]}</td>
                <td>${row["gender"]}</td>
				<td>${row["status"]}</td>
				<td>
					<span>${row["clazz"] != null ? row["clazz"]["name"] : "还未分配"}</span>
                </td>
                <td>
					<button class="btn btn-primary btn-xs course-view"><i class="glyphicon glyphicon-list-alt"></i> View </button>
                </td>
                <td>
                    <button class="btn btn-primary btn-xs student-view"><i class="glyphicon glyphicon-list-alt"></i> View </button>
                </td>
            </tr>
			`;
            $("#list tbody").append(rowHtml);
        }
    }

    function getOne(){
        $(".student-view").each(function(i,obj){
            $(obj).click(function(){
                $("#detail").empty();
                $("#student-view").css("display","block");
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                var num = $(this).parent().siblings(":nth-child(2)").text();
                //console.log(num);

                var params={
                    url:'student/'+num,
                    sCallback:function(data) {
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
                        $('#detail').append(detail);
                    }
                };
                document.base.getData(params);
            });
        });
    }

    function changeClass(){
        $(".class-edit").each(function (i,obj) {
            $(obj).click(function(){
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                $("#class-edit").css("display","block");
                $("#class-list").empty();
                var dnum = $(this).attr('data-value');
                var snum = $(this).parent().siblings(":nth-child(2)").text();
                var params = {
                    url:'class/by_department',
                    data:{id:dnum},
                    sCallback:function(data){
                        var class_list = $("#class-list");
                        if(data.length > 0){
                            var line = 0;
                            for(let i = 0; i<data.length; i++){
                                let row = data[i];
                                let changeLine = '';
                                let list = `
									<span class="btn btn-info goto-change" data-value="${snum+','+row['id']}">${row['name']}</span>
								`;
                                line ++;
                                changeLine = line%5 == 0 ? "<hr/>" : '';
                                list = list + changeLine;
                                class_list.append(list);
                            }
                        }else{
                            class_list.append("<p>该院系还没有任何班级</p>");
                        }
                        class_list.append('<p></p>');
                        gotoChangeClass();
                    },
                };
                document.base.getData(params);
            });
        });
    }

    function gotoChangeClass(){
        $(".goto-change").each(function(i,obj){
            $(obj).click(function(){
                let data = $(this).attr('data-value');
                let dataArr = data.split(",");
                //console.log(dataArr);
                var params = {
                    url:'student/class/change',
                    type: 'POST',
                    data:{num:dataArr[0], id:dataArr[1]},
                    sCallback:function(data){
                        if(data){
                            alert("更改班级成功");
                        }
                        $("#class-edit").css("display","");
                        $("#list").css("opacity","1");
                        $("#head").css("opacity","1");
                        init(1);
                    }
                };
                document.base.getData(params);
            });
        });
    }

    function getCourse(){
        $(".course-view").each(function (i,obj) {
            $(obj).click(function(){
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                $("#course-view").css("display","block");
                $("#course-view-list").empty();
                var num = $(this).parent().siblings(":nth-child(2)").text();
                var params = {
                    url:'student/course/by_num',
                    data:{id:num},
                    sCallback:function(data){
                        var course = data['course'];
                        var course_list = $("#course-view-list");
                        if(course.length > 0){
                            var line = 0;
                            for(let i = 0; i<course.length; i++){
                                let row = course[i];
                                let changeLine = '';
                                let list = `
									<span class="btn btn-info goto-edit" data-value="${num+","+row['id']+","+row['name']}">${row['name']}</span>
								`;
                                line ++;
                                changeLine = line%5 == 0 ? "<hr/>" : '';
                                list = list + changeLine;
                                course_list.append(list);
                            }
                        }else{
                            course_list.append("<p>该学生还没有任何课程</p>");
                        }
                        course_list.append('<p></p>');
                        editCourse();
                    },
                };
                document.base.getData(params);
            });
        });
    }

    function editCourse(){
        $(".goto-edit").each(function(i,obj){
            $(obj).click(function(){
                let data = $(this).attr('data-value');
                let dataArr = data.split(",");
                console.log(dataArr);
                $("#course-edit").css("display","block");
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                for(let i=0; i<dataArr.length; i++){
                    $("#course-edit input").eq(i).val(dataArr[i]);
                }
            });
        });
    }

    function AddCourse(){
        $(".course-add").each(function (i,obj) {
            $(obj).click(function(){
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                $("#course-add").css("display","block");
                $("#course-list").empty();
                var snum = $(this).parent().siblings(":nth-child(2)").text();
                var params = {
                    url:'course/all',
                    sCallback:function(data){
                        var course_list = $("#course-list");
                        if(data.length > 0){
                            var line = 0;
                            for(let i = 0; i<data.length; i++){
                                let row = data[i];
                                let changeLine = '';
                                let list = `
									<span class="btn btn-info goto-change" data-value="${snum+','+row['id']}">${row['name']}</span>
								`;
                                line ++;
                                changeLine = line%5 == 0 ? "<hr/>" : '';
                                list = list + changeLine;
                                course_list.append(list);
                            }
                        }else{
                            course_list.append("<p>未找到课程</p>");
                        }
                        course_list.append('<p></p>');
                        gotoAddCourse();
                    },
                };
                document.base.getData(params);
            });
        });
    }

    function gotoAddCourse(){
        $(".goto-change").each(function(i,obj){
            $(obj).click(function(){
                let data = $(this).attr('data-value');
                let dataArr = data.split(",");
                console.log(dataArr);
                var params = {
                    url:'student/course/add',
                    type: 'POST',
                    data:{s:dataArr[0], c:dataArr[1]},
                    sCallback:function(data){
                        if(data){
                            alert("添加课程成功");
                        }
                        $("#course-add").css("display","");
                        $("#list").css("opacity","1");
                        $("#head").css("opacity","1");
                        init(1);
                    },
                    eCallback:function (data) {
                        alert(data.responseJSON.message+'，请重新选择');
                    }
                };
                document.base.getData(params);
            });
        });
    }

    function changeRoom(){
        $(".room-edit").each(function (i,obj) {
            $(obj).click(function(){
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                $("#room-edit").css("display","block");
                $("#room-list").empty();
                var gender = $(this).attr('data-value');
                var snum = $(this).parent().siblings(":nth-child(2)").text();
                var params = {
                    url:'room/by_gender',
                    data:{gender:gender},
                    sCallback:function(data){
                        var room_list = $("#room-list");
                        if(data.length > 0){
                            var line = 0;
                            for(let i = 0; i<data.length; i++){
                                let row = data[i];
                                let changeLine = '';
                                let list = `
									<span class="btn btn-info goto-change" data-value="${snum+','+row['id']}">${row['name']}</span>
								`;
                                line ++;
                                changeLine = line%5 == 0 ? "<hr/>" : '';
                                list = list + changeLine;
                                room_list.append(list);
                            }
                        }else{
                            room_list.append("<p>未找到宿舍</p>");
                        }
                        room_list.append('<p></p>');
                        gotoChangeRoom();
                    },
                };
                document.base.getData(params);
            });
        });
    }

    function gotoChangeRoom(){
        $(".goto-change").each(function(i,obj){
            $(obj).click(function(){
                let data = $(this).attr('data-value');
                let dataArr = data.split(",");
                var params = {
                    url:'student/room/change',
                    type: 'POST',
                    data:{num:dataArr[0], id:dataArr[1]},
                    sCallback:function(data){
                        if(data){
                            alert("更改宿舍成功");
                        }
                        $("#room-edit").css("display","");
                        $("#list").css("opacity","1");
                        $("#head").css("opacity","1");
                        init(1);
                    }
                };
                document.base.getData(params);
            });
        });
    }

    function back(){
        $(".back").each(function(i,obj){
            $(obj).click(function(){
                $("#edit").css("display","none");
                $("#add").css("display","none");
                $("#student-view").css("display","none");
                $("#course-edit").css("display","none");
                $("#course-add").css("display","none");
                $("#course-view").css("display","none");
                $("#class-edit").css("display","none");
                $("#room-edit").css("display","none");
                $("#list").css("opacity","1");
                $("#head").css("opacity","1");
            });
        });
    }

    function deleteAll(){

    }



});
