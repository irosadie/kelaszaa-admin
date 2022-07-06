<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "disbursement_plan".
 *
 * @property int $id
 * @property int|null $disbursement_master_id
 * @property string|null $name
 * @property string|null $desc
 * @property int|null $percentage_estimate
 * @property int|null $amount_estimate
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property DisbursementMaster $disbursementMaster
 */
class DisbursementPlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'disbursement_plan';
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
            [['disbursement_master_id', 'percentage_estimate', 'amount_estimate', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['disbursement_master_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisbursementMaster::class, 'targetAttribute' => ['disbursement_master_id' => 'id']],
            [['month'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'disbursement_master_id' => Yii::t('app', 'Disbursement Master ID'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'percentage_estimate' => Yii::t('app', 'Percentage Estimate'),
            'amount_estimate' => Yii::t('app', 'Amount Estimate'),
            'month' => Yii::t('app', 'Month'),
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
     * Gets query for [[DisbursementMaster]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisbursementMaster()
    {
        return $this->hasOne(DisbursementMaster::class, ['id' => 'disbursement_master_id']);
    }
}