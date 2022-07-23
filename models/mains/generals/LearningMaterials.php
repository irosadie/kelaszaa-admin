<?php

namespace app\models\mains\generals;

use Yii;
use app\models\identities\Users;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "learning_materials".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $topic_id
 * @property string|null $thumbnail
 * @property string|null $title
 * @property string|null $short_desc
 * @property string|null $desc
 * @property string|null $media
 * @property string|null $media_type
 * @property string|null $media_extension
 * @property int|null $media_weight
 * @property int|null $media_long
 * @property string|null $media_unit
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deleted_by0
 * @property Topics $topic
 * @property Users $updatedBy
 */
class LearningMaterials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'learning_materials';
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
            [['title', 'desc'], 'required'],
            [['topic_id', 'media_weight', 'media_long', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['short_desc', 'desc', 'media_type', 'media_unit'], 'string'],
            [['code', 'media_extension'], 'string', 'max' => 20],
            [['thumbnail', 'title', 'media'], 'string', 'max' => 255],

            ['created_by', 'default', 'value' => Yii::$app->user->id],
            ['updated_by', 'default', 'value' => Yii::$app->user->id, 'when' => function ($model) {
                return !$model->isNewRecord;
            }],
            ['deleted_at', 'default', 'value' => time(), 'on' => 'delete'],
            ['deleted_by', 'default', 'value' => Yii::$app->user->id, 'on' => 'delete'],
            [['desc'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topics::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
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
            'topic_id' => Yii::t('app', 'Topic ID'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'title' => Yii::t('app', 'Title'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'desc' => Yii::t('app', 'Desc'),
            'media' => Yii::t('app', 'Media'),
            'media_type' => Yii::t('app', 'Media Type'),
            'media_extension' => Yii::t('app', 'Media Extension'),
            'media_weight' => Yii::t('app', 'Media Weight'),
            'media_long' => Yii::t('app', 'Media Long'),
            'media_unit' => Yii::t('app', 'Media Unit'),
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
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[deleted_by0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[Topic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topics::className(), ['id' => 'topic_id']);
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