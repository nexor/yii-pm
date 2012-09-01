<?php

/**
 * Personal Messages module for Yii framework
 *
 * @version 0.3
 */
class PmModule extends CWebModule
{
	/**
	 * User model class name
	 * @var string
	 */
	public $userClass = 'User';

	/**
	 * Method for getting user name.
	 * If callback, then user model will be passed as the argument.
	 * @var string|callback
	 */
	public $getNameMethod = 'getName';

	/**
	 * @var string|callback
	 */
	public $getUserIdExpression = 'Yii::app()->user->id';

	/**
	 * User's table name
	 * @var string
	 */
	public $tableName = 'pm';

	/**
	 * Delete messages from database, not only mark as deleted
	 * @var bool
	 */
	public $reallyDelete = true;

	/**
	 * Messages per page in the outgoing list
	 * @var int
	 */
	public $outgoingPageSize = 10;

	/**
	 * Messages per page in the incoming list
	 * @var int
	 */
	public $incomingPageSize = 10; //

	/**
	 * Enable conversation mode
	 * @var bool
	 */
	public $conversationMode = true;

	/**
	 * Additional models import
	 * @var array
	 */
	public $import = array();

	/**
	 * Module initialization
	 * @return void
	 */
	public function init()
	{
		$this->setImport(array_merge(
			$this->import,
			array(
				'pm.models.*',
				'pm.components.*',
			)
		));
		return parent::init();
	}

	/**
	 * Translate message
	 *
	 * @param $message
	 * @param array $params
	 * @return string
	 */
	public static function t($message, $params = array())
	{
		return Yii::t('PmModule.pm', $message, $params);
	}

	/**
	 * Get current user id
	 * @return string
	 */
	public function getUserId()
	{
		return $this->evaluateExpression($this->getUserIdExpression);
	}

	/**
	 * Get username for the given user model.
	 * @param CActiveRecord $model  User model
	 * @throws CException
	 * @return string
	 */
	public function getUserName($model)
	{
		$getNameMethod = $this->getNameMethod;
		if (is_string($getNameMethod))
		{
			return $model->$getNameMethod();
		} elseif (is_callable($getNameMethod)) {
			return $this->evaluateExpression($getNameMethod, array('user' => $model));
		} else {
			throw new CException(__CLASS__." error: 'getNameMethod' option must be a string or valid callback");
		}

	}
}
