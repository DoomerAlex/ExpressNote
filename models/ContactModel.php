<?php

class Contact extends FirstModel{

	// все контакты пользователя
	public function allContact($id){ 
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT user_id_first, user_id_last"
			." FROM friendlist"
			." WHERE user_id_first = ? OR user_id_last = ?");
		$query->execute([$id, $id]);
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			if ($row->user_id_first != $id){
				$user = Contact::previewContacts($row->user_id_first);
				array_push($result, $user);
			}
			else if ($row->user_id_last != $id){
				$user = Contact::previewContacts($row->user_id_last);
				array_push($result, $user);
			}
		}
		return $result;
	}


	// получает инфу для карточек контактов
	public function previewContacts($id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT id, first_name, last_name, avatar"
			." FROM users"
			." WHERE id = ?");
		$query->execute([$id]);
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$result['id'] = $row->id;
			$result['first_name'] = $row->first_name;
			$result['last_name'] = $row->last_name;
			$result['avatar'] = $row->avatar;
		}
		return $result;
	}

	// заявки в контакты
	public function getNewApplications($id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT user_id_from"
			." FROM friendlist_attestation"
			." WHERE user_id_to = ?");
		$query->execute([$id]);
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$user = Contact::previewContacts($row->user_id_from);
			array_push($result, $user);
		}
		return $result;
	}

	//поиск по имени в контактах
	public function getUserForName($name){
		$pdo = DataBase::getConnectDb();
		$name_like = "%$name%";
		$query = $pdo->prepare(
			" SELECT id, first_name, last_name, avatar"
			." FROM users"
			." WHERE first_name LIKE ? OR last_name LIKE ?");
		$query->execute([$name_like, $name_like]);
		$user = array();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$user['id'] = $row->id;
			$user['first_name'] = $row->first_name;
			$user['last_name'] = $row->last_name;
			$user['avatar'] = $row->avatar;
			array_push($result, $user);
		}
		return $result;
	}

	// создание новой группы
	public function AddNewGroup($name, $director_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			if(!empty($_POST['parent_group'])) $parent = $_POST['parent_group'];
			else $parent = null;
			$query = $pdo->prepare("INSERT INTO groups (name, director_id, parent_id) VALUES(?, ?, ?)");
			$query->execute([$name, $director_id, $parent]);
			$query = $pdo->prepare("SELECT id FROM groups WHERE director_id = ? AND name = ? ORDER BY id DESC LIMIT 1");
			$query->execute([$director_id, $name]);
			$group_id = $query->fetchColumn();
			$query = $pdo->prepare("INSERT INTO users_group (group_id, user_id, access) VALUES(?, ?, ?)");
			$query->execute([$group_id, $director_id, 1]);
			$pdo->commit();
			return $group_id;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// Приглашение пользователя в группу
	public function AddNewUserInGroup($group_id, $user_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("INSERT INTO users_group (group_id, user_id, access) VALUES(?, ?, ?)");

			$query->execute([$group_id, $user_id, 0]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// получаем все группы в которых сотоит пользователь
	public function getAllGroupsByUserId($user_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT  G.*, UG.access"
			." FROM users_group UG, groups G "
			." WHERE UG.group_id = G.id AND UG.user_id = ?");
		$query->execute([$user_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['name'] = $row->name;
			$val['date_create'] = $row->date_create;
			$val['parent_id'] = $row->parent_id;
			$val['access'] = $row->access;
			$val['director'] = Contact::previewContacts($row->director_id);
			array_push($result, $val);
		}
		return $result;
	}

	// добавление пользователя в группу
	public function AddUserInGroup($group_id, $user_id, $access){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("UPDATE users_group SET access = ? WHERE user_id = ? AND group_id = ?");

			$query->execute([$access, $user_id, $group_id]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
		return true;
	}
	// удаление пользоваятеля и группы
	public function DeleteUserInGroup($group_id, $user_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("DELETE FROM users_group WHERE group_id = ? AND user_id = ?");

			$query->execute([$group_id, $user_id]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
		return true;
	}

	// получение данных о группе
	public function GetGroupById($group_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT access FROM users_group WHERE group_id = ? AND user_id = ?");
		$query->execute([$group_id, $_SESSION['user_id']]);
		$access = $query->fetchColumn();
		if ($access > 0){ // проверка доступа к группе
			$query = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
			$query->execute([$group_id]);
			$result = array();
			
			while($row = $query->fetch(PDO::FETCH_LAZY)){
				$result['id'] = $row->id;
				$result['name'] = $row->name;
				$result['date_create'] = $row->date_create;
				$result['director'] = FirstModel::getUserById($row->director_id);
				$result['parent_group'] = $row->parent_id;
			}
		}
		else{ // если доступа нет
			$result = false;
		}
		return $result;
	}

	// все люди состоящие в группе
	public function GetUsersByGroupId($group_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT UG.access, U.id, U.first_name, U.last_name, U.avatar FROM users_group UG, users U WHERE UG.group_id = ? AND UG.user_id = U.id ORDER BY UG.access");
		$query->execute([$group_id]);
		$result = array();
		$val = array();
		while($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['first_name'] = $row->first_name;
			$val['last_name'] = $row->last_name;
			$val['avatar'] = $row->avatar;
			$val['access'] = $row->access;
			array_push($result, $val);
		}
		return $result;
	}


	// изменение прав доступа для группы
	public function ChangeAccessForGroup($user_id, $access, $group_id){
		$pdo = DataBase::getConnectDb();
		$user_access = self::ChechUserAccessInGroup($_SESSION['user_id'], $group_id);
		if ($user_access == 1){
			try{
				$pdo->beginTransaction();
				$query = $pdo->prepare("UPDATE users_group SET access = ? WHERE user_id = ? AND group_id = ?");
				$query->execute([$access, $user_id, $group_id]);
				$pdo->commit();
			}
			catch (Exception $e){
				$pdo->rollBack();
				echo "Ошибка: " . $e->getMessage();
				return false;
			}
		}
		else return 'Недостаточно прав для изменения';
		return true;
	}

	// удаление пользователя из группы
	public function DeleteUserForGroup($user_id, $group_id){
		$pdo = DataBase::getConnectDb();
		$user_access = self::ChechUserAccessInGroup($_SESSION['user_id'], $group_id);
		if ($user_access == 1){
			try{
				$pdo->beginTransaction();
				$query = $pdo->prepare("DELETE FROM users_group WHERE group_id = ? AND user_id = ?");
				$query->execute([$group_id, $user_id]);

				$pdo->commit();
			}
			catch (Exception $e){
				$pdo->rollBack();
				echo "Ошибка: " . $e->getMessage();
				return false;
			}
		}
		else return 'Недостаточно прав для изменения';
		return true;
	}

	// проверить парава доступа для одного пользователя в группе
	public function ChechUserAccessInGroup($user_id, $group_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT access FROM users_group WHERE user_id = ? AND group_id = ?");
		$query->execute([$user_id, $group_id]);
		$result = $query->fetchColumn();
		return $result;
	}

	// новые пользователи для добавления в группу
	public function getNewUsersForGroup($group_id, $user_id){
		$pdo = DataBase::getConnectDb();
		$users = self::allContact($user_id);
		$users_in_group = self::GetUsersByGroupId($group_id);
		foreach($users as $key => $value){
			foreach ($users_in_group as $value2){
				if ($value['id'] == $value2['id']) unset($users[$key]); 
			}
		}
		return $users;
	}
}