<?php

/**
 * This is the model class for table "pm".
 *
 * The followings are the available columns in table 'pm':
 * @property string $id
 * @property integer $sender
 * @property integer $recipient
 * @property integer $read
 * @property integer $date
 * @property string $subject
 * @property string $text
 */
class PersonalMessage extends CActiveRecord
{	
	/**
	 * Returns the static model of the specified AR class.
	 * @return PersonalMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->getModule('pm')->tableName;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('text', 'required'),
			array('subject', 'safe'),
			array('id, sender_id, recipient_id, read, created, subject, text', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		$userClass = Yii::app()->getModule('pm')->userClass;
		return array(
			'sender' => array(self::BELONGS_TO, $userClass, 'sender_id'),
			'recipient' => array(self::BELONGS_TO, $userClass, 'recipient_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender_id' => PmModule::t('Sender'),
			'recipient_id' => PmModule::t('Recipient'),
			'read' => PmModule::t('Read'),
			'created' => PmModule::t('Created'),
			'subject' => PmModule::t('Subject'),
			'text' => PmModule::t('Text'),

			'interlocutorId' => PmModule::t('Interlocutor')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('sender_id',$this->sender);

		$criteria->compare('recipient_id',$this->recipient);

		$criteria->compare('subject',$this->subject,true);

		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Scopes list
	 *
	 * @return array
	 */
	public function scopes()
	{
		return array(
			'unread' => array(
				'condition' => '`read`=0 && `dr`=0'
			),
		);
	}

	/**
	 * Scope: messages which given user can read and delete
	 *
	 * @return self
	 */
	public function haveAccess($userId)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition' => '(sender_id = :user_id AND ds=0) OR (recipient_id = :user_id AND dr=0)',
			'params' => array(
				':user_id' => $userId
			)
		));
		return $this;
	}

	/**
	 * Scope: messages by given recipient Id
	 *
	 * @return self
	 */
	public function byRecipient($recipientId)
	{
		$this->getDbCriteria()->mergeWith(array(
        		'condition' => 'recipient_id=:recipient_id',
			'params' => array(
				'recipient_id' => $recipientId
			)
    		));
    		return $this;
	}

	/**
	 * Scope: messages by given sender Id
	 *
	 * @return self
	 */
	public function bySender($senderId)
	{
		$this->getDbCriteria()->mergeWith(array(
        		'condition' => 'sender_id=:sender_id',
			'params' => array(
				'sender_id' => $senderId
			)
   	 	));
    		return $this;
	}

	/**
	 * Scope: messages with given interlocutor Id
	 *
	 * @return self
	 */
	public function byInterlocutorId($interlocutorId)
	{
		$selfId = Yii::app()->getModule('pm')->getUserId();

		$this->getDbCriteria()->mergeWith(array(
			'condition' => '(sender_id=:user_id AND recipient_id=:inter_id) 
				OR (recipient_id=:user_id AND sender_id=:inter_id)',

			'params' => array(
				':user_id' => $selfId,
				':inter_id' => $interlocutorId
			)
   	 	));
    		return $this;
	}

	/**
	 * Get Id of the interlocutor
	 *
	 * @return int
	 */
	public function getInterlocutorId()
	{	
		$selfId = Yii::app()->getModule('pm')->getUserId();
		if ($this->recipient_id == $selfId)
		{
			return $this->sender_id;
		} elseif ($this->sender_id == $selfId)
		{
			return $this->recipient_id;
		} else {
			return false;
		}
	}

	/**
	 * Get name of the interlocutor
	 *
	 * @return string
	 */
	public function getInterlocutorName()
	{
		$selfId = Yii::app()->getModule('pm')->getUserId();
		if ($this->recipient_id == $selfId)
		{
			return $this->getSenderName();
		} elseif ($this->sender_id == $selfId)
		{
			return $this->getRecipientName();
		} else {
			return null;
		}
	}

	/**
	 * Get sender name
	 *
	 * @return string
	 */
	public function getSenderName()
	{
		if ($this->sender)
		{
			$getNameMethod = Yii::app()->getModule('pm')->getNameMethod;
			return $this->sender->$getNameMethod();
		} else {
			return null;
		}
	}

	/**
	 * Get recipient name
	 *
	 * @return string
	 */
	public function getRecipientName()
	{
		if ($this->recipient)
		{
			$getNameMethod = Yii::app()->getModule('pm')->getNameMethod;
			return $this->recipient->$getNameMethod();
		} else {
			return null;
		}
	}

	/**
	 * Send this message to one or many users.
	 *
	 * @param int|array $recipients recipients user id's
	 */
	public function send($recipients)
	{
		if (!is_array($recipients))
		{
			$this->recipient_id = $recipients;
			$this->save();
			return $this;
		} else {

			$this->_errorModels = array();
			foreach ($recipients as $userid)
			{
				$message = $this->clone();
				$message->recipient_id = $userid;
				if (!$message->save())
				{
					$this->errorModels[] = $message;	
				}
			}
			
			return count($this->errorModels) == 0;
		}
	}

	public function getErrorsModels()
	{
		return $this->_errorModels;
	}

	/**
	 * Mark message as read
	 */
	public function markAsRead()
	{
		if (!$this->read)
		{
			$this->read = 1;
			$this->save(false, array('read'));
		}
	}

	/**
	 * Add 'Re: ' prefix to message subject
	 *
	 * @param string $reply source topic
	 */
	public function addReplyPrefix($reply)
	{
		if (substr($reply, 0, 4)=="Re: ")
		{
			return str_replace("Re: ", "Re(2): ", $reply);
		} elseif (substr($reply, 0, 3)=="Re(")
		{
				return preg_replace_callback('~^Re\((\d{1,2})\)~', array($this, '_replaceReplyPrefixCallback'), $reply);
		} else {
			return "Re: $reply";
		}
	}

	/**
	 * Replace 'Re: ' prefix callback function
	 *
	 * @param array $patterns
	 */
	private function _replaceReplyPrefixCallback($patterns)
	{
		return str_replace($patterns[1], $patterns[1]+1, $patterns[0]);
	}	
}
