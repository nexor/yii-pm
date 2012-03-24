<?php

class ThreadController extends Controller
{
	protected $_userId; // current user ID

	public function beforeAction($action)
	{	
		if (!$this->module->conversationMode)
		{
			$this->redirect(array('default/index'));
		}
		$this->_userId = Yii::app()->getModule('pm')->getUserId();
		$this->breadcrumbs = array(
			PmModule::t('Personal messages') => array('/pm')
		);
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
	 */
	public function actionIndex()
	{
		$unread = PersonalMessage::model()
			->unread()
			->byRecipient($this->_userId)
			->count();	
		
		$user = new $this->module->userClass;
		$users = $user->findAll(array(
			'limit' => 10	
		));
		
		$dataProvider = new CActiveDataProvider('PersonalMessage', array(
			'criteria' => array(
				'condition' => '(t.sender_id = :user_id OR t.recipient_id = :user_id)',

				'params' => array(
					':user_id' => $this->_userId
				),
				'group' => 'thread_id DESC',
				'with' => array('recipient', 'sender'),
			)		
		));

		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'unread' => $unread,
			'users' => $users
		));
	}

	/**
	 * View thread
	 * @param int $id userid
	 */
	public function actionView($id)
	{
		$dataProvider = new CActiveDataProvider('PersonalMessage', array(
			'criteria' => array(
				'scopes' => array(
					'byInterlocutorId' => $id
				),
				'order' => 'id DESC'
			)
		));

		$message = new PersonalMessage;
		$message->recipient_id = $id;
		if (isset($_POST['PersonalMessage']))
		{
			$message->attributes = $_POST['PersonalMessage'];	
			$message->sender_id = $this->_userId;
			$message->recipient_id = $id;
			
			$lastMessage = PersonalMessage::model()->byInterlocutorId($id)->find();
			if ($lastMessage !== null && $lastMessage->thread_id != 0)
			{
				$message->thread_id = $lastMessage->thread_id;
			}

			if ($message->save())
			{
				if ($message->thread_id == 0)
				{
					$message->thread_id = $message->id;
					$message->save(false, array('thread_id'));
				}
				$this->refresh();
			}
		}
		$this->render('view', array(
			'dataProvider' => $dataProvider,
			'message' => $message
		));
	} 

	/**
	 * View threads with unread messages
	 */
	public function actionUnreadList() 
	{
		$dataProvider = new CActiveDataProvider('PersonalMessage', array(
			'criteria' => array(
				'condition' => 't.recipient_id = :user_id AND `read`=0',

				'params' => array(
					':user_id' => $this->_userId
				),
				//'group' => 'thread_id DESC',
				'with' => array('recipient', 'sender')
			)		
		));

		$this->render('unreadList', array(
			'dataProvider' => $dataProvider
		));
	}
}
