// открывает/закрывает список выбора типа задач
$('.select-box').click(function(){
	
	if($(this).find('ul').css('display') == 'none'){
		$(this).find('ul').slideDown(300);
	}
	else{
		$(this).find('ul').slideUp(300);
	}
});

// выбор из списка
$('.task-page').on('click', '.select-choise', function(){
	$( "#"+$(this).attr('data-id') ).val( $(this).attr('data-val') );
	$( "#"+$(this).attr('data-id')+"-val").html( $(this).html() );
	$( "#"+$(this).attr('data-id')+"-select").slideUp(300);
	getTasksWhithFilter();
});

// выбор от меня / мне
$('.task-page').on('click', '.task-filter-top-button', function(){
	$('.task-filter-top-button').removeClass('active');
	$(this).addClass('active');
	$('#executor-filter').val($(this).attr('data-val'));
	getTasksWhithFilter();
});


$('.task-page').on('change', '.js-filter-task-chnge', function(){
	getTasksWhithFilter();
});

function getTasksWhithFilter(){
	var form = $("#executor-filter, #filter-status-task, #deadline-filter, #control-filter").serialize();
	$.ajax({
		url: "/AjaxFilterTasks",
		type: "post",
		data: form,
		success: function(data){
			alert(data);
		}
	});
}