<?php

class PmWidget extends CWidget {

	public function run()
	{
		if (!Yii::app()->user->isGuest)
		{
			$tableName = Yii::app()->getModule('pm')->tableName;
			$unread = Yii::app()->db->createCommand(
				"SELECT COUNT(*) FROM $tableName WHERE recipient=:userid AND `read`=0 AND dr=0"
			)->queryScalar(array(
				':userid' => Yii::app()->getModule('pm')->getUserId()
			));

			$this->render('pmwidget', array(
				'unread' => $unread
			));
		}
	}
}
