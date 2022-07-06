<?php

namespace app\models\smart\generals;

use Yii;

/**
 * This is the model class for table "public_sekolah".
 *
 * @property string $sekolah_id
 * @property string $nama
 * @property string|null $kode
 * @property string|null $nama_nomenklatur
 * @property string|null $nss
 * @property string|null $npsn
 * @property string|null $tag_line
 * @property int $bentuk_pendidikan_id
 * @property string $alamat_jalan
 * @property float|null $rt
 * @property float|null $rw
 * @property string|null $nama_dusun
 * @property string|null $desa_kelurahan
 * @property string|null $kode_wilayah
 * @property string|null $kode_pos
 * @property float|null $lintang
 * @property float|null $bujur
 * @property string|null $nomor_telepon
 * @property string|null $nomor_fax
 * @property string|null $email
 * @property string|null $website
 * @property int $kebutuhan_khusus_id
 * @property float $status_sekolah
 * @property string|null $sk_pendirian_sekolah
 * @property string|null $tanggal_sk_pendirian
 * @property float $status_kepemilikan_id
 * @property string|null $yayasan_id
 * @property string|null $sk_izin_operasional
 * @property string|null $tanggal_sk_izin_operasional
 * @property string|null $no_rekening
 * @property string|null $nama_bank
 * @property string|null $cabang_kcp_unit
 * @property string|null $rekening_atas_nama
 * @property float|null $mbs
 * @property float|null $luas_tanah_milik
 * @property float|null $luas_tanah_bukan_milik
 * @property int|null $kode_registrasi
 * @property string|null $npwp
 * @property string|null $nm_wp
 * @property string|null $flag
 * @property int $aktif
 * @property string|null $logo
 * @property string|null $kepsek_uid
 * @property string|null $tmt_penugasan
 * @property string|null $sk_penugasan
 * @property int|null $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $soft_delete
 *
 * @property AbekSmkV13[] $abekSmkV13s
 * @property MBentukPendidikan $bentukPendidikan
 * @property CatatanWaliKelas[] $catatanWaliKelas
 * @property MKebutuhanKhusus $kebutuhanKhusus
 * @property MMstWilayah $kodeWilayah
 * @property MJurusan[] $mJurusans
 * @property MMataPelajaran[] $mMataPelajarans
 * @property MasterKelulusan[] $masterKelulusans
 * @property PegawaiTugasTambahan[] $pegawaiTugasTambahans
 * @property PpdbPendaftaran[] $ppdbPendaftarans
 * @property PublicJurusanSp[] $publicJurusanSps
 * @property PublicNilaiSikap[] $publicNilaiSikaps
 * @property PublicNilai[] $publicNilais
 * @property PublicPegawai[] $publicPegawais
 * @property PublicPesertaDidik[] $publicPesertaDidiks
 * @property PublicPklV13[] $publicPklV13s
 * @property PublicPrasarana[] $publicPrasaranas
 * @property PublicPtkTerdaftar[] $publicPtkTerdaftars
 * @property PublicRombonganBelajar[] $publicRombonganBelajars
 * @property StaffRiwayatBkg[] $staffRiwayatBkgs
 * @property MStatusKepemilikan $statusKepemilikan
 * @property PublicYayasan $yayasan
 */
