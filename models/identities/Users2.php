<?php

namespace app\models\identities;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "hak_akses".
 *
 * @property int $id
 * @property string $username
 * @property string|null $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email
 * @property int $status
 * @property int|null $role
 * @property string $table_name
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $login_at
 * @property string|null $login_platform
 * @property string|null $secret_key
 *
 * @property BelMMusic[] $belMMusics
 * @property DaMasterKategori[] $daMasterKategoris
 * @property DaPjPekerjaan[] $daPjPekerjaans
 * @property DaTaskDetail[] $daTaskDetails
 * @property DaTask[] $daTasks
 * @property JenisTunjangan[] $jenisTunjangans
 * @property KisiIndikatorPencapaianKompetensi[] $kisiIndikatorPencapaianKompetensis
 * @property KisiJawaban[] $kisiJawabans
 * @property KisiKompetensiDasar[] $kisiKompetensiDasars
 * @property KisiMateri[] $kisiMateris
 * @property KisiSoal[] $kisiSoals
 * @property KisiStandarKompetensi[] $kisiStandarKompetensis
 * @property MAlasanLayakPip[] $mAlasanLayakPips
 * @property MAlatTransportasi[] $mAlatTransportasis
 * @property MBank[] $mBanks
 * @property MBentukPendidikan[] $mBentukPendidikans
 * @property MBidangStudi[] $mBidangStudis
 * @property MBkg[] $mBkgs
 * @property MGelarAkademik[] $mGelarAkademiks
 * @property MJabatanFungsional[] $mJabatanFungsionals
 * @property MJabatanTugasPtk[] $mJabatanTugasPtks
 * @property MJenisBeasiswa[] $mJenisBeasiswas
 * @property MJenisDiklat[] $mJenisDiklats
 * @property MJenisHapusBuku[] $mJenisHapusBukus
 * @property MJenisKeluar[] $mJenisKeluars
 * @property MJenisLembaga[] $mJenisLembagas
 * @property MJenisPenghargaan[] $mJenisPenghargaans
 * @property MJenisPrasarana[] $mJenisPrasaranas
 * @property MJenisPtk[] $mJenisPtks
 * @property MJenisRombel[] $mJenisRombels
 * @property MJenisSertifikasi[] $mJenisSertifikasis
 * @property MJenisTinggal[] $mJenisTinggals
 * @property MJenjangPendidikan[] $mJenjangPendidikans
 * @property MJurusan[] $mJurusans
 * @property MKeahlianLaboratorium[] $mKeahlianLaboratoria
 * @property MKelompokBidang[] $mKelompokBidangs
 * @property MKurikulum[] $mKurikulums
 * @property MLembagaPengangkat[] $mLembagaPengangkats
 * @property MLevelWilayah[] $mLevelWilayahs
 * @property MMstWilayah[] $mMstWilayahs
 * @property MNegara[] $mNegaras
 * @property MPangkatGolongan[] $mPangkatGolongans
 * @property MPekerjaan[] $mPekerjaans
 * @property MPenghasilanOrangtuaWali[] $mPenghasilanOrangtuaWalis
 * @property MSemester[] $mSemesters
 * @property MStatusAnak[] $mStatusAnaks
 * @property MStatusKeaktifanPegawai[] $mStatusKeaktifanPegawais
 * @property MStatusKepegawaian[] $mStatusKepegawaians
 * @property MStatusKepemilikanSarpras[] $mStatusKepemilikanSarpras
 * @property MStatusKepemilikan[] $mStatusKepemilikans
 * @property MSumberGaji[] $mSumberGajis
 * @property MTahunAjaran[] $mTahunAjarans
 * @property MTingkatPendidikan[] $mTingkatPendidikans
 * @property MTingkatPenghargaan[] $mTingkatPenghargaans
 * @property NotifPegawai[] $notifPegawais
 * @property NotifSiswa[] $notifSiswas
 * @property PegawaiBidangSdm[] $pegawaiBidangSdms
 * @property PegawaiBuku[] $pegawaiBukus
 * @property PegawaiDiklat[] $pegawaiDiklats
 * @property PegawaiKaryaTulis[] $pegawaiKaryaTulis
 * @property PegawaiKesejahteraan[] $pegawaiKesejahteraans
 * @property PegawaiNilaiTest[] $pegawaiNilaiTests
 * @property PegawaiRiwayatGajiBerkala[] $pegawaiRiwayatGajiBerkalas
 * @property PegawaiRwyFungsional[] $pegawaiRwyFungsionals
 * @property PegawaiRwyKepangkatan[] $pegawaiRwyKepangkatans
 * @property PegawaiRwyKerja[] $pegawaiRwyKerjas
 * @property PegawaiRwyPendFormal[] $pegawaiRwyPendFormals
 * @property PegawaiRwySertifikasi[] $pegawaiRwySertifikasis
 * @property PegawaiRwyStruktural[] $pegawaiRwyStrukturals
 * @property PegawaiTugasTambahan[] $pegawaiTugasTambahans
 * @property PegawaiTunjangan[] $pegawaiTunjangans
 * @property PenjadwalanAlgenRecord[] $penjadwalanAlgenRecords
 * @property PenjadwalanJadwalAwal[] $penjadwalanJadwalAwals
 * @property PenjadwalanPtkBerhalangan[] $penjadwalanPtkBerhalangans
 * @property PenjadwalanSimpanRecord[] $penjadwalanSimpanRecords
 * @property PenjadwalanTmpGenerate[] $penjadwalanTmpGenerates
 * @property PpdbGelombang[] $ppdbGelombangs
 * @property PublicJurusanSp[] $publicJurusanSps
 * @property PublicPegawai[] $publicPegawais
 * @property PublicPegawai[] $publicPegawais0
 * @property PublicPembelajaran[] $publicPembelajarans
 * @property PublicPesertaDidikBaru[] $publicPesertaDidikBarus
 * @property PublicPesertaDidikLongitudinal[] $publicPesertaDidikLongitudinals
 * @property PublicPesertaDidik[] $publicPesertaDidiks
 * @property PublicPrasarana[] $publicPrasaranas
 * @property PublicPtkTerdaftar[] $publicPtkTerdaftars
 * @property PublicPtk[] $publicPtks
 * @property PublicRombonganBelajar[] $publicRombonganBelajars
 * @property PublicSekolahLongitudinal[] $publicSekolahLongitudinals
 * @property PublicYayasan[] $publicYayasans
 * @property RelasiPegawaiRekeningBank[] $relasiPegawaiRekeningBanks
 * @property StaffItemBkg[] $staffItemBkgs
 * @property StaffKomentarBkg[] $staffKomentarBkgs
 * @property StaffRiwayatBkg[] $staffRiwayatBkgs
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
        return 'hak_akses';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'status', 'table_name'], 'required'],
            [['status', 'role'], 'integer'],
            [['created_at', 'updated_at', 'login_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email', 'table_name', 'login_platform'], 'string', 'max' => 100],
            [['secret_key'], 'string', 'max' => 50],
            [['email'], 'unique'],
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
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
            'table_name' => Yii::t('app', 'Table Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'login_at' => Yii::t('app', 'Login At'),
            'login_platform' => Yii::t('app', 'Login Platform'),
            'secret_key' => Yii::t('app', 'Secret Key'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        //return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        $hakAkses = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);

        if ($hakAkses && $hakAkses->table_name == 'pegawai') {
            return static::find()
                ->joinWith("pegawaiU")
                ->where(['id' => $id, 'hak_akses.status' => self::STATUS_ACTIVE, "soft_delete" => 0, "status_pegawai" => "aktif"])
                ->one();
        } else {
            return $hakAkses;
        }
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $hakAkses = static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        if ($hakAkses->table_name == 'pegawai') {
            return static::find()
                ->joinWith("pegawaiU")
                ->where(['username' => $username, 'hak_akses.status' => self::STATUS_ACTIVE, "soft_delete" => 0, "status_pegawai" => "aktif"])
                ->one();
        } else {
            return $hakAkses;
        }
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
        // return static::findOne([
        //     'password_reset_token' => $token,
        //     'status' => self::STATUS_ACTIVE,
        // ]);

        return static::find()
            ->joinWith("pegawaiU")
            ->where(['password_reset_token' => $token, 'hak_akses.status' => self::STATUS_ACTIVE, "soft_delete" => 0])
            ->one();
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
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public static function findIdentity($id)
    // {
    //     return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public static function findIdentityByAccessToken($token, $type = null)
    // {
    //     return static::findOne( ['access_token' => hash( 'sha256' , $token)]);
    // }

    // /**
    //  * Finds user by username
    //  *
    //  * @param string $username
    //  * @return static|null
    //  */
    // public static function findByUsername($username)
    // {
    //     return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    // }

    // /**
    //  * Finds user by password reset token
    //  *
    //  * @param string $token password reset token
    //  * @return static|null
    //  */
    // public static function findByPasswordResetToken($token)
    // {
    //     if (!static::isPasswordResetTokenValid($token)) {
    //         return null;
    //     }

    //     return static::findOne([
    //         'password_reset_token' => $token,
    //         'status' => self::STATUS_ACTIVE,
    //     ]);
    // }

    // /**
    //  * Finds user by verification email token
    //  *
    //  * @param string $token verify email token
    //  * @return static|null
    //  */
    // public static function findByVerificationToken($token) {
    //     return static::findOne([
    //         'verification_token' => $token,
    //         'status' => self::STATUS_INACTIVE
    //     ]);
    // }

    // /**
    //  * Finds out if password reset token is valid
    //  *
    //  * @param string $token password reset token
    //  * @return bool
    //  */
    // public static function isPasswordResetTokenValid($token)
    // {
    //     if (empty($token)) {
    //         return false;
    //     }

    //     $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    //     $expire = Yii::$app->params['passwordResetTokenExpire'];
    //     return $timestamp + $expire >= time();
    // }

    // /**
    //  * Validates password
    //  *
    //  * @param string $password password to validate
    //  * @return bool if password provided is valid for current user
    //  */

    // public function oldPassword($attribute, $params)
    // {
    //     $_user     = self::findOne(Yii::$app->user->id);
    //     $_validate = Yii::$app->security->validatePassword($this->old_password, $_user->password_hash);
    //     if(!$_validate){
    //         $this->addError($attribute, 'Old password is wrong.');
    //     }
    // }

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
        $enc = Yii::$app->encryptor;
        return $password == $enc->decode($this->password_hash);
        // return Yii::$app->security->validatePassword($password, $this->password_hash);
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
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiU()
    {
        return $this->hasOne(\app\models\smart\generals\PublicPegawai::class, ['hak_akses' => 'id']);
    }

    /**
     * Gets query for [[BelMMusics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBelMMusics()
    {
        return $this->hasMany(BelMMusic::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[DaMasterKategoris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaMasterKategoris()
    {
        return $this->hasMany(DaMasterKategori::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[DaPjPekerjaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaPjPekerjaans()
    {
        return $this->hasMany(DaPjPekerjaan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[DaTaskDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaTaskDetails()
    {
        return $this->hasMany(DaTaskDetail::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[DaTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaTasks()
    {
        return $this->hasMany(DaTask::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[JenisTunjangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisTunjangans()
    {
        return $this->hasMany(JenisTunjangan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiIndikatorPencapaianKompetensis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiIndikatorPencapaianKompetensis()
    {
        return $this->hasMany(KisiIndikatorPencapaianKompetensi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiJawabans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiJawabans()
    {
        return $this->hasMany(KisiJawaban::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiKompetensiDasars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiKompetensiDasars()
    {
        return $this->hasMany(KisiKompetensiDasar::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiMateris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiMateris()
    {
        return $this->hasMany(KisiMateri::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiSoals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiSoals()
    {
        return $this->hasMany(KisiSoal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[KisiStandarKompetensis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKisiStandarKompetensis()
    {
        return $this->hasMany(KisiStandarKompetensi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MAlasanLayakPips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMAlasanLayakPips()
    {
        return $this->hasMany(MAlasanLayakPip::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MAlatTransportasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMAlatTransportasis()
    {
        return $this->hasMany(MAlatTransportasi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MBanks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMBanks()
    {
        return $this->hasMany(MBank::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MBentukPendidikans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMBentukPendidikans()
    {
        return $this->hasMany(MBentukPendidikan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MBidangStudis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMBidangStudis()
    {
        return $this->hasMany(MBidangStudi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMBkgs()
    {
        return $this->hasMany(MBkg::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MGelarAkademiks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMGelarAkademiks()
    {
        return $this->hasMany(MGelarAkademik::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJabatanFungsionals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJabatanFungsionals()
    {
        return $this->hasMany(MJabatanFungsional::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJabatanTugasPtks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJabatanTugasPtks()
    {
        return $this->hasMany(MJabatanTugasPtk::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisBeasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisBeasiswas()
    {
        return $this->hasMany(MJenisBeasiswa::class, ['create_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisDiklats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisDiklats()
    {
        return $this->hasMany(MJenisDiklat::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisHapusBukus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisHapusBukus()
    {
        return $this->hasMany(MJenisHapusBuku::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisKeluars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisKeluars()
    {
        return $this->hasMany(MJenisKeluar::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisLembagas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisLembagas()
    {
        return $this->hasMany(MJenisLembaga::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisPenghargaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisPenghargaans()
    {
        return $this->hasMany(MJenisPenghargaan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisPrasaranas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisPrasaranas()
    {
        return $this->hasMany(MJenisPrasarana::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisPtks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisPtks()
    {
        return $this->hasMany(MJenisPtk::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisRombels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisRombels()
    {
        return $this->hasMany(MJenisRombel::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisSertifikasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisSertifikasis()
    {
        return $this->hasMany(MJenisSertifikasi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenisTinggals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenisTinggals()
    {
        return $this->hasMany(MJenisTinggal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJenjangPendidikans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJenjangPendidikans()
    {
        return $this->hasMany(MJenjangPendidikan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MJurusans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJurusans()
    {
        return $this->hasMany(MJurusan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MKeahlianLaboratoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMKeahlianLaboratoria()
    {
        return $this->hasMany(MKeahlianLaboratorium::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MKelompokBidangs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMKelompokBidangs()
    {
        return $this->hasMany(MKelompokBidang::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MKurikulums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMKurikulums()
    {
        return $this->hasMany(MKurikulum::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MLembagaPengangkats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMLembagaPengangkats()
    {
        return $this->hasMany(MLembagaPengangkat::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MLevelWilayahs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMLevelWilayahs()
    {
        return $this->hasMany(MLevelWilayah::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MMstWilayahs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMMstWilayahs()
    {
        return $this->hasMany(MMstWilayah::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MNegaras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMNegaras()
    {
        return $this->hasMany(MNegara::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MPangkatGolongans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMPangkatGolongans()
    {
        return $this->hasMany(MPangkatGolongan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MPekerjaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMPekerjaans()
    {
        return $this->hasMany(MPekerjaan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MPenghasilanOrangtuaWalis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMPenghasilanOrangtuaWalis()
    {
        return $this->hasMany(MPenghasilanOrangtuaWali::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MSemesters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMSemesters()
    {
        return $this->hasMany(MSemester::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MStatusAnaks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMStatusAnaks()
    {
        return $this->hasMany(MStatusAnak::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MStatusKeaktifanPegawais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMStatusKeaktifanPegawais()
    {
        return $this->hasMany(MStatusKeaktifanPegawai::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MStatusKepegawaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMStatusKepegawaians()
    {
        return $this->hasMany(MStatusKepegawaian::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MStatusKepemilikanSarpras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMStatusKepemilikanSarpras()
    {
        return $this->hasMany(MStatusKepemilikanSarpras::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MStatusKepemilikans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMStatusKepemilikans()
    {
        return $this->hasMany(MStatusKepemilikan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MSumberGajis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMSumberGajis()
    {
        return $this->hasMany(MSumberGaji::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MTahunAjarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMTahunAjarans()
    {
        return $this->hasMany(MTahunAjaran::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MTingkatPendidikans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMTingkatPendidikans()
    {
        return $this->hasMany(MTingkatPendidikan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MTingkatPenghargaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMTingkatPenghargaans()
    {
        return $this->hasMany(MTingkatPenghargaan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[NotifPegawais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifPegawais()
    {
        return $this->hasMany(NotifPegawai::class, ['edit_by' => 'id']);
    }

    /**
     * Gets query for [[NotifSiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifSiswas()
    {
        return $this->hasMany(NotifSiswa::class, ['edit_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiBidangSdms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiBidangSdms()
    {
        return $this->hasMany(PegawaiBidangSdm::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiBukus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiBukus()
    {
        return $this->hasMany(PegawaiBuku::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiDiklats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiDiklats()
    {
        return $this->hasMany(PegawaiDiklat::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiKaryaTulis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiKaryaTulis()
    {
        return $this->hasMany(PegawaiKaryaTulis::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiKesejahteraans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiKesejahteraans()
    {
        return $this->hasMany(PegawaiKesejahteraan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiNilaiTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiNilaiTests()
    {
        return $this->hasMany(PegawaiNilaiTest::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRiwayatGajiBerkalas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRiwayatGajiBerkalas()
    {
        return $this->hasMany(PegawaiRiwayatGajiBerkala::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwyFungsionals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyFungsionals()
    {
        return $this->hasMany(PegawaiRwyFungsional::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwyKepangkatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyKepangkatans()
    {
        return $this->hasMany(PegawaiRwyKepangkatan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwyKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyKerjas()
    {
        return $this->hasMany(PegawaiRwyKerja::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwyPendFormals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyPendFormals()
    {
        return $this->hasMany(PegawaiRwyPendFormal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwySertifikasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwySertifikasis()
    {
        return $this->hasMany(PegawaiRwySertifikasi::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiRwyStrukturals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyStrukturals()
    {
        return $this->hasMany(PegawaiRwyStruktural::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiTugasTambahans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTugasTambahans()
    {
        return $this->hasMany(PegawaiTugasTambahan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PegawaiTunjangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTunjangans()
    {
        return $this->hasMany(PegawaiTunjangan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PenjadwalanAlgenRecords]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanAlgenRecords()
    {
        return $this->hasMany(PenjadwalanAlgenRecord::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PenjadwalanJadwalAwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanJadwalAwals()
    {
        return $this->hasMany(PenjadwalanJadwalAwal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PenjadwalanPtkBerhalangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanPtkBerhalangans()
    {
        return $this->hasMany(PenjadwalanPtkBerhalangan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PenjadwalanSimpanRecords]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanSimpanRecords()
    {
        return $this->hasMany(PenjadwalanSimpanRecord::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PenjadwalanTmpGenerates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanTmpGenerates()
    {
        return $this->hasMany(PenjadwalanTmpGenerate::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PpdbGelombangs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpdbGelombangs()
    {
        return $this->hasMany(PpdbGelombang::class, ['create_by' => 'id']);
    }

    /**
     * Gets query for [[PublicJurusanSps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicJurusanSps()
    {
        return $this->hasMany(PublicJurusanSp::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPegawais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPegawais()
    {
        return $this->hasMany(PublicPegawai::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPegawais0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPegawais0()
    {
        return $this->hasMany(PublicPegawai::class, ['hak_akses' => 'id']);
    }

    /**
     * Gets query for [[PublicPembelajarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPembelajarans()
    {
        return $this->hasMany(PublicPembelajaran::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPesertaDidikBarus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPesertaDidikBarus()
    {
        return $this->hasMany(PublicPesertaDidikBaru::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPesertaDidikLongitudinals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPesertaDidikLongitudinals()
    {
        return $this->hasMany(PublicPesertaDidikLongitudinal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPesertaDidiks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPesertaDidiks()
    {
        return $this->hasMany(PublicPesertaDidik::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPrasaranas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPrasaranas()
    {
        return $this->hasMany(PublicPrasarana::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPtkTerdaftars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtkTerdaftars()
    {
        return $this->hasMany(PublicPtkTerdaftar::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicPtks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtks()
    {
        return $this->hasMany(PublicPtk::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicRombonganBelajars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicRombonganBelajars()
    {
        return $this->hasMany(PublicRombonganBelajar::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicSekolahLongitudinals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicSekolahLongitudinals()
    {
        return $this->hasMany(PublicSekolahLongitudinal::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[PublicYayasans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicYayasans()
    {
        return $this->hasMany(PublicYayasan::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[RelasiPegawaiRekeningBanks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelasiPegawaiRekeningBanks()
    {
        return $this->hasMany(RelasiPegawaiRekeningBank::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[StaffItemBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffItemBkgs()
    {
        return $this->hasMany(StaffItemBkg::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[StaffKomentarBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffKomentarBkgs()
    {
        return $this->hasMany(StaffKomentarBkg::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[StaffRiwayatBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRiwayatBkgs()
    {
        return $this->hasMany(StaffRiwayatBkg::class, ['created_by' => 'id']);
    }
}