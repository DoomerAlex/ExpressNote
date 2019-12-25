<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$arResult['name_page'];?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/components/font_awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/templates/style.css">
	<link rel="stylesheet" type="text/css" href="/templates/pages/<?=$page;?>/style.css">
</head>
<body>

	<div class="wrapper">
		<div class="content">

			<? if (isset($_SESSION['user_id'])):?>
			<header class="header clearfix">
				<div class="header-name">
					<a href="" class="header-name-link">ExpressNote</a>
				</div>
				<div class="header-page-name">
					<hr>
					<span><?=$arResult['name_page']?></span>
				</div>
				<a href="/profile" class="header-avatar clearfix">
					<div class="header-avatar-name">
						<div class="header-avatar-name-first"><?=$_SESSION['user_first_name']?></div>
						<div class="header-avatar-name-last"><?=$_SESSION['user_last_name']?></div>
					</div>
					<?if (isset($_SESSION['user_avatar']) && $_SESSION['user_avatar'] != NULL ):?> 
						<img src="/templates/images/avatar-user/<?=$_SESSION['user_avatar']?>" class="header-avatar-img">
					<?else: ?>
						<img src="/templates/images/avatar-user/none.jpg" class="header-avatar-img">
					<?endif;?>
				</a>

			</header>

			<nav class="main-menu">
				<div class="main-menu-item">
					<a href="/tasks" class="main-menu-item-link <?if($_SERVER['REQUEST_URI'] == '/tasks') echo 'active'?>" title="Задачи">
						<?if(!empty($arResult['notices']['count_new_tasks']) && $arResult['notices']['count_new_tasks'] > 0):?>
							<div class="main-menu-item-num"><?=$arResult['notices']['count_new_tasks'];?></div>
						<?endif;?>
						<img src="/templates/images/menu-icon/menu1.png" class="main-menu-item-img">
						<div class="main-menu-item-text">Задачи</div>
					</a>
				</div>
				<div class="main-menu-item">
					<a href="/massanges" class="main-menu-item-link <?if($_SERVER['REQUEST_URI'] == '/massanges') echo 'active'?>" title="Сообщения">
						<div class="main-menu-item-num">12</div>
						<img src="/templates/images/menu-icon/menu2.png" class="main-menu-item-img">
						<div class="main-menu-item-text">Сообщения</div>
					</a>
				</div>
				<div class="main-menu-item">
					<a href="/reminders" class="main-menu-item-link <?if($_SERVER['REQUEST_URI'] == '/reminders') echo 'active'?>" title="Напоминания">
						<div class="main-menu-item-num">2</div>
						<img src="/templates/images/menu-icon/menu3.png" class="main-menu-item-img">
						<div class="main-menu-item-text">Напоминания</div>
					</a>
				</div>
				<div class="main-menu-item">
					<a href="/folders" class="main-menu-item-link <?if($_SERVER['REQUEST_URI'] == '/folders') echo 'active'?>" title="Заметки">
						<img src="/templates/images/menu-icon/menu4.png" class="main-menu-item-img">
						<div class="main-menu-item-text">Заметки</div>
					</a>
				</div>
				<div class="main-menu-item">
					<a href="/contacts" class="main-menu-item-link <?if($_SERVER['REQUEST_URI'] == '/contacts') echo 'active'?>" title="Контакты">
						<?if((!empty($arResult['notices']['count_new_friends']) && $arResult['notices']['count_new_friends'] > 0) || (!empty($arResult['notices']['count_new_groups']) && $arResult['notices']['count_new_groups'] > 0)):?>
							<div class="main-menu-item-num" id="main_menu_item_num_contacts">
								<?=$arResult['notices']['count_new_groups'] + $arResult['notices']['count_new_friends']?>
							</div>
						<?endif;?>
						<img src="/templates/images/menu-icon/menu5.png" class="main-menu-item-img">
						<div class="main-menu-item-text">Контакты</div>
					</a>
				</div>
			</nav>
			<? endif; ?>