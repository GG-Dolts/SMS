document.base={
	baseUrl:"http://123.206.114.104/",
	
	getData:function(params){
        if(!params.type){
            params.type='GET';
        }
        var that=this;
        $.ajax({
            type:params.type,
            url:this.baseUrl+params.url,
            data:params.data,
            success:function(data){
                params.sCallback && params.sCallback(data);
            },
            error:function(data){
                params.eCallback && params.eCallback(data);
            }
        });
    },

	getPage:function(data){
		if(!data["last_page"]){
			let page = "<div style='color: #FF1800'>没有数据啦</div>";
			$("#page").append(page);
			return;
		}
		var pagePart = '';
		var begin = 0;
		for(let i=1; i<=data["last_page"]; i++){
			pagePart += `
				<li class="${data['current_page'] == i ? 'active' : ''}">
				<span>${i} <span class="sr-only"></span></span>
				</li>`;
			begin++;
			if(begin > 4){
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
	},

	enterPage:function(func, keyword){
		$("#page input").keydown(function(event){
			if(event.keyCode == 13){
				console.log(keyword);
				if(!keyword){
					func($(this).val());
				}else{

					console.log(func);
					console.log($(this).val());
					func($(this).val(), keyword)
				}

			}
		});
	},

	
	gotoPage:function(func){
		var len = $("#page li").length;
		$("#page li").each(function(i, obj){
			$(obj).click(function(){
				if($(this).attr("class") !== "disabled" && i == 0){
					func($(this).val());
				}
				if($(this).attr("class") !== "disabled" && i == len-1){
					func($(this).val());
				}
				if($(this).attr("class") !== "active" && i !== 0 && i !== len-1){
					func($(this).text().trim());
				}
				
			});
		});
	},
	
	deleteOne:function(urlPart, func){
		$(".delete").each(function(i, obj){
			$(obj).click(function(){
				num = $(this).parent().siblings(":nth-child(2)").text();
				var params={
					url:urlPart+num,
					data:{},
					sCallback:function(data) {
						func(1);
					}
				};
				document.base.getData(params);
			});
		});
	},
	
	getDepartment:function(num, start, last){
		var result = "";
		start = start || 0;
		last = last || 3;
		num = num.toString();
		if(num.length > 3 && num.length<11){
			let dpart = num.slice(start, last);
			switch (dpart){
				case "101":
					result = "软件工程院系";
					break;
				case "102":
					result = "电子工程院系";
					break;
				case "103":
					result = "外语院系";
					break;
				case "104":
					result = "数学院系";
					break;
				case "105":
					result = "物理院系";
					break;
				case "106":
					result = "化学院系";
					break;
				case "107":
					result = "生物院系";
					break;
				case "108":
					result = "金融院系";
					break;
				case "109":
					result = "航空院系";
					break;
				case "110":
					result = "体育院系";
					break;
				case "111":
					result = "哲学院系";
					break;
				case "112":
					result = "文学院系";
					break;
				case "113":
					result = "医学院系";
					break;
				case "114":
					result = "管理院系";
					break;
				case "115":
					result = "土木工程院系";
					break;
			}
		}
		return result;
	},
	
	edit:function(last){
		$(".edit").each(function(i,obj){
			$(obj).click(function(){
				$("#edit").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
				//数据赋值
				for(i=2; i<=$(this).parent().siblings().length-last; i++){
					let value = $(this).parent().siblings(":nth-child("+i+")").text();
					//console.log(value);
					$("#edit input").eq(i-2).val(value);
				}
				let value = $(this).parent().siblings(":nth-last-child(2)").text();
				$("#edit textarea").eq(0).val(value);
				//console.log(value);
				//console.log($("#edit input").eq(2));
				//console.log($(this).parent().siblings());
			});
		});
	},
	
	add:function(){
		$(".add").each(function(i,obj){
			$(obj).click(function(){
				$("#add").css("display","block");
				$("#list").css("opacity","0.6");
				$("#head").css("opacity","0.6");
			});
		});
	},

	back:function(){
		$(".back").each(function(i,obj){
			$(obj).click(function(){
				$("#edit").css("display","none");
				$("#add").css("display","none");
				$("#list").css("opacity","1");
				$("#head").css("opacity","1");
			});
		});
	},
};

