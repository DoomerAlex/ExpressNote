$('.new-group-page').on('click', '.user-box-item', function(){
	if($(this).hasClass('active')){
		$(this).removeClass('active');
		$(this).find("input[name='user_check[]']").val('false');
	}
	else{
		$(this).addClass('active');
		$(this).find("input[name='user_check[]']").val('true');
	}
});

// отправка формы
$('.new-group-page').on('submit', '#new-group-form', function(e){
	e.preventDefault();
	var msg = $('#new-group-form').serialize();
	$.ajax({
		url: "/AjaxAddNewGroup",
		type: 'POST',
		data: msg,
		success: function(data){
			alert(data);
		}
	});
});