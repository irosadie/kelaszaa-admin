<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "disbursement_master".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $schools
 * @property string $disbursement_in_year
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $craeted_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property DisbursementPlan[] $disbursementPlans
 */
class DisbursementMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'disbursement_master';
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
            [['name', 'desc', 'schools', 'disbursement_in_year'], 'required'],
            [['desc', 'disbursement_in_year'], 'string'],
            [['status', 'created_at', 'craeted_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 255],
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
            'schools' => Yii::t('app', 'Schools'),
            'disbursement_in_year' => Yii::t('app', 'Disbursement In Year'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'craeted_by' => Yii::t('app', 'Craeted By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[DisbursementPlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisbursementPlans()
    {
        return $this->hasMany(DisbursementPlan::class, ['disbursement_master_id' => 'id']);
    }
}