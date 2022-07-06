<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "juknis".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $schools
 * @property float|null $school_year_id
 * @property string|null $year
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property JuknisItem[] $juknisItems
 * @property JuknisRelation[] $juknisRelations
 * @property Rkat[] $rkats
 */
class Juknis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'juknis';
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
            [['name'], 'required'],
            [['desc'], 'string'],
            [['school_year_id'], 'number'],
            [['year', 'schools'], 'safe'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 255]
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
            'school_year_id' => Yii::t('app', 'School Year ID'),
            'year' => Yii::t('app', 'Year'),
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
     * Gets query for [[JuknisItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknisItems()
    {
        return $this->hasMany(JuknisItem::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[JuknisRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknisRelations()
    {
        return $this->hasMany(JuknisRelation::class, ['juknis_id' => 'id']);
    }

    /**
     * Gets query for [[Rkats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkats()
    {
        return $this->hasMany(Rkat::class, ['juknis_id' => 'id']);
    }
}