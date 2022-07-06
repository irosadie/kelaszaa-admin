<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $roles
 * @property string|null $schools
 * @property string|null $year_of_graduates
 * @property string|null $title
 * @property string|null $sort_desc
 * @property string|null $desc
 * @property string|null $content
 * @property string|null $read_more_uri
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property NotificationReads[] $notificationReads
 * @property Users $updatedBy
 * @property Users $user
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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['roles', 'schools', 'year_of_graduates', 'sort_desc', 'desc', 'content'], 'string'],
            [['title', 'read_more_uri'], 'string', 'max' => 255],
            ['created_by', 'default', 'value' => Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['updated_by' => 'id']],
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
            'roles' => Yii::t('app', 'Roles'),
            'schools' => Yii::t('app', 'Schools'),
            'year_of_graduates' => Yii::t('app', 'Year Of Graduates'),
            'title' => Yii::t('app', 'Title'),
            'sort_desc' => Yii::t('app', 'Sort Desc'),
            'desc' => Yii::t('app', 'Desc'),
            'content' => Yii::t('app', 'Content'),
            'read_more_uri' => Yii::t('app', 'Read More Uri'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[NotificationReads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationReads()
    {
        return $this->hasMany(NotificationReads::class, ['notification_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'updated_by']);
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
