<?php

namespace App\Helpers;

class CategoryHelper
{
    public static function getMap(): array
    {
        return [
            'Sektor Pertanian, Kehutanan, dan Perikanan' => [
                'Agroteknologi' => ['agroteknologi', 'agrotechnology', 'agronomi', 'proteksi tanaman', 'hama', 'penyakit tanaman', 'pemuliaan tanaman'],
                'Agribisnis' => ['agribisnis', 'agribusiness', 'sosial ekonomi pertanian', 'penyuluhan pertanian'],
                'Ilmu Tanah' => ['ilmu tanah', 'soil science', 'pedologi'],
                'Proteksi Tanaman' => ['proteksi tanaman', 'plant protection', 'entomologi', 'fitopatologi'],
                'Ilmu Kehutanan' => ['ilmu kehutanan', 'forestry', 'silvikultur', 'konservasi hutan'],
                'Teknologi Hasil Hutan' => ['teknologi hasil hutan', 'wood technology', 'hasil hutan'],
                'Budidaya Perairan (Akuakultur)' => ['budidaya perairan', 'akuakultur', 'aquaculture', 'perikanan budidaya', 'fish farming'],
                'Manajemen Sumberdaya Perairan' => ['sumberdaya perairan', 'aquatic resource', 'limnologi'],
                'Peternakan' => ['peternakan', 'animal husbandry', 'produksi ternak', 'nutrisi ternak'],
                'Teknologi Hasil Peternakan' => ['teknologi hasil peternakan', 'animal product technology', 'pengolahan hasil ternak']
            ],
            'Sektor Pertambangan dan Penggalian' => [
                'Teknik Pertambangan' => ['teknik pertambangan', 'mining engineering', 'eksplorasi tambang'],
                'Teknik Perminyakan' => ['teknik perminyakan', 'petroleum engineering', 'drilling engineer', 'reservoir engineer'],
                'Teknik Geologi' => ['teknik geologi', 'geological engineering', 'geologist'],
                'Geofisika' => ['geofisika', 'geophysics', 'geofisikawan', 'seismik'],
                'Teknik Geomatika' => ['teknik geomatika', 'geomatic engineering', 'surveyor', 'geodesi']
            ],
            'Sektor Industri Pengolahan (Manufaktur)' => [
                'Teknik Mesin' => ['teknik mesin', 'mechanical engineering', 'maintenance engineer', 'mesin perkakas', 'manufaktur mesin'],
                'Teknik Industri' => ['teknik industri', 'industrial engineering', 'supply chain', 'production planner', 'lean manufacturing', 'qc', 'qa manufaktur'],
                'Teknik Kimia' => ['teknik kimia', 'chemical engineering', 'proses industri', 'petrokimia'],
                'Teknologi Pangan' => ['teknologi pangan', 'food technology', 'qc pangan', 'qa pangan', 'haccp', 'halal supervisor'],
                'Teknik Elektro' => ['teknik elektro', 'electrical engineering', 'instrumentation engineer', 'automation engineer', 'plc'],
                'Teknik Tekstil' => ['teknik tekstil', 'textile engineering', 'garmen', 'serat tekstil'],
                'Teknologi Hasil Pertanian' => ['teknologi hasil pertanian', 'agricultural product technology', 'post-harvest']
            ],
            'Sektor Pengadaan Listrik, Gas, Air, dan Pengelolaan Sampah' => [
                'Teknik Elektro (Arus Kuat)' => ['arus kuat', 'power system', 'gardu induk', 'transmisi listrik', 'distribusi listrik'],
                'Teknik Tenaga Listrik' => ['tenaga listrik', 'power engineering', 'pembangkit listrik', 'generator'],
                'Teknik Lingkungan' => ['teknik lingkungan', 'environmental engineering', 'amdal', 'wastewater', 'ipal', 'sanitasi', 'pengolahan limbah', 'persampahan'],
                'Teknik Kimia' => ['water treatment', 'analisis kimia air', 'pengolahan gas'],
                'Manajemen Energi' => ['manajemen energi', 'energy management', 'audit energi', 'efisiensi energi']
            ],
            'Sektor Konstruksi' => [
                'Teknik Sipil' => ['teknik sipil', 'civil engineering', 'site engineer', 'struktur bangunan', 'estimator', 'quantity surveyor', 'jalan jembatan'],
                'Arsitektur' => ['arsitektur', 'architecture', 'arsitek', 'architect', '3d visualizer', 'drafter', 'desain bangunan'],
                'Perencanaan Wilayah dan Kota (Planologi)' => ['planologi', 'urban planning', 'perencanaan wilayah', 'regional planning', 'gis specialist'],
                'Teknik Infrastruktur Real Estate' => ['infrastruktur real estate', 'real estate engineering', 'pengembangan properti'],
                'Manajemen Konstruksi' => ['manajemen konstruksi', 'construction management', 'project manager konstruksi']
            ],
            'Sektor Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor' => [
                'Manajemen Pemasaran' => ['pemasaran', 'marketing', 'sales', 'digital marketing', 'brand manager', 'retail buyer', 'merchandiser'],
                'Bisnis Digital' => ['bisnis digital', 'digital business', 'e-commerce', 'growth hacker'],
                'Kewirausahaan (Entrepreneurship)' => ['kewirausahaan', 'entrepreneurship', 'wirausaha', 'business development'],
                'Perdagangan Internasional' => ['perdagangan internasional', 'international trade', 'ekspor impor', 'export import'],
                'Logistik perdagangan' => ['logistik perdagangan', 'trade logistics', 'distribusi retail']
            ],
            'Sektor Transportasi, Logistik, dan Pergudangan' => [
                'Manajemen Logistik' => ['manajemen logistik', 'logistics management', 'supply chain', 'warehouse manager', 'kepala gudang'],
                'Teknik Logistik' => ['teknik logistik', 'logistics engineering', 'distribution network'],
                'Manajemen Transportasi (Darat/Laut/Udara)' => ['manajemen transportasi', 'transportation management', 'fleet manager', 'operasi logistik'],
                'Teknik Dirgantara (Penerbangan)' => ['teknik dirgantara', 'penerbangan', 'aerospace engineering', 'aircraft maintenance', 'aviasi'],
                'Teknik Perkapalan' => ['teknik perkapalan', 'naval architecture', 'marine engineering', 'galangan kapal'],
                'Ilmu Kelautan/Sistem Perkapalan' => ['ilmu kelautan', 'marine science', 'sistem perkapalan']
            ],
            'Sektor Penyediaan Akomodasi dan Penyediaan Makan Minum (Hospitality)' => [
                'Pariwisata' => ['pariwisata', 'tourism', 'tour guide', 'travel agent', 'destinasi wisata'],
                'Perhotelan' => ['perhotelan', 'hospitality', 'front office', 'housekeeping', 'hotel management', 'guest relation'],
                'Tata Boga (Culinary Arts)' => ['tata boga', 'culinary', 'chef', 'koki', 'pastry', 'baker', 'barista', 'cook helper'],
                'Manajemen Bisnis Pariwisata' => ['bisnis pariwisata', 'tourism business', 'event organizer'],
                'Destinasi Pariwisata' => ['destinasi pariwisata', 'tourism destination', 'pengembangan wisata']
            ],
            'Sektor Informasi dan Komunikasi (TIK)' => [
                'Teknik Informatika' => ['teknik informatika', 'informatics', 'software engineer', 'developer', 'programmer', 'backend', 'frontend', 'laravel', 'golang', 'react', 'vue'],
                'Ilmu Komputer' => ['ilmu komputer', 'computer science', 'data scientist', 'data analyst', 'ai engineer', 'machine learning'],
                'Sistem Informasi' => ['sistem informasi', 'information system', 'it analyst', 'product manager', 'scrum master', 'business analyst'],
                'Rekayasa Perangkat Lunak (RPL)' => ['rekayasa perangkat lunak', 'rpl', 'software engineering', 'sdet', 'qa engineer', 'tester'],
                'Teknologi Informasi' => ['teknologi informasi', 'it support', 'network engineer', 'sysadmin', 'cloud engineer', 'devops', 'cyber security'],
                'Ilmu Komunikasi' => ['ilmu komunikasi', 'communication science', 'public relations', 'pr', 'spokesperson', 'corporate communication'],
                'Jurnalistik' => ['jurnalistik', 'journalism', 'jurnalis', 'reporter', 'editor berita', 'redaktur'],
                'Desain Komunikasi Visual (DKV)' => ['desain komunikasi visual', 'dkv', 'graphic designer', 'ui/ux designer', 'illustrator', 'creative designer'],
                'Produksi Film/Televisi' => ['produksi film', 'televisi', 'broadcasting', 'videographer', 'video editor', 'sutradara', 'cameraman']
            ],
            'Sektor Keuangan, Asuransi, dan Real Estat' => [
                'Akuntansi' => ['akuntansi', 'accounting', 'akuntan', 'auditor', 'tax specialist', 'perpajakan', 'bookkeeper'],
                'Manajemen Keuangan' => ['keuangan', 'finance', 'financial analyst', 'investment analyst', 'wealth management'],
                'Ilmu Ekonomi' => ['ilmu ekonomi', 'economics', 'ekonom', 'analis ekonomi'],
                'Pembangunan' => ['ekonomi pembangunan', 'pembangunan', 'development economics'],
                'Aktuaria' => ['aktuaria', 'actuarial', 'aktuaris', 'risk modeler'],
                'Matematika Bisnis' => ['matematika bisnis', 'business mathematics', 'quantitative analyst'],
                'Manajemen Aset/Properti' => ['manajemen aset', 'properti', 'property management', 'asset management', 'appraiser']
            ],
            'Sektor Jasa Profesional, Ilmiah, dan Teknis' => [
                'Ilmu Hukum' => ['ilmu hukum', 'law', 'legal', 'corporate lawyer', 'pengacara', 'legal officer', 'kontrak hukum'],
                'Notariat' => ['notariat', 'notaris', 'notary'],
                'Manajemen Kedirgantaraan' => ['manajemen kedirgantaraan', 'aerospace management', 'bandara', 'airport management'],
                'Statistika/Sains Data' => ['statistika', 'statistics', 'sains data', 'data science', 'statistisi', 'actuarial science'],
                'Psikologi Industri & Organisasi' => ['psikologi', 'hrd', 'recruiter', 'talent acquisition', 'organizational development', 'psikotes'],
                'Fisika' => ['fisika', 'physics', 'fisikawan', 'lab analyst'],
                'Kimia' => ['kimia', 'chemistry', 'analis kimia', 'lab technician', 'quality control kimia'],
                'Biologi' => ['biologi', 'biology', 'mikrobiologi', 'bioteknologi', 'lab researcher']
            ],
            'Sektor Administrasi Pemerintahan, Pertahanan, dan Jaminan Sosial Wajib' => [
                'Ilmu Administrasi Negara/Publik' => ['administrasi negara', 'administrasi publik', 'public administration'],
                'Kebijakan Publik' => ['kebijakan publik', 'public policy', 'policy analyst'],
                'Ilmu Pemerintahan' => ['ilmu pemerintahan', 'government studies'],
                'Ilmu Politik' => ['ilmu politik', 'political science', 'analis politik'],
                'Hubungan Internasional' => ['hubungan internasional', 'international relations', 'diplomat', 'foreign affairs'],
                'STPDN/IPDN' => ['stpdn', 'ipdn', 'pamong praja'],
                'Akademi Militer' => ['akademi militer', 'akmil', 'tni', 'perwira militer'],
                'Akademi Kepolisian' => ['akademi kepolisian', 'akpol', 'polri', 'perwira polisi']
            ],
            'Sektor Jasa Pendidikan' => [
                'Rumpun Pendidikan' => ['pendidikan', 'keguruan', 'guru', 'teacher', 'dosen', 'tutor', 'tentor', 'pgsd', 'paud', 'pengajar'],
                'Manajemen Pendidikan' => ['manajemen education', 'kepala sekolah'],
                'Kurikulum Teknologi Pendidikan' => ['teknologi pendidikan', 'educational technology', 'instructional designer', 'kurikulum']
            ],
            'Sektor Kesehatan Manusia dan Kegiatan Sosial' => [
                'Kedokteran Umum' => ['kedokteran umum', 'dokter umum', 'general practitioner', 'dokter'],
                'Kedokteran Gigi' => ['kedokteran gigi', 'dokter gigi', 'dentist'],
                'Keperawatan' => ['keperawatan', 'perawat', 'nurse', 'nursing care'],
                'Kebidanan' => ['kebidanan', 'bidan', 'midwife'],
                'Farmasi' => ['farmasi', 'pharmacy', 'apoteker', 'pharmacist', 'asisten apoteker', 'qc farmasi'],
                'Ilmu Gizi' => ['ilmu gizi', 'nutritionist', 'ahli gizi', 'dietisien'],
                'Kesehatan Masyarakat' => ['kesehatan masyarakat', 'public health', 'k3', 'epidemiologi', 'promosi kesehatan'],
                'Ilmu Kesejahteraan Sosial' => ['kesejahteraan sosial', 'social work', 'pekerja sosial', 'pemberdayaan masyarakat']
            ],
            'Sektor Jasa Kesenian, Hiburan, dan Rekreasi' => [
                'Seni Rupa' => ['seni rupa', 'fine arts', 'pelukis', 'curator', 'art director', 'sculptor'],
                'Seni Musik' => ['seni musik', 'music', 'musisi', 'music director', 'komposer', 'vocal coach'],
                'Seni Tari' => ['seni tari', 'dance', 'koreografer', 'penari', 'choreographer'],
                'Teater' => ['teater', 'theater', 'aktor', 'aktris', 'sutradara teater', 'seni peran'],
                'Manajemen Olahraga' => ['manajemen olahraga', 'sports management', 'sport coordinator'],
                'Ilmu Keolahragaan' => ['keolahragaan', 'olahraga', 'sports science', 'atlet', 'pelatih olahraga', 'personal trainer', 'fitness instructor']
            ]
        ];
    }

