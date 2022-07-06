<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "disbursement".
 *
 * @property int $id
 * @property int|null $rkat_item_id
 * @property int|null $disbursement_plan_id
 * @property string|null $desc
 * @property int|null $percentage
 * @property int|null $amount_request
 * @property string|null $validations
 * @property string|null $validation_level
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 * @property int|null $updated_by
 *
 * @property DisbursementPlan $disbursementPlan
 * @property RkatItem $rkatItem
 */
class Disbursement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'disbursement';
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
            [['rkat_item_id', 'disbursement_plan_id', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by', 'updated_by'], 'integer'],
            [['desc', 'validations', 'validation_level'], 'string'],
            [['percentage'], 'string'],
            [['rkat_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => RkatItem::class, 'targetAttribute' => ['rkat_item_id' => 'id']],
            [['disbursement_plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisbursementPlan::class, 'targetAttribute' => ['disbursement_plan_id' => 'id']],
            [['status', 'amount_request', 'amount_approved'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rkat_item_id' => Yii::t('app', 'Rkat Item ID'),
            'disbursement_plan_id' => Yii::t('app', 'Disbursement Plan ID'),
            'desc' => Yii::t('app', 'Desc'),
            'percentage' => Yii::t('app', 'Percentage'),
            'amount_request' => Yii::t('app', 'Amount'),
            'validations' => Yii::t('app', 'Validations'),
            'validation_level' => Yii::t('app', 'Validation Level'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[DisbursementPlan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisbursementPlan()
    {
        return $this->hasOne(DisbursementPlan::class, ['id' => 'disbursement_plan_id']);
    }

    /**
     * Gets query for [[RkatItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkatItem()
    {
        return $this->hasOne(RkatItem::class, ['id' => 'rkat_item_id']);
    }

    /**
     * Gets query for [[PurchaseReports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseReports()
    {
        return $this->hasMany(PurchaseReport::class, ['disbursement_id' => 'id'])
            ->andOnCondition(['is', 'deleted_at', new \yii\db\Expression('null')]);
    }
}