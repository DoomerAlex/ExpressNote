$('body').on('change', ".checkbox-subtask", function(){
	if($(this).prop('checked')){
		// завершение
		$.ajax({
			url: "/AjaxCloseSubtask",
			type: "POST",
			data: ({id_subtask: $(this).attr('data-val')}),
			success: function(data){
				if (data != true) alert(data);
			}
		});

	}
	else{
		// возобновление
		$.ajax({
			url: "/AjaxOpenSubtask",
			type: "POST",
			data: ({id_subtask: $(this).attr('data-val')}),
			success: function(data){
				if (data != true) alert(data);
			}
		});
	}
});

// считает символы в комментариях
$('.task-page').on('keyup', '.task-add-comments-form-text', function(){
	var num = $(this).attr('maxlength');
	var count = $(this).val().length;
	$('#task-comment-form-num-words').html(num - count);
});

$('.task-page').on('submit','#task-add-comment-form', function(e){
	e.preventDefault();
	var msg = $('#task-add-comment-form').serialize();
	$.ajax({
		url: "/AjaxAddComment",
		type: "POST",
		data: msg,
		success: function(data){
			if(data == true){
				var date = new Date();
				var str = "<div class='task-comment-item clearfix' style='display:none'>"
							+"<div class='task-comment-item-left'>"
								+"<a href='/profile'>"
									+"<img src='"+$('.header-avatar-img').attr('src')+"' alt='image'>"
								+"</a>"
							+"</div>"
							+"<div class='task-comment-item-right'>"
								+"<div class='task-comment-item-name-box'>"
									+"<a href='/profile' class='task-comment-item-name'>"
										+$('.header-avatar-name-first').html()+" "+$('.header-avatar-name-last').html()
									+"</a>"
									+"<span class='task-comment-item-date'>"
										+date.getHours()+":"+date.getMinutes()+" "+date.getDate()+" "+date.getMonth()
									+"</span>"
								+"</div>"
								+"<div>"+$('.task-add-comments-form-text').val()+"</div>"
							+"</div>"
						+"</div>";

				
				$('.task-comments-block').append(str);
				$('.task-comments-block > .task-comment-item:last-child').slideDown(300);
				$('.task-add-comments-form-text').val('');
				$('#task-comment-form-num-words').html($('.task-add-comments-form-text').attr('maxlength'));
			}
			else alert(data);
		}
	});
});

$('.task-page').on('click', '.task-comment-item-close', function(e){
	e.preventDefault();
	var id = $(this).attr('data-val');
	$.ajax({
		url: "/AjaxDeleteComment",
		type: "POST",
		data: ({comment_id: id}),
		success: function(data){
			if(data == true){
				$("#task-comment-item-"+id).slideUp(300);
			}
			else alert(data);
		}
	});
});


// изменить статус задачи
function cahngeStatusTask(task_id, event){
	$.ajax({
		url: "/AjaxChangeStatusTask",
		type: "POST",
		data: ({task_id: task_id, status: event}),
		success: function(data){
			if(data == true){
				$.ajax({
					url: "/AjaxReloadDetailTask",
					type: "POST",
					data: ({task_id: task_id}),
					success: function(data2){
						if(data2 != false){
							$(".task-page").html(data2);
						}
						else alert(data2);
					}
				});
			}
			else alert(data);
		}
	});
}


$('.task-page').on('click','#butt-comments', function(){
	$('.task-comments-history-button').removeClass('active');
	$(this).addClass('active');
	$('.task-comments-section').css('display','block');
	$('.task-history-section').css('display','none');
});

$('.task-page').on('click', '#butt-history', function(){
	$('.task-comments-history-button').removeClass('active');
	$(this).addClass('active');
	$('.task-comments-section').css('display','none');
	$('.task-history-section').css('display','block');
});