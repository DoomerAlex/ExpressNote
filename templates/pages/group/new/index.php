<div class="new-group-page">
	<div class="new-group-page-form-box">
		<form action="" metod="post" id="new-group-form">
			<input type="text" name="group_name" value="" maxlength="200" class="new-group-form-title" placeholder="Название группы">

			
			<h2 class="new-group-h2">Выберите участников:</h2>
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

			<input type="submit" name="submit" value="Создать группу" class="new-group-submit-button">


		</form>
	</div>
	
</div>