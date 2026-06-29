<?php

namespace App\Helpers;

class LocationHelper
{
    protected static array $provinces = [
        'Aceh' => [
            // Kota otonom
            'Kota Banda Aceh', 'Kota Langsa', 'Kota Lhokseumawe', 'Kota Sabang', 'Kota Subulussalam',
            // Kabupaten
            'Kabupaten Aceh Barat', 'Kabupaten Aceh Barat Daya', 'Kabupaten Aceh Besar', 'Kabupaten Aceh Jaya', 
            'Kabupaten Aceh Selatan', 'Kabupaten Aceh Singkil', 'Kabupaten Aceh Tamiang', 'Kabupaten Aceh Tengah',
            'Kabupaten Aceh Tenggara', 'Kabupaten Aceh Timur', 'Kabupaten Aceh Utara', 'Kabupaten Bener Meriah',
            'Kabupaten Bireuen', 'Kabupaten Gayo Lues', 'Kabupaten Nagan Raya', 'Kabupaten Pidie', 
            'Kabupaten Pidie Jaya', 'Kabupaten Simeulue'
        ],
        'Bali' => [
            // Kota otonom
            'Kota Denpasar',
            // Kabupaten
            'Kabupaten Badung', 'Kabupaten Bangli', 'Kabupaten Buleleng', 'Kabupaten Gianyar', 
            'Kabupaten Jembrana', 'Kabupaten Karangasem', 'Kabupaten Klungkung', 'Kabupaten Tabanan'
        ],
        'Banten' => [
            // Kota otonom
            'Kota Cilegon', 'Kota Serang', 'Kota Tangerang', 'Kota Tangerang Selatan',
            // Kabupaten
            'Kabupaten Lebak', 'Kabupaten Pandeglang', 'Kabupaten Serang', 'Kabupaten Tangerang'
        ],
        'Bengkulu' => [
            // Kota otonom
            'Kota Bengkulu',
            // Kabupaten
            'Kabupaten Bengkulu Selatan', 'Kabupaten Bengkulu Tengah', 'Kabupaten Bengkulu Utara',
            'Kabupaten Kaur', 'Kabupaten Kepahiang', 'Kabupaten Lebong', 'Kabupaten Mukomuko',
            'Kabupaten Rejang Lebong', 'Kabupaten Seluma'
        ],
        'DI Yogyakarta' => [
            // Kota otonom
            'Kota Yogyakarta',
            // Kabupaten
            'Kabupaten Bantul', 'Kabupaten Gunung Kidul', 'Kabupaten Kulon Progo', 'Kabupaten Sleman'
        ],
        'DKI Jakarta' => [
            // Kota administrasi
            'Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 
            'Jakarta Timur', 'Jakarta Utara',
            // Kabupaten administrasi
            'Kabupaten Administrasi Kepulauan Seribu'
        ],
        'Gorontalo' => [
            // Kota otonom
            'Kota Gorontalo',
            // Kabupaten
            'Kabupaten Bone Bolango', 'Kabupaten Boalemo', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara',
            'Kabupaten Pohuwato'
        ],
        'Jambi' => [
            // Kota otonom
            'Kota Jambi', 'Kota Sungai Penuh',
            // Kabupaten
            'Kabupaten Batang Hari', 'Kabupaten Bungo', 'Kabupaten Kerinci', 'Kabupaten Merangin', 
            'Kabupaten Muaro Jambi', 'Kabupaten Sarolangun', 'Kabupaten Tanjung Jabung Barat', 
            'Kabupaten Tanjung Jabung Timur', 'Kabupaten Tebo'
        ],
        'Jawa Barat' => [
            // Kota otonom
            'Kota Bandung', 'Kota Banjar', 'Kota Bekasi', 'Kota Bogor', 'Kota Cimahi', 'Kota Cirebon', 
            'Kota Depok', 'Kota Sukabumi', 'Kota Tasikmalaya',
            // Kabupaten
            'Kabupaten Bandung', 'Kabupaten Bandung Barat', 'Kabupaten Bekasi', 'Kabupaten Bogor',
            'Kabupaten Ciamis', 'Kabupaten Cianjur', 'Kabupaten Cirebon', 'Kabupaten Garut',
            'Kabupaten Indramayu', 'Kabupaten Karawang', 'Kabupaten Kuningan', 'Kabupaten Majalengka',
            'Kabupaten Pangandaran', 'Kabupaten Purwakarta', 'Kabupaten Subang', 'Kabupaten Sukabumi',
            'Kabupaten Sumedang', 'Kabupaten Tasikmalaya'
        ],
        'Jawa Tengah' => [
            // Kota otonom
            'Kota Magelang', 'Kota Pekalongan', 'Kota Salatiga', 'Kota Semarang', 'Kota Surakarta',
            // Kabupaten
            'Kabupaten Banjarnegara', 'Kabupaten Banyumas', 'Kabupaten Batang', 'Kabupaten Blora',
            'Kabupaten Boyolali', 'Kabupaten Brebes', 'Kabupaten Cilacap', 'Kabupaten Demak',
            'Kabupaten Grobogan', 'Kabupaten Jepara', 'Kabupaten Karanganyar', 'Kabupaten Kebumen',
            'Kabupaten Kendal', 'Kabupaten Klaten', 'Kabupaten Kudus', 'Kabupaten Magelang',
            'Kabupaten Pati', 'Kabupaten Pekalongan', 'Kabupaten Pemalang', 'Kabupaten Purworejo',
            'Kabupaten Rembang', 'Kabupaten Semarang', 'Kabupaten Sragen', 'Kabupaten Sukoharjo',
            'Kabupaten Tegal', 'Kabupaten Temanggung', 'Kabupaten Wonogiri', 'Kabupaten Wonosobo'
        ],
        'Jawa Timur' => [
            // Kota otonom
            'Kota Batu', 'Kota Blitar', 'Kota Kediri', 'Kota Madiun', 'Kota Malang', 'Kota Mojokerto',
            'Kota Pasuruan', 'Kota Probolinggo', 'Kota Surabaya', 'Kota Jember',
            // Kabupaten
            'Kabupaten Bangkalan', 'Kabupaten Banyuwangi', 'Kabupaten Batu', 'Kabupaten Blitar',
            'Kabupaten Bojonegoro', 'Kabupaten Bondowoso', 'Kabupaten Gresik', 'Kabupaten Jember',
            'Kabupaten Jombang', 'Kabupaten Kediri', 'Kabupaten Lamongan', 'Kabupaten Lumajang',
            'Kabupaten Madiun', 'Kabupaten Magetan', 'Kabupaten Malang', 'Kabupaten Mojokerto',
            'Kabupaten Nganjuk', 'Kabupaten Ngawi', 'Kabupaten Pacitan', 'Kabupaten Pamekasan',
            'Kabupaten Pasuruan', 'Kabupaten Ponorogo', 'Kabupaten Probolinggo', 'Kabupaten Sampang',
            'Kabupaten Sidoarjo', 'Kabupaten Situbondo', 'Kabupaten Sumenep', 'Kabupaten Trenggalek',
            'Kabupaten Tuban', 'Kabupaten Tulungagung'
        ],
        'Kalimantan Barat' => [
            // Kota otonom
            'Kota Pontianak', 'Kota Singkawang',
            // Kabupaten
            'Kabupaten Bengkayang', 'Kabupaten Kapuas Hulu', 'Kabupaten Ketapang', 'Kabupaten Kubu Raya',
            'Kabupaten Landak', 'Kabupaten Melawi', 'Kabupaten Mempawah', 'Kabupaten Sanggau', 
            'Kabupaten Sekadau', 'Kabupaten Sintang'
        ],
        'Kalimantan Selatan' => [
            // Kota otonom
            'Kota Banjarbaru', 'Kota Banjarmasin',
            // Kabupaten
            'Kabupaten Balangan', 'Kabupaten Banjar', 'Kabupaten Barito Kuala', 'Kabupaten Hulu Sungai Selatan',
            'Kabupaten Hulu Sungai Tengah', 'Kabupaten Hulu Sungai Utara', 'Kabupaten Tabalong', 
            'Kabupaten Tanah Bumbu', 'Kabupaten Tanah Laut'
        ],
        'Kalimantan Tengah' => [
            // Kota otonom
            'Kota Palangka Raya',
            // Kabupaten
            'Kabupaten Barito Selatan', 'Kabupaten Barito Timur', 'Kabupaten Barito Utara', 'Kabupaten Gunung Mas',
            'Kabupaten Kapuas', 'Kabupaten Kotawaringin Barat', 'Kabupaten Kotawaringin Timur', 
            'Kabupaten Kualakapuas', 'Kabupaten Lamandau', 'Kabupaten Murung Raya', 'Kabupaten Pulang Pisau',
            'Kabupaten Seruyan', 'Kabupaten Sukamara', 'Kabupaten Suku'
        ],
        'Kalimantan Timur' => [
            // Kota otonom
            'Kota Balikpapan', 'Kota Samarinda',
            // Kabupaten
            'Kabupaten Berau', 'Kabupaten Kutai Kartanegara', 'Kabupaten Kutai Timur', 'Kabupaten Kutai Barat',
            'Kabupaten Paser', 'Kabupaten Penajam Paser Utara'
        ],
        'Kalimantan Utara' => [
            // Kota otonom
            'Kota Tarakan',
            // Kabupaten
            'Kabupaten Bulungan', 'Kabupaten Malinau', 'Kabupaten Nunukan', 'Kabupaten Tana Tidung'
        ],
        'Kepulauan Bangka Belitung' => [
            // Kota otonom
            'Kota Pangkal Pinang',
            // Kabupaten
            'Kabupaten Bangka', 'Kabupaten Bangka Barat', 'Kabupaten Bangka Selatan', 'Kabupaten Bangka Tengah',
            'Kabupaten Belitung', 'Kabupaten Belitung Timur'
        ],
        'Kepulauan Riau' => [
            // Kota otonom
            'Kota Batam', 'Kota Tanjung Pinang',
            // Kabupaten
            'Kabupaten Bintan', 'Kabupaten Karimun', 'Kabupaten Lingga', 'Kabupaten Natuna',
            'Kabupaten Kepulauan Anambas'
        ],
        'Lampung' => [
            // Kota otonom
            'Kota Bandar Lampung', 'Kota Metro', 'Kota Prabumulih',
            // Kabupaten
            'Kabupaten Lampung Barat', 'Kabupaten Lampung Selatan', 'Kabupaten Lampung Tengah', 
            'Kabupaten Lampung Timur', 'Kabupaten Lampung Utara', 'Kabupaten Mesuji', 'Kabupaten Pesisir Barat',
            'Kabupaten Tanggamus', 'Kabupaten Tulang Bawang Barat', 'Kabupaten Way Kanan'
        ],
        'Maluku' => [
            // Kota otonom
            'Kota Ambon', 'Kota Tual',
            // Kabupaten
            'Kabupaten Maluku Barat Daya', 'Kabupaten Maluku Tengah', 'Kabupaten Maluku Tenggara',
            'Kabupaten Maluku Tenggara Barat', 'Kabupaten Seram Bagian Barat', 'Kabupaten Seram Bagian Timur'
        ],
        'Maluku Utara' => [
            // Kota otonom
            'Kota Ternate', 'Kota Tidore Kepulauan',
            // Kabupaten
            'Kabupaten Halmahera Barat', 'Kabupaten Halmahera Selatan', 'Kabupaten Halmahera Tengah',
            'Kabupaten Halmahera Timur', 'Kabupaten Halmahera Utara', 'Kabupaten Pulau Morotai',
            'Kabupaten Pulau Sula'
        ],
        'Nusa Tenggara Barat' => [
            // Kota otonom
            'Kota Bima', 'Kota Mataram',
            // Kabupaten
            'Kabupaten Dompu', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah', 'Kabupaten Lombok Timur',
            'Kabupaten Lombok Utara', 'Kabupaten Sumbawa'
        ],
        'Nusa Tenggara Timur' => [
            // Kota otonom
            'Kota Kupang',
            // Kabupaten
            'Kabupaten Alor', 'Kabupaten Belu', 'Kabupaten Ende', 'Kabupaten Flores Timur', 
            'Kabupaten Kupang', 'Kabupaten Lembata', 'Kabupaten Malaka', 'Kabupaten Manggarai',
            'Kabupaten Manggarai Barat', 'Kabupaten Manggarai Timur', 'Kabupaten Nagekeo', 
            'Kabupaten Ngada', 'Kabupaten Rote Ndao', 'Kabupaten Sabu Raijua', 'Kabupaten Sikka',
            'Kabupaten Sumba Barat', 'Kabupaten Sumba Barat Daya', 'Kabupaten Sumba Tengah', 
            'Kabupaten Sumba Timur', 'Kabupaten Timor Tengah Selatan', 'Kabupaten Timor Tengah Utara'
        ],
        'Papua' => [
            // Kota otonom
            'Kota Jayapura',
            // Kabupaten
            'Kabupaten Jayapura', 'Kabupaten Keerom', 'Kabupaten Kepulauan Yapen', 'Kabupaten Mamberamo Raya',
            'Kabupaten Mamberamo Tengah', 'Kabupaten Puncak Jaya', 'Kabupaten Sarmi', 'Kabupaten Supiori',
            'Kabupaten Waropen', 'Kabupaten Yahukimo'
        ],
        'Papua Barat' => [
            // Kota otonom
            'Kota Manokwari', 'Kota Sorong',
            // Kabupaten
            'Kabupaten Fakfak', 'Kabupaten Kaimana', 'Kabupaten Manokwari Selatan', 'Kabupaten Maybrat',
            'Kabupaten Pegunungan Arfak', 'Kabupaten Raja Ampat', 'Kabupaten South Sorong', 'Kabupaten Tambrauw',
            'Kabupaten Teluk Bintuni', 'Kabupaten Teluk Wondama'
        ],
        'Papua Barat Daya' => [
            // Kota otonom
            'Kota Sorong',
            // Kabupaten
            'Kabupaten Manokwari Selatan', 'Kabupaten Maybrat', 'Kabupaten Pegunungan Arfak', 
            'Kabupaten Sorong', 'Kabupaten Sorong Selatan', 'Kabupaten Tambrauw', 
            'Kabupaten Teluk Bintuni', 'Kabupaten Teluk Wondama'
        ],
        'Papua Pegunungan' => [
            // Kota otonom
            'Kota Wamena',
            // Kabupaten
            'Kabupaten Jayawijaya', 'Kabupaten Lanny Jaya', 'Kabupaten Mamberamo Tengah', 'Kabupaten Nduga',
            'Kabupaten Puncak', 'Kabupaten Tolikara', 'Kabupaten Yalimo'
        ],
        'Papua Selatan' => [
            // Kota otonom
            'Kota Merauke',
            // Kabupaten
            'Kabupaten Asmat', 'Kabupaten Boven Digoel', 'Kabupaten Mappi', 'Kabupaten Merauke',
            'Kabupaten Muyu', 'Kabupaten Pegunungan Bintang'
        ],
        'Papua Tengah' => [
            // Kota otonom
            'Kota Timika',
            // Kabupaten
            'Kabupaten Deiyai', 'Kabupaten Dogiyai', 'Kabupaten Intan Jaya', 'Kabupaten Mimika', 
            'Kabupaten Nabire', 'Kabupaten Paniai', 'Kabupaten Puncak Jaya'
        ],
        'Riau' => [
            // Kota otonom
            'Kota Dumai', 'Kota Pekanbaru',
            // Kabupaten
            'Kabupaten Bengkalis', 'Kabupaten Indragiri Hilir', 'Kabupaten Indragiri Hulu', 
            'Kabupaten Kampar', 'Kabupaten Kepulauan Meranti', 'Kabupaten Pelalawan', 'Kabupaten Rokan Hilir',
            'Kabupaten Rokan Hulu', 'Kabupaten Siak'
        ],
        'Sulawesi Barat' => [
            // Kota otonom
            'Kota Majene', 'Kota Mamuju',
            // Kabupaten
            'Kabupaten Mamasa', 'Kabupaten Mamuju Tengah', 'Kabupaten Mamuju Utara', 'Kabupaten Pasangkayu',
            'Kabupaten Polewali Mandar'
        ],
        'Sulawesi Selatan' => [
            // Kota otonom
            'Kota Makassar', 'Kota Palopo', 'Kota Parepare',
            // Kabupaten
            'Kabupaten Bantaeng', 'Kabupaten Barru', 'Kabupaten Bone', 'Kabupaten Bulukumba', 
            'Kabupaten Enrekang', 'Kabupaten Gowa', 'Kabupaten Jeneponto', 'Kabupaten Luwu', 
            'Kabupaten Luwu Timur', 'Kabupaten Luwu Utara', 'Kabupaten Maros', 'Kabupaten Pinrang',
            'Kabupaten Selayar', 'Kabupaten Sidenreng Rappang', 'Kabupaten Sinjai', 'Kabupaten Takalar',
            'Kabupaten Tana Toraja', 'Kabupaten Toraja Utara', 'Kabupaten Wajo'
        ],
        'Sulawesi Tengah' => [
            // Kota otonom
            'Kota Palu',
            // Kabupaten
            'Kabupaten Banggai', 'Kabupaten Banggai Kepulauan', 'Kabupaten Banggai Laut', 
            'Kabupaten Buol', 'Kabupaten Donggala', 'Kabupaten Morowali', 'Kabupaten Morowali Utara',
            'Kabupaten Parigi Moutong', 'Kabupaten Poso', 'Kabupaten Sigi', 'Kabupaten Tojo Una-Una',
            'Kabupaten Tolitoli'
        ],
        'Sulawesi Tenggara' => [
            // Kota otonom
            'Kota Baubau', 'Kota Kendari',
            // Kabupaten
            'Kabupaten Bombana', 'Kabupaten Buton', 'Kabupaten Buton Selatan', 'Kabupaten Buton Tengah',
            'Kabupaten Buton Utara', 'Kabupaten Kolaka', 'Kabupaten Kolaka Timur', 'Kabupaten Kolaka Utara',
            'Kabupaten Konawe', 'Kabupaten Konawe Selatan', 'Kabupaten Konawe Utara', 'Kabupaten Muna',
            'Kabupaten Muna Barat', 'Kabupaten Wakatobi'
        ],
        'Sulawesi Utara' => [
            // Kota otonom
            'Kota Bitung', 'Kota Kotamobagu', 'Kota Manado', 'Kota Tomohon',
            // Kabupaten
            'Kabupaten Bolaang Mongondow', 'Kabupaten Bolaang Mongondow Selatan', 
            'Kabupaten Bolaang Mongondow Timur', 'Kabupaten Bolaang Mongondow Utara', 
            'Kabupaten Kepulauan Sangihe', 'Kabupaten Kepulauan Talaud', 'Kabupaten Minahasa',
            'Kabupaten Minahasa Selatan', 'Kabupaten Minahasa Utara'
        ],
        'Sumatera Barat' => [
            // Kota otonom
            'Kota Bukittinggi', 'Kota Padang', 'Kota Padang Panjang', 'Kota Pariaman', 
            'Kota Payakumbuh', 'Kota Sawahlunto', 'Kota Solok',
            // Kabupaten
            'Kabupaten Agam', 'Kabupaten Dharmasraya', 'Kabupaten Kepulauan Mentawai', 'Kabupaten Lima Puluh Kota',
            'Kabupaten Padang Pariaman', 'Kabupaten Pasaman', 'Kabupaten Pasaman Barat', 'Kabupaten Pesisir Selatan',
            'Kabupaten Sawahlunto Sijunjung', 'Kabupaten Solok', 'Kabupaten Solok Selatan', 'Kabupaten Tanah Datar'
        ],
        'Sumatera Selatan' => [
            // Kota otonom
            'Kota Lubuklinggau', 'Kota Pagar Alam', 'Kota Palembang', 'Kota Prabumulih',
            // Kabupaten
            'Kabupaten Banyuasin', 'Kabupaten Empat Lawang', 'Kabupaten Lahat', 'Kabupaten Muara Enim',
            'Kabupaten Musi Banyuasin', 'Kabupaten Musi Rawas', 'Kabupaten Musi Rawas Utara',
            'Kabupaten Ogan Ilir', 'Kabupaten Ogan Komering Ilir', 'Kabupaten Ogan Komering Ulu',
            'Kabupaten Ogan Komering Ulu Selatan', 'Kabupaten Ogan Komering Ulu Timur', 
            'Kabupaten Penukal Abab Lematang Ilir'
        ],
        'Sumatera Utara' => [
            // Kota otonom
            'Kota Binjai', 'Kota Medan', 'Kota Padang Sidimpuan', 'Kota Pematangsiantar', 'Kota Sibolga', 
            'Kota Tanjung Balai', 'Kota Tebing Tinggi',
            // Kabupaten
            'Kabupaten Asahan', 'Kabupaten Batubara', 'Kabupaten Dairi', 'Kabupaten Deli Serdang',
            'Kabupaten Dhuaka', 'Kabupaten Gunungsitoli', 'Kabupaten Humbang Hasundutan', 'Kabupaten Karo',
            'Kabupaten Labuhanbatu', 'Kabupaten Labuhanbatu Selatan', 'Kabupaten Labuhanbatu Utara', 
            'Kabupaten Langkat', 'Kabupaten Mandailing Natal', 'Kabupaten Nias', 'Kabupaten Nias Barat',
            'Kabupaten Nias Selatan', 'Kabupaten Nias Utara', 'Kabupaten Pakpak Bharat', 'Kabupaten Samosir',
            'Kabupaten Serdang Bedagai', 'Kabupaten Simalungun', 'Kabupaten Tapanuli Selatan', 
            'Kabupaten Tapanuli Tengah', 'Kabupaten Tapanuli Utara'
        ]
    ];
    
