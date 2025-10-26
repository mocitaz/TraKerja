<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\JobApplication;
use Livewire\Component;

class JobApplicationForm extends Component
{
    public $jobApplication;
    public $company_name = '';
    public $position = '';
    public $location = '';
    public $selectedProvince = '';
    public $selectedCity = '';
    public $cities = [];
    public $selectedCountry = '';
    public $selectedInternationalCity = '';
    public $internationalCities = [];
    public $isRemote = false;
    public $isSeluruhIndonesia = false;
    public $isInternational = false;
    public $platform = '';
    public $platformOther = '';
    // Deprecated legacy status (removed from UI); keep for backward compatibility when loading old records
    public $status = 'Applied';
    public $application_status = '';
    public $recruitment_stage = '';
    public $career_level = '';
    public $platform_link = '';
    public $application_date = '';
    public $notes = '';
    
    // Interview fields
    public $interview_date = '';
    public $interview_type = '';
    public $interview_location = '';
    public $interview_notes = '';

    public $applicationStatusOptions = [
        'On Process',
        'Declined',
        'Accepted'
    ];

    public $recruitmentStageOptions = [
        'Applied',
        'Follow Up',
        'Assessment Test',
        'Psychotest',
        'HR - Interview',
        'User - Interview',
        'LGD',
        'Presentation Round',
        'Offering',
        'Not Processed'
    ];

    public $careerLevelOptions = [
        'Intern',
        'Full Time',
        'Contract',
        'MT',
        'Freelance'
    ];

    public $platformOptions = [
        '9cv9',
        'Cake: Cari Lowongan',
        'Dealls',
        'Disnakerja.com',
        'Email',
        'Fiverr',
        'Freelancer',
        'Glassdoor',
        'Glints',
        'Google Forms',
        'Indeed',
        'JobStreet',
        'Jobseeker App',
        'JobsDB',
        'Jora',
        'Kalibrr',
        'Karir.com',
        'Karirhub (SIAPkerja)',
        'KitaLulus',
        'LinkedIn',
        'Loker.id',
        'Microsoft Forms',
        'NusaCrowd',
        'Pintarnya.com',
        'SkillAcademy',
        'SkillTrade',
        'Talentics',
        'Tech in Asia',
        'Urbanhire',
        'Website Company',
        'Other'
    ];

