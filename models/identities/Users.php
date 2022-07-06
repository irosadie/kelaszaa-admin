<?php

namespace app\models\identities;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
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
 * @property int $status
 * @property string|null $role
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Bookings[] $bookings
 * @property Bookings[] $bookings0
 * @property Bookings[] $bookings1
 * @property Certificates[] $certificates
 * @property Certificates[] $certificates0
 * @property Certificates[] $certificates1
 * @property ClassMembers[] $classMembers
 * @property ClassMembers[] $classMembers0
 * @property ClassMembers[] $classMembers1
 * @property Classes[] $classes
 * @property Classes[] $classes0
 * @property Classes[] $classes1
 * @property Classes[] $classes2
 * @property Discussions[] $discussions
 * @property LearningMaterials[] $learningMaterials
 * @property LearningMaterials[] $learningMaterials0
 * @property LearningMaterials[] $learningMaterials1
 * @property MeetSchedules[] $meetSchedules
 * @property MeetSchedules[] $meetSchedules0
 * @property MeetSchedules[] $meetSchedules1
 * @property Topics[] $topics
 * @property Topics[] $topics0
 * @property Topics[] $topics1
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $new_password, $repeat_password, $old_password, $password;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = -1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
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
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['full_name', 'address', 'gender', 'born_in', 'born_at', 'phone', 'email'], 'required'],
            [['password_hash'], 'required', 'on' => ['mentor-create']],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['full_name'], 'string', 'max' => 128],
            [['auth_key', 'role'], 'string', 'max' => 32],
            [['register_token'], 'string', 'max' => 225],
            [['email'], 'string', 'max' => 100],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password_hash'], function ($attribute, $params) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
                return;
            }, 'on' => ['mentor-create']],
            [['username'], function ($attribute, $params) {
                $this->username = $this->email;
                return;
            }, 'on' => ['mentor-create']],
            [['biography'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['phone'], 'string', 'max' => 16],
            [['born_in', 'born_at', 'address', 'gender', 'avatar', 'biography', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'safe'],
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
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => hash('sha256', $token)]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */

    public function oldPassword($attribute, $params)
    {
        $_user     = self::findOne(Yii::$app->user->id);
        $_validate = Yii::$app->security->validatePassword($this->old_password, $_user->password_hash);
        if (!$_validate) {
            $this->addError($attribute, 'Old password is wrong.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Bookings0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings0()
    {
        return $this->hasMany(Bookings::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Bookings1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings1()
    {
        return $this->hasMany(Bookings::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Certificates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates()
    {
        return $this->hasMany(Certificates::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Certificates0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates0()
    {
        return $this->hasMany(Certificates::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Certificates1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates1()
    {
        return $this->hasMany(Certificates::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[ClassMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers()
    {
        return $this->hasMany(ClassMembers::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ClassMembers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers0()
    {
        return $this->hasMany(ClassMembers::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[ClassMembers1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers1()
    {
        return $this->hasMany(ClassMembers::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Classes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClasses()
    {
        return $this->hasMany(Classes::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Classes0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClasses0()
    {
        return $this->hasMany(Classes::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Classes1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClasses1()
    {
        return $this->hasMany(Classes::className(), ['mentor_id' => 'id']);
    }

    /**
     * Gets query for [[Classes2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClasses2()
    {
        return $this->hasMany(Classes::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Discussions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussions()
    {
        return $this->hasMany(Discussions::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[LearningMaterials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLearningMaterials()
    {
        return $this->hasMany(LearningMaterials::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[LearningMaterials0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLearningMaterials0()
    {
        return $this->hasMany(LearningMaterials::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[LearningMaterials1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLearningMaterials1()
    {
        return $this->hasMany(LearningMaterials::className(), ['deleted-by' => 'id']);
    }

    /**
     * Gets query for [[MeetSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetSchedules()
    {
        return $this->hasMany(MeetSchedules::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MeetSchedules0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetSchedules0()
    {
        return $this->hasMany(MeetSchedules::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[MeetSchedules1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetSchedules1()
    {
        return $this->hasMany(MeetSchedules::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Topics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topics::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Topics0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics0()
    {
        return $this->hasMany(Topics::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Topics1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics1()
    {
        return $this->hasMany(Topics::className(), ['updated_by' => 'id']);
    }
}