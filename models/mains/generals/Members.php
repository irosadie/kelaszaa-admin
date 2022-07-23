<?php

namespace app\models\mains\generals;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $full_name
 * @property string|null $auth_key
 * @property string|null $password_hash
 * @property string|null $register_token
 * @property string|null $password_reset_token
 * @property string|null $email
 * @property string|null $phone
 * @property int $avatar
 * @property string|null $born_in
 * @property string|null $born_at
 * @property int|null $gender
 * @property string|null $address
 * @property string|null $agency
 * @property string|null $agency_address
 * @property string|null $agency_phone
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Certificates[] $certificates
 * @property ClassMembers[] $classMembers
 * @property Discussions[] $discussions
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members';
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
            [['gender', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['born_at', 'biography', 'avatar'], 'safe'],
            ['created_by', 'default', 'value' => Yii::$app->user->id],
            ['updated_by', 'default', 'value' => Yii::$app->user->id, 'when' => function ($model) {
                return !$model->isNewRecord;
            }],
            ['deleted_at', 'default', 'value' => time(), 'on' => 'delete'],
            ['deleted_by', 'default', 'value' => Yii::$app->user->id, 'on' => 'delete'],
            [['biography'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['password_hash'], function ($attribute, $params) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
                return;
            }, 'when' => function ($model) {
                return $model->isNewRecord;
            }],
            [['username', 'password_hash', 'password_reset_token', 'born_in', 'address', 'agency', 'agency_address'], 'string', 'max' => 255],
            [['full_name'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 32],
            [['register_token'], 'string', 'max' => 225],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 16],
            [['agency_phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'full_name' => Yii::t('app', 'Full Name'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'register_token' => Yii::t('app', 'Register Token'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'avatar' => Yii::t('app', 'Avatar'),
            'born_in' => Yii::t('app', 'Born In'),
            'born_at' => Yii::t('app', 'Born At'),
            'gender' => Yii::t('app', 'Gender'),
            'address' => Yii::t('app', 'Address'),
            'agency' => Yii::t('app', 'Agency'),
            'agency_address' => Yii::t('app', 'Agency Address'),
            'agency_phone' => Yii::t('app', 'Agency Phone'),
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
     * Gets query for [[Certificates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates()
    {
        return $this->hasMany(Certificates::className(), ['member_id' => 'id']);
    }

    /**
     * Gets query for [[ClassMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers()
    {
        return $this->hasMany(ClassMembers::className(), ['member_id' => 'id']);
    }

    /**
     * Gets query for [[Discussions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussions()
    {
        return $this->hasMany(Discussions::className(), ['member_id' => 'id']);
    }
}