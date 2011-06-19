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
		return array(
			'senderUser' => array(self::BELONGS_TO, Yii::app()->getModule('pm')->userClass, 'sender'),
			'recipientUser' => array(self::BELONGS_TO, Yii::app()->getModule('pm')->userClass, 'recipient')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender' => PmModule::t('Отправитель'),
			'recipient' => PmModule::t('Получатель'),
			'read' => PmModule::t('Прочитано'),
			'date' => PmModule::t('Дата'),
			'subject' => PmModule::t('Тема сообщения'),
			'text' => PmModule::t('Текст'),
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
}
