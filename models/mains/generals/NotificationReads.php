<?php

namespace app\models\mains\generals;

use Yii;

/**
 * This is the model class for table "notification_reads".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $notification_id
 * @property string|null $role_type
 * @property int|null $read_at
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

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_id', 'read_at'], 'integer'],
            [['role_type'], 'string', 'max' => 255],
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
}
