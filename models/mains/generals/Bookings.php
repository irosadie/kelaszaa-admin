<?php

namespace app\models\mains\generals;

use Yii;

/**
 * This is the model class for table "bookings".
 *
 * @property int $id
 * @property string $code
 * @property int|null $class_id
 * @property int|null $member_id
 * @property string $title_in_booking
 * @property int|null $coupon_code
 * @property string|null $discount_type
 * @property int|null $discount_percentage
 * @property int|null $discount_amount
 * @property int|null $price_normal
 * @property int|null $price_in_booking
 * @property int|null $price_paid
 * @property string|null $xendit_data
 * @property int|null $payment_method_id
 * @property int|null $valid_until
 * @property int|null $is_confirmed
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 * @property int|null $updated_by
 *
 * @property Classes $class
 * @property ClassMembers[] $classMembers
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property PaymentMethods $paymentMethod
 * @property Users $updatedBy
 */
class Bookings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookings';
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
            [['code', 'title_in_booking'], 'required'],
            [['class_id', 'member_id', 'coupon_code', 'discount_percentage', 'discount_amount', 'price_normal', 'price_in_booking', 'price_paid', 'payment_method_id', 'valid_until', 'is_confirmed', 'status', 'created_by', 'updated_at', 'created_at', 'deleted_at', 'deleted_by', 'updated_by'], 'integer'],
            [['discount_type', 'xendit_data'], 'string'],
            [['code'], 'string', 'max' => 20],
            [['title_in_booking'], 'string', 'max' => 255],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethods::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
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
            'class_id' => Yii::t('app', 'Class ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'title_in_booking' => Yii::t('app', 'Title In Booking'),
            'coupon_code' => Yii::t('app', 'Coupon Code'),
            'discount_type' => Yii::t('app', 'Discount Type'),
            'discount_percentage' => Yii::t('app', 'Discount Percentage'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'price_normal' => Yii::t('app', 'Price Normal'),
            'price_in_booking' => Yii::t('app', 'Price In Booking'),
            'price_paid' => Yii::t('app', 'Price Paid'),
            'xendit_data' => Yii::t('app', 'Xendit Data'),
            'payment_method_id' => Yii::t('app', 'Payment Method ID'),
            'valid_until' => Yii::t('app', 'Valid Until'),
            'is_confirmed' => Yii::t('app', 'Is Confirmed'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Class]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * Gets query for [[ClassMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers()
    {
        return $this->hasMany(ClassMembers::className(), ['booking_id' => 'id']);
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
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethods::className(), ['id' => 'payment_method_id']);
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
