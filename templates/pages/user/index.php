
<div class="profile-content-page clearfix">
	
		
		<div class="profile-avatar-box">
			<? if ($arResult['data']['avatar'] != ''): ?>
				<img src="/templates/images/avatar-user/<?=$arResult['data']['avatar']?>" width="300" height="300">
			<? else: ?>
				<img src="/templates/images/avatar-user/none.jpg" width="300" height="300">
			<? endif; ?>


			<div class="user-buttons-box">
				<? if ($arResult['friend'] == 'true'):?>
					<a href="#" class="user-button-chat">Перейти в чат</a>
					<a href="#" class="user-button-close-contakt" onclick="DeleteContakt(<?=$arResult['user_id']?>)">Удалить из контактов</a>
				<? elseif ($arResult['friend'] == 'false'): ?>
					<a href="#" class="user-button-add-contakt" onclick="addNewContakt(<?=$arResult['user_id']?>)">Добавить в контакты</a>
				<? elseif ($arResult['friend'] == 'none'): ?>
					
				<? elseif ($arResult['friend'] == 'wait'): ?>
					<a href="#" class="user-button-back-contakt" onclick="BackNewContakt(<?=$arResult['user_id']?>)">Отменить заявку</a>
				<? elseif ($arResult['friend'] == 'choise'): ?>
					<div class="user-massage-under-photo">
						Вам поступила заявка.<br>
						Добавить пользователя в список контактов?
					</div>
					<div class="clearfix">
						<a href="#" class="user-button-ok" onclick="NewContakt(<?=$arResult['user_id']?>)">Принять</a>
						<a href="#" class="user-button-no" onclick="CloseNewContakt(<?=$arResult['user_id']?>)">Отклонить</a>
					</div>
				<?endif;?>
			</div>


		</div>
		
		<div class="profile-contakt-info-box">
			<h1 class="profile-name">
				<?=$arResult['data']['first_name'].' '.$arResult['data']['last_name']?> 
			</h1>
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

		<div class="user-tasks-box">
			<h2 class="user-tasks-h2">Сводка по задачам</h2>
			<table class="user-tasks-table">
				<tbody>
					<tr>
						<td>Новые</td>
						<td class="user-tasks-table-num">1</td>
					</tr>
					<tr>
						<td>В работе</td>
						<td class="user-tasks-table-num">5</td>
					</tr>
					<tr>
						<td>Просрочены</td>
						<td class="user-tasks-table-num">2</td>
					</tr>
					<tr>
						<td>Отложены</td>
						<td class="user-tasks-table-num">0</td>
					</tr>
					<tr>
						<td>Наблюдает</td>
						<td class="user-tasks-table-num">2</td>
					</tr>
					<tr>
						<td>Сделаны</td>
						<td class="user-tasks-table-num">24</td>
					</tr>
				</tbody>
			</table>
		</div>
	
</div>