    protected static array $remoteKeywords = [
        'remote', 'wfh', 'work from home', 'kerja dari rumah', 'telecommute'
    ];

    /**
     * Classify location text into normalized province and city.
     */
    public static function classify(string $locationText, string $title = '', string $description = ''): array
    {
        $locationText = trim($locationText);
        
        // Junk check: if it is garbage text due to scraper failure
        $junkKeywords = ['web', 'developer', 'engineer', 'programmer', 'full stack', 'frontend', 'backend', 'design', 'development', 'technology', 'creative'];
        foreach ($junkKeywords as $junk) {
            if (strcasecmp($locationText, $junk) === 0 || stripos($locationText, ' ' . $junk) !== false) {
                $locationText = '';
                break;
            }
        }
        
        // 1. Check for remote/wfh
        foreach (self::$remoteKeywords as $keyword) {
            if (stripos($locationText, $keyword) !== false || 
                stripos($title, $keyword) !== false ||
                stripos(substr($description, 0, 1000), $keyword) !== false) {
                return [
                    'province' => 'Remote / WFH',
                    'city' => 'Remote'
                ];
            }
        }

        // 2. Pre-normalization for major cities to match the exact DB lists (JobApplicationForm)
        $lowerLoc = strtolower($locationText);
        $lowerTitle = strtolower($title);
        $lowerDesc = strtolower(substr($description, 0, 1000));

        $checkText = function($pattern) use ($lowerLoc, $lowerTitle, $lowerDesc) {
            return str_contains($lowerLoc, $pattern) || 
                   str_contains($lowerTitle, $pattern) || 
                   str_contains($lowerDesc, $pattern);
        };

        if ($checkText('jakarta')) {
            return ['province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan'];
        }
        if ($checkText('surabaya')) {
            return ['province' => 'Jawa Timur', 'city' => 'Kota Surabaya'];
        }
        if ($checkText('bandung')) {
            return ['province' => 'Jawa Barat', 'city' => 'Kota Bandung'];
        }
        if ($checkText('yogyakarta') || $checkText('jogja')) {
            return ['province' => 'DI Yogyakarta', 'city' => 'Kota Yogyakarta'];
        }
        if ($checkText('solo') || $checkText('surakarta')) {
            return ['province' => 'Jawa Tengah', 'city' => 'Kota Surakarta'];
        }
        if ($checkText('medan')) {
            return ['province' => 'Sumatera Utara', 'city' => 'Kota Medan'];
        }
        if ($checkText('tangerang')) {
            return ['province' => 'Banten', 'city' => 'Kota Tangerang Selatan'];
        }
        if ($checkText('bekasi')) {
            return ['province' => 'Jawa Barat', 'city' => 'Kota Bekasi'];
        }
        if ($checkText('depok')) {
            return ['province' => 'Jawa Barat', 'city' => 'Kota Depok'];
        }
        if ($checkText('bogor')) {
            return ['province' => 'Jawa Barat', 'city' => 'Kota Bogor'];
        }
        if ($checkText('semarang')) {
            return ['province' => 'Jawa Tengah', 'city' => 'Kota Semarang'];
        }
        if ($checkText('makassar')) {
            return ['province' => 'Sulawesi Selatan', 'city' => 'Kota Makassar'];
        }
        if ($checkText('palembang')) {
            return ['province' => 'Sumatera Selatan', 'city' => 'Kota Palembang'];
        }
        if ($checkText('pekanbaru')) {
            return ['province' => 'Riau', 'city' => 'Kota Pekanbaru'];
        }
        if ($checkText('denpasar')) {
            return ['province' => 'Bali', 'city' => 'Kota Denpasar'];
        }
        if ($checkText('batam')) {
            return ['province' => 'Kepulauan Riau', 'city' => 'Kota Batam'];
        }

        // 3. Scan locationText for specific cities (with prefix-stripping match)
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $city) {
                $cleanCity = preg_replace('/^(Kabupaten|Kota)\s+/i', '', $city);
                if (stripos($locationText, $city) !== false || stripos($locationText, $cleanCity) !== false) {
                    return [
                        'province' => $province,
                        'city' => self::normalizeCity($city)
                    ];
                }
            }
        }

        // 4. Scan title and description for specific cities (with prefix-stripping match)
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $city) {
                $cleanCity = preg_replace('/^(Kabupaten|Kota)\s+/i', '', $city);
                if (strlen($cleanCity) < 4) {
                    if (stripos($title, $cleanCity) !== false) {
                        return [
                            'province' => $province,
                            'city' => self::normalizeCity($city)
                        ];
                    }
                    continue;
                }
                if (stripos($title, $cleanCity) !== false || 
                    stripos(substr($description, 0, 1000), $cleanCity) !== false) {
                    return [
                        'province' => $province,
                        'city' => self::normalizeCity($city)
                    ];
                }
            }
        }

        // 5. Default fallback
        return [
            'province' => 'Lainnya',
            'city' => 'Indonesia'
        ];
    }
    
    /**
     * Normalize specific city variants to standard name.
     */
    public static function normalizeCity(string $city): string
    {
        $city = trim($city);
        if (strcasecmp($city, 'Solo') === 0 || strcasecmp($city, 'Kota Solo') === 0) {
            return 'Kota Surakarta';
        }
        if (strcasecmp($city, 'Jogja') === 0 || strcasecmp($city, 'Kota Jogja') === 0) {
            return 'Kota Yogyakarta';
        }
        if (strcasecmp($city, 'Jakarta') === 0 || strcasecmp($city, 'Jakarta Raya') === 0) {
            return 'Jakarta Selatan';
        }
        if (strcasecmp($city, 'Pasar Minggu') === 0) {
            return 'Jakarta Selatan';
        }
        if (strcasecmp($city, 'Lembang') === 0) {
            return 'Kota Bandung';
        }
        
        $cleanCity = preg_replace('/^(Kabupaten|Kota)\s+/i', '', $city);
        
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $c) {
                $cleanC = preg_replace('/^(Kabupaten|Kota)\s+/i', '', $c);
                if (strcasecmp($cleanCity, $cleanC) === 0) {
                    return $c; // Return the official prefixed name (e.g., "Kota Bandung")
                }
            }
        }
        
        return $city;
    }

    /**
     * Map a normalized city back to its province.
     */
    public static function getProvinceForCity(string $city): string
    {
        $city = self::normalizeCity($city);
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $c) {
                if (strcasecmp($city, self::normalizeCity($c)) === 0) {
                    return $province;
                }
            }
        }
        
        if (strcasecmp($city, 'Remote') === 0) {
            return 'Remote / WFH';
        }
        
        return 'Lainnya';
    }

    /**
     * Get statistics of jobs per province and city.
     */
    public static function getLocationStatistics(): array
    {
        $jobLocations = \App\Models\JobPosting::where('status', 'active')
            ->whereNotNull('location')
            ->select('location', \DB::raw('count(*) as count'))
            ->groupBy('location')
            ->get();
            
        $stats = [];
        
        foreach ($jobLocations as $item) {
            $city = $item->location;
            $count = $item->count;
            $province = self::getProvinceForCity($city);
            
            if (!isset($stats[$province])) {
                $stats[$province] = [
                    'count' => 0,
                    'cities' => []
                ];
            }
            
            $stats[$province]['count'] += $count;
            $stats[$province]['cities'][] = [
                'name' => $city,
                'count' => $count
            ];
        }
        
        // Sort provinces by job count descending
        uasort($stats, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        return $stats;
    }

    /**
     * Get list of all provinces in Indonesia.
     */
    public static function getAllProvinces(): array
    {
        return array_keys(self::$provinces);
    }

    /**
     * Get list of cities belonging to a specific province.
     */
    public static function getCitiesForProvince(string $province): array
    {
        $province = trim($province);
        if (isset(self::$provinces[$province])) {
            return array_values(array_unique(array_map([self::class, 'normalizeCity'], self::$provinces[$province])));
        }
        return [];
    }
}
