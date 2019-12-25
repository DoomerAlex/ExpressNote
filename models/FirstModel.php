<?php

class FirstModel{

	// получает дату в нормальном виде
	public static function getDate($bad_date){ 
		$time = mb_substr($bad_date,-8,5,"UTF-8");
		$date = mb_substr($bad_date,8,2,"UTF-8").'.'.mb_substr($bad_date,5,2,"UTF-8").'.'.mb_substr($bad_date,2,2,"UTF-8");
		return $time.' '.$date;
	}

	// проверяет чекбоксы
	public static function checkbox_verify($_name) { 
		$result=0;
		if (isset($_REQUEST[$_name])) {
			if ($_REQUEST[$_name]=='on') $result=1;
		}
		return $result;
	}

	// аворизация
	public static function Authorization(){ 
		if (isset($_SESSION['user_id'])){ // если пользлватель был авторизован
			return true;
		}
		else if (isset($_POST['login']) && isset($_POST['password'])){

		}
		else { // если не было входа
			if ($_SERVER['REQUEST_URI'] != '/AjaxRegistration') 
				session_destroy();
			if ($_SERVER['REQUEST_URI'] != '/registration' && 
				$_SERVER['REQUEST_URI'] != '/AjaxRegistrationForm' &&
				$_SERVER['REQUEST_URI'] != '/AjaxCheckPhone' &&
				$_SERVER['REQUEST_URI'] != '/AjaxRegistration'){
				echo '<script>location.replace("/registration");</script>';
				exit;
			}
		}
	}

	// очистка номера телефона от лишних символов
	public static function clearPhoneForTrash($phone){
		$result = preg_replace('/\s/', '', $phone);
		$result = preg_replace('/\)/', '', $result);
		$result = preg_replace('/\(/', '', $result);
		return $result;
	}

	// уведомления для бокового меню
	public function checkNoticesByUserId($user_id){
		$pdo = DataBase::getConnectDb();
		// задачи
		$query = $pdo->prepare("SELECT count(id) FROM tasks WHERE executor_id = ? AND status = 1");
		$query->execute([$user_id]);
		$result['count_new_tasks'] = $query->fetchColumn();
		// заявки в группы
		$query = $pdo->prepare("SELECT count(group_id) FROM users_group WHERE user_id = ? AND access = 0");
		$query->execute([$user_id]);
		$result['count_new_groups'] = $query->fetchColumn();
		// заявки в друзья
		$query = $pdo->prepare("SELECT count(id) FROM friendlist_attestation WHERE user_id_to = ? AND attestation = 1");
		$query->execute([$user_id]);
		$result['count_new_friends'] = $query->fetchColumn();
		return $result;
	}


	
	// подключение шаблона страницы
	public static function openPage($page, $arResult){
		if (!empty($_SESSION['user_id'])){
			$notices = self::checkNoticesByUserId($_SESSION['user_id']);
			$arResult += ['notices' => $notices];
		}
		
		require_once(ROOT.'/templates/header.php');
		require_once(ROOT.'/templates/pages/'.$page.'/index.php');
		require_once(ROOT.'/templates/footer.php');
	}

	// данные о пользователе
	public function getUserById($user_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
		$query->execute([$user_id]);
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$result['id'] = $row->id;
			$result['first_name'] = $row->first_name;
			$result['last_name'] = $row->last_name;
			$result['avatar'] = $row->avatar;
			$result['email'] = $row->email;
			$result['phone'] = $row->phone;
			$result['reg_date'] = $row->reg_date;
		}
		return $result;
	}
}