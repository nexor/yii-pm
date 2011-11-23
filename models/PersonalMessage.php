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

	public $senderUsername;
	public $recipientUsername;
	
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
			array('id, sender, recipient, read, date, subject, text', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		$userClass = Yii::app()->getModule('pm')->userClass;
		return array(
			'senderUser' => array(self::BELONGS_TO, $userClass, 'sender'),
			'recipientUser' => array(self::BELONGS_TO, $userClass, 'recipient')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender' => PmModule::t('Sender'),
			'recipient' => PmModule::t('Recipient'),
			'read' => PmModule::t('Read'),
			'date' => PmModule::t('Date'),
			'subject' => PmModule::t('Subject'),
			'text' => PmModule::t('Text'),
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

		$criteria->compare('sender',$this->sender);

		$criteria->compare('recipient',$this->recipient);

		$criteria->compare('subject',$this->subject,true);

		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
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
			$this->recipient = $recipients;
			$this->save();
			return $this;
		} else {

			$this->_errorModels = array();
			foreach ($recipients as $userid)
			{
				$message = $this->clone();
				$message->recipient = $userid;
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

	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			if (empty($this->date))
			{
				$this->date = time();
			}
		}
		return parent::beforeSave();
	}

	public function markAsRead()
	{
		if (!$this->read)
		{
			$this->read = 1;
			$this->save();
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