    public static function getSektorList(): array
    {
        return array_keys(self::getMap());
    }

    public static function getAllMajors(): array
    {
        $majors = [];
        foreach (self::getMap() as $sektor => $majorList) {
            foreach ($majorList as $majorName => $keywords) {
                $majors[] = $majorName;
            }
        }
        return array_values(array_unique($majors));
    }

    public static function getSektors(): array
    {
        return array_keys(static::getMap());
    }

    public static function getMajorsForSektor(string $sektor): array
    {
        $map = self::getMap();
        if (isset($map[$sektor])) {
            return array_keys($map[$sektor]);
        }
        
        // Handle alias or slightly different names
        if (str_contains($sektor, 'Perdagangan Besar')) {
            return array_keys($map['Sektor Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor'] ?? []);
        }
        if (str_contains($sektor, 'Akomodasi')) {
            return array_keys($map['Sektor Penyediaan Akomodasi dan Penyediaan Makan Minum (Hospitality)'] ?? []);
        }
        if (str_contains($sektor, 'Administrasi Pemerintahan')) {
            return array_keys($map['Sektor Administrasi Pemerintahan, Pertahanan, dan Jaminan Sosial Wajib'] ?? []);
        }
        if (str_contains($sektor, 'Kesenian')) {
            return array_keys($map['Sektor Jasa Kesenian, Hiburan, dan Rekreasi'] ?? []);
        }
        if (str_contains($sektor, 'Kesehatan')) {
            return array_keys($map['Sektor Kesehatan Manusia dan Kegiatan Sosial'] ?? []);
        }

        return [];
    }

    public static function classify(string $title, string $description): array
    {
        $searchStr = strtolower($title . ' ' . $description);
        
        // Default fallbacks
        $sektor = 'Sektor Jasa Profesional, Ilmiah, dan Teknis';
        $major = 'Semua Jurusan';

        // Check for specific major keywords first
        foreach (self::getMap() as $sektorName => $majors) {
            foreach ($majors as $majorName => $keywords) {
                foreach ($keywords as $keyword) {
                    $pattern = '/\b' . preg_quote(strtolower($keyword), '/') . '\b/i';
                    if (str_contains($keyword, '/') || str_contains($keyword, '&') || str_contains($keyword, '(')) {
                        $pattern = '/' . preg_quote(strtolower($keyword), '/') . '/i';
                    }
                    if (preg_match($pattern, $searchStr)) {
                        return [
                            'sektor' => $sektorName,
                            'jurusan' => $majorName
                        ];
                    }
                }
            }
        }

        return [
            'sektor' => $sektor,
            'jurusan' => $major
        ];
    }
}
