<?php

namespace app\models\danabos\generals;

use Yii;
use app\models\smart\generals\PublicSekolah;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rkat".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property int|null $juknis_id
 * @property float|null $school_year_id
 * @property string|null $year
 * @property string|null $school_id
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Juknis $juknis
 * @property RkatItem[] $rkatItems
 */
class Rkat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rkat';
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
            [['id', 'juknis_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['juknis_id', 'year', 'name', 'school_id'], 'required'],
            [['desc'], 'string'],
            [['school_year_id'], 'number'],
            [['year'], 'safe'],
            [['code'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 255],
            [['school_id'], 'string', 'max' => 100],
            [['id'], 'unique'],
            [['juknis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Juknis::class, 'targetAttribute' => ['juknis_id' => 'id']],
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
            'juknis_id' => Yii::t('app', 'Juknis ID'),
            'school_year_id' => Yii::t('app', 'School Year ID'),
            'year' => Yii::t('app', 'Year'),
            'school_id' => Yii::t('app', 'School ID'),
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
     * Gets query for [[Juknis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknis()
    {
        return $this->hasOne(Juknis::class, ['id' => 'juknis_id']);
    }

    /**
     * Gets query for [[School]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(PublicSekolah::class, ['sekolah_id' => 'school_id']);
    }

    /**
     * Gets query for [[RkatItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkatItems()
    {
        return $this->hasMany(RkatItem::class, ['rkat_id' => 'juknis_id']);
    }
}