$(function(){

	var isSearch = false;
	document.base.add();
	document.base.back();
	search();
	init(1);
	
	function init(pageIndex){
		isSearch = false;
		var params={
            url:'room',
            data:{page:pageIndex,size:5},
            sCallback:function(data) {
				$("#list tbody").empty();
				$("#page").empty();
                getList(data);
				document.base.getPage(data);
				document.base.edit(1);
				document.base.enterPage(init);
				document.base.gotoPage(init);
				document.base.deleteOne("room/delete/", init);
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
			url:'room/search',
			sCallback:function(data) {
				$("#list tbody").empty();
				$("#page").empty();
				getList(data);
				document.base.getPage(data);
				document.base.edit(1);
				document.base.gotoPage(search);
				document.base.enterPage(search);
				document.base.deleteOne("room/delete/", init);
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
		let rows = data["data"];
		var result = "";
		for(let i=0; i<rows.length; i++){
			let row = rows[i];
			let rowHtml = `
			<tr>
                <td>#</td>
                <td>${row["id"]}</td>
                <td>${row["name"]}</td>
                <td>${row["gender"]}</td>
                <td>
                    <button class="btn btn-info btn-xs edit"><i class="glyphicon glyphicon-pencil"></i> Edit </button>
                    <button class="btn btn-danger btn-xs delete"><i class="glyphicon glyphicon-trash"></i> Delete </button>
                </td>
            </tr>
			`;
			$("#list tbody").append(rowHtml);
		}
	}
	
	
	function add(){

	}
	
	function edit(){
		
	}
	
	
	function deleteAll(){
		
	}

	
	
});
