<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "juknis_item".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $parent_id
 * @property string|null $value
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property JuknisRelation[] $juknisRelations
 * @property Juknis $parent
 */
class JuknisItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'juknis_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required', 'on' => 'item-juknis'],
            [['parent_id'], 'required', 'on' => 'item-juknis'],
            [['parent_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['value'], 'string'],
            [['code'], 'string', 'max' => 15],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => JuknisItem::class, 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'value' => Yii::t('app', 'Value'),
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
     * Gets query for [[JuknisRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknisRelations()
    {
        return $this->hasMany(JuknisRelation::class, ['juknis_item_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(JuknisItem::class, ['id' => 'parent_id']);
    }
}