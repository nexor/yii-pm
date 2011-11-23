<?php

/**
 * Personal Messages module for Yii framework
 *
 * @version 0.2
 */
class PmModule extends CWebModule
{
	public $userClass = 'User';    // User model class name
	public $useridField = 'id';    // Primary key name for user's table
	public $tableName = '`pm`';    // User's table name
	public $reallyDelete = true;   // Delete messages from database, not only mark as deleted
	public $outgoingPageSize = 10; // Messages per page in the outgoing list
	public $incomingPageSize = 10; // Messages per page in the incoming list
	
	public function init()
	{
		$this->setImport(array(
			'pm.models.*',
			'pm.components.*',
		));
	}

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
		return Yii::app()->user->{$this->useridField};
	}
}
