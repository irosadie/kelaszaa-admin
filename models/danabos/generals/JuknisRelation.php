<?php

namespace app\models\danabos\generals;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "juknis_relation".
 *
 * @property int $id
 * @property int|null $juknis_id
 * @property int|null $juknis_item_id
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Juknis $juknis
 * @property JuknisItem $juknisItem
 * @property RkatItem[] $rkatItems
 */
class JuknisRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'juknis_relation';
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
            [['juknis_id', 'juknis_item_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['juknis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Juknis::class, 'targetAttribute' => ['juknis_id' => 'id']],
            [['juknis_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => JuknisItem::class, 'targetAttribute' => ['juknis_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'juknis_id' => Yii::t('app', 'Juknis ID'),
            'juknis_item_id' => Yii::t('app', 'Juknis Item ID'),
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
     * Gets query for [[JuknisItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuknisItem()
    {
        return $this->hasOne(JuknisItem::class, ['id' => 'juknis_item_id']);
    }

    /**
     * Gets query for [[RkatItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRkatItems()
    {
        return $this->hasMany(RkatItem::class, ['juknis_relation_id' => 'id']);
    }
}