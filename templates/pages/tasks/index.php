<div class="task-page clearfix">
	<div class="task-page-filter-box">
		<a href="/tasks/new" class="task-add-task-button">
			<i class="fa fa-plus" aria-hidden="true"></i>
			Поставить задачу
		</a>
		
		<hr class="task-filters-hr">
		<h2 class="task-filters-h2">Фильтры</h2>

		<div class="task-filter-select-type-box select-box">
			<input type="hidden" name="status-taks" value="active" id="filter-status-task" class="js-filter-task-chnge">
			<div class="task-filter-select-type-selected">
				<span id="filter-status-task-val">Активные</span>
				<i class="fa fa-angle-down" aria-hidden="true"></i>
			</div>
			<ul id="filter-status-task-select">
				<li><span data-val="active" class="select-choise" data-id="filter-status-task">Активные</span></li>
				<li><span data-val="new" class="select-choise" data-id="filter-status-task">Новые</span></li>
				<li><span data-val="wait" class="select-choise" data-id="filter-status-task">Отложенные</span></li>
				<li><span data-val="wait_control" class="select-choise" data-id="filter-status-task">Ждут проверки</span></li>
				<li><span data-val="close" class="select-choise" data-id="filter-status-task">Завершенные</span></li>
				<li><span data-val="all" class="select-choise" data-id="filter-status-task">Все</span></li>
			</ul>
		</div>

		<div class="checkbox-type1-box">
			<input type="checkbox" name="new-task-deadline-check" id="deadline-filter" class="checkbox-type1 js-filter-task-chnge">
			<label for="deadline-filter" class="checkbox-label-type1" id="deadline-input-label">Просроченные</label>
		</div>

		<div class="checkbox-type1-box">
			<input type="checkbox" name="new-task-control-check" id="control-filter" class="checkbox-type1 js-filter-task-chnge">
			<label for="control-filter" class="checkbox-label-type1" id="deadline-input-label">Контролируются</label>
		</div>

		


	</div>
	<div class="task-page-content-box">
		<div>
			<input type="hidden" name="executor-filter" value="to_me" id="executor-filter">
			<ul class="task-filter-top clearfix">
				<li>
					<span class="task-filter-top-button active" data-val="to_me">Мои задачи</span>
				</li>
				<li>
					<span class="task-filter-top-button" data-val="from_me">Задачи от меня</span>
				</li>
			</ul>
		</div>
		<div class="task-table-box">
			<table class="task-table">
				<tr>
					<th>Задача</th>
					<th>Крайний срок</th>
					<th></th>
				</tr>
				<?foreach ($arResult['tasks'] as $value):?>

					<tr>
						<td class="task-table-title">
							<a href="/task/<?=$value['id']?>" class="task-table-task-title-link
							<?
								if($value['status'] == 'close') echo ' close';
								else if ($value['status'] == 'wait_control') echo ' wait-control';
								else if ($value['status'] == 'canceled') echo ' canceled';
								else if ($value['deadline'] > 0 && $value['deadline_val'] < time()) echo ' old';
								else if ($value['status'] == 'defer') echo ' defer';
								else if ($value['status'] == 'new') echo ' new';
								else if ($value['status'] == 'in_work') echo ' in-work';
							?>
							"><?=$value['title']?></a>
						</td>
						<td class="task-table-deadline"><?=$value['deadline']?></td>
						<td class="task-table-menu"><i class="fa fa-bars" aria-hidden="true"></i></td>
					</tr>
					

				<?endforeach;?>
			</table>	
		</div>
	</div>
	
</div>