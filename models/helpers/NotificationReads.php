<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notification_reads".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $notification_id
 * @property string|null $role_type
 * @property int|null $read_at
 *
 * @property Notifications $notification
 * @property Users $user
 */
class NotificationReads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification_reads';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'read_at'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_id', 'read_at'], 'integer'],
            [['role_type'], 'string', 'max' => 255],
            ['created_by', 'default', 'value' => Yii::$app->user->id],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notifications::class, 'targetAttribute' => ['notification_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'notification_id' => Yii::t('app', 'Notification ID'),
            'role_type' => Yii::t('app', 'Role Type'),
            'read_at' => Yii::t('app', 'Read At'),
        ];
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notifications::class, ['id' => 'notification_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