    public $provinces = [
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

    public $countries = [
        'Afghanistan' => [
            'Balkh', 'Bamyan', 'Fayzabad', 'Ghazni', 'Herat', 'Jalalabad', 'Kabul', 'Kandahar',
            'Kunduz', 'Lashkar Gah', 'Mazar-i-Sharif', 'Pul-e Khumri', 'Taloqan'
        ],
        'Albania' => [
            'Durrës', 'Elbasan', 'Fier', 'Korçë', 'Shkodër', 'Tirana', 'Vlorë'
        ],
        'Algeria' => [
            'Aïn Defla', 'Aïn Témouchent', 'Algiers', 'Annaba', 'Batna', 'Béjaïa',
            'Biskra', 'Blida', 'Bordj Bou Arréridj', 'Boumerdès', 'Constantine', 'Djelfa',
            'El Oued', 'El Tarf', 'Ghardaïa', 'Guelma', 'Jijel', 'M\'Sila', 'Mascara', 'Mila',
            'Mostaganem', 'Naâma', 'Oran', 'Ouargla', 'Relizane', 'Sétif', 'Sidi Bel Abbès',
            'Skikda', 'Tébessa', 'Tiaret', 'Timimoun', 'Tindouf', 'Tipaza', 'Tissemsilt', 'Tlemcen'
        ],
        'Angola' => [
            'Benguela', 'Cabinda', 'Huambo', 'Kuito', 'Lobito', 'Luanda', 'Lubango',
            'Malanje', 'Mocâmedes', 'Saurimo', 'Sumbe'
        ],
        'Argentina' => [
            'Bahía Blanca', 'Buenos Aires', 'Catamarca', 'Comodoro Rivadavia', 'Concordia',
            'Corrientes', 'Córdoba', 'Formosa', 'La Plata', 'La Rioja', 'Mar del Plata', 'Mendoza',
            'Neuquén', 'Paraná', 'Posadas', 'Rawson', 'Resistencia', 'Río Cuarto', 'Río Gallegos',
            'Rosario', 'Salta', 'San Fernando del Valle de Catamarca', 'San Juan', 'San Luis',
            'San Miguel de Tucumán', 'San Nicolás de los Arroyos', 'San Rafael', 'San Salvador de Jujuy',
            'Santa Fe', 'Santa Rosa', 'Santiago del Estero', 'Tandil', 'Ushuaia', 'Viedma',
            'Villa Carlos Paz', 'Villa Mercedes'
        ],
        'Armenia' => [
            'Abovyan', 'Ararat', 'Armavir', 'Ashtarak', 'Dilijan', 'Gavar', 'Goris', 'Gyumri',
            'Hrazdan', 'Ijevan', 'Kapan', 'Masis', 'Vagharshapat', 'Vanadzor', 'Yerevan'
        ],
        'Australia' => [
            'Adelaide', 'Albany', 'Albury', 'Ballarat', 'Bendigo', 'Brisbane', 'Broken Hill',
            'Bunbury', 'Bundaberg', 'Cairns', 'Canberra', 'Coffs Harbour', 'Darwin', 'Devonport',
            'Dubbo', 'Geelong', 'Geraldton', 'Gladstone', 'Gold Coast', 'Hervey Bay', 'Hobart',
            'Kalgoorlie', 'Launceston', 'Lismore', 'Mackay', 'Maitland', 'Melbourne', 'Mildura',
            'Mount Gambier', 'Murray Bridge', 'Newcastle', 'Nowra', 'Perth', 'Port Augusta',
            'Port Macquarie', 'Rockhampton', 'Shepparton', 'Sunshine Coast', 'Sydney', 'Tamworth',
            'Toowoomba', 'Townsville', 'Traralgon', 'Wagga Wagga', 'Warrnambool', 'Whyalla',
            'Wollongong'
        ],
        'Austria' => [
            'Amstetten', 'Ansfelden', 'Baden', 'Bludenz', 'Braunau am Inn', 'Bregenz', 'Dornbirn',
            'Eisenstadt', 'Feldkirch', 'Gmunden', 'Graz', 'Hallein', 'Hohenems', 'Innsbruck',
            'Kapfenberg', 'Kitzbühel', 'Klagenfurt', 'Klosterneuburg', 'Krems', 'Kufstein',
            'Leoben', 'Leonding', 'Lienz', 'Linz', 'Ried im Innkreis', 'Saalfelden', 'Salzburg',
            'Sankt Pölten', 'Sankt Veit an der Glan', 'Schwaz', 'Schwechat', 'Spittal an der Drau',
            'Steyr', 'Stockerau', 'Telfs', 'Ternitz', 'Traiskirchen', 'Traun', 'Tulln', 'Vienna',
            'Villach', 'Vöcklabruck', 'Wels', 'Wiener Neustadt', 'Wolfsberg', 'Wörgl', 'Zell am See'
        ],
        'Azerbaijan' => [
            'Agdam', 'Baku', 'Ganja', 'Khankendi', 'Lankaran', 'Mingachevir', 'Nakhchivan',
            'Quba', 'Qusar', 'Shaki', 'Shirvan', 'Sumqayit', 'Xankəndi', 'Yevlakh'
        ],
        'Bahrain' => [
            'A\'ali', 'Arad', 'Isa Town', 'Manama', 'Muharraq', 'Riffa', 'Sitra'
        ],
        'Bangladesh' => [
            'Barishal', 'Bogra', 'Chittagong', 'Comilla', 'Dhaka', 'Jessore', 'Khulna',
            'Mymensingh', 'Narayanganj', 'Rajshahi', 'Rangpur', 'Sylhet', 'Tongi'
        ],
        'Belgium' => [
            'Aalst', 'Anderlecht', 'Antwerp', 'Beveren', 'Binche', 'Braine-l\'Alleud', 'Bruges',
            'Brussels', 'Charleroi', 'Dendermonde', 'Dilbeek', 'Etterbeek', 'Geel',
            'Genk', 'Geraardsbergen', 'Ghent', 'Halle', 'Hasselt', 'Heist-op-den-Berg', 'Herstal',
            'Ieper', 'Ixelles', 'Jette', 'Kortrijk', 'La Louvière', 'Leuven', 'Liège', 'Lier',
            'Lokeren', 'Lommel', 'Mechelen', 'Molenbeek-Saint-Jean', 'Mons', 'Mouscron', 'Namur',
            'Ninove', 'Ostend', 'Roeselare', 'Saint-Josse-ten-Noode', 'Schaerbeek', 'Seraing',
            'Sint-Niklaas', 'Sint-Pieters-Leeuw', 'Sint-Truiden', 'Tienen', 'Tournai', 'Turnhout',
            'Uccle', 'Verviers', 'Wavre'
        ],
        'Bhutan' => [
            'Chhukha', 'Gelephu', 'Jakar', 'Phuntsholing', 'Samdrup Jongkhar', 'Thimphu',
            'Trongsa', 'Trashigang', 'Wamrong'
        ],
        'Bolivia' => [
            'Cochabamba', 'El Alto', 'La Paz', 'Oruro', 'Potosí', 'Santa Cruz de la Sierra',
            'Sucre', 'Tarija', 'Trinidad'
        ],
        'Brazil' => [
            'Ananindeua', 'Aparecida de Goiânia', 'Aracaju', 'Belém', 'Belo Horizonte', 'Brasília',
            'Campinas', 'Campo Grande', 'Campos dos Goytacazes', 'Carapicuíba', 'Caxias do Sul',
            'Contagem', 'Cuiabá', 'Curitiba', 'Diadema', 'Duque de Caxias', 'Feira de Santana',
            'Florianópolis', 'Fortaleza', 'Goiânia', 'Guarulhos', 'Jaboatão dos Guararapes',
            'João Pessoa', 'Joinville', 'Londrina', 'Macapá', 'Maceió', 'Manaus', 'Mauá', 'Natal',
            'Niterói', 'Nova Iguaçu', 'Osasco', 'Porto Alegre', 'Recife', 'Ribeirão Preto',
            'Rio de Janeiro', 'Salvador', 'Santo André', 'Santos', 'São Bernardo do Campo',
            'São Gonçalo', 'São João de Meriti', 'São José dos Campos', 'São Luís', 'São Paulo',
            'Serra', 'Sorocaba', 'Teresina', 'Uberlândia', 'Vila Velha'
        ],
        'Brunei' => [
            'Bandar Seri Begawan', 'Bangar', 'Kuala Belait', 'Muara', 'Seria', 'Tutong'
        ],
        'Bulgaria' => [
            'Asenovgrad', 'Aytos', 'Balchik', 'Blagoevgrad', 'Botevgrad', 'Burgas', 'Chirpan',
            'Dimitrovgrad', 'Dobrich', 'Gabrovo', 'Gorna Oryahovitsa', 'Gotse Delchev', 'Haskovo',
            'Karnobat', 'Karlovo', 'Kavarna', 'Kazanlak', 'Kozloduy', 'Krichim', 'Kyustendil',
            'Lovech', 'Lom', 'Montana', 'Novi Pazar', 'Panagyurishte', 'Pazardzhik', 'Pernik',
            'Petrich', 'Pleven', 'Plovdiv', 'Radomir', 'Razgrad', 'Ruse', 'Samokov', 'Sevlievo',
            'Shumen', 'Silistra', 'Sliven', 'Smolyan', 'Sofia', 'Sopot', 'Svishtov', 'Stara Zagora',
            'Targovishte', 'Troyan', 'Varna', 'Veliko Tarnovo', 'Velingrad', 'Vidin', 'Vratsa',
            'Yambol'
        ],
        'Cambodia' => [
            'Battambang', 'Kampong Cham', 'Phnom Penh', 'Poipet', 'Siem Reap', 'Sihanoukville',
            'Sisophon', 'Svay Rieng', 'Ta Khmau', 'Takeo'
        ],
        'Cameroon' => [
            'Bafoussam', 'Bamenda', 'Douala', 'Garoua', 'Kousséri', 'Maroua', 'Ngaoundéré',
            'Yaoundé'
        ],
        'Canada' => [
            'Abbotsford', 'Airdrie', 'Barrie', 'Brantford', 'Calgary', 'Cambridge', 'Charlottetown',
            'Chilliwack', 'Courtenay', 'Edmonton', 'Fort McMurray', 'Fredericton', 'Grande Prairie',
            'Guelph', 'Halifax', 'Hamilton', 'Kamloops', 'Kelowna', 'Kingston', 'Kitchener',
            'Lethbridge', 'London', 'Medicine Hat', 'Moncton', 'Montreal', 'Nanaimo', 'Oshawa',
            'Ottawa', 'Prince George', 'Quebec City', 'Red Deer', 'Regina', 'Saguenay', 'Saint John',
            'Saint-Jérôme', 'Sarnia', 'Saskatoon', 'Sherbrooke', 'Spruce Grove', 'Sudbury',
            'Thunder Bay', 'Toronto', 'Trois-Rivières', 'Vancouver', 'Vernon', 'Victoria',
            'Whitehorse', 'Windsor', 'Winnipeg', 'Yellowknife'
        ],
        'Chile' => [
            'Ancud', 'Angol', 'Antofagasta', 'Arica', 'Calama', 'Calafate', 'Castro', 'Cauquenes',
            'Chillán', 'Cochrane', 'Concepción', 'Copiapó', 'Coronel', 'Coyhaique', 'Curicó',
            'Iquique', 'La Serena', 'Lautaro', 'Lebu', 'Linares', 'Los Ángeles', 'Melipilla',
            'Natales', 'Osorno', 'Ovalle', 'Porvenir', 'Puerto Aysén', 'Puerto Montt',
            'Puerto Williams', 'Punta Arenas', 'Quilpué', 'Rancagua', 'Rawson', 'Rengo',
            'Río Gallegos', 'San Antonio', 'San Felipe', 'San Fernando', 'Santiago', 'Santa Rosa',
            'Talca', 'Temuco', 'Tortel', 'Ushuaia', 'Valdivia', 'Valparaíso', 'Viedma',
            'Villa O\'Higgins', 'Villarrica', 'Viña del Mar'
        ],
        'China' => [
            'Beijing', 'Changchun', 'Changsha', 'Changhua', 'Chengdu', 'Chiayi', 'Chongqing',
            'Dalian', 'Dongguan', 'Foshan', 'Fuzhou', 'Guangzhou', 'Guiyang', 'Haikou', 'Hangzhou',
            'Harbin', 'Hefei', 'Hohhot', 'Hong Kong', 'Hsinchu', 'Jinan', 'Kaohsiung', 'Keelung',
            'Kunming', 'Lanzhou', 'Lhasa', 'Macau', 'Nanchang', 'Nanjing', 'Nanning', 'Ningbo',
            'Pingtung', 'Qingdao', 'Sakai', 'Shanghai', 'Shenyang', 'Shenzhen', 'Shijiazhuang',
            'Taichung', 'Tainan', 'Taipei', 'Taiyuan', 'Taoyuan', 'Tianjin', 'Urumqi', 'Wuhan',
            'Xi\'an', 'Xining', 'Yinchuan', 'Yunlin', 'Zhengzhou'
        ],
        'Colombia' => [
            'Barranquilla', 'Bogotá', 'Bucaramanga', 'Cali', 'Cartagena', 'Cúcuta', 'Ibagué',
            'Manizales', 'Medellín', 'Pereira', 'Soledad', 'Villavicencio'
        ],
        'Croatia' => [
            'Ajdovščina', 'Belišće', 'Benkovac', 'Biograd na Moru', 'Bjelovar', 'Buje', 'Buzet',
            'Cres', 'Crikvenica', 'Čakovec', 'Daruvar', 'Delnice', 'Đakovo', 'Donja Stubica',
            'Drniš', 'Duga Resa', 'Dugo Selo', 'Dubrovnik', 'Dvor', 'Glina', 'Gospić', 'Grobnik',
            'Hrvatska Kostajnica', 'Hvar', 'Ilok', 'Imotski', 'Ivanec', 'Ivanić-Grad', 'Karlovac',
            'Koprivnica', 'Kutina', 'Makarska', 'Našice', 'Nova Gradiška', 'Ogulin', 'Osijek',
            'Petrinja', 'Požega', 'Pula', 'Rijeka', 'Samobor', 'Sibenik', 'Sisak', 'Slatina',
            'Slavonski Brod', 'Split', 'Varaždin', 'Virovitica', 'Vukovar', 'Zadar', 'Zagreb',
            'Zaprešić', 'Županja'
        ],
        'Cuba' => [
            'Camagüey', 'Cienfuegos', 'Guantánamo', 'Havana', 'Holguín', 'Matanzas', 'Pinar del Río',
            'Santiago de Cuba', 'Santa Clara'
        ],
        'Czech Republic' => [
            'Brno', 'Budějovice', 'Chomutov', 'Český Těšín', 'České Budějovice',
            'Děčín', 'Frýdek-Místek', 'Havířov', 'Hradec Králové', 'Jablonec nad Nisou',
            'Jihlava', 'Karviná', 'Karvina', 'Kladno', 'Liberec', 'Mladá Boleslav', 'Most',
            'Olomouc', 'Opava', 'Ostrava', 'Pardubice', 'Plzeň', 'Prague', 'Prostějov',
            'Přerov', 'Teplice', 'Třebíč', 'Třinec', 'Ústí nad Labem', 'Vsetín', 'Zlín',
            'Znojmo'
        ],
        'Denmark' => [
            'Aalborg', 'Aarhus', 'Copenhagen', 'Esbjerg', 'Fredericia', 'Frederikshavn', 'Haderslev',
            'Helsingør', 'Herning', 'Hillerød', 'Hjørring', 'Holbæk', 'Holstebro', 'Horsens',
            'Kolding', 'Køge', 'Nakskov', 'Næstved', 'Nykøbing Falster', 'Odense', 'Randers',
            'Ringkøbing', 'Roskilde', 'Rønne', 'Silkeborg', 'Skive', 'Slagelse', 'Svendborg',
            'Sønderborg', 'Taastrup', 'Varde', 'Vejle', 'Viborg'
        ],
        'Egypt' => [
            '10th of Ramadan City', '6th of October City', 'Abnub', 'Abu Kabir', 'Akhmim', 'Al-Badari',
            'Al-Fashn', 'Al-Hamidiyya', 'Al-Matayy', 'Al-Minya', 'Al-Qusiyya', 'Alexandria',
            'Arish', 'Aswan', 'Asyut', 'Bani Mazar', 'Banha', 'Beni Suef', 'Bilbeis', 'Cairo',
            'Damanhur', 'Damietta', 'Desouk', 'Edfu', 'El Mahalla El Kubra', 'Faiyum', 'Girga',
            'Giza', 'Hurghada', 'Ismailia', 'Kafr el-Dawwar', 'Kafr el-Sheikh', 'Luxor', 'Mallawi',
            'Manfalut', 'Mansoura', 'Marsa Matruh', 'Matareya', 'Minya', 'Mit Ghamr', 'Port Said',
            'Qalyub', 'Qena', 'Samalut', 'Shibin El Kom', 'Shubra El Kheima', 'Sohag', 'Suez',
            'Tanta', 'Zagazig'
        ],
        'Estonia' => [
            'Elva', 'Haapsalu', 'Jõgeva', 'Jõhvi', 'Kallaste', 'Karksi-Nuia',
            'Kärdla', 'Keila', 'Kiviõli', 'Kohtla-Järve', 'Kuressaare', 'Lihula', 'Maardu',
            'Mustvee', 'Narva', 'Otepää', 'Paide', 'Pärnu', 'Põltsamaa', 'Põlva', 'Rakvere',
            'Rapla', 'Sauga', 'Saue', 'Sillamäe', 'Sindi', 'Tallinn', 'Tapa', 'Tartu', 'Tõrva',
            'Valga', 'Viljandi', 'Võru'
        ],
        'Ethiopia' => [
            'Addis Ababa', 'Adama', 'Bahir Dar', 'Dire Dawa', 'Gondar', 'Hawassa', 'Mekelle',
            'Jimma', 'Dessie'
        ],
        'Finland' => [
            'Espoo', 'Forssa', 'Hämeenlinna', 'Hanko', 'Heinola', 'Helsinki', 'Hyvinkää',
            'Iisalmi', 'Imatra', 'Järvenpää', 'Joensuu', 'Jyväskylä', 'Kaarina', 'Kajaani',
            'Kangasala', 'Karkkila', 'Kemi', 'Kerava', 'Kokkola', 'Kotka', 'Kouvola',
            'Kuopio', 'Kuusamo', 'Lahti', 'Lappeenranta', 'Lieksa', 'Lohja', 'Mikkeli',
            'Naantali', 'Nokia', 'Nurmijärvi', 'Oulu', 'Pori', 'Porvoo', 'Raisio',
            'Riihimäki', 'Rovaniemi', 'Salo', 'Savonlinna', 'Seinäjoki', 'Tampere',
            'Tornio', 'Turku', 'Uusikaupunki', 'Vaasa', 'Vantaa'
        ],
        'France' => [
            'Aix-en-Provence', 'Amiens', 'Angers', 'Annecy', 'Argenteuil', 'Asnières-sur-Seine',
            'Avignon', 'Bordeaux', 'Boulogne-Billancourt', 'Brest', 'Caen', 'Clermont-Ferrand',
            'Colombes', 'Créteil', 'Dijon', 'Dunkirk', 'Grenoble', 'Le Havre', 'Le Mans', 'Lille',
            'Limoges', 'Lyon', 'Marseille', 'Montpellier', 'Montreuil', 'Mulhouse', 'Nancy',
            'Nanterre', 'Nantes', 'Nice', 'Nîmes', 'Orléans', 'Paris', 'Perpignan', 'Poitiers',
            'Reims', 'Rennes', 'Roubaix', 'Rouen', 'Saint-Denis', 'Saint-Étienne', 'Strasbourg',
            'Toulon', 'Toulouse', 'Tourcoing', 'Tours', 'Versailles', 'Villeurbanne'
        ],
        'Georgia' => [
            'Batumi', 'Gori', 'Kutaisi', 'Poti', 'Rustavi', 'Sukhumi', 'Tbilisi', 'Tskhinvali',
            'Zugdidi'
        ],
        'Germany' => [
            'Aachen', 'Augsburg', 'Berlin', 'Bielefeld', 'Bochum', 'Bonn', 'Braunschweig',
            'Bremen', 'Chemnitz', 'Cologne', 'Dortmund', 'Dresden', 'Duisburg', 'Düsseldorf',
            'Erfurt', 'Essen', 'Frankfurt', 'Freiburg', 'Gelsenkirchen', 'Hagen', 'Halle',
            'Hamburg', 'Hamm', 'Hannover', 'Karlsruhe', 'Kassel', 'Kiel', 'Krefeld', 'Leipzig',
            'Leverkusen', 'Lübeck', 'Ludwigshafen', 'Magdeburg', 'Mainz', 'Mannheim', 'Mönchengladbach',
            'Mülheim', 'Munich', 'Münster', 'Nuremberg', 'Oberhausen', 'Oldenburg', 'Osnabrück',
            'Potsdam', 'Rostock', 'Saarbrücken', 'Solingen', 'Stuttgart', 'Wiesbaden', 'Wuppertal'
        ],
        'Ghana' => [
            'Accra', 'Adenta', 'Akim Oda', 'Akwatia', 'Asamankese', 'Ashaiman', 'Begoro',
            'Bolgatanga', 'Cape Coast', 'Dodowa', 'Ho', 'Kade', 'Kintampo', 'Koforidua',
            'Konongo', 'Kumasi', 'Madina', 'Mpraeso', 'Nkawkaw', 'Obuasi', 'Prestea',
            'Sekondi-Takoradi', 'Suhum', 'Sunyani', 'Tamale', 'Tarkwa', 'Techiman', 'Tema', 'Wa'
        ],
        'Greece' => [
            'Athens', 'Corfu', 'Heraklion', 'Ioannina', 'Kalamata', 'Larissa', 'Patras',
            'Piraeus', 'Rhodes', 'Thessaloniki', 'Volos'
        ],
        'Hungary' => [
            'Baja', 'Békéscsaba', 'Budapest', 'Cegléd', 'Dabas', 'Debrecen', 'Eger', 'Érd',
            'Esztergom', 'Gödöllő', 'Gyöngyös', 'Győr', 'Hódmezővásárhely', 'Kaposvár',
            'Kazincbarcika', 'Kecskemét', 'Kiskunfélegyháza', 'Kiskunhalas', 'Miskolc',
            'Nagykanizsa', 'Nyíregyháza', 'Orosháza', 'Ózd', 'Pápa', 'Pécs', 'Salgótarján',
            'Sárvár', 'Sopron', 'Székesfehérvár', 'Szeged', 'Szekszárd', 'Szentes', 'Szolnok',
            'Szombathely', 'Szigethalom', 'Szigetszentmiklós', 'Tatabánya', 'Vác', 'Várpalota',
            'Zalaegerszeg'
        ],
        'India' => [
            'Ahmadnagar', 'Ahmedabad', 'Agra', 'Akola', 'Amritsar', 'Aurangabad',
            'Bangalore', 'Bhopal', 'Bhusawal', 'Chennai', 'Delhi', 'Dhule', 'Faridabad',
            'Ghaziabad', 'Guntur', 'Guwahati', 'Hyderabad', 'Ichalkaranji', 'Indore',
            'Jaipur', 'Jalgaon', 'Jalna', 'Kalyan-Dombivali', 'Kanpur', 'Kolkata', 'Kolhapur',
            'Latur', 'Lucknow', 'Ludhiana', 'Malegaon', 'Meerut', 'Mumbai', 'Nagpur', 'Nashik',
            'Navi Mumbai', 'Patna', 'Pimpri-Chinchwad', 'Pune', 'Rajkot', 'Ranchi', 'Sangli',
            'Solapur', 'Srinagar', 'Surat', 'Thane', 'Ulhasnagar', 'Vadodara',
            'Varanasi', 'Vasai-Virar', 'Vijayawada', 'Visakhapatnam'
        ],
        'Iran' => [
            'Ahvaz', 'Ardabil', 'Bandar Abbas', 'Gorgan', 'Hamadan', 'Isfahan', 'Karaj',
            'Kermanshah', 'Mashhad', 'Qom', 'Rasht', 'Sari', 'Shiraz', 'Tabriz', 'Teheran',
            'Urmia', 'Yazd', 'Zahidan', 'Zanjan'
        ],
        'Iraq' => [
            'Baghdad', 'Basra', 'Erbil', 'Fallujah', 'Karbala', 'Kirkuk', 'Mosul', 'Najaf',
            'Nasiriyah', 'Ramadi', 'Samarra', 'Sulaimaniyah', 'Tikrit'
        ],
        'Ireland' => [
            'Cork', 'Dublin', 'Galway', 'Limerick', 'Waterford'
        ],
        'Israel' => [
            'Acre (Akko)', 'Ashdod', 'Ashkelon', 'Be\'er Sheva', 'Beit She\'an', 'Bnei Brak',
            'Dimona', 'Eilat', 'Hadera', 'Haifa', 'Herzliya', 'Holon', 'Jerusalem', 'Karmiel',
            'Kfar Saba', 'Lod', 'Modi\'in-Maccabim-Re\'ut', 'Nahariya', 'Nazareth (Natzrat)',
            'Netanya', 'Petah Tikva', 'Raanana', 'Ramat Gan', 'Rehovot', 'Rishon LeZion',
            'Tel Aviv-Yafo', 'Tiberias (Tveria)', 'Zikhron Yaakov'
        ],
        'Italy' => [
            'Ancona', 'Andria', 'Arezzo', 'Bari', 'Bergamo', 'Bologna', 'Bolzano', 'Brescia',
            'Brindisi', 'Cagliari', 'Catania', 'Cesena', 'Ferrara', 'Florence', 'Foggia',
            'Forlì', 'Genoa', 'La Spezia', 'Lecce', 'Livorno', 'Messina', 'Milan', 'Modena',
            'Monza', 'Naples', 'Novara', 'Padua', 'Palermo', 'Parma', 'Perugia', 'Pesaro',
            'Pescara', 'Piacenza', 'Prato', 'Ravenna', 'Reggio Calabria', 'Reggio Emilia',
            'Rimini', 'Rome', 'Salerno', 'Sassari', 'Syracuse', 'Terni', 'Trento', 'Trieste',
            'Turin', 'Udine', 'Venice', 'Verona', 'Vicenza'
        ],
        'Japan' => [
            'Asahikawa', 'Chiba', 'Fukuoka', 'Fujisawa', 'Fukuyama', 'Funabashi', 'Gifu',
            'Hachioji', 'Hadera', 'Hakodate', 'Hamamatsu', 'Hiroshima', 'Honcho', 'Ichikawa',
            'Iwaki', 'Kagoshima', 'Kawagoe', 'Kawaguchi', 'Kawasaki', 'Kitakyushu', 'Kobe',
            'Kochi', 'Koriyama', 'Kumamoto', 'Kurashiki', 'Kyoto', 'Machida', 'Matsudo',
            'Matsuyama', 'Naha', 'Nagoya', 'Niigata', 'Nishinomiya', 'Okayama', 'Omiya',
            'Osaka', 'Sagamihara', 'Saitama', 'Sakai', 'Sapporo', 'Sendai', 'Shimonoseki',
            'Shizuoka', 'Takamatsu', 'Takasaki', 'Tokyo', 'Toyohashi', 'Toyonaka', 'Toyota',
            'Utsunomiya', 'Yokohama'
        ],
        'Kamerun' => [
            'Bafoussam', 'Bamenda', 'Douala', 'Garoua', 'Kousséri', 'Maroua', 'Ngaoundéré',
            'Yaoundé'
        ],
        'Kazakhstan' => [
            'Almaty', 'Astana', 'Atyrau', 'Karaganda', 'Kostanay', 'Kyzylorda', 'Oral',
            'Oskemen', 'Pavlodar', 'Semey', 'Shymkent', 'Taraz', 'Temirtau', 'Turkistan'
        ],
        'Kenya' => [
            'Bungoma', 'Busia', 'Eldoret', 'Embu', 'Garissa', 'Homa Bay', 'Isiolo', 'Kajiado',
            'Kakamega', 'Kericho', 'Kiambu', 'Kilifi', 'Kirinyaga', 'Kisii', 'Kisumu', 'Kitale',
            'Kwale', 'Lamu', 'Machakos', 'Malindi', 'Mandera', 'Marsabit', 'Meru', 'Mombasa',
            'Murang\'a', 'Nairobi', 'Nakuru', 'Narok', 'Nyeri', 'Taita Taveta', 'Tana River',
            'Tharaka Nithi', 'Thika', 'Wajir'
        ],
        'Kirgizstan' => [
            'Bishkek', 'Jalal-Abad', 'Karakol', 'Naryn', 'Osh', 'Talas', 'Tokmok', 'Uzgen'
        ],
        'Kolombia' => [
            'Barranquilla', 'Bogotá', 'Bucaramanga', 'Cali', 'Cartagena', 'Cúcuta', 'Ibagué',
            'Manizales', 'Medellín', 'Pereira', 'Soledad', 'Villavicencio'
        ],
        'Korea Utara' => [
            'Chongjin', 'Hamhung', 'Hyesan', 'Kaesong', 'Nampo', 'Pyongyang', 'Rason', 'Sinuiju',
            'Wonsan'
        ],
        'Korea Selatan' => [
            'Ansan', 'Anyang', 'Asan', 'Busan', 'Bucheon', 'Changwon', 'Cheonan', 'Cheongju',
            'Chuncheon', 'Daegu', 'Daejeon', 'Gangneung', 'Gimhae', 'Goyang', 'Gumi', 'Gunsan',
            'Gwangju', 'Gwangmyeong', 'Gyeongju', 'Gyeongsan', 'Hwaseong', 'Iksan', 'Incheon',
            'Jeju', 'Jeonju', 'Jinju', 'Mokpo', 'Namyangju', 'Osan', 'Pyeongtaek', 'Sejong',
            'Seoul', 'Seongnam', 'Siheung', 'Suwon', 'Ulsan', 'Wonju', 'Yangju', 'Yongin'
        ],
        'Kuba' => [
            'Camagüey', 'Cienfuegos', 'Guantánamo', 'Havana', 'Holguín', 'Matanzas', 'Pinar del Río',
            'Santiago de Cuba', 'Santa Clara'
        ],
        'Kuwait' => [
            'Al Ahmadi', 'Al Farwaniyah', 'Hawalli', 'Jahra', 'Kuwait City', 'Salmiya'
        ],
        'Laos' => [
            'Luang Prabang', 'Pakse', 'Savannakhet', 'Thakhek', 'Vientiane', 'Xam Neua'
        ],
        'Latvia' => [
            'Aizkraukle', 'Aizpute', 'Ape', 'Auce', 'Baldone', 'Balvi', 'Bauska', 'Brocēni',
            'Carnikava', 'Cēsis', 'Cesvaine', 'Dagda', 'Daugavpils', 'Dobele', 'Durbe',
            'Engure', 'Ērgļi', 'Garkalne', 'Grobiņa', 'Gulbene', 'Iecava', 'Ikšķile',
            'Inčukalns', 'Jaunjelgava', 'Jaunpiebalga', 'Jaunpils', 'Jēkabpils', 'Jelgava',
            'Jūrmala', 'Krāslava', 'Kuldīga', 'Līvāni', 'Liepāja', 'Ludza',
            'Madona', 'Ogre', 'Olaine', 'Preiļi', 'Rēzekne', 'Riga', 'Saldus', 'Salaspils',
            'Sigulda', 'Smiltene', 'Talsi', 'Tukums', 'Valmiera', 'Ventspils'
        ],
        'Lebanon' => [
            'Baabda', 'Baalbek', 'Beirut', 'Jounieh', 'Saida', 'Tripoli', 'Tyre', 'Zahle'
        ],
        'Lithuania' => [
            'Alytus', 'Anykščiai', 'Biržai', 'Druskininkai', 'Elektrėnai',
            'Gargždai', 'Garliava', 'Grigiškės', 'Jonava', 'Joniškis', 'Jurbarkas',
            'Kaišiadorys', 'Kaunas', 'Kazlų Rūda', 'Kėdainiai', 'Kelmė', 'Klaipėda',
            'Kretinga', 'Kupiškis', 'Kuršėnai', 'Lentvaris', 'Mažeikiai', 'Marijampolė',
            'Molėtai', 'Neringa', 'Naujoji Akmenė', 'Pagėgiai', 'Palanga', 'Panevėžys',
            'Pasvalys', 'Prienai', 'Plungė', 'Radviliškis', 'Raseiniai', 'Rietavas',
            'Rokiškis', 'Skuodas', 'Šalčininkai', 'Šiauliai', 'Šilutė', 'Širvintos',
            'Tauragė', 'Telšiai', 'Ukmergė', 'Utena', 'Varėna', 'Vilkaviškis',
            'Vilnius', 'Visaginas', 'Zarasai'
        ],
        'Madagascar' => [
            'Antananarivo', 'Antsirabe', 'Fianarantsoa', 'Mahajanga', 'Toamasina',
            'Toliara'
        ],
        'Malaysia' => [
            'Alor Setar', 'Bandar Seri Begawan', 'Batu Pahat', 'Cameron Highlands',
            'Fraser\'s Hill', 'Genting Highlands', 'George Town', 'Ipoh', 'Johor Bahru', 'Kangar',
            'Klang', 'Kluang', 'Kota Bharu', 'Kota Kinabalu', 'Kota Tinggi', 'Kuala Lumpur',
            'Kuala Selangor', 'Kuala Terengganu', 'Kuching', 'Kulim', 'Kulai', 'Lumut', 'Malacca',
            'Miri', 'Muar', 'Petaling Jaya', 'Pontian', 'Pulau Carey', 'Pulau Ketam',
            'Pulau Langkawi', 'Pulau Pangkor', 'Pulau Perhentian', 'Pulau Redang', 'Pulau Tioman',
            'Sandakan', 'Segamat', 'Seremban', 'Shah Alam', 'Sitiawan', 'Subang Jaya',
            'Sungai Siput', 'Taiping', 'Tapah', 'Tanjung Malim', 'Teluk Intan'
        ],
        'Maldives' => [
            'Addu City', 'Fuvahmulah', 'Hithadhoo', 'Malé', 'Thinadhoo', 'Villingili'
        ],
        'Mexico' => [
            'Acapulco', 'Aguascalientes', 'Apodaca', 'Cancún', 'Chihuahua', 'Ciudad López Mateos',
            'Coacalco', 'Culiacán', 'Cuernavaca', 'Durango', 'Ecatepec', 'General Escobedo',
            'Guadalajara', 'Guadalupe', 'Hermosillo', 'Irapuato', 'Juárez', 'León', 'Matamoros',
            'Mazatlán', 'Mérida', 'Mexicali', 'Mexico City', 'Monterrey', 'Morelia', 'Móstoles',
            'Naucalpan', 'Nezahualcóyotl', 'Nuevo Laredo', 'Puebla', 'Querétaro', 'Reynosa',
            'San Luis Potosí', 'San Nicolás de los Garza', 'Santa Catarina',
            'Soledad de Graciano Sánchez', 'Tampico', 'Tijuana', 'Tlalnepantla', 'Toluca',
            'Tonalá', 'Torreón', 'Tuxtla Gutiérrez', 'Veracruz', 'Xalapa', 'Xico', 'Zapopan',
            'Zaragoza'
        ],
        'Mongolia' => [
            'Arvaikheer', 'Choibalsan', 'Erdenet', 'Hovd', 'Mörön', 'Sükhbaatar', 'Ulaangom',
            'Ulaanbaatar'
        ],
        'Morocco' => [
            'Agadir', 'Beni Mellal', 'Berkane', 'Berrechid', 'Casablanca', 'El Jadida',
            'Errachidia', 'Fez', 'Guelmim', 'Kenitra', 'Khemisset', 'Khouribga', 'Ksar El Kebir',
            'Larache', 'Marrakech', 'Meknes', 'Mohammedia', 'Nador', 'Ouarzazate', 'Oujda',
            'Rabat', 'Safi', 'Sale', 'Settat', 'Sidi Bennour', 'Sidi Kacem', 'Sidi Slimane',
            'Skhirat', 'Tangier', 'Taza', 'Temara', 'Tetouan', 'Tiflet'
        ],
        'Myanmar' => [
            'Bago', 'Hpa-an', 'Lashio', 'Mandalay', 'Mawlamyine', 'Monywa', 'Myitkyina',
            'Naypyidaw', 'Pathein', 'Sittwe', 'Taunggyi', 'Yangon'
        ],
        'Nepal' => [
            'Bharatpur', 'Biratnagar', 'Butwal', 'Dharan', 'Hetauda', 'Janakpur', 'Kathmandu',
            'Lalitpur', 'Pokhara', 'Siddharthanagar'
        ],
        'Netherlands' => [
            'Alkmaar', 'Almere', 'Amersfoort', 'Amstelveen', 'Amsterdam', 'Apeldoorn', 'Arnhem',
            'Breda', 'Delft', 'Dordrecht', 'Eindhoven', 'Enschede', 'Groningen', 'Haarlem',
            'Hengelo', 'Hilversum', 'Hoofddorp', 'Hoorn', 'Leiden', 'Leeuwarden', 'Maastricht',
            'Nijmegen', 'Purmerend', 'Rotterdam', 'The Hague', 'Tilburg', 'Utrecht', 'Venlo',
            'Vlaardingen', 'Zaandam', 'Zaanstad', 'Zoetermeer', 'Zwolle'
        ],
        'Nigeria' => [
            'Aba', 'Abuja', 'Ado', 'Ado Ekiti', 'Akure', 'Benin City', 'Emure', 'Ibadan',
            'Ife', 'Igbara Odo', 'Igbara Oke', 'Ikare', 'Ikere', 'Ikerre', 'Ilawe', 'Ilesa',
            'Ilorin', 'Ise', 'Kaduna', 'Kano', 'Lagos', 'Maiduguri', 'Ode', 'Ogbomoso', 'Ondo',
            'Owo', 'Oyo', 'Port Harcourt', 'Zaria'
        ],
        'Norway' => [
            'Ålesund', 'Arendal', 'Askøy', 'Bærum', 'Bergen', 'Drammen', 'Egersund',
            'Fredrikstad', 'Gjøvik', 'Grimstad', 'Halden', 'Hamar', 'Haugesund', 'Horten',
            'Kongsberg', 'Kongsvinger', 'Kristiansand', 'Kristiansund', 'Larvik', 'Lillehammer',
            'Mo i Rana', 'Moss', 'Narvik', 'Oslo', 'Sandefjord', 'Sandnes', 'Skien', 'Stavanger',
            'Steinkjer', 'Tromsø', 'Trondheim', 'Tønsberg'
        ],
        'Oman' => [
            'Bawshar', 'Muscat', 'Nizwa', 'Rustaq', 'Salalah', 'Sohar', 'Suwayq'
        ],
        'Pakistan' => [
            'Faisalabad', 'Gujranwala', 'Hyderabad', 'Islamabad', 'Karachi', 'Lahore', 'Multan',
            'Peshawar', 'Quetta', 'Rawalpindi', 'Sialkot', 'Sukkur'
        ],
        'Palestina' => [
            'Bethlehem', 'Gaza City', 'Hebron (Al-Khalil)', 'Jenin', 'Jericho (Ariha)', 'Nablus',
            'Ramallah', 'Tulkarm'
        ],
        'Peru' => [
            'Arequipa', 'Callao', 'Chiclayo', 'Cusco', 'Huancayo', 'Iquitos', 'Lima', 'Piura',
            'Trujillo'
        ],
        'Philippines' => [
            'Antipolo', 'Bacoor', 'Bacolod', 'Cagayan de Oro', 'Calamba', 'Caloocan', 'Cebu City',
            'Davao City', 'Las Piñas', 'Makati', 'Malabon', 'Manila', 'Marikina', 'Muntinlupa',
            'Parañaque', 'Pasig', 'Quezon City', 'San Jose del Monte', 'Taguig', 'Valenzuela',
            'Zamboanga City'
        ],
        'Poland' => [
            'Białystok', 'Bielsko-Biała', 'Bydgoszcz', 'Bytom', 'Chorzów', 'Częstochowa',
            'Dąbrowa Górnicza', 'Elbląg', 'Gdańsk', 'Gdynia', 'Gliwice', 'Gorzów Wielkopolski',
            'Grudziądz', 'Jastrzębie-Zdrój', 'Jaworzno', 'Jelenia Góra', 'Kalisz', 'Katowice',
            'Kielce', 'Koszalin', 'Kraków', 'Legnica', 'Lublin', 'Łódź', 'Nowy Sącz',
            'Olsztyn', 'Opole', 'Płock', 'Poznań', 'Radom', 'Ruda Śląska', 'Rybnik', 'Rzeszów',
            'Słupsk', 'Sosnowiec', 'Szczecin', 'Tarnów', 'Toruń', 'Tychy', 'Włocławek',
            'Wrocław', 'Zabrze', 'Zielona Góra'
        ],
        'Portugal' => [
            'Braga', 'Coimbra', 'Funchal', 'Lisbon', 'Porto', 'Setúbal', 'Vila Nova de Gaia'
        ],
        'Qatar' => [
            'Abu az Zuluf', 'Al Daayen', 'Al Khor', 'Al Rayyan', 'Al Wakrah', 'Doha', 'Umm Salal'
        ],
        'Romania' => [
            'Alba Iulia', 'Arad', 'Bacău', 'Baia Mare', 'Bârlad', 'Bistrița', 'Botoșani',
            'Brăila', 'Brașov', 'Bucharest', 'Buzău', 'Călărași', 'Câmpina', 'Câmpulung',
            'Cluj-Napoca', 'Codlea', 'Constanța', 'Craiova', 'Deva', 'Drobeta-Turnu Severin',
            'Focșani', 'Galați', 'Giurgiu', 'Hunedoara', 'Iași', 'Lugoj', 'Mangalia',
            'Mediaș', 'Miercurea Ciuc', 'Piatra Neamț', 'Pitești', 'Ploiești', 'Râmnicu Vâlcea',
            'Reșița', 'Roman', 'Satu Mare', 'Sfântu Gheorghe', 'Sibiu', 'Sighetu Marmației',
            'Sighișoara', 'Slatina', 'Târgoviște', 'Târgu Mureș', 'Timișoara', 'Tulcea',
            'Turda', 'Vaslui', 'Zalău'
        ],
        'Russia' => [
            'Arkhangelsk', 'Astrakhan', 'Barnaul', 'Belgorod', 'Bryansk', 'Cheboksary', 'Chelyabinsk',
            'Kazan', 'Kaliningrad', 'Kirov', 'Krasnodar', 'Krasnoyarsk', 'Kurgan', 'Kursk', 'Lipetsk',
            'Makhachkala', 'Magnitogorsk', 'Moscow', 'Murmansk', 'Naberezhnye Chelny', 'Nizhny Novgorod',
            'Nizhny Tagil', 'Novosibirsk', 'Omsk', 'Orël', 'Orenburg', 'Penza', 'Perm', 'Rostov-on-Don',
            'Ryazan', 'Saint Petersburg', 'Samara', 'Saratov', 'Smolensk', 'Sochi', 'Tolyatti', 'Tomsk',
            'Tula', 'Tver', 'Tyumen', 'Ufa', 'Ulyanovsk', 'Vladimir', 'Vladivostok', 'Volgograd',
            'Voronezh', 'Yaroslavl', 'Yekaterinburg'
        ],
        'Saudi Arabia' => [
            'Abha', 'Buraydah', 'Dammam', 'Hofuf', 'Jeddah', 'Jubail', 'Khobar', 'Mecca',
            'Medina', 'Riyadh', 'Tabuk', 'Taif'
        ],
        'Serbia' => [
            'Belgrade', 'Čačak', 'Jagodina', 'Kikinda', 'Kragujevac', 'Kraljevo', 'Kruševac',
            'Niš', 'Novi Sad', 'Pančevo', 'Pirot', 'Požarevac', 'Ruma', 'Šabac',
            'Smederevo', 'Sombor', 'Subotica', 'Užice', 'Valjevo', 'Vranje', 'Zaječar', 'Zrenjanin'
        ],
        'Singapore' => [
            'Ang Mo Kio', 'Bedok', 'Bukit Batok', 'Bukit Merah', 'Bukit Panjang', 'Central Region',
            'Choa Chu Kang', 'Clementi', 'East Region', 'Geylang', 'Hougang', 'Jurong East',
            'Kallang', 'Marine Parade', 'North Region', 'North-East Region', 'Pasir Ris', 'Punggol',
            'Queenstown', 'Sembawang', 'Sengkang', 'Serangoon', 'Singapore', 'Toa Payoh',
            'West Region', 'Woodlands', 'Yishun'
        ],
        'Slovakia' => [
            'Banská Bystrica', 'Bratislava', 'Košice', 'Martin', 'Michalovce', 'Nitra',
            'Nové Zámky', 'Piešťany', 'Poprad', 'Prešov', 'Prievidza', 'Trenčín',
            'Trnava', 'Žilina', 'Zvolen'
        ],
        'Slovenia' => [
            'Ajdovščina', 'Brezice', 'Celje', 'Črnomelj', 'Domžale', 'Dravograd', 'Gornja Radgona',
            'Hrastnik', 'Idrija', 'Ilirska Bistrica', 'Izola', 'Ivančna Gorica', 'Jesenice',
            'Juršinci', 'Kamnik', 'Kanal', 'Kidričevo', 'Kobarid', 'Kobilje', 'Kočevje', 'Komen',
            'Koper', 'Kostanjevica na Krki', 'Kozje', 'Kranj', 'Križevci', 'Krško', 'Kungota',
            'Kuzma', 'Laško', 'Ljubljana', 'Logatec', 'Maribor', 'Murska Sobota', 'Nova Gorica',
            'Novo Mesto', 'Postojna', 'Ptuj', 'Ravne na Koroškem', 'Slovenska Bistrica',
            'Slovenj Gradec', 'Škofja Loka', 'Trbovlje', 'Vrhnika'
        ],
        'South Africa' => [
            'Atteridgeville', 'Benoni', 'Bloemfontein', 'Boksburg', 'Brakpan', 'Bronkhorstspruit',
            'Cape Town', 'Cullinan', 'Durban', 'East London', 'Ga-Rankuwa', 'Germiston',
            'Hammanskraal', 'Johannesburg', 'Kimberley', 'Klerksdorp', 'Mabopane', 'Mamelodi',
            'Nelspruit', 'Pietermaritzburg', 'Polokwane', 'Port Elizabeth', 'Potchefstroom',
            'Pretoria', 'Rayton', 'Roodepoort', 'Rustenburg', 'Soshanguve', 'Soweto', 'Tembisa',
            'Vereeniging', 'Welkom', 'Winterveld'
        ],
        'Spain' => [
            'A Coruña', 'Albacete', 'Alcalá de Henares', 'Algeciras', 'Alicante', 'Almería',
            'Badajoz', 'Badalona', 'Barcelona', 'Bilbao', 'Burgos', 'Cádiz', 'Cartagena',
            'Córdoba', 'Elche', 'Fuenlabrada', 'Getafe', 'Gijón', 'Granada', 'Huelva',
            'Jerez de la Frontera', 'La Coruña', 'Las Palmas', 'Leganés', 'León', 'Lleida',
            'Logroño', 'L\'Hospitalet', 'Madrid', 'Málaga', 'Marbella', 'Mataró', 'Móstoles',
            'Murcia', 'Palma', 'Pamplona', 'San Cristóbal de La Laguna', 'San Sebastián',
            'Sabadell', 'Salamanca', 'Santa Coloma de Gramenet', 'Santa Cruz de Tenerife',
            'Seville', 'Tarragona', 'Terrassa', 'Valencia', 'Valladolid', 'Vigo', 'Vitoria-Gasteiz',
            'Zaragoza'
        ],
        'Sri Lanka' => [
            'Anuradhapura', 'Batticaloa', 'Colombo', 'Galle', 'Jaffna', 'Kandy', 'Kotte',
            'Moratuwa', 'Negombo', 'Trincomalee', 'Vavuniya'
        ],
        'Sudan' => [
            'Atbara', 'Kassala', 'Khartoum', 'Omdurman', 'Port Sudan', 'Wad Madani', 'El Obeid'
        ],
        'Sweden' => [
            'Borås', 'Borlänge', 'Eskilstuna', 'Eslöv', 'Falun', 'Falköping', 'Gävle', 'Gothenburg',
            'Halmstad', 'Helsingborg', 'Hudiksvall', 'Hässleholm', 'Jönköping', 'Kalmar',
            'Karlskrona', 'Karlstad', 'Katrineholm', 'Kristianstad', 'Kungsbacka', 'Kungälv',
            'Landskrona', 'Ljungby', 'Luleå', 'Lund', 'Malmö', 'Mariestad', 'Mölndal', 'Motala',
            'Nässjö', 'Norrköping', 'Nyköping', 'Skövde', 'Stockholm', 'Södertälje', 'Sölvesborg',
            'Sundsvall', 'Trelleborg', 'Trollhättan', 'Uddevalla', 'Umeå', 'Uppsala', 'Varberg',
            'Västervik', 'Västerås', 'Växjö', 'Örebro', 'Örnsköldsvik', 'Östersund', 'Ängelholm'
        ],
        'Switzerland' => [
            'Baden', 'Basel', 'Bern', 'Biel', 'Bülach', 'Carouge', 'Chêne-Bougeries', 'Chur',
            'Cologny', 'Dietikon', 'Dübendorf', 'Emmen', 'Fribourg', 'Geneva', 'Kloten', 'Köniz',
            'Kriens', 'La Chaux-de-Fonds', 'Lancy', 'Lausanne', 'Lucerne', 'Lugano', 'Meyrin',
            'Neuchâtel', 'Onex', 'Plan-les-Ouates', 'Rapperswil-Jona', 'Regensdorf', 'Riehen',
            'Schaffhausen', 'Sion', 'St. Gallen', 'Thônex', 'Thun', 'Uster', 'Vernier', 'Versoix',
            'Wädenswil', 'Winterthur', 'Zollikon', 'Zurich'
        ],
        'Syria' => [
            'Aleppo', 'Damascus', 'Daraa', 'Deir ez-Zor', 'Hama', 'Homs', 'Idlib', 'Latakia',
            'Qamishli', 'Raqqa'
        ],
        'Tajikistan' => [
            'Dushanbe', 'Khujand', 'Khorugh', 'Kulob', 'Qurghonteppa', 'Tursunzoda'
        ],
        'Thailand' => [
            'Bangkok', 'Bang Saen', 'Chanthaburi', 'Chiang Mai', 'Chonburi', 'Hat Yai', 'Kanchanaburi',
            'Khon Kaen', 'Ko Chang', 'Ko Lanta', 'Ko Muk', 'Ko Pha Ngan', 'Ko Phi Phi', 'Ko Samet',
            'Ko Samui', 'Ko Tao', 'Ko Tarutao', 'Lampang', 'Mae Hong Son', 'Nakhon Nayok',
            'Nakhon Pathom', 'Nakhon Ratchasima', 'Nakhon Sawan', 'Nakhon Si Thammarat', 'Nonthaburi',
            'Pattaya', 'Phetchaburi', 'Phitsanulok', 'Prachinburi', 'Prachuap Khiri Khan', 'Phuket',
            'Ratchaburi', 'Rayong', 'Roi Et', 'Sa Kaeo', 'Samut Prakan', 'Samut Sakhon',
            'Samut Songkhram', 'Saraburi', 'Si Racha', 'Sukhothai', 'Surat Thani', 'Tak',
            'Trat', 'Ubon Ratchathani', 'Udon Thani'
        ],
        'Timor Leste' => [
            'Aileu', 'Baucau', 'Dili', 'Ermera', 'Liquiçá', 'Maubara', 'Suai', 'Viqueque'
        ],
        'Tunisia' => [
            'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Carthage', 'Djerba',
            'Gabès', 'Gafsa', 'Hammamet', 'Kairouan', 'Kasserine', 'Kébili', 'La Marsa',
            'Mahdia', 'Manouba', 'Medenine', 'Monastir', 'Nabeul', 'Radès', 'Sfax',
            'Sidi Bou Said', 'Siliana', 'Sousse', 'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan'
        ],
        'Turkey' => [
            'Adana', 'Ankara', 'Antalya', 'Bursa', 'Diyarbakır', 'Gaziantep', 'Istanbul',
            'İzmir', 'Kayseri', 'Konya', 'Mersin', 'Şanlıurfa'
        ],
        'Turkmenistan' => [
            'Ashgabat', 'Balkanabat', 'Dashoguz', 'Mary', 'Turkmenabat', 'Turkmenbashi'
        ],
        'Ukraine' => [
            'Bakhmut', 'Berdiansk', 'Bila Tserkva', 'Cherkasy', 'Chernihiv', 'Chernivtsi', 'Dnipro',
            'Donetsk', 'Ivano-Frankivsk', 'Kharkiv', 'Kherson', 'Khmelnytskyi', 'Konotop',
            'Kostiantynivka', 'Kramatorsk', 'Kremenchuk', 'Kryvyi Rih', 'Kyiv', 'Luhansk', 'Lutsk',
            'Lviv', 'Mariupol', 'Melitopol', 'Mykolaiv', 'Nizhyn', 'Odesa', 'Poltava', 'Rivne',
            'Shostka', 'Simferopol', 'Sumy', 'Ternopil', 'Uzhhorod', 'Vinnytsia', 'Zaporizhzhia',
            'Zhytomyr'
        ],
        'Uni Emirat Arab' => [
            'Abu Dhabi', 'Ajman', 'Al Ain', 'Dubai', 'Fujairah', 'Ras Al Khaimah', 'Sharjah',
            'Umm Al Quwain'
        ],
        'United Kingdom' => [
            'Belfast', 'Birmingham', 'Blackburn', 'Blackpool', 'Bolton', 'Bournemouth', 'Brighton',
            'Bristol', 'Burnley', 'Cambridge', 'Cardiff', 'Colchester', 'Coventry', 'Derby',
            'Doncaster', 'Edinburgh', 'Exeter', 'Glasgow', 'Gloucester', 'Hull', 'Ipswich', 'Leeds',
            'Leicester', 'Liverpool', 'London', 'Luton', 'Maidstone', 'Manchester', 'Mansfield',
            'Middlesbrough', 'Newcastle', 'Northampton', 'Nottingham', 'Norwich', 'Oxford',
            'Peterborough', 'Plymouth', 'Portsmouth', 'Reading', 'Sheffield', 'Southampton',
            'Southend-on-Sea', 'Stoke-on-Trent', 'Sunderland', 'Swansea', 'Swindon', 'Telford',
            'Walsall', 'Wolverhampton', 'York'
        ],
        'United States' => [
            'Albuquerque', 'Arlington', 'Atlanta', 'Austin', 'Baltimore', 'Boston', 'Charlotte',
            'Chicago', 'Colorado Springs', 'Columbus', 'Dallas', 'Denver', 'Detroit', 'El Paso',
            'Fort Worth', 'Fresno', 'Houston', 'Indianapolis', 'Jacksonville', 'Kansas City',
            'Las Vegas', 'Long Beach', 'Los Angeles', 'Louisville', 'Memphis', 'Mesa',
            'Miami', 'Milwaukee', 'Minneapolis', 'Nashville', 'New Orleans', 'New York', 'Oakland',
            'Oklahoma City', 'Omaha', 'Philadelphia', 'Phoenix', 'Portland', 'Raleigh',
            'Sacramento', 'San Antonio', 'San Diego', 'San Francisco', 'San Jose', 'Seattle',
            'Tampa', 'Tucson', 'Tulsa', 'Virginia Beach', 'Washington'
        ],
        'Uzbekistan' => [
            'Andijan', 'Bukhara', 'Fergana', 'Namangan', 'Nukus', 'Qarshi', 'Samarqand',
            'Tashkent', 'Termez', 'Urgench'
        ],
        'Vatican' => [
            'Vatican City'
        ],
        'Venezuela' => [
            'Barquisimeto', 'Caracas', 'Ciudad Bolívar', 'Maracaibo', 'Maracay', 'Valencia',
            'Maturín', 'Petare', 'Barcelona'
        ],
        'Vietnam' => [
            'Bien Hoa', 'Can Tho', 'Da Nang', 'Haiphong', 'Hanoi', 'Ho Chi Minh City',
            'Hue', 'Nha Trang', 'Phu Quoc', 'Qui Nhon', 'Vinh'
        ],
        'Yaman' => [
            'Aden', 'Al Hudaydah', 'Ibb', 'Mukalla', 'Sana\'a', 'Ta\'izz'
        ],
        'Yordania' => [
            'Ajloun', 'Amman', 'Aqaba', 'Irbid', 'Kerak', 'Madaba', 'Salt', 'Zarqa'
        ],
        'Yunani' => [
            'Athens', 'Corfu', 'Heraklion', 'Ioannina', 'Kalamata', 'Larissa', 'Patras',
            'Piraeus', 'Rhodes', 'Thessaloniki', 'Volos'
        ],
        'Zambia' => [
            'Chipata', 'Kabwe', 'Livingstone', 'Lusaka', 'Ndola', 'Kitwe', 'Chingola'
        ],
        'Zimbabwe' => [
            'Bulawayo', 'Chitungwiza', 'Gweru', 'Harare', 'Mutare', 'Kwekwe', 'Masvingo'
        ]
    ];

    public $isEditing = false;

    // Autocomplete suggestions
    public $companySuggestions = [];
    public $positionSuggestions = [];
    public $showCompanySuggestions = false;
    public $showPositionSuggestions = false;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'selectedProvince' => 'nullable|string|max:255',
        'platform' => 'required|string|max:255',
        'platformOther' => 'required_if:platform,Other|string|max:255',
        // Remove old status from validation; application_status replaces it
        'application_status' => 'required|string|in:On Process,Declined,Accepted',
        'recruitment_stage' => 'required|string|in:Applied,Follow Up,Assessment Test,Psychotest,HR - Interview,User - Interview,LGD,Presentation Round,Offering,Not Processed',
        'career_level' => 'required|string|in:Intern,Full Time,Contract,MT,Freelance',
        'platform_link' => 'nullable|url',
        'application_date' => 'required|date',
        'notes' => 'nullable|string',
        // Interview fields (conditional validation)
        'interview_date' => 'nullable|date',
        'interview_type' => 'nullable|string|in:Phone,Video,In-person,Panel',
        'interview_location' => 'nullable|string|max:500',
        'interview_notes' => 'nullable|string',
    ];

