<?php

class Tasks extends FirstModel{

	// контактные данные для пользователя
	public function getContakts($user_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			"SELECT U.first_name, U.last_name, U.id, U.avatar"
			." FROM friendlist F, users U "
			." WHERE (F.user_id_first = ? AND F.user_id_last = U.id) OR (F.user_id_last = ? AND F.user_id_first = U.id)");
		$query->execute([$user_id, $user_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['first_name'] = $row->first_name;
			$val['last_name'] = $row->last_name;
			$val['avatar'] = $row->avatar;
			array_push($result, $val);
		}
		return $result;
	}

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

	// Добавление задачи в БД
	public function addTask(){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
		
			$director = $_SESSION['user_id']; 			// постановщик
			$title = $_POST['new-task-title']; 			// заголовок
			$text = $_POST['new-task-text']; 			// текст
			$executor = $_POST['new-task-executor']; 	// исполнитель
			$control = FirstModel::checkbox_verify('new-task-control'); // контроль

			$deadlene_check = FirstModel::checkbox_verify('new-task-deadline-check');
			if ($deadlene_check == 1){
				$deadline_year = $_POST['new-task-deadline-year'];
				$deadline_month = $_POST['new-task-deadline-month'];
				$deadline_day = $_POST['new-task-deadline-day'];
				$deadline_hour = $_POST['new-task-deadline-hour'];
				$deadline_minute = $_POST['new-task-deadline-minute'];

				$datetime = mktime($deadline_hour, $deadline_minute, 0, $deadline_month, $deadline_day, $deadline_year); // час, минуты, секунды, месяц, день, год
			}
			else $datetime = NULL;
			/*
				new -> новая
				in_work -> в работе
				canceled -> отменена
				defer -> отложена
				close -> завершена
				wait_control -> ожидает согласования
			*/
			
			if ($executor != $director) $status = 1;
			else $status = 2;

			$query = $pdo->prepare(
				"INSERT INTO tasks (title, text, executor_id, director_id, deadline, date_create, status, control)"
				." VALUES(?, ?, ?, ?, FROM_UNIXTIME(?), CURRENT_TIMESTAMP, ?, ?)");
			$query->execute([$title, $text, $executor, $director, $datetime, $status, $control]);

			$query = $pdo->prepare(
				"SELECT id FROM tasks WHERE title = ? AND director_id = ? ORDER BY date_create DESC LIMIT 1");
			$query->execute([$title, $director]);
			$id_task = $query->fetchColumn();

			// подзадачи
			$subtasks = $_POST['new-task-subtasks'];

			if (is_array($subtasks)){
				$query = $pdo->prepare("INSERT INTO subtasks (task_id, status, text) VALUES( ?, ?, ?)");
				foreach ($subtasks as  $val) {
					$query->execute([$id_task, 1, $val]);
				}
			}
			
			Tasks::AddEventByTask($id_task, 1);

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// все задачи по id пользлователя
	public function getAllTasksByUserId($user_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			"SELECT T.id, T.title, T.executor_id, T.director_id, T.deadline, T.date_create, T.status, S.id as id_status, S.name, S.ru_name"
			." FROM tasks T, status_task S"
			." WHERE (T.executor_id = ? OR T.director_id = ?)"
			." AND T.status = S.id"
			." AND T.status != 3"
			." ORDER BY deadline");
		$query->execute([$user_id, $user_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['title'] = $row->title;
			$val['executor'] = $row->executor_id;
			$val['director'] = $row->director_id;
			if ($row->deadline > 0){
				$val['deadline'] = FirstModel::getDate($row->deadline);
				$val['deadline_val'] = strtotime($row->deadline);
			}
			else $val['deadline'] = '';
			$val['date_create'] = $row->date_create;
			$val['status'] = $row->name;
			$val['ru_status'] = $row->ru_name;
			$val['status_id'] = $row->id_status;
			array_push($result, $val);
		}
		return $result;
	}

	
	// задача детально
	public function getTaskById($task_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT T.*, S.id as status_id, S.name, S.ru_name FROM tasks T, status_task S WHERE T.id = ? AND T.status = S.id");
		$query->execute([$task_id]);
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$result['id'] = $row->id;
			$result['title'] = $row->title;
			$result['text'] = $row->text;
			$result['executor'] = Tasks::getUserById($row->executor_id);
			$result['director'] = Tasks::getUserById($row->director_id);
			$result['deadline'] = strtotime($row->deadline);
			$result['date_create'] = strtotime($row->date_create);
			$result['status'] = $row->name;
			$result['ru_status'] = $row->ru_name;
			$result['status_id'] = $row->status_id;
			$result['control'] = $row->control;
			$result['subtasks'] = Tasks::getSubtasksByIdTask($task_id);
		}
		return $result;
	}

