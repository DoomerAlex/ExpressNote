<!-- Боковое меню -->
		<div class="task-form2">
			<div class="task-deadline-box clearfix">
				<div class="task-deadline-day" style="color: <?if($arResult['task']['deadline'] < time()) echo 'red;'; else echo '#6eb690;';?>">
					<? $deadline = getdate($arResult['task']['deadline']);?>
					<?=date('d', $arResult['task']['deadline']);?>
				</div>
				<div class="task-deadline-month-box">
					<div class="task-deadline-month"><?=$deadline['month'];?></div>
					<div class="task-deadline-time"><?=date('H:i', $arResult['task']['deadline']);?></div>
				</div>
			</div>
			<div class="task-date-time-box">
				Поставлена: <?= date('d.m.Y H:i', $arResult['task']['date_create']);?>
			</div>
			<div class="task-executor-box">
				<div>Исполняющий:</div>
				<a class="task-executor-select-box" href="/user/<?=$arResult['task']['executor']['id'];?>">
					<div class="task-executor-img-box">
						<img src="/templates/images/avatar-user/<?=$arResult['task']['executor']['avatar'];?>" id="form_executor_img" alt='image'>
					</div>
					<div class="task-executor-name-box"><?=$arResult['task']['executor']['first_name'].' '.$arResult['task']['executor']['last_name'];?></div>
				</a>
			</div>
			<div class="task-executor-box">
				<div>Постановщик:</div>
				<a class="task-executor-select-box" href="/user/<?=$arResult['task']['director']['id'];?>">
					<div class="task-executor-img-box">
						<img src="/templates/images/avatar-user/<?=$arResult['task']['director']['avatar'];?>" id="form_executor_img" alt='image'>
					</div>
					<div class="task-executor-name-box"><?=$arResult['task']['director']['first_name'].' '.$arResult['task']['director']['last_name'];?></div>
				</a>
			</div>
			
			<div>
				<span>Статус: </span><span><?=$arResult['task']['ru_status'];?></span>
			</div>

			<?if($arResult['task']['control'] == true): ?>
				<div class="task-control-box">
					<i class="fa fa-exclamation" aria-hidden="true"></i> <span>Задача контролируется</span>
				</div>
			<?endif;?>
		</div>
		<!-- Конец бокового меню -->
	
		<div class="task-form1">
			<div class="task-title">
				<?=$arResult['task']['title'];?>
			</div>
			<div class="task-text">
				<?=$arResult['task']['text'];?>
			</div>
			<div class="new-task-form-all-subtasks-box">
				<?foreach($arResult['task']['subtasks'] as $val):?>
					<div class="task-subtask-box">
						<input type="checkbox" id="subtask_<?=$val['id']?>" class="checkbox-subtask" <?if($val['status'] == false) echo 'checked';?> data-val="<?=$val['id']?>">
						<label for="subtask_<?=$val['id']?>" class="checkbox-label-subtask"><?=$val['text'];?></label>
					</div>
				<?endforeach;?>
			</div>
			<div class="task-buttons-box clearfix">
				
				

				<?if($arResult['task']['status'] == 'in_work' || ($arResult['task']['status'] == 'wait_control' && $_SESSION['user_id'] == $arResult['task']['director']['id'])):?>
					<button class="task-buttons-close-task" onclick="cahngeStatusTask(<?=$arResult['task']['id']?>,3)">Завершить</button>
				<?endif;?>

				<?if($arResult['task']['status'] == 'canceled' || $arResult['task']['status'] == 'close' || $arResult['task']['status'] == 'wait_control'):?>
					<button class="task-buttons-close-task" onclick="cahngeStatusTask(<?=$arResult['task']['id']?>,1)">Возобновить</button>
				<?endif;?>

				<?if($arResult['task']['status'] == 'new' || $arResult['task']['status'] == 'defer'):?>
					<button class="task-buttons-close-task" onclick="cahngeStatusTask(<?=$arResult['task']['id']?>,2)">Приступить</button>
				<?endif;?>

				<?if($arResult['task']['status'] == 'new' || $arResult['task']['status'] == 'in_work'):?>
					<button class="task-buttons-wait-task" onclick="cahngeStatusTask(<?=$arResult['task']['id']?>,6)">Отменить</button>
				<?endif;?>

				<?if($arResult['task']['status'] == 'new' || $arResult['task']['status'] == 'in_work'):?>
					<button class="task-buttons-wait-task" onclick="cahngeStatusTask(<?=$arResult['task']['id']?>,4)">Отложить</button>
				<?endif;?>

				<!-- new -> новая
				2 -> in_work -> в работе
				6 -> canceled -> отменена
				4 -> defer -> отложена
				3 -> close -> завершена
				5 -> wait_control -> ожидает согласования -->


				<?if($arResult['task']['director']['id'] == $_SESSION['user_id']):?>
					<a href="/task/edit/<?=$arResult['task']['id']?>" class="task-buttons-edit-task">Редактировать</a>
				<?endif;?>
				<a href="/tasks/" class="task-buttons-back">Назад</a>
			</div>

			
				<div class="task-comments-history-box clearfix">
					<button class="task-comments-history-button active" id="butt-comments">Комментарии</button>
					<button class="task-comments-history-button" id="butt-history">История</button>
				</div>
				
			<div class="task-comments-section">
				<div class="task-comments-block">
				<?foreach($arResult['comments'] as $value):?>
					<div class="task-comment-item clearfix" id="task-comment-item-<?=$value['id'];?>">
						<div class="task-comment-item-left">
							<a href="/user/<?=$value['user']['id']?>">
								<img src="/templates/images/avatar-user/<?=$value['user']['avatar']?>" alt="image">
							</a>
						</div>
						<div class="task-comment-item-right">
							<div class="task-comment-item-name-box">
								<a href="/user/<?=$value['user']['id']?>" class="task-comment-item-name">
									<?=$value['user']['first_name'].' '.$value['user']['last_name'];?>
								</a>

								<? if($_SESSION['user_id'] == $value['user']['id']):?>
									<a href="#" class="task-comment-item-close" data-val="<?=$value['id']?>">
										<i class="fa fa-times" aria-hidden="true"></i>
									</a>
								<?endif;?>

								<span class="task-comment-item-date">
									<?=date('H:i d F',$value['date_create']);?>
								</span>
								
							</div>
							<div><?=$value['text'];?></div>
						</div>
					</div>
				<?endforeach?>	
				</div>

				<hr class="task-comments-hr">

				<div class="task-comments-new-box">
					<form id="task-add-comment-form" method="post">
						<input type="hidden" name="task_id" value="<?=$arResult['task']['id']?>">
						<textarea placeholder="Ваш комментарий" class="task-add-comments-form-text" maxlength="500" required name="text"></textarea>
						<div class="task-add-comments-form-num-text">Осталось <span id="task-comment-form-num-words">500</span> символов</div>
						<input type="submit" name="submit-comment" value="Добавить комментарий" class="task-add-comments-form-submit">
					</form>
				</div>
			</div>

			<div class="task-history-section" style="display: none;">
				<table class="task-history-table">

					<?foreach($arResult['history'] as $value):?>
						<tr>
							<td class="task-history-table-name">
								<a href="/user/<?=$value['user_id']?>">
									<?=$value['user_first_name'].' '.$value['user_last_name'];?>		
								</a>
							</td>
							<td class="task-history-table-row">
								<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
							</td>
							<td class="task-history-table-event">
								<?=$value['event']?>
							</td>
							<td class="task-history-table-date">
								<?=date('d.m.Y H:i', $value['datetime']);?>
							</td>
						</tr>
					<?endforeach;?>
					
				</table>
			</div>
			
			

		</div>