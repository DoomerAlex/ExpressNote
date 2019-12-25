<?
return array(
	// регистрация
	'registration' => 'registration/OpenForm', // открытие формы для входа в учетку

	'AjaxRegistrationForm' => 'registration/AjaxOpenRegistrationForm', // открытие формы для регистрации
	'AjaxCheckEmail' => 'registration/AjaxCheckEmail', // проверка E-mail на уникальность при регистрации
	'AjaxCheckPhone' => 'registration/AjaxCheckPhone', // проверка телефона на уникальность при регистрации
	'AjaxLogin' => 'registration/AjaxLogin', // вход в личный кабинет
	'AjaxRegistration' => 'registration/AjaxRegistration', // регистрация пользователя
	'exit' => 'registration/Exit', // выход из аккаунта
	// задачи
	'tasks/new' => 'tasks/PageNewTask', // форма для новой задачи
	'task/([0-9]+)' => 'tasks/DetailTask/$1', // задача детально
	'task/edit/([0-9]+)' => 'tasks/EditPage/$1', // редактирование задачи
	'tasks' => 'tasks/AllTasks', // все задачи
	'AjaxAddTask' => 'tasks/AjaxAddTask', // добавление новой задачи
	'AjaxEditTask' => 'tasks/AjaxEditTask', // иземение задачи
	'AjaxDeleteTask' => 'tasks/AjaxDeleteTask', // удаление задачи
	'AjaxChangeStatusTask' => 'tasks/AjaxChangeStatusTask', // сменить статус задачи
	'AjaxReloadDetailTask' => 'tasks/AjaxReloadDetailTask', // перезагрузка страницы детальног просмотра задачи
	'AjaxFilterTasks' => 'tasks/AjaxFilterTasks', // изменение фильтра по задачам

	'AjaxCloseSubtask' => 'tasks/AjaxCloseSubtask', // закрывает подзадачу
	'AjaxOpenSubtask' => 'tasks/AjaxOpenSubtask', // возобновляет подзадачу

	'AjaxAddComment' => 'tasks/AjaxAddComment', // добавить комментарий
	'AjaxDeleteComment' => 'tasks/AjaxDeleteComment', // удалить коммент

	'calendar' => 'tasks/Calendar', // страница календаря
	// сообщения
	'massanges' => 'massange/AllMassanges', // все сообщения
	// заметки
	'folders' => 'folders/AllFolders', // все заметки
	// напоминания
	'reminders' => 'reminders/AllReminders', // все напомнинания
	// контакты
	'contacts' => 'contacts/AllContacts', // все контакты
	'AjaxSearchUserForName' => 'contacts/AjaxSearchUserForName', // поиск на странице контактов
	'user/([0-9]+)' => 'registration/ProfilePageUser/$1', // страница профиля
	'profile' => 'registration/ProfilePage', // страница профиля
	'AjaxAddNewContakt' => 'registration/AjaxAddNewContakt', //создание запроса для нового контакта
	'AjaxBackNewContakt' => 'registration/AjaxBackNewContakt', //отмена заявки нового контакта
	'AjaxCloseNewContakt' => 'registration/AjaxCloseNewContakt', //отклонение заявки нового контакта
	'AjaxNewContakt' => 'registration/AjaxNewContakt', //добавление нового контакта
	'AjaxDeleteContakt' => 'registration/AjaxDeleteContakt', //удаление контакта

	// группы
	'newGroup' => 'contacts/NewGroupPage', // страница для создания группы
	'group/([0-9]+)' => 'contacts/GroupPage/$1', // страница управления группой
	'AjaxAddNewGroup' => 'contacts/AjaxAddNewGroup', // создагние новой группы
	'AjaxInviteInGroup' => 'contacts/AjaxInviteInGroup', // принятие/отклонение приглашения в группу
	'AjaxGetGroupUsers' => 'contacts/AjaxGetGroupUsers', // Участники группы
	'AjaxGetGroupStatistics' => 'contacts/AjaxGetGroupStatistics', // статистика группы
	'AjaxGetGroupOptions' => 'contacts/AjaxGetGroupOptions', // настройки группы
	'AjaxChangeAccessForGroup' => 'contacts/AjaxChangeAccessForGroup', // смена пав доступа для к группе пользователя
	'AjaxDeleteUserForGroup' => 'contacts/AjaxDeleteUserForGroup', // удаление пользователя из группы
	'AjaxGetNewUserInGroup' => 'contacts/AjaxGetNewUserInGroup', // новые пользователи для группы
	'AjaxAddNewUserInGroup' => 'contacts/AjaxAddNewUserInGroup', // добавляет пользователей в группу

);