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
    public $isRemote = false;
    public $isSeluruhIndonesia = false;
    public $platform = 'JobStreet';
    public $platformOther = '';
    // Deprecated legacy status (removed from UI); keep for backward compatibility when loading old records
    public $status = 'Applied';
    public $application_status = 'On Process';
    public $recruitment_stage = 'Applied';
    public $career_level = 'Full Time';
    public $platform_link = '';
    public $application_date = '';
    public $notes = '';

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
        'Aceh' => ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Meulaboh', 'Sabang', 'Subulussalam'],
        'Bali' => ['Badung', 'Bangli', 'Buleleng', 'Denpasar', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
        'Banten' => ['Cilegon', 'Lebak', 'Pandeglang', 'Serang', 'Tangerang', 'Tangerang Selatan'],
        'Bengkulu' => ['Bengkulu', 'Curup', 'Kepahiang', 'Manna', 'Mukomuko', 'Tais'],
        'DI Yogyakarta' => ['Bantul', 'Gunung Kidul', 'Kulon Progo', 'Sleman', 'Yogyakarta'],
        'DKI Jakarta' => ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 'Kepulauan Seribu'],
        'Gorontalo' => ['Gorontalo', 'Kwandang', 'Limboto', 'Marisa', 'Pohuwato', 'Suwawa', 'Tilamuta'],
        'Jambi' => ['Bungo', 'Jambi', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Sungai Penuh', 'Tebo'],
        'Jawa Barat' => ['Banjar', 'Bandung', 'Bekasi', 'Bogor', 'Ciamis', 'Cimahi', 'Cirebon', 'Depok', 'Karawang', 'Kuningan', 'Majalengka', 'Purwakarta', 'Subang', 'Sukabumi', 'Sumedang', 'Tasikmalaya'],
        'Jawa Tengah' => ['Boyolali', 'Cilacap', 'Klaten', 'Karanganyar', 'Magelang', 'Pekalongan', 'Pemalang', 'Purwokerto', 'Salatiga', 'Semarang', 'Sragen', 'Sukoharjo', 'Surakarta', 'Tegal', 'Wonogiri'],
        'Jawa Timur' => ['Blitar', 'Jember', 'Kediri', 'Lumajang', 'Madiun', 'Magetan', 'Malang', 'Mojokerto', 'Pacitan', 'Pasuruan', 'Ponorogo', 'Probolinggo', 'Surabaya', 'Trenggalek', 'Tulungagung'],
        'Kalimantan Barat' => ['Bengkayang', 'Kapuas Hulu', 'Ketapang', 'Landak', 'Pontianak', 'Sanggau', 'Singkawang', 'Sintang'],
        'Kalimantan Selatan' => ['Amuntai', 'Banjarbaru', 'Banjarmasin', 'Barabai', 'Kandangan', 'Martapura', 'Pelaihari', 'Rantau'],
        'Kalimantan Tengah' => ['Barito Selatan', 'Barito Utara', 'Kapuas', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Palangka Raya', 'Pangkalan Bun', 'Sampit'],
        'Kalimantan Timur' => ['Balikpapan', 'Bontang', 'Samarinda', 'Sangatta', 'Tenggarong'],
        'Kalimantan Utara' => ['Malinau', 'Nunukan', 'Tana Tidung', 'Tarakan'],
        'Kepulauan Riau' => ['Batam', 'Dabo Singkep', 'Ranai', 'Tanjung Balai Karimun', 'Tanjung Uban', 'Tanjungpinang'],
        'Lampung' => ['Bandar Lampung', 'Gedong Tataan', 'Kalianda', 'Kotabumi', 'Liwa', 'Metro', 'Pringsewu', 'Sukadana'],
        'Maluku' => ['Ambon', 'Bula', 'Dobo', 'Masohi', 'Namlea', 'Piru', 'Saumlaki', 'Tual'],
        'Maluku Utara' => ['Jailolo', 'Maba', 'Sanana', 'Sofifi', 'Ternate', 'Tidore', 'Tobelo', 'Weda'],
        'Nusa Tenggara Barat' => ['Bima', 'Gerung', 'Lembar', 'Mataram', 'Praya', 'Selong', 'Sumbawa Besar', 'Taliwang'],
        'Nusa Tenggara Timur' => ['Atambua', 'Bajawa', 'Ende', 'Kupang', 'Larantuka', 'Maumere', 'Ruteng', 'Waingapu'],
        'Papua' => ['Biak Numfor', 'Jayapura', 'Kepulauan Yapen', 'Keerom', 'Sarmi', 'Supiori', 'Waropen', 'Yapen'],
        'Papua Barat' => ['Bintuni', 'Fakfak', 'Kaimana', 'Manokwari', 'Ransiki', 'Sorong', 'Teminabuan'],
        'Papua Barat Daya' => ['Manokwari Selatan', 'Maybrat', 'Pegunungan Arfak', 'Sorong', 'Sorong Selatan', 'Tambrauw', 'Teluk Bintuni', 'Teluk Wondama'],
        'Papua Pegunungan' => ['Jayawijaya', 'Lanny Jaya', 'Mamberamo Tengah', 'Nduga', 'Puncak', 'Tolikara', 'Wamena', 'Yalimo'],
        'Papua Selatan' => ['Asmat', 'Boven Digoel', 'Mappi', 'Merauke', 'Pegunungan Bintang', 'Yahukimo'],
        'Papua Tengah' => ['Deiyai', 'Dogiyai', 'Intan Jaya', 'Mimika', 'Nabire', 'Paniai', 'Puncak Jaya', 'Timika'],
        'Riau' => ['Bagansiapiapi', 'Bengkalis', 'Dumai', 'Pangkalan Kerinci', 'Pekanbaru', 'Rengat', 'Siak Sri Indrapura', 'Tembilahan'],
        'Sulawesi Barat' => ['Mamasa', 'Mamuju', 'Mamuju Tengah', 'Mamuju Utara', 'Majene', 'Pasangkayu', 'Polewali'],
        'Sulawesi Selatan' => ['Bantaeng', 'Jeneponto', 'Makassar', 'Palopo', 'Parepare', 'Sengkang', 'Takalar', 'Watampone'],
        'Sulawesi Tengah' => ['Ampana', 'Buol', 'Donggala', 'Luwuk', 'Palu', 'Parigi', 'Poso', 'Toli-Toli'],
        'Sulawesi Tenggara' => ['Bau-Bau', 'Kendari', 'Kolaka', 'Lasusua', 'Raha', 'Unaaha', 'Wanggudu'],
        'Sulawesi Utara' => ['Airmadidi', 'Amurang', 'Bitung', 'Kotamobagu', 'Manado', 'Tahuna', 'Tondano', 'Tomohon'],
        'Sumatera Barat' => ['Bukittinggi', 'Padang', 'Padang Panjang', 'Pariaman', 'Payakumbuh', 'Sawahlunto', 'Solok'],
        'Sumatera Selatan' => ['Baturaja', 'Indralaya', 'Kayu Agung', 'Lubuklinggau', 'Martapura', 'Pagar Alam', 'Palembang', 'Prabumulih'],
        'Sumatera Utara' => ['Binjai', 'Gunungsitoli', 'Medan', 'Padangsidimpuan', 'Pematangsiantar', 'Sibolga', 'Tanjungbalai', 'Tebing Tinggi']
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
        if (!$this->isRemote && !$this->isSeluruhIndonesia && empty($this->selectedProvince)) {
            $this->addError('selectedProvince', 'The selected province field is required.');
            $this->dispatch('showNotification', 
                type: 'error',
                title: 'Validation Error',
                message: 'Please select a province for the job location',
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
        
        $this->updateLocation();
    }

    public function updatedSelectedCity()
    {
        Log::info('updatedSelectedCity called', ['selectedCity' => $this->selectedCity]);
        $this->updateLocation();
    }

    public function updateLocation()
    {
        if ($this->isRemote) {
            $this->location = 'Remote';
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        } elseif ($this->isSeluruhIndonesia) {
            $this->location = 'Seluruh Indonesia';
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        } elseif ($this->selectedProvince && $this->selectedCity) {
            $this->location = $this->selectedCity . ', ' . $this->selectedProvince;
        } elseif ($this->selectedProvince) {
            $this->location = $this->selectedProvince;
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
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        }
        $this->updateLocation();
    }

    public function updatedIsSeluruhIndonesia()
    {
        if ($this->isSeluruhIndonesia) {
            $this->isRemote = false;
            $this->selectedProvince = '';
            $this->selectedCity = '';
            $this->cities = [];
        }
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
        $this->platform = 'JobStreet';
        $this->platformOther = '';
        // Reset legacy status though not used in UI
        $this->status = 'Applied';
        $this->application_status = 'On Process';
        $this->recruitment_stage = 'Applied';
        $this->career_level = 'Full Time';
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
