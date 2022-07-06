<?php

namespace app\models\mains\generals;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string|null $code
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
            [['user_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['roles', 'schools', 'year_of_graduates', 'sort_desc', 'desc', 'content'], 'string'],
            [['code'], 'string', 'max' => 15],
            [['title', 'read_more_uri'], 'string', 'max' => 255],
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
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
}
