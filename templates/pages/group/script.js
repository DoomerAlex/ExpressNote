$('.group-top-menu-box').on('click', '.group-top-menu-link', function(e){
	e.preventDefault();
	$('.group-top-menu-link').removeClass('active');
	$(this).addClass('active');
	$.ajax({
		url: $(this).attr('href'),
		type: 'post',
		data: {group_id: $(this).attr('data-group')},
		success: function(data){
			if (data != false){
				$('#group-ajax-content').html(data);
			}
			else alert(data);
		}
	});
});

// изменение прав доступа для админа
$('#group-ajax-content').on('change', '.change-user-access', function(){
	var result = confirm('Сменить права доступа?');
	if (result == true){
		$.ajax({
			url: "/AjaxChangeAccessForGroup",
			type: 'post',
			data: {user_id: $(this).attr('data-user'), access: $(this).val(), group_id: $(this).attr('data-group')},
			success: function(data){
				if (data != true){
					alert(data);
				}
			}
		});
	}
	else{

	}
});

// удаление пользователя из группы
function deleteUserForGroup(group_id, user_id){
	var result = confirm('Удалить пользователя из группы?');
	if (result == true){
		$.ajax({
			url: "/AjaxDeleteUserForGroup",
			type: 'post',
			data: {user_id: user_id, group_id: group_id},
			success: function(data){
				if (data != true){
					alert(data);
				}
				else{
					$('#group-user-row-'+user_id).slideUp(300);
				}
			}
		});
	}
}

// выбор новых участников группы
$('#group-ajax-content').on('click', '.user-box-item', function(){
	if($(this).hasClass('active')){
		$(this).removeClass('active');
		$(this).find("input[name='user_check[]']").val('false');
	}
	else{
		$(this).addClass('active');
		$(this).find("input[name='user_check[]']").val('true');
	}
});

// отправка формы новых участников группы
$('#group-ajax-content').on('submit', '#new-group-form', function(e){
	e.preventDefault();
	var msg = $('#new-group-form').serialize();
	$.ajax({
		url: "/AjaxAddNewUserInGroup",
		type: 'POST',
		data: msg,
		success: function(data){
			$('#group-ajax-content').html(data);
			alert('Заявки отправлены');
		}
	});
});