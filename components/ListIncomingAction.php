<?php

class ListIncomingAction extends CAction
{
	public $userId;	
	public function run()
	{
		$pm = Yii::app()->getModule('pm');
		$this->userId = $this->controller->sender;

		$criteria = new CDbCriteria(array(
			'condition' => 'recipient=:recipient AND `dr`=0',
			'params' => array(
				':recipient' => $this->userId
			),
			'with' => 'senderUser',
			'order' => '`t`.`id` DESC'	
		));

		$dataProvider = new CActiveDataProvider('PersonalMessage', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $pm->incomingPageSize
			)
		));

		$this->controller->render('listincoming', array(
			'dataProvider' => $dataProvider,
		));
	}
}
