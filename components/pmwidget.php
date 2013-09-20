<?php

class PmWidget extends CWidget {

	public $userId = null;
	public $tableName = null;
	public $moduleName = 'pm';
	public $url = array('/pm/default/listincoming');

	public function run()
	{
		if (Yii::app()->user->isGuest) {
			return;
		}
		
		$module = Yii::app()->getModule($this->$moduleName);
		$tableName = ($this->tableName === null) ? $module->tableName : $this->tableName;
		$userId = ($this->userId === null) ? $module->getUserId() : $this->userId;
		
		$sql = "SELECT COUNT(*) FROM $tableName WHERE `recipient_id`=:userid AND `read`=0 AND `dr`=0";
		$unread = Yii::app()->db->createCommand($sql)->queryScalar(array(':userid' => $userId));

		$this->render('pmwidget', array(
			'unread' => $unread
		));
	}
}
