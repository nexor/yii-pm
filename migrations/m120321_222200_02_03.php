<?php

/**
 * Migration for pm module from v. 0.2 to v. 0.3
 */
class m120321_222200_02_03 extends CDbMigration
{
	/**
	 * @var personal messages table name
	 */
	public $tablename = 'pm';

	/**
	 * @var convert existing messages to threads
	 */
	public $createThreads = true;

	public function up()
	{ 
		$this->renameColumn($this->tablename, 'sender', 'sender_id');
		$this->renameColumn($this->tablename, 'recipient', 'recipient_id');
		
		$this->addColumn($this->tablename, 'created', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date`'); 
		$this->update($this->tablename, array(
			'created' => new CDbExpression('FROM_UNIXTIME(`date`)')
		));
		
		$this->dropColumn($this->tablename, 'date');
		$this->addColumn($this->tablename, 'thread_id', 'INT UNSIGNED NOT NULL DEFAULT "0" AFTER recipient_id');
		$this->createIndex('thread_id', $this->tablename, 'thread_id');
		$this->createIndex('dr', $this->tablename, 'dr');
		$this->createIndex('ds', $this->tablename, 'ds');


		if ($this->createThreads)
		{
			$this->createThreads();
		}
	}

	public function down()
	{
		$this->dropIndex('ds', $this->tablename);
		$this->dropIndex('dr', $this->tablename);
		$this->dropColumn($this->tablename, 'thread_id');
		$this->renameColumn($this->tablename, 'recipient_id',  'recipient');
		$this->renameColumn($this->tablename, 'sender_id',  'sender');

		$this->addColumn($this->tablename, 'date', "INT(10) UNSIGNED NOT NULL AFTER `created`");
		$this->update($this->tablename, array(
			'date' => new CDbExpression('UNIX_TIMESTAMP(created)')	
		));
		$this->dropColumn($this->tablename, 'created');
	}

	public function createThreads()
	{
		while (1)
		{
			$message = Yii::app()->db->createCommand("SELECT * FROM
	       			{$this->tablename} WHERE thread_id=0 ORDER BY id ASC LIMIT 1")->queryRow();
			if (!$message)
				break;

			Yii::app()->db->createCommand("UPDATE {$this->tablename} SET thread_id=:thread_id WHERE
				(sender_id = :user1_id AND recipient_id = :user2_id) OR (sender_id = :user2_id AND recipient_id=:user1_id)"
			)->execute(array(
				':thread_id' => $message['id'],
				':user1_id' => $message['sender_id'],
				':user2_id' => $message['recipient_id']
			));
		}
	}
}
