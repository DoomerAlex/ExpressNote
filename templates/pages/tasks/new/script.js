// Добавление подзадач
function addSubtask(id){
	var str = 	"<div id='new-task-form-subtask-close-"+id+"' class='new-task-form-subtask-close-box' style='display: none;'>"
					+ "<a href='#' class='new-task-form-subtask-close' onclick='return closeSubtask("+id+")' title='Удалить подзадачу'>"
						+ "<i class='fa fa-times' aria-hidden='true'></i>"
					+ "</a>"
					+ "<input type='text' name='new-task-subtasks[]' placeholder='Подзадача' class='new-task-form-subtask'>"
				+ "</div>"
				+ "<div id='subtask-"+(id+1)+"' class='new-task-form-subtask-box'>"
					+ "<a href='#'' onclick='return addSubtask("+(id+1)+")'>"
						+ "<i class='fa fa-plus' aria-hidden='true'></i> Добавить подзадачу"
					+ "</a>"
				+ "</div>";
	$('#subtask-'+id).html(str);
	$('#new-task-form-subtask-close-'+id).slideDown(300);
	return false;
}

function closeSubtask(id){
	$('#new-task-form-subtask-close-'+id).slideUp(300);
	var pause = "$('#new-task-form-subtask-close-"+id+"').html('')";
	setTimeout(pause, 300);
	return false;
}

$('.calendar-time-close-button').click(function(e){
	e.preventDefault();
	$('#calendar-time-block1').animate({opacity: 0}, 300);
	var pause = "$('#calendar-time-block1').css('display', 'none')";
	setTimeout(pause, 300);
	pause = "$('#calendar-time-block2').css('display', 'block')";
	setTimeout(pause, 300);
	$('#calendar-time-block2').delay(300).animate({opacity: 1}, 300);

	pause = "$('.calendar-time-input1').val('23')";
	setTimeout(pause, 300);
	pause = "$('.calendar-time-input2').val('59')";
	setTimeout(pause, 300);
});

$('.calendar-edit-time').click(function(e){
	e.preventDefault();
	$('#calendar-time-block2').animate({opacity: 0}, 300);
	var pause = "$('#calendar-time-block2').css('display', 'none')";
	setTimeout(pause, 300);
	pause = "$('#calendar-time-block1').css('display', 'block')";
	setTimeout(pause, 300);
	$('#calendar-time-block1').delay(300).animate({opacity: 1}, 300);

	$('.calendar-time-input1').val('19');
	$('.calendar-time-input2').val('00');
});

// валидация времени - часы
$('.calendar-time-input1').change(function(){
	var value = $(this).val();
	if (value > 0 && value <= 23){

	}
	else if (value >= 24){
		$(this).val('23');
	}
	else{
		$(this).val('00');
	}
});
// валидация времени - минуты
$('.calendar-time-input2').change(function(){
	var value = $(this).val();
	if (value > 0 && value <= 59){

	}
	else if (value >= 60){
		$(this).val('59');
	}
	else{
		$(this).val('00');
	}
});


$('#deadline-input-label').click(function(){
	$('.calendar').slideToggle(500);
});

$(".new-task-form-executor-select-box").click(function(){
	$('.new-task-form-executor-select-ul-box').slideToggle(500);
});

// выбор исполнителя из списка
function ChangeSelectExecutor(id_executor){

	$('#form_val_executor').val(id_executor);
	var id_block = "#form_select_value_"+id_executor;
	$('#form_executor_img').attr('src', $(id_block + ' img').attr('src'));
	$('#form_executor_name').html($(id_block + ' .new-task-form-executor-name-box').html());
	$('.new-task-form-executor-select-ul-box').slideUp(300);
	return false;
}

$('#add-new-task-form').submit(function(e){
	e.preventDefault();
	var msg = $('#add-new-task-form').serialize();
	$.ajax({
		url: "/AjaxAddTask",
		type: "POST",
		data: msg,
		success: function(data){
			if (data == true){
				$('.new-task-form-title').val(''); // очистка заголовка
				$('.new-task-form-textarea').val(''); // очистка текста
				alert('Задача поставлена!');
			}
			else alert(data);
		}
	});
});

/*============================ календарь =======================*/
var date 	= new Date(); 			// текущая дата
var month_trans = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];

var year 	= date.getFullYear(); 	// активный год
var month 	= date.getMonth(); 		// активный месяц


function getCalendarValues(date_val){

	$('.calendar .calendar-month').html(month_trans[month]);
	$('.calendar .calendar-year').html(year);

	var week = date_val.getDay();
	if (week == 0) week = 7;
	date_val.setDate( - week + 2);

	var str = "<table>" 
				+"<tr>"
					+"<th>Пн</th>"
					+"<th>Вт</th>"
					+"<th>Ср</th>"
					+"<th>Чт</th>"
					+"<th>Пт</th>"
					+"<th>Сб</th>"
					+"<th>Вс</th>"
				+"</tr>";

	var check = false;
	while (check == false){
		str += "<tr>";
		for (var i = 1; i <=7; i++){
			if (date_val.getMonth() != month) str += "<td><span class='";
			else str += "<td><a href='#' data-year='"+year+"' data-month='"+(month + 1)+"' data-day='"+date_val.getDate()+"' class='";
			if (date_val.getMonth() != month) str += "bloked "; // если не текущий месяц то блокируется
			if ($('#form_hidden_year').val() == year && $('#form_hidden_month').val() == (date_val.getMonth() + 1) && date_val.getDate() == $('#form_hidden_day').val()) str += "select ";
			str += "'>"+date_val.getDate();
			if (date_val.getMonth() != month) str += "</span></td>";
			else str += "</a></td>";
			date_val.setDate(date_val.getDate() + 1);
		}
		str += "</tr>";
		if(date_val.getMonth() > month) check = true;
		if(date_val.getFullYear() > year){
			check = true;
		}
	}
	check = false;

	str += "</table>";
	$('#calendar_change_body').html(str);
	str = '';
}

// при загрузке
	var date2 = new Date(year, month, 1, 0, 0, 0, 0);
	$('#form_hidden_day').val(date.getDate());
	$('#form_hidden_month').val(date.getMonth() + 1);
	$('#form_hidden_year').val(date.getFullYear());
	getCalendarValues(date2);


// следующий месяц
$('.calendar-month-next').click(function(e){
	e.preventDefault();
	if (month == 11) {
		month = 0;
		year++;
	}
	else month++;
	var date2 = new Date(year, month, 1, 0, 0, 0, 0);
	getCalendarValues(date2);
});

// предыдущий месяц
$('.calendar-month-pred').click(function(e){
	e.preventDefault();
	if (month == 0){
		month = 11;
		year--;
	} 
	else month--;
	var date2 = new Date(year, month, 1, 0, 0, 0, 0);
	getCalendarValues(date2);
});

// Выбор даты
$('.calendar').on('click', '#calendar_change_body > table a', function(e){
	e.preventDefault();
	$('#calendar_change_body > table a').removeClass('select');
	$('#calendar_change_body > table span').removeClass('select');
	$(this).addClass('select');
	$('#form_hidden_day').val($(this).attr('data-day'));
	$('#form_hidden_month').val($(this).attr('data-month'));
	$('#form_hidden_year').val($(this).attr('data-year'));
});