    protected $listeners = [
        'edit-job' => 'editJob',
        'editJob' => 'editJob',
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
        'resetFormForNewJob' => 'resetFormForNewJob',
        'clearEditJobSession' => 'clearEditJobSession',
    ];

    public function updatedCompanyName($value)
    {
        if (strlen($value) >= 2) {
            $this->companySuggestions = JobApplication::where('user_id', Auth::id())
                ->where('company_name', 'like', '%' . $value . '%')
                ->distinct()
                ->pluck('company_name')
                ->take(5)
                ->toArray();
            $this->showCompanySuggestions = true;
        } else {
            $this->showCompanySuggestions = false;
            $this->companySuggestions = [];
        }
    }

    public function updatedPosition($value)
    {
        if (strlen($value) >= 2) {
            $this->positionSuggestions = JobApplication::where('user_id', Auth::id())
                ->where('position', 'like', '%' . $value . '%')
                ->distinct()
                ->pluck('position')
                ->take(5)
                ->toArray();
            $this->showPositionSuggestions = true;
        } else {
            $this->showPositionSuggestions = false;
            $this->positionSuggestions = [];
        }
    }


    public function selectCompanySuggestion($company)
    {
        $this->company_name = $company;
        $this->showCompanySuggestions = false;
        $this->companySuggestions = [];
    }

