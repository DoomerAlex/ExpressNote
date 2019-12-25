<?php
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/RegistrationModel.php');

class RegistrationController extends FirstController{

	public function actionOpenForm(){ // открывает форму для входа
		if (!isset($_SESSION['user_id'])){
			$arResult = array('name_page' => 'вход в учетную запись');
			FirstModel::openPage('registration',$arResult);
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxLogin(){ // вход в личный кабинет
		$login = Registration::login();
		if ($login == true){
			echo 'true';
		}
		else {
			echo 'false';
		}
		return true;
	}

	public function actionExit(){ // выход из личного кабинета
		$result = Registration::exit();
		echo "<script>location.replace('/registration');</script>";
		return true;
	}

	public function actionAjaxOpenRegistrationForm(){ // открывает форму для регистрации
		require_once('templates/pages/registration/components/RegistrationForm.php');
		return true;
	}

	public function actionAjaxCheckEmail(){
		$result = Registration::checkEmail($_POST['email']);
		echo $result;
		return true;
	}
	
	public function actionAjaxCheckPhone(){
		$result = Registration::checkPhone($_POST['phone']);
		echo $result;
		return true;
	}

	public function actionAjaxRegistration(){
		$result = Registration::addUser($_POST['reg_first_name'], $_POST['reg_last_name'], $_POST['reg_phone'], $_POST['reg_email'], $_POST['reg_password']);
		echo $result;
		return true;
	}

	public function actionProfilePage(){
		if (isset($_SESSION['user_id'])){
			$data_profile = Registration::AllDataProfile($_SESSION['user_id']);
			$arResult = array('name_page' => 'Ваш профиль', 'data' => $data_profile);
			FirstModel::openPage('profile',$arResult);
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionProfilePageUser($id){
		if (isset($_SESSION['user_id'])){
			$data_profile = Registration::AllDataProfile($id);
			if ($_SESSION['user_id'] != $id) $friend = Registration::CheckFriend($_SESSION['user_id'], $id);
			else $friend = 'none';
			$arResult = array('name_page' => 'Профиль пользователя', 'data' => $data_profile, 'friend' => $friend, 'user_id' => $id);
			FirstModel::openPage('user',$arResult);
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxAddNewContakt(){
		if (isset($_SESSION['user_id'])){
			$result = Registration::AddNewContakt($_POST['id_contakt'], $_SESSION['user_id']);
			echo $result;
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxBackNewContakt(){
		if (isset($_SESSION['user_id'])){
			$result = Registration::BackNewContakt($_POST['id_contakt'], $_SESSION['user_id']);
			echo $result;
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxCloseNewContakt(){
		if (isset($_SESSION['user_id'])){
			$result = Registration::BackNewContakt($_SESSION['user_id'], $_POST['id_contakt']);
			/* отправка уведомления, что заявку отклонили */
			echo $result;
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxNewContakt(){
		if (isset($_SESSION['user_id'])){
			$result = Registration::NewContakt($_SESSION['user_id'], $_POST['id_contakt']);
			/* отправка уведомления, что заявку приняли */
			echo $result;
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionAjaxDeleteContakt(){
		if (isset($_SESSION['user_id'])){
			$result = Registration::DeleteContakt($_SESSION['user_id'], $_POST['id_contakt']);
			/* отправка уведомления, что тебя удалили */
			echo $result;
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}
}