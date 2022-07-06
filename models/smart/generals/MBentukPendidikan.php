<?php

namespace app\models\smart\generals;

use Yii;

/**
 * This is the model class for table "m_bentuk_pendidikan".
 *
 * @property int $bentuk_pendidikan_id
 * @property string $nama
 * @property string|null $direktorat_pembinaan
 * @property float $jenjang_pendidikan_id
 * @property float $aktif
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $soft_delete
 * @property int|null $created_by
 *
 * @property HakAkses $createdBy
 * @property MJenjangPendidikan $jenjangPendidikan
 * @property PublicSekolah[] $publicSekolahs
 */
class MBentukPendidikan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_bentuk_pendidikan';
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
            [['bentuk_pendidikan_id', 'nama', 'jenjang_pendidikan_id', 'aktif'], 'required'],
            [['bentuk_pendidikan_id', 'soft_delete', 'created_by'], 'integer'],
            [['jenjang_pendidikan_id', 'aktif'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 50],
            [['direktorat_pembinaan'], 'string', 'max' => 40],
            [['bentuk_pendidikan_id'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => HakAkses::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['jenjang_pendidikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJenjangPendidikan::className(), 'targetAttribute' => ['jenjang_pendidikan_id' => 'jenjang_pendidikan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bentuk_pendidikan_id' => Yii::t('app', 'Bentuk Pendidikan ID'),
            'nama' => Yii::t('app', 'Nama'),
            'direktorat_pembinaan' => Yii::t('app', 'Direktorat Pembinaan'),
            'jenjang_pendidikan_id' => Yii::t('app', 'Jenjang Pendidikan ID'),
            'aktif' => Yii::t('app', 'Aktif'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(HakAkses::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[JenjangPendidikan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenjangPendidikan()
    {
        return $this->hasOne(MJenjangPendidikan::className(), ['jenjang_pendidikan_id' => 'jenjang_pendidikan_id']);
    }

    /**
     * Gets query for [[PublicSekolahs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicSekolahs()
    {
        return $this->hasMany(PublicSekolah::className(), ['bentuk_pendidikan_id' => 'bentuk_pendidikan_id']);
    }
}
