<?
	foreach ($arResult['users'] as $val){
		if($_SESSION['user_id'] == $val['id']) $access = $val['access'];
	}
?>


			<div class="group-user-table">

				<?foreach($arResult['users'] as $user):?>
					<?if ($user['access'] != 0):?>
						
						<div class="group-user-row" id="group-user-row-<?=$user['id']?>">
							<div class="group-user-colum group-user-table-avatar-box">
								<img src="/templates/images/avatar-user/<?=$user['avatar'];?>" alt="avatar">
							</div>
							<div class="group-user-colum">
								<a href="/user/<?=$user['id']?>">
									<?=$user['first_name']." ".$user['last_name']?>
								</a>
							</div>
							<?if($access == 1):?>
								<div class="group-user-colum">
									<select class="change-user-access" data-user="<?=$user['id']?>" data-group="<?=$arResult['group']['id']?>">
										<option value="3" <?if($user['access'] == 3) echo "selected='selected'";?>>Сотрудник</option>
										<option value="2" <?if($user['access'] == 2) echo "selected='selected'";?>>Управляющий</option>
										<option value="1" <?if($user['access'] == 1) echo "selected='selected'";?>>Администратор</option>
									</select>
								</div>
							<?else:?>
								<div class="group-user-colum">
									<?
									if ($user['access'] == 1) echo 'Администратор';
									else if ($user['access'] == 2) echo 'Управляющий';
									else if ($user['access'] == 3) echo 'Сотрудник';
									?>
								</div>
							<?endif;?>
							<div class="group-user-colum">
								<a href="/massages/<?=$user['id']?>" class="group-user-table-massage-link" title="Перейти в чат">
									<i class="fa fa-comment-o" aria-hidden="true"></i>
								</a>
							</div>
							<div class="group-user-colum">
								<a href="/tasks/new/?executor_id=<?=$user['id']?>" class="group-user-table-task-link" title="Поставить задачу">
									<i class="fa fa-check-square-o" aria-hidden="true"></i>
								</a>
							</div>
							<?if($access == 1 && $user['access'] != 1):?>
								<div class="group-user-colum group-user-table-delete-box">
									<a href="#" title="Выгнать из группы" class="group-user-table-delete-user" onclick="return deleteUserForGroup(<?=$arResult['group']['id'].", ".$user['id'];?>)">
										<i class="fa fa-times" aria-hidden="true"></i>
									</a>
								</div>
							<?endif;?>
						</div>
					<?endif;?>
				<?endforeach;?>
				
			</div>