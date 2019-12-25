<?
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/TaskModel.php');

class TasksController extends FirstController{

	public function actionAllTasks(){
		$tasks = Tasks::getAllTasksByUserId($_SESSION['user_id']);
		$arResult = array('name_page' => 'Задачи', 'tasks' => $tasks);
		FirstModel::openPage('tasks',$arResult);
		return true;
	}

	public function actionCalendar(){
		$arResult = array('name_page' => 'Календарь');
		FirstModel::openPage('calendar',$arResult);
		return true;
	}

	public function actionPageNewTask(){
		$arExecutors = Tasks::getContakts($_SESSION['user_id']);
		if(!empty($_GET['executor_id'])){
			foreach ($arExecutors as $key => $val){
				if($_GET['executor_id'] == $val['id']){
					$executor = $arExecutors[$key];
				}
			}
		}
		$arResult = array('name_page' => 'Новая задача', 'contacts' => $arExecutors, 'executor' => $executor);
		FirstModel::openPage('tasks/new', $arResult);
		return true;
	}

	// Ajax добавление в БД новой задачи
	public function actionAjaxAddTask(){
		$result = Tasks::AddTask();
		echo $result;
		return true;
	}

	// просмотр задачи детально
	public function actionDetailTask($id_task){
		$arTask = Tasks::getTaskById($id_task);
		$arComments = Tasks::getAllCommentsByTaskId($id_task);
		$history = Tasks::getHistoryByTaskId($id_task);
		$arResult = array('name_page' => 'Задача', 'task' => $arTask, 'comments' => $arComments, 'history' => $history);
		FirstModel::openPage('tasks/detail', $arResult);
		return true;
	}

	public function actionAjaxCloseSubtask(){
		echo Tasks::CloseSubtask($_POST['id_subtask']);
		return true;
	}

	public function actionAjaxOpenSubtask(){
		echo Tasks::OpenSubtask($_POST['id_subtask']);
		return true;
	}

	public function actionAjaxAddComment(){
		echo Tasks::AddComment($_POST['task_id'], $_POST['text'], $_SESSION['user_id']);
		return true;
	}

	public function actionAjaxDeleteComment(){
		echo Tasks::DeleteComment($_POST['comment_id']);
		return true;
	}

	public function actionAjaxChangeStatusTask(){
		echo Tasks::ChangeStatusTask($_POST['task_id'], $_POST['status']);
		return true;
	}

	public function actionEditPage($id_task){
		$arTask = Tasks::getTaskById($id_task);
		$arComments = Tasks::getAllCommentsByTaskId($id_task);
		$arExecutors = Tasks::getContakts($_SESSION['user_id']);
		$arResult = array('name_page' => 'Редактирование задачи', 'task' => $arTask, 'comments' => $arComments, 'contacts' => $arExecutors);
		FirstModel::openPage('tasks/edit', $arResult);
		return true;
	}

	public function actionAjaxEditTask(){
		echo Tasks::EditTaskById($_POST['edit_task_id']);
		return true;
	}

	public function actionAjaxDeleteTask(){
		echo Tasks::DeleteTaskById($_POST['task_id']);
		return true;
	}

	public function actionAjaxReloadDetailTask(){
		$arTask = Tasks::getTaskById($_POST['task_id']);
		$arComments = Tasks::getAllCommentsByTaskId($_POST['task_id']);
		$history = Tasks::getHistoryByTaskId($_POST['task_id']);
		$arResult = array('task' => $arTask, 'comments' => $arComments, 'history' => $history);
		require_once(ROOT."/templates/pages/tasks/detail/ajaxPage.php");
		return true;
	}

	public function actionAjaxFilterTasks(){
		print_r($_POST);
		return true;
	}
}