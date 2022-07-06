<?php

namespace app\models\mains\generals;

use app\models\identities\Users;
use Yii;

/**
 * This is the model class for table "classes".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $thumbnail
 * @property string|null $title
 * @property string|null $short_desc
 * @property string|null $desc
 * @property string|null $main_video
 * @property string|null $main_file
 * @property int|null $mentor_id
 * @property string|null $class_begin
 * @property string|null $class_end
 * @property int|null $days_in_class
 * @property string|null $certificate_file
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Bookings[] $bookings
 * @property ClassMembers[] $classMembers
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Discussions[] $discussions
 * @property MeetSchedules[] $meetSchedules
 * @property Users $mentor
 * @property Topics[] $topics
 * @property Users $updatedBy
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classes';
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
            [['short_desc', 'desc'], 'string'],
            [['mentor_id', 'days_in_class', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['class_begin', 'class_end'], 'safe'],
            [['code'], 'string', 'max' => 20],
            [['thumbnail', 'title', 'main_video', 'main_file', 'certificate_file'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['mentor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['mentor_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'title' => Yii::t('app', 'Title'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'desc' => Yii::t('app', 'Desc'),
            'main_video' => Yii::t('app', 'Main Video'),
            'main_file' => Yii::t('app', 'Main File'),
            'mentor_id' => Yii::t('app', 'Mentor ID'),
            'class_begin' => Yii::t('app', 'Class Begin'),
            'class_end' => Yii::t('app', 'Class End'),
            'days_in_class' => Yii::t('app', 'Days In Class'),
            'certificate_file' => Yii::t('app', 'Certificate File'),
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
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::className(), ['class_id' => 'id']);
    }

    /**
     * Gets query for [[ClassMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassMembers()
    {
        return $this->hasMany(ClassMembers::className(), ['class_id' => 'id']);
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
     * Gets query for [[Discussions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussions()
    {
        return $this->hasMany(Discussions::className(), ['class_id' => 'id']);
    }

    /**
     * Gets query for [[MeetSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetSchedules()
    {
        return $this->hasMany(MeetSchedules::className(), ['class_id' => 'id']);
    }

    /**
     * Gets query for [[Mentor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentor()
    {
        return $this->hasOne(Users::className(), ['id' => 'mentor_id']);
    }

    /**
     * Gets query for [[Topics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topics::className(), ['class_id' => 'id']);
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