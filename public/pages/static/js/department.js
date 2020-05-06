$(function(){

	var isSearch = false;
	document.base.add();
	document.base.back();
	search();
	init(1);

	function init(pageIndex){
		isSearch = false;
		var params={
            url:'department',
            data:{page:pageIndex,size:5},
            sCallback:function(data) {
            	//console.log(data);
				$("#list tbody").empty();
				$("#page").empty();
                getList(data);
				document.base.getPage(data);
				document.base.edit(3);
				document.base.enterPage(init);
				document.base.gotoPage(init);
				document.base.deleteOne("department/delete/", init);
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
			url:'department/search',
			sCallback:function(data) {
				$("#list tbody").empty();
				$("#page").empty();
				getList(data);
				document.base.getPage(data);
				document.base.edit(1);
				document.base.gotoPage(search);
				document.base.enterPage(search);
				document.base.deleteOne("department/delete/", init);
			}
		};
		$("#search button").click(function(){
			var keyword = $("#search input").val().trim();
			params.data = {q:keyword,page:pageIndex,size:5};
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
		let rows = data["data"] || data;
		var result = "";
		for(let i=0; i<rows.length; i++){
			let row = rows[i];
			let rowHtml = `
			<tr>
			<td>#</td>
			<td>${row["num"]}</td>
			<td>${row["name"]}</td>
			<td>${row["year"]}</td>
			<td>${row["delay"]}</td>
			<td>${row["introduce"]}</td>
			<td>
			<button class="btn btn-info btn-xs edit"><i class="glyphicon glyphicon-pencil"></i> Edit </button>
			<button class="btn btn-danger btn-xs delete"><i class="glyphicon glyphicon-trash"></i> Delete </button>
			</td>
			</tr>
			`;
			$("#list tbody").append(rowHtml);
		}
	}
	
	function getPage(data){
		var pagePart = '';
		var begin = 0;
		for(let i=1; i<=data["last_page"]; i++){
			pagePart += `
				<li class="${data['current_page'] == i ? 'active' : ''}">
				<span>${i} <span class="sr-only"></span></span>
				</li>`;
			begin++;
			if(begin > 3){
				break; 
			}
		}
		let page = `
			<span class="count">共${data["total"]}条</span>
			<ul class="pagination">
			<li class="${data['current_page'] == 1 ? 'disabled' : ''}" value="${data['current_page']-1}"><span aria-hidden="true"  aria-label="Previous">&laquo;</span></li>
			${pagePart}
			<li class="${data['current_page'] == data['last_page'] ? 'disabled' : ''}" value="${data['current_page']+1}">
			<span aria-hidden="true" aria-label="Next">&raquo;</span></li>
			</ul>
			<span class="goto">前往<input class="form-control" type="text" maxlength="2" value="${data['current_page']}">页</span>
		`;		
		$("#page").append(page);
	}

	function enterPage(){
		$("#page input").keydown(function(event){
			if(event.keyCode == 13){
				init($(this).val());
			}
		});
	}
	
	function gotoPage(){
		var len = $("#page li").length;
		$("#page li").each(function(i, obj){
			$(obj).click(function(){
				if($(this).attr("class") !== "disabled" && i == 0){
					init($(this).val());
				}
				if($(this).attr("class") !== "disabled" && i == len-1){
					init($(this).val());
				}
				if($(this).attr("class") !== "active" && i !== 0 && i !== len-1){
					init($(this).text().trim());
				}
				
			});
		});
	}
	

	function deleteOne(){
		$(".delete").each(function(i, obj){
			$(obj).click(function(){
				num = $(this).parent().siblings(":nth-child(2)").text();
				var params={
					url:'department/delete/'+num,
					data:{},
					sCallback:function(data) {
						init(1);
					}
				};
				document.base.getData(params);
			});
		});
	}
	
	function deleteAll(){
		
	}

	
	
});
