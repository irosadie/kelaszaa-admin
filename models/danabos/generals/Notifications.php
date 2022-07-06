<?php

namespace app\models\danabos\generals;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_levels
 * @property int|null $notification_template_id
 * @property string|null $short_content
 * @property string|null $contents
 * @property string|null $read_more_url
 * @property int|null $status
 * @property int|null $read_at
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_template_id', 'status', 'read_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['user_levels', 'short_content', 'contents', 'read_more_url'], 'string'],
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
            'user_levels' => Yii::t('app', 'User Levels'),
            'notification_template_id' => Yii::t('app', 'Notification Template ID'),
            'short_content' => Yii::t('app', 'Short Content'),
            'contents' => Yii::t('app', 'Contents'),
            'read_more_url' => Yii::t('app', 'Read More Url'),
            'status' => Yii::t('app', 'Status'),
            'read_at' => Yii::t('app', 'Read At'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
}
