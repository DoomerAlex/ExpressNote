// вход в учетную запись
$('.content').on('submit', '#login_form', function(e){ // запрос на вход в учетную запись
	e.preventDefault();
	var msg = $('#login_form').serialize();
	$.ajax({
		url: "/AjaxLogin",
		type: "POST",
		data: msg,
		success: function(data){
			if (data == 'true') location.replace("/tasks");
			else $('.registration-forget-password').html('Не верный логин или пароль');
		}
	});
});


$('.content').on('click', '.registration-button', function(e){
	e.preventDefault();
	$.ajax({
		url: "/AjaxRegistrationForm",
		type: "POST",
		data: "",
		success: function(data){
			$('.registration-box-left').html(data);
			$('.registration-link-type-reg').html("<a href='/registration' class='registration-link-back'>Назад</a>");
		}
	});
});

$('.content').on('click', '.registration-step-1', function(){
		$('.registration-box-1').css('display', 'block');
		$('.registration-box-2').css('display', 'none');
		$('.registration-box-3').css('display', 'none');
		$('.registration-box-4').css('display', 'none');

		$('.registration-step-2').removeClass('active');
		$('.registration-step-3').removeClass('active');
		$('.registration-step-4').removeClass('active');
});

$('.content').on('click', '.registration-step-2', function(){

	if ( $('#reg_first_name').val() != '' && $('#reg_first_name').val() != ' ' && 
		$('#reg_last_name').val() != '' && $('#reg_last_name').val() != ''){

		$('.registration-box-1').css('display', 'none');
		$('.registration-box-2').css('display', 'block');
		$('.registration-box-3').css('display', 'none');
		$('.registration-box-4').css('display', 'none');

		$('.registration-step-2').addClass('active');
		$('.registration-step-3').removeClass('active');
		$('.registration-step-4').removeClass('active');

		$('#reg_phone').mask("8 (999) 999 99 99", {
			placeholder: "8 (___) ___ __ __",
			completed: function(){
				var phone = this.val();
				phone = phone.replace(/ /g, "");
				phone = phone.replace(/\)/g, "");
				phone = phone.replace(/\(/g, "");
				$.ajax({
					url: "/AjaxCheckPhone",
					type: "POST",
					data: {phone: phone},
					success: function(data){
						if (data == true) $('#reg_phone').css('border-color', '#6eb690');
						else $('#reg_phone').css('border-color', 'red');
					}
				});
			}
		});

	}
	else if( $('#reg_first_name').val() == '' || $('#reg_first_name').val() == ' '){
		$('#reg_first_name').css('border-color', 'red');
	}
	else if ($('#reg_last_name').val() == '' || $('#reg_last_name').val() == ' '){
		$('#reg_last_name').css('border-color', 'red');
	}
	else alert('Ошибка!');

});

$('.content').on('click', '.registration-step-3', function(){
	$('.registration-box-1').css('display', 'none');
	$('.registration-box-2').css('display', 'none');
	$('.registration-box-3').css('display', 'block');
	$('.registration-box-4').css('display', 'none');

	$('.registration-step-2').addClass('active');
	$('.registration-step-3').addClass('active');
	$('.registration-step-4').removeClass('active');
});

$('.content').on('click', '.registration-step-4', function(){
	$('.registration-box-1').css('display', 'none');
	$('.registration-box-2').css('display', 'none');
	$('.registration-box-3').css('display', 'none');
	$('.registration-box-4').css('display', 'block');

	$('.registration-step-2').addClass('active');
	$('.registration-step-3').addClass('active');
	$('.registration-step-4').addClass('active');
});


function CheckEmail(){ // проверка E-mail
	var email = $('#reg_email').val();
	email = email.replace(/ /g, "");
	var email_const = /^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*\.[A-Za-z]{2,6}$/;
	if (email_const.test(email) == true && email != ""){
		$.ajax({
			url: "/AjaxChekEmail",
			type: "POST",
			data: ({email: email}),
			success: function(data){
				if (data == true) $('#reg_email').css('border-color','#6eb690');
				else $('#reg_email').css('border-color','red');
			}
		});
	}
	else $('#reg_email').css('border-color','red');
}


// Регистрация
$('.content').on('submit', '#registration-form', function(e){
	e.preventDefault();
	var msg = $('#registration-form').serialize();
	$.ajax({
		url: "/AjaxRegistration",
		type: "POST",
		data: msg,
		success: function(data){
			if (data == true) location.replace("/tasks");
			else alert('Что-то пошло не так');
		}
	});
});