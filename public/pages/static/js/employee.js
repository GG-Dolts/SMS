$(function(){

	var isSearch = false;
	document.base.add();
	back();
	search();
	init(1);
	
	function init(pageIndex){
		isSearch = false;
		var params={
            url:'employee',
            data:{page:pageIndex,size:10},
            sCallback:function(data) {
				$("#list tbody").empty();
				$("#page").empty();
				$("#detail").empty();
				$("#class-list").empty();
				$("#course-list").empty();
                getList(data);
				document.base.getPage(data);
				getOne();
				getClass();
				addClass();
				getCourse();
				addCourse();
				document.base.edit(5);
				document.base.enterPage(init);
				document.base.gotoPage(init);
				document.base.deleteOne("employee/delete/", init);
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
			url:'employee/search',
			sCallback:function(data) {
				$("#list tbody").empty();
				$("#page").empty();
				getList(data);
				document.base.getPage(data);
				getOne();
				getClass();
				addClass();
				getCourse();
				addCourse();
				document.base.edit(5);
				document.base.gotoPage(search);
				document.base.enterPage(search);
				document.base.deleteOne("employee/delete/", init);
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
                <td>${row["education"]}</td>
                <td>${row["belongs"] != null ? row["belongs"]["name"] : "其他"}</td>
                <td>${row["gender"]}</td>
				<td>${row["title"]}</td>
				<td>${row["status"]}</td>
				<td>
                    <button class="btn btn-primary btn-xs class-view"><i class="glyphicon glyphicon-list-alt"></i> View </button>
                    <button class="btn btn-success btn-xs class-add"><i class="glyphicon glyphicon-plus"></i> Add </button> 
                </td>
                <td>
					<button class="btn btn-primary btn-xs course-view"><i class="glyphicon glyphicon-list-alt"></i> View </button>
                    <button class="btn btn-success btn-xs course-add"><i class="glyphicon glyphicon-plus"></i> Add </button>
                </td>
                <td>
                    <button class="btn btn-primary btn-xs employee-view"><i class="glyphicon glyphicon-list-alt"></i> View </button>
                    <button class="btn btn-info btn-xs edit"><i class="glyphicon glyphicon-pencil"></i> Edit </button>
                    <button class="btn btn-danger btn-xs delete"><i class="glyphicon glyphicon-trash"></i> Delete </button>
                </td>
            </tr>
			`;
			$("#list tbody").append(rowHtml);
		}
	}
	
	function getOne(){
		$(".employee-view").each(function(i,obj){
			$(obj).click(function(){
				$("#detail").empty();
				$("#employee-view").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				var num = $(this).parent().siblings(":nth-child(2)").text();
				//console.log(num);

				var params={
					url:'employee/'+num,
					sCallback:function(data) {
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
				$("#employee-view").css("display","none");
				$("#course-add").css("display","none");
				$("#course-edit").css("display","none");
				$("#course-view").css("display","none");
				$("#class-add").css("display","none");
				$("#class-edit").css("display","none");
				$("#class-view").css("display","none");
				$("#list").css("opacity","1");
				$("#head").css("opacity","1");
			});
		});
	}

	function getClass(){
		$(".class-view").each(function (i,obj) {
			$(obj).click(function(){
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				$("#class-view").css("display","block");
				$("#class-list").empty();
				var num = $(this).parent().siblings(":nth-child(2)").text();
				console.log(num);
				var params = {
				 	url:'class/get/'+num,
					sCallback:function(data){
				 		var clazz = data['clazz'];
				 		console.log(clazz);
						var class_list = $("#class-list");
				 		if(clazz.length > 0){
				 			var line = 0;
				 			for(let i = 0; i<clazz.length; i++){
				 				let row = clazz[i];
				 				let changeLine = '';
								let list = `
									<span class="btn btn-info goto-edit" data-value="${num+","+row['id']+","+row['name']}">${row['name']}</span>
								`;
								line ++;
								changeLine = line%5 == 0 ? "<hr/>" : '';
								list = list + changeLine;
								class_list.append(list);
							}
						}else{
							class_list.append("<p>该教职员工没有指导任何班级</p>");
						}
						class_list.append('<p></p>');
				 		editClass();
					},
				};
				document.base.getData(params);
			});
		});
	}

	function editClass(){
		$(".goto-edit").each(function(i,obj){
			$(obj).click(function(){
				let data = $(this).attr('data-value');
				let dataArr = data.split(",");
				console.log(dataArr);
				$("#class-edit").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				for(let i=0; i<dataArr.length; i++){
					$("#class-edit input").eq(i).val(dataArr[i]);
				}
			});
		});
	}

	function addClass(){
		$(".class-add").each(function(i,obj){
			$(obj).click(function(){
				var num = $(this).parent().siblings(":nth-child(2)").text();
				console.log(num);
				$("#class-add").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				$("#class-add input").eq(0).val(num);
			});
		});
	}

	function getCourse(){
		$(".course-view").each(function (i,obj) {
			$(obj).click(function(){
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				$("#course-view").css("display","block");
				$("#course-list").empty();
				var num = $(this).parent().siblings(":nth-child(2)").text();
				console.log(num);
				var params = {
					url:'course/get/'+num,
					sCallback:function(data){
						var course = data['course'];
						console.log(course);
						var course_list = $("#course-list");
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
							course_list.append("<p>该教职员工没有指导任何课程</p>");
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
				//console.log(dataArr);
				$("#course-edit").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				for(let i=0; i<dataArr.length; i++){
					$("#course-edit input").eq(i).val(dataArr[i]);
				}
			});
		});
	}

	function addCourse(){
		$(".course-add").each(function(i,obj){
			$(obj).click(function(){
				var num = $(this).parent().siblings(":nth-child(2)").text();
				$("#course-add").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				$("#course-add input").eq(0).val(num);
			});
		});
	}
	
	function deleteAll(){
		
	}

	
	
});