class PublicSekolah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'public_sekolah';
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
            [['sekolah_id', 'nama', 'bentuk_pendidikan_id', 'alamat_jalan', 'status_kepemilikan_id', 'aktif'], 'required'],
            [['bentuk_pendidikan_id', 'kebutuhan_khusus_id', 'kode_registrasi', 'aktif', 'created_by', 'soft_delete'], 'integer'],
            [['rt', 'rw', 'lintang', 'bujur', 'status_sekolah', 'status_kepemilikan_id', 'mbs', 'luas_tanah_milik', 'luas_tanah_bukan_milik'], 'number'],
            [['tanggal_sk_pendirian', 'tanggal_sk_izin_operasional', 'tmt_penugasan', 'created_at', 'updated_at'], 'safe'],
            [['sekolah_id', 'nama', 'nama_nomenklatur', 'website', 'yayasan_id', 'nm_wp', 'kepsek_uid', 'sk_penugasan'], 'string', 'max' => 100],
            [['kode', 'kode_pos'], 'string', 'max' => 5],
            [['nss'], 'string', 'max' => 12],
            [['npsn', 'kode_wilayah'], 'string', 'max' => 8],
            [['tag_line', 'logo'], 'string', 'max' => 255],
            [['alamat_jalan', 'sk_pendirian_sekolah', 'sk_izin_operasional'], 'string', 'max' => 80],
            [['nama_dusun', 'desa_kelurahan', 'email', 'cabang_kcp_unit'], 'string', 'max' => 60],
            [['nomor_telepon', 'nomor_fax', 'no_rekening', 'nama_bank'], 'string', 'max' => 20],
            [['rekening_atas_nama'], 'string', 'max' => 50],
            [['npwp'], 'string', 'max' => 15],
            [['flag'], 'string', 'max' => 3],
            [['sekolah_id'], 'unique'],
            [['yayasan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicYayasan::class, 'targetAttribute' => ['yayasan_id' => 'yayasan_id']],
            [['bentuk_pendidikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBentukPendidikan::class, 'targetAttribute' => ['bentuk_pendidikan_id' => 'bentuk_pendidikan_id']],
            [['kebutuhan_khusus_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKebutuhanKhusus::class, 'targetAttribute' => ['kebutuhan_khusus_id' => 'kebutuhan_khusus_id']],
            [['kode_wilayah'], 'exist', 'skipOnError' => true, 'targetClass' => MMstWilayah::class, 'targetAttribute' => ['kode_wilayah' => 'kode_wilayah']],
            [['status_kepemilikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MStatusKepemilikan::class, 'targetAttribute' => ['status_kepemilikan_id' => 'status_kepemilikan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sekolah_id' => Yii::t('app', 'Sekolah ID'),
            'nama' => Yii::t('app', 'Nama'),
            'kode' => Yii::t('app', 'Kode'),
            'nama_nomenklatur' => Yii::t('app', 'Nama Nomenklatur'),
            'nss' => Yii::t('app', 'Nss'),
            'npsn' => Yii::t('app', 'Npsn'),
            'tag_line' => Yii::t('app', 'Tag Line'),
            'bentuk_pendidikan_id' => Yii::t('app', 'Bentuk Pendidikan ID'),
            'alamat_jalan' => Yii::t('app', 'Alamat Jalan'),
            'rt' => Yii::t('app', 'Rt'),
            'rw' => Yii::t('app', 'Rw'),
            'nama_dusun' => Yii::t('app', 'Nama Dusun'),
            'desa_kelurahan' => Yii::t('app', 'Desa Kelurahan'),
            'kode_wilayah' => Yii::t('app', 'Kode Wilayah'),
            'kode_pos' => Yii::t('app', 'Kode Pos'),
            'lintang' => Yii::t('app', 'Lintang'),
            'bujur' => Yii::t('app', 'Bujur'),
            'nomor_telepon' => Yii::t('app', 'Nomor Telepon'),
            'nomor_fax' => Yii::t('app', 'Nomor Fax'),
            'email' => Yii::t('app', 'Email'),
            'website' => Yii::t('app', 'Website'),
            'kebutuhan_khusus_id' => Yii::t('app', 'Kebutuhan Khusus ID'),
            'status_sekolah' => Yii::t('app', 'Status Sekolah'),
            'sk_pendirian_sekolah' => Yii::t('app', 'Sk Pendirian Sekolah'),
            'tanggal_sk_pendirian' => Yii::t('app', 'Tanggal Sk Pendirian'),
            'status_kepemilikan_id' => Yii::t('app', 'Status Kepemilikan ID'),
            'yayasan_id' => Yii::t('app', 'Yayasan ID'),
            'sk_izin_operasional' => Yii::t('app', 'Sk Izin Operasional'),
            'tanggal_sk_izin_operasional' => Yii::t('app', 'Tanggal Sk Izin Operasional'),
            'no_rekening' => Yii::t('app', 'No Rekening'),
            'nama_bank' => Yii::t('app', 'Nama Bank'),
            'cabang_kcp_unit' => Yii::t('app', 'Cabang Kcp Unit'),
            'rekening_atas_nama' => Yii::t('app', 'Rekening Atas Nama'),
            'mbs' => Yii::t('app', 'Mbs'),
            'luas_tanah_milik' => Yii::t('app', 'Luas Tanah Milik'),
            'luas_tanah_bukan_milik' => Yii::t('app', 'Luas Tanah Bukan Milik'),
            'kode_registrasi' => Yii::t('app', 'Kode Registrasi'),
            'npwp' => Yii::t('app', 'Npwp'),
            'nm_wp' => Yii::t('app', 'Nm Wp'),
            'flag' => Yii::t('app', 'Flag'),
            'aktif' => Yii::t('app', 'Aktif'),
            'logo' => Yii::t('app', 'Logo'),
            'kepsek_uid' => Yii::t('app', 'Kepsek Uid'),
            'tmt_penugasan' => Yii::t('app', 'Tmt Penugasan'),
            'sk_penugasan' => Yii::t('app', 'Sk Penugasan'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
        ];
    }

    /**
     * Gets query for [[AbekSmkV13s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbekSmkV13s()
    {
        return $this->hasMany(AbekSmkV13::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[BentukPendidikan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBentukPendidikan()
    {
        return $this->hasOne(MBentukPendidikan::class, ['bentuk_pendidikan_id' => 'bentuk_pendidikan_id']);
    }

    /**
     * Gets query for [[CatatanWaliKelas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatatanWaliKelas()
    {
        return $this->hasMany(CatatanWaliKelas::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[KebutuhanKhusus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKebutuhanKhusus()
    {
        return $this->hasOne(MKebutuhanKhusus::class, ['kebutuhan_khusus_id' => 'kebutuhan_khusus_id']);
    }

    /**
     * Gets query for [[KodeWilayah]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKodeWilayah()
    {
        return $this->hasOne(MMstWilayah::class, ['kode_wilayah' => 'kode_wilayah']);
    }

    /**
     * Gets query for [[MJurusans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJurusans()
    {
        return $this->hasMany(MJurusan::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[MMataPelajarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMMataPelajarans()
    {
        return $this->hasMany(MMataPelajaran::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[MasterKelulusans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKelulusans()
    {
        return $this->hasMany(MasterKelulusan::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PegawaiTugasTambahans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTugasTambahans()
    {
        return $this->hasMany(PegawaiTugasTambahan::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PpdbPendaftarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpdbPendaftarans()
    {
        return $this->hasMany(PpdbPendaftaran::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicJurusanSps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicJurusanSps()
    {
        return $this->hasMany(PublicJurusanSp::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicNilaiSikaps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicNilaiSikaps()
    {
        return $this->hasMany(PublicNilaiSikap::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicNilais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicNilais()
    {
        return $this->hasMany(PublicNilai::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicPegawais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPegawais()
    {
        return $this->hasMany(PublicPegawai::class, ['homebase' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicPesertaDidiks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPesertaDidiks()
    {
        return $this->hasMany(PublicPesertaDidik::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicPklV13s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPklV13s()
    {
        return $this->hasMany(PublicPklV13::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicPrasaranas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPrasaranas()
    {
        return $this->hasMany(PublicPrasarana::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicPtkTerdaftars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtkTerdaftars()
    {
        return $this->hasMany(PublicPtkTerdaftar::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[PublicRombonganBelajars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicRombonganBelajars()
    {
        return $this->hasMany(PublicRombonganBelajar::class, ['sekolah_id' => 'sekolah_id']);
    }

    /**
     * Gets query for [[StaffRiwayatBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRiwayatBkgs()
    {
        return $this->hasMany(StaffRiwayatBkg::class, ['id_sekolah' => 'sekolah_id']);
    }

    /**
     * Gets query for [[StatusKepemilikan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusKepemilikan()
    {
        return $this->hasOne(MStatusKepemilikan::class, ['status_kepemilikan_id' => 'status_kepemilikan_id']);
    }

    /**
     * Gets query for [[Yayasan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getYayasan()
    {
        return $this->hasOne(PublicYayasan::class, ['yayasan_id' => 'yayasan_id']);
    }
}
