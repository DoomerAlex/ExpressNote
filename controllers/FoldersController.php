<?
require_once(ROOT.'/controllers/FirstController.php');
require_once(ROOT.'/models/FolderModel.php');


class FoldersController extends FirstController{

	public function actionAllFolders(){
		$arResult = array('name_page' => 'Заметки');
		FirstModel::openPage('massanges',$arResult);
		return true;
	}

}