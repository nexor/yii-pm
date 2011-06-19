<?php

class PmWidget extends CWidget {
	public $userIdField = 'id';
	public $tableAlias = 'pm';

	public function run()
	{
		$unread = Yii::app()->db->createCommand(
			"SELECT COUNT(*) as cnt FROM {$this->tableAlias} WHERE recipient=:userid AND `read`=0 AND dr=0"
		)->queryScalar(array(
			':userid' => Yii::app()->user->{$this->userIdField}
		));

		$this->render('pmwidget', array(
			'unread' => $unread
		));
	}
}