	// подзадачи для задачи
	public function getSubtasksByIdTask($task_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT id, text, status FROM subtasks WHERE task_id = ?");
		$query->execute([$task_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['text'] = $row->text;
			$val['status'] = $row->status;
			array_push($result, $val);
		}
		return $result;
	}

	// закрыть подзадачу
	public function CloseSubtask($id_subtask){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("UPDATE subtasks SET status = false WHERE id = ?");
			$query->execute([$id_subtask]);

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}
	// Возобновить подзадачу
	public function OpenSubtask($id_subtask){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("UPDATE subtasks SET status = true WHERE id = ?");
			$query->execute([$id_subtask]);

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// все комментарии по id задачи
	public function getAllCommentsByTaskId($id_task){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare("SELECT * FROM comments_task WHERE task_id = ?");
		$query->execute([$id_task]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
				$val['id'] = $row->id;
				$val['user'] = Tasks::getUserById($row->user_id);
				$val['task_id'] = $row->task_id;
				$val['text'] = $row->text;
				$val['date_create'] = strtotime($row->date_create);
				array_push($result, $val);
			}
		return $result;
	}

	// добавление комментария
	public function AddComment($task_id, $text, $user_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("INSERT INTO comments_task (task_id, user_id, text) VALUES(?, ?, ?)");
			$query->execute([$task_id, $user_id, $text]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}
	// удаление комментария
	public function DeleteComment($comment_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("DELETE FROM comments_task WHERE id = ?");
			$query->execute([$comment_id]);
			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// смена статуса задачи
	public function ChangeStatusTask($task_id, $status){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("SELECT T.control, T.status, T.executor_id, T.director_id, S.id as status_id FROM tasks T, status_task S WHERE T.id = ? AND T.status = S.id");
			$query->execute([$task_id]);
			while ($row = $query->fetch(PDO::FETCH_LAZY)){
				if ($_SESSION['user_id'] == $row->executor_id || $_SESSION['user_id'] == $row->director_id){
					if ($status == 3){
						if($_SESSION['user_id'] == $row->director_id){ // если звкрывающий = постановщику, то закрывает сразу
							$st = $status;
						}
						else if($row->control == true && $row->status != 5){ // если контроль есть, но проверки не ждет отправляет на проверку
							$st = 5;
							$control = false;
						}
						else if($row->control != true){ // если контроля нет, то закрывает
							$st = $status;
							$control = true;
						}
						else{
							return false;
						}
					}
					else $st = $status;
				}
				else{
					return false;
				}
			}
			$query = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
			$query->execute([$st, $task_id]);

			if($st == 1) Tasks::AddEventByTask($task_id, 4); // возобновление задачи
			else if($st == 2) Tasks::AddEventByTask($task_id, 8); // принята в работу
			else if($st == 3 && $control == true) Tasks::AddEventByTask($task_id, 2); // завершена
			else if($st == 3 && $control == false) Tasks::AddEventByTask($task_id, 3); // подтверждение завершения
			else if($st == 4) Tasks::AddEventByTask($task_id, 5); // отложена
			else if($st == 5) Tasks::AddEventByTask($task_id, 2); // завершена
			else if($st == 6) Tasks::AddEventByTask($task_id, 7); // отменена

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// редактирование задачи
	public function EditTaskById($id_task){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("SELECT S.id FROM tasks T, subtasks S WHERE T.id = ? AND S.task_id = T.id");
			$query->execute([$id_task]);
			$subtasks_id = array(); // id-шники с подзадачами
			while ($row = $query->fetch(PDO::FETCH_LAZY)){
				array_push($subtasks_id, $row->id);
			}
			
			$title = $_POST['edit-task-title'];
			$text = $_POST['edit-task-text'];
			$executor = $_POST['edit-task-executor'];
			$control = FirstModel::checkbox_verify('edit-task-control');
			$subtasks = $_POST['edit-task-subtasks'];
			$old_subtasks = $_POST['edit-old-subtask-id'];

			$deadlene_check = FirstModel::checkbox_verify('edit-task-deadline-check');
			if ($deadlene_check == 1){
				$deadline_year = $_POST['new-task-deadline-year'];
				$deadline_month = $_POST['new-task-deadline-month'];
				$deadline_day = $_POST['new-task-deadline-day'];
				$deadline_hour = $_POST['new-task-deadline-hour'];
				$deadline_minute = $_POST['new-task-deadline-minute'];

				$datetime = mktime($deadline_hour, $deadline_minute, 0, $deadline_month, $deadline_day, $deadline_year); // час, минуты, секунды, месяц, день, год
			}
			else $datetime = NULL;

			$query = $pdo->prepare("UPDATE tasks SET title = ?, text = ?, executor_id = ?, deadline = FROM_UNIXTIME(?), control = ? WHERE id = ? AND director_id = ?");
			$query->execute([$title, $text, $executor, $datetime, $control, $id_task, $_SESSION['user_id']]);

			
			if (is_array($subtasks)){
				for($i = 0; $i < count($subtasks); $i++){
					if(!empty($old_subtasks[$i]) && in_array($old_subtasks[$i], $subtasks_id)){ // если все вернулось переписывваем
						foreach($subtasks_id as $key => $value){
							if($value == $old_subtasks[$i]) unset($subtasks_id[$key]);
						}
						$query = $pdo->prepare("UPDATE subtasks SET text = ? WHERE id = ?");
						$query->execute([$subtasks[$i], $old_subtasks[$i]]);
					}
					else if(empty($old_subtasks[$i]) && !empty($subtasks[$i])){ // если новая - добаляем
						$query = $pdo->prepare("INSERT INTO subtasks (task_id, status, text) VALUES( ?, 1, ?)");
						$query->execute([$id_task, $subtasks[$i]]);
					}
				}
				foreach ($subtasks_id as $value){ // удаляем
					$query = $pdo->prepare("DELETE FROM subtasks WHERE id = ?");
					$query->execute([$value]);
				}
			}
			Tasks::AddEventByTask($id_task, 6); // изменение задачи 

			$pdo->commit();
			return true;
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// запись в журнал событий
	public function AddEventByTask($task_id, $event_id){
		try{
			$pdo = DataBase::getConnectDb();
			$query = $pdo->prepare("INSERT INTO task_event_list (task_id, user_id, event_id) VALUES(?, ?, ?)");
			$query->execute([$task_id, $_SESSION['user_id'], $event_id]);
			return true;
		}
		catch (Exception $e){
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	// история для задачи
	public function getHistoryByTaskId($task_id){
		$pdo = DataBase::getConnectDb();
		$query = $pdo->prepare(
			"SELECT U.id as user_id, U.first_name, U.last_name, EL.event, TEL.datetime "
			." FROM task_event_list TEL, users U, events_list EL "
			." WHERE TEL.task_id = ?"
			." AND TEL.user_id = U.id"
			." AND TEL.event_id = EL.id"
			." ORDER BY TEL.datetime"
		);
		$query->execute([$task_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['user_id'] = $row->user_id;
			$val['user_first_name'] = $row->first_name;
			$val['user_last_name'] = $row->last_name;
			$val['event'] = $row->event;
			$val['datetime'] = strtotime($row->datetime);
			array_push($result, $val);
		}
		return $result;
	}

	// удаление задачи
	public function DeleteTaskById($task_id){
		$pdo = DataBase::getConnectDb();
		try{
			$pdo->beginTransaction();
			$query = $pdo->prepare("SELECT director_id FROM tasks WHERE id = ?");
			$query->execute([$task_id]);
			$director = $query->fetchColumn();
			if ($director == $_SESSION['user_id']){

				$query = $pdo->prepare("DELETE FROM tasks WHERE id = ?"); // удаление задачи
				$query->execute([$task_id]);
				$query = $pdo->prepare("DELETE FROM task_event_list WHERE task_id = ?"); // удаление истории задачи
				$query->execute([$task_id]);
				$query = $pdo->prepare("DELETE FROM subtasks WHERE task_id = ?"); // удаление подзадач
				$query->execute([$task_id]);
				$query = $pdo->prepare("DELETE FROM comments_task WHERE task_id = ?"); // удаление комментариев
				$query->execute([$task_id]);

				$pdo->commit();
				return true;
			}
			else{
				$pdo->rollBack();
				return false;
			}
		}
		catch (Exception $e){
			$pdo->rollBack();
			echo "Ошибка: " . $e->getMessage();
			return false;
		}
	}

	public function getTasksByFilters(){
		$pdo = DataBase::getConnectDb();

		return true;
	}


	// получение задач по фильтру
	public function getTaskList($parram){
		$pdo = DataBase::getConnectDb();

		/*
			$parram = array(
				['executor_id'] => int; // id исполнителя
				['director_id'] => null; // id постановщика
				['status_task'] => 0-6; // id статуса задачи
				['deadline'] => date; // дедлайн
				['control'] => true/false; // контролируется
				['deadline_fail'] => true/false; // просроченные

			);

		*/
		$strQuery = "SELECT T.id, T.title, T.executor_id, T.director_id, T.deadline, T.date_create, T.status, S.id as id_status, S.name, S.ru_name FROM tasks T, status_task S";
		if ($parram['executor_id'])


		$query = $pdo->prepare(
			"SELECT T.id, T.title, T.executor_id, T.director_id, T.deadline, T.date_create, T.status, S.id as id_status, S.name, S.ru_name"
			." FROM tasks T, status_task S"
			." WHERE (T.executor_id = ? OR T.director_id = ?)"
			." AND T.status = S.id"
			." AND T.status != 3"
			." ORDER BY deadline");
		$query->execute([$user_id, $user_id]);
		$result = array();
		$val = array();
		while ($row = $query->fetch(PDO::FETCH_LAZY)){
			$val['id'] = $row->id;
			$val['title'] = $row->title;
			$val['executor'] = $row->executor_id;
			$val['director'] = $row->director_id;
			if ($row->deadline > 0){
				$val['deadline'] = FirstModel::getDate($row->deadline);
				$val['deadline_val'] = strtotime($row->deadline);
			}
			else $val['deadline'] = '';
			$val['date_create'] = $row->date_create;
			$val['status'] = $row->name;
			$val['ru_status'] = $row->ru_name;
			$val['status_id'] = $row->id_status;
			array_push($result, $val);
		}
		return $result;
	}

}