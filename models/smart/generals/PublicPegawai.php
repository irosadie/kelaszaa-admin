<?php

namespace app\models\smart\generals;

use Yii;

/**
 * This is the model class for table "public_pegawai".
 *
 * @property string $pegawai_uid
 * @property string $nama
 * @property string|null $kd
 * @property int|null $gelar_akademik_id
 * @property string|null $nip
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $nik
 * @property string $foto
 * @property string|null $niy_nigk
 * @property string|null $nuptk
 * @property int|null $status_kepegawaian_id
 * @property float|null $jenis_ptk_id
 * @property int|null $pengawas_bidang_studi_id
 * @property int|null $agama_id
 * @property string $alamat_jalan
 * @property float|null $rt
 * @property float|null $rw
 * @property string|null $nama_dusun
 * @property string $desa_kelurahan
 * @property string|null $kode_wilayah
 * @property string|null $alamat_jalan_domisili
 * @property string|null $kode_wilayah_domisili
 * @property string|null $kode_pos
 * @property string|null $no_telepon_rumah
 * @property string|null $no_hp
 * @property string|null $email
 * @property float|null $status_keaktifan_id
 * @property string|null $sk_cpns
 * @property string|null $tgl_cpns
 * @property string|null $sk_pengangkatan
 * @property string|null $tmt_pengangkatan
 * @property float|null $lembaga_pengangkat_id
 * @property float|null $jenis_ptk_pertama_id
 * @property string|null $sk_pengangkatan_pertama
 * @property string|null $tmt_pengangkatan_pertama
 * @property float|null $lembaga_pengangkat_pertama_id
 * @property float|null $pangkat_golongan_id
 * @property int|null $keahlian_laboratorium_id
 * @property float|null $sumber_gaji_id
 * @property string $nama_ibu_kandung
 * @property float $status_perkawinan
 * @property string|null $nama_suami_istri
 * @property string|null $nip_suami_istri
 * @property int|null $pekerjaan_suami_istri
 * @property string|null $tmt_pns
 * @property float|null $sudah_lisensi_kepala_sekolah
 * @property int|null $jumlah_sekolah_binaan
 * @property float|null $pernah_diklat_kepengawasan
 * @property string|null $nm_wp
 * @property int|null $status_data
 * @property string|null $karpeg
 * @property string|null $karpas
 * @property int|null $mampu_handle_kk
 * @property float|null $keahlian_braille
 * @property float|null $keahlian_bhs_isyarat
 * @property string|null $npwp
 * @property string|null $kewarganegaraan
 * @property string|null $homebase
 * @property int $tipe
 * @property int|null $hak_akses
 * @property int|null $maks_jam
 * @property int|null $min_jam
 * @property string|null $status
 * @property string|null $file_ktp
 * @property string|null $file_sk_pengangkatan_pertama
 * @property string|null $file_sk_pengangkatan
 * @property string|null $file_sk_cpns
 * @property string|null $file_skbn
 * @property string|null $file_surat_kesehatan
 * @property string|null $no_kk
 * @property string|null $file_kk
 * @property int|null $created_by
 * @property string $updated_at
 * @property int $soft_delete
 * @property string $created_at
 * @property string|null $no_hp_pasangan
 * @property string $status_pegawai
 * @property string|null $file_npwp
 *
 * @property MAgama $agama
 * @property MAgama $agama0
 * @property HakAkses $createdBy
 * @property DaPjPekerjaan[] $daPjPekerjaans
 * @property DaTask[] $daTasks
 * @property MGelarAkademik $gelarAkademik
 * @property HakAkses $hakAkses
 * @property PublicSekolah $homebase0
 * @property IzinDinas[] $izinDinas
 * @property IzinDinas[] $izinDinas0
 * @property MJenisPtk $jenisPtk
 * @property MKeahlianLaboratorium $keahlianLaboratorium
 * @property MNegara $kewarganegaraan0
 * @property MMstWilayah $kodeWilayah
 * @property MLembagaPengangkat $lembagaPengangkat
 * @property MKebutuhanKhusus $mampuHandleKk
 * @property NotifPegawai[] $notifPegawais
 * @property MPangkatGolongan $pangkatGolongan
 * @property PegawaiAnak[] $pegawaiAnaks
 * @property PegawaiBidangSdm[] $pegawaiBidangSdms
 * @property PegawaiBuku[] $pegawaiBukus
 * @property PegawaiDiklat[] $pegawaiDiklats
 * @property PegawaiKaryaTulis[] $pegawaiKaryaTulis
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
 * @property MPekerjaan $pekerjaanSuamiIstri
 * @property MBidangStudi $pengawasBidangStudi
 * @property PenjadwalanPtkBerhalangan[] $penjadwalanPtkBerhalangans
 * @property PublicPembelajaran[] $publicPembelajarans
 * @property PublicPtkTerdaftar[] $publicPtkTerdaftars
 * @property PublicPtk[] $publicPtks
 * @property PublicRombonganBelajar[] $publicRombonganBelajars
 * @property RelasiPegawaiRekeningBank[] $relasiPegawaiRekeningBanks
 * @property SimpegPengguna[] $simpegPenggunas
 * @property StaffRiwayatBkg[] $staffRiwayatBkgs
 * @property StaffRiwayatBkg[] $staffRiwayatBkgs0
 * @property MStatusKeaktifanPegawai $statusKeaktifan
 * @property MStatusKepegawaian $statusKepegawaian
 * @property MSumberGaji $sumberGaji
 */
class PublicPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'public_pegawai';
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
            [['pegawai_uid', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nik', 'foto', 'alamat_jalan', 'desa_kelurahan', 'nama_ibu_kandung', 'status_perkawinan'], 'required'],
            [['gelar_akademik_id', 'status_kepegawaian_id', 'pengawas_bidang_studi_id', 'agama_id', 'keahlian_laboratorium_id', 'pekerjaan_suami_istri', 'jumlah_sekolah_binaan', 'status_data', 'mampu_handle_kk', 'tipe', 'hak_akses', 'maks_jam', 'min_jam', 'created_by', 'soft_delete'], 'integer'],
            [['tanggal_lahir', 'tgl_cpns', 'tmt_pengangkatan', 'tmt_pengangkatan_pertama', 'tmt_pns', 'updated_at', 'created_at'], 'safe'],
            [['jenis_ptk_id', 'rt', 'rw', 'status_keaktifan_id', 'lembaga_pengangkat_id', 'jenis_ptk_pertama_id', 'lembaga_pengangkat_pertama_id', 'pangkat_golongan_id', 'sumber_gaji_id', 'status_perkawinan', 'sudah_lisensi_kepala_sekolah', 'pernah_diklat_kepengawasan', 'keahlian_braille', 'keahlian_bhs_isyarat'], 'number'],
            [['status', 'status_pegawai'], 'string'],
            [['pegawai_uid'], 'string', 'max' => 25],
            [['nama', 'nama_ibu_kandung', 'nama_suami_istri', 'nm_wp', 'homebase'], 'string', 'max' => 100],
            [['kd', 'kode_pos'], 'string', 'max' => 5],
            [['nip', 'nip_suami_istri'], 'string', 'max' => 18],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['tempat_lahir'], 'string', 'max' => 32],
            [['nik', 'nuptk', 'karpas'], 'string', 'max' => 16],
            [['foto'], 'string', 'max' => 255],
            [['niy_nigk'], 'string', 'max' => 30],
            [['alamat_jalan', 'sk_cpns', 'sk_pengangkatan', 'sk_pengangkatan_pertama'], 'string', 'max' => 80],
            [['nama_dusun', 'desa_kelurahan', 'email'], 'string', 'max' => 60],
            [['kode_wilayah', 'kode_wilayah_domisili'], 'string', 'max' => 8],
            [['alamat_jalan_domisili', 'file_ktp', 'file_sk_pengangkatan_pertama', 'file_sk_pengangkatan', 'file_sk_cpns', 'file_skbn', 'file_surat_kesehatan', 'no_kk', 'file_kk', 'file_npwp'], 'string', 'max' => 200],
            [['no_telepon_rumah', 'no_hp', 'no_hp_pasangan'], 'string', 'max' => 20],
            [['karpeg'], 'string', 'max' => 10],
            [['npwp'], 'string', 'max' => 15],
            [['kewarganegaraan'], 'string', 'max' => 2],
            [['pegawai_uid'], 'unique'],
            [['agama_id'], 'exist', 'skipOnError' => true, 'targetClass' => MAgama::class, 'targetAttribute' => ['agama_id' => 'agama_id']],
            [['pangkat_golongan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPangkatGolongan::class, 'targetAttribute' => ['pangkat_golongan_id' => 'pangkat_golongan_id']],
            [['pekerjaan_suami_istri'], 'exist', 'skipOnError' => true, 'targetClass' => MPekerjaan::class, 'targetAttribute' => ['pekerjaan_suami_istri' => 'pekerjaan_id']],
            [['status_keaktifan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MStatusKeaktifanPegawai::class, 'targetAttribute' => ['status_keaktifan_id' => 'status_keaktifan_id']],
            [['status_kepegawaian_id'], 'exist', 'skipOnError' => true, 'targetClass' => MStatusKepegawaian::class, 'targetAttribute' => ['status_kepegawaian_id' => 'status_kepegawaian_id']],
            [['sumber_gaji_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSumberGaji::class, 'targetAttribute' => ['sumber_gaji_id' => 'sumber_gaji_id']],
            [['agama_id'], 'exist', 'skipOnError' => true, 'targetClass' => MAgama::class, 'targetAttribute' => ['agama_id' => 'agama_id']],
            [['gelar_akademik_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGelarAkademik::class, 'targetAttribute' => ['gelar_akademik_id' => 'gelar_akademik_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => HakAkses::class, 'targetAttribute' => ['created_by' => 'id']],
            [['hak_akses'], 'exist', 'skipOnError' => true, 'targetClass' => HakAkses::class, 'targetAttribute' => ['hak_akses' => 'id']],
            [['homebase'], 'exist', 'skipOnError' => true, 'targetClass' => PublicSekolah::class, 'targetAttribute' => ['homebase' => 'sekolah_id']],
            [['pengawas_bidang_studi_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBidangStudi::class, 'targetAttribute' => ['pengawas_bidang_studi_id' => 'bidang_studi_id']],
            [['jenis_ptk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJenisPtk::class, 'targetAttribute' => ['jenis_ptk_id' => 'jenis_ptk_id']],
            [['keahlian_laboratorium_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKeahlianLaboratorium::class, 'targetAttribute' => ['keahlian_laboratorium_id' => 'keahlian_laboratorium_id']],
            [['mampu_handle_kk'], 'exist', 'skipOnError' => true, 'targetClass' => MKebutuhanKhusus::class, 'targetAttribute' => ['mampu_handle_kk' => 'kebutuhan_khusus_id']],
            [['lembaga_pengangkat_id'], 'exist', 'skipOnError' => true, 'targetClass' => MLembagaPengangkat::class, 'targetAttribute' => ['lembaga_pengangkat_id' => 'lembaga_pengangkat_id']],
            [['kode_wilayah'], 'exist', 'skipOnError' => true, 'targetClass' => MMstWilayah::class, 'targetAttribute' => ['kode_wilayah' => 'kode_wilayah']],
            [['kewarganegaraan'], 'exist', 'skipOnError' => true, 'targetClass' => MNegara::class, 'targetAttribute' => ['kewarganegaraan' => 'negara_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pegawai_uid' => Yii::t('app', 'Pegawai Uid'),
            'nama' => Yii::t('app', 'Nama'),
            'kd' => Yii::t('app', 'Kd'),
            'gelar_akademik_id' => Yii::t('app', 'Gelar Akademik ID'),
            'nip' => Yii::t('app', 'Nip'),
            'jenis_kelamin' => Yii::t('app', 'Jenis Kelamin'),
            'tempat_lahir' => Yii::t('app', 'Tempat Lahir'),
            'tanggal_lahir' => Yii::t('app', 'Tanggal Lahir'),
            'nik' => Yii::t('app', 'Nik'),
            'foto' => Yii::t('app', 'Foto'),
            'niy_nigk' => Yii::t('app', 'Niy Nigk'),
            'nuptk' => Yii::t('app', 'Nuptk'),
            'status_kepegawaian_id' => Yii::t('app', 'Status Kepegawaian ID'),
            'jenis_ptk_id' => Yii::t('app', 'Jenis Ptk ID'),
            'pengawas_bidang_studi_id' => Yii::t('app', 'Pengawas Bidang Studi ID'),
            'agama_id' => Yii::t('app', 'Agama ID'),
            'alamat_jalan' => Yii::t('app', 'Alamat Jalan'),
            'rt' => Yii::t('app', 'Rt'),
            'rw' => Yii::t('app', 'Rw'),
            'nama_dusun' => Yii::t('app', 'Nama Dusun'),
            'desa_kelurahan' => Yii::t('app', 'Desa Kelurahan'),
            'kode_wilayah' => Yii::t('app', 'Kode Wilayah'),
            'alamat_jalan_domisili' => Yii::t('app', 'Alamat Jalan Domisili'),
            'kode_wilayah_domisili' => Yii::t('app', 'Kode Wilayah Domisili'),
            'kode_pos' => Yii::t('app', 'Kode Pos'),
            'no_telepon_rumah' => Yii::t('app', 'No Telepon Rumah'),
            'no_hp' => Yii::t('app', 'No Hp'),
            'email' => Yii::t('app', 'Email'),
            'status_keaktifan_id' => Yii::t('app', 'Status Keaktifan ID'),
            'sk_cpns' => Yii::t('app', 'Sk Cpns'),
            'tgl_cpns' => Yii::t('app', 'Tgl Cpns'),
            'sk_pengangkatan' => Yii::t('app', 'Sk Pengangkatan'),
            'tmt_pengangkatan' => Yii::t('app', 'Tmt Pengangkatan'),
            'lembaga_pengangkat_id' => Yii::t('app', 'Lembaga Pengangkat ID'),
            'jenis_ptk_pertama_id' => Yii::t('app', 'Jenis Ptk Pertama ID'),
            'sk_pengangkatan_pertama' => Yii::t('app', 'Sk Pengangkatan Pertama'),
            'tmt_pengangkatan_pertama' => Yii::t('app', 'Tmt Pengangkatan Pertama'),
            'lembaga_pengangkat_pertama_id' => Yii::t('app', 'Lembaga Pengangkat Pertama ID'),
            'pangkat_golongan_id' => Yii::t('app', 'Pangkat Golongan ID'),
            'keahlian_laboratorium_id' => Yii::t('app', 'Keahlian Laboratorium ID'),
            'sumber_gaji_id' => Yii::t('app', 'Sumber Gaji ID'),
            'nama_ibu_kandung' => Yii::t('app', 'Nama Ibu Kandung'),
            'status_perkawinan' => Yii::t('app', 'Status Perkawinan'),
            'nama_suami_istri' => Yii::t('app', 'Nama Suami Istri'),
            'nip_suami_istri' => Yii::t('app', 'Nip Suami Istri'),
            'pekerjaan_suami_istri' => Yii::t('app', 'Pekerjaan Suami Istri'),
            'tmt_pns' => Yii::t('app', 'Tmt Pns'),
            'sudah_lisensi_kepala_sekolah' => Yii::t('app', 'Sudah Lisensi Kepala Sekolah'),
            'jumlah_sekolah_binaan' => Yii::t('app', 'Jumlah Sekolah Binaan'),
            'pernah_diklat_kepengawasan' => Yii::t('app', 'Pernah Diklat Kepengawasan'),
            'nm_wp' => Yii::t('app', 'Nm Wp'),
            'status_data' => Yii::t('app', 'Status Data'),
            'karpeg' => Yii::t('app', 'Karpeg'),
            'karpas' => Yii::t('app', 'Karpas'),
            'mampu_handle_kk' => Yii::t('app', 'Mampu Handle Kk'),
            'keahlian_braille' => Yii::t('app', 'Keahlian Braille'),
            'keahlian_bhs_isyarat' => Yii::t('app', 'Keahlian Bhs Isyarat'),
            'npwp' => Yii::t('app', 'Npwp'),
            'kewarganegaraan' => Yii::t('app', 'Kewarganegaraan'),
            'homebase' => Yii::t('app', 'Homebase'),
            'tipe' => Yii::t('app', 'Tipe'),
            'hak_akses' => Yii::t('app', 'Hak Akses'),
            'maks_jam' => Yii::t('app', 'Maks Jam'),
            'min_jam' => Yii::t('app', 'Min Jam'),
            'status' => Yii::t('app', 'Status'),
            'file_ktp' => Yii::t('app', 'File Ktp'),
            'file_sk_pengangkatan_pertama' => Yii::t('app', 'File Sk Pengangkatan Pertama'),
            'file_sk_pengangkatan' => Yii::t('app', 'File Sk Pengangkatan'),
            'file_sk_cpns' => Yii::t('app', 'File Sk Cpns'),
            'file_skbn' => Yii::t('app', 'File Skbn'),
            'file_surat_kesehatan' => Yii::t('app', 'File Surat Kesehatan'),
            'no_kk' => Yii::t('app', 'No Kk'),
            'file_kk' => Yii::t('app', 'File Kk'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'no_hp_pasangan' => Yii::t('app', 'No Hp Pasangan'),
            'status_pegawai' => Yii::t('app', 'Status Pegawai'),
            'file_npwp' => Yii::t('app', 'File Npwp'),
        ];
    }

    /**
     * Gets query for [[Agama]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgama()
    {
        return $this->hasOne(MAgama::class, ['agama_id' => 'agama_id']);
    }

    /**
     * Gets query for [[Agama0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgama0()
    {
        return $this->hasOne(MAgama::class, ['agama_id' => 'agama_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(HakAkses::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DaPjPekerjaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaPjPekerjaans()
    {
        return $this->hasMany(DaPjPekerjaan::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[DaTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDaTasks()
    {
        return $this->hasMany(DaTask::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[GelarAkademik]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGelarAkademik()
    {
        return $this->hasOne(MGelarAkademik::class, ['gelar_akademik_id' => 'gelar_akademik_id']);
    }

    /**
     * Gets query for [[HakAkses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHakAkses()
    {
        return $this->hasOne(HakAkses::class, ['id' => 'hak_akses']);
    }

    /**
     * Gets query for [[Homebase0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomebase0()
    {
        return $this->hasOne(PublicSekolah::class, ['sekolah_id' => 'homebase']);
    }

    /**
     * Gets query for [[IzinDinas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIzinDinas()
    {
        return $this->hasMany(IzinDinas::class, ['pengganti' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[IzinDinas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIzinDinas0()
    {
        return $this->hasMany(IzinDinas::class, ['created_by' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[JenisPtk]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPtk()
    {
        return $this->hasOne(MJenisPtk::class, ['jenis_ptk_id' => 'jenis_ptk_id']);
    }

    /**
     * Gets query for [[KeahlianLaboratorium]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKeahlianLaboratorium()
    {
        return $this->hasOne(MKeahlianLaboratorium::class, ['keahlian_laboratorium_id' => 'keahlian_laboratorium_id']);
    }

    /**
     * Gets query for [[Kewarganegaraan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKewarganegaraan0()
    {
        return $this->hasOne(MNegara::class, ['negara_id' => 'kewarganegaraan']);
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
     * Gets query for [[LembagaPengangkat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLembagaPengangkat()
    {
        return $this->hasOne(MLembagaPengangkat::class, ['lembaga_pengangkat_id' => 'lembaga_pengangkat_id']);
    }

    /**
     * Gets query for [[MampuHandleKk]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMampuHandleKk()
    {
        return $this->hasOne(MKebutuhanKhusus::class, ['kebutuhan_khusus_id' => 'mampu_handle_kk']);
    }

    /**
     * Gets query for [[NotifPegawais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifPegawais()
    {
        return $this->hasMany(NotifPegawai::class, ['pegawai_id' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PangkatGolongan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPangkatGolongan()
    {
        return $this->hasOne(MPangkatGolongan::class, ['pangkat_golongan_id' => 'pangkat_golongan_id']);
    }

    /**
     * Gets query for [[PegawaiAnaks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiAnaks()
    {
        return $this->hasMany(PegawaiAnak::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiBidangSdms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiBidangSdms()
    {
        return $this->hasMany(PegawaiBidangSdm::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiBukus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiBukus()
    {
        return $this->hasMany(PegawaiBuku::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiDiklats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiDiklats()
    {
        return $this->hasMany(PegawaiDiklat::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiKaryaTulis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiKaryaTulis()
    {
        return $this->hasMany(PegawaiKaryaTulis::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiNilaiTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiNilaiTests()
    {
        return $this->hasMany(PegawaiNilaiTest::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRiwayatGajiBerkalas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRiwayatGajiBerkalas()
    {
        return $this->hasMany(PegawaiRiwayatGajiBerkala::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwyFungsionals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyFungsionals()
    {
        return $this->hasMany(PegawaiRwyFungsional::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwyKepangkatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyKepangkatans()
    {
        return $this->hasMany(PegawaiRwyKepangkatan::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwyKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyKerjas()
    {
        return $this->hasMany(PegawaiRwyKerja::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwyPendFormals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyPendFormals()
    {
        return $this->hasMany(PegawaiRwyPendFormal::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwySertifikasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwySertifikasis()
    {
        return $this->hasMany(PegawaiRwySertifikasi::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiRwyStrukturals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiRwyStrukturals()
    {
        return $this->hasMany(PegawaiRwyStruktural::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiTugasTambahans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTugasTambahans()
    {
        return $this->hasMany(PegawaiTugasTambahan::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PegawaiTunjangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiTunjangans()
    {
        return $this->hasMany(PegawaiTunjangan::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PekerjaanSuamiIstri]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanSuamiIstri()
    {
        return $this->hasOne(MPekerjaan::class, ['pekerjaan_id' => 'pekerjaan_suami_istri']);
    }

    /**
     * Gets query for [[PengawasBidangStudi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengawasBidangStudi()
    {
        return $this->hasOne(MBidangStudi::class, ['bidang_studi_id' => 'pengawas_bidang_studi_id']);
    }

    /**
     * Gets query for [[PenjadwalanPtkBerhalangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenjadwalanPtkBerhalangans()
    {
        return $this->hasMany(PenjadwalanPtkBerhalangan::class, ['ptk_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PublicPembelajarans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPembelajarans()
    {
        return $this->hasMany(PublicPembelajaran::class, ['ptk_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PublicPtkTerdaftars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtkTerdaftars()
    {
        return $this->hasMany(PublicPtkTerdaftar::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PublicPtks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtks()
    {
        return $this->hasMany(PublicPtk::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[PublicRombonganBelajars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicRombonganBelajars()
    {
        return $this->hasMany(PublicRombonganBelajar::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[RelasiPegawaiRekeningBanks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelasiPegawaiRekeningBanks()
    {
        return $this->hasMany(RelasiPegawaiRekeningBank::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[SimpegPenggunas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimpegPenggunas()
    {
        return $this->hasMany(SimpegPengguna::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[StaffRiwayatBkgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRiwayatBkgs()
    {
        return $this->hasMany(StaffRiwayatBkg::class, ['pegawai_uid' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[StaffRiwayatBkgs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRiwayatBkgs0()
    {
        return $this->hasMany(StaffRiwayatBkg::class, ['validasi_by' => 'pegawai_uid']);
    }

    /**
     * Gets query for [[StatusKeaktifan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusKeaktifan()
    {
        return $this->hasOne(MStatusKeaktifanPegawai::class, ['status_keaktifan_id' => 'status_keaktifan_id']);
    }

    /**
     * Gets query for [[StatusKepegawaian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusKepegawaian()
    {
        return $this->hasOne(MStatusKepegawaian::class, ['status_kepegawaian_id' => 'status_kepegawaian_id']);
    }

    /**
     * Gets query for [[SumberGaji]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSumberGaji()
    {
        return $this->hasOne(MSumberGaji::class, ['sumber_gaji_id' => 'sumber_gaji_id']);
    }
}
