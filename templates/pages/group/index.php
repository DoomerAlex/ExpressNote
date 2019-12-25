<?
	foreach ($arResult['users'] as $val){
		if($_SESSION['user_id'] == $val['id']) $access = $val['access'];
	}
?>


<div class="group-page">
	
	<div>
		<div class="group-top-menu-box">
			<ul class="group-top-menu clearfix">
				<li>
					<a href="/AjaxGetGroupUsers" data-group="<?=$arResult['group']['id']?>" class="group-top-menu-link active" title="Участники">
						<i class="fa fa-users" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="/AjaxGetGroupStatistics" data-group="<?=$arResult['group']['id']?>" class="group-top-menu-link" title="Статистика">
						<i class="fa fa-line-chart" aria-hidden="true"></i>
					</a>
				</li>
				<?if($access == 1 || $access == 2):?>
					<li>
						<a href="/AjaxGetTasksForGroup" data-group="<?=$arResult['group']['id']?>" class="group-top-menu-link" title="Задачи в группе">
							<i class="fa fa-exclamation" aria-hidden="true"></i>
						</a>
					</li>
				<?endif;?>
				<?if($access == 1):?>
					<li>
						<a href="/AjaxGetNewUserInGroup" data-group="<?=$arResult['group']['id']?>" class="group-top-menu-link" title="Пригласить в группу">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a href="/AjaxGetGroupOptions" data-group="<?=$arResult['group']['id']?>" class="group-top-menu-link" title="Настройки">
							<i class="fa fa-cog" aria-hidden="true"></i>
						</a>
					</li>
				<?endif;?>
			</ul>
		</div>

		<div id="group-ajax-content">

			<?require_once('AjaxUsers.php');?>
			
		</div>


	</div>

	

</div>


