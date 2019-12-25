<?php

class Registration extends FirstModel{

	public function login(){	// вход в учетную запись
		if(isset($_POST['login']) && isset($_POST['password'])){
			$pdo = DataBase::getConnectDb();

			if (preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-0-9A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $_POST['login'])){
				$query = $pdo->prepare(
				" SELECT id, first_name, last_name, email, avatar"
				." FROM users"
				." WHERE email = ?"
				." AND password = ?");
			}
			else {
				$query = $pdo->prepare(
				" SELECT id, first_name, last_name, email, phone, avatar"
				." FROM users"
				." WHERE phone = ?"
				." AND password = ?");
			}
			$query->execute(array($_POST['login'], $_POST['password']));
			while ($row = $query->fetch(PDO::FETCH_LAZY)){
				$_SESSION['user_id'] = $row->id;
				$_SESSION['user_first_name'] = $row->first_name;
				$_SESSION['user_last_name'] = $row->last_name;
				$_SESSION['user_email'] = $row->email; 
				$_SESSION['user_avatar'] = $row->avatar; 
			}
			if (isset($_SESSION['user_id'])) return true;
			else return false;
		}
		else return false;
	}

	public function addUser($first_name, $last_name, $phone, $email, $password){ // добавление нового пользователя
		$pdo = DataBase::getConnectDb();
		$phone = FirstModel::clearPhoneForTrash($phone);
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare(
				"INSERT INTO users (first_name, last_name, password, phone, reg_date, email)"
				." VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP, ?)");
			$query->execute([$first_name, $last_name, $password, $phone, $email]);
			$query = $pdo->prepare(
				" SELECT id"
				." FROM users"
				." WHERE email = ?");
			$query->execute([$email]);
			$_SESSION['user_id'] = $query->fetchColumn();
			$_SESSION['user_first_name'] = $first_name;
			$_SESSION['user_last_name'] = $last_name;
			$_SESSION['user_email'] = $email;
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	public function checkEmail($email){ // проверяет email на уникальность 
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT COUNT(email)"
			." FROM users"
			." WHERE email = ?");
		$query->execute([$email]);
		$result = $query->fetchColumn();
		if ($result == 0) return true;
		else return false;
	}

	public function checkPhone($phone){ // проверяет телефон на уникальность
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT COUNT(phone)"
			." FROM users"
			." WHERE phone = ?");
		$query->execute([$phone]);
		$result = $query->fetchColumn();
		if ($result == 0) return true;
		else return false;
	}

	// выход из учетной записи
	public function exit(){ 
		$_SESSION = array();
		session_destroy();
		return true;
	}

	// вся информация о профиле
	public function AllDataProfile($id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			" SELECT *"
			." FROM users"
			." WHERE id = ?");
		$query->execute([$id]);
		$result=array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$result['id'] = $row->id;
			$result['first_name'] = $row->first_name;
			$result['last_name'] = $row->last_name;
			$result['email'] = $row->email;
			$result['phone'] = $row->phone;
			$result['avatar'] = $row->avatar;
			$result['reg_date'] = $row->reg_date;
		}
		return $result;
	}


	// проверка на френдлист
	public function CheckFriend($id_from, $id_to){
		$pdo = DataBase::getConnectDb();
		// проверяет, есть ли пользователь уже в френдлисте
		$query = $pdo->prepare(
			" SELECT COUNT(user_id_first)"
			." FROM friendlist"
			." WHERE (user_id_last = ? AND user_id_first = ?) OR (user_id_last = ? AND user_id_first = ?)");
		$query->execute([$id_to, $id_from, $id_from, $id_to]);
		$result = $query->fetchColumn();
		if ($result > 0){
			return 'true';
		}
		else{
			// может ты уже подавал заявку?
			$query = $pdo->prepare(
				" SELECT attestation"
				." FROM friendlist_attestation"
				." WHERE user_id_to = ? AND user_id_from = ?");
			$query->execute([$id_to, $id_from]);
			if ($query->fetchColumn() == true){
				return 'wait';
			}
			else{
				// а может тебе подавали заявку?
				$query = $pdo->prepare(
					" SELECT attestation"
					." FROM friendlist_attestation"
					." WHERE user_id_to = ? AND user_id_from = ?");
				$query->execute([$id_from, $id_to]);
				if ($query->fetchColumn() == true){
					return 'choise';
				}
				else{
					return 'false';
				}
			}
		}
	}

	// запрос на новый контакт
	public function AddNewContakt($id_to, $id_from){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare(
				"INSERT INTO friendlist_attestation (user_id_to, user_id_from, date_time)"
					." VALUES(?, ?, CURRENT_TIMESTAMP)");
			$query->execute([$id_to, $id_from]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// отменить запрос на новый контакт
	public function BackNewContakt($id_to, $id_from){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare(
				"DELETE FROM friendlist_attestation"
					." WHERE user_id_to = ? AND user_id_from = ?");
			$query->execute([$id_to, $id_from]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// добавление в список контактов
	public function NewContakt($id_to, $id_from){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare(
				"INSERT INTO friendlist (user_id_first, user_id_last, date_time)"
					." VALUES(?, ?, CURRENT_TIMESTAMP)");
			$query->execute([$id_to, $id_from]);

			$query = $pdo->prepare(
				"DELETE FROM friendlist_attestation"
					." WHERE user_id_to = ? AND user_id_from = ?");
			$query->execute([$id_to, $id_from]);

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// удаление контакта
	public function DeleteContakt($id_to, $id_from){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare(
				"DELETE FROM friendlist"
					." WHERE (user_id_first = ? AND user_id_last = ?) OR (user_id_first = ? AND user_id_last = ?)");
			$query->execute([$id_to, $id_from, $id_from, $id_to]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

}