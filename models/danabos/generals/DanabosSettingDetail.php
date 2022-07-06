<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "danabos_setting_detail".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $school_id
 * @property int|null $student_total_estimate
 * @property int|null $funds_per_person_estimate
 * @property int|null $total_amount_estimate
 * @property int|null $disbursement_date_estimate
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class DanabosSettingDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'danabos_setting_detail';
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
            [['desc'], 'string'],
            [['student_total_estimate', 'funds_per_person_estimate', 'total_amount_estimate', 'disbursement_date_estimate', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 255],
            [['school_id'], 'string', 'max' => 100],
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
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'school_id' => Yii::t('app', 'School ID'),
            'student_total_estimate' => Yii::t('app', 'Student Total Estimate'),
            'funds_per_person_estimate' => Yii::t('app', 'Funds Per Person Estimate'),
            'total_amount_estimate' => Yii::t('app', 'Total Amount Estimate'),
            'disbursement_date_estimate' => Yii::t('app', 'Disbursement Date Estimate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
}