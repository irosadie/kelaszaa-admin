<?php

namespace app\models\mains\generals;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $table
 * @property string|null $activity
 * @property string|null $data_before
 * @property string|null $data_inserted
 * @property int|null $time
 * @property int|null $user_id
 * @property string|null $user_role
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
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
            [['data_before', 'data_inserted'], 'string'],
            [['time', 'user_id'], 'integer'],
            [['code'], 'string', 'max' => 15],
            [['table', 'activity', 'user_role'], 'string', 'max' => 255],
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
            'table' => Yii::t('app', 'Table'),
            'activity' => Yii::t('app', 'Activity'),
            'data_before' => Yii::t('app', 'Data Before'),
            'data_inserted' => Yii::t('app', 'Data Inserted'),
            'time' => Yii::t('app', 'Time'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_role' => Yii::t('app', 'User Role'),
        ];
    }
}
