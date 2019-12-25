<?
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/ContactModel.php');


class ContactsController extends FirstController{

	public function actionAllContacts(){
		$contacts = Contact::allContact($_SESSION['user_id']);
		$applications = Contact::getNewApplications($_SESSION['user_id']);
		$groups = Contact::getAllGroupsByUserId($_SESSION['user_id']);
		$arResult = array('name_page' => 'Контакты', 'contacts' => $contacts, 'applications' => $applications, 'groups' => $groups);
		FirstModel::openPage('contacts',$arResult);
		return true;
	}

	public function actionAjaxSearchUserForName(){
		if (isset($_SESSION['user_id'])){
			if ($_POST['name'] != '' && $_POST['name'] != ' '){
				$result = Contact::getUserForName($_POST['name']);
				require_once(ROOT. '/templates/pages/contacts/components/SearchAjax.php');
			}
			else echo 'false';
		}
		else echo "<script>location.replace('/tasks');</script>";
		return true;
	}

	public function actionNewGroupPage(){
		$contacts = Contact::allContact($_SESSION['user_id']);
		$arResult = array('name_page' => 'Создание группы', 'users' => $contacts);
		FirstModel::openPage('group/new', $arResult);
		return true;
	}

	public function actionAjaxAddNewGroup(){
		$group_id = Contact::AddNewGroup($_POST['group_name'], $_SESSION['user_id']);
		for($i=0; $i < count($_POST['user_id']); $i++){
			if ($_POST['user_check'][$i] == 'true'){
				Contact::AddNewUserInGroup($group_id, $_POST['user_id'][$i]);
			}
		}
		return true;
	}

	public function actionAjaxInviteInGroup(){
		if ($_POST['agree'] == 'true'){
			echo Contact::AddUserInGroup($_POST['group_id'], $_SESSION['user_id'], 3);
		}
		else if ($_POST['agree'] == 'false'){
			echo Contact::DeleteUserInGroup($_POST['group_id'], $_SESSION['user_id']);
		}
		else echo 'ошибка';
		return true;
	}

	// открыть страницу с группой
	public function actionGroupPage($group_id){
		$group = Contact::GetGroupById($group_id);
		if ($group != false){
			$users = Contact::GetUsersByGroupId($group_id);
			$arResult = array('name_page' => $group['name'], 'group' => $group, 'users' => $users);
			FirstModel::openPage('group', $arResult);
		}
		else{
			$arResult = array('name_page' => 'Ошибочка!');
			FirstModel::openPage('page404', $arResult);
		}
		return true;
	}

	// Ajax получение пользователей группы
	public function actionAjaxGetGroupUsers(){
		$group = Contact::GetGroupById($_POST['group_id']);
		if ($group != false){
			$users = Contact::GetUsersByGroupId($_POST['group_id']);
			$arResult = array('group' => $group, 'users' => $users);
			require_once(ROOT.'/templates/pages/group/AjaxUsers.php');
		}
		return true;
	}

	// смена прав доступа к группе
	public function actionAjaxChangeAccessForGroup(){
		echo Contact::ChangeAccessForGroup($_POST['user_id'], $_POST['access'], $_POST['group_id']);
		return true;
	}

	public function actionAjaxDeleteUserForGroup(){
		echo Contact::DeleteUserForGroup($_POST['user_id'], $_POST['group_id']);
		return true;
	}

	public function actionAjaxGetNewUserInGroup(){
		$users = Contact::getNewUsersForGroup($_POST['group_id'], $_SESSION['user_id']);
		$arResult = array('users' => $users, 'group_id' => $_POST['group_id']);
		require_once(ROOT.'/templates/pages/group/AjaxNewUsers.php');
		return true;
	}

	public function actionAjaxAddNewUserInGroup(){
		for($i=0; $i < count($_POST['user_id']); $i++){
			if ($_POST['user_check'][$i] == 'true'){
				Contact::AddNewUserInGroup($_POST['group_id'], $_POST['user_id'][$i]);
			}
		}
		$users = Contact::getNewUsersForGroup($_POST['group_id'], $_SESSION['user_id']);
		$arResult = array('users' => $users);
		require_once(ROOT.'/templates/pages/group/AjaxNewUsers.php');
		return true;
	}

}