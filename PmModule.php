<?php

class PmModule extends CWebModule
{
	public $userClass = 'User'; //User model class name
	public $useridField = 'id'; // поле в таблице пользователя - id пользователя
	public $tableName = 'pm'; // таблица с персональными сообщениями
	public $reallyDelete = true; // удалять сообщения из базы
	public $outgoingPageSize = 10; // отправленных сообщений на страницу
	public $incomingPageSize = 10; // принятых сообщений на страницу
	public $filters = array(
		'pm' => array(
			'accessControl',
		),
	);
	
	public function init()
	{
		$this->setImport(array(
			'pm.models.*',
			'pm.components.*',
		));
	}

	public static function t($message)
	{
		return Yii::t('PmModule.pm', $message);
	}

	/**
	 * This method must return unique user id
	 * @return string
	 */
	public function getUserId()
	{
		return Yii::app()->user->{$this->useridField};
	}
}
