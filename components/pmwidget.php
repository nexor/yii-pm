<?php

class PmWidget extends CWidget {

	public $userId = null;
	public $tableName = null;
	public $url = array('/pm/default/listincoming');

	public function run()
	{
		if (!Yii::app()->user->isGuest)
		{
			if ($this->tableName === null)
			{
				$tableName = Yii::app()->getModule('pm')->tableName;
			} else {
				$tableName = $this->tableName;
			}

			if ($this->userId === null)
			{
				$unread = Yii::app()->db->createCommand(
					"SELECT COUNT(*) FROM $tableName WHERE recipient_id=:userid AND `read`=0 AND dr=0"
				)->queryScalar(array(
					':userid' => Yii::app()->getModule('pm')->getUserId()
				));
			} else {
				$unread = Yii::app()->db->createCommand(
					"SELECT COUNT(*) FROM $tableName WHERE recipient_id=:userid AND `read`=0 AND dr=0"
				)->queryScalar(array(
					':userid' => $this->userId
				));
			}

			$this->render('pmwidget', array(
				'unread' => $unread
			));
		}
	}
}
