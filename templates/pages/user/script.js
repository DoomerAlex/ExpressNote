
function addNewContakt(id){
	$.ajax({
		url: "/AjaxAddNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$('.user-buttons-box').html("<a href='#' class='user-button-back-contakt' onclick='BackNewContakt("+id+")'>Отменить заявку</a>");
			else
				alert(data);
		}
	});
}

function BackNewContakt(id){
	$.ajax({
		url: "/AjaxBackNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$('.user-buttons-box').html("<a href='#' class='user-button-add-contakt' onclick='addNewContakt("+id+")'>Добавить в контакты</a>");
			else
				alert(data);
		}
	});
}

function CloseNewContakt(id){
	$.ajax({
		url: "/AjaxCloseNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$('.user-buttons-box').html("<a href='#' class='user-button-add-contakt' onclick='addNewContakt("+id+")'>Добавить в контакты</a>");
			else
				alert(data);
		}
	});
}

function NewContakt(id){
	$.ajax({
		url: "/AjaxNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$('.user-buttons-box').html("<a href='#' class='user-button-chat'>Перейти в чат</a><a href='#' class='user-button-close-contakt' onclick='DeleteContakt("+id+")'>Удалить из контактов</a>");
			else
				alert(data);
		}
	});
}

function DeleteContakt(id){
	$.ajax({
		url: "/AjaxDeleteContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$('.user-buttons-box').html("<a href='#' class='user-button-add-contakt' onclick='addNewContakt("+id+")'>Добавить в контакты</a>");
			else
				alert(data);
		}
	});
}