    public function selectPositionSuggestion($position)
    {
        $this->position = $position;
        $this->showPositionSuggestions = false;
        $this->positionSuggestions = [];
    }

    public function hideSuggestions()
    {
        $this->showCompanySuggestions = false;
        $this->showPositionSuggestions = false;
    }

    public function selectPlatformSuggestion($platform)
    {
        $this->platform = $platform;
        $this->platformOther = '';
    }

    /**
     * Get most used platforms for quick suggestions
     */
    public function getMostUsedPlatformsProperty()
    {
        $userPlatforms = JobApplication::where('user_id', Auth::id())
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->orderBy('platform', 'asc') // Secondary sort by name for consistency
            ->limit(5)
            ->get()
            ->pluck('platform')
            ->toArray();
        
        // If user has no platforms yet, show common platforms
        if (empty($userPlatforms)) {
            return ['JobStreet', 'LinkedIn', 'Glints', 'Kalibrr', 'Karir.com'];
        }
        
        return $userPlatforms;
    }
    
    public function getListeners()
    {
        return [
            'edit-job' => 'editJob',
            'editJob' => 'editJob'
        ];
    }

    public function mount($jobApplication = null)
    {
        if ($jobApplication) {
            $this->isEditing = true;
            $this->jobApplication = $jobApplication;
            $this->company_name = $jobApplication->company_name;
            $this->position = $jobApplication->position;
            $this->location = $jobApplication->location;
            
            // Parse existing location to set province and city
            $this->parseLocation($jobApplication->location);
            
            // Check if platform is in the predefined list
            if (in_array($jobApplication->platform, $this->platformOptions)) {
                $this->platform = $jobApplication->platform;
                $this->platformOther = '';
            } else {
                $this->platform = 'Other';
                $this->platformOther = $jobApplication->platform;
            }
            
            // Keep legacy status loaded but unused
            $this->status = $jobApplication->status;
            $this->application_status = $jobApplication->application_status ?? 'On Process';
            $this->recruitment_stage = $jobApplication->recruitment_stage ?? 'Applied';
            $this->career_level = $jobApplication->career_level ?? 'Full Time';
            $this->platform_link = $jobApplication->platform_link;
            $this->application_date = $jobApplication->application_date->format('Y-m-d');
            $this->notes = $jobApplication->notes;
        } else {
            // Reset form for new job
            $this->resetForm();
        }
    }

