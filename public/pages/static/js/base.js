$("#avatar").mousemove(function(){
	$("#user-handle").css("display", "block");
});

$("#user-handle").mousemove(function(){
	$("#user-handle").css("display", "block");
	$("#avatar").mouseout(function(){
		$("#user-handle").css("display", "none");
	});
});

$("#user-handle").mouseout(function(){
	$("#user-handle").css("display", "none");
});

$("#nav li").each(function(i, obj){
	$(obj).mouseover(function(){
		$(this).css("backgroundColor","#939393");
		$(this).css("font-size","18px");
	});
	$(obj).mouseout(function(){
		$(this).css("backgroundColor","");
		$(this).css("font-size","");
	});
});