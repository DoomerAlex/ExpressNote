<div class="contact-page clearfix">
	
		<div class="contact-filter-box">
			<form class="contact-search-form" id="contact-search-form" action=''>
				<input type="text" name="name" placeholder="Поиск" class="contact-input-search">
				<a href="#" class="contact-search-link-submit" title="Поиск">
					<i class="fa fa-search" aria-hidden="true"></i>
				</a>
			</form>
			<ul class="contact-menu-list">

				<li>
					<a href="#" class="contact-menu-list-group clearfix">
						<span>Мои группы</span>
						<span class="contact-menu-list-group-arrow active"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
					</a>
					<ul class="contact-list-group">
						<?foreach($arResult['groups'] as $val):?>
							<?if($val['access'] != 0):?>
								<li><a href="/group/<?=$val['id']?>"><?=$val['name']?></a></li>
							<?else:?>
								<?
								if (!empty($count_invite_group)) $count_invite_group++;
								else $count_invite_group = 1;
								?>
							<?endif;?>
						<?endforeach;?>						
					</ul>
				</li>
				
				<?if(!empty($count_invite_group)):?>
					<li id="contact-menu-list-new-group">
						<a href="#" class="contact-menu-list-new-group">
							<span>Приглашения в группу</span>
							<span class="contact-menu-list-new-count-group"><?=$count_invite_group;?></span>
						</a>
					</li>
				<?endif;?>

				<li>
					<a href="/newGroup">Создать группу</a>
				</li>
				
			</ul>
		</div>
		<div class="contact-display">
	
			<?if (count($arResult['applications']) != 0):?>
				<h2 class="contact-new-title">Новые заявки</h2>
				<div class="contacts-new-box clearfix">
					<?foreach ($arResult['applications'] as $value):?>
						<div class="contact-item" id="contact-item-new-<?=$value['id']?>">
							<a href="/user/<?=$value['id']?>">
								<div>
									<div class="contact-item-img-box">
										<?if ($value['avatar'] != ''):?>
											<img src="/templates/images/avatar-user/<?=$value['avatar']?>" width="100" height="100">
										<?else:?>
											<img src="/templates/images/avatar-user/none.jpg" width="100" height="100">
										<?endif;?>
									</div>

									<div class="contact-item-name">
										<?=$value['first_name']." ".$value['last_name'];?>
									</div>
								</div>
							</a>
							<div class="contact-item-buttons-box clearfix">
								<a href="#" class="contact-button-yes" onclick="AddContact(<?=$value['id']?>)"><i class="fa fa-check" aria-hidden="true"></i></a>
								<a href="#" class="contact-button-no" onclick="CancelContact(<?=$value['id']?>)"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div>
						</div>
					<?endforeach;?>
				</div>
			<?endif;?>


			<div class="contact-user-box clearfix">
				<?foreach ($arResult['contacts'] as $value):?>
					<a class="contact-item" href="/user/<?=$value['id']?>">
						<div class="contact-item-img-box">
							<?if ($value['avatar'] != ''):?>
								<img src="/templates/images/avatar-user/<?=$value['avatar']?>" width="100" height="100">
							<?else:?>
								<img src="/templates/images/avatar-user/none.jpg" width="100" height="100">
							<?endif;?>
						</div>
						<div class="contact-item-name">
							<?=$value['first_name']." ".$value['last_name'];?>
						</div>
					</a>
				<?endforeach;?>


			</div>
		</div>

		
	
</div>


<div class="popup-form-invite">
	<div class="new-group-invite-close-box"></div>
	<div class="new-group-invite-box">
		<button class="new-group-invite-close"><i class="fa fa-times" aria-hidden="true"></i></button>
		<h2 class="popup-form-h2">Приглашения в группу:</h2>
		<div class="popup-form">

			<?foreach($arResult['groups'] as $value):?>
				<?if($value['access'] == 0):?>
					<div class="popup-form-row" id="invite_<?=$value['id']?>">
						<div class="popup-form-column">
							<img src="/templates/images/avatar-user/<?=$value['director']['avatar']?>" alt="">
						</div>
						<div class="popup-form-column">
							<?=$value['director']['first_name'].' '.$value['director']['last_name'];?>
						</div>
						<div class="popup-form-column">
							приглашает в
						</div>
						<div class="popup-form-column popup-form-column-name">
							<?=$value['name'];?>
						</div>
						<div class="popup-form-column">
							<a href="#" class="popup-form-agree-button" onclick="return agreeGroup(<?=$value['id']?>)">
								<i class="fa fa-check" aria-hidden="true"></i>
							</a>
						</div>
						<div class="popup-form-column">
							<a href="#" class="popup-form-disagree-button" onclick="return disagreeGroup(<?=$value['id']?>)">
								<i class="fa fa-times" aria-hidden="true"></i>
							</a>
						</div>
					</div>
				<?endif;?>
			<?endforeach;?>

		</div>
	</div>
</div>