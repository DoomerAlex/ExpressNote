<?
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/MassangeModel.php');


class MassangeController extends FirstController{

	public function actionAllMassanges(){
		$arResult = array('name_page' => 'Сообщения');
		FirstModel::openPage('massanges',$arResult);
		return true;
	}

}