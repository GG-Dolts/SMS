$(function(){

    var isSearch = false;
    document.base.add();
    back();
    search();
    init(1);

    function init(pageIndex){
        isSearch = false;
        var params={
            url:'course/by_employee',
            data:{page:pageIndex,size:5},
            sCallback:function(data) {
                $("#list tbody").empty();
                $("#page").empty();
                getList(data);
                document.base.getPage(data);
                addInfo();
                document.base.enterPage(init);
                document.base.gotoPage(init);
                document.base.deleteOne("course/delete/", init);
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
            url:'course/search',
            sCallback:function(data) {
                $("#list tbody").empty();
                $("#page").empty();
                getList(data);
                document.base.getPage(data);
                addInfo();
                document.base.gotoPage(search);
                document.base.enterPage(search);
                document.base.deleteOne("course/delete/", init);
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
            let room = row['room'];
            console.log(room['pivot']);
            let classroom = '';
            let time = '';
            for(let j=0; j<room.length; j++){
                let rstr = j+1 === room.length ? room[j]['name'] : room[j]['name']+',';
                let t = '周'+room[j]['pivot']['week']+' '+room[j]['pivot']['start_time']+'-'+room[j]['pivot']['end_time'];
                let tstr = j+1 === room.length ? t : t+',';
                classroom += rstr;
                time += tstr;
            }

            let rowHtml = `
			<tr>
                <td>#</td>
                <td>${row["id"]}</td>
                <td>${row["name"]}</td>
				<td>${classroom}</td>
				<td>${time}</td>
				<td>${row["term"]}</td>
                <td>
                    <button class="btn btn-success btn-xs add-info"><i class="glyphicon glyphicon-plus"></i> Add </button>
                    <button class="btn btn-danger btn-xs delete"><i class="glyphicon glyphicon-trash"></i> Delete </button>
                </td>
            </tr>
			`;
            $("#list tbody").append(rowHtml);
        }
    }

    function addInfo(){
        $(".add-info").each(function(i,obj){
            $(obj).click(function(){
                $("#add-info").css("display","block");
                $("#list").css("opacity","0.6");
                $("#head").css("opacity","0.6");
                let value = $(this).parent().siblings(':nth-child(2)').text();
                console.log(value);
                $("#add-info input").eq(0).val(value);
            });
        });
    }

    function back(){
        $(".back").each(function(i,obj){
            $(obj).click(function(){
                $("#add-info").css("display","none");
                $("#add").css("display","none");
                $("#list").css("opacity","1");
                $("#head").css("opacity","1");
            });
        });
    }


});
