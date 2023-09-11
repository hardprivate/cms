$('.shows span:eq(0)').slideDown(300, function(){
	$(this).next().slideDown(300, arguments.callee);
});