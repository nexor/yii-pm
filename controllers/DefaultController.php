<?php

class DefaultController extends Controller
{
	protected $_userId; // current user ID

	public function beforeAction($action)
	{
		if (parent::beforeAction($action) )
		{
			$this->_userId = Yii::app()->getModule('pm')->getUserId();
			return true;
		} else
		{
			return false;
		}
	}

	public function filters()
	{
		if (isset(Yii::app()->getModule('pm')->filters['pm']))
		{
			return Yii::app()->getModule('pm')->filters['pm'];
		} else {
			return array(

			);
		}
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
		if ( ($model->recipient == $this->_userId) && !$model->read) {
			$model->read = 1;
			$model->save();
		}
	
		$this->render('view', array(
			'model' => $model
		));
	}

	/**
	 * Compose personal message
	 *
	 * $mode - перменная, которая передается в view для определения действия
	 *   compose - показать форму отправки сообщения
	 *   success - сообщение успешно отправлено
	 */
	public function actionCreate($to)
	{
		$model = new PersonalMessage;
		$model->sender = $this->_userId;
		$model->recipient = $to;
		if ($model->recipientUser === null)
		{
			$this->redirect($this->createUrl('/pm/default'));
		}

		if (isset($_POST['PersonalMessage'])) 
		{
			$model->attributes = $_POST['PersonalMessage'];
			$model->date = time();
			
			if ($model->save())
		       	{
				Yii::app()->user->setFlash('success', PmModule::t('Сообщение отправлено!'));
				$this->redirect(array('/pm/default'));					
			}
			
		} else {
			if ($model->sender == $model->recipient)
			{
				$this->redirect($this->createUrl('/pm/default'));
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
	 * Ответить на существующее сообщение
	 * @todo запретить отвечать на свои же сообщения
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
			$modelNew->date = time();			

			if ($modelNew->save()) {
				Yii::app()->user->setFlash('success', PmModule::t('Сообщение отправлено!'));
				$this->redirect(array('/pm/default'));					
			} else {
				$mode = 'error';
			}
			
		} else
		{
			$modelNew->subject = $this->addRe($model->subject);	
		}

		$this->render('reply',
			array(
				'modelNew' => $modelNew,
				'model' => $model
			)
		);
	}
	 
	/**
	 * Удалить персональное сообщение
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
					$this->render('delsuccess');
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

	private function haveAccess(&$model) 
	{
		return (($this->_userId == $model->sender) && !$model->ds) ||
			   (($this->_userId == $model->recipient) && !$model->dr);
	}

	/**
	 * Add 'Re: ' prefix to message subject
	 *
	 * @param string $reply
	 */
	private function addRe($reply)
	{
		if (substr($reply, 0, 4)=="Re: ")
		{
			return str_replace("Re: ", "Re(2): ", $reply);
		} elseif (substr($reply, 0, 3)=="Re(")
		{
				return preg_replace_callback('~^Re\((\d{1,2})\)~', array($this, 'replaceRe'), $reply);
		} else {
			return "Re: $reply";
		}
	}

	/**
	 * Replace 'Re: ' prefix callback function
	 *
	 * @param array $patterns
	 */
	private function replaceRe($patterns)
	{
		return str_replace($patterns[1], $patterns[1]+1, $patterns[0]);
	}
}
