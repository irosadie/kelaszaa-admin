<?php

namespace app\models\danabos\generals;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string|null $table
 * @property string|null $activity
 * @property string|null $data_before
 * @property string|null $data_inserted
 * @property int|null $time
 * @property int|null $user_id
 * @property string|null $user_role
 * @property string|null $user_role_auth
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_before', 'data_inserted'], 'string'],
            [['time', 'user_id'], 'integer'],
            [['table', 'activity', 'user_role', 'user_role_auth'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'table' => Yii::t('app', 'Table'),
            'activity' => Yii::t('app', 'Activity'),
            'data_before' => Yii::t('app', 'Data Before'),
            'data_inserted' => Yii::t('app', 'Data Inserted'),
            'time' => Yii::t('app', 'Time'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_role' => Yii::t('app', 'User Role'),
            'user_role_auth' => Yii::t('app', 'User Role Auth'),
        ];
    }
}
