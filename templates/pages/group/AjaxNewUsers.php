<?if (count($arResult['users']) > 0):?>

<form action="" metod="post" id="new-group-form">
	<h2 class="new-group-h2">Выберите новых участников:</h2>
	<input type="hidden" name="group_id" value="<?=$arResult['group_id'];?>">
	<div class="new-group-users-box clearfix">
		
			<?foreach($arResult['users'] as $value):?>
				<div class="user-box-item clearfix">
					<input type="hidden" name="user_id[]" value="<?=$value['id']?>">
					<input type="hidden" name="user_check[]" value="false">
					<div class="user-box-item-img-box">
						<img src="/templates/images/avatar-user/<?=$value['avatar']?>" alt="image">
					</div>
					<div class="user-box-item-name-box">
						<?=$value['first_name'].' '.$value['last_name'];?>
					</div>
				</div>
			<?endforeach;?>
		
	</div>
	<input type="submit" name="submit" value="Отправить заявки" class="new-group-submit-button">
</form>
<?else:?>
	<div class="warning-massage">Нет контактов, котоым можно было бы отправить приглашение.</div>
<?endif;?>