
<div class="profile-content-page">
	<div class="clearfix">

		<h1 class="profile-name">
			<?=$arResult['data']['first_name'].' '.$arResult['data']['last_name']?> 
		</h1>
		<div class="profile-avatar-box">
			<? if ($arResult['data']['avatar'] != ''): ?>
				<img src="/templates/images/avatar-user/<?=$arResult['data']['avatar']?>" width="300" height="300">
			<? else: ?>
				<img src="/templates/images/avatar-user/none.jpg" width="300" height="300">
			<? endif; ?>

			<a href="/exit" class="exit-button">Выйти</a>
		</div>


		<div class="profile-contakt-info-box">
			<h2 class="profile-contakt-title">
				Контактная информация
			</h2>
			<div class="profile-contakt-mail">
				<i class="fa fa-envelope-o" aria-hidden="true"></i>
				<?=$arResult['data']['email']?>
			</div>
			<div class="profile-contakt-phone">
				<i class="fa fa-phone" aria-hidden="true"></i>
				<?=$arResult['data']['phone']?>
			</div>
		</div>

	</div>
</div>