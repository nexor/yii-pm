<?php

/**
 * Personal Messages module for Yii framework
 *
 * @version 0.3
 */
class PmModule extends CWebModule
{
	public $userClass = 'User';    // User model class name
	public $getNameMethod = 'getName';
	public $getUserIdExpression = 'Yii::app()->user->id';
	public $tableName = 'pm';    // User's table name
	public $reallyDelete = true;   // Delete messages from database, not only mark as deleted
	public $outgoingPageSize = 10; // Messages per page in the outgoing list
	public $incomingPageSize = 10; // Messages per page in the incoming list
	
	public $conversationMode = true;
	
	public function init()
	{
		$this->setImport(array(
			'pm.models.*',
			'pm.components.*',
		));
	}

	/**
	 * Translate message
	 *
	 * @return string
	 */
	public static function t($message, $params = array())
	{
		return Yii::t('PmModule.pm', $message, $params);
	}

	/**
	 * This method must return current user id
	 * @return string
	 */
	public function getUserId()
	{
		return eval('return '.$this->getUserIdExpression.';');
	}
}
