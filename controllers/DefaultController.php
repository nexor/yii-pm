<?php

class DefaultController extends Controller
{
	protected $_userId; // current user ID

	public function beforeAction($action)
	{	
		$this->_userId = Yii::app()->getModule('pm')->getUserId();
		return parent::beforeAction($action);
	}

	public function filters()
	{
		return array(
			'accessControl'
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
			),
			array('deny',
				'users' => array('*')
			)
		);
	}

	/**
	 * Personal messages menu
	 *
	 * @todo use pmwidget
	 */
	public function actionIndex()
	{		
		$criteria = new CDbCriteria(array(
			'condition' => 'recipient=:userid AND `read` = 0 AND `dr`=0',
			'params' => array(
				':userid' => $this->_userId
			)
		));

		$unread = PersonalMessage::model()->count($criteria);	
		$this->render('index', array('unread' => $unread));
	}

	/**
	 * View message
	 * @param int $id message ID
	 */
	public function actionView($id)
	{
		$model = PersonalMessage::model()->with('senderUser')->findByPk($id);
		if ( ($model === null) || ($model->sender == $this->_userId && $model->ds) || ($model->recipient == $this->_userId && $model->dr))
		{
			throw new CHttpException(404, "Message not found");
		}
		
		if (!$this->haveAccess($model))
		{
			throw new CHttpException(403, "Access forbidden");
		}
			
		// mark message as read
		if ($model->recipient == $this->_userId)
		{
			$model->markAsRead();
		}
	
		$this->render('view', array(
			'model' => $model
		));
	}

	/**
	 * Compose personal message
	 *
	 * $mode - перменная, которая передается в view для определения действия
	 */
	public function actionCreate($to)
	{
		$model = new PersonalMessage;
		$model->sender = $this->_userId;
		$model->recipient = $to;
		if ($model->recipientUser === null)
		{
			throw new CHttpException(404, PmModule::t('User not found'));
		}

		if (isset($_POST['PersonalMessage'])) 
		{
			$model->attributes = $_POST['PersonalMessage'];
			$model->date = time();
			
			if ($model->save())
		       	{
				Yii::app()->user->setFlash('success', PmModule::t('Message has been sent.'));
				$this->redirect(array('/pm/default'));					
			}
			
		} else {
			if ($model->sender == $model->recipient)
			{
				throw new CHttpException(403, "You can't send messages to yourself");
			}
			
			if (isset($_GET['subj']))
			{
				$model->subject = $_GET['subj'];
			}	
		}

		$this->render('create',
			array(
				'model' => $model
			)
		);
	} 


	/**
	 * Reply to message
	 * @todo запретить отвечать на свои же сообщения
	 *
	 * @param int $id message ID
	 */
	public function actionReply($id)
	{
		$model = PersonalMessage::model()->with('senderUser')->findByPk($id);
		if (!$this->haveAccess($model))
		{
			throw new CHttpException(404, "Message not found");
		}

		$modelNew = new PersonalMessage;
		$modelNew->sender = $this->_userId;
		$modelNew->recipient = $model->sender == $this->_userId?
		$model->recipient:$model->sender;
		
		if (isset($_POST['PersonalMessage'])) {
			$modelNew->attributes = $_POST['PersonalMessage'];

			if ($modelNew->save()) {
				Yii::app()->user->setFlash('success', PmModule::t('Сообщение отправлено'));
				$this->redirect(array('/pm/default'));					
			} else {
				$mode = 'error';
			}
			
		} else
		{
			$modelNew->subject = $model->addReplyPrefix($model->subject);	
		}

		$this->render('reply',
			array(
				'modelNew' => $modelNew,
				'model' => $model
			)
		);
	}
	 
	/**
	 * Delete message
	 *
	 * @param int $id message ID
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = PersonalMessage::model()->findByPk($id);
			if ( ($model != null) && $this->haveAccess($model) ) {			
				($this->_userId == $model->sender)?$model->ds=1:$model->dr=1;
				if (Yii::app()->getModule('pm')->reallyDelete && $model->ds && $model->dr) {
					$model->delete();
				} else {
					$model->save();
				}
					
				if (!isset($_GET['ajax']))
				{
					Yii::app()->user->setFlash('success', PmModule::t('Message has been succsefully deleted.'));
					$this->redirect(array('/pm/default/listincoming'));
				}
			}
		} else {
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * View incoming messages list
	 */
	public function actionListIncoming()
	{
		$pm = Yii::app()->getModule('pm');

		$criteria = new CDbCriteria(array(
			'condition' => 'recipient=:recipient AND `dr`=0',
			'params' => array(
				':recipient' => $this->_userId
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

		$this->render('listincoming', array(
			'dataProvider' => $dataProvider,
		));
	}
	
	/**
	 * View outgoing messages list
	 */
	public function actionListOutgoing()
	{
		$pm = Yii::app()->getModule('pm');
		$criteria=new CDbCriteria(array(
			'condition' => '`sender`=:userid AND `ds`=0',
			'params' => array(
				':userid' => $this->_userId
			),
			'order' => '`t`.`id` DESC',
			'with' => 'recipientUser'		
		));

		$dataProvider = new CActiveDataProvider('PersonalMessage', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $pm->outgoingPageSize
			)
		));

		$userModel = new $pm->userClass;
		$userTablename = $userModel->tableName();

		$this->render('listoutgoing', array(
			'dataProvider' => $dataProvider
		));
	}

	public function setSender($id) {
		if (!$id) {
			die( PmModule::t('Вы должны зарегистрироваться, чтобы воспользоваться ЛС'));
		}
		$this->_userId = $id;
	}

	public function getSender()
	{
		return $this->_userId;
	}

	/**
	 * Check if user have access to edit message model
	 *
	 * @param PersonalMessage $model
	 * @return bool
	 */
	private function haveAccess(&$model) 
	{
		return (($this->_userId == $model->sender) && !$model->ds) ||
			   (($this->_userId == $model->recipient) && !$model->dr);
	}

	
}
