function CancelContact(id){
	var id_block = '#contact-item-new-'+id;
	$.ajax({
		url: "/AjaxCloseNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$(id_block).css('display', 'none');
			else
				alert(data);
		}
	});
}

function AddContact(id){
	var id_block = '#contact-item-new-'+id;
	$.ajax({
		url: "/AjaxNewContakt",
		type: "POST",
		data: {id_contakt: id},
		success: function(data){
			if(data == true)
				$(id_block).css('display', 'none');
			else
				alert(data);
		}
	});
}

$(document).on('submit', '#contact-search-form', function(e){
	e.preventDefault();
	var msg = $('#contact-search-form').serialize();
	$.ajax({
		url: "/AjaxSearchUserForName",
		type: "POST",
		data: msg,
		success: function(data){
			if(data != 'false')$('.contact-user-box').html(data);
			//alert(data);
		}
	});
});

$(".contact-page").on('click', '.contact-menu-list-group', function(e){
	e.preventDefault();
	if($('.contact-list-group').css('display') == 'none'){
		$('.contact-list-group').slideDown(300);
		$('.contact-menu-list-group-arrow').addClass('active');
	}
	else{
		$('.contact-list-group').slideUp(300);
		$('.contact-menu-list-group-arrow').removeClass('active');
	}
});

// открытие формы приглашения в группу
$('.contact-page').on('click', '.contact-menu-list-new-group', function(e){
	e.preventDefault();
	$('.popup-form-invite').css('display','block');
});

$(document).on('click', '.new-group-invite-close', function(e){
	e.preventDefault();
	$('.popup-form-invite').css('display','none');
});

// принять приглашение в группу
function agreeGroup(group_id){
	$.ajax({
		url: '/AjaxInviteInGroup',
		type: 'post',
		data: {group_id: group_id, agree: 'true'},
		success: function(data){
			if(data == true){
				$('#invite_'+group_id).css('display', 'none');
				$('.contact-list-group').append("<li><a href='/group/"+group_id+"'>"+$("#invite_"+group_id+" .popup-form-column-name").html()+"</a></li>");
				$('.contact-menu-list-new-count-group').html($('.contact-menu-list-new-count-group').html() - 1);
				if($('.contact-menu-list-new-count-group').html() == 0){
					$('#contact-menu-list-new-group').slideUp(300);
					$('.popup-form-invite').css('display','none');
				}
				$('#main_menu_item_num_contacts').html($('#main_menu_item_num_contacts').html() - 1);
				if($('#main_menu_item_num_contacts').html() == 0) $('#main_menu_item_num_contacts').css('display', 'none');
			}
			else alert(data);
		}
	});
	return false;
}

// отклонить приглашение в группу
function disagreeGroup(group_id){
	$.ajax({
		url: '/AjaxInviteInGroup',
		type: 'post',
		data: {group_id: group_id, agree: 'false'},
		success: function(data){
			if(data == true){
				$('#invite_'+group_id).css('display', 'none');
				$('.contact-menu-list-new-count-group').html($('.contact-menu-list-new-count-group').html() - 1);
				if($('.contact-menu-list-new-count-group').html() == 0){
					$('#contact-menu-list-new-group').slideUp(300);
					$('.popup-form-invite').css('display','none');
				}
				$('#main_menu_item_num_contacts').html($('#main_menu_item_num_contacts').html() - 1);
				if($('#main_menu_item_num_contacts').html() == 0) $('#main_menu_item_num_contacts').css('display', 'none');
			}
			else alert(data);
		}
	});
	return false;
}