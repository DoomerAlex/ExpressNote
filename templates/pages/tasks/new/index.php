<div class="new-task-page">
	<form class="clearfix" method="post" id="add-new-task-form">
		<div class="new-task-form1">
			<input type="text" name="new-task-title" placeholder="Заголовок" class="new-task-form-title">
			<textarea placeholder="Текст задачи..." class="new-task-form-textarea" name='new-task-text'></textarea>
			<div class="new-task-form-all-subtasks-box">
				<div id="subtask-1" class="new-task-form-subtask-box">
					<a href="#" onclick="return addSubtask(1)">
						<i class="fa fa-plus" aria-hidden="true"></i> Добавить подзадачу
					</a>
				</div>
				
			</div>
			<div class="new-task-form-buttons-box clearfix">
				<input type='submit' class="new-task-form-buttons-add-task" value='Поставить задачу'>
				<a href="#" class="new-task-form-buttons-back">Назад</a>
			</div>
		</div>


		<div class="new-task-form2">
			<div class="checkbox-type1-box">
				<input type="checkbox" name="new-task-deadline-check" id="deadline-input" class="checkbox-type1" checked="ture">
				<label for="deadline-input" class="checkbox-label-type1" id="deadline-input-label">Указать карайний срок</label>
			</div>
			<div class='calendar'>
				<input type="hidden" name="new-task-deadline-day" val="" id="form_hidden_day">
				<input type="hidden" name="new-task-deadline-month" val="" id="form_hidden_month">
				<input type="hidden" name="new-task-deadline-year" val="" id="form_hidden_year">
				<div class='calendar-month-box'>
					<a href="#" class='calendar-month-pred'><i class="fa fa-angle-left" aria-hidden="true"></i></a>
					<span class='calendar-month'><!--Месяц--></span>
					<span class='calendar-year'><!--Год--></span>
					<a href="#" class='calendar-month-next'><i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
				<div id="calendar_change_body">

				<!-- Calendar here -->

				</div>
				<div class="calendar-time-box">
					<div class="calendar-time-box1" id="calendar-time-block1">
						Время:
						<input type="text" name="new-task-deadline-hour" class="calendar-time-input1" maxlength="2" value='19'>
						<span> : </span>
						<input type="text" name="new-task-deadline-minute" class="calendar-time-input2" maxlength="2" value='00'>
						<a href="#" class="calendar-time-close-button"><i class="fa fa-times" aria-hidden="true"></i></a>
					</div>
					<div class="calendar-time-box2" id="calendar-time-block2">
						<a href="#" class="calendar-edit-time">Редактировать время</a>
					</div>
				</div>
			</div>
			<div>
				<div class="new-task-executor-box">
					<div>Исполняющий:</div>
					<div class="new-task-form-executor-select-box">
						
							<input type="hidden" name="new-task-executor" value="<?if(!empty($arResult['executor']['id'])) echo $arResult['executor']['id']; else echo $_SESSION['user_id']?>" id="form_val_executor">
						
							
						
						
						<div class="new-task-form-executor-img-box">
							<img src="/templates/images/avatar-user/<?if(!empty($arResult['executor']['id'])) echo $arResult['executor']['avatar']; else echo $_SESSION['user_avatar'];?>" id="form_executor_img" alt='image'>
						</div>
						<div class="new-task-form-executor-name-box" id="form_executor_name">
							<?if(!empty($arResult['executor']['id'])) echo $arResult['executor']['first_name']." ".$arResult['executor']['last_name']; else echo $_SESSION['user_first_name'].' '.$_SESSION['user_last_name'];?>
						</div>
					</div>
					<div class="new-task-form-executor-select-ul-box">
						<ul class="new-task-form-executor-select-ul">

							<li>
								<a href="#" class="clearfix" onclick="return ChangeSelectExecutor(<?=$_SESSION['user_id']?>)" id="form_select_value_<?=$_SESSION['user_id']?>">
									<div class="new-task-form-executor-img-box">
										<img src="/templates/images/avatar-user/<?=$_SESSION['user_avatar']?>" alt="image">
									</div>
									<div class="new-task-form-executor-name-box"><?=$_SESSION['user_first_name']." ".$_SESSION['user_last_name']?></div>
								</a>
							</li>

							<?foreach ($arResult['contacts'] as $val): ?>
								<li>
									<a href="#" class="clearfix" onclick="return ChangeSelectExecutor(<?=$val['id']?>)" id="form_select_value_<?=$val['id']?>">
										<div class="new-task-form-executor-img-box">
											<img src="/templates/images/avatar-user/<?=$val['avatar']?>" alt="image">
										</div>
										<div class="new-task-form-executor-name-box"><?=$val['first_name']." ".$val['last_name']?></div>
									</a>
								</li>
							<?endforeach;?>

						</ul>
					</div>

				</div>
				
				<div class="new-task-control-checkbox-box">
					<input type="checkbox" name="new-task-control" id="new-task-following" class="checkbox-type1">
					<label for="new-task-following" class="checkbox-label-type1" id="deadline-input-label">Контролировать</label>
				</div>

			</div>
		</div>
	</form>
</div>