<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rkat_item".
 *
 * @property int $id
 * @property int|null $rkat_id
 * @property int|null $juknis_relation_id
 * @property int|null $amount_estimate
 * @property string|null $validations
 * @property string|null $validation_level
 * @property string|null $note
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property JuknisRelation $juknisRelation
 * @property Rkat $rkat
 */
class RkatItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rkat_item';
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
            [['rkat_id', 'juknis_relation_id', 'amount_estimate', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['validations', 'validation_level', 'note'], 'string'],
            [['rkat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rkat::class, 'targetAttribute' => ['rkat_id' => 'juknis_id']],
            [['juknis_relation_id'], 'exist', 'skipOnError' => true, 'targetClass' => JuknisRelation::class, 'targetAttribute' => ['juknis_relation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rkat_id' => Yii::t('app', 'Rkat ID'),
            'juknis_relation_id' => Yii::t('app', 'Juknis Relation ID'),
            'amount_estimate' => Yii::t('app', 'Amount Estimate'),
            'validations' => Yii::t('app', 'Validations'),
            'validation_level' => Yii::t('app', 'Validation Level'),
            'note' => Yii::t('app', 'Note'),
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
     * Gets query for [[JuknisRelation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknisRelation()
    {
        return $this->hasOne(JuknisRelation::class, ['id' => 'juknis_relation_id']);
    }

    /**
     * Gets query for [[Rkat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkat()
    {
        return $this->hasOne(Rkat::class, ['id' => 'rkat_id']);
    }

    // public function getPurchases()
    // {
    //     return $this->hasMany(PurchaseReport::class, ['rkat_item_id' => 'id']);
    // }

    public function getDisbursements()
    {
        return $this->hasMany(Disbursement::class, ['rkat_item_id' => 'id'])
            ->andOnCondition(['status' => 1])
            ->andOnCondition(['is', 'deleted_at', new \yii\db\Expression('null')]);
    }

    public function getRemainingFunds($status = false)
    {
        //status = jika true, maka menghitung berdasarkan yg sudah dicairkan / validasi true
        //jika status = false, maka mengitung, sisa = total - total pengajuan, include yg belum divalidasi
        $disbursement = Disbursement::find()
            ->where(['rkat_item_id' => $this->id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->andWhere(['status' => 1])
            ->andWhere($status ? ['validation_level' => 'treasurer'] : "1")
            ->all();
        $total = 0;
        foreach ($disbursement as $key => $value) :
            $total += $value->amount_approved ? $value->amount_approved : $value->amount_request;
        endforeach;

        return $this->amount_estimate - $total;
    }
}