<?
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/RemindeModel.php');


class RemindersController extends FirstController{

	public function actionAllReminders(){
		$arResult = array('name_page' => 'Напомининия');
		FirstModel::openPage('reminders',$arResult);
		return true;
	}

}