    public function editJob($jobId)
    {
        Log::info('editJob called with jobId:', ['jobId' => $jobId, 'type' => gettype($jobId), 'stack' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)]);
        
        // Prevent multiple calls for the same job
        if ($this->isEditing && $this->jobApplication && $this->jobApplication->id == $jobId) {
            Log::info('Edit job already loaded, skipping...', ['jobId' => $jobId]);
            return;
        }
        
        // Ensure jobId is an integer
        $jobId = (int) $jobId;
        
        // Store job ID in session to trigger component re-render
        session(['edit-job-id' => $jobId]);
        
        Log::info('Searching for job with ID:', ['jobId' => $jobId, 'userId' => Auth::id()]);
        
        $jobApplication = JobApplication::where('id', $jobId)
            ->where('user_id', Auth::id())
            ->first();

        if ($jobApplication) {
            Log::info('Job found, loading data...', ['jobId' => $jobId, 'company' => $jobApplication->company_name]);
            
            $this->isEditing = true;
            $this->jobApplication = $jobApplication;
            $this->company_name = $jobApplication->company_name;
            $this->position = $jobApplication->position;
            $this->location = $jobApplication->location;
            
            // Parse existing location to set province and city
            $this->parseLocation($jobApplication->location);
            
            // Check if platform is in the predefined list
            if (in_array($jobApplication->platform, $this->platformOptions)) {
                $this->platform = $jobApplication->platform;
                $this->platformOther = '';
            } else {
                $this->platform = 'Other';
                $this->platformOther = $jobApplication->platform;
            }
            
            // Keep legacy status loaded but unused
            $this->status = $jobApplication->status;
            $this->application_status = $jobApplication->application_status ?? 'On Process';
            $this->recruitment_stage = $jobApplication->recruitment_stage ?? 'Applied';
            $this->career_level = $jobApplication->career_level ?? 'Full Time';
            $this->platform_link = $jobApplication->platform_link;
            $this->application_date = $jobApplication->application_date->format('Y-m-d');
            $this->notes = $jobApplication->notes;
            
            // Load interview details
            $this->interview_date = $jobApplication->interview_date ? $jobApplication->interview_date->format('Y-m-d\TH:i') : '';
            $this->interview_type = $jobApplication->interview_type ?? '';
            $this->interview_location = $jobApplication->interview_location ?? '';
            $this->interview_notes = $jobApplication->interview_notes ?? '';
            
            Log::info('Job data loaded successfully', [
                'company_name' => $this->company_name,
                'position' => $this->position,
                'status' => $this->status,
                'application_status' => $this->application_status,
                'recruitment_stage' => $this->recruitment_stage,
                'career_level' => $this->career_level,
                'selectedProvince' => $this->selectedProvince,
                'selectedCity' => $this->selectedCity,
                'cities_count' => count($this->cities)
            ]);
            
            // Force re-render
            $this->dispatch('$refresh');
            
            // Also try to emit a custom event to refresh the form
            $this->dispatch('job-loaded', [
                'company_name' => $this->company_name,
                'position' => $this->position,
                'status' => $this->status
            ]);
        } else {
            Log::warning('Job not found or not authorized', ['jobId' => $jobId, 'userId' => Auth::id()]);
        }
    }

    public function save()
    {
        Log::info('Save method called', [
            'isEditing' => $this->isEditing,
            'company_name' => $this->company_name,
            'position' => $this->position,
            'platform' => $this->platform,
            'status' => $this->status,
            'selectedProvince' => $this->selectedProvince,
            'selectedCity' => $this->selectedCity,
            'location' => $this->location
        ]);
        
        
        // Ensure location is set before validation
        $this->updateLocation();
        
        // Custom validation for location
        if (!$this->isRemote && !$this->isSeluruhIndonesia && !$this->isInternational && empty($this->selectedProvince)) {
            $this->addError('selectedProvince', 'The selected province field is required.');
            $this->dispatch('showNotification', 
                type: 'error',
                title: 'Validation Error',
                message: 'Please select a province for the job location',
                duration: 4000
            );
            return;
        }
        
        if ($this->isInternational && empty($this->selectedCountry)) {
            $this->addError('selectedCountry', 'The selected country field is required.');
            $this->dispatch('showNotification', 
                type: 'error',
                title: 'Validation Error',
                message: 'Please select a country for international location',
                duration: 4000
            );
            return;
        }
        
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showNotification', 
                type: 'error',
                title: 'Validation Error',
                message: 'Please check all required fields and try again',
                duration: 4000
            );
            throw $e;
        }

        $data = [
            'user_id' => Auth::id(),
            'company_name' => $this->company_name,
            'position' => $this->position,
            'location' => $this->location,
            'platform' => $this->platform === 'Other' ? $this->platformOther : $this->platform,
            // Retain legacy status for backward compatibility; field remains in DB
            'status' => $this->status,
            'application_status' => $this->application_status,
            'recruitment_stage' => $this->recruitment_stage,
            'career_level' => $this->career_level,
            'platform_link' => $this->platform_link,
            'application_date' => $this->application_date,
            'notes' => $this->notes,
            // Interview details (only save if recruitment_stage is HR or User Interview)
            'interview_date' => (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview'])) ? $this->interview_date : null,
            'interview_type' => (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview'])) ? $this->interview_type : null,
            'interview_location' => (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview'])) ? $this->interview_location : null,
            'interview_notes' => (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview'])) ? $this->interview_notes : null,
        ];

        try {
            if ($this->isEditing) {
                $this->jobApplication->update($data);
                session()->flash('message', 'Job application updated successfully!');
                Log::info('Job updated successfully', ['jobId' => $this->jobApplication->id]);
                
                // Send notification for job update
                $this->dispatch('showNotification', 
                    type: 'success',
                    title: 'Job Application Updated',
                    message: "Successfully updated application for {$this->company_name}",
                    duration: 3000
                );
                
                // Dispatch interview-updated event for calendar refresh
                if (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview']) && $this->interview_date) {
                    $this->dispatch('interview-updated');
                }
            } else {
                $newJob = JobApplication::create($data);
                session()->flash('message', 'Job application created successfully!');
                Log::info('Job created successfully', ['jobId' => $newJob->id]);
                
                // Send notification for new job
                $this->dispatch('showNotification', 
                    type: 'success',
                    title: 'Job Application Added',
                    message: "Successfully added application for {$this->company_name}",
                    duration: 3000
                );
                
                // Dispatch interview-updated event for calendar refresh
                if (in_array($this->recruitment_stage, ['HR - Interview', 'User - Interview']) && $this->interview_date) {
                    $this->dispatch('interview-updated');
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to save job application', ['error' => $e->getMessage()]);
            
            $this->dispatch('showNotification', 
                type: 'error',
                title: 'Save Failed',
                message: 'Failed to save job application. Please try again.',
                duration: 5000
            );
            
            return;
        }

        // Dispatch global events for auto-refresh
        $this->dispatch('job-saved');
        
        // Reset form and close modal
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function updatedSelectedProvince()
    {
        Log::info('updatedSelectedProvince called', [
            'selectedProvince' => $this->selectedProvince, 
            'isEditing' => $this->isEditing
        ]);
        
        // Clear selected city when province changes
        $this->selectedCity = '';
        Log::info('Selected city cleared due to province change', ['selectedCity' => $this->selectedCity]);
        
        // Uncheck remote, seluruh indonesia, and international when province is selected
        $this->isRemote = false;
        $this->isSeluruhIndonesia = false;
        $this->isInternational = false;
        $this->selectedCountry = '';
        $this->selectedInternationalCity = '';
        $this->internationalCities = [];
        
        $this->updateLocation();
    }

    public function updatedSelectedCity()
    {
        Log::info('updatedSelectedCity called', ['selectedCity' => $this->selectedCity]);
        
        // Uncheck remote, seluruh indonesia, and international when city is selected
        $this->isRemote = false;
        $this->isSeluruhIndonesia = false;
        $this->isInternational = false;
        $this->selectedCountry = '';
        $this->selectedInternationalCity = '';
        $this->internationalCities = [];
        
        $this->updateLocation();
    }

    public function updateLocation()
    {
        if ($this->isRemote) {
            $this->location = 'Remote';
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        } elseif ($this->isSeluruhIndonesia) {
            $this->location = 'Seluruh Indonesia';
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        } elseif ($this->isInternational) {
            if ($this->selectedCountry && $this->selectedInternationalCity) {
                $this->location = $this->selectedInternationalCity . ', ' . $this->selectedCountry;
            } elseif ($this->selectedCountry) {
                $this->location = $this->selectedCountry;
            } else {
                $this->location = 'International';
            }
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        } elseif ($this->selectedProvince && $this->selectedCity) {
            $this->location = $this->selectedCity . ', ' . $this->selectedProvince;
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        } elseif ($this->selectedProvince) {
            $this->location = $this->selectedProvince;
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        } else {
            $this->location = '';
        }
        
        Log::info('Location updated', [
            'isRemote' => $this->isRemote,
            'isSeluruhIndonesia' => $this->isSeluruhIndonesia,
            'selectedProvince' => $this->selectedProvince,
            'selectedCity' => $this->selectedCity,
            'location' => $this->location
        ]);
    }

    public function updatedIsRemote()
    {
        if ($this->isRemote) {
            $this->isSeluruhIndonesia = false;
            $this->isInternational = false;
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        }
        $this->updateLocation();
    }

    public function updatedIsSeluruhIndonesia()
    {
        if ($this->isSeluruhIndonesia) {
            $this->isRemote = false;
            $this->isInternational = false;
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
            $this->selectedCountry = '';
            $this->selectedInternationalCity = '';
            $this->internationalCities = [];
        }
        $this->updateLocation();
    }

    public function updatedIsInternational()
    {
        if ($this->isInternational) {
            $this->isRemote = false;
            $this->isSeluruhIndonesia = false;
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        }
        $this->updateLocation();
    }

    public function updatedSelectedCountry()
    {
        $this->selectedInternationalCity = '';
        if ($this->selectedCountry && isset($this->countries[$this->selectedCountry])) {
            $this->internationalCities = $this->countries[$this->selectedCountry];
        } else {
            $this->internationalCities = [];
        }
        $this->updateLocation();
    }

    public function updatedSelectedInternationalCity()
    {
        $this->updateLocation();
    }

    public function parseLocation($location)
    {
        Log::info('Parsing location:', ['location' => $location]);
        
        // Reset values
        $this->selectedProvince = '';
        $this->selectedCity = '';
        $this->cities = [];
        $this->isRemote = false;
        $this->isSeluruhIndonesia = false;
        $this->isInternational = false;
        $this->selectedCountry = '';
        $this->selectedInternationalCity = '';
        $this->internationalCities = [];
        
        if (empty($location)) {
            return;
        }
        
        // Check for special cases first
        if ($location === 'Remote') {
            $this->isRemote = true;
            Log::info('Location is Remote');
            return;
        }
        
        if ($location === 'Seluruh Indonesia') {
            $this->isSeluruhIndonesia = true;
            Log::info('Location is Seluruh Indonesia');
            return;
        }
        
        // Try to parse "City, Province" format
        if (strpos($location, ', ') !== false) {
            $parts = explode(', ', $location);
            $city = trim($parts[0]);
            $province = trim($parts[1]);
            
            Log::info('Parsed location parts:', ['city' => $city, 'province' => $province]);
            
            // Check if province exists in our data
            if (isset($this->provinces[$province])) {
                $this->selectedProvince = $province;
                $this->cities = $this->provinces[$province];
                
                Log::info('Province found, cities loaded:', ['cities_count' => count($this->cities)]);
                
                // Check if city exists in the province
                if (in_array($city, $this->cities)) {
                    $this->selectedCity = $city;
                    Log::info('City found and selected:', ['selectedCity' => $city]);
                } else {
                    Log::warning('City not found in province cities:', ['city' => $city, 'available_cities' => $this->cities]);
                }
            } else {
                Log::warning('Province not found:', ['province' => $province, 'available_provinces' => array_keys($this->provinces)]);
            }
        } else {
            // Check if it's just a province
            if (isset($this->provinces[$location])) {
                $this->selectedProvince = $location;
                $this->cities = $this->provinces[$location];
                Log::info('Location is a province:', ['province' => $location]);
            } else {
                Log::warning('Location not recognized:', ['location' => $location]);
            }
        }
        
        Log::info('Final parsed values:', [
            'selectedProvince' => $this->selectedProvince,
            'selectedCity' => $this->selectedCity,
            'cities_count' => count($this->cities)
        ]);
    }

    public function clearEditJobSession()
    {
        Log::info('Clearing edit job session');
        session()->forget('edit-job-id');
    }

    public function resetFormForNewJob()
    {
        Log::info('Resetting form for new job', [
            'current_isEditing' => $this->isEditing,
            'current_jobApplication_id' => $this->jobApplication ? $this->jobApplication->id : null,
            'current_company_name' => $this->company_name
        ]);
        $this->resetForm();
        Log::info('Form reset completed', [
            'new_isEditing' => $this->isEditing,
            'new_jobApplication' => $this->jobApplication,
            'new_company_name' => $this->company_name
        ]);
        
        // Force component refresh to update the UI
        $this->dispatch('$refresh');
    }

    public function resetForm()
    {
        $this->company_name = '';
        $this->position = '';
        $this->location = '';
        $this->selectedProvince = '';
        $this->selectedCity = '';
        $this->cities = [];
        $this->isRemote = false;
        $this->isSeluruhIndonesia = false;
        $this->isInternational = false;
        $this->selectedCountry = '';
        $this->selectedInternationalCity = '';
        $this->internationalCities = [];
        $this->platform = '';
        $this->platformOther = '';
        // Reset legacy status though not used in UI
        $this->status = 'Applied';
        $this->application_status = '';
        $this->recruitment_stage = '';
        $this->career_level = '';
        $this->platform_link = '';
        $this->application_date = now('Asia/Jakarta')->format('Y-m-d'); // Use WIB timezone
        $this->notes = '';
        $this->isEditing = false;
        $this->jobApplication = null;
    }

    public function render()
    {
        return view('livewire.job-application-form');
    }
}
