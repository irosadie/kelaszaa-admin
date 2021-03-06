<?php

namespace app\models\mains\generals;

use Yii;
use app\models\identities\Users;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "meet_schedules".
 *
 * @property int $id
 * @property int|null $class_id
 * @property string|null $title
 * @property string|null $short_desc
 * @property string|null $desc
 * @property string|null $thumbnail
 * @property string|null $via
 * @property string|null $url
 * @property int|null $date_begin
 * @property int|null $date_end
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Classes $class
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Users $updatedBy
 */
class MeetSchedules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meet_schedules';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function delete()
    {
        $this->scenario = 'delete';
        if ($this->save()) :
            return true;
        endif;
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'date_begin', 'date_end', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['short_desc', 'desc', 'via'], 'string'],
            [['title', 'thumbnail', 'url'], 'string', 'max' => 255],

            ['created_by', 'default', 'value' => Yii::$app->user->id],
            ['updated_by', 'default', 'value' => Yii::$app->user->id, 'when' => function ($model) {
                return !$model->isNewRecord;
            }],
            ['deleted_at', 'default', 'value' => time(), 'on' => 'delete'],
            ['deleted_by', 'default', 'value' => Yii::$app->user->id, 'on' => 'delete'],

            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'title' => Yii::t('app', 'Title'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'desc' => Yii::t('app', 'Desc'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'via' => Yii::t('app', 'Via'),
            'url' => Yii::t('app', 'Url'),
            'date_begin' => Yii::t('app', 'Date Begin'),
            'date_end' => Yii::t('app', 'Date End'),
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
     * Gets query for [[Class]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }
}