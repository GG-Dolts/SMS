$(function(){
    init();

    function init(pageIndex){
        isSearch = false;
        var params={
            url:'student/course/my',
            data:{page:pageIndex,size:5},
            sCallback:function(data) {
                //console.log(data);
                $("#list tbody").empty();
                $("#page").empty();
                getList(data);
                document.base.getPage(data);
            },
            eCallback:function (data) {
                responseJSON = data.responseJSON;
                alert(responseJSON.message);
                window.parent.location = '../index/login.html';
            }
        };
        document.base.getData(params);
    }

    function getList(data){
        let courses = data["course"] || data;
        var result = "";
        for(let i=0; i<courses.length; i++){
            let row = courses[i];
            let rowHtml = `
			<tr>
			<td>#</td>
			<td>${row["id"]}</td>
			<td>${row["name"]}</td>
			<td>${row["term"]}</td>
			<td>${row["pivot"]['score']}</td>
			</tr>
			`;
            $("#list tbody").append(rowHtml);
        }
    }
});