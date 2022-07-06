<?php

namespace app\models\danabos\generals;

use Yii;

/**
 * This is the model class for table "notification_templates".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $parent_id
 * @property string|null $type
 * @property string|null $short_desc
 * @property string|null $content
 * @property string|null $targets
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property NotificationTemplates[] $notificationTemplates
 * @property NotificationTemplates $parent
 */
class NotificationTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['type', 'short_desc', 'content', 'targets'], 'string'],
            [['code'], 'string', 'max' => 15],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationTemplates::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'content' => Yii::t('app', 'Content'),
            'targets' => Yii::t('app', 'Targets'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[NotificationTemplates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationTemplates()
    {
        return $this->hasMany(NotificationTemplates::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(NotificationTemplates::class, ['id' => 'parent_id']);
    }
}
