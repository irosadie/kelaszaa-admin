<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_report".
 *
 * @property int $id
 * @property int|null $disbursement_id
 * @property string|null $item_name
 * @property int|null $item_total
 * @property int|null $item_price
 * @property int|null $amount_total
 * @property string|null $photos
 * @property string|null $proof_of_payments
 * @property string|null $desc
 * @property string|null $store_name
 * @property string|null $store_address
 * @property string|null $store_phone
 * @property int|null $created_at
 * @property int|null $craeted_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property RkatItem $rkatItem
 */
class PurchaseReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_report';
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
            [['disbursement_id', 'item_total', 'item_price', 'created_at', 'craeted_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['item_name', 'date', 'unit_str', 'item_total', 'amount_total', 'desc', 'store_name', 'store_phone', 'store_address'], 'required'],
            [['photos', 'desc'], 'string'],
            [['item_name', 'proof_of_payments', 'store_name', 'store_address'], 'string', 'max' => 255],
            [['store_phone'], 'string', 'max' => 15],
            [['date', 'unit_str'], 'safe'],
            [['disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Disbursement::class, 'targetAttribute' => ['disbursement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'disbursement_id' => Yii::t('app', 'Disbursement ID'),
            'item_name' => Yii::t('app', 'Item Name'),
            'item_total' => Yii::t('app', 'Item Total'),
            'item_price' => Yii::t('app', 'Item Price'),
            'amount_total' => Yii::t('app', 'Amount Total'),
            'photos' => Yii::t('app', 'Photos'),
            'proof_of_payments' => Yii::t('app', 'Proof Of Payments'),
            'desc' => Yii::t('app', 'Desc'),
            'store_name' => Yii::t('app', 'Store Name'),
            'store_address' => Yii::t('app', 'Store Address'),
            'store_phone' => Yii::t('app', 'Store Phone'),
            'created_at' => Yii::t('app', 'Created At'),
            'craeted_by' => Yii::t('app', 'Craeted By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[RkatItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkatItem()
    {
        return $this->hasOne(RkatItem::class, ['id' => 'disbursement_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Setting::class, ['id' => 'unit_id']);
    }

    public function getTotalPurchase()
    {
        return self::find()->where(['disbursement_id' => $this->disbursement_id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->sum('amount_total');
